function refreshView(parent){
    if(Qdata.questionData.length === 0)document.getElementById("nopreview").style.display = "block";

    Qdata.questionData.forEach((item, index)=>{

        if(item.type == "radio" )
            renderRadioQuestions(parent,item.question, item.options.split(','), item.answer, index+1);
        else if(item.type == "checkbox" )
            renderCheckboxQuestions(parent,item.question, item.options.split(','), item.answer, index+1);
        else if(item.type == "loose_text" || item.type == "strict_text")
            renderInputQuestions(parent, item.question, item.answer, index+1);
    })
}

function renderRadioQuestions(parent, question, options, answer, start){

    let qCard = document.createElement("div");

    qCard.id = "qcard_"+start;
    qCard.classList.add("card","p-2","col-md-12","col-xs-12","col-sm-12");

    let header = "<div class=\"card-header card-header-info p-2\">\n" +
        "                <div class=\"question_badge pl-2 pr-2 pt-2 h-100 fs-5\">Q."+start+"</div>\n" +
        "                <div class=\"card-title ml-5 text-lowercase fs-5 fw-bold\">"+question+"</div>\n" +
        "            </div>";

    qCard.innerHTML = header;
    for(let i = 0; i < options.length; i++){
        let optionContainer = "<div class=\"col-md-12 col-sm-12 col-xs-12 mt-2 \">" +
            "                        <div class=\"radio\">\n" +
            "                            <label class=\"fs-5 text-dark\">\n" +
            "                                <input type=\"radio\" class=\"fs-2\" name='"+start+"' value=\""+options[i]+"\">\n" +
            options[i] +(options[i] == answer?"<span class='ml-2 material-icons text-success'>verified</span>":"")+
            "                            </label>\n" +
            "                        </div>\n" +
            "                    </div>";
        qCard.innerHTML += optionContainer;
    }
    let removeBtn = document.createElement("button");
    removeBtn.classList.add("btn","btn-outline-danger","col-lg-5","col-md-5","col-sm-6","col-xs-6");
    removeBtn.innerText = "Remove";
    removeBtn.id = "remove_q_"+start;

    removeBtn.onclick=()=>{
        Qdata.questionData.splice(start-1,1);
        qCard.remove();
        console.log(Qdata.questionData);
        parent.innerHTML = "";
        refreshView(parent);
    }
    qCard.appendChild(removeBtn);

    parent.appendChild(qCard);
    if(start === 1){
        document.getElementById("nopreview").style.display = "none";
    }
}


function renderCheckboxQuestions(parent, question, options, answer, start){

    let qCard = document.createElement("div");
    let answerArray = answer.split(",");
    qCard.id = "qcard_"+start;
    qCard.classList.add("card","p-2","col-md-12","col-xs-12","col-sm-12");

    let header = "<div class=\"card-header card-header-info p-2\">\n" +
        "                <div class=\"question_badge pl-2 pr-2 pt-2 h-100 fs-5\">Q."+start+"</div>\n" +
        "                <div class=\"card-title ml-5 text-lowercase fs-5 fw-bold\">"+question+"</div>\n" +
        "            </div>";

    qCard.innerHTML = header;
    for(let i = 0; i < options.length; i++){
        let optionContainer = "<div class=\"col-md-12 col-sm-12 col-xs-12 mt-2 \">" +
            "                        <div class=\"checkbox\">\n" +
            "                            <label class=\"fs-5 text-dark\">\n" +
            "                                <input type=\"checkbox\" class=\"fs-2\" name='"+start+"' value=\""+options[i]+"\">\n" +
            options[i] +(answerArray.includes(options[i])?"<span class='ml-2 material-icons text-success'>verified</span>":"")+
            "                            </label>\n" +
            "                        </div>\n" +
            "                    </div>";
        qCard.innerHTML += optionContainer;
    }
    let removeBtn = document.createElement("button");
    removeBtn.classList.add("btn","btn-outline-danger","col-lg-5","col-md-5","col-sm-6","col-xs-6");
    removeBtn.innerText = "Remove";
    removeBtn.id = "remove_q_"+start;

    removeBtn.onclick=()=>{
        Qdata.questionData.splice(start-1,1);
        qCard.remove();
        parent.innerHTML = "";
       refreshView(parent);
    }
    qCard.appendChild(removeBtn);

    parent.appendChild(qCard);
    if(start === 1){
        document.getElementById("nopreview").style.display = "none";
    }
}

function renderInputQuestions(parent, question, answer, start){

    let qCard = document.createElement("div");

    qCard.id = "qcard_"+start;
    qCard.classList.add("card","p-2","col-md-12","col-xs-12","col-sm-12");

    let header = "<div class=\"card-header card-header-info p-2\">\n" +
        "                <div class=\"question_badge pl-2 pr-2 pt-2 h-100 fs-5\">Q."+start+"</div>\n" +
        "                <div class=\"card-title ml-5 text-lowercase fs-5 fw-bold\">"+question+"</div>\n" +
        "            </div>";

    qCard.innerHTML = header;
    let optionContainer = "<div class=\"col-md-12 col-sm-12 col-xs-12 mt-2 \">" +
            "                        <div class=\"col-12\">\n" +
            "                                <input type=\"text\" class=\"fs-6 form-control\" data-toggle=\"tooltip\" data-placement=\"bottom\" title='"+answer+"' disabled name='"+start+"' value=\""+answer+"\">"+
            "                        </div>\n" +
            "                    </div>";
    qCard.innerHTML += optionContainer;
    let removeBtn = document.createElement("button");

    removeBtn.classList.add("btn","btn-outline-danger","col-lg-5","col-md-5","col-sm-6","col-xs-6");
    removeBtn.innerText = "Remove";
    removeBtn.id = "remove_q_"+start;

    removeBtn.onclick=()=>{
        Qdata.questionData.splice(start-1,1);
        qCard.remove();
        parent.innerHTML = "";
        refreshView(parent);
    }
    qCard.appendChild(removeBtn);
    parent.appendChild(qCard);
    if(start === 1){
        document.getElementById("nopreview").style.display = "none";
    }
}





