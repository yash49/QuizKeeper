<?php
    require_once 'sidebar.php';
    require_once 'backend/connector.php';
    //require_once 'QuestionsRender.php';
    renderSideBar("hostQuiz");
?>
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
                                Qdata.quiz_title = "<?php echo $questionsDetails['details']['title']; ?>";
                                Qdata.quiz_desc = "<?php echo $questionsDetails['details']['description']; ?>";
                                Qdata.quiz_start_date = "<?php echo $questionsDetails['details']['fromdate']; ?>";
                                Qdata.quiz_end_date = "<?php echo $questionsDetails['details']['todate']; ?>";
                                Qdata.quiz_shuffle = <?php echo $questionsDetails['details']['shuffle']?"true":"false"; ?>;
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
                                console.log(Qdata);
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
                                    <a class="nav-link" id="quiz_questions_tab"  onclick="if(!editMode)validateQuizDetails()"  href="#quiz_questions" data-toggle="tab">
                                        <i class="material-icons">contact_support</i> Questions
                                        <div class="ripple-container"></div>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="quiz_participants_tab" onclick="if(!editMode)validateQuizQuestions()" href="#quiz_participants" data-toggle="tab">
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
                                    <input type="text" required name="quiz_title" placeholder="Quiz Title"  class="form-control">
                                </div>
                                <div class="col-md-6 mb-4 order-md-2 order-sm-1 order-xs-1 col-sm-12">
                                    <textarea required name="quiz_desc" placeholder="Quiz Description" class="form-control"></textarea>
                                </div>

                                <div class="col-md-6 mb-4 order-md-1 order-sm-2 order-xs-2 col-sm-12">
                                    <input type="datetime-local" required name="quiz_start_date" placeholder="Start At " class="form-control">
                                </div>

                                <div class="col-md-6 mb-4 order-md-3 order-sm-3 order-xs-3 col-sm-12">
                                    <input type="datetime-local" required name="quiz_end_date" placeholder="Ends At " class="form-control">
                                </div>


                                <div class="radio col-md-6 order-md-4 mt-2 order-sm-4 order-xs-4 col-sm-12">
                                        <label class="fs-5 text-dark">
                                            <input type="checkbox" class="fs-5" name="quiz_shuffle">
                                            Shuffle Questions?
                                        </label>
                                </div>
                                <div class="col-6 order-5 text-right">
                                    <button class="mr-4 btn btn-info" onclick="if(!editMode)validateQuizDetails()"> Next </button>
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
                                        <button class="mr-4 btn btn-info" onclick="if(!editMode)validateQuizQuestions()"> Next </button>
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
                                    <button onclick="sendQReq()" class=" btn btn-info float-right" style="display: none" id="final_save_btn"> Host Quiz </button>
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

