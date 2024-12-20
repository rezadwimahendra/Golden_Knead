<?php
session_start();
require_once '../koneksi.php';

// Cek jika admin belum login
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = $_POST['nama'];
    $peran = $_POST['peran'];
    $rating = floatval($_POST['rating']);
    $testimoni = $_POST['testimoni'];

    try {
        // Data testimoni baru
        $newTestimoni = [
            'nama' => $nama,
            'peran' => $peran,
            'rating' => $rating,
            'testimoni' => $testimoni,
            'tanggal' => new MongoDB\BSON\UTCDateTime()
        ];

        // Insert testimoni baru
        $result = $testimoniCollection->insertOne($newTestimoni);
        
        if ($result->getInsertedCount() > 0) {
            $_SESSION['success'] = "Testimoni berhasil ditambahkan!";
        } else {
            throw new Exception("Gagal menambahkan testimoni");
        }
    } catch (Exception $e) {
        $_SESSION['error'] = "Gagal menambahkan testimoni: " . $e->getMessage();
    }
}

header("Location: testimoni.php");
exit();
?> 