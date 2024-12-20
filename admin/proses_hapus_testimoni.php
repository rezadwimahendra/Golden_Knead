<?php
session_start();
require_once '../koneksi.php';

// Cek jika admin belum login
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

if (isset($_GET['id'])) {
    try {
        $id = $_GET['id'];
        
        // Hapus testimoni
        $result = $testimoniCollection->deleteOne(['_id' => new MongoDB\BSON\ObjectId($id)]);
        
        if ($result->getDeletedCount() > 0) {
            $_SESSION['success'] = "Testimoni berhasil dihapus!";
        } else {
            throw new Exception("Testimoni tidak ditemukan");
        }
    } catch (Exception $e) {
        $_SESSION['error'] = "Gagal menghapus testimoni: " . $e->getMessage();
    }
}

header("Location: testimoni.php");
exit();
?> 