<script src="js/QuestionRenderer.js"></script>
<script>
    function validateQuizDetails(){
        if (!document.getElementsByName("quiz_title")[0].validity.valid || !document.getElementsByName("quiz_desc")[0].validity.valid ||
            !document.getElementsByName("quiz_end_date")[0].validity.valid || !document.getElementsByName("quiz_start_date")[0].validity.valid){
            document.getElementById("quiz_questions_tab").href = "";
            $.notify({message: "Please fill all details properly"}, {type: 'warning', timer: 1000, placement: {from: 'bottom', align: 'right'}});
        }
        else{

            Qdata.quiz_title = document.getElementsByName("quiz_title")[0].value;
            Qdata.quiz_desc = document.getElementsByName("quiz_desc")[0].value;
            Qdata.quiz_shuffle = document.getElementsByName("quiz_shuffle")[0].checked?1:0;
            console.log(document.getElementsByName("quiz_start_date")[0].value);
            Qdata.quiz_start_date = new Date(document.getElementsByName("quiz_start_date")[0].value).getTime()/1000;
            Qdata.quiz_end_date = new Date(document.getElementsByName("quiz_end_date")[0].value).getTime()/1000;

            document.getElementById("quiz_questions_tab").href = "#quiz_questions";
        }

    }
    function validateQuizQuestions(){
        if(Qdata.questionData.length === 0){
            document.getElementById("quiz_participants_tab").href = "";
            $.notify({message: "Please add question(s)"}, {type: 'warning', timer: 1000, placement: {from: 'bottom', align: 'right'}});
        }
        else{
            document.getElementById("quiz_participants_tab").href = "#quiz_participants";
            document.getElementById('quiz_participants_tab').click();
        }
    }


    function addOptions(type){

        let handler_form = document.getElementById(type=='radio'?'radio_q_form':'check_q_form');

        let count = document.getElementById(type=='radio'?"radio_q_options_count":"check_q_options_count").value;

        let optionsPanel = document.getElementById(type=='radio'?"radio_q_options_panel":"check_q_options_panel");
        let opTrack = optionsPanel.childElementCount;

        for(let i = 0; i < count; i++,opTrack++){
            let optionContainer = document.createElement("div");
            optionContainer.name = type=='radio'?"radio_q_option":"check_q_option";
            optionContainer.classList.add("col-md-12","col-sm-12","m-3");

            let inputField = document.createElement("input");
            inputField.required = true;
            inputField.name = (type=='radio'?'radio_q_option':'check_q_option');
            inputField.placeholder = "Option content";
            inputField.classList.add("form-control");

            let trueAnsCheck = document.createElement("input");
            trueAnsCheck.type = type;
            trueAnsCheck.required = true;
            trueAnsCheck.name = (type=='radio'?'radio_q_option_chk':'check_q_option_chk');
            trueAnsCheck.classList.add("radio","p-2");

            optionContainer.innerHTML = "Option "+(opTrack+1)+":";
            optionContainer.appendChild(inputField);
            optionContainer.innerHTML += "<label>True Answer:&nbsp</label>";
            optionContainer.appendChild(trueAnsCheck);

            optionsPanel.appendChild(optionContainer);
        }
        //handler_form.insertBefore(optionsPanel,handler_form.childNodes[handler_form.childNodes.length-2]);

    }

    function addRadioQuestion(){
        if(!document.getElementById("radio_q_question").validity.valid ||
            !document.getElementById("radio_q_marks").validity.valid ||
            !document.getElementById("radio_q_question").validity.valid ){
            return;
        }

        let question = document.getElementById("radio_q_question").value;
        let type = "radio";
        let mark = document.getElementById("radio_q_marks").value;
        let options = document.getElementsByName("radio_q_option");
        let trueOptions = document.getElementsByName("radio_q_option_chk");

        let isTrueChecked = false;
        for(let i = 0; i < options.length; i++){
            isTrueChecked = isTrueChecked || trueOptions[i].checked;
        }
        if(!isTrueChecked)return;

        let answer = "";
        let optionsData = "";


        for(let i = 0; i < options.length; i++){
            if(trueOptions[i].checked){
                answer = options[i].value;
            }
            optionsData += options[i].value+(i < options.length-1?",":"");
        }
        let Q = {
            question:question,
            answer:answer,
            type:type,
            options:optionsData,
            mark:mark
        };
        Qdata.questionData.push(Q);
        document.getElementById("radio_q_question").value = "";
        document.getElementById("radio_q_marks").value = "";

        document.getElementById("radio_q_form").removeChild(document.getElementById("radio_q_options_panel"));
        $.notify({message: "Question added"}, {type: 'success', timer: 1000, placement: {from: 'bottom', align: 'right'}});

        renderRadioQuestions(document.getElementById("previewContainer"),question, optionsData.split(","), answer, Qdata.questionData.length);
    }

    function addCheckBoxQuestion(){
        if(!document.getElementById("check_q_question").validity.valid ||
            !document.getElementById("check_q_marks").validity.valid ||
            !document.getElementById("check_q_question").validity.valid ){
            return;
        }

        let question = document.getElementById("check_q_question").value;
        let type = "checkbox";
        let mark = document.getElementById("check_q_marks").value;
        let options = document.getElementsByName("check_q_option");
        let trueOptions = document.getElementsByName("check_q_option_chk");

        let isTrueChecked = false;
        for(let i = 0; i < options.length; i++){
            isTrueChecked = isTrueChecked || trueOptions[i].checked;
        }
        if(!isTrueChecked)return;

        let answer = "";
        let optionsData = "";


        for(let i = 0; i < options.length; i++){
            if(trueOptions[i].checked){
                answer += options[i].value+",";
            }
            optionsData += options[i].value+(i < options.length-1?",":"");
        }
        answer = answer.slice(0,answer.length-1);

        let Q = {
            question:question,
            answer:answer,
            type:type,
            options:optionsData,
            mark:mark
        };
        Qdata.questionData.push(Q);
        document.getElementById("check_q_question").value = "";
        document.getElementById("check_q_marks").value = "";

        document.getElementById("check_q_form").removeChild(document.getElementById("check_q_options_panel"));
        $.notify({message: "Question added"}, {type: 'success', timer: 1000, placement: {from: 'bottom', align: 'right'}});

        renderCheckboxQuestions(document.getElementById("previewContainer"),question, optionsData.split(","), answer, Qdata.questionData.length);
    }

    function addTextInputQuestion(inType){

            if(!document.getElementById(inType+"_text_q_question").validity.valid ||
                !document.getElementById(inType+"_text_q_marks").validity.valid){
                if(inType == 'strict'){
                    if(!document.getElementById("strict_text_q_answer").validity.valid) return;
                }
                return;
            }



        let question = document.getElementById(inType+"_text_q_question").value;
        let type = inType+"_text";
        let mark = document.getElementById(inType+"_text_q_marks").value;
        let answer = (inType == "strict")?document.getElementById(inType+"_text_q_answer").value:"";



        let Q = {
            question:question,
            answer:answer,
            type:type,
            mark:mark
        };
        Qdata.questionData.push(Q);
        document.getElementById(inType+"_text_q_question").value = "";
        document.getElementById(inType+"_text_q_marks").value = "";
        if(inType == "strict")document.getElementById("strict_text_q_answer").value = "";

        $.notify({message: "Question added"}, {type: 'success', timer: 1000, placement: {from: 'bottom', align: 'right'}});
        renderInputQuestions(document.getElementById("previewContainer"),question, answer, Qdata.questionData.length);
    }


    function addEmail(emailAddress = ""){
        if(emailAddress === "" && !document.getElementById("email_inp").validity.valid && isValidEmail(document.getElementById("email_inp").value))
            return;

        if(emailAddress === "") emailAddress = document.getElementById("email_inp").value;

        emailList.push(document.getElementById("email_inp").value);
        let emailDiv = document.createElement("div");
        emailDiv.id = "email_"+emailList.length;
        emailDiv.classList.add("d-flex","p-2","mt-3");

        let emailtext = document.createElement("span");
        emailtext.classList.add("mr-auto","fs-5","align-middle");
        emailtext.name = "email_data";
        emailtext.innerText = emailAddress;

        let removeBtn = document.createElement("button");
        removeBtn.classList.add("btn-sm", "btn-outline-danger","ml-auto","mt-auto");
        removeBtn.innerText = "Remove";

        removeBtn.onclick = ()=>{
            emailList.splice(emailList.indexOf(emailtext.innerText),1);
            emailDiv.remove();
            console.log(emailList);
            if(emailList.length === 0){
                document.getElementById("noemail").style.display = "block";
                document.getElementById("final_save_btn").style.display = "none";
            }

        };

        emailDiv.appendChild(emailtext);
        emailDiv.appendChild(removeBtn);
        document.getElementById("emailContainer").appendChild(emailDiv);
        document.getElementById("noemail").style.display = "none";
        document.getElementById("final_save_btn").style.display = "block";
        document.getElementById("email_inp").value = "";
    }

    function addEmailBatch(){
        if(!document.getElementById("bunch_emailbox").validity.valid) return;
        let emailsArray = document.getElementById("bunch_emailbox").value.split(',');

        let invalidAddresses = "";
        for(let i = 0; i < emailsArray.length; i++){
            if(isValidEmail(emailsArray[i].trim())){
                addEmail(emailsArray[i]);
            }
            else{
                invalidAddresses+="<br>"+emailsArray[i];
            }
        }
        document.getElementById("bunch_emailbox").value = "";
        if(invalidAddresses === ""){
            $.notify({message: "Participants added successfully"}, {type: 'success', timer: 1000, placement: {from: 'bottom', align: 'right'}});
        }
        else $.notify({message: "Following addresses are invalid:"+invalidAddresses}, {type: 'danger', timer: 3000, placement: {from: 'bottom', align: 'center'}});


    }
    function isValidEmail(email) {
        var emailRegex = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
        return typeof email === 'string'
            && emailRegex.test(email)
    }

    function sendAddQrequest(url, data, callback){
        console.log(data);
    $.ajax({
        data:data,
        url:url,
        method:"POST",
        success:function (response){
            if(response.result == "Success"){
                callback(response,"success");
            }
            else{
                callback(response,"danger");
            }
        }

    });
}


function sendQReq(){
        Qdata.email = emailList;
        sendAddQrequest('backend/QuizSave.php',Qdata,(response,type)=>{
        $.notify({message: response.message}, {type: type, timer: 2000, placement: {from: 'top', align: 'right'}});
            if(type !== "danger"){
                document.getElementById("quiz_creds_key").innerText = response.quizKey;
                document.getElementById("quiz_creds_pass").innerText = response.quizPass;
                $("#quiz_creds_modal").modal("toggle");
            }
    }
    );

}

</script>
</body>
</html>