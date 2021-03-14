<?php
    session_start();
    header("Content-Type: application/json");

    require_once 'connector.php';

    if(!isset($_POST['ansid']) || !isset($_SESSION['uid'])){
        echo json_encode(array("result"=>"Failed","message"=>"Insufficient Data!"));
    }
    else{
        $stmt = $conn->prepare("Update Answers set marks_granted=1 where ansid = ?");

        $stmt->bind_param("i",$_POST['ansid']);

        if ($stmt->execute() === TRUE)
        {
            // updated successfully
            echo json_encode(array("result"=>"Success","message"=>"marks granted"));
        }
        else
        {
            echo json_encode(array("result"=>"Fail","message"=>"SQL quiz data update error"));
            $conn->close();
            exit("");
        }
    }
?>
