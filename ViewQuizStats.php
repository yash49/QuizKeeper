
<?php

require_once 'sidebar.php';
renderSideBar("hostedQuizStats");
require_once 'backend/connector.php';
$qid=$_POST['qid'];
function getAttemptedUsers($qid,$conn)
{
    $stmt=$conn->prepare("SELECT count(QuizAttempt.qaid) as ucount FROM Quiz,QuizAttempt where QuizAttempt.qid=Quiz.qid and Quiz.qid=? GROUP BY Quiz.qid");
    $stmt->bind_param('i',$qid);
    if($stmt->execute()==TRUE)
    {
        $result = $stmt->get_result();
        while($row=$result->fetch_assoc())
        {
            if ($row['ucount']>0)
                return $row['ucount'];
            else
                return 0;
        }
        return 0;
    }
    else
        return 0;
}
function getQuestionTableFromInt($s){
    $a = array(0=>array("TextQns","tqid"), 1=>array("TextQns","tqid"), 2=>array("CheckboxQns","cbqid"), 3=> array("MCQ","mid"));
    return $a[$s];
}
function getQuestionTypeInt($s){
    $a = array(3=>"radio",2=>"checkbox",1=>"loose_text",0=>"strict_text");
    return $a[$s];
}

function getUserAnswer($qnsid, $sort,$uid){
    global $conn;
    $stmt = $conn->prepare("SELECT * FROM Answers WHERE qnsid = ? AND userid = ?");
    $stmt->bind_param('ii', $qnsid, $uid);

    if ($stmt->execute() == TRUE) {
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();

        if($sort == true){
            $ansArray = explode(",",$row['ans']);
            sort($ansArray);
            $row['ans'] = implode(",",$ansArray);
        }
        return $row;
    }
    return "";
}
$stmt=$conn->prepare("SELECT sum(marks) as total FROM Questions WHERE qid = '".$qid."';");
$totalmarks=0;
if($stmt->execute()==TRUE)
    {
        $result = $stmt->get_result();
        if($row=$result->fetch_assoc())
        {
            $totalmarks= $row['total'];
        }

    }

