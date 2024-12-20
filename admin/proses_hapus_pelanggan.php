<?php
session_start();
require_once '../koneksi.php';

if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    
    // Cari pelanggan yang akan dihapus
    $pelanggan = $pelangganCollection->findOne(['_id' => new MongoDB\BSON\ObjectId($id)]);
    
    if ($pelanggan) {
        // Hapus foto profil jika ada
        if (isset($pelanggan->foto) && file_exists("../" . $pelanggan->foto)) {
            unlink("../" . $pelanggan->foto);
        }
        
        // Hapus semua pesanan terkait
        $pesananCollection->deleteMany(['pelanggan_id' => (string)$pelanggan->_id]);
        
        // Hapus pelanggan
        $result = $pelangganCollection->deleteOne(['_id' => new MongoDB\BSON\ObjectId($id)]);
        
        if ($result->getDeletedCount() > 0) {
            $_SESSION['success'] = "Pelanggan dan semua pesanannya berhasil dihapus!";
        } else {
            $_SESSION['error'] = "Gagal menghapus pelanggan.";
        }
    } else {
        $_SESSION['error'] = "Pelanggan tidak ditemukan!";
    }
} else {
    $_SESSION['error'] = "ID pelanggan tidak valid!";
}

header("Location: pelanggan.php");
exit();
?> 