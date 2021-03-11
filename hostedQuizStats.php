<?php
include 'assets/RandomColor.php';
use Colors\RandomColor;

require_once 'sidebar.php';
require_once 'backend/connector.php';

renderSideBar("hostedQuizStats");

function getAttemptedUsers($qid,$conn)
{
        $stmt=$conn->prepare("SELECT count(QuizAttempt.qaid) as ucount FROM Quiz,QuizAttempt where QuizAttempt.qid=Quiz.qid and Quiz.qid=? GROUP BY Quiz.qid");
        $stmt->bind_param('i',$qid);
        if($stmt->execute()==TRUE)
        {
                $result = $stmt->get_result();
                while($row=$result->fetch_assoc())
                {
                        return $row['ucount'];
                }
                return "0";
        }
        else
                return "SQL Err";
}

?>
<script src="js/controller.js"></script>
<div class="content ml-3 mr-3">

        <div class="card card-plain ">
            <div class="card-header card-header-info ml-3 mr-3">
                <span class="card-title fs-4 "><span class="material-icons align-middle mr-2">event</span>
                    Ongoing/Upcoming Quizzes
                </span>
            </div>
            <div class="card-body p-2 row justify-content-center">
                <?php


                    $query = "SELECT Quiz.qid,(SELECT COUNT(Questions.xid) FROM Questions WHERE Questions.qid = Quiz.qid) AS QCount,Quiz.title,Quiz.description, Quiz.fromdate, Quiz.todate, Quiz.uid, Quiz.quizkey, Quiz.password FROM `Quiz`,Questions WHERE Quiz.uid = ? AND Quiz.todate >= CURDATE() GROUP BY Quiz.qid ORDER BY fromdate";
                    $stmt = $conn->prepare($query);
                    $stmt->bind_param("i",$_SESSION['uid']);

                    if($stmt->execute() === TRUE) {
                        $result = $stmt->get_result();
                        $i = 1;
                        $dates = array();
                        $pastColors = array();
                        $color = RandomColor::one(array('hue'=>array('green',160),'luminosity'=>"dark"));
                        while ($row = $result->fetch_assoc()) {
                           // print_r($row);
                            $frDate = date_create($row['fromdate'])->setTimezone(new DateTimeZone("Asia/Kolkata"))->format("d/m/Y");
                            if(!in_array($frDate,$dates)){
                                array_unshift($dates, $frDate);
                                $color = RandomColor::one(array('hue'=>array('green',160),'luminosity'=>"dark"));
                                if(in_array($color,$pastColors)) $color = RandomColor::one(array('hue'=>array('purple', 180, 'red'),'luminosity'=>"dark"));
                                array_unshift($pastColors, $color);
                            }
                ?>
                         <div class="col-md-4 col-sm-12 col-xs-12 col-lg-4 pl-4" id="quiz_<?php echo $row['qid'];?>">
                            <div class="quiz-card card text-center">
                                <div style="background: <?php echo $color; ?>" class="custom-header ml-3 mr-3">
                                    <div class="row justify-content-center text-center">
                                        <div class="col-12 text-center">
                                            <div class="fs-3 p-2" style="color:#2d2f60; background: #fff;opacity:0.5; border-radius: 8px;"><strong><?php echo $i.".".$row['title']; ?></strong></div>
                                        </div>

                                        <div class="col-md-12 col-sm-12 col-xs-12 fs-5 mt-3">
                                            <span><span class="material-icons align-middle mr-2 fs-2">quiz</span>Questions:
                                                <?php echo $row['QCount'];?>
                                            </span>
                                        </div>

                                    </div>
                                </div>
                                <div class="card-body row text-center">
                                    <div class="col-md-12 col-sm-12 col-xs-12 fs-6 mt-2">
                                            <span class="d-flex justify-content-around">
                                                <span class="material-icons align-middle mr-auto">date_range</span>
                                                <span class=" flex-grow-1 mr-4">From <strong><?php
                                                        $fr_date = date_create($row['fromdate'])->setTimezone(new DateTimeZone("Asia/Kolkata"))->format("d/m/Y g:i:s");
                                                        echo $fr_date; ?></strong><br/> To
                                                    <strong><?php $to_date = date_create($row['todate'])->setTimezone(new DateTimeZone("Asia/Kolkata"))->format("d/m/Y g:i:s");
                                                        echo $to_date; ?></strong></span>
                                            </span>
                                    </div>
                                </div>
                                <div class="card-footer ">
                                    <div class="optionsContainer text-center w-100">
                                        <form  style="display: inline-block" id="<?php echo $row['qid'];?>" method="post" action="/quizkeeper/hostQuiz.php">
                                            <input type="hidden" name="mode" value="ed+<?php echo $row['qid'];?>">
                                            <button name="edit_quiz_btn" class="btn btn-sm btn-outline-success" onclick="">
                                                Edit Quiz
                                            </button>
                                        </form>
                                            <button id="remove_quiz_btn<?php echo $row['qid'];?>" onclick="removeQuiz(<?php echo $row['qid'];?>)" class="btn btn-sm btn-outline-danger">
                                                Remove Quiz                                            </button>
                                        <span style="position:relative; display: none; margin-bottom: -10px" id="loadbar<?php echo $row['qid'];?>" class="ml-2 fs-6 spinner-border text-danger">
                                                </span>

                                    </div>
                                </div>
                                <div class="quiz-creds custom-card bg-light" style="border-radius: 50px 8px 8px 50px;">
                                    <img src="assets/img/shield.png" class="float-left" style="transform: translate(-1px,0px)" width="48px" height="48px">
                                    <div class="text-center text-white" style="transform: translate(-20px,0px)">
                                        <div>key:&nbsp;<strong><?php echo $row['quizkey'];?></strong><br>password:&nbsp;<strong><?php echo $row['password'];?></strong></div>
                                    </div>
                                </div>
                            </div>
                         </div>

               <?php $i++;}}?>

            </div>
        </div>

    <!-----------------------------------------------PAST QUIZ--------------------------------------------------------->

    <div class="card card-plain mt-5">
        <div class="card-header card-header-info ml-3 mr-3">
                <span class="card-title fs-4 "><span class="material-icons align-middle mr-2">event</span>
                    Past Quizzes
                </span>
        </div>
        <div class="card-body p-4 row justify-content-center">
            <?php


            $query = "SELECT Quiz.qid,(SELECT COUNT(Questions.xid) FROM Questions WHERE Questions.qid = Quiz.qid) AS QCount,Quiz.title,Quiz.description, Quiz.fromdate, Quiz.todate, Quiz.uid, Quiz.quizkey, Quiz.password FROM `Quiz`,Questions WHERE Quiz.uid = ? AND Quiz.todate < CURRENT_TIMESTAMP GROUP BY Quiz.qid ORDER BY fromdate";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("i",$_SESSION['uid']);

            if($stmt->execute() === TRUE) {
                $result = $stmt->get_result();
                $i = 1;
                $dates = array();
                $pastColors = array();
                $color = RandomColor::one(array('hue'=>array('green', 160),'luminosity'=>"dark"));
                while ($row = $result->fetch_assoc()) {
                    // print_r($row);
                    $frDate = date_create($row['fromdate'])->setTimezone(new DateTimeZone("Asia/Kolkata"))->format("d/m/Y");
                    if(!in_array($frDate,$dates)){
                        array_unshift($dates, $frDate);
                        $color = RandomColor::one(array('hue'=>array('green', 160),'luminosity'=>"dark"));
                        if(in_array($color,$pastColors)) $color = RandomColor::one(array('hue'=>array('purple', 180, 'red'),'luminosity'=>"dark"));
                        array_unshift($pastColors, $color);
                    }
                    ?>

                    <div class="col-md-12 col-sm-12 col-xs-12 col-lg-12 p-3">
                        <div class="custom-card row text-center">
                            <div style="background: <?php echo $color; ?>; border-radius: 8px 0px 0px 8px" class="text-white col-md-4 col-sm-12 col-xs-12 mr-auto">
                                <div class="row justify-content-center text-center">
                                    <div class="col-12 text-center">
                                        <div style="color:#2d2f60; background: #fff;opacity:0.5; border-radius:0px 0px 8px 8px;"> <strong class="fs-4 p-2" ><?php echo $i.".".$row['title']; ?></strong></div>
                                    </div>

                                    <div class="col-md-6 col-sm-6 col-xs-6 fs-5 mt-2">
                                            <span><span class="material-icons align-middle mr-2 fs-4">quiz</span>
                                                <?php echo $row['QCount'];?>
                                            </span>
                                    </div>

                                    <div class="col-md-6 col-sm-6 col-xs-6 fs-5 mt-2">
                                            <span><span class="material-icons align-middle mr-2 fs-4">people</span><?php echo getAttemptedUsers($row['qid'],$conn);?>
                                            </span>
                                    </div>

                                </div>
                            </div>
                            <div class="card-body col-md-6 col-sm-12 col-xs-12 text-center ml-auto">
                                <div class="col-md-12 col-sm-12 col-xs-12 fs-6 mt-2">
                                            <div class="row">
                                                <div class="col-md-6 col-sm-6 col-xs-12">
                                                    <span class="material-icons align-middle">date_range</span>
                                                    <span class="">From <strong><?php
                                                        $fr_date = date_create($row['fromdate'])->setTimezone(new DateTimeZone("Asia/Kolkata"))->format("d/m/Y g:i:s");
                                                        echo $fr_date; ?></strong><br/> To
                                                    <strong><?php $to_date = date_create($row['todate'])->setTimezone(new DateTimeZone("Asia/Kolkata"))->format("d/m/Y g:i:s");
                                                        echo $to_date; ?></strong></span>
                                                </div>
                                                <span class="col-md-6 col-sm-6 col-xs-12 text-right"><button class="btn btn-sm btn-outline-info"><span class="material-icons align-middle">analytics</span> Details</button></span>
                                            </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <?php $i++;}}?>

        </div>
    </div>
</div>
</div> <!--END OF main-panel class-->
</div><!--END OF wrapper class-->
</body>
</html>