<?php
require_once 'sidebar.php';
renderSideBar("myResults");
?>

    <div class="content ml-3 mr-3">
    <div class="card card-plain"> 
        <div class="card-header card-header-info ml-3 mr-3">
                <span class="card-title fs-4 "><span class="material-icons align-middle mr-2">library_books</span>
                    Quizes Attempted in Past
                </span>
        </div>
        <div class="card-body p-4 row justify-content-center">

                <?php

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
                                        return -1;
                                }
                                else
                                        return -1;
                        }

                
                        $uid = $_SESSION['uid'];
                        require 'backend/connector.php';

                        $stmt = $conn->prepare("select * from QuizAttempt where uid=?");
                        $stmt->bind_param('i',$uid);

                        $quizes = array();

                        if($stmt->execute()==TRUE)
                        {
                                $result = $stmt->get_result();
                                while($row=$result->fetch_assoc())
                                {
                                        array_push($quizes,$row['qid']);
                                }
                        }
                        else
                        {
                                echo "Something went wrong [Issue in sql select query to QuizAttempt]";
                                exit("");
                        }

                        $color = "#2a9865";

                        $stmt = $conn->prepare("SELECT Quiz.qid as qid,title,description,fromdate,todate,count(Questions.qnsid) as qcount FROM Quiz,Questions where Quiz.qid=Questions.qid and Quiz.qid=? GROUP BY Quiz.qid");
                        

                        foreach($quizes as $qid)
                        {
                                $stmt->bind_param('i',$qid);
                                if($stmt->execute()==TRUE)
                                {
                                        $result = $stmt->get_result();
                                        $row = $result->fetch_assoc();

                                        if($row)
                                        {
                                                $totalusers = getAttemptedUsers($qid,$conn);
                ?>

<div class="col-md-12 col-sm-12 col-xs-12 col-lg-12 p-3">

<div class="custom-card row text-center">
            <div style="background:<?php echo $color;?>; border-radius: 8px 0px 0px 8px" class="text-white col-md-4 col-sm-12 col-xs-12 mr-auto">
                <div class="row justify-content-center text-center">
                    <div class="col-12 text-center">
                        <div style="color:#2d2f60; background: #fff;opacity:0.5; border-radius:0px 0px 8px 8px;"> <strong class="fs-4 p-2" ><?php echo $row['title']; ?></strong></div>
                    </div>

                    <div class="col-md-6 col-sm-6 col-xs-6 fs-5 mt-2">
                            <span><span class="material-icons align-middle mr-2 fs-4">quiz</span>
                                <?php echo $row['qcount'];?>
                            </span>
                    </div>

                    <div class="col-md-6 col-sm-6 col-xs-6 fs-5 mt-2">
                            <span><span class="material-icons align-middle mr-2 fs-4">people</span><?php echo $totalusers;?>
                            </span>
                    </div>

                </div>
            </div>
            <div class="card-body col-md-6 col-sm-12 col-xs-12 text-center ml-auto">
                <div class="col-md-12 col-sm-12 col-xs-12 fs-6 mt-2">
                            <div class="row">
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <span class="material-icons align-middle">date_range</span>
                                    <span class="">From <strong>
                                    <?php
                                        $fr_date = date_create($row['fromdate'])->setTimezone(new DateTimeZone("Asia/Kolkata"))->format("d/m/Y g:i:s");
                                        echo $fr_date; 
                                    ?></strong><br/> To
                                    <strong>
                                    <?php $to_date = date_create($row['todate'])->setTimezone(new DateTimeZone("Asia/Kolkata"))->format("d/m/Y g:i:s");
                                        echo $to_date;
                                 
                                    ?>
                                    </strong></span>
                                </div>
                                <form class="col-md-6 col-sm-6 col-xs-12 text-right" action="ViewQuiz.php" method="POST">
                                <input type="hidden" name="qid" value="<?php echo $row['qid'].",".$totalusers; ?>" />
                                <span ><button class="btn btn-sm btn-outline-info"><span class="material-icons align-middle">analytics</span> Details</button></span>
                                </form>
                            </div>
                </div>
            </div>
        </div>
</div>      

                <?php
                                        }
                                }
                                else
                                {

                                }
                        }

                        $conn->close();
                ?>


        </div>
    </div>
    </div>
    </div> <!--END OF main-panel class-->
    </div><!--END OF wrapper class-->
    </body>
    </html><?php
