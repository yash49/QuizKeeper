<?php

    //echo 'IN connector<br>' ;

    //$db_servername = "sql12.freemysqlhosting.net";
    //$db_username = "sql12394568";
    //$db_password = "rvzXDJrmIL";
    //$dbname = "sql12394568";
 
    $db_servername = "remotemysql.com";
    $db_username = "qUH1Egciqc";
    $db_password = "yWQndClncV";
    $dbname = "qUH1Egciqc";

    $conn = new mysqli($db_servername, $db_username, $db_password, $dbname);

    if ($conn->connect_error) {
    die("failed: " . $conn->connect_error);
    }

    

?>