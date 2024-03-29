function sendRequest(url, data, callback){
    $.ajax({
       data:data,
        url:url,
        method:"POST",
        success:function (response){
           if(response.result == "Success"){
               callback(response.message,"success");
               setTimeout(()=>{window.location.href = "verification.php"},2100);
           }
           else{
               callback(response.message,"danger");
           }
        }

    });
}

function sendLoginRequest(url, data, callback){
    $.ajax({
        data:data,
        url:url,
        method:"POST",
        success:function (response){
            switch(response.result){
                case "verify":
                    window.location.href = "verification.php";
                    break;
                case "Success":
                    callback(response.message,"success");
                    window.location.href = "dashboard.php";
                    break;
                case "Fail":
                    callback(response.message,"danger");
                    break;
            }
        }
    });
}

function sendVerifyRequest(url, data, callback){
    $.ajax({
        data:data,
        url:url,
        method:"POST",
        success:function (response){
            switch(response.result){

                case "Success":
                    callback(response.message,"success");
                    window.location.href = "index.php";
                    break;
                case "Fail":
                    callback(response.message,"danger");
                    break;
            }
        }
    });
}


function removeQuiz(qid){
    let data = {quiz_id:qid};
    document.getElementById("remove_quiz_btn"+qid).disabled = true;
    document.getElementById("loadbar"+qid).style.display = "inline-block";
    $.ajax({
        data:data,
        url:'backend/QuizDelete.php',
        method:"POST",
        success:function (response){
            switch(response.result){

                case "Success":
                   // callback(response.message,"success");
                    $.notify({message: "Quiz deleted"}, {type: 'success', timer: 1000, placement: {from: 'top', align: 'right'}});
                    document.getElementById("quiz_"+qid).remove();
                    break;
                case "Fail":
                    $.notify({message: "Error occured while deleting the quiz!"}, {type: 'danger', timer: 1000, placement: {from: 'top', align: 'right'}});
                    //callback(response.message,"danger");
                    break;
            }

        },
        error:function (){
            $.notify({message: "Error occured while deleting the quiz!"}, {type: 'danger', timer: 1000, placement: {from: 'top', align: 'right'}});
            document.getElementById("remove_quiz_btn"+qid).disabled = false;
            document.getElementById("loadbar"+qid).style.display = "none";
        }
    });
}

function updateManualQuestion(ansid, marks){
    let data = {ansid: ansid};
    $.ajax({
        data:data,
        url:'backend/SaveManualQuestion.php',
        method:"POST",
        success:function (response){
            switch(response.result){

                case "Success":
                    // callback(response.message,"success");
                    $.notify({message: "Marks granted"}, {type: 'success', timer: 1000, placement: {from: 'top', align: 'right'}});
                    document.getElementById('marks_'+ansid).innerText = marks;
                    break;
                case "Fail":
                    $.notify({message: "Error occured while saving the marks!"}, {type: 'danger', timer: 1000, placement: {from: 'top', align: 'right'}});
                    //callback(response.message,"danger");
                    break;
            }

        },
        error:function (){
            $.notify({message: "Error occured while deleting the quiz!"}, {type: 'danger', timer: 1000, placement: {from: 'top', align: 'right'}});
        }
    });
}