<?php

define('DB_HOST', 'db-cloud.czgog2qqq1ar.us-east-1.rds.amazonaws.com');
define('DB_USER', 'root');
define('DB_PASS', 'assignment-pass');
define('DB_NAME', 'cloud');

$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
