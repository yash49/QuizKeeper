<?php
    session_start();
    // if session set then redirect to dashboard
header("Content-Type: application/json");
    if(isset($_SESSION['uid']))
    {
        echo json_encode(array("message"=>"already logged in"));
    }
    else if(isset($_POST['signup_name'],$_POST['signup_email'],$_POST['signup_phone'],$_POST['signup_password']))
    {
        $name  = $_POST['signup_name'];
        $email  = $_POST['signup_email'];
        $mobile  = $_POST['signup_phone'];
        $signup_password  = md5($_POST['signup_password']);

        require 'connector.php';

        $verified=0;

        $stmt = $conn->prepare("INSERT INTO Users(name,password,email,mobile,isverified) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssi",$name,$signup_password,$email,$mobile,$verified);


        // $sql = "INSERT INTO Users(name,password,email,mobile,isverified) values ('".$name."','".$password."','".$email."','".$mobile."',0)";

        // fire verification email

        if ($stmt->execute() === TRUE) {
            
            // redirect to login sigup page for re login
            echo json_encode(array("result"=>"Success","message"=>"Signup successfully! check email for verification".$signup_password.$_POST['signup_password']));

        } else {
            echo json_encode(array("result"=>"Fail","message"=>"Something went wrong!"));

        }

        $conn->close();

    }
    else
    {
        echo json_encode(array("result"=>"Fail","message"=>400));
    }

?>