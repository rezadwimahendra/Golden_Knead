<?php
session_start();
require_once '../koneksi.php';

// Cek jika admin belum login
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

// Ambil semua pelanggan
$pelanggan = $pelangganCollection->find();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Pelanggan - Admin Panel</title>
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
                        <a class="nav-link" href="pesanan.php">
                            <i class="bi bi-cart3"></i> Pesanan
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="produk.php">
                            <i class="bi bi-box-seam"></i> Produk
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="pelanggan.php">
                            <i class="bi bi-people"></i> Pelanggan
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
            <h1 class="page-title mb-0">Kelola Pelanggan</h1>
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

        <div class="card">
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Foto</th>
                            <th>Nama</th>
                            <th>Email</th>
                            <th>Telepon</th>
                            <th>Alamat</th>
                            <th>Tanggal Daftar</th>
                            <th>Total Pesanan</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($pelanggan as $item): 
                            // Hitung total pesanan
                            $totalPesanan = $pesananCollection->count(['pelanggan_id' => (string)$item->_id]);
                        ?>
                        <tr>
                            <td>
                                <img src="../<?php echo isset($item->foto) ? htmlspecialchars($item->foto) : 'assets/images/default-profile.jpg'; ?>" 
                                     alt="<?php echo htmlspecialchars($item->nama); ?>"
                                     style="width: 50px; height: 50px; object-fit: cover; border-radius: 50%;">
                            </td>
                            <td><?php echo htmlspecialchars($item->nama); ?></td>
                            <td><?php echo htmlspecialchars($item->email); ?></td>
                            <td><?php echo htmlspecialchars($item->telepon); ?></td>
                            <td><?php echo htmlspecialchars($item->alamat); ?></td>
                            <td>
                                <?php 
                                if (isset($item->tanggal_daftar)) {
                                    if ($item->tanggal_daftar instanceof MongoDB\BSON\UTCDateTime) {
                                        echo date('d/m/Y H:i', $item->tanggal_daftar->toDateTime()->getTimestamp());
                                    } else {
                                        echo date('d/m/Y H:i', strtotime($item->tanggal_daftar));
                                    }
                                } else {
                                    echo '-';
                                }
                                ?>
                            </td>
                            <td>
                                <span class="badge bg-info"><?php echo $totalPesanan; ?> pesanan</span>
                            </td>
                            <td>
                                <button class="btn btn-info btn-action" onclick="lihatPesanan('<?php echo $item->_id; ?>')">
                                    <i class="bi bi-cart"></i> Pesanan
                                </button>
                                <button class="btn btn-danger btn-action" onclick="hapusPelanggan('<?php echo $item->_id; ?>')">
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

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function lihatPesanan(id) {
            window.location.href = 'pesanan.php?pelanggan_id=' + id;
        }

        function hapusPelanggan(id) {
            if (confirm('Apakah Anda yakin ingin menghapus pelanggan ini? Semua pesanan terkait juga akan dihapus.')) {
                window.location.href = 'proses_hapus_pelanggan.php?id=' + id;
            }
        }
    </script>
</body>
</html> 