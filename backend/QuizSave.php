<?php

    
    session_start();
    header("Content-Type: application/json");

    function getQuestionTypeInt($s)
    {
        $a = array("radio"=>3,"checkbox"=>2,"loose_text"=>1,"strict_text"=>0);
        return $a[$s];
    }

    // returns id of inserted qns
    function insertRadioQuestion($question,$conn)
    {
        $qns = $question['questoion'];
        $options = $question['options'];
        $correctans = $question['answer'];
        $sql="INSERT INTO MCQ (qns,options,correctans) VALUES ('".$qns."','".$options."','".$correctans."'); SELECT LAST_INSERT_ID() as yoo;";

        $response = -1;

        if ($conn -> multi_query($sql)) 
        {
            do 
            {

                if ($result = $conn -> store_result()) 
                {
                    while ($row = $result -> fetch_row()) 
                    {
                        $response = $row[0];
                    }
                    $result -> free_result();
                }

            }while ($conn -> next_result());
        }

        return $response;
    }

    // returns id of inserted qns
    function insertCheckboxQuestion($question,$conn)
    {
        $qns = $question['questoion'];
        $options = $question['options'];
        $correctans = $question['answer'];
        $sql="INSERT INTO CheckboxQns (qns,options,correctans) VALUES ('".$qns."','".$options."','".$correctans."'); SELECT LAST_INSERT_ID() as yoo;";

        $response = -1;

        if ($conn -> multi_query($sql)) 
        {
            do 
            {

                if ($result = $conn -> store_result()) 
                {
                    while ($row = $result -> fetch_row()) 
                    {
                        $response = $row[0];
                    }
                    $result -> free_result();
                }

            }while ($conn -> next_result());
        }

        return $response;
    }

    // returns id of inserted qns
    function insertTextQuestion($question,$conn)
    {
        $qns = $question['questoion'];
        $type = getQuestionTypeInt($question['type']);
        $ans = $question['answer'];

        $sql="INSERT INTO TextQns (type,qns,ans) VALUES (".$type.",'".$qns."','".$ans."'); SELECT LAST_INSERT_ID() as yoo;";

        $response = -1;

        if ($conn -> multi_query($sql)) 
        {
            do 
            {

                if ($result = $conn -> store_result()) 
                {
                    while ($row = $result -> fetch_row()) 
                    {
                        $response = $row[0];
                    }
                    $result -> free_result();
                }

            }while ($conn -> next_result());
        }

        return $response;
    }

    

    // returns xid of the Question | xid is primary key
    function insertParticularQuestionToQuiz($question,$conn)
    {
        if($question['type']=="radio")
        {
            return insertRadioQuestion($question,$conn);
        }
        if($question['type']=="checkbox")
        {
            return insertRadioQuestion($question,$conn);
        }
        else if($question["type"]=="loose_text" || $question["type"]=="strict_text")
        {
            return insertTextQuestion($question,$conn);
        }
        return -1;
    }

    // returns status that it was successful or it
    function addQuestionToQuiz($qid,$question,$conn)
    {
        // Insert into particular table and get that xid based on type
        $xid = insertParticularQuestionToQuiz($question,$conn);        

        if($xid==-1)
        {
            return 0;
        }

        $type = getQuestionTypeInt($question['type']);
        $marks = $question['mark'];
        
        $stmt = $conn->prepare("INSERT INTO Questions(type,xid,marks,qid)VALUES (?, ?, ?, ?)");

        $stmt->bind_param("iiii",$type,$xid,$marks,$qid);
        

        if ($stmt->execute() === TRUE) 
        {
            return 1;
        } 
        else 
        {
            return 0;
        }
    }
    
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

    $mandatory=explode(",","quiz_title,quiz_desc,quiz_start_date,quiz_end_date,quiz_shuffle,questionCount,questionsData");
    
    $allareset = 1;

    foreach($mandatory as $i)
    {
        $allareset &= isset($i);
    }
        

    if(!isset($_SESSION['uid']))
    {
        echo json_encode(array("result"=>"Failed","message"=>"User not logged in"));
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
        
        // Question Structure
        
        // var aaa = {questionCount:2, questionsData:[
        //     {
        //         id:1,
        //         question:"WHAT IS...",
        //         answer:"ABC",
        //         type: radio/checkbox/loose_text/strict_text
        //         options:"CSV",
        //         mark:10
        //     },
        //     {
        //         id:2,
        //         question:"WHAT IS...",
        //         answer:"ABCs",
        //         type:"text",
        //         options:"",
        //         mark:10
        //     }
        // ]};

        $totalQuestions = $_POST['questionCount'];
        $questions = $_POST['questionsData'];

        for($i=0;$i<$totalQuestions;$i++)
        {
            $question = $questions[$i];
            $status = addQuestionToQuiz($qid,$question,$conn);
            if($status==0)
            {
                echo json_encode(array("result"=>"Failed","message"=>"Error while adding Questions"));
                $conn->close();
                exit("");
            }
        }

        echo json_encode(array("result"=>"Success","message"=>"Quiz has been created successfully"));

        $conn->close();
        
    }
    else
    {
        echo json_encode(array("result"=>"Bad-request","message"=>"400"));
    }

?>