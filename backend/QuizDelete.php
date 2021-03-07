<?php
    
    session_start();
    header("Content-Type: application/json");

    function deleteRadioQuestion($xid,$conn)
    {
        $stmt = $conn->prepare("delete from MCQ where mid=?");
        
        $stmt->bind_param("i",$xid);

        if($stmt->execute() === TRUE)
        {
            // success
            return 1;
        }
        else
        {
            return 0;
        }
    }

    function deleteCheckboxQuestion($xid,$conn)
    {
        $stmt = $conn->prepare("delete from CheckboxQns where cbqid=?");
        
        $stmt->bind_param("i",$xid);

        if($stmt->execute() === TRUE)
        {
            // success
            return 1;
        }
        else
        {
            return 0;
        }
    }

    function deleteTextQuestion($xid,$conn)
    {
        $stmt = $conn->prepare("delete from TextQns where tqid=?");
        
        $stmt->bind_param("i",$xid);

        if($stmt->execute() === TRUE)
        {
            // success
            return 1;
        }
        else
        {
            return 0;
        }
    }

    function deleteQuestionsOfQuiz($qid,$conn)
    {
        $stmt = $conn->prepare("select * from Questions where qid=?");
        
        $stmt->bind_param("i",$qid);

        if($stmt->execute() === TRUE)
        {
            $result = $stmt->get_result();
            while ($row = $result->fetch_assoc()) {
                
                $type=getQuestionTypeChar($row['type']);
                $xid = $row['xid'];

                $statuss = 1;

                if($type=='radio')
                {
                    $statuss = deleteRadioQuestion($xid,$conn);
                }
                else if($type=='checkbox')
                {
                    $statuss = deleteCheckboxQuestion($xid,$conn);
                }
                else
                {
                    $statuss = deleteTextQuestion($xid,$conn);
                }

                if($statuss==0)
                    return 0;
            }

            $stmt = $conn->prepare("delete from Questions where qid=?");
        
            $stmt->bind_param("i",$qid);

            if($stmt->execute() === TRUE)
            {
                // success
                return 1;
            }
            else
            {
                return 0;
            }
        }
        else
        {
            return 0;
        }
        return 1;
    }

    function getQuestionTypeInt($s)
    {
        $a = array("radio"=>3,"checkbox"=>2,"loose_text"=>1,"strict_text"=>0);
        return $a[$s];
    }

    function getQuestionTypeChar($s)
    {
        $a = array(3=>"radio",2=>"checkbox",1=>"loose_text",0=>"strict_text");
        return $a[$s];
    }

    $mandatory=explode(",","quiz_id");
    
    $allareset = TRUE;

    foreach($mandatory as $i)
    {
        $allareset &= isset($_POST[$i]);
    }
        

    if(!isset($_SESSION['uid']))
    {
        echo json_encode(array("result"=>"Failed","message"=>"User not logged in"));
    }
    else if($allareset)
    {
        $qid = $_POST['quiz_id'];
       
        require 'connector.php';
        
        // Deleting the qns of quiz

        $statuss = deleteQuestionsOfQuiz($qid,$conn);
        
        if($statuss==0)
        {
            echo json_encode(array("result"=>"Failed","message"=>"Error while deleting Questions"));
            $conn->close();
            exit("");
        }
        // Now I will remove the quiz entry

        $stmt = $conn->prepare("delete from Quiz where qid=?");
        
        $stmt->bind_param("i",$qid);

        if($stmt->execute() === TRUE)
        {
            // success
            echo json_encode(array("result"=>"Success","message"=>"Quiz has been deleted Successfully"));
        }
        else
        {
            echo json_encode(array("result"=>"Failed","message"=>"Error while removing the quiz entry"));
        }
      
        $conn->close();
        
    }
    else
    {
        echo json_encode(array("result"=>"Bad-request","message"=>"400"));
    }

?>