<?php
require_once 'sidebar.php';
require_once 'backend/connector.php';
renderSideBar("dashboard");


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

$row = $result->fetch_assoc()
?>
            <div class="content ml-3 mr-3">

                <div class="row justify-content-center">
                    <div class="col-lg-10 col-md-10 col-sm-12">
                        <div class="card">
                            <div class="card-header card-header-tabs card-header-primary">
                                <div class="nav-tabs-navigation">
                                    <div class="nav-tabs-wrapper d-flex">
                                        <span class="nav-tabs-title" style="font-size: 20px;">Profile</span>


                                    </div>
                                </div>
                            </div>
                            <div class="card-body justify-content-center fs-3 fw-bold" >

                                <div class="col-md-12 col-sm-12 col-xs-12">
                                    <br>
                                    <span class="material-icons fs-3 fw-bold" style="color: #0b3251">
face
</span>
                                    <font style="color: #283593">
                                        <?php
                                            echo $row['name'];
                                        ?>
                                    </font>
                                    <br><br>
                                    <span class="material-icons fs-3 fw-bold" style="color: #0b3251">
                                            email
                                        </span>
                                    <font style="color: #283593">
                                        <?php
                                        echo $row['email'];
                                        ?>
                                    </font>

                                    <br><br>
                                    <span class="material-icons fs-3 fw-bold" style="color: #0b3251">
call
</span>
                                    <font style="color: #283593">
                                        <?php
                                        echo $row['mobile'];
                                        ?>
                                    </font>

                                    <br><br>

                                        <!--<span class="material-icons-outlined  fs-3 fw-bold">file_upload</span>-->
                                    <font style="color: #283593" class="">Quiz's attempted :
                                    </font>
                                    <font style="color: #283593">
                                        <?php
                                        echo $totalattempt." ";
                                        ?>
                                    </font>

                                    <br><br>

                                        <!--<span class="material-icons-outlined">keyboard_arrow_down</span>-->
                                    <font style="color: #283593">Quiz's hosted :
                                    </font>
                                    <font style="color: #283593">
                                        <?php
                                        echo $totalhost;
                                        ?>
                                    </font>

                                    <br>


                                </div>
                                <div class="container justify-content-end" style="">

                                    <!-- Trigger the modal with a button -->
                                    <button type="button" class="btn btn-info btn-lg" data-toggle="modal" data-target="#myModal" style="margin-top: 20px;">Edit Profile</button>

                                    <!-- Modal -->
                                    <div class="modal fade" id="myModal" role="dialog">
                                        <div class="modal-dialog">

                                            <!-- Modal content-->
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                    <h4 class="modal-title">Modal Header</h4>
                                                </div>
                                                <div class="modal-body">
                                                    <p>Some text in the modal.</p>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                                </div>
                                            </div>

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