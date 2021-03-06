<?php
require_once 'sidebar.php';

renderSideBar("dashboard");

$db_servername = "remotemysql.com";
$db_username = "qUH1Egciqc";
$db_password = "yWQndClncV";
$dbname = "qUH1Egciqc";
$conn = new mysqli($db_servername, $db_username, $db_password, $dbname);
$uid=$_SESSION['uid'];
$query="select * from Users where uid=".$uid.";";
$stmt = $conn->prepare($query);
$stmt->execute();
$result = $stmt->get_result();
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
                                        <div class="ml-auto ">
                                            <form action="" style="margin-top: 0px">
                                                <button class="btn btn-info ">Edit Profile</button>

                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body justify-content-center fs-3 fw-bold" >

                                <div class="col-md-12 col-sm-12 col-xs-12" style="">
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

                                    <br>




                            </div>
                        </div>
                    </div>
                </div> <!--END OF main-panel class-->
            </div><!--END OF wrapper class-->
</body>
</html>