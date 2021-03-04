<?php
    
    function getLatestQid($uid)
    {
        require 'connector.php';
        
        $stmt = $conn->prepare("select max(qid) as yoo from Quiz where uid=?");
        
        $stmt->bind_param("i",$uid);

        $qid = -1;

        if($stmt->execute() === TRUE)
        {
            $result = $stmt->get_result();
            while ($row = $result->fetch_assoc()) {
                $qid=$row['yoo'];
            }
        }
        else
        {
            $conn->close();
            return -1;
        }

        $conn->close();	
        return $qid;
    }

    function getIdPass($n)
    {
        $id = "";
        $pass = "";
        $a=str_split("1234567890qwertyuiopasdfghjklzxcvbnm");
        $random=array_rand($a,$n);
        foreach($random as $i) $id.=$a[$i];
        $random=array_rand($a,$n);
        foreach($random as $i) $pass.=$a[$i];

        require 'connector.php';

        $stmt = $conn->prepare("select qid from Quiz where quizkey=? or password=?");
        $stmt->bind_param("ss",$id,$pass);

        if($stmt->execute() === TRUE)
        {
            $result = $stmt->get_result();
            if($result -> num_rows>0)
            {
                $conn->close();
                return getIdPass($n);
            }
            else
            {
                $conn->close();
                return array($id,$pass);
            }
        }
        else
        {
            $conn->close();
            return -1;
        }

    }

    $mandatory=explode(",","quiz_title,quiz_desc,quiz_start_date,quiz_end_date,quiz_shuffle");
    
    $allareset = 1;

    foreach($mandatory as $i)
    {
        $allareset &= isset($i);
    }
        

    session_start();
    header("Content-Type: application/json");
    if(!isset($_SESSION['uid']))
    {
        echo json_encode(array("message"=>"User not logged in"));
    }
    else if($allareset==1)
    {
        $quiz_title  = $_POST['quiz_title'];
        $quiz_desc  = $_POST['quiz_desc'];
        $quiz_start_date  = date("Y-m-d H:i:s",$_POST['quiz_start_date']);
        $quiz_end_date  = date("Y-m-d H:i:s",$_POST['quiz_end_date']);
        $quiz_shuffle  = $_POST['quiz_shuffle'];

        require 'connector.php';
        
        $idpass = getIdPass(8);

        if($idpass==-1)
        {
            echo json_encode(array("result"=>"Fail","message"=>"SQL quizid quizpassword error"));
            exit("");
        }

        $quiz_id = $idpass[0];
        $quiz_password = $idpass[1];
        $uid = intval($_SESSION['uid']);    
    

        $stmt = $conn->prepare("INSERT INTO Quiz(uid,title,description,fromdate,todate,shuffle,quizkey,password) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?)");

        $stmt->bind_param("issssiss",$uid,$quiz_title,$quiz_desc,$quiz_start_date,$quiz_end_date,$quiz_shuffle,$quiz_id,$quiz_password);
        
        $qid = -1;

        if ($stmt->execute() === TRUE) 
        {
            // get latest qid by uid
            $qid = getLatestQid($uid);
        } 
        else 
        {
            echo json_encode(array("result"=>"Fail","message"=>"SQL quiz data entry error"));
            $conn->close();
            exit("");
        }

        if($qid==-1)
        {
            echo json_encode(array("result"=>"Fail","message"=>"Quiz id entry has not been generated in Database."));
            $conn->close();
            exit("");
        }

        // Here I have the $qid of the user , now i Need to insert the questions 

        


        $conn->close();
        
    }
    else
    {
        echo json_encode(array("result"=>"Bad-request","message"=>"400"));
    }

?>