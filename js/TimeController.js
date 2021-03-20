let end = 0;
function setTime(duration, total, handler){
    if(duration >= 0){
        if(duration <= 60){
            document.getElementById("quiz_attempt_navbar").classList.remove("bg-success");
            document.getElementById("quiz_attempt_navbar").classList.add("bg-danger");
        }

        let seconds = parseInt((duration) % 60);
        let minutes = parseInt((duration) / 60) % 60;
        let hours = parseInt((duration ) / 3600) % 24;
        document.getElementById("time").innerText = hours +":"+ minutes + ":" + seconds;
        let width = parseInt(duration*100/total);
        document.getElementById("time_bar").style.width = width+"%";
        // submit form automatically
    }
    else{

        console.log(document.getElementById("answers_form"));
        document.getElementById("answers_form").submit();
        clearInterval(handler);

    }
}
function startQuizProcess(){

   // let lastPoint = localStorage.getItem("lastTspot");
   // let end = (lastPoint == null)?1800:lastPoint;
    let total = end;
    let handler = null;
    console.log(end);

    if(end > 0){
        handler = setInterval(()=>{setTime(end,total, handler);end-=1;}, 1000);
    }

}
window.onload = function (){
    $.ajax({
        data:{type:"fetch",key:"lastTspot"},
        url:'SyncTime.php',
        async:false,
        method:"POST",
        success:function (response){
            if(response.result == "Success"){
                end = response.value;
                startQuizProcess();
            }
            else{
                alert("ERROR");
            }
        }

    });
}

window.onbeforeunload = function(event) {
    let data = {type:"save",key:"lastTspot",value:end};
    navigator.sendBeacon("SyncTime.php",JSON.stringify(data));
};