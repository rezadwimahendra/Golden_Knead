<?php
session_start();
require_once '../koneksi.php';

// Cek jika pelanggan belum login
if (!isset($_SESSION['pelanggan_id'])) {
    header("Location: ../index.php");
    exit();
}

// Ambil data pelanggan
$pelanggan = $pelangganCollection->findOne(['_id' => new MongoDB\BSON\ObjectId($_SESSION['pelanggan_id'])]);

// Ambil ulasan yang sudah diberikan oleh pelanggan ini
$ulasan = $testimoniCollection->findOne(['pelanggan_id' => $_SESSION['pelanggan_id']]);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Beri Ulasan - Golden Knead</title>
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
        }

        .rating-input {
            display: flex;
            flex-direction: row-reverse;
            justify-content: flex-end;
            gap: 10px;
            font-size: 1.5rem;
        }

        .rating-input input {
            display: none;
        }

        .rating-input label {
            color: #ddd;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .rating-input label:hover,
        .rating-input label:hover ~ label,
        .rating-input input:checked ~ label {
            color: #ffc107;
        }

        .rating-input label:hover,
        .rating-input input:checked + label {
            transform: scale(1.1);
        }

        .review-card {
            background: white;
            border-radius: 20px;
            padding: 30px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
            margin-bottom: 30px;
        }

        .review-header {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
        }

        .review-rating {
            color: #ffc107;
            font-size: 1.2rem;
            margin-bottom: 15px;
        }

        .review-text {
            color: #6c757d;
            font-size: 1rem;
            line-height: 1.6;
            margin-bottom: 0;
        }

        .btn-submit {
            background: linear-gradient(135deg, var(--tertiary-color), #ff7f50);
            color: white;
            border: none;
            padding: 12px 25px;
            border-radius: 25px;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .btn-submit:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.15);
            color: white;
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
                        <a class="nav-link active" href="ulasan.php">
                            <i class="bi bi-star"></i> Ulasan
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
        <h1 class="page-title">Beri Ulasan</h1>

        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <?= htmlspecialchars($_SESSION['success']) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            <?php unset($_SESSION['success']); ?>
        <?php endif; ?>

        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <?= htmlspecialchars($_SESSION['error']) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            <?php unset($_SESSION['error']); ?>
        <?php endif; ?>

        <?php if ($ulasan): ?>
        <!-- Tampilkan ulasan yang sudah ada -->
        <div class="review-card">
            <div class="review-header">
                <div>
                    <h5 class="mb-0"><?php echo htmlspecialchars($pelanggan->nama); ?></h5>
                    <small class="text-muted"><?php echo date('d/m/Y', $ulasan->tanggal->toDateTime()->getTimestamp()); ?></small>
                </div>
            </div>
            <div class="review-rating">
                <?php
                $rating = $ulasan->rating;
                for ($i = 1; $i <= 5; $i++) {
                    if ($i <= $rating) {
                        echo '<i class="bi bi-star-fill"></i>';
                    } elseif ($i - 0.5 == $rating) {
                        echo '<i class="bi bi-star-half"></i>';
                    } else {
                        echo '<i class="bi bi-star"></i>';
                    }
                }
                ?>
            </div>
            <p class="review-text"><?php echo htmlspecialchars($ulasan->testimoni); ?></p>
            <button class="btn btn-submit mt-3" onclick="enableEdit()">
                <i class="bi bi-pencil"></i> Edit Ulasan
            </button>
        </div>
        <?php endif; ?>

        <!-- Form ulasan -->
        <div class="review-card" <?php echo $ulasan ? 'style="display: none;"' : ''; ?> id="reviewForm">
            <h5 class="mb-4">Bagikan Pengalaman Anda</h5>
            <form action="proses_ulasan.php" method="POST">
                <div class="mb-4">
                    <label class="form-label">Rating</label>
                    <div class="rating-input">
                        <input type="radio" id="star5" name="rating" value="5" <?php echo $ulasan && $ulasan->rating == 5 ? 'checked' : ''; ?> required>
                        <label for="star5"><i class="bi bi-star-fill"></i></label>
                        <input type="radio" id="star4" name="rating" value="4" <?php echo $ulasan && $ulasan->rating == 4 ? 'checked' : ''; ?>>
                        <label for="star4"><i class="bi bi-star-fill"></i></label>
                        <input type="radio" id="star3" name="rating" value="3" <?php echo $ulasan && $ulasan->rating == 3 ? 'checked' : ''; ?>>
                        <label for="star3"><i class="bi bi-star-fill"></i></label>
                        <input type="radio" id="star2" name="rating" value="2" <?php echo $ulasan && $ulasan->rating == 2 ? 'checked' : ''; ?>>
                        <label for="star2"><i class="bi bi-star-fill"></i></label>
                        <input type="radio" id="star1" name="rating" value="1" <?php echo $ulasan && $ulasan->rating == 1 ? 'checked' : ''; ?>>
                        <label for="star1"><i class="bi bi-star-fill"></i></label>
                    </div>
                </div>
                <div class="mb-4">
                    <label for="testimoni" class="form-label">Ulasan Anda</label>
                    <textarea class="form-control" id="testimoni" name="testimoni" rows="4" required><?php echo $ulasan ? htmlspecialchars($ulasan->testimoni) : ''; ?></textarea>
                </div>
                <?php if ($ulasan): ?>
                    <input type="hidden" name="id" value="<?php echo $ulasan->_id; ?>">
                <?php endif; ?>
                <button type="submit" class="btn btn-submit">
                    <i class="bi bi-send"></i> <?php echo $ulasan ? 'Update Ulasan' : 'Kirim Ulasan'; ?>
                </button>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function enableEdit() {
            document.querySelector('.review-card').style.display = 'none';
            document.getElementById('reviewForm').style.display = 'block';
        }
    </script>
</body>
</html> 