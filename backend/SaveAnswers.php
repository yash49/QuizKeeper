<?php

    session_start();

    if(!isset($_SESSION['uid']) || !isset($_SESSION['qid']))
    {
    	header("Location: http://{$_SERVER['SERVER_NAME']}/QuizKeeper/index.php");
	    die();
    }
    
    require 'connector.php';

    $qid = $_SESSION['qid'];
    $uid = $_SESSION['uid'];

    $stmt = $conn->prepare("select * from Quiz where qid=?");
    $stmt->bind_param('i',$qid);

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
        $conn->close();
        header("Location: http://{$_SERVER['SERVER_NAME']}/QuizKeeper/dashboard.php");
        die();
    }

    if(strtotime('now')<strtotime($quiz_from_date) || strtotime('now')>strtotime($quiz_to_date))
    {
        $conn->close();
        header("Location: http://{$_SERVER['SERVER_NAME']}/QuizKeeper/dashboard.php");
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

    $_SESSION['qid'] = $qid;

    $stmt = $conn->prepare("Select * from Questions where qid=?");
    $stmt->bind_param('i',$qid);

    
    $questions = array();

    if($stmt->execute()===TRUE)
    {
        $result = $stmt->get_result();

        while($row = $result->fetch_assoc())
        {
            $questions[$row['qnsid']]=getQuestionTypeInt($row['type']);
        }
    }
    else
    {
        $conn->close();
        header("Location: http://{$_SERVER['SERVER_NAME']}/QuizKeeper/dashboard.php");
        die();
    }

    // attempt

    // $stmt = $conn->prepare("insert into QuizAttempt(uid,qid) VALUES(?,?)");
    // $stmt->bind_param('ii',$uid,$qid);

    // if($stmt->execute()===TRUE)
    // {
        
    // }
    // else
    // {
    //     $conn->close();
    //     header("Location: http://{$_SERVER['SERVER_NAME']}/QuizKeeper/dashboard.php");
    //     die();
    // }

    // save ans

    $stmt = $conn->prepare("insert into Answers(qnsid,userid,ans) VALUES(?,?,?)");
    
    foreach($questions as $qnsid=>$type)
    {
        $ans = '';
        if(isset($_POST[$qnsid]))
        {
            $ans = $_POST[$qnsid];
            if($type=='checkbox')
            {
                $tmp='';
                foreach($ans as $value)
                {
                    $tmp = $tmp.$value.',';
                }
                $ans = $tmp;
            }
        }
        $stmt->bind_param('iis',$qnsid,$uid,$ans);
        if($stmt->execute()===TRUE)
        {
            
        }
        else
        {
            
            $conn->close();
            header("Location: http://{$_SERVER['SERVER_NAME']}/QuizKeeper/dashboard.php");
            die();
        }
    }

    $conn->close();
    unset($_SESSION['qid']);
    header("Location: http://{$_SERVER['SERVER_NAME']}/QuizKeeper/attemptQuiz.php");
    die();
?>