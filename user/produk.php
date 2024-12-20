<?php
session_start();
require_once '../koneksi.php';

// Cek jika user belum login
if (!isset($_SESSION['pelanggan_id'])) {
    header("Location: ../index.php");
    exit();
}

// Ambil kategori dari query string jika ada
$kategori = isset($_GET['kategori']) ? $_GET['kategori'] : null;

// Buat filter query
$filter = [];
if ($kategori && $kategori !== 'all') {
    $filter = ['nama' => new MongoDB\BSON\Regex($kategori, 'i')];
}

// Ambil produk dengan filter
$produk = $produkCollection->find($filter);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Produk - Golden Knead</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/styles.css">
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

        .category-filter {
            display: flex;
            justify-content: center;
            gap: 15px;
            margin-bottom: 40px;
            flex-wrap: wrap;
        }

        .filter-btn {
            padding: 12px 25px;
            border: none;
            border-radius: 25px;
            background: white;
            color: var(--tertiary-color);
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
        }

        .filter-btn:hover, .filter-btn.active {
            background: linear-gradient(135deg, var(--tertiary-color), #ff7f50);
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.1);
        }

        .card {
            border: none;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
            overflow: hidden;
            background: white;
            transition: all 0.3s ease;
        }

        .card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.12);
        }

        .card-img-wrapper {
            position: relative;
            padding-top: 75%;
            overflow: hidden;
            border-radius: 20px 20px 0 0;
        }

        .card-img-top {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.5s ease;
        }

        .card:hover .card-img-top {
            transform: scale(1.1);
        }

        .card-body {
            padding: 2rem;
            text-align: center;
            background: white;
        }

        .card-title {
            color: var(--tertiary-color);
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: 1rem;
            font-family: 'Playfair Display', serif;
        }

        .card-text {
            color: #666;
            font-size: 1rem;
            line-height: 1.6;
            margin-bottom: 1.5rem;
            min-height: 80px;
        }

        .product-price {
            color: var(--tertiary-color);
            font-weight: 600;
            font-size: 1.4rem;
            margin-bottom: 1.5rem;
            font-family: 'Playfair Display', serif;
        }

        .product-badge {
            position: absolute;
            top: 20px;
            right: 20px;
            background: linear-gradient(135deg, var(--accent-color), #ffd700);
            color: #2c3e50;
            padding: 8px 20px;
            border-radius: 25px;
            font-size: 0.9rem;
            font-weight: 500;
            z-index: 1;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }

        .btn-order {
            background: linear-gradient(135deg, var(--tertiary-color), #ff7f50);
            color: white;
            padding: 12px 25px;
            border-radius: 25px;
            font-weight: 500;
            transition: all 0.3s ease;
            text-transform: uppercase;
            letter-spacing: 1px;
            font-size: 0.9rem;
            border: none;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        .btn-order:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(0,0,0,0.15);
            color: white;
        }

        .footer {
            background: linear-gradient(135deg, var(--primary-color), var(--soft-color));
            color: white;
            padding: 80px 0 30px;
            margin-top: 80px;
            position: relative;
            overflow: hidden;
            box-shadow: 0 -10px 30px rgba(0, 0, 0, 0.1);
        }

        .footer::before {
            content: '';
            position: absolute;
            top: -50px;
            left: 0;
            right: 0;
            height: 100px;
            background: linear-gradient(to bottom right, transparent 49%, var(--primary-color) 50%);
            filter: drop-shadow(0 -5px 5px rgba(0,0,0,0.1));
        }

        .footer-logo {
            font-family: 'Playfair Display', serif;
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 1.5rem;
            color: white;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.1);
        }

        .footer-desc {
            color: rgba(255, 255, 255, 0.9);
            line-height: 1.8;
            font-size: 1.05rem;
            margin-bottom: 2rem;
        }

        .footer h5 {
            font-family: 'Playfair Display', serif;
            font-size: 1.4rem;
            margin-bottom: 1.5rem;
            position: relative;
            padding-bottom: 15px;
            font-weight: bold;
        }

        .footer h5::after {
            content: '';
            position: absolute;
            left: 0;
            bottom: 0;
            width: 60px;
            height: 3px;
            background-color: var(--accent-color);
            border-radius: 2px;
        }

        .footer-links {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .footer-links li {
            margin-bottom: 15px;
        }

        .footer-links a {
            color: rgba(255, 255, 255, 0.9);
            text-decoration: none;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 10px;
        }

        .footer-links a:hover {
            color: white;
            transform: translateX(5px);
        }

        .footer-links i {
            font-size: 1.2em;
        }

        .footer-bottom {
            text-align: center;
            margin-top: 60px;
            padding-top: 30px;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
        }

        .footer-bottom p {
            margin: 0;
            color: rgba(255, 255, 255, 0.9);
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

            .footer {
                padding: 60px 0 30px;
            }

            .navbar-brand {
                font-size: 22px;
            }

            .page-title {
                font-size: 2rem;
            }

            .category-filter {
                gap: 10px;
            }

            .filter-btn {
                padding: 8px 20px;
                font-size: 0.9rem;
            }
        }

        @media (max-width: 767.98px) {
            .page-title {
                font-size: 1.8rem;
                margin: 1.5rem 0;
            }

            .category-filter {
                gap: 8px;
                margin-bottom: 30px;
            }

            .filter-btn {
                padding: 6px 15px;
                font-size: 0.85rem;
            }

            .card-title {
                font-size: 1.3rem;
            }

            .card-text {
                font-size: 0.9rem;
                min-height: 60px;
            }

            .product-price {
                font-size: 1.2rem;
            }

            .btn-order {
                padding: 10px 20px;
                font-size: 0.85rem;
            }

            .footer-logo {
                font-size: 1.5rem;
            }

            .footer-desc {
                font-size: 0.9rem;
            }
        }

        @media (max-width: 575.98px) {
            .page-title {
                font-size: 1.6rem;
                margin: 1rem 0;
            }

            .category-filter {
                gap: 6px;
                margin-bottom: 20px;
            }

            .filter-btn {
                padding: 5px 12px;
                font-size: 0.8rem;
            }

            .card {
                margin-bottom: 20px;
            }

            .card-body {
                padding: 1.5rem;
            }

            .card-title {
                font-size: 1.2rem;
            }

            .product-badge {
                padding: 6px 15px;
                font-size: 0.8rem;
            }

            .btn-order {
                padding: 8px 15px;
                font-size: 0.8rem;
            }

            .footer {
                padding: 40px 0 20px;
                margin-top: 40px;
            }

            .footer h5 {
                font-size: 1.1rem;
                margin-bottom: 1rem;
            }

            .footer-links a {
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
                        <a class="nav-link active" href="produk.php">
                            <i class="bi bi-shop"></i> Produk
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="riwayat.php">
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
        <h1 class="page-title">Produk Kami</h1>

        <!-- Category Filter -->
        <div class="category-filter">
            <?php 
            $current_category = isset($_GET['kategori']) ? $_GET['kategori'] : 'all';
            ?>
            <button class="filter-btn <?php echo $current_category === 'all' ? 'active' : ''; ?>" data-category="all">Semua</button>
            <button class="filter-btn <?php echo $current_category === 'Brownies' ? 'active' : ''; ?>" data-category="Brownies">Brownies</button>
            <button class="filter-btn <?php echo $current_category === 'Donat' ? 'active' : ''; ?>" data-category="Donat">Donat</button>
            <button class="filter-btn <?php echo $current_category === 'Roti' ? 'active' : ''; ?>" data-category="Roti">Roti</button>
        </div>

        <!-- Products Grid -->
        <div class="row g-4">
            <?php foreach ($produk as $item): ?>
            <div class="col-md-4 mb-4" data-category="<?php echo htmlspecialchars($item->nama ?? ''); ?>">
                <div class="card">
                    <div class="card-img-wrapper">
                        <img src="../<?php echo htmlspecialchars($item->gambar); ?>" class="card-img-top" alt="<?php echo htmlspecialchars($item->nama); ?>">
                        <?php if (isset($item->badge)): ?>
                        <div class="product-badge"><?php echo htmlspecialchars($item->badge); ?></div>
                        <?php endif; ?>
                    </div>
                    <div class="card-body">
                        <h5 class="card-title"><?php echo htmlspecialchars($item->nama); ?></h5>
                        <p class="card-text"><?php echo htmlspecialchars($item->deskripsi); ?></p>
                        <p class="product-price">Rp <?php echo number_format($item->harga, 0, ',', '.'); ?></p>
                        <button class="btn btn-order w-100" onclick="location.href='pesan.php?id=<?php echo $item->_id; ?>'">
                            <i class="bi bi-bag-plus"></i> Pesan Sekarang
                        </button>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="row">
                <div class="col-lg-4 mb-4">
                    <div class="footer-logo">
                        <i class="bi bi-bread-slice"></i> Golden Knead
                    </div>
                    <p class="footer-desc">
                        Menyajikan roti dan kue berkualitas premium dengan cita rasa autentik. 
                        Dibuat dengan bahan-bahan pilihan dan penuh cinta untuk kepuasan pelanggan kami.
                    </p>
                </div>
                <div class="col-lg-4 mb-4">
                    <h5>Tautan Cepat</h5>
                    <ul class="footer-links">
                        <li><a href="produk.php"><i class="bi bi-shop"></i> Produk Kami</a></li>
                        <li><a href="riwayat.php"><i class="bi bi-clock-history"></i> Riwayat Pesanan</a></li>
                        <li><a href="profil.php"><i class="bi bi-person-circle"></i> Profil Saya</a></li>
                        <li><a href="#"><i class="bi bi-info-circle"></i> Tentang Kami</a></li>
                        <li><a href="#"><i class="bi bi-shield-check"></i> Kebijakan Privasi</a></li>
                    </ul>
                </div>
                <div class="col-lg-4 mb-4">
                    <h5>Hubungi Kami</h5>
                    <ul class="footer-links">
                        <li>
                            <a href="#">
                                <i class="bi bi-geo-alt"></i>
                                Jl. Roti Enak No. 123, Kota Bandung
                            </a>
                        </li>
                        <li>
                            <a href="tel:+6281234567890">
                                <i class="bi bi-telephone"></i>
                                +62 812-3456-7890
                            </a>
                        </li>
                        <li>
                            <a href="mailto:info@goldenknead.com">
                                <i class="bi bi-envelope"></i>
                                info@goldenknead.com
                            </a>
                        </li>
                        <li>
                            <a href="#">
                                <i class="bi bi-clock"></i>
                                Buka Setiap Hari: 08.00 - 20.00
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="footer-bottom">
                <p>&copy; <?php echo date('Y'); ?> Golden Knead. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Filter produk berdasarkan kategori
        document.querySelectorAll('.filter-btn').forEach(button => {
            button.addEventListener('click', () => {
                // Remove active class from all buttons
                document.querySelectorAll('.filter-btn').forEach(btn => {
                    btn.classList.remove('active');
                });
                
                // Add active class to clicked button
                button.classList.add('active');
                
                const category = button.getAttribute('data-category');
                // Redirect dengan kategori yang dipilih
                if (category === 'all') {
                    window.location.href = 'produk.php';
                } else {
                    window.location.href = 'produk.php?kategori=' + category;
                }
            });
        });
    </script>
</body>
</html> 