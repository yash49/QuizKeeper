<?php

    //echo 'IN connector<br>' ;

    $db_servername = "sql12.freemysqlhosting.net:3306";
    $db_username = "sql12394568";
    $db_password = "rvzXDJrmIL";
    $dbname = "sql12394568";
 
    $conn = new mysqli($db_servername, $db_username, $db_password, $dbname);

    if ($conn->connect_error) {
    die("failed: " . $conn->connect_error);
    }

    

?>