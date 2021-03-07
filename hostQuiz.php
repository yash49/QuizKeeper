<?php
    require_once 'sidebar.php';
    require_once 'backend/connector.php';
    //require_once 'QuestionsRender.php';
    renderSideBar("hostQuiz");
?>
<script src="js/QuestionRenderer.js"></script>
<div class="content ml-4 mr-4">
    <script>let Qdata = {questionData:[]}; let emailList = []; editMode = false;</script>
        <?php
                function getQuestionTableFromInt($s){
                    $a = array(0=>array("TextQns","tqid"), 1=>array("TextQns","tqid"), 2=>array("CheckboxQns","cbqid"), 3=> array("MCQ","mid"));
                    return $a[$s];
                }
                function getQuestionTypeInt($s){
                    $a = array(3=>"radio",2=>"checkbox",1=>"loose_text",0=>"strict_text");
                    return $a[$s];
                }

                $editMode = false;
                $qid = -1;
                if(isset($_POST['mode']) && $_POST['mode'].strpos($_POST['mode'],"ed") != false) {
                    $editMode = true;
                    $qid = substr($_POST['mode'], 2);

                    $query = "SELECT * FROM Quiz WHERE qid = ? AND uid = ?";
                    $stmt = $conn->prepare($query);
                    $stmt->bind_param("ii", $qid, $_SESSION['uid']);

                    $quizDetails = null;
                    if ($stmt->execute() === TRUE) {
                        $result = $stmt->get_result();
                        $quizDetails = $result->fetch_assoc();

                        $query = "SELECT * FROM Questions WHERE qid = ?";
                        $qst_stmt = $conn->prepare($query);
                        $qst_stmt->bind_param("i", $qid);

                        $questionsDetails = array();
                        $questionsDetails['details']= $quizDetails;
                        if ($qst_stmt->execute() === TRUE) {
                            $result = $qst_stmt->get_result();
                            while ($qst_row = $result->fetch_assoc()) {
                                $temp_q = array();
                                $q_table = getQuestionTableFromInt($qst_row['type']);

                                $query = "SELECT * FROM " . $q_table[0] . " WHERE " . $q_table[1] . " = ?";
                                $qt_stmt = $conn->prepare($query);
//                            if(!$qt_stmt)echo $query."<br>";

                                $qt_stmt->bind_param("i", $qst_row['xid']);
                                if ($qt_stmt->execute() === TRUE) {
                                    $question_result = $qt_stmt->get_result();
                                    $temp_q = $question_result->fetch_assoc();
                                    $temp_q['mark'] = $qst_row['marks'];
                                    $temp_q['qstid'] = $temp_q[$q_table[1]];
                                    $temp_q['question'] = $temp_q['qns'];
                                    $temp_q['type'] = getQuestionTypeInt($qst_row['type']);
                                    if(isset($temp_q['correctans']))
                                        $temp_q['answer'] = $temp_q['correctans'];
                                    else
                                        $temp_q['answer'] = $temp_q['ans'];
                                    array_push($questionsDetails, $temp_q);
                                }
                            }

                            /*------------Fetch all Questions-----------------*/

                            ?>
                            <script>
                                editMode = true;
                                Qdata.quiz_id = "<?php echo $questionsDetails['details']['qid']; ?>";
                                Qdata.quiz_title = "<?php echo $questionsDetails['details']['title']; ?>";
                                Qdata.quiz_desc = "<?php echo $questionsDetails['details']['description']; ?>";
                                Qdata.quiz_start_date = "<?php echo $questionsDetails['details']['fromdate']; ?>";
                                Qdata.quiz_end_date = "<?php echo $questionsDetails['details']['todate']; ?>";
                                Qdata.quiz_shuffle = <?php echo $questionsDetails['details']['shuffle']?"true":"false"; ?>;
                                Qdata.quiz_key = <?php echo $questionsDetails['details']['quizkey']; ?>;
                                Qdata.quiz_password = <?php echo $questionsDetails['details']['password']; ?>;
                                let temp = {};
                                <?php foreach ($questionsDetails as $key=>$question){

                                    if($key !== "details"){
                                ?>
                                    temp = {};
                                    temp.question = "<?php echo $question['question'];?>";
                                    temp.answer = "<?php echo $question['answer'];?>";
                                    temp.options = "<?php if(isset($question['options']))echo $question['options'];?>";
                                    temp.mark = "<?php echo $question['mark'];?>";
                                    temp.type = "<?php echo $question['type'];?>";
                                    Qdata.questionData.push(temp);
                                <?php } } ?>
                                console.log("from php:",Qdata);
                            </script>
    <?php
                        }
                    }
                }

                        ?>

    <div class="row justify-content-center">
        <div class="col-lg-10 col-md-10 col-sm-12">
            <div class="card">
                <div class="card-header card-header-tabs card-header-primary">
                    <div class="nav-tabs-navigation">
                        <div class="nav-tabs-wrapper">
                            <span class="nav-tabs-title fs-5 fw-bold">Host QUIZ:</span>
                            <ul class="nav nav-tabs border-0" data-tabs="tabs">
                                <li class="nav-item">
                                    <a class="nav-link active" href="#quiz_details" data-toggle="tab">
                                        <i class="material-icons">info</i> Information
                                        <div class="ripple-container"></div>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <!--"-->
                                    <a class="nav-link" id="quiz_questions_tab"  onclick="validateQuizDetails()"  href="#quiz_questions" data-toggle="tab">
                                        <i class="material-icons">contact_support</i> Questions
                                        <div class="ripple-container"></div>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="quiz_participants_tab" onclick="validateQuizQuestions()" href="#quiz_participants" data-toggle="tab">
                                        <i class="material-icons">assignment_ind</i>Participants
                                        <div class="ripple-container"></div>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="tab-content">
                        <div class="tab-pane active" id="quiz_details">
                            <form class="row p-3" id="quiz_details_form" onsubmit="event.preventDefault();document.getElementById('quiz_questions_tab').click()">
                                <div class="col-md-6 mb-4 order-md-0 order-sm-0 order-xs-0 col-sm-12">
                                    <input type="text" value="<?php if($editMode) echo $quizDetails['title']; ?>" required name="quiz_title" placeholder="Quiz Title"  class="form-control">
                                </div>
                                <div class="col-md-6 mb-4 order-md-2 order-sm-1 order-xs-1 col-sm-12">
                                    <textarea required name="quiz_desc" placeholder="Quiz Description" class="form-control"><?php if($editMode) echo $quizDetails['description']; ?></textarea>
                                </div>

                                <div class="col-md-6 mb-4 order-md-1 order-sm-2 order-xs-2 col-sm-12">
                                    <input type="datetime-local" value="<?php if($editMode) echo (new DateTime($quizDetails['fromdate']))->setTimezone(new DateTimeZone("Asia/Kolkata"))->format('Y-m-d\TH:i:s'); ?>" required name="quiz_start_date" placeholder="Start At " class="form-control">
                                </div>

                                <div class="col-md-6 mb-4 order-md-3 order-sm-3 order-xs-3 col-sm-12">
                                    <input type="datetime-local" value="<?php if($editMode) echo (new DateTime($quizDetails['todate']))->setTimezone(new DateTimeZone("Asia/Kolkata"))->format('Y-m-d\TH:i:s'); ?>" required name="quiz_end_date" placeholder="Ends At " class="form-control">
                                </div>


                                <div class="radio col-md-6 order-md-4 mt-2 order-sm-4 order-xs-4 col-sm-12">
                                        <label class="fs-5 text-dark">
                                            <input type="checkbox" <?php if($editMode) echo ($quizDetails['shuffle'] == 1?"checked":""); ?> class="fs-5" name="quiz_shuffle">
                                            Shuffle Questions?
                                        </label>
                                </div>
                                <div class="col-6 order-5 text-right">
                                    <button class="mr-4 btn btn-info" onclick="validateQuizDetails()"> Next </button>
                                </div>
                            </form>

                        </div>

                        <div class="tab-pane" id="quiz_questions">
                            <div class="row justify-content-center">
                                <div class="col-md-6 col-sm-6 col-xs-6">
                                    <div>
                                        <div id="previewContainer"></div>
                                        <img id="nopreview" src="assets/img/preview_holder.svg" width="350px" height="350px">
                                    </div>
                                </div>
                                <div class="col-md-4 col-sm-4 col-xs-4">
                                    <div class="list-group">
                                        <h4 class="list-group-item disabled list-group-item-action bg-info text-white">
                                            Questions Type
                                        </h4>
                                        <a href="#" data-toggle="modal" data-target="#radio_modal" class="list-group-item list-group-item-action p-3">
                                            <i class="material-icons">radio_button_checked</i> Single Choice Question (Radio buttons)
                                        </a>
                                        <a href="#"  data-toggle="modal" data-target="#checkbox_modal" class="list-group-item list-group-item-action p-3">
                                            <i class="material-icons">check_circle</i> Multiple choice Question (Checkboxes)
                                        </a>
                                        <a href="#" data-toggle="modal" data-target="#strict_text_modal" class="list-group-item list-group-item-action p-3">
                                            <i class="material-icons">article</i> Strict Text Input (Strictly match answer)
                                        </a>
                                        <a href="#" data-toggle="modal" data-target="#loose_text_modal" class="list-group-item list-group-item-action p-3">
                                            <i class="material-icons">assignment_turned_in</i> Loose Text Input (Manual evaluation)
                                        </a>
                                    </div>
                                    <div class="col-6 order-5 text-right">
                                        <button class="mr-4 btn btn-info" onclick="validateQuizQuestions()"> Next </button>
                                    </div>
                                </div>

                            </div>

                        </div>

                        <div class="tab-pane" id="quiz_participants">
                            <div class="row justify-content-center">
                                <div class="col-md-8 col-sm-8 col-xs-12 row justify-content-center">
                                    <input type="email" placeholder="email address" required  class="mr-auto form-control col-md-12 col-sm-12 col-xs-12" id="email_inp" name="email_inp">
                                    <input type="button" class="btn-sm btn-success ml-3 col-md-3 col-sm-10 col-xs-10 mt-2" onclick="addEmail()" id="add_email" name="add_email" value="add email address">
                                    <input type="button" data-toggle="modal" data-target="#email_modal" class="mt-2 btn-sm btn-outline-success ml-3 col-md-5 col-sm-10 col-xs-10" id="add_emailbatch" name="add_emailbatch" value="add batch of email addresses">
                                </div>

                                <div id="emailContainer" class="col-md-10 col-sm-12 col-xs-12"></div>

                                <div class="col-md-12 col-sm-12 col-xs-12 text-center" id="noemail">
                                    <img src="assets/img/email_holder.svg" width="250px" height="250px">
                                </div>
                                <div class="col-md-12 col-sm-12 col-xs-12 mt-3">
                                    <button onclick="sendQReq()" class=" btn btn-info float-right" id="final_save_btn">  Host Quiz </button>
                                    <div style="position:absolute; display: none; margin-bottom:-10px; right:130px;bottom:15px" id="loadbar" class="ml-2 spinner-border text-success"></div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!--------------------------------------------------MODALS------------------------------------------------------------->

    <!--------------------------------------------------Radio MODAL------------------------------------------------------------->

    <div class="modal fade w-90" id="radio_modal" tabindex="-1" role="dialog" aria-labelledby="radio_modal_title" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="radio_modal_title">Single Choice Question</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="container-fluid">
                   <form id="radio_q_form" onsubmit="event.preventDefault();" class="row justify-content-center">

                       <div class="col-md-12 col-sm-12 m-3">
                           <input type="text" id="radio_q_question" placeholder="Question" required class="form-control">
                       </div>

                       <div class="col-md-12 col-sm-12 m-3">
                           <input type="number" min="0" id="radio_q_marks" placeholder="Mark" required class="form-control">
                       </div>

                       <div class="col-md-12 col-sm-12 m-3">
                           <input type="number" min="2" max="15" id="radio_q_options_count" value="4" required class="form-control">
                           <input type="button" class="btn btn-info" value="Add Options" onclick="addOptions('radio')">
                       </div>
                        <div id="radio_q_options_panel" class="row justify-content-start"></div>
                       <div class="modal-footer">
                           <button class="btn btn-primary" onclick="addRadioQuestion()">Add Question</button>
                           <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                       </div>
                   </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!--------------------------------------------------Checkbox MODAL------------------------------------------------------------->

    <div class="modal fade w-90" id="checkbox_modal" tabindex="-1" role="dialog" aria-labelledby="checkbox_modal_title" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="checkbox_modal_title">Checkbox Choice Question</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="container-fluid">
                        <form id="check_q_form" onsubmit="event.preventDefault();" class="row justify-content-center">

                            <div class="col-md-12 col-sm-12 m-3">
                                <input type="text" id="check_q_question" placeholder="Question" required class="form-control">
                            </div>

                            <div class="col-md-12 col-sm-12 m-3">
                                <input type="number" min="0" id="check_q_marks" placeholder="Mark" required class="form-control">
                            </div>

                            <div class="col-md-12 col-sm-12 m-3">
                                <input type="number" min="2" max="15" id="check_q_options_count" value="4" required class="form-control">
                                <input type="button" class="btn btn-info" value="Add Options" onclick="addOptions('checkbox')">
                            </div>
                            <div id="check_q_options_panel" class="row justify-content-start"></div>
                            <div class="modal-footer">
                                <button class="btn btn-primary" onclick="addCheckBoxQuestion()">Add Question</button>
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!--------------------------------------------------Strict MODAL------------------------------------------------------------->

    <div class="modal fade w-90" id="strict_text_modal" tabindex="-1" role="dialog" aria-labelledby="strict_text_modal_title" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="strict_text_modal_title">Strict Text Input Question</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="container-fluid">
                        <form id="strict_text_q_form" onsubmit="event.preventDefault();" class="row justify-content-center">

                            <div class="col-md-12 col-sm-12 m-3">
                                <input type="text" id="strict_text_q_question" placeholder="Question" required class="form-control">
                            </div>

                            <div class="col-md-12 col-sm-12 m-3">
                                <input type="number" min="0" id="strict_text_q_marks" placeholder="Mark" required class="form-control">
                            </div>

                            <div class="col-md-12 col-sm-12 m-3">
                                <input type="text" id="strict_text_q_answer" placeholder="Type Answer: (Case Sensitive)" required class="form-control">
                            </div>

                            <div class="modal-footer">
                                <button class="btn btn-primary" onclick="addTextInputQuestion('strict')">Add Question</button>
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!--------------------------------------------------loose MODAL------------------------------------------------------------->

    <div class="modal fade w-90" id="loose_text_modal" tabindex="-1" role="dialog" aria-labelledby="loose_text_modal_title" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="loose_text_modal_title">Normal Text Input Question (Manual Evaluation)</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="container-fluid">
                        <form id="loose_text_q_form" onsubmit="event.preventDefault();" class="row justify-content-center">

                            <div class="col-md-12 col-sm-12 m-3">
                                <input type="text" id="loose_text_q_question" placeholder="Question" required class="form-control">
                            </div>

                            <div class="col-md-12 col-sm-12 m-3">
                                <input type="number" min="0" id="loose_text_q_marks" placeholder="Mark" required class="form-control">
                            </div>

                            <div class="modal-footer">
                                <button class="btn btn-primary" onclick="addTextInputQuestion('loose')">Add Question</button>
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!--------------------------------------------------EMAIL MODAL------------------------------------->
    <div class="modal fade w-90" id="email_modal" tabindex="-1" role="dialog" aria-labelledby="email_modal_title" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="email_modal_title">Add Bunch of email addresses</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="container-fluid">

                            <div class="m-3">
                                <textarea  id="bunch_emailbox" placeholder="Enter multiple email addresses ( Comma , separated)" required class="form-control"></textarea>
                                <div class="alert alert-warning mt-2">Make sure each email address is in valid format</div>
                            </div>


                            <div class="modal-footer">
                                <button class="btn btn-primary" onclick="addEmailBatch()">Add batch</button>
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <!--------------------------------------------------CRED MODAL------------------------------------->
    <div class="modal fade w-90" id="quiz_creds_modal" tabindex="-1" role="dialog" aria-labelledby="quiz_creds_title" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="quiz_creds_title">Save credentials of your QUIZ</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="container-fluid text-center">
                        <div>
                            <img src="assets/img/security.gif" width="250px" height="250px">
                        </div>
                        <div class="m-3">
                            Key : <div class="alert alert-success mt-2" id="quiz_creds_key"></div>
                            Password : <div class="alert alert-success mt-2" id="quiz_creds_pass"></div>
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal" onclick="$('#quiz_creds_modal').modal('toggle'); window.location.href='hostQuiz.php';">Close</button>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
</div> <!--END OF main-panel class-->
</div><!--END OF wrapper class-->


<script src="js/UIController.js"></script>

</body>
</html>