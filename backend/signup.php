<?php
    session_start();

    function generateNumericOTP() { 
        $n = 6;
        $generator = "0123456789"; 
        $result = ""; 
      
        for ($i = 1; $i <= $n; $i++) { 
            $result .= substr($generator, (rand()%(strlen($generator))), 1); 
        } 
        return $result; 
    } 

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

        foreach($to as $email=>$name)
            $mail->addAddress($email, $name);

        $mail->Subject = $subject;

        $mail->Body = $body;

        if (!$mail->send()) {
           // echo 'Mailer Error: ' . $mail->ErrorInfo;
        } else {
           // echo 'Message sent!';
        }
    }


    // if session set then redirect to dashboard
    header("Content-Type: application/json");
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
        $otp = generateNumericOTP();

        $stmt = $conn->prepare("INSERT INTO Users(name,password,email,mobile,otp,isverified) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssii",$name,$password,$email,$mobile,$otp,$verified);
            
        $to = ARRAY($email=>$name);
        $subject = "Quiz Keeper Verification OTP";
        $body = "Welcome to the family.\nYour OTP is $otp";

        sendMail($to,$subject,$body);

        if ($stmt->execute() === TRUE) {
            echo json_encode(array("result"=>"Success","message"=>"Signup successfully! check email for verification"));
        } else {
            echo json_encode(array("result"=>"Fail","message"=>"Something went wrong!"));
        }

        $conn->close(); 

    }
    else
    {
        echo json_encode(array("result"=>"Bad-request","message"=>"400"));
    }

?>