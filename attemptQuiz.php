<?php
require_once 'sidebar.php';
renderSideBar("attemptQuiz");
?>

<div class="content ml-3 mr-3">
        <div class="card ">
            <div class="card-header card-header-primary">

            </div>
            <div class="card-body">
                <div class="row justify-content-center">
                    <div class="col-md-8 col-sm-8 col-xs-12 justify-content-center">
                        <form action="quizkeeper/backend/AttemptQuiz.php" name="quiz_cred_form" method="post">
                            <input type="email" placeholder="Quiz Key" required  class="mr-auto form-control col-md-12 col-sm-12 col-xs-12" id="email_inp" name="key_inp">
                            <input type="email" placeholder="Quiz Key" required  class="mr-auto form-control col-md-12 col-sm-12 col-xs-12" id="email_inp" name="password_inp">
                            <button class="btn btn-sm btn-success ml-3 mt-2" onclick="startQuiz()" id="attempt_quiz_btn" name="attempt_quiz_btn">Attempt Quiz</button>
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