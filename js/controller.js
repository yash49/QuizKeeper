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