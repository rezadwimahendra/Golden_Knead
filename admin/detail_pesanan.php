<?php
session_start();
require_once '../koneksi.php';

// Cek apakah user adalah admin
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

// Ambil ID pesanan dari parameter URL
$pesanan_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Query untuk mengambil detail pesanan
$query_pesanan = "SELECT p.*, pel.nama as nama_pelanggan, pel.email, pel.telepon, pel.alamat 
                 FROM pesanan p 
                 JOIN pelanggan pel ON p.pelanggan_id = pel.pelanggan_id 
                 WHERE p.pesanan_id = ?";
$stmt = $koneksi->prepare($query_pesanan);
$stmt->bind_param("i", $pesanan_id);
$stmt->execute();
$result_pesanan = $stmt->get_result();
$pesanan = $result_pesanan->fetch_assoc();

// Query untuk mengambil item pesanan
$query_items = "SELECT dp.*, p.nama as nama_produk, p.gambar 
               FROM detail_pesanan dp 
               JOIN produk p ON dp.produk_id = p.produk_id 
               WHERE dp.pesanan_id = ?";
$stmt = $koneksi->prepare($query_items);
$stmt->bind_param("i", $pesanan_id);
$stmt->execute();
$result_items = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Pesanan - Admin Golden Knead</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <style>
        .order-status {
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-weight: 500;
            text-align: center;
        }
        .status-pending { background-color: #ffeeba; color: #856404; }
        .status-diproses { background-color: #b8daff; color: #004085; }
        .status-selesai { background-color: #c3e6cb; color: #155724; }
        .status-dibatalkan { background-color: #f5c6cb; color: #721c24; }
        
        .product-image {
            width: 80px;
            height: 80px;
            object-fit: cover;
            border-radius: 10px;
        }
    </style>
</head>
<body class="bg-light">
    <?php include 'components/navbar.php'; ?>

    <div class="container py-5">
        <?php if (isset($pesanan)): ?>
            <div class="row">
                <div class="col-md-8 mx-auto">
                    <div class="card shadow-sm">
                        <div class="card-header bg-primary text-white">
                            <h5 class="mb-0">Detail Pesanan #<?= $pesanan_id ?></h5>
                        </div>
                        <div class="card-body">
                            <!-- Informasi Pelanggan -->
                            <div class="mb-4">
                                <h6 class="fw-bold">Informasi Pelanggan</h6>
                                <div class="row">
                                    <div class="col-md-6">
                                        <p class="mb-1"><strong>Nama:</strong> <?= htmlspecialchars($pesanan['nama_pelanggan']) ?></p>
                                        <p class="mb-1"><strong>Email:</strong> <?= htmlspecialchars($pesanan['email']) ?></p>
                                        <p class="mb-1"><strong>Telepon:</strong> <?= htmlspecialchars($pesanan['telepon']) ?></p>
                                    </div>
                                    <div class="col-md-6">
                                        <p class="mb-1"><strong>Alamat:</strong> <?= htmlspecialchars($pesanan['alamat']) ?></p>
                                        <p class="mb-1"><strong>Tanggal Pesanan:</strong> <?= date('d/m/Y H:i', strtotime($pesanan['created_at'])) ?></p>
                                        <p class="mb-1">
                                            <strong>Status:</strong> 
                                            <span class="order-status status-<?= strtolower($pesanan['status']) ?>">
                                                <?= ucfirst($pesanan['status']) ?>
                                            </span>
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <!-- Item Pesanan -->
                            <h6 class="fw-bold mb-3">Item Pesanan</h6>
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>Produk</th>
                                            <th>Harga</th>
                                            <th>Jumlah</th>
                                            <th class="text-end">Subtotal</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php while ($item = $result_items->fetch_assoc()): ?>
                                            <tr>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <img src="../assets/images/<?= htmlspecialchars($item['gambar']) ?>" 
                                                             class="product-image me-3" 
                                                             alt="<?= htmlspecialchars($item['nama_produk']) ?>">
                                                        <span><?= htmlspecialchars($item['nama_produk']) ?></span>
                                                    </div>
                                                </td>
                                                <td>Rp <?= number_format($item['harga_satuan'], 0, ',', '.') ?></td>
                                                <td><?= $item['jumlah'] ?></td>
                                                <td class="text-end">Rp <?= number_format($item['subtotal'], 0, ',', '.') ?></td>
                                            </tr>
                                        <?php endwhile; ?>
                                        <tr class="table-primary">
                                            <td colspan="3" class="text-end fw-bold">Total</td>
                                            <td class="text-end fw-bold">Rp <?= number_format($pesanan['total_harga'], 0, ',', '.') ?></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                            <!-- Update Status -->
                            <div class="mt-4">
                                <h6 class="fw-bold mb-3">Update Status Pesanan</h6>
                                <form action="proses_update_status.php" method="POST" class="d-flex gap-2">
                                    <input type="hidden" name="pesanan_id" value="<?= $pesanan_id ?>">
                                    <select name="status" class="form-select">
                                        <option value="pending" <?= $pesanan['status'] == 'pending' ? 'selected' : '' ?>>Pending</option>
                                        <option value="diproses" <?= $pesanan['status'] == 'diproses' ? 'selected' : '' ?>>Diproses</option>
                                        <option value="selesai" <?= $pesanan['status'] == 'selesai' ? 'selected' : '' ?>>Selesai</option>
                                        <option value="dibatalkan" <?= $pesanan['status'] == 'dibatalkan' ? 'selected' : '' ?>>Dibatalkan</option>
                                    </select>
                                    <button type="submit" class="btn btn-primary">Update Status</button>
                                </form>
                            </div>
                        </div>
                    </div>

                    <div class="text-center mt-4">
                        <a href="daftar_pesanan.php" class="btn btn-secondary">
                            <i class="bi bi-arrow-left"></i> Kembali ke Daftar Pesanan
                        </a>
                    </div>
                </div>
            </div>
        <?php else: ?>
            <div class="alert alert-danger text-center">
                Pesanan tidak ditemukan.
                <br>
                <a href="daftar_pesanan.php" class="btn btn-secondary mt-3">
                    <i class="bi bi-arrow-left"></i> Kembali ke Daftar Pesanan
                </a>
            </div>
        <?php endif; ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 