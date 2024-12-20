<?php
session_start();
require_once '../koneksi.php';

// Cek jika user belum login
if (!isset($_SESSION['pelanggan_id'])) {
    header("Location: ../index.php");
    exit();
}

// Cek jika ada ID pesanan
if (!isset($_GET['id'])) {
    header("Location: riwayat.php");
    exit();
}

// Ambil data pesanan
$pesanan = $pesananCollection->findOne(['_id' => new MongoDB\BSON\ObjectId($_GET['id'])]);

// Jika pesanan tidak ditemukan
if (!$pesanan) {
    header("Location: riwayat.php");
    exit();
}

// Ambil data produk
$produk = $produkCollection->findOne(['_id' => new MongoDB\BSON\ObjectId($pesanan->produk_id)]);

// Ambil data pelanggan
$pelanggan = $pelangganCollection->findOne(['_id' => new MongoDB\BSON\ObjectId($_SESSION['pelanggan_id'])]);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Pesanan - Golden Knead</title>
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
            margin-bottom: 30px;
        }

        .order-header {
            background: linear-gradient(135deg, var(--primary-color), var(--soft-color));
            padding: 30px;
            color: white;
            text-align: center;
        }

        .order-number {
            font-size: 1.8rem;
            font-weight: 700;
            margin-bottom: 10px;
            font-family: 'Playfair Display', serif;
        }

        .order-date {
            font-size: 1.1rem;
            opacity: 0.9;
        }

        .order-status {
            display: inline-block;
            padding: 8px 20px;
            border-radius: 25px;
            font-size: 1rem;
            font-weight: 500;
            margin-top: 15px;
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

        .order-details {
            padding: 30px;
        }

        .detail-section {
            margin-bottom: 30px;
        }

        .detail-title {
            color: var(--tertiary-color);
            font-size: 1.3rem;
            font-weight: 600;
            margin-bottom: 15px;
            font-family: 'Playfair Display', serif;
        }

        .detail-item {
            display: flex;
            justify-content: space-between;
            margin-bottom: 15px;
            padding-bottom: 15px;
            border-bottom: 1px solid #eee;
        }

        .detail-label {
            color: #666;
            font-weight: 500;
        }

        .detail-value {
            color: #2c3e50;
            font-weight: 600;
        }

        .product-image {
            width: 100px;
            height: 100px;
            object-fit: cover;
            border-radius: 10px;
            margin-right: 20px;
        }

        .product-details {
            display: flex;
            align-items: flex-start;
            margin-bottom: 20px;
            flex-wrap: wrap;
        }

        .product-image {
            width: 120px;
            height: 120px;
            object-fit: cover;
            border-radius: 10px;
            margin-right: 20px;
            margin-bottom: 15px;
        }

        .product-info {
            flex: 1;
            min-width: 200px;
        }

        .product-info h4 {
            color: var(--tertiary-color);
            font-size: 1.2rem;
            margin-bottom: 10px;
            font-family: 'Playfair Display', serif;
        }

        .product-info p {
            color: #666;
            margin-bottom: 8px;
            font-size: 0.95rem;
        }

        .detail-item {
            display: flex;
            justify-content: space-between;
            margin-bottom: 15px;
            padding-bottom: 15px;
            border-bottom: 1px solid #eee;
            flex-wrap: wrap;
            gap: 10px;
        }

        .detail-label {
            color: #666;
            font-weight: 500;
            flex: 1;
            min-width: 140px;
        }

        .detail-value {
            color: #2c3e50;
            font-weight: 600;
            text-align: right;
            flex: 2;
        }

        .btn-back {
            background: linear-gradient(135deg, var(--tertiary-color), #ff7f50);
            color: white;
            padding: 12px 25px;
            border-radius: 25px;
            font-weight: 500;
            transition: all 0.3s ease;
            border: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .btn-back:hover {
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

            .navbar-brand {
                font-size: 22px;
            }

            .page-title {
                font-size: 2rem;
            }

            .order-header {
                padding: 20px;
            }

            .order-number {
                font-size: 1.5rem;
            }
        }

        @media (max-width: 767.98px) {
            .page-title {
                font-size: 1.8rem;
                margin: 1.5rem 0;
            }

            .order-details {
                padding: 20px;
            }

            .detail-title {
                font-size: 1.2rem;
            }

            .product-image {
                width: 80px;
                height: 80px;
            }

            .product-info h4 {
                font-size: 1.1rem;
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

            .order-header {
                padding: 15px;
            }

            .order-number {
                font-size: 1.3rem;
            }

            .order-date {
                font-size: 1rem;
            }

            .order-status {
                padding: 6px 15px;
                font-size: 0.9rem;
            }

            .order-details {
                padding: 15px;
            }

            .detail-title {
                font-size: 1.1rem;
            }

            .detail-item {
                flex-direction: column;
                gap: 5px;
            }

            .product-image {
                width: 60px;
                height: 60px;
            }

            .product-info h4 {
                font-size: 1rem;
            }

            .btn-back {
                padding: 10px 20px;
                font-size: 0.9rem;
            }

            .container {
                padding-left: 10px;
                padding-right: 10px;
            }

            .card {
                border-radius: 15px;
            }

            .order-header {
                padding: 15px;
            }

            .order-number {
                font-size: 1.3rem;
            }

            .order-date {
                font-size: 0.9rem;
            }

            .order-status {
                padding: 6px 15px;
                font-size: 0.9rem;
            }

            .order-details {
                padding: 15px;
            }

            .detail-title {
                font-size: 1.1rem;
                margin-bottom: 12px;
            }

            .product-details {
                flex-direction: column;
            }

            .product-image {
                width: 100%;
                height: 200px;
                margin-right: 0;
                margin-bottom: 15px;
            }

            .product-info {
                width: 100%;
            }

            .product-info h4 {
                font-size: 1.1rem;
            }

            .product-info p {
                font-size: 0.9rem;
            }

            .detail-item {
                flex-direction: column;
                gap: 5px;
            }

            .detail-label {
                width: 100%;
                color: #666;
            }

            .detail-value {
                width: 100%;
                text-align: left;
                padding-left: 0;
            }

            .btn-back {
                width: 100%;
                padding: 12px;
                font-size: 0.95rem;
                justify-content: center;
            }
        }

        @media (max-width: 767.98px) {
            .page-title {
                font-size: 1.8rem;
                margin: 1.5rem 0;
            }

            .order-details {
                padding: 20px 15px;
            }

            .detail-section {
                margin-bottom: 25px;
            }

            .product-image {
                width: 100px;
                height: 100px;
            }

            .product-info h4 {
                font-size: 1.1rem;
            }
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
                margin: 1.5rem 0;
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
                            <i class="bi bi-chat-quote"></i> Ulasan
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
        <h1 class="page-title">Detail Pesanan</h1>

        <div class="card">
            <div class="order-header">
                <div class="order-number">#<?php echo substr($pesanan->_id, -8); ?></div>
                <div class="order-date"><?php echo date('d/m/Y H:i', $pesanan->tanggal_pesanan->toDateTime()->getTimestamp()); ?></div>
                <div class="order-status status-<?php echo strtolower($pesanan->status); ?>">
                    <?php echo htmlspecialchars($pesanan->status); ?>
                </div>
            </div>

            <div class="order-details">
                <div class="detail-section">
                    <h3 class="detail-title">Informasi Produk</h3>
                    <div class="product-details">
                        <img src="../<?php echo isset($produk->gambar) ? htmlspecialchars($produk->gambar) : 'assets/images/default-product.jpg'; ?>" alt="<?php echo htmlspecialchars($pesanan->nama_produk); ?>" class="product-image">
                        <div class="product-info">
                            <h4><?php echo htmlspecialchars($pesanan->nama_produk); ?></h4>
                            <p>Jumlah: <?php echo $pesanan->jumlah; ?></p>
                            <p>Harga: Rp <?php echo number_format($pesanan->harga, 0, ',', '.'); ?></p>
                        </div>
                    </div>
                </div>

                <div class="detail-section">
                    <h3 class="detail-title">Informasi Pengiriman</h3>
                    <div class="detail-item">
                        <span class="detail-label">Alamat Pengiriman</span>
                        <span class="detail-value"><?php echo htmlspecialchars($pesanan->alamat_pengiriman); ?></span>
                    </div>
                    <?php if (isset($pesanan->catatan) && !empty($pesanan->catatan)): ?>
                    <div class="detail-item">
                        <span class="detail-label">Catatan</span>
                        <span class="detail-value"><?php echo htmlspecialchars($pesanan->catatan); ?></span>
                    </div>
                    <?php endif; ?>
                </div>

                <div class="detail-section">
                    <h3 class="detail-title">Ringkasan Pembayaran</h3>
                    <div class="detail-item">
                        <span class="detail-label">Subtotal Produk</span>
                        <span class="detail-value">Rp <?php echo number_format($pesanan->harga * $pesanan->jumlah, 0, ',', '.'); ?></span>
                    </div>
                    <div class="detail-item">
                        <span class="detail-label">Total Pembayaran</span>
                        <span class="detail-value">Rp <?php echo number_format($pesanan->total, 0, ',', '.'); ?></span>
                    </div>
                </div>

                <div class="text-center mt-4">
                    <a href="riwayat.php" class="btn btn-back">
                        <i class="bi bi-arrow-left"></i> Kembali ke Riwayat Pesanan
                    </a>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 