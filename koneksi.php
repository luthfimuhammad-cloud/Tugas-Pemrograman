<?php
mysqli_report(MYSQLI_REPORT_OFF);

$host     = "localhost";
$user     = "root";     
$pass     = "";         
$db_name  = "db_kas";   

$koneksi = @mysqli_connect($host, $user, $pass, $db_name);

if (!$koneksi) {
    $koneksi = null;
}
?>
