<?php
session_start();
require_once '../koneksi.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = $_POST['nama'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $telepon = $_POST['telepon'];
    $alamat = $_POST['alamat'];

    // Cek apakah email sudah terdaftar
    $existingUser = $pelangganCollection->findOne(['email' => $email]);
    if ($existingUser) {
        $_SESSION['error'] = "Email sudah terdaftar!";
        header("Location: ../index.php");
        exit();
    }

    // Data pelanggan baru
    $newPelanggan = [
        'nama' => $nama,
        'email' => $email,
        'password' => $password,
        'telepon' => $telepon,
        'alamat' => $alamat,
        'tanggal_daftar' => new MongoDB\BSON\UTCDateTime()
    ];

    try {
        // Insert data pelanggan baru
        $result = $pelangganCollection->insertOne($newPelanggan);
        
        if ($result->getInsertedCount() > 0) {
            // Set session untuk login otomatis
            $_SESSION['pelanggan_id'] = (string)$result->getInsertedId();
            $_SESSION['nama'] = $nama;
            
            // Redirect ke beranda
            header("Location: beranda.php");
            exit();
        } else {
            throw new Exception("Gagal menyimpan data");
        }
    } catch (Exception $e) {
        $_SESSION['error'] = "Gagal mendaftar: " . $e->getMessage();
        header("Location: ../index.php");
        exit();
    }
} else {
    header("Location: ../index.php");
    exit();
}
?> 