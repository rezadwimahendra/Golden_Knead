<?php
session_start();
require_once '../koneksi.php';

if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    
    // Hapus pesanan
    $result = $pesananCollection->deleteOne(['_id' => new MongoDB\BSON\ObjectId($id)]);
    
    if ($result->getDeletedCount() > 0) {
        $_SESSION['success'] = "Pesanan berhasil dihapus!";
    } else {
        $_SESSION['error'] = "Gagal menghapus pesanan.";
    }
} else {
    $_SESSION['error'] = "ID pesanan tidak valid!";
}

// Redirect kembali ke halaman sebelumnya
$redirect = 'pesanan.php';
if (isset($_SERVER['HTTP_REFERER'])) {
    $redirect = $_SERVER['HTTP_REFERER'];
}

header("Location: " . $redirect);
exit();
?> 