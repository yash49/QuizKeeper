function sendRequest(url, data, callback){
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