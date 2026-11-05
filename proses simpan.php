<?php
session_start();
require_once 'koneksi.php';

// Memastikan variabel koneksi tersedia dan request dikirim via POST
if (isset($koneksi) && $_SERVER['REQUEST_METHOD'] === 'POST') {
    // Ambil dan amankan data dari inputan form
    $tanggal    = mysqli_real_escape_string($koneksi, $_POST['tanggal'] ?? '');
    $keterangan = mysqli_real_escape_string($koneksi, $_POST['keterangan'] ?? '');
    $jenis      = mysqli_real_escape_string($koneksi, $_POST['jenis'] ?? 'masuk');
    $jumlah     = (int)($_POST['jumlah'] ?? 0);

    // Query INSERT untuk menyimpan transaksi kas baru
    $query = "INSERT INTO kas (tanggal, keterangan, jenis, jumlah) 
              VALUES ('$tanggal', '$keterangan', '$jenis', '$jumlah')";

    if (mysqli_query($koneksi, $query)) {
        header("Location: UTS aplikasi.php");
        exit;
    } else {
        echo "Gagal menyimpan data transaksi: " . mysqli_error($koneksi);
    }
} else {
    header("Location: UTS aplikasi.php");
    exit;
}
?>