<?php
session_start();
require_once 'koneksi.php';

if (isset($koneksi) && $_SERVER['REQUEST_METHOD'] === 'POST') {
    
    $id         = (int)($_POST['id'] ?? 0);
    $tanggal    = mysqli_real_escape_string($koneksi, $_POST['tanggal'] ?? '');
    $keterangan = mysqli_real_escape_string($koneksi, $_POST['keterangan'] ?? '');
    $jenis      = mysqli_real_escape_string($koneksi, $_POST['jenis'] ?? 'masuk');
    $jumlah     = (int)($_POST['jumlah'] ?? 0);

    $query = "UPDATE kas SET 
                tanggal    = '$tanggal',
                keterangan = '$keterangan',
                jenis      = '$jenis',
                jumlah     = '$jumlah' 
            WHERE id = '$id'";

    if (mysqli_query($koneksi, $query)) {
        header("Location: UTS aplikasi.php");
        exit;
    } else {
        echo "Gagal memperbarui data transaksi: " . mysqli_error($koneksi);
    }
} else {
    header("Location: UTS aplikasi.php");
    exit;
}
?>