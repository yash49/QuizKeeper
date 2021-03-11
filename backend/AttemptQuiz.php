<html>
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <title>QUIZKepeer</title>
    <meta content='width=device-width, initial-scale=1.0, shrink-to-fit=no' name='viewport' />

    <link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700|Roboto+Slab:400,700|Material+Icons" />
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/latest/css/font-awesome.min.css">
    <link rel="stylesheet" href="../assets/css/material-dashboard.css">
    <link rel="stylesheet" href="../assets/css/styles.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-BmbxuPwQa2lc/FVzBcNJ7UAyJxM6wuqIj61tLrc4wSX0szH/Ev+nYRRuWlolflfl" crossorigin="anonymous">

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/js/bootstrap.bundle.min.js" integrity="sha384-b5kHyXgcpbZJO/tY9Ul7kGkf1S0CWuKcCD38l8YkeH8z8QjE0GmW1gYU5S9FOnJ0" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"  integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>

    <script src="../js/controller.js"></script>
    <script src="../js/UIController.js"></script>
    <script src="../assets/js/plugins/bootstrap-notify.js"></script>


</head>
<body>
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

    $qid = -1;
    
    if($allareset==FALSE)
    {
       
        if(isset($_SESSION['qid']))
        {
            
            $qid = $_SESSION['qid'];
        }
        else
        {    
            header("Location: http://{$_SERVER['SERVER_NAME']}/QuizKeeper/attemptQuiz.php");
            die();
        }
    }
    require 'connector.php';


    if($qid==-1)
    {
        

        $quiz_key = $_POST['quiz_key'];
        $quiz_password = $_POST['quiz_password'];
        

        $stmt = $conn->prepare("select * from Quiz where quizkey=? and password=?");
        $stmt->bind_param('ss',$quiz_key,$quiz_password);

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
    }
    else
    {
       
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
            header("Location: http://{$_SERVER['SERVER_NAME']}/QuizKeeper/attemptQuiz.php");
            die();
        }
    }

    

    if($qid==-1)
    {
        header("Location: http://{$_SERVER['SERVER_NAME']}/QuizKeeper/attemptQuiz.php");
        die();
    }

    

    if(strtotime('now')<strtotime($quiz_from_date) || strtotime('now')>strtotime($quiz_to_date))
    {
        header("Location: http://{$_SERVER['SERVER_NAME']}/QuizKeeper/attemptQuiz.php");
        die();
    }

    
    if(!isset($_SESSION['thisisoriginalbrowser']))
    {
        $uid = $_SESSION['uid'];
        $stmt = $conn->prepare("select count(qaid) as yoo from QuizAttempt where uid=? and qid=?");
        $stmt->bind_param('ii',$uid,$qid);

        if($stmt->execute()==TRUE)
        {
        
            $result = $stmt->get_result();
            while($row=$result->fetch_assoc())
            {
                if($row['yoo']>0)
                {
                    header("Location: http://{$_SERVER['SERVER_NAME']}/QuizKeeper/attemptQuiz.php");
                    die();
                }
            }
        }
        else
        {
            header("Location: http://{$_SERVER['SERVER_NAME']}/QuizKeeper/attemptQuiz.php");
            die();
        }
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
?>
<!-------------------------------------------------------------------UI PART---------------------------------------------->
<nav class="navbar fixed-top  bg-success" id="quiz_attempt_navbar">
    <span class="navbar-brand"><h4><span class="material-icons fs-4" style="transform: translateY(5px)">quiz</span> <?php echo $quiz_title; ?> </h4></span>

    <li class="nav-item w-50" style="transform: translate(-20px,-15px)">

        <span class="progress" style="height: 35px; box-shadow: 4px 4px 6px #00353b;background:linear-gradient(90deg,#3895D3,#0b75c9);">
            <h5 style="transform: translateY(4px);position:absolute;margin-left: 10px; text-shadow: black 2px 2px;" id="time">HH:MM:SS</h5>
            <span class="progress-bar bg-info progress-bar-striped progress-bar-animated" role="progressbar" id="time_bar" aria-valuenow="10" aria-valuemin="0" aria-valuemax="100"></span>
        </span>
    </li>
</nav>
<script>startQuizProcess(new Date('<?php echo $quiz_to_date; ?>') );</script>
<div class="container-fluid pt-5">

    <div class="row justify-content-center">
        <div class="col-md-7 p-4 mt-3 mb-3">
            <div class="row justify-content-center">
                <form name="answers_form" id="answers_form" action="/QuizKeeper/backend/SaveAnswers.php" method="post">


<?php
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

    // ATTEMPT ENTRY FOR USER
    if(!isset($_SESSION['thisisoriginalbrowser']))
    {
    $stmt = $conn->prepare("insert into QuizAttempt(uid,qid) VALUES(?,?)");
    $stmt->bind_param('ii',$uid,$qid);

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
    $_SESSION['thisisoriginalbrowser'] = TRUE;
    $conn->close();

?>
<input type="submit" class="btn btn-success btn-lg ml-auto mr-auto mt-2"/>
    </form>
</div>
</div>

</div>
</div>
</body>
</html>
