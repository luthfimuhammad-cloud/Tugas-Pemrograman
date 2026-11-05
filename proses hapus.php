<?php
session_start();
require_once 'koneksi.php';

// Memastikan untuk koneksi tersedia dan parameter ID ada pada link GET:
if (isset($koneksi) && isset($_GET['id'])) {
    $id = (int)$_GET['id'];

    // Query DELETE untuk menghapus transaksi kas berdasarkan ID:
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