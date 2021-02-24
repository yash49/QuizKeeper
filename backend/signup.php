<?php
    session_start();
    // if session set then redirect to dashboard
    if(isset($_SESSION['uid']))
    {
        echo json_encode(array("message"=>"already logged in"));
    }
    else if(isset($_POST['signup_name'],$_POST['signup_email'],$_POST['signup_phone'],$_POST['signup_password']))
    {
        $name  = $_POST['signup_name'];
        $email  = $_POST['signup_email'];
        $mobile  = $_POST['signup_phone'];
        $password  = $_POST['signup_password'];

        $password = md5($password);

        require 'connector.php';
        
        $verified=0;

        $stmt = $conn->prepare("INSERT INTO Users(name,password,email,mobile,isverified) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssi",$name,$password,$email,$mobile,$verified);


        // $sql = "INSERT INTO Users(name,password,email,mobile,isverified) values ('".$name."','".$password."','".$email."','".$mobile."',0)";

        // fire verification email

        if ($stmt->execute() === TRUE) {
            
            // redirect to login sigup page for re login
            //echo "login ma jay che<br>";
            
            echo '{message:"success"}';

            //echo "<script>window.location.href ='../index.php'</script>";
        } else {
            // some error has occured
            // echo "locha thaya<br>";
            
            echo '{message:"fail"}';

            //echo $sql;
        }

        $conn->close();

    }
    else
    {
        // bad request 400 
        echo '{message:"Bad Request:400"}';
    }

?>