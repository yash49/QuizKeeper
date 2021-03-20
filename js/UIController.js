let quizData = [];
if(typeof editMode != "undefined") {
    if (editMode) {
        Qdata.questionData.forEach((temp, index) => {
            if (temp.type == "radio")
                renderRadioQuestions(document.getElementById("previewContainer"), temp.question, temp.options.split(","), temp.answer, index + 1);
            if (temp.type == "checkbox")
                renderCheckboxQuestions(document.getElementById("previewContainer"), temp.question, temp.options.split(","), temp.answer, index + 1);
            else if (temp.type == "loose_text" || temp.type == "strict_text")
                renderInputQuestions(document.getElementById("previewContainer"), temp.question, temp.answer, index + 1);
        })

    }
}
function validateQuizDetails(){
    if (!document.getElementsByName("quiz_title")[0].validity.valid || !document.getElementsByName("quiz_desc")[0].validity.valid ||
        !document.getElementsByName("quiz_end_date")[0].validity.valid || !document.getElementsByName("quiz_start_date")[0].validity.valid || !document.getElementsByName("quiz_duration")[0].validity.valid){
        document.getElementById("quiz_questions_tab").href = "";
        $.notify({message: "Please fill all details properly"}, {type: 'warning', timer: 1000, placement: {from: 'bottom', align: 'right'}});
    }
    else{

        Qdata.quiz_title = document.getElementsByName("quiz_title")[0].value;
        Qdata.quiz_desc = document.getElementsByName("quiz_desc")[0].value;
        Qdata.quiz_shuffle = document.getElementsByName("quiz_shuffle")[0].checked?1:0;
        Qdata.quiz_start_date = new Date(document.getElementsByName("quiz_start_date")[0].value).getTime()/1000;
        Qdata.quiz_end_date = new Date(document.getElementsByName("quiz_end_date")[0].value).getTime()/1000;
        Qdata.quiz_duration = document.getElementsByName("quiz_duration")[0].value;
        document.getElementById("quiz_questions_tab").href = "#quiz_questions";
    }

}
function validateQuizQuestions(){
    if(editMode && Qdata.questionData.length > 0){
        document.getElementById("quiz_participants_tab").href = "#quiz_participants";
        document.getElementById('quiz_participants_tab').click();return;
    }
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

    document.getElementById("radio_q_options_panel").innerHTML = "";

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

    document.getElementById("check_q_options_panel").innerHTML = "";
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
    {console.log("here");return;}

    if(emailAddress === "") emailAddress = document.getElementById("email_inp").value;

    emailList.push(emailAddress);
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

        if(emailList.length === 0){
            document.getElementById("noemail").style.display = "block";
            //  document.getElementById("final_save_btn").style.display = "none";
        }

    };

    emailDiv.appendChild(emailtext);
    emailDiv.appendChild(removeBtn);
    document.getElementById("emailContainer").appendChild(emailDiv);
    document.getElementById("noemail").style.display = "none";
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
    console.log(Qdata);
    document.getElementById("final_save_btn").disabled = true;
    document.getElementById("loadbar").style.display = "block";

    sendAddQrequest((editMode)?'backend/QuizEdit.php':'backend/QuizSave.php',Qdata,(response,type)=>{
            $.notify({message: response.message}, {type: type, timer: 2000, placement: {from: 'top', align: 'right'}});
            if(type !== "danger"){
                document.getElementById("quiz_creds_key").innerText = response.quizKey;
                document.getElementById("quiz_creds_pass").innerText = response.quizPass;
                $("#quiz_creds_modal").modal("toggle");

            }
            document.getElementById("final_save_btn").disabled = false;
            document.getElementById("loadbar").style.display = "none";
        }
    );


}


/*
function setTime(duration, total, handler){
    if(duration >= 0){
        if(duration <= 60){
            document.getElementById("quiz_attempt_navbar").classList.remove("bg-success");
            document.getElementById("quiz_attempt_navbar").classList.add("bg-danger");
        }
        localStorage.setItem("lastTspot",end);
        let seconds = parseInt((duration) % 60);
        let minutes = parseInt((duration) / 60) % 60;
        let hours = parseInt((duration ) / 3600) % 24;
        document.getElementById("time").innerText = hours +":"+ minutes + ":" + seconds;
        let width = parseInt(duration*100/total);
        document.getElementById("time_bar").style.width = width+"%";
        // submit form automatically
    }
    else{
        localStorage.removeItem("lastTspot");
        console.log(document.getElementById("answers_form"));
        document.getElementById("answers_form").submit();
        clearInterval(handler);

    }

}
function startQuizProcess(time){

    let lastPoint = localStorage.getItem("lastTspot");
    end = (lastPoint == null)?1800:lastPoint;
    let total = end;
    let handler = null;

    if(end > 0){
        handler = setInterval(()=>{setTime(end,total, handler);end-=1;}, 1000);
    }

}
*/

function prepareQuizTimeline(){
    google.charts.load('current', {'packages':['timeline']});
    google.charts.setOnLoadCallback(drawChart);
}
function drawChart(){
    console.log(quizData);
    var data = new google.visualization.DataTable();

    data.addColumn('string', 'Quiz Name');
    data.addColumn('string', 'Quiz Bar');
    data.addColumn({ type: 'string', role: 'tooltip' });
    data.addColumn('date', 'Start');
    data.addColumn('date', 'End');

    var chart = new google.visualization.Timeline(document.getElementById('quizTimelineChart'));
    var options = {
        height:quizData.length*60,
        width:'auto',
        hAxis: {
            title: 'Time',
            format: 'dd/MM/yyyy HH:mm'
        },

        gantt: {
            trackHeight: 30,
        }
    };

    for(let i = 0; i < quizData.length; i++){
        var startDate = new Date(parseInt(quizData[i].startDate)*1000);
        var endDate = new Date(parseInt(quizData[i].endDate)*1000);
        data.addRow([quizData[i].qname, null,  customTimelineHover(quizData[i].qname,startDate,endDate),startDate, endDate]);
    }
    chart.draw(data, options);

    //console.log(data);
}

function customTimelineHover(quizName, starts, ends) {
    return '<div style="padding:5px 5px 5px 5px; z-index: 5000">' +
        '<table class="table table-responsive">' +
        '<tr><td>Title</td>' +
        '<td><b>' + quizName + '</b></td>' + '</tr>' + '<tr>' +
        '<td>Starts at</td>' +
        '<td><b>' + starts.toLocaleString('en-IN',{dateStyle:"short",timeStyle:"short",hour12:true})+ '</b></td>' + '</tr>' + '<tr>' +
        '<td>Ends at</td>' +
        '<td><b>' + ends.toLocaleString('en-IN',{dateStyle:"short",timeStyle:"short",hour12:true}) + '</b></td>' + '</tr>' + '</table>' + '</div>';

}

$(window).resize(function(){
    if(quizData != null && quizData.length > 0 )
        drawChart();
});


function questionWiseChart(){

    // md.startAnimationForLineChart(dailySalesChart);

}