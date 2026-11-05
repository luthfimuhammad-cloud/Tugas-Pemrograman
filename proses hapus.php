<?php
session_start();
require_once 'koneksi.php';

if (isset($koneksi) && isset($_GET['id'])) {
    $id = (int)$_GET['id'];

    $query = "DELETE FROM kas WHERE id = '$id'";

    if (mysqli_query($koneksi, $query)) {
        header("Location: UTS aplikasi.php");
        exit;
    } else {
        echo "Gagal menghapus data transaksi: " . mysqli_error($koneksi);
    }
} else {
    header("Location: UTS aplikasi.php");
    exit;
}
?>
