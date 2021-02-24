function sendRequest(url, data, callback){

    $.ajax({
       data:data,
        url:url,
        method:"POST",
        success:function (response){
           console.log(response);
           if(response.message == "Success"){
               callback("Signup successfully! check email for verification","success");
           }
           else{
               callback("Something went wrong!","danger");
           }

        }
    });
}