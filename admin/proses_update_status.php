<?php
session_start();
require_once '../koneksi.php';

if (!isset($_SESSION['admin_id']) || !isset($_POST['pesanan_id']) || !isset($_POST['status'])) {
    header("Location: login.php");
    exit();
}

$pesanan_id = intval($_POST['pesanan_id']);
$status = $_POST['status'];
$valid_status = ['pending', 'diproses', 'selesai', 'dibatalkan'];

if (!in_array($status, $valid_status)) {
    $_SESSION['error'] = "Status tidak valid!";
    header("Location: detail_pesanan.php?id=" . $pesanan_id);
    exit();
}

$query = "UPDATE pesanan SET status = ? WHERE pesanan_id = ?";
$stmt = $koneksi->prepare($query);
$stmt->bind_param("si", $status, $pesanan_id);

if ($stmt->execute()) {
    $_SESSION['success'] = "Status pesanan berhasil diperbarui!";
} else {
    $_SESSION['error'] = "Gagal memperbarui status pesanan!";
}

header("Location: detail_pesanan.php?id=" . $pesanan_id);
exit();
?> 