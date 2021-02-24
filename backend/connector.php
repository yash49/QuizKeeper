<?php

    //echo 'IN connector<br>' ;

    $servername = "sql12.freemysqlhosting.net:3306";
    $username = "sql12394568";
    $password = "rvzXDJrmIL";
    $dbname = "sql12394568";
 
    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
    die("failed: " . $conn->connect_error);
    }

    

?>