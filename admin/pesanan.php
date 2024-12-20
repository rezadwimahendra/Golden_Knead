<?php
session_start();
require_once '../koneksi.php';

// Cek jika admin belum login
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

// Filter pesanan berdasarkan pelanggan jika ada
$filter = [];
if (isset($_GET['pelanggan_id'])) {
    $filter['pelanggan_id'] = $_GET['pelanggan_id'];
    $pelanggan = $pelangganCollection->findOne(['_id' => new MongoDB\BSON\ObjectId($_GET['pelanggan_id'])]);
}

// Filter berdasarkan status jika ada
if (isset($_GET['status']) && !empty($_GET['status'])) {
    $filter['status'] = $_GET['status'];
}

// Ambil semua pesanan
$pesanan = $pesananCollection->find(
    $filter,
    ['sort' => ['tanggal_pesanan' => -1]]
);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Pesanan - Admin Panel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/admin.css">
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg fixed-top">
        <div class="container">
            <span class="navbar-brand">
                <i class="bi bi-bread-slice"></i> Admin Panel
            </span>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="dashboard.php">
                            <i class="bi bi-speedometer2"></i> Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="pesanan.php">
                            <i class="bi bi-cart3"></i> Pesanan
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="produk.php">
                            <i class="bi bi-box-seam"></i> Produk
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="pelanggan.php">
                            <i class="bi bi-people"></i> Pelanggan
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="testimoni.php">
                            <i class="bi bi-chat-quote"></i> Testimoni
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="../logout.php">
                            <i class="bi bi-box-arrow-right"></i> Keluar
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container py-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="page-title mb-0">
                <?php if (isset($pelanggan)): ?>
                    Pesanan <?php echo htmlspecialchars($pelanggan->nama); ?>
                <?php else: ?>
                    Kelola Pesanan
                <?php endif; ?>
            </h1>
            <?php if (isset($pelanggan)): ?>
                <a href="pelanggan.php" class="btn btn-primary btn-action">
                    <i class="bi bi-arrow-left"></i> Kembali
                </a>
            <?php endif; ?>
        </div>

        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <?php 
                echo $_SESSION['success'];
                unset($_SESSION['success']);
                ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <?php 
                echo $_SESSION['error'];
                unset($_SESSION['error']);
                ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <!-- Filter Status -->
        <div class="card mb-4">
            <div class="card-body">
                <form method="GET" class="row g-3 align-items-end">
                    <?php if (isset($_GET['pelanggan_id'])): ?>
                        <input type="hidden" name="pelanggan_id" value="<?php echo htmlspecialchars($_GET['pelanggan_id']); ?>">
                    <?php endif; ?>
                    <div class="col-md-4">
                        <label for="status" class="form-label">Filter Status</label>
                        <select class="form-control" id="status" name="status">
                            <option value="">Semua Status</option>
                            <option value="Menunggu" <?php echo (isset($_GET['status']) && $_GET['status'] === 'Menunggu') ? 'selected' : ''; ?>>Menunggu</option>
                            <option value="Diproses" <?php echo (isset($_GET['status']) && $_GET['status'] === 'Diproses') ? 'selected' : ''; ?>>Diproses</option>
                            <option value="Selesai" <?php echo (isset($_GET['status']) && $_GET['status'] === 'Selesai') ? 'selected' : ''; ?>>Selesai</option>
                            <option value="Dibatalkan" <?php echo (isset($_GET['status']) && $_GET['status'] === 'Dibatalkan') ? 'selected' : ''; ?>>Dibatalkan</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-primary btn-action w-100">
                            <i class="bi bi-filter"></i> Filter
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <div class="card">
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>No. Pesanan</th>
                            <th>Tanggal</th>
                            <th>Pelanggan</th>
                            <th>Produk</th>
                            <th>Total</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($pesanan as $item): 
                            $pelanggan = isset($item->pelanggan_id) ? 
                                $pelangganCollection->findOne(['_id' => new MongoDB\BSON\ObjectId($item->pelanggan_id)]) : 
                                null;
                        ?>
                        <tr>
                            <td>#<?php echo substr($item->_id, -8); ?></td>
                            <td>
                                <?php 
                                if (isset($item->tanggal_pesanan)) {
                                    if ($item->tanggal_pesanan instanceof MongoDB\BSON\UTCDateTime) {
                                        echo date('d/m/Y H:i', $item->tanggal_pesanan->toDateTime()->getTimestamp());
                                    } else {
                                        echo date('d/m/Y H:i', strtotime($item->tanggal_pesanan));
                                    }
                                } else {
                                    echo '-';
                                }
                                ?>
                            </td>
                            <td>
                                <?php if ($pelanggan): ?>
                                    <div class="d-flex align-items-center">
                                        <img src="../<?php echo isset($pelanggan->foto) ? htmlspecialchars($pelanggan->foto) : 'assets/images/default-profile.jpg'; ?>" 
                                             alt="<?php echo htmlspecialchars($pelanggan->nama); ?>"
                                             style="width: 35px; height: 35px; object-fit: cover; border-radius: 50%; margin-right: 10px;">
                                        <div>
                                            <?php echo htmlspecialchars($pelanggan->nama); ?><br>
                                            <small class="text-muted"><?php echo htmlspecialchars($pelanggan->telepon); ?></small>
                                        </div>
                                    </div>
                                <?php else: ?>
                                    <span class="text-muted">Pelanggan tidak ditemukan</span>
                                <?php endif; ?>
                            </td>
                            <td><?php echo htmlspecialchars($item->nama_produk); ?></td>
                            <td>Rp <?php echo number_format($item->total, 0, ',', '.'); ?></td>
                            <td>
                                <span class="status-badge status-<?php echo strtolower($item->status); ?>">
                                    <?php echo htmlspecialchars($item->status); ?>
                                </span>
                            </td>
                            <td>
                                <button class="btn btn-warning btn-action" onclick="updateStatus('<?php echo $item->_id; ?>')">
                                    <i class="bi bi-pencil"></i> Status
                                </button>
                                <button class="btn btn-danger btn-action" onclick="hapusPesanan('<?php echo $item->_id; ?>')">
                                    <i class="bi bi-trash"></i> Hapus
                                </button>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Modal Update Status -->
    <div class="modal fade" id="updateStatusModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Update Status Pesanan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form action="proses_update_status.php" method="POST">
                        <input type="hidden" name="id" id="pesanan_id">
                        <div class="mb-3">
                            <label for="status_update" class="form-label">Status</label>
                            <select class="form-control" id="status_update" name="status" required>
                                <option value="Menunggu">Menunggu</option>
                                <option value="Diproses">Diproses</option>
                                <option value="Selesai">Selesai</option>
                                <option value="Dibatalkan">Dibatalkan</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-success btn-action w-100">
                            <i class="bi bi-check-lg"></i> Update Status
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function updateStatus(id) {
            document.getElementById('pesanan_id').value = id;
            new bootstrap.Modal(document.getElementById('updateStatusModal')).show();
        }

        function hapusPesanan(id) {
            if (confirm('Apakah Anda yakin ingin menghapus pesanan ini?')) {
                window.location.href = 'proses_hapus_pesanan.php?id=' + id;
            }
        }
    </script>
</body>
</html> 