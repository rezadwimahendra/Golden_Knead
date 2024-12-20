<?php
session_start();
require_once '../koneksi.php';

// Redirect jika belum login
if (!isset($_SESSION['pelanggan_id'])) {
    header("Location: ../index.php");
    exit();
}

header("Location: produk.php");
exit();
?> 