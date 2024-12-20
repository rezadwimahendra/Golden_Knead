<?php
session_start();
require_once '../koneksi.php';

if (!isset($_SESSION['pelanggan_id'])) {
    header("Location: ../index.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $produk_id = $_POST['produk_id'];
    $jumlah = intval($_POST['jumlah']);
    $harga = floatval($_POST['harga']);
    $catatan = isset($_POST['catatan']) ? $_POST['catatan'] : '';
    $alamat = $_POST['alamat'];

    // Ambil data produk
    $produk = $produkCollection->findOne(['_id' => new MongoDB\BSON\ObjectId($produk_id)]);
    if (!$produk) {
        $_SESSION['error'] = "Produk tidak ditemukan!";
        header("Location: ./produk.php");
        exit();
    }

    // Hitung total
    $total = $jumlah * $harga;

    // Data pesanan baru
    $newPesanan = [
        'pelanggan_id' => $_SESSION['pelanggan_id'],
        'produk_id' => $produk_id,
        'nama_produk' => $produk->nama,
        'jumlah' => $jumlah,
        'harga' => $harga,
        'total' => $total,
        'alamat_pengiriman' => $alamat,
        'catatan' => $catatan,
        'status' => 'Menunggu',
        'tanggal_pesanan' => new MongoDB\BSON\UTCDateTime()
    ];

    try {
        // Insert pesanan baru
        $result = $pesananCollection->insertOne($newPesanan);
        
        if ($result->getInsertedCount() > 0) {
            $_SESSION['success'] = "Pesanan berhasil dibuat!";
            header("Location: ./riwayat.php");
            exit();
        } else {
            throw new Exception("Gagal membuat pesanan");
        }
    } catch (Exception $e) {
        $_SESSION['error'] = "Gagal membuat pesanan: " . $e->getMessage();
        header("Location: ./produk.php");
        exit();
    }
} else {
    header("Location: ./produk.php");
    exit();
}
?> 