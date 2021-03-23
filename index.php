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


</head>

<body class="bg-white">

<nav class="navbar navbar-success bg-success">
  <div class="container-fluid">
    <a class="navbar-brand text-bold">QUIZKepeer</a>

     <?php
        session_start();
        if(isset($_SESSION['qid']))
        {
          //print_r($_SESSION);
          header("Location: http://{$_SERVER['SERVER_NAME']}/QuizKeeper/backend/AttemptQuiz.php");
          die();
        }
        if(isset($_SESSION['uid'])){
     ?>
            <script> window.location.href = "dashboard.php";</script>
      <?php }

      else { ?>
    <form class="d-flex" method="post">
      <input class="form-control" type="text" placeholder="Email" name="login_email">
      <input class="form-control ml-2" type="Password" placeholder="Password" name="login_password">
      <input class="btn btn-secondary ml-2" type="button" onclick="prepareLoginRequest('backend/login.php')" name="login_submit" value="Login">
    </form>
     <?php } ?>

  </div>
</nav>
    <div class="container text-center bg-white mt-5">


        <div class="row justify-content-center bg-white">
            <div class="col-md-6 col-sm-6 col-xs-12 order-md-2 order-sm-2  order-lg-2 bg-white">
                <img width="450" height="450" src="assets/img/Exams_header_animated.gif"/>
            </div>
            <div class="col-md-6 col-sm-6 col-xs-12 mt-2 text-center">
              <section style="
                overflow:show;
                background: linear-gradient(5deg,transparent, #4b8bcf);
                position:absolute;
                width:90%;
                height: 100%;
                z-index:0;
                margin-top: -40px;
                margin-left:-20px;
                clip-path: polygon(0% 0,100% 11%,100% 85%,0 100%);"></section>
                <section style="
                overflow:show;
                background: linear-gradient(-15deg,#22b573 , transparent);
                position:absolute;
                width:90%;
                height: 100%;
                z-index:0;
                margin-top: -30px;
                margin-left:-30px;
                clip-path: polygon(0% 0,100% 11%,100% 85%,0 100%);"></section>

                <!-- <h1><strong style="text-shadow: #0a6ebd;">Host or attempt the quiz with ease!</strong></h1> -->
                <div class="card">
                <div class="card-header card-header-success">
                  <h4 class="card-title">Signup</h4>
                  <p class="card-category">Fill the following details</p>
                </div>
                <div class="card-body">
                  <form method="Post">
                    <div class="row">
                      <div class="col-md-6">
                        <div class="form-group">
                          <label class="bmd-label-floating">Name</label>
                          <input type="text" name="signup_name" class="form-control">
                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="form-group">
                          <label class="bmd-label-floating">Email</label>
                          <input type="Email" name="signup_email" class="form-control">
                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="form-group">
                          <label class="bmd-label-floating">Phone</label>
                          <input type="tel" name="signup_phone" class="form-control">
                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="form-group">
                          <label class="bmd-label-floating">Password</label>
                          <input type="password" name="signup_password" class="form-control">
                        </div>
                      </div>
                      <div class="col-md-12 text-center">
                        <div class="text-center">
                          <input type="button" onclick="prepareSignupRequest('backend/signup.php')" name="signup_submit" value="Create Account" class="btn btn-success"/>
                            <div style="position:absolute; display: none; margin-bottom:-12px; right:15px;bottom:15px" id="loadbar" class="ml-2 spinner-border text-success">
                            </div>
                        </div>
                      </div>
                    </div>
                   </form> 
                </div>   
                </div>
            </div>    
            
        </div>


        <div class="row justify-content-center">

            <div class="col-md-4 col-xs-12 col-lg-4 p-2">
                <div class="card border border-success">
                    <div class="card-body">
                        <img src="assets/img/Teachers.svg" width="90%" />
                        <h3 style="margin-top:-30px">Evaluation Made Easy</h3>
                        <p>Evaluate your targeted audience, wether you are teacher, interviewer, community leader. Just prepare quiz with few clicks and leave rest on Quizkeeper.</p>
                    </div>
                </div>
            </div>

            <div class="col-md-4 col-xs-12 col-lg-4 p-2">
                  <div class="card border border-success">
                       <div class="card-body">
                            <img src="assets/img/Grades.svg" width="90%" />
                            <h3 style="margin-top:-30px">Get your results</h3>
                            <p> attempt the quiz and get the detailed result to test your knowledge, ace your test and share your results to share your success.
                       </div>
                  </div>
            </div>

            <div class="col-md-4 col-xs-12 col-lg-4 p-2">
                  <div class="card border border-success">
                       <div class="card-body">
                           <img src="assets/img/Analysis.svg" width="90%" />
                           <h3 style="margin-top:-30px">Statistics of hosted quiz</h3>
                           <p> Get the detailed report of your hosted quiz, number of participants, question-wise analysis, obtained mark distribution analysis and more.
                       </div>
                  </div>
            </div>

        </div>
    </div>
</body>

<script>

    function prepareSignupRequest(url){
        document.getElementsByName("signup_submit")[0].disabled = true;
        let name = document.getElementsByName("signup_name")[0].value;
        let email = document.getElementsByName("signup_email")[0].value;
        let phone = document.getElementsByName("signup_phone")[0].value;
        let password = document.getElementsByName("signup_password")[0].value;

        let data = {signup_name:name, signup_email:email, signup_phone:phone, signup_password:password};

        document.getElementById("loadbar").style.display = "inline-block";
        sendRequest(url,data,(message,type)=>{
           $.notify({message: message}, {type: type, timer: 2000, placement: {from: 'top', align: 'right'}});
            document.getElementById("loadbar").style.display = "none";
            document.getElementsByName("signup_submit")[0].disabled = false;
        })

    }
    function prepareLoginRequest(url){
        let email = document.getElementsByName("login_email")[0].value;
        let password = document.getElementsByName("login_password")[0].value;

        let data = {login_email:email, login_password:password};

        sendLoginRequest(url,data,(message,type)=>{
            $.notify({message: message}, {type: type, timer: 2000, placement: {from: 'top', align: 'right'}});
        })

    }
</script>
<script src="https://cdn.jsdelivr.net/npm/darkmode-js@1.5.7/lib/darkmode-js.min.js"></script>
</html>