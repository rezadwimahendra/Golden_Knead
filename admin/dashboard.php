<?php
session_start();
require_once '../koneksi.php';

// Cek jika admin belum login
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

// Ambil data admin
$admin = $adminCollection->findOne(['_id' => new MongoDB\BSON\ObjectId($_SESSION['admin_id'])]);
if (!$admin) {
    session_destroy();
    header("Location: login.php");
    exit();
}

// Ambil statistik
$totalPelanggan = $pelangganCollection->count();
$totalPesanan = $pesananCollection->count();
$pesananBaru = $pesananCollection->count(['status' => 'Menunggu']);
$pesananProses = $pesananCollection->count(['status' => 'Diproses']);

// Ambil pesanan terbaru
$pesananTerbaru = $pesananCollection->find(
    [],
    [
        'sort' => ['tanggal_pesanan' => -1],
        'limit' => 5
    ]
);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Golden Knead</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary-color: #9ac1f0;
            --secondary-color: #72fa93;
            --accent-color: #f6c445;
            --tertiary-color: #e45f2b;
            --soft-color: #a0e548;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #f0f4f8 0%, #d7e3ec 100%);
            min-height: 100vh;
            padding-top: 80px;
        }

        .navbar {
            background: linear-gradient(135deg, var(--primary-color), var(--soft-color)) !important;
            padding: 15px 0;
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.3), 0 4px 10px rgba(0, 0, 0, 0.2);
            border-bottom: 1px solid rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            z-index: 1000;
        }

        .navbar-brand {
            color: white !important;
            font-weight: 700;
            font-size: 26px;
            font-family: 'Playfair Display', serif;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.1);
            padding: 5px 15px;
            border-radius: 30px;
            transition: all 0.3s ease;
        }

        .navbar-brand:hover {
            background: rgba(255, 255, 255, 0.1);
            transform: translateY(-2px);
        }

        .navbar-brand i {
            margin-right: 8px;
            font-size: 1.2em;
            vertical-align: middle;
        }

        .nav-link {
            color: white !important;
            font-weight: 500;
            padding: 10px 20px !important;
            border-radius: 25px;
            transition: all 0.3s ease;
            margin: 0 5px;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .nav-link i {
            font-size: 1.2em;
        }

        .nav-link:hover, .nav-link.active {
            background: rgba(255, 255, 255, 0.1);
            transform: translateY(-2px);
        }

        .page-title {
            color: var(--tertiary-color);
            font-size: 2.5rem;
            margin-bottom: 2rem;
            font-family: 'Playfair Display', serif;
            font-weight: 700;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.1);
        }

        .stats-card {
            background: white;
            border-radius: 20px;
            padding: 25px;
            margin-bottom: 30px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
            transition: all 0.3s ease;
        }

        .stats-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.12);
        }

        .stats-icon {
            width: 60px;
            height: 60px;
            border-radius: 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.8rem;
            margin-bottom: 15px;
            background: linear-gradient(135deg, var(--accent-color), #ffd700);
            color: white;
        }

        .stats-value {
            font-size: 2rem;
            font-weight: 700;
            color: var(--tertiary-color);
            margin-bottom: 5px;
        }

        .stats-label {
            color: #666;
            font-size: 0.9rem;
        }

        .table-card {
            background: white;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
        }

        .table-card .card-header {
            background: linear-gradient(135deg, var(--primary-color), var(--soft-color));
            color: white;
            padding: 20px 25px;
            border: none;
        }

        .table-card .card-title {
            font-family: 'Playfair Display', serif;
            font-size: 1.5rem;
            margin: 0;
        }

        .table {
            margin-bottom: 0;
        }

        .table th {
            font-weight: 600;
            color: var(--tertiary-color);
            border-bottom: 2px solid var(--accent-color);
            padding: 15px;
        }

        .table td {
            padding: 15px;
            vertical-align: middle;
        }

        .order-status {
            display: inline-block;
            padding: 6px 15px;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 500;
        }

        .status-menunggu {
            background-color: #ffeeba;
            color: #856404;
        }

        .status-diproses {
            background-color: #b8daff;
            color: #004085;
        }

        .status-selesai {
            background-color: #c3e6cb;
            color: #155724;
        }

        .status-dibatalkan {
            background-color: #f5c6cb;
            color: #721c24;
        }

        @media (max-width: 991.98px) {
            .navbar-nav {
                background: rgba(255, 255, 255, 0.1);
                padding: 15px;
                border-radius: 15px;
                margin-top: 15px;
            }

            .nav-link {
                justify-content: center;
                margin: 5px 0;
            }

            .page-title {
                font-size: 2rem;
            }

            .stats-value {
                font-size: 1.8rem;
            }
        }

        @media (max-width: 767.98px) {
            .page-title {
                font-size: 1.8rem;
                margin-bottom: 1.5rem;
            }

            .stats-card {
                padding: 20px;
            }

            .stats-icon {
                width: 50px;
                height: 50px;
                font-size: 1.5rem;
            }

            .stats-value {
                font-size: 1.5rem;
            }

            .table-card .card-title {
                font-size: 1.3rem;
            }
        }

        @media (max-width: 575.98px) {
            .page-title {
                font-size: 1.6rem;
                margin-bottom: 1rem;
            }

            .stats-value {
                font-size: 1.3rem;
            }

            .stats-label {
                font-size: 0.8rem;
            }

            .table th, .table td {
                padding: 10px;
                font-size: 0.85rem;
            }

            .order-status {
                padding: 4px 10px;
                font-size: 0.75rem;
            }
        }
    </style>
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
                        <a class="nav-link active" href="dashboard.php">
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
        <h1 class="page-title">Dashboard</h1>

        <!-- Statistics -->
        <div class="row">
            <div class="col-md-3 col-sm-6">
                <div class="stats-card">
                    <div class="stats-icon">
                        <i class="bi bi-people"></i>
                    </div>
                    <div class="stats-value"><?php echo $totalPelanggan; ?></div>
                    <div class="stats-label">Total Pelanggan</div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6">
                <div class="stats-card">
                    <div class="stats-icon">
                        <i class="bi bi-cart3"></i>
                    </div>
                    <div class="stats-value"><?php echo $totalPesanan; ?></div>
                    <div class="stats-label">Total Pesanan</div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6">
                <div class="stats-card">
                    <div class="stats-icon">
                        <i class="bi bi-hourglass-split"></i>
                    </div>
                    <div class="stats-value"><?php echo $pesananBaru; ?></div>
                    <div class="stats-label">Pesanan Baru</div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6">
                <div class="stats-card">
                    <div class="stats-icon">
                        <i class="bi bi-gear"></i>
                    </div>
                    <div class="stats-value"><?php echo $pesananProses; ?></div>
                    <div class="stats-label">Pesanan Diproses</div>
                </div>
            </div>
        </div>

        <!-- Recent Orders -->
        <div class="table-card mt-4">
            <div class="card-header">
                <h5 class="card-title">Pesanan Terbaru</h5>
            </div>
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>No. Pesanan</th>
                            <th>Tanggal</th>
                            <th>Pelanggan</th>
                            <th>Total</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($pesananTerbaru as $pesanan): 
                            $pelanggan = isset($pesanan->pelanggan_id) ? 
                                $pelangganCollection->findOne(['_id' => new MongoDB\BSON\ObjectId($pesanan->pelanggan_id)]) : 
                                null;
                        ?>
                        <tr>
                            <td>#<?php echo substr($pesanan->_id, -8); ?></td>
                            <td><?php 
                                if (isset($pesanan->tanggal_pesanan) && $pesanan->tanggal_pesanan instanceof MongoDB\BSON\UTCDateTime) {
                                    echo date('d/m/Y H:i', $pesanan->tanggal_pesanan->toDateTime()->getTimestamp());
                                } else {
                                    echo date('d/m/Y H:i', strtotime($pesanan->tanggal_pesanan));
                                }
                            ?></td>
                            <td><?php echo $pelanggan ? htmlspecialchars($pelanggan->nama) : '<span class="text-muted">Pelanggan tidak ditemukan</span>'; ?></td>
                            <td>Rp <?php echo number_format(isset($pesanan->total) ? $pesanan->total : 0, 0, ',', '.'); ?></td>
                            <td>
                                <span class="order-status status-<?php echo strtolower($pesanan->status); ?>">
                                    <?php echo htmlspecialchars($pesanan->status); ?>
                                </span>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 