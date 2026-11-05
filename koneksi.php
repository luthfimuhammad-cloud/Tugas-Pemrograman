<?php

$host     = "localhost";
$user     = "root";     
$pass     = "";         
$db_name  = "db_kas";   

$koneksi = mysqli_connect($host, $user, $pass, $db_name);

if (!$koneksi) {
    die("Koneksi ke database gagal: " . mysqli_connect_error());
}
?>
