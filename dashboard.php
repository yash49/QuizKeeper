<?php
require_once 'sidebar.php';
require_once 'backend/connector.php';
renderSideBar("dashboard");


if (isset($_POST['submit'])) {

    $newname=$_POST['name'];
    $newmobile=$_POST['mobile'];


        $upd="UPDATE Users SET name='".$newname."' ,mobile='".$newmobile."'   WHERE uid=".$_SESSION['uid'].";";
        $conn->query($upd);



}

$uid=$_SESSION['uid'];
$query="select * from Users where uid=".$uid.";";
$hostquery="select * from Quiz where uid=".$uid.";";
$attemptquery="select * from QuizAttempt where uid=".$uid.";";
$stmt = $conn->prepare($query);







$stmt->execute();
$result = $stmt->get_result();

$stmt = $conn->prepare($hostquery);
$stmt->execute();
$resulthost = $stmt->get_result();

$stmt = $conn->prepare($attemptquery);
$stmt->execute();
$resultattempt = $stmt->get_result();

$totalhost=mysqli_num_rows($resulthost);
$totalattempt=mysqli_num_rows($resultattempt);

$row = $result->fetch_assoc();

$conn->close();


?>
            <div class="content ml-3 mr-3">

                <div class="row justify-content-center">
                    <div class="col-lg-10 col-md-10 col-sm-12">
                        <div class="card">
                            <div class="card-header card-header-tabs card-header-primary">
                                <div class="nav-tabs-navigation">
                                    <div class="nav-tabs-wrapper d-flex">
                                        <span class="nav-tabs-title" style="font-size: 20px;">
                                    <span>
                                        <?php
                                        echo $row['name'];
                                        ?>
                                    </span>
                                        </span>


                                    </div>
                                </div>
                            </div>
                            <div class="card-body justify-content-center fs-3 fw-bold" >

                                <div class="col-md-12 col-sm-12 col-xs-12">
                                    <!--<br>
                                    <span class="material-icons fs-3 fw-bold" style="color: #0b3251">face</span>
                                    <font style="color: #283593">
                                        <?php
/*                                            echo $row['name'];
                                        */?>
                                    </font>
                                    <br><br>
                                    <span class="material-icons fs-3 fw-bold" style="color: #0b3251">email</span>
                                    <font style="color: #283593">
                                        <?php
/*                                        echo $row['email'];
                                        */?>
                                    </font>

                                    <br><br>
                                    <span class="material-icons fs-3 fw-bold" style="color: #0b3251">call</span>
                                    <font style="color: #283593">
                                        <?php
/*                                        echo $row['mobile'];
                                        */?>
                                    </font>

                                    <br><br>

                                        <span class="material-icons-outlined  fs-3 fw-bold">file_upload</span>s
                                    <font style="color: #283593" class="">Quiz's attempted :
                                    </font>
                                    <font style="color: #283593">
                                        <?php
/*                                        echo $totalattempt." ";
                                        */?>
                                    </font>

                                    <br><br>

                                        <span class="material-icons-outlined">keyboard_arrow_down</span>
                                    <font style="color: #283593">Quiz's hosted :
                                    </font>
                                    <font style="color: #283593">
                                        <?php
/*                                        echo $totalhost;
                                        */?>
                                    </font>

                                    <br>-->

                                <div class="row">


                                    <div class="col-lg-6 col-md-6 col-sm-12">
                                        <div class="card card-stats pb-3">
                                            <div class="card-header card-header-success card-header-icon">
                                                <div class="card-icon">
                                                    <i class="material-icons">email</i>
                                                </div>
                                                <p class="card-category">email</p>
                                                <p class="card-title fs-6 fw-bold"><?php
                                                        echo $row['email'];
                                                        ?>
                                                </p>
                                            </div>

                                        </div>
                                    </div>


                                    <div class="col-lg-6 col-md-6 col-sm-12">
                                        <div class="card card-stats pb-3">
                                            <div class="card-header card-header-success card-header-icon">
                                                <div class="card-icon">
                                                    <i class="material-icons">call</i>
                                                </div>
                                                <p class="card-category">Mobile</p>
                                                <h5 class="card-title"><strong><?php
                                                        echo $row['mobile'];
                                                        ?></strong>
                                                </h5>
                                            </div>

                                        </div>
                                    </div>



                                    <div class="col-lg-6 col-md-6 col-sm-12">
                                        <div class="card card-stats pb-3">
                                            <div class="card-header card-header-success card-header-icon">
                                                <div class="card-icon">
                                                    <i class="material-icons">public</i>
                                                </div>
                                                <p class="card-category">Quiz Hosted</p>
                                                <h5 class="card-title"><strong><?php
                                                        echo $totalhost." ";
                                                        ?></strong>
                                                </h5>
                                            </div>

                                        </div>
                                    </div>


                                    <div class="col-lg-6 col-md-6 col-sm-12">
                                        <div class="card card-stats pb-3">
                                            <div class="card-header card-header-success card-header-icon">
                                                <div class="card-icon">
                                                    <i class="material-icons">library_books</i>
                                                </div>
                                                <p class="card-category">Quiz Attempted</p>
                                                <h5 class="card-title"><strong>
                                                        <?php
                                                        echo $totalattempt." ";
                                                        ?></strong>
                                                </h5>
                                            </div>

                                        </div>
                                    </div>

                                </div>



                                </div>
                                <div class="container justify-content-end" style="">

                                    <!-- Trigger the modal with a button -->
                                    <button type="button" class="btn btn-info btn-lg " data-toggle="modal" data-target="#myModal" style="margin-top: 20px;">Edit Profile</button>

                                    <!-- Modal -->
                                    <div class="modal fade" id="myModal" role="dialog">
                                        <div class="modal-dialog modal-lg">
                                            <form  method="POST">
                                            <!-- Modal content-->
                                            <div class="modal-content">
                                                <div class="modal-header bg-info" >
                                                    <h4 class="modal-title text-white">Edit Profile</h4>
                                                    <button type="button" class="close text-white" data-dismiss="modal">&times;</button>

                                                </div>
                                                <div class="modal-body">

                                                    <input type="text" placeholder="Username" required  class="mr-auto form-control col-md-12 col-sm-12 col-xs-12" id="quiz_key" name="name" value='<?php
                                                    echo $row['name'];
                                                    ?>'>
                                                    <input type="text" placeholder="Mobile" required  class="mr-auto form-control col-md-12 col-sm-12 col-xs-12" id="quiz_key" name="mobile" value='<?php
                                                    echo $row['mobile'];
                                                    ?>'>


                                                </div>
                                                <div class="modal-footer">
                                                    <input type="submit" class="btn btn-dark"  name="submit">
                                                </div>
                                            </div>
                                            </form>



                                        </div>
                                    </div>

                                </div>

                            </div>



                    </div>






                    </div>


                </div> <!--END OF main-panel class-->





            </div><!--END OF wrapper class-->



</body>
</html>