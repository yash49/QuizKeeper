<?php
    
    session_start();
    header("Content-Type: application/json");

    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\SMTP;

    require '../vendor/autoload.php';

    function sendMail($to,$subject,$body)
    {
        $mail = new PHPMailer();
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->Port = 587;
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->SMTPAuth = true;

        $mail->Username = 'quiz.keeper10@gmail.com';
        $mail->Password = 'Quiz_100';
        $mail->setFrom('quiz.keeper10@gmail.com', 'Quiz Keeper');

        foreach($to as $email)
            $mail->addAddress($email);

        $mail->Subject = $subject;

        $mail->Body = $body;

        if (!$mail->send()) {
           // echo 'Mailer Error: ' . $mail->ErrorInfo;
        } else {
           // echo 'Message sent!';
        }
    }

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

    // returns id of inserted qns
    function insertRadioQuestion($question,$conn)
    {
        $qns = $question['question'];
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
        $qns = $question['question'];
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
        $qns = $question['question'];
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
            return insertCheckboxQuestion($question,$conn);
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
    

    $mandatory=explode(",","quiz_id,quiz_title,quiz_desc,quiz_start_date,quiz_end_date,quiz_shuffle,questionData,email,quiz_key,quiz_password");
    
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
        $quiz_title  = $_POST['quiz_title'];
        $quiz_desc  = $_POST['quiz_desc'];
        $quiz_start_date  = date("Y-m-d H:i:s",$_POST['quiz_start_date']);
        $quiz_end_date  = date("Y-m-d H:i:s",$_POST['quiz_end_date']);
        $quiz_shuffle  = $_POST['quiz_shuffle'];
        $quiz_id = $_POST['quiz_key'];
        $quiz_password = $_POST['quiz_password'];
        
        require 'connector.php';
        
        

        $stmt = $conn->prepare("Update Quiz set title=?,description=?,fromdate=?,todate=?,shuffle=? where qid = ?");

        $stmt->bind_param("ssssii",$quiz_title,$quiz_desc,$quiz_start_date,$quiz_end_date,$quiz_shuffle,$qid);

        if ($stmt->execute() === TRUE) 
        {
            // updated successfully
        } 
        else 
        {
            echo json_encode(array("result"=>"Fail","message"=>"SQL quiz data update error"));
            $conn->close();
            exit("");
        }

       // Here I need to delete previous questions and add new questions
        // Question Structure
        
        // var aaa = { quiz_id = qid,
            // "questionData": [
            //     {
            //         "question": "Edit mode MCQ q",
            //         "answer": "B",
            //         "options": "A,B,C",
            //         "mark": "1",
            //         "type": "radio"
            //     },
            //     {
            //         "question": "Checkbox Q in edit mode",
            //         "answer": "AC,CC",
            //         "options": "AC,BC,CC",
            //         "mark": "1",
            //         "type": "checkbox"
            //     },
            //     {
            //         "question": "Strict Q for edit mode",
            //         "answer": "edit mode",
            //         "options": "",
            //         "mark": "1",
            //         "type": "strict_text"
            //     },
            //     {
            //         "question": "Tell me about your self in edit mode",
            //         "answer": "",
            //         "options": "",
            //         "mark": "1",
            //         "type": "loose_text"
            //     }
            // ],
            // "quiz_title": "editing test",
            // "quiz_desc": "qwerty",
            // "quiz_start_date": 1615788900,
            // "quiz_end_date": 1615875300,
            // "quiz_shuffle": 1,
            // "email": [] }
       
        // Logic to delete previous questions

        deleteQuestionsOfQuiz($qid,$conn);
        
        // Now I will add neww Questions

        $questions = $_POST['questionData'];
        $totalQuestions = count($questions);

        for($i=0;$i<$totalQuestions;$i++)
        {
            $question = $questions[$i];
            $status = addQuestionToQuiz($qid,$question,$conn);
            if($status==0)
            {
                echo json_encode(array("result"=>"Failed","message"=>"Error while updating Questions"));
                $conn->close();
                exit("");
            }
        }

        
        
        //send mail to participants

        $emaillist = $_POST['email'];

        $to = $emaillist;

        $subject = "Quiz Keeper | You have been Invited to Attempt a Quiz";
        $body = "Quiz Title : $quiz_title\n";
        $body .= "Quiz Description : $quiz_desc\n";
        $body .= "Quiz Start Date : $quiz_start_date\n";
        $body .= "Quiz End Date : $quiz_end_date\n\n";
        $body .= "Quiz Key : $quiz_id\n";
        $body .= "Quiz Password : $quiz_password\n";
        

        sendMail($to,$subject,$body);
    

        echo json_encode(array("result"=>"Success","message"=>"Quiz has been updated successfully","quizKey"=>$quiz_id, "quizPass"=>$quiz_password));

        $conn->close();
        
    }
    else
    {
        echo json_encode(array("result"=>"Bad-request","message"=>"400"));
    }

?>