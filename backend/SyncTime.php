<?php
    session_start();

    header("Content-Type: application/json");
    
    if(isset($_POST['type']))
    {
        $type = $_POST['type'];

        if($type=="fetch")
        {
            if(isset($_POST['key']))
            {
                $key = $_POST['key'];
                $value = $_SESSION[$key];
                echo json_encode(array("result"=>"Success","message"=>"You have successfully fetched the key","value"=>$value));
            }
            else
            {
                echo json_encode(array("result"=>"Fail","message"=>"Bad Request - 400 - key not defined"));
            }
        }
        else if($type=="save")
        {
            if(isset($_POST['key']) && isset($_POST['value']))
            {
                $key = $_POST['key'];
                $value = $_POST['value'];
                $_SESSION[$key] = $value;
                echo json_encode(array("result"=>"Success","message"=>"You have successfully saved the key & value."));
            }
            else
            {
                echo json_encode(array("result"=>"Fail","message"=>"Bad Request - 400 - key or value not defined"));
            }
        }
        else
        {
            echo json_encode(array("result"=>"Fail","message"=>"type must be save or fetch"));
        }
    }
    else
    {
        echo json_encode(array("result"=>"Fail","message"=>"Bad Request - 400 - type not defined"));
    }
    
        
    
    echo json_encode(array("result"=>"Success","message"=>"Signup successfully! check email for verification"));
    echo json_encode(array("result"=>"Fail","message"=>"Something went wrong!"));

  
?>