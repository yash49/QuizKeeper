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

    if($stmt->execute() === TRUE)
    {
        $result = $stmt->get_result();

        while ($row = $result->fetch_assoc()) {
            $qid = $row['qid'];
        }
    }
    else
    {
        header("Location: http://{$_SERVER['SERVER_NAME']}/QuizKeeper/attemptQuiz.php");
        die();
    }

?>