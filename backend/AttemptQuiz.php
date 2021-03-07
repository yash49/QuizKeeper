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

    $stmt = $conn->prepare("select * from Quiz where")

?>