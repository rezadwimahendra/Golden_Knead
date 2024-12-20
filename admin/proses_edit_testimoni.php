<?php
session_start();
require_once '../koneksi.php';

// Cek jika admin belum login
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $id = $_POST['id'];
        $nama = $_POST['nama'];
        $peran = $_POST['peran'];
        $rating = floatval($_POST['rating']);
        $testimoni = $_POST['testimoni'];

        // Update testimoni
        $result = $testimoniCollection->updateOne(
            ['_id' => new MongoDB\BSON\ObjectId($id)],
            ['$set' => [
                'nama' => $nama,
                'peran' => $peran,
                'rating' => $rating,
                'testimoni' => $testimoni,
                'updated_at' => new MongoDB\BSON\UTCDateTime()
            ]]
        );

        if ($result->getModifiedCount() > 0) {
            $_SESSION['success'] = "Testimoni berhasil diperbarui!";
        } else {
            throw new Exception("Tidak ada perubahan yang disimpan");
        }
    } catch (Exception $e) {
        $_SESSION['error'] = "Gagal memperbarui testimoni: " . $e->getMessage();
    }
}

header("Location: testimoni.php");
exit();
?> 