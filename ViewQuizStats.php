
<?php

require_once 'sidebar.php';
renderSideBar("myResults");
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
                        <?php echo getAttemptedUsers($qid,$conn); ?>
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
                <h4 class="card-title">Evaluation Details</h4>
                <p class="card-category">Check and compare your answers with correct one</p>
            </div>
            <div class="card-body table-responsive">
                <table class="table table-striped table-hover">
                    <thead class="text-info">
                    <tr><th>User name</th>
                        <th>Email</th>
                        <th>Marks</th>
                    </tr></thead>
                    <tbody>
                    <?php
                    $result=$conn->query("SELECT * FROM Users,QuizAttempt where Users.uid=QuizAttempt.uid and qid=".$qid.";");

                        while($row=$result->fetch_assoc())
                        {
                        ?>
                        <tr>
                            <td><?php echo $row['name'];?></td>
                            <td><?php  echo $row['email'];?></td>
                            <td><?php ?></td>
                        </tr>
                    <?php } ?>


                    </tbody>
                </table>
            </div>
        </div>
    </div>


</div>
