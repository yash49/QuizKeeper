<?php

    // if session set then redirect to dashboard

    if(isset($_POST['login_email'],$_POST['login_password']))
    {
        $email  = $_POST['login_email'];
       
        $password  = $_POST['login_password'];

        $password = md5($password);

        require 'connector.php';
        

        $stmt = $conn->prepare("select * from Users where email=? and password=?");
        $stmt->bind_param("ss",$email,$password);


        if($stmt->execute() === TRUE)
        {
            $result = $stmt->get_result();
            if($result -> num_rows>0)
            {
                // start session and stuff
                echo '{message:"successfully logged in"}';
            }
            else
            {
                echo '{message:"please check your id password"}';
            }
        }
        else
        {
            echo '{message:"sql error"}';
        }


        $conn->close();

    }
    else
    {
        // bad request 400 
        echo '{message:"Bad Request:400"}';
    }

?>