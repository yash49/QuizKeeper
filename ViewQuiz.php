<?php
require_once 'sidebar.php';
renderSideBar((isset($_POST['mode']))?"hostedQuizStats":"myResults");


require_once 'backend/connector.php';

        function getQuestionTableFromInt($s){
            $a = array(0=>array("TextQns","tqid"), 1=>array("TextQns","tqid"), 2=>array("CheckboxQns","cbqid"), 3=> array("MCQ","mid"));
            return $a[$s];
        }
        function getQuestionTypeInt($s){
            $a = array(3=>"radio",2=>"checkbox",1=>"loose_text",0=>"strict_text");
            return $a[$s];
        }

        function getUserAnswer($qnsid, $sort){
            global $conn;
            $uid = $_SESSION['uid'];
            if(isset($_POST['mode'])){
                $uid = $_POST['uid'];
            }
            $stmt = $conn->prepare("SELECT * FROM Answers WHERE qnsid = ? AND userid = ?");
            $stmt->bind_param('ii', $qnsid, $uid);

            if ($stmt->execute() == TRUE) {
                $result = $stmt->get_result();
                $row = $result->fetch_assoc();

                if($sort == true){
                    $ansArray = explode(",",$row['ans']);
                    unset($ansArray[count($ansArray)-1]);
                    sort($ansArray);
                    return implode(",",$ansArray);
                }
                return $row['ans'];
            }
            return "";
        }

    if(!isset($_POST['qid'])){
        header("Location: http://{$_SERVER['SERVER_NAME']}/QuizKeeper/myResults.php");
    }
    $postData = explode(",",$_POST['qid']);
    $qid = $postData[0];
    $totalUsers = $postData[1];

    $stmt = $conn->prepare("SELECT Quiz.qid as qid,title,description,fromdate,todate,count(Questions.qnsid) as qcount FROM Quiz,Questions where Quiz.qid=Questions.qid and Quiz.qid=? GROUP BY Quiz.qid");
    $stmt->bind_param('i',$qid);

    if($stmt->execute()==TRUE)  {
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();

        /*-------------------FETCH QUESTIONS OF THIS QUIZ--------------------------*/

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

                        $ans = getUserAnswer($q_row['qnsid'],($q_row['type'] == 2)?true:false);
                        $tempQ['type'] = $q_row['type'];
                        $tempQ['mark'] = $q_row['marks'];

                        $tempQ['user_answer'] = $ans;
                        $tempQ['question'] = $singleQ['qns'];

                        $tempQ['obtain_mark'] = 0;
                        if($tempQ['user_answer'] == $tempQ['true_answer']){
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

?>
<div class="content ml-3 mr-3">
    <h3><span class="badge badge-success"><?php echo $row['title'];?></span></h3>
    <div class="row justify-content-center">
        <div class="col-lg-4 col-md-4 col-sm-12">
            <div class="card card-stats">
                <div class="card-header card-header-success card-header-icon p-2">
                    <div class="card-icon">
                        <i class="material-icons">quiz</i>
                    </div>
                    <p class="card-category">Questions</p>
                    <h3 class="card-title">
                       <?php echo $row['qcount']; ?>
                    </h3>
                </div>
            </div>
        </div>

        <div class="col-lg-4 col-md-4 col-sm-12">
            <div class="card card-stats">
                <div class="card-header card-header-success card-header-icon p-2">
                    <div class="card-icon">
                        <i class="material-icons">verified</i>
                    </div>
                    <p class="card-category">Marks obtained</p>
                    <h3 class="card-title">
                            <?php echo $obtainedMarks."/".$totalMarks; ?>
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
                        <?php echo $totalUsers; ?>
                    </h3>
                </div>
            </div>
        </div>
    </div>

    <div class="row justify-content-center mt-5">
        <div class="card">
            <div class="card-header card-header-info">
                <h4 class="card-title">Evaluation Details <?php if(isset($_POST['mode'])) echo " of ".$_POST['uname']; ?></h4>
                <p class="card-category">Check and compare your answers with correct one</p>
            </div>
            <div class="card-body table-responsive">
                <table class="table table-striped table-hover">
                    <thead class="text-info">
                    <tr><th>No</th>
                        <th>Question</th>
                        <th>Correct Answer</th>
                        <th>Your Answer</th>
                        <th>Marks</th>
                    </tr></thead>
                    <tbody>
                        <?php
                            $start = 1;
                            foreach ($questionsDetails as $ui_question){

                        ?>
                                <td><?php echo $start; $start++; ?></td>
                                 <td><?php echo $ui_question['question'] ?></td>
                                 <td><?php echo $ui_question['true_answer']; ?></td>
                                 <td style="background:<?php echo
                                 ($ui_question['type'] != 1)?($ui_question['user_answer'] == $ui_question['true_answer'])?'#198754':'#dc3545' : '#fd7e14'?> " class="text-white"><?php echo $ui_question['user_answer']; ?></td>
                                <td>
                                    <span class="badge <?php echo ($ui_question['obtain_mark'] == 0)?'badge-danger':'badge-success
                                '?>">
                                        <?php echo $ui_question['obtain_mark']."/".$ui_question['mark']; ?>
                                    </span>
                                    <?php if(isset($_POST['mode']) && $ui_question['type'] == 1){ ?>
                                        <button class="btn btn-sm btn-success" onclick="saveManualQuestion()">give marks</button>
                                    <?php }?>
                                </td>
                             </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>
<?php
    }
?>