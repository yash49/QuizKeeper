<html>
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
        <title>QUIZKepeer</title>
        <meta content='width=device-width, initial-scale=1.0, shrink-to-fit=no' name='viewport' />

        <link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700|Roboto+Slab:400,700|Material+Icons" />
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/latest/css/font-awesome.min.css">
        <link rel="stylesheet" href="assets/css/material-dashboard.css">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-BmbxuPwQa2lc/FVzBcNJ7UAyJxM6wuqIj61tLrc4wSX0szH/Ev+nYRRuWlolflfl" crossorigin="anonymous">

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/js/bootstrap.bundle.min.js" integrity="sha384-b5kHyXgcpbZJO/tY9Ul7kGkf1S0CWuKcCD38l8YkeH8z8QjE0GmW1gYU5S9FOnJ0" crossorigin="anonymous"></script>
        <script src="https://code.jquery.com/jquery-3.5.1.min.js"  integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>

        <script src="js/controller.js"></script>
        <script src="assets/js/plugins/bootstrap-notify.js"></script>
        <style>
            .anim-container{
                transform:translate(0px,10px);
                animation: paper 2s;
            }
            .mail{
                animation: move 2s infinite;
            }
            @keyframes paper{
                0%{transform:translate(0px,25%)}
                100%{transform:translate(0px,10px);}
            }
            @keyframes move {
                0%{margin-top: 50px}
                50%{margin-top: 60px}
                100%{margin-top: 50px}
            }
            @media only screen and (max-width: 992px) {
                .mail{visibility: hidden}
            }
        </style>
    </head>

    <body>
    <div class="container mt-5 p-4">

        <div class="row justify-content-center">

            <div class="col-md-12 row justify-content-center">
                <img src="assets/img/mail.svg" class="col-md-5 col-sm-6 col-xs-12 mail" style="position: absolute;transform:translate(0px,170px);z-index: 2"/>
                <img src="assets/img/mailCover.svg" class="col-md-5 col-sm-6 col-xs-6 mail" style="position: absolute;z-index: -1"/>

                <form  class="card anim-container col-md-4 text-center" method="post" style="z-index: 1;padding-bottom:160px">

                    <div class="card-header card-header-success">VERIFICATION</div>
                    <p class="text-muted mt-1">You will need to verify your email to complete the registration</p>
                    <div class="row justify-content-center p-3">
                        <div class="input-group col-md-10 pb-1">
                            <div class="input-group-prepend">
                                <span class="input-group-text bg-info text-white" id="basic-addon1">@</span>
                            </div>
                            <input name="verify_email" id="verify_email" type="email" required class="form-control" placeholder="Email address" aria-label="Email">
                        </div>
                        <div class="input-group col-md-10 pb-1">
                            <div class="input-group-prepend">
                                <span class="input-group-text bg-info text-white" id="basic-addon1">*</span>
                            </div>
                            <input name="verify_otp" id="verify_otp" type="number" min="000000" max="999999" required class="form-control" placeholder="OTP" aria-label="otp"/>
                        </div>
                    </div>

                    <input type="button" onclick="prepareVerificationRequest('backend/verificationHandler.php')" class="btn btn-success w-50" style="margin:auto;transform:translate(0px,-15px);z-index: 15" value="Verify"/>
                </form>
            </div>

        </div>

        </div>
    </body>

<script>
    function prepareVerificationRequest(url){
        let email = document.getElementsByName("verify_email")[0].value;
        let password = document.getElementsByName("verify_otp")[0].value;

        let data = {verify_email:email, verify_otp:password};

        sendVerifyRequest(url,data,(message,type)=>{
            $.notify({message: message}, {type: type, timer: 2000, placement: {from: 'top', align: 'right'}});
        })
    }
</script>
</html>
