<?php
session_start();
require_once '../koneksi.php';

// Cek jika user belum login
if (!isset($_SESSION['pelanggan_id'])) {
    header("Location: ../index.php");
    exit();
}

// Ambil data pelanggan
$pelanggan = $pelangganCollection->findOne(['_id' => new MongoDB\BSON\ObjectId($_SESSION['pelanggan_id'])]);

// Ambil riwayat pesanan
$pesanan = $pesananCollection->find(
    ['pelanggan_id' => $_SESSION['pelanggan_id']],
    ['sort' => ['tanggal_pesanan' => -1]]
);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Riwayat Pesanan - Golden Knead</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary-color: #9ac1f0;    /* Biru */
            --secondary-color: #72fa93;   /* Hijau */
            --accent-color: #f6c445;      /* Kuning */
            --tertiary-color: #e45f2b;    /* Orange */
            --soft-color: #a0e548;        /* Hijau Muda */
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
            text-align: center;
            margin: 2rem 0;
            font-family: 'Playfair Display', serif;
            font-weight: 700;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.1);
        }

        .card {
            border: none;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
            overflow: hidden;
            background: white;
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
            padding: 8px 15px;
            border-radius: 20px;
            font-size: 0.9rem;
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

        .btn-detail {
            background: linear-gradient(135deg, var(--tertiary-color), #ff7f50);
            color: white;
            padding: 8px 20px;
            border-radius: 20px;
            font-weight: 500;
            transition: all 0.3s ease;
            border: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .btn-detail:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            color: white;
        }

        .empty-state {
            text-align: center;
            padding: 40px 20px;
        }

        .empty-state i {
            font-size: 4rem;
            color: var(--tertiary-color);
            margin-bottom: 1rem;
        }

        .empty-state h4 {
            color: var(--tertiary-color);
            font-family: 'Playfair Display', serif;
            font-weight: 700;
            margin-bottom: 1rem;
        }

        .empty-state p {
            color: #666;
            margin-bottom: 2rem;
        }

        .btn-shop {
            background: linear-gradient(135deg, var(--tertiary-color), #ff7f50);
            color: white;
            padding: 12px 30px;
            border-radius: 25px;
            font-weight: 500;
            transition: all 0.3s ease;
            border: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .btn-shop:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.1);
            color: white;
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

            .table-responsive {
                border-radius: 20px;
            }

            .navbar-brand {
                font-size: 22px;
            }

            .page-title {
                font-size: 2rem;
            }

            .table th, .table td {
                padding: 12px;
            }
        }

        @media (max-width: 767.98px) {
            .page-title {
                font-size: 1.8rem;
                margin: 1.5rem 0;
            }

            .table th, .table td {
                padding: 10px;
                font-size: 0.9rem;
            }

            .order-status {
                padding: 6px 12px;
                font-size: 0.8rem;
            }

            .btn-detail {
                padding: 6px 15px;
                font-size: 0.85rem;
            }

            .empty-state i {
                font-size: 3rem;
            }

            .empty-state h4 {
                font-size: 1.3rem;
            }

            .empty-state p {
                font-size: 0.9rem;
            }
        }

        @media (max-width: 575.98px) {
            .page-title {
                font-size: 1.6rem;
                margin: 1rem 0;
            }

            .card {
                margin: 0 -0.5rem;
                border-radius: 15px;
            }

            .table th, .table td {
                padding: 8px;
                font-size: 0.85rem;
            }

            .order-status {
                padding: 4px 10px;
                font-size: 0.75rem;
            }

            .btn-detail {
                padding: 5px 12px;
                font-size: 0.8rem;
            }

            .empty-state {
                padding: 30px 15px;
            }

            .empty-state i {
                font-size: 2.5rem;
            }

            .empty-state h4 {
                font-size: 1.2rem;
            }

            .empty-state p {
                font-size: 0.85rem;
                margin-bottom: 1.5rem;
            }

            .btn-shop {
                padding: 8px 20px;
                font-size: 0.9rem;
            }
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg fixed-top">
        <div class="container">
            <span class="navbar-brand">
                <i class="bi bi-bread-slice"></i> Golden Knead
            </span>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="produk.php">
                            <i class="bi bi-shop"></i> Produk
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="riwayat.php">
                            <i class="bi bi-clock-history"></i> Riwayat Pesanan
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="profil.php">
                            <i class="bi bi-person-circle"></i> Profil
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="ulasan.php">
                            <i class="bi bi-star"></i> Ulasan
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="./logout.php">
                            <i class="bi bi-box-arrow-right"></i> Keluar
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container py-4">
        <h1 class="page-title">Riwayat Pesanan</h1>

        <div class="card">
            <div class="card-body">
                <?php
                $pesananArray = iterator_to_array($pesanan);
                if (count($pesananArray) > 0):
                ?>
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>No. Pesanan</th>
                                <th>Tanggal</th>
                                <th>Produk</th>
                                <th>Total</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($pesananArray as $order): ?>
                            <tr>
                                <td>#<?php echo substr($order->_id, -8); ?></td>
                                <td><?php echo date('d/m/Y H:i', $order->tanggal_pesanan->toDateTime()->getTimestamp()); ?></td>
                                <td><?php echo htmlspecialchars($order->nama_produk); ?></td>
                                <td>Rp <?php echo number_format($order->total, 0, ',', '.'); ?></td>
                                <td>
                                    <span class="order-status status-<?php echo strtolower($order->status); ?>">
                                        <?php echo htmlspecialchars($order->status); ?>
                                    </span>
                                </td>
                                <td>
                                    <a href="detail_pesanan.php?id=<?php echo $order->_id; ?>" class="btn btn-detail">
                                        <i class="bi bi-eye"></i> Detail
                                    </a>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <?php else: ?>
                <div class="empty-state">
                    <i class="bi bi-bag-x"></i>
                    <h4>Belum Ada Pesanan</h4>
                    <p>Anda belum memiliki riwayat pesanan. Yuk, mulai belanja!</p>
                    <a href="produk.php" class="btn btn-shop">
                        <i class="bi bi-shop"></i> Mulai Belanja
                    </a>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 