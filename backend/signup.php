<?php

    // if session set then redirect to dashboard
    if(isset($_POST['signup_name'],$_POST['signup_email'],$_POST['signup_phone'],$_POST['signup_password']))
    {
        $name  = $_POST['signup_name'];
        $email  = $_POST['signup_email'];
        $mobile  = $_POST['signup_phone'];
        $password  = $_POST['signup_password'];

        $password = md5($password);

        require 'connector.php';
        
        $verified=0;
        header('Content-Type: application/json');

        $stmt = $conn->prepare("INSERT INTO Users(name,password,email,mobile,isverified) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssi",$name,$password,$email,$mobile,$verified);


        // $sql = "INSERT INTO Users(name,password,email,mobile,isverified) values ('".$name."','".$password."','".$email."','".$mobile."',0)";
        // fire verification email

        if ($stmt->execute() === TRUE) {
            // redirect to login sigup page for re login
            echo json_encode(array("message"=>"Success"));

        } else {
            echo json_encode(array("message"=>"Failed"));

        }


    }
    else
    {
        // bad request 400
        echo json_encode(array("message"=>"400"));

    }

?>