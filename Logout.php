<?php
session_start();

$_SESSION = array();

session_destroy();

header("Location: login.php");
exit;
?>

<a href="logout.php" 
   onclick="return confirm('Apakah Anda yakin ingin logout?')">
    Logout
</a>
