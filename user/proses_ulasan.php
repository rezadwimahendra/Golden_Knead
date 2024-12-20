<?php
session_start();
require_once '../koneksi.php';

// Cek jika pelanggan belum login
if (!isset($_SESSION['pelanggan_id'])) {
    header("Location: ../index.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $rating = intval($_POST['rating']);
    $testimoni = $_POST['testimoni'];
    $pelanggan_id = $_SESSION['pelanggan_id'];

    try {
        // Ambil data pelanggan
        $pelanggan = $pelangganCollection->findOne(['_id' => new MongoDB\BSON\ObjectId($pelanggan_id)]);

        if (!$pelanggan) {
            throw new Exception("Data pelanggan tidak ditemukan");
        }

        // Data ulasan
        $ulasanData = [
            'pelanggan_id' => $pelanggan_id,
            'nama' => $pelanggan->nama,
            'peran' => 'Pelanggan',
            'rating' => $rating,
            'testimoni' => $testimoni,
            'tanggal' => new MongoDB\BSON\UTCDateTime()
        ];

        // Jika ada ID, berarti update ulasan yang sudah ada
        if (isset($_POST['id'])) {
            $result = $testimoniCollection->updateOne(
                ['_id' => new MongoDB\BSON\ObjectId($_POST['id'])],
                ['$set' => $ulasanData]
            );

            if ($result->getModifiedCount() > 0) {
                $_SESSION['success'] = "Ulasan berhasil diperbarui!";
            } else {
                throw new Exception("Tidak ada perubahan yang disimpan");
            }
        } else {
            // Tambah ulasan baru
            $result = $testimoniCollection->insertOne($ulasanData);
            
            if ($result->getInsertedCount() > 0) {
                $_SESSION['success'] = "Terima kasih atas ulasan Anda!";
            } else {
                throw new Exception("Gagal menambahkan ulasan");
            }
        }
    } catch (Exception $e) {
        $_SESSION['error'] = "Error: " . $e->getMessage();
    }
}

header("Location: ulasan.php");
exit();
?> 