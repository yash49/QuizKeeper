<?php
    require_once 'sidebar.php';
    require_once 'QuestionsRender.php';
    renderSideBar("hostQuiz");
?>
<div class="content ml-4 mr-4">
        <?php
                /*$start = 1;
                $qnsans = array(1=>array('question'=>"What is bootstrap?",
                                        'option'=>array("UI kit", "UI framework", "UI fake", "For Lazy devs.")),
                                2=>array('question'=>"What is CSS?",
                                                'option'=>array("JS", "CSS", "SV", "Yash")));
                renderRadioQuestions($qnsans, $start);

                $qnsans = array(3=>array('question'=>"QNS 1 desc?",
                                        'option'=>array("A","B","C")),
                                4=>array('question'=>"QNS 2 desc?",
                                                'option'=>array("SP")));

                renderCheckBoxQuestions($qnsans, $start);

                $qns = array(5=>array("question"=>"Kya aapke toothpaste me namak he?"));
                renderTextQuestions($qns,$start);
                
                $qns = array(6=>array("question"=>"Who is god?"));
                renderTextQuestions($qns,$start);*/
        ?>

    <div class="row justify-content-center">
        <div class="col-lg-10 col-md-10 col-sm-12">
            <div class="card">
                <div class="card-header card-header-tabs card-header-primary">
                    <div class="nav-tabs-navigation">
                        <div class="nav-tabs-wrapper">
                            <span class="nav-tabs-title">Host QUIZ:</span>
                            <ul class="nav nav-tabs border-0" data-tabs="tabs">
                                <li class="nav-item">
                                    <a class="nav-link active" href="#quiz_details" data-toggle="tab">
                                        <i class="material-icons">info</i> Information
                                        <div class="ripple-container"></div>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <!---->
                                    <a class="nav-link" id="quiz_questions_tab" onclick="validateQuizDetails()"  href="#quiz_questions" data-toggle="tab">
                                        <i class="material-icons">contact_support</i> Questions
                                        <div class="ripple-container"></div>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="#quiz_participants" data-toggle="tab">
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
                                    <button class="mr-4 btn btn-info" onclick="validateQuizDetails()"> Next </button>
                                </div>
                            </form>

                        </div>

                        <div class="tab-pane" id="quiz_questions">
                            <div class="row justify-content-center">

                                <div class="col-md-4 col-sm-4 col-xs-4">
                                    <div class="list-group">
                                        <h4 class="list-group-item disabled list-group-item-action bg-info text-white">
                                            Questions Type
                                        </h4>
                                        <a href="#" data-toggle="modal" data-target="#radio_modal" class="list-group-item list-group-item-action p-3">
                                            <i class="material-icons">radio_button_checked</i> Single Choice Question (Radio buttons)
                                        </a>
                                        <a href="#" class="list-group-item list-group-item-action p-3">
                                            <i class="material-icons">check_circle</i> Multiple choice Question (Checkboxes)
                                        </a>
                                        <a href="#" class="list-group-item list-group-item-action p-3">
                                            <i class="material-icons">article</i> Strict Text Input (Strictly match answer)
                                        </a>
                                        <a href="#" class="list-group-item list-group-item-action p-3">
                                            <i class="material-icons">assignment_turned_in</i> Loose Text Input (Manual evaluation)
                                        </a>
                                    </div>
                                    <div class="col-6 order-5 text-right">
                                        <button class="mr-4 btn btn-info" onclick="sendQReq()"> Next </button>
                                    </div>
                                </div>

                            </div>

                        </div>

                        <div class="tab-pane" id="quiz_participants">

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

</div>
</div> <!--END OF main-panel class-->
</div><!--END OF wrapper class-->


<script>
    let Qdata = {questionData:[]};
    function addOptions(type){

        let handler_form = document.getElementById(type=='radio'?'radio_q_form':'check_q_form');

        let count = document.getElementById(type=='radio'?"radio_q_options_count":"check_q_options_count").value;

        let opTrack = handler_form.childElementCount-4;
        let optionsPanel = document.createElement("div");
        optionsPanel.classList.add("row","justify-content-start");
        optionsPanel.id = "radio_q_options_panel";

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
            trueAnsCheck.type = "radio";
            trueAnsCheck.required = true;
            trueAnsCheck.name = (type=='radio'?'radio_q_option_chk':'check_q_option_chk');
            trueAnsCheck.classList.add("radio","p-2");

            optionContainer.innerHTML = "Option "+(opTrack+1)+":";
            optionContainer.appendChild(inputField);
            optionContainer.innerHTML += "<label>True Answer:&nbsp</label>";
            optionContainer.appendChild(trueAnsCheck);

            optionsPanel.appendChild(optionContainer);
        }
        handler_form.insertBefore(optionsPanel,handler_form.childNodes[handler_form.childNodes.length-2]);

    }

    function validateQuizDetails(){
        if (!document.getElementsByName("quiz_title")[0].validity.valid || !document.getElementsByName("quiz_desc")[0].validity.valid ||
            !document.getElementsByName("quiz_end_date")[0].validity.valid || !document.getElementsByName("quiz_start_date")[0].validity.valid){
            document.getElementById("quiz_questions_tab").href = "";
        }
        else{

            Qdata.quiz_title = document.getElementsByName("quiz_title")[0].value;
            Qdata.quiz_desc = document.getElementsByName("quiz_desc")[0].value;
            Qdata.quiz_shuffle = document.getElementsByName("quiz_shuffle")[0].checked;
            Qdata.quiz_start_date = new Date(document.getElementsByName("quiz_start_date")[0].value).getTime()/1000;
            Qdata.quiz_end_date = new Date(document.getElementsByName("quiz_end_date")[0].value).getTime()/1000;

            document.getElementById("quiz_questions_tab").href = "#quiz_questions";
        }

    }

    function addRadioQuestion(){
        event.preventDefault();
        let question = document.getElementById("radio_q_question").value;
        let type = "radio";
        let mark = document.getElementById("radio_q_marks").value;
        let options = document.getElementsByName("radio_q_option");
        let trueOptions = document.getElementsByName("radio_q_option_chk");

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
        $.notify({message: "Question Saved"}, {type: 'success', timer: 1000, placement: {from: 'bottom', align: 'right'}});

    }

    function sendAddQrequest(url, data, callback){
        console.log(data);
    $.ajax({
        data:data,
        url:url,
        method:"POST",
        success:function (response){
            if(response.result == "Success"){
                callback(response.message,"success");
            }
            else{
                callback(response.message,"danger");
            }
        }

    });
}


function sendQReq(){
        sendAddQrequest('backend/QuizSave.php',Qdata,(message,type)=>{
        $.notify({message: message}, {type: type, timer: 2000, placement: {from: 'top', align: 'right'}});
    });



}
</script>
</body>
</html>