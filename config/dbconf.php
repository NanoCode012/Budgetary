<?php
$dbhost = "localhost";
$dbuser = "siit";
$dbpass = "siitonly";
$accdb = "manager";

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
$mysqli = mysqli_connect($dbhost, $dbuser, $dbpass, $accdb);

ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);
?>
