<?php
include 'assets/RandomColor.php';
use Colors\RandomColor;

require_once 'sidebar.php';
require_once 'backend/connector.php';

renderSideBar("hostedQuizStats");

?>

<div class="content ml-3 mr-3">

        <div class="card card-plain ">
            <div class="card-header card-header-info ml-3 mr-3">
                <span class="card-title fs-4 "><span class="material-icons align-middle mr-2">event</span>
                    Upcoming Quizzes
                </span>
            </div>
            <div class="card-body p-2 row justify-content-center">
                <?php


                    $query = "SELECT Quiz.qid,(SELECT COUNT(Questions.xid) FROM Questions WHERE Questions.qid = Quiz.qid) AS QCount,Quiz.title,Quiz.description, Quiz.fromdate, Quiz.todate, Quiz.uid, Quiz.quizkey, Quiz.password FROM `Quiz`,Questions WHERE Quiz.uid = ? AND Quiz.fromdate > CURRENT_TIMESTAMP GROUP BY Quiz.qid ORDER BY fromdate";
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
                            <div class="card text-center">
                                <div style="background: <?php echo $color; ?>" class="custom-header ml-3 mr-3">
                                    <div class="row justify-content-center text-center">
                                        <div class="col-12 text-center">
                                            <strong class="fs-3 p-2" style="color:#2d2f60; background: #fff;opacity:0.5; border-radius: 8px;"><?php echo $i.".".$row['title']; ?></strong>
                                        </div>

                                        <div class="col-md-6 col-sm-6 col-xs-6 fs-4 mt-3">
                                            <span><span class="material-icons align-middle mr-2 fs-2">quiz</span>
                                                <?php echo $row['QCount'];?>
                                            </span>
                                        </div>

                                        <div class="col-md-6 col-sm-6 col-xs-6 fs-4 mt-3">
                                            <span><span class="material-icons align-middle mr-2 fs-2">people</span><?php echo 10;?>
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
                                <div class="card-footer justify-content-center">
                                    <span class="optionsContainer row justify-content-center">
                                        <form class="col-md-6 col-sm-6 col-xs-12 col-lg-6" id="<?php echo $row['qid'];?>" method="post" action="/quizkeeper/hostQuiz.php">
                                            <input type="hidden" name="mode" value="ed+<?php echo $row['qid'];?>">
                                            <button name="edit_quiz_btn" class="btn btn-sm btn-outline-success" onclick="">
                                                Edit Quiz
                                            </button>
                                        </form>
                                        <div class="col-md-6 col-sm-6 col-xs-12 col-lg-6">
                                            <button name="remove_quiz_btn" onclick="removeQuiz(<?php echo $row['qid'];?>)" class="btn btn-sm btn-outline-danger">
                                                Remove Quiz
                                            </button>
                                        </div>



                                    </span>
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
                    Ongoing/Past Quizzes
                </span>
        </div>
        <div class="card-body p-2 row justify-content-center">
            <?php


            $query = "SELECT Quiz.qid,(SELECT COUNT(Questions.xid) FROM Questions WHERE Questions.qid = Quiz.qid) AS QCount,Quiz.title,Quiz.description, Quiz.fromdate, Quiz.todate, Quiz.uid, Quiz.quizkey, Quiz.password FROM `Quiz`,Questions WHERE Quiz.uid = ? AND Quiz.fromdate <= CURRENT_TIMESTAMP GROUP BY Quiz.qid ORDER BY fromdate";
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
                        <div class="custom-card d-flex text-center">
                            <span style="background: <?php echo $color; ?>" class="text-white col-md-4 col-sm-4 col-xs-4 mr-auto">
                                <div class="row justify-content-center text-center">
                                    <div class="col-12 text-center">
                                        <strong class="fs-4 p-2" style="color:#2d2f60; background: #fff;opacity:0.5; border-radius: 8px;"><?php echo $i.".".$row['title']; ?></strong>
                                    </div>

                                    <div class="col-md-6 col-sm-6 col-xs-6 fs-5 mt-2">
                                            <span><span class="material-icons align-middle mr-2 fs-4">quiz</span>
                                                <?php echo $row['QCount'];?>
                                            </span>
                                    </div>

                                    <div class="col-md-6 col-sm-6 col-xs-6 fs-5 mt-2">
                                            <span><span class="material-icons align-middle mr-2 fs-4">people</span><?php echo 10;?>
                                            </span>
                                    </div>

                                </div>
                            </span>
                            <span class="card-body text-center ml-auto">
                                <div class="col-md-12 col-sm-12 col-xs-12 fs-6 mt-2">
                                            <span class="row">
                                                <div class="col-md-6 col-sm-6 col-xs-12">
                                                    <span class="material-icons align-middle">date_range</span>
                                                    <span class="">From <strong><?php
                                                        $fr_date = date_create($row['fromdate'])->setTimezone(new DateTimeZone("Asia/Kolkata"))->format("d/m/Y g:i:s");
                                                        echo $fr_date; ?></strong><br/> To
                                                    <strong><?php $to_date = date_create($row['todate'])->setTimezone(new DateTimeZone("Asia/Kolkata"))->format("d/m/Y g:i:s");
                                                        echo $to_date; ?></strong></span>
                                                </div>
                                                <span class="col-md-6 col-sm-6 col-xs-12 text-right"><button class="btn btn-sm btn-outline-info"><span class="material-icons align-middle">analytics</span> Details</button></span>
                                            </span>
                                </div>
                            </span>
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