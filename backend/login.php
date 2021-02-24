<?php
    session_start();
    // if session set then redirect to dashboard
    header('Content-Type: application/json');
    if(isset($_SESSION['uid']))
    {
        echo json_encode(array("message"=>"already logged in"));
    }
    else if(isset($_POST['login_email'],$_POST['login_password']))
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
                $row = $result->fetch_assoc();
                if($row['isverified']==0)
                {
                    echo json_encode(array("message"=>"Please verify your email"));
                }
                else
                {
                    // start session and stuff
                    echo json_encode(array("message"=>"successfully logged in"));
                    $_SESSION['uid'] = $row['uid'];
                    $_SESSION['name'] = $row['name'];
                }
            }
            else
            {
                echo json_encode(array("message"=>"please check your id password"));
            }
        }
        else
        {
            echo json_encode(array("message"=>"sql error"));
        }


        $conn->close();

    }
    else
    {
        // bad request 400 
        echo '{message:"Bad Request:400"}';
    }

?>