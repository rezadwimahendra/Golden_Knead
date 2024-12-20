<?php
// Menggunakan Composer autoload untuk MongoDB driver
require_once __DIR__ . '/vendor/autoload.php';

// Konfigurasi koneksi MongoDB
try {
    $mongoClient = new MongoDB\Client("mongodb://localhost:27017");
    $db = $mongoClient->golden_knead;  // Nama database

    // Pilih koleksi yang diperlukan
    $adminCollection = $db->admin;
    $pelangganCollection = $db->pelanggan;
    $produkCollection = $db->produk;
    $pesananCollection = $db->pesanan;
    $testimoniCollection = $db->testimoni;
    
} catch (MongoDB\Driver\Exception\Exception $e) {
    die("Koneksi gagal: " . $e->getMessage());
}
?> 