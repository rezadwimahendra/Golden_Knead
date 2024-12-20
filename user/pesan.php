<?php
session_start();
require_once '../koneksi.php';

// Cek jika user belum login
if (!isset($_SESSION['pelanggan_id'])) {
    header("Location: ../index.php");
    exit();
}

// Cek jika ada ID produk
if (!isset($_GET['id'])) {
    header("Location: produk.php");
    exit();
}

// Ambil data produk
$produk = $produkCollection->findOne(['_id' => new MongoDB\BSON\ObjectId($_GET['id'])]);

// Jika produk tidak ditemukan
if (!$produk) {
    header("Location: produk.php");
    exit();
}

// Ambil data pelanggan
$pelanggan = $pelangganCollection->findOne(['_id' => new MongoDB\BSON\ObjectId($_SESSION['pelanggan_id'])]);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pesan <?php echo htmlspecialchars($produk->nama); ?> - Golden Knead</title>
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

        .nav-link:hover {
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

        .product-img {
            width: 100%;
            height: 300px;
            object-fit: cover;
        }

        .product-details {
            padding: 2rem;
        }

        .product-title {
            color: var(--tertiary-color);
            font-size: 1.8rem;
            font-weight: 700;
            margin-bottom: 1rem;
            font-family: 'Playfair Display', serif;
        }

        .product-price {
            color: var(--tertiary-color);
            font-size: 1.4rem;
            font-weight: 600;
            margin-bottom: 1rem;
        }

        .product-description {
            color: #666;
            line-height: 1.6;
            margin-bottom: 1.5rem;
        }

        .form-label {
            font-weight: 500;
            color: #2c3e50;
        }

        .form-control {
            border-radius: 15px;
            padding: 12px 20px;
            border: 1px solid #e1e8ed;
            transition: all 0.3s ease;
        }

        .form-control:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.2rem rgba(154, 193, 240, 0.25);
        }

        .total-section {
            background: #f8f9fa;
            padding: 1.5rem;
            border-radius: 15px;
            margin: 1.5rem 0;
        }

        .total-price {
            color: var(--tertiary-color);
            font-size: 1.4rem;
            font-weight: 600;
        }

        .btn-pesan {
            background: linear-gradient(135deg, var(--tertiary-color), #ff7f50);
            color: white;
            padding: 12px 25px;
            border-radius: 25px;
            font-weight: 500;
            transition: all 0.3s ease;
            width: 100%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            border: none;
        }

        .btn-pesan:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(0,0,0,0.15);
            color: white;
        }

        @media (max-width: 767.98px) {
            .page-title {
                font-size: 2rem;
                margin: 1.5rem 0;
            }

            .product-img {
                height: 250px;
            }

            .product-title {
                font-size: 1.5rem;
            }

            .product-price {
                font-size: 1.2rem;
            }

            .product-details {
                padding: 1.5rem;
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

        <div class="row">
            <div class="col-md-6">
                <div class="card">
                    <img src="../<?php echo isset($produk->gambar) ? htmlspecialchars($produk->gambar) : 'assets/images/default-product.jpg'; ?>" class="product-img" alt="<?php echo htmlspecialchars($produk->nama); ?>">
                    <div class="product-details">
                        <h2 class="product-title"><?php echo htmlspecialchars($produk->nama); ?></h2>
                        <p class="product-price">Rp <?php echo number_format($produk->harga, 0, ',', '.'); ?></p>
                        <p class="product-description"><?php echo htmlspecialchars($produk->deskripsi); ?></p>
                        <p><strong>Stok:</strong> <?php echo isset($produk->stok) ? $produk->stok : 'Tersedia'; ?></p>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card">
                    <div class="product-details">
                        <h3 class="mb-4">Form Pemesanan</h3>
                        <form action="proses_pesan.php" method="POST">
                            <input type="hidden" name="produk_id" value="<?php echo $produk->_id; ?>">
                            <input type="hidden" name="harga" value="<?php echo $produk->harga; ?>">
                            
                            <div class="mb-3">
                                <label for="jumlah" class="form-label">Jumlah</label>
                                <input type="number" class="form-control" id="jumlah" name="jumlah" min="1" max="<?php echo isset($produk->stok) ? $produk->stok : 100; ?>" value="1" required onchange="hitungTotal()">
                            </div>

                            <div class="mb-3">
                                <label for="catatan" class="form-label">Catatan (Opsional)</label>
                                <textarea class="form-control" id="catatan" name="catatan" rows="3"></textarea>
                            </div>

                            <div class="mb-3">
                                <label for="alamat" class="form-label">Alamat Pengiriman</label>
                                <textarea class="form-control" id="alamat" name="alamat" rows="3" readonly><?php echo htmlspecialchars($pelanggan->alamat); ?></textarea>
                            </div>

                            <div class="total-section">
                                <div class="d-flex justify-content-between align-items-center">
                                    <span>Total:</span>
                                    <span class="total-price" id="total">Rp <?php echo number_format($produk->harga, 0, ',', '.'); ?></span>
                                </div>
                            </div>

                            <button type="submit" class="btn btn-pesan">
                                <i class="bi bi-bag-check"></i> Pesan Sekarang
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function hitungTotal() {
            const jumlah = document.getElementById('jumlah').value;
            const harga = <?php echo $produk->harga; ?>;
            const total = jumlah * harga;
            document.getElementById('total').textContent = 'Rp ' + total.toLocaleString('id-ID');
        }
    </script>
</body>
</html> 