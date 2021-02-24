<?php

    session_start();    
    header('Content-Type: application/json');

    if(isset($_SESSION['uid']))
    {
        echo json_encode(array("result"=>"Success","message"=>"already logged in"));
    }
    else if(isset($_POST['verify_email'],$_POST['verify_otp']))
    {
        $email = $_POST['verify_email'];
        $otp = $_POST['verify_otp'];

        require 'connector.php';

        $stmt = $conn->prepare("Update Users set isverified=1 where email=? and otp=?");

        $stmt->bind_param("si",$email,$otp);

        if($stmt->execute()===TRUE)
        {
            $affected = $stmt->affected_rows;
            if($affected<=0)
            {
                echo json_encode(array("result"=>"Fail","message"=>"OTP or Email is wrong"));
            }
            else
            {
                echo json_encode(array("result"=>"Success","message"=>"Verification Done"));
            }

        }
        else
        {
            echo json_encode(array("result"=>"Fail","message"=>"DB Error"));
        }
    }
    else
    {
        echo json_encode(array("result"=>"Fail","message"=>"Bad Request:400"));
    }
?>