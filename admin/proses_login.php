<?php
session_start();
require_once '../koneksi.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Cari admin berdasarkan email
    $admin = $adminCollection->findOne(['email' => $email]);

    if ($admin && password_verify($password, $admin->password)) {
        // Login berhasil
        $_SESSION['admin_id'] = (string)$admin->_id;
        $_SESSION['admin_nama'] = $admin->nama;
        
        // Redirect ke dashboard
        header("Location: ./dashboard.php");
        exit();
    } else {
        // Login gagal
        $_SESSION['error'] = "Email atau kata sandi salah!";
        header("Location: ./login.php");
        exit();
    }
} else {
    header("Location: ./login.php");
    exit();
}
?> 