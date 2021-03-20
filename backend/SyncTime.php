<?php
    session_start();
    //print_r($_REQUEST);
    header("Content-Type: application/json");
    $rawValue = file_get_contents('php://input');
    $data = json_decode($rawValue);

    //print_r($data);

    if(isset($data->type) || isset($_POST['type']))
    {

        $type = isset($data->type)?$data->type:($_POST['type']);

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

            if(isset($data->key) && isset($data->value))
            {
                $key = $data->key;
                $value = $data->value;
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
?>