<?php
// Konfigurasi Database MySQL
$host     = "localhost";
$user     = "root";     // Username default XAMPP
$pass     = "";         // Password default XAMPP (kosong)
$db_name  = "db_kas";   // Nama database aplikasi kas

// Membuat koneksi ke MySQL
$koneksi = mysqli_connect($host, $user, $pass, $db_name);

// Mengecek apakah koneksi berhasil
if (!$koneksi) {
    die("Koneksi ke database gagal: " . mysqli_connect_error());
}
?>