<?php
session_start();
require_once '../koneksi.php';

if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    
    // Cari produk yang akan dihapus
    $produk = $produkCollection->findOne(['_id' => new MongoDB\BSON\ObjectId($id)]);
    
    if ($produk) {
        // Hapus gambar produk jika ada
        if (isset($produk->gambar) && file_exists("../" . $produk->gambar)) {
            unlink("../" . $produk->gambar);
        }
        
        // Hapus produk dari database
        $result = $produkCollection->deleteOne(['_id' => new MongoDB\BSON\ObjectId($id)]);
        
        if ($result->getDeletedCount() > 0) {
            $_SESSION['success'] = "Produk berhasil dihapus!";
        } else {
            $_SESSION['error'] = "Gagal menghapus produk.";
        }
    } else {
        $_SESSION['error'] = "Produk tidak ditemukan!";
    }
} else {
    $_SESSION['error'] = "ID produk tidak valid!";
}

header("Location: produk.php");
exit();
?> 