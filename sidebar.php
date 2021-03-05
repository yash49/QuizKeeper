<?php function renderSideBar($tab){?>
<html>
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <title>QUIZKepeer</title>
    <meta content='width=device-width, initial-scale=1.0, shrink-to-fit=no' name='viewport' />

    <link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700|Roboto+Slab:400,700|Material+Icons" />
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/latest/css/font-awesome.min.css">
    <link href="assets/css/material-dashboard.css?v=2.1.2" rel="stylesheet" />
    <link href="assets/css/styles.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-BmbxuPwQa2lc/FVzBcNJ7UAyJxM6wuqIj61tLrc4wSX0szH/Ev+nYRRuWlolflfl" crossorigin="anonymous">

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/js/bootstrap.bundle.min.js" integrity="sha384-b5kHyXgcpbZJO/tY9Ul7kGkf1S0CWuKcCD38l8YkeH8z8QjE0GmW1gYU5S9FOnJ0" crossorigin="anonymous"></script>
    <!--<script src="https://code.jquery.com/jquery-3.5.1.min.js"  integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>-->
    <script src="assets/js/core/jquery.min.js"></script>
    <script src="js/controller.js"></script>

    <script src="assets/js/core/popper.min.js"></script>
    <script src="assets/js/core/bootstrap-material-design.min.js"></script>
    <script src="assets/js/plugins/perfect-scrollbar.jquery.min.js"></script>
    <script src="assets/js/plugins/bootstrap-notify.js"></script>
    <script src="assets/js/plugins/nouislider.min.js"></script>
    <script src="assets/js/material-dashboard.js?v=2.1.2" type="text/javascript"></script>

    <?php if($tab == 'hostQuiz'){?>
    <script>
        window.onbeforeunload = function(event) {
            return true;

        };
    </script>
      <?php }  ?>
</head>


<body>
<?php
session_start();
if(!isset($_SESSION['uid'])) echo "<script>window.location.href = 'index.php'</script>";
?>
<div class="wrapper">
    <div class="sidebar" data-color="green" data-background-color="white"  data-image="assets/img/control_panel.svg">

        <div class="logo text-center fw-bold fs-5"><?php echo $_SESSION['name'];?></div>
        <div class="sidebar-wrapper">
            <ul class="nav">
                <li class="nav-item <?php echo ($tab=='dashboard'?' active':'')  ?> ">
                    <a class="nav-link" href="dashboard.php">
                        <i class="material-icons fw-bold">person</i>
                        <p class="fw-bold fs-6">User Profile</p>
                    </a>
                </li>
                <li class="nav-item <?php echo($tab=="attemptQuiz"?" active":"") ?>">
                    <a class="nav-link" href="attemptQuiz.php">
                        <i class="material-icons">content_paste</i>
                        <p class="fw-bold fs-6 fw-bold">Attempt QUIZ</p>
                    </a>
                </li>
                <li class="nav-item <?php echo ($tab=="myResults"?" active":"") ?>">
                    <a class="nav-link" href="myResults.php">
                        <i class="material-icons">library_books</i>
                        <p class="fw-bold fs-6">My Results</p>
                    </a>
                </li>
                <li class="nav-item <?php echo ($tab=="hostQuiz"?" active":"") ?>">
                    <a class="nav-link" href="hostQuiz.php">
                        <i class="material-icons">bubble_chart</i>
                        <p class="fw-bold fs-6">Host QUIZ</p>
                    </a>
                </li>
                <li class="nav-item <?php echo $tab=='hostedQuizStats'?'active':'' ?>">
                    <a class="nav-link" href="hostedQuizStats.php">
                        <i class="material-icons">analytics</i>
                        <p class="fw-bold fs-6">Hosted QUIZ Statistics</p>
                    </a>
                </li>
                <li class="nav-item active-pro">
                    <a class="nav-link  bg-danger " href="logout.php">
                        <i class="material-icons"></i>
                        <p class="text-center text-white font-weight-bold">Logut</p>
                    </a>
                </li>
            </ul>
        </div>
    </div>

    <div class="main-panel">
        <nav class="navbar navbar-expand-lg navbar-transparent navbar-absolute fixed-top ">
            <div class="container-fluid">
                <div class="navbar-wrapper">
                </div>
                <button class="navbar-toggler" type="button" data-toggle="collapse" aria-controls="navigation-index" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="navbar-toggler-icon icon-bar"></span>
                    <span class="navbar-toggler-icon icon-bar"></span>
                    <span class="navbar-toggler-icon icon-bar"></span>
                </button>
            </div>
        </nav>
<?php } ?>