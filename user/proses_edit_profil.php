<?php
session_start();
require_once '../koneksi.php';

if (!isset($_SESSION['pelanggan_id'])) {
    header("Location: ../index.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = $_POST['nama'];
    $email = $_POST['email'];
    $alamat = $_POST['alamat'];
    $no_hp = $_POST['no_hp'];

    try {
        $result = $pelangganCollection->updateOne(
            ['_id' => new MongoDB\BSON\ObjectId($_SESSION['pelanggan_id'])],
            ['$set' => [
                'nama' => $nama,
                'email' => $email,
                'alamat' => $alamat,
                'no_hp' => $no_hp
            ]]
        );

        if ($result->getModifiedCount() > 0) {
            $_SESSION['success'] = "Profil berhasil diperbarui!";
            header("Location: ./profil.php");
            exit();
        } else {
            throw new Exception("Tidak ada perubahan yang disimpan");
        }
    } catch (Exception $e) {
        $_SESSION['error'] = "Gagal memperbarui profil: " . $e->getMessage();
        header("Location: ./edit_profil.php");
        exit();
    }
} else {
    header("Location: ./edit_profil.php");
    exit();
}
?> 