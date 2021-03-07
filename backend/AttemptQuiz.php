<?php

    session_start();

    if(!isset($_SESSION['uid']))
    {
        header("Location: http://{$_SERVER['SERVER_NAME']}/QuizKeeper/index.php");
        die();
    }

    require_once '../QuestionsRender.php';

    $mandatory=explode(",","quiz_key,quiz_password");
    
    $allareset = TRUE;

    foreach($mandatory as $i)
    {
        $allareset &= isset($_POST[$i]);
    }

    if($allareset==FALSE)
    {
        header("Location: http://{$_SERVER['SERVER_NAME']}/QuizKeeper/attemptQuiz.php");
        die();
    }

    require 'connector.php';

    $quiz_key = $_POST['quiz_key'];
    $quiz_password = $_POST['quiz_password'];
    

    $stmt = $conn->prepare("select * from Quiz where quizkey=? and password=?");
    $stmt->bind_param('ss',$quiz_key,$quiz_password);

    $qid = -1;
    $quiz_title = '';
    $quiz_desc = '';
    $quiz_from_date = '';
    $quiz_to_date = '';
    $quiz_shuffle = -1;


    if($stmt->execute() === TRUE)
    {
        $result = $stmt->get_result();

        while ($row = $result->fetch_assoc()) {
            $qid = $row['qid'];
            $quiz_title = $row['title'];
            $quiz_desc = $row['description'];
            $quiz_from_date = $row['fromdate'];
            $quiz_to_date = $row['todate'];
            $quiz_shuffle = $row['shuffle'];
        }
    }
    else
    {
        header("Location: http://{$_SERVER['SERVER_NAME']}/QuizKeeper/attemptQuiz.php");
        die();
    }

    if($qid==-1)
    {
        header("Location: http://{$_SERVER['SERVER_NAME']}/QuizKeeper/attemptQuiz.php");
        die();
    }

    function getQuestionTableFromInt($s){
        $a = array(0=>array("TextQns","tqid"), 1=>array("TextQns","tqid"), 2=>array("CheckboxQns","cbqid"), 3=> array("MCQ","mid"));
        return $a[$s];
    }

    function getQuestionTypeInt($s){
        $a = array(3=>"radio",2=>"checkbox",1=>"loose_text",0=>"strict_text");
        return $a[$s];
    }

    $stmt = $conn->prepare("Select * from Questions where qid=?");
    $stmt->bind_param('i',$qid);

    
    $questions = array();
    
    // {
    //     mark:
    //     xid:
    //     type:
    //     qns:
    //     options:
    // }

    if($stmt->execute()===TRUE)
    {
        $result = $stmt->get_result();

        while($row = $result->fetch_assoc())
        {
            $singlequestion = array();

            $singlequestion['mark'] = $row['marks'];
            $singlequestion['xid'] = $row['xid'];
            $singlequestion['type'] = getQuestionTypeInt($row['type']);
            $singlequestion['qnsid'] = $row['qnsid'];

            $q_table = getQuestionTableFromInt($row['type']);

            $query = "SELECT * FROM " . $q_table[0] . " WHERE " . $q_table[1] . " = ?";
            $qt_stmt = $conn->prepare($query);
            $qt_stmt->bind_param("i", $row['xid']);

            if($qt_stmt->execute()===TRUE)
            {
                $qt_result = $qt_stmt->get_result();
                while($qt_row = $qt_result->fetch_assoc())
                {
                    $singlequestion['question']=$qt_row['qns'];
                    if($singlequestion['type']=='radio' || $singlequestion['type']=='checkbox')
                    {
                        $singlequestion['options']=explode(',',$qt_row['options']);
                        if($quiz_shuffle==1)
                        {
                            shuffle($singlequestion['options']);
                        }
                    }
                }
            }
            else
            {
                header("Location: http://{$_SERVER['SERVER_NAME']}/QuizKeeper/attemptQuiz.php");
                die();
            }

            array_push($questions,$singlequestion);

        }
    }
    else
    {
        header("Location: http://{$_SERVER['SERVER_NAME']}/QuizKeeper/attemptQuiz.php");
        die();
    }

    // here we have the array called $questions which contains all the questions
    // {
    //     mark:,
    //     xid:,
    //     type:,
    //     qns:,
    //     options:, // only if radio or checkbox
    // }

    if($quiz_shuffle==1)
    {
        shuffle($questions);
    }

    $start = 1;

    foreach($questions as $question)
    {
        if($question['type']=='radio')
        {
            renderRadioQuestion($question,$start);
        }
        else if($question['type']=='checkbox')
        {
            renderCheckboxQuestion($question,$start);
        }
        else
        {
            renderTextQuestion($question,$start);
        }
    }

?>