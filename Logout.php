<?php
session_start();

// Menghapus seluruh data session
$_SESSION = array();

// Menghapus session
session_destroy();

// Mengarahkan kembali ke halaman login
header("Location: login.php");
exit;
?>

<a href="logout.php" 
   onclick="return confirm('Apakah Anda yakin ingin logout?')">
    Logout
</a>