function getTotalQuestions($qid,$conn)
{
    $stmt=$conn->prepare("SELECT count(qnsid) as qcount FROM Questions where qid=?");
    $stmt->bind_param('i',$qid);
    if($stmt->execute()==TRUE)
    {
        $result = $stmt->get_result();
        while($row=$result->fetch_assoc())
        {
            return $row['qcount'];
        }
        return -1;
    }
    else
        return -1;
}
?>
<div class="content ml-3 mr-3">
    <div class="row justify-content-center">

        <div class="col-lg-4 col-md-4 col-sm-12">
            <div class="card card-stats">
                <div class="card-header card-header-success card-header-icon p-2">
                    <div class="card-icon">
                        <i class="material-icons">quiz</i>
                    </div>
                    <p class="card-category">Questions</p>
                    <h3 class="card-title">
                        <?php echo getTotalQuestions($qid,$conn); ?>
                    </h3>
                </div>
            </div>
        </div>



        <div class="col-lg-4 col-md-4 col-sm-12">
            <div class="card card-stats">
                <div class="card-header card-header-success card-header-icon p-2">
                    <div class="card-icon">
                        <i class="material-icons">people</i>
                    </div>
                    <p class="card-category">Total Users Attempted</p>
                    <h3 class="card-title">
                        <?php $totalUsers = getAttemptedUsers($qid,$conn); echo $totalUsers; ?>
                    </h3>
                </div>
            </div>
        </div>

        <div class="col-lg-4 col-md-4 col-sm-12">
            <div class="card card-stats">
                <div class="card-header card-header-success card-header-icon p-2">
                    <div class="card-icon">
                        <i class="material-icons">add_task</i>
                    </div>
                    <p class="card-category">Total Marks</p>
                    <h3 class="card-title">
                        <?php echo $totalmarks ?>
                    </h3>
                </div>
            </div>
        </div>

    </div>

    <div class="row justify-content-center mt-5">
        <div class="card">
            <div class="card-header card-header-info">
                <h4 class="card-title">Quiz Details</h4>
                <p class="card-category">Check Attempted users details</p>
            </div>
            <div class="card-body table-responsive">
                <table class="table table-striped table-hover">
                    <thead class="text-info">
                    <tr><th>User name</th>
                        <th>Email</th>
                        <th>Mobile</th>
                        <th>Marks</th>
                        <th>More</th>
                    </tr></thead>
                    <tbody>
                    <?php
                    //$result=$conn->query("SELECT * FROM Users,QuizAttempt where Users.uid=QuizAttempt.uid;");
                    $result=$conn->query("SELECT * FROM Users,QuizAttempt where Users.uid=QuizAttempt.uid and qid=".$qid.";");

                        while($row=$result->fetch_assoc())
                        {
                        ?>
                        <tr>
                            <td><?php echo $row['name'];?></td>
                            <td><?php  echo $row['email'];?></td>
                            <td><?php  echo $row['mobile'];?></td>
                            <td><?php


                                $questionStmt = $conn->prepare("SELECT * FROM Questions WHERE qid=?");
                                $questionStmt->bind_param('i',$qid);
                                $obtainedMarks = 0;
                                $totalMarks = 0;
                                $questionsDetails = array();
                                if($questionStmt->execute() == TRUE){

                                    $q_result = $questionStmt->get_result();

                                    while($q_row = $q_result->fetch_assoc()){
                                        $tempQ = $q_row;
                                        $totalMarks += $q_row['marks'];

                                        $q_table = getQuestionTableFromInt($q_row['type']);

                                        $query = "SELECT * FROM " . $q_table[0] . " WHERE " . $q_table[1] . " = ?";
                                        $qt_stmt = $conn->prepare($query);

                                        $qt_stmt->bind_param("i", $q_row['xid']);
                                        if ($qt_stmt->execute() === TRUE) {
                                            $singleResult = $qt_stmt->get_result();
                                            while($singleQ = $singleResult->fetch_assoc()){
                                                if(isset($singleQ['correctans'])) {
                                                    $ansArray = explode(",",$singleQ['correctans']);
                                                    sort($ansArray);
                                                    $tempQ['true_answer'] = implode(",",$ansArray);
                                                }
                                                else
                                                    $tempQ['true_answer'] = ($q_row['type'] == 0)?$singleQ['ans']:"<span class='badge badge-info'>MANUAL EVALUATION</span>";

                                                $ans = getUserAnswer($q_row['qnsid'],($q_row['type'] == 2)?true:false,$row['uid']);
                                                $tempQ['type'] = $q_row['type'];
                                                $tempQ['mark'] = $q_row['marks'];

                                                $tempQ['user_answer'] = $ans['ans'];
                                                $tempQ['question'] = $singleQ['qns'];

                                                $tempQ['ansid'] = $ans['ansid'];
                                                $tempQ['obtain_mark'] = 0;
                                                $tempQ['marks_granted'] = $ans['marks_granted'];

                                                if($tempQ['marks_granted'] == TRUE || $tempQ['user_answer'] == $tempQ['true_answer']){
                                                    $obtainedMarks += $q_row['marks'];
                                                    $tempQ['obtain_mark'] = $q_row['marks'];
                                                }

                                                if(strlen($tempQ['user_answer']) == 0) $tempQ['user_answer'] = "<span class='badge badge-info'>Not Attempted</span>";
                                                // print_r($tempQ);
                                            }
                                            array_push($questionsDetails, $tempQ);
                                        }
                                    }
                                }

                                echo $obtainedMarks."/".$totalMarks;

                                ?></td>
                                <td>
                                    <form method="post" action="ViewQuiz.php">
                                        <input type="hidden" value="<?php echo $qid.",".$totalUsers; ?>" name="qid" >
                                        <input type="hidden" value="<?php echo $row['uid'] ?>" name="uid" >
                                        <input type="hidden" value="<?php echo $row['name'] ?>" name="uname" >
                                        <input type="hidden" name="mode" value="hostMode">
                                        <span class="col-md-3 col-sm-3 col-xs-12 text-right"><button class="btn btn-sm btn-outline-info"><span class="material-icons align-middle">analytics</span> Details</button></span>
                                    </form>
                                </td>
                        </tr>


                    <?php } ?>


                    </tbody>
                </table>
            </div>
        </div>
    </div>


</div>
