<html>
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <title>QUIZKepeer</title>
    <meta content='width=device-width, initial-scale=1.0, shrink-to-fit=no' name='viewport' />

    <link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700|Roboto+Slab:400,700|Material+Icons" />
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/latest/css/font-awesome.min.css">
    <link href="assets/css/material-dashboard.css?v=2.1.2" rel="stylesheet" />
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
</head>


<body>
    <?php
        session_start();
        if(!isset($_SESSION['uid'])) echo "<script>window.location.href = 'index.php'</script>";
    ?>
    <div class="wrapper">
        <div class="sidebar" data-color="green" data-background-color="white"  data-image="assets/img/sidebar-1.jpg">

            <div class="logo"><a href="http://www.creative-tim.com" class="simple-text logo-normal">

                </a></div>
            <div class="sidebar-wrapper">
                <ul class="nav">
                    <li class="nav-item ">
                        <a class="nav-link" href="./dashboard.html">
                            <i class="material-icons">dashboard</i>
                            <p>Dashboard</p>
                        </a>
                    </li>
                    <li class="nav-item active">
                        <a class="nav-link" href="./user.html">
                            <i class="material-icons">person</i>
                            <p>User Profile</p>
                        </a>
                    </li>
                    <li class="nav-item ">
                        <a class="nav-link" href="./tables.html">
                            <i class="material-icons">content_paste</i>
                            <p>Table List</p>
                        </a>
                    </li>
                    <li class="nav-item ">
                        <a class="nav-link" href="./typography.html">
                            <i class="material-icons">library_books</i>
                            <p>Typography</p>
                        </a>
                    </li>
                    <li class="nav-item ">
                        <a class="nav-link" href="./icons.html">
                            <i class="material-icons">bubble_chart</i>
                            <p>Icons</p>
                        </a>
                    </li>
                    <li class="nav-item ">
                        <a class="nav-link" href="./map.html">
                            <i class="material-icons">location_ons</i>
                            <p>Maps</p>
                        </a>
                    </li>
                    <li class="nav-item ">
                        <a class="nav-link" href="./notifications.html">
                            <i class="material-icons">notifications</i>
                            <p>Notifications</p>
                        </a>
                    </li>
                    <li class="nav-item ">
                        <a class="nav-link" href="./rtl.html">
                            <i class="material-icons">language</i>
                            <p>RTL Support</p>
                        </a>
                    </li>
                    <li class="nav-item active-pro ">
                        <a class="nav-link" href="./upgrade.html">
                            <i class="material-icons">unarchive</i>
                            <p>Upgrade to PRO</p>
                        </a>
                    </li>
                </ul>
            </div>
        </div>

        <div class="main-panel">
            <nav class="navbar navbar-expand-lg navbar-transparent navbar-absolute fixed-top ">
                <div class="container-fluid">
                    <div class="navbar-wrapper">
                        <a class="navbar-brand" href="javascript:;">Dashboard here</a>
                    </div>
                    <button class="navbar-toggler" type="button" data-toggle="collapse" aria-controls="navigation-index" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="navbar-toggler-icon icon-bar"></span>
                        <span class="navbar-toggler-icon icon-bar"></span>
                        <span class="navbar-toggler-icon icon-bar"></span>
                    </button>
                </div>
            </nav>
        </div>
    </div>
</body>
</html>