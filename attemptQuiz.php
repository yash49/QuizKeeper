<?php
require_once 'sidebar.php';
renderSideBar("attemptQuiz");
?>

<div class="content ml-3 mr-3" >
        <div class="card ">
            <div class="card-header card-header-primary fs-4 fw-bold">
                Quiz Credentials
            </div>
            <div class="card-body">
                <div class="row justify-content-center">
                    <div class="col-md-8 col-sm-8 col-xs-12 justify-content-center">
                        <form action="quizkeeper/backend/AttemptQuiz.php" name="quiz_cred_form" method="post">
                            <input type="text" placeholder="Quiz Key" required  class="mr-auto form-control col-md-12 col-sm-12 col-xs-12" id="quiz_key" name="quiz_key">
                            <input type="password" placeholder="Quiz Password" required  class="mr-auto mt-2 form-control col-md-12 col-sm-12 col-xs-12" id="quiz_password" name="quiz_password">
                            <input type="submit" class="btn btn-sm btn-success ml-3 mt-2" id="attempt_quiz_btn" name="attempt_quiz_btn" value="Attempt Quiz">
                        </form>
                    </div>

                    <div id="emailContainer" class="col-md-10 col-sm-12 col-xs-12"></div>

                    <div class="col-md-12 col-sm-12 col-xs-12 text-center" style="overflow: hidden" id="attempt_banner">
                        <img src="assets/img/exam_header_bg.svg" width="350px" height="350px">
                    </div>
                </div>
            </div>
        </div>

</div>
</div> <!--END OF main-panel class-->
</div><!--END OF wrapper class-->
</body>
</html>