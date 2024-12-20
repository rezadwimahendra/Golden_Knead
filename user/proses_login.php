<?php
session_start();
require_once '../koneksi.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Cari user berdasarkan email
    $pelanggan = $pelangganCollection->findOne(['email' => $email]);

    if ($pelanggan && password_verify($password, $pelanggan->password)) {
        // Login berhasil
        $_SESSION['pelanggan_id'] = (string)$pelanggan->_id;
        $_SESSION['nama'] = $pelanggan->nama;
        
        // Redirect ke beranda
        header("Location: ./beranda.php");
        exit();
    } else {
        // Login gagal
        $_SESSION['error'] = "Email atau kata sandi salah!";
        header("Location: ../index.php");
        exit();
    }
} else {
    header("Location: ../index.php");
    exit();
}
?> 