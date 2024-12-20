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
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil Saya - Golden Knead</title>
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

        .card {
            border: none;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
            overflow: hidden;
            background: white;
        }

        .profile-header {
            background: linear-gradient(135deg, var(--primary-color), var(--soft-color));
            padding: 40px 20px;
            text-align: center;
            color: white;
            position: relative;
        }

        .profile-img {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            border: 5px solid rgba(255, 255, 255, 0.2);
            object-fit: cover;
            margin-bottom: 20px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
        }

        .profile-name {
            font-family: 'Playfair Display', serif;
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 10px;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.1);
        }

        .membership-badge {
            display: inline-block;
            padding: 8px 20px;
            background: linear-gradient(135deg, var(--accent-color), #ffd700);
            color: #2c3e50;
            border-radius: 25px;
            font-weight: 500;
            margin-bottom: 30px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }

        .membership-badge i {
            margin-right: 8px;
            color: #2c3e50;
        }

        .profile-stats {
            display: flex;
            justify-content: center;
            gap: 40px;
            margin-top: 20px;
        }

        .stat-item {
            text-align: center;
        }

        .stat-value {
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 5px;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.1);
        }

        .stat-label {
            font-size: 0.9rem;
            opacity: 0.9;
        }

        .profile-info {
            padding: 40px;
        }

        .info-label {
            color: var(--tertiary-color);
            font-weight: 600;
            margin-bottom: 8px;
            font-size: 0.9rem;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .info-value {
            color: #2c3e50;
            font-size: 1.1rem;
            margin-bottom: 25px;
            padding: 12px 20px;
            background: rgba(0, 0, 0, 0.02);
            border-radius: 10px;
        }

        .btn-edit {
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
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }

        .btn-edit:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.15);
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

            .profile-stats {
                gap: 20px;
            }

            .stat-value {
                font-size: 1.5rem;
            }

            .profile-info {
                padding: 20px;
            }

            .navbar-brand {
                font-size: 22px;
            }

            .profile-name {
                font-size: 2rem;
            }
        }

        @media (max-width: 767.98px) {
            .profile-header {
                padding: 30px 15px;
            }

            .profile-img {
                width: 120px;
                height: 120px;
            }

            .profile-name {
                font-size: 1.8rem;
            }

            .membership-badge {
                padding: 6px 15px;
                font-size: 0.85rem;
            }

            .stat-value {
                font-size: 1.3rem;
            }

            .stat-label {
                font-size: 0.8rem;
            }

            .info-label {
                font-size: 0.8rem;
            }

            .info-value {
                font-size: 1rem;
                padding: 10px 15px;
            }

            .btn-edit {
                padding: 10px 25px;
                font-size: 0.9rem;
            }
        }

        @media (max-width: 575.98px) {
            .profile-header {
                padding: 25px 10px;
            }

            .profile-img {
                width: 100px;
                height: 100px;
                margin-bottom: 15px;
            }

            .profile-name {
                font-size: 1.6rem;
            }

            .membership-badge {
                padding: 5px 12px;
                font-size: 0.8rem;
                margin-bottom: 20px;
            }

            .profile-stats {
                gap: 15px;
            }

            .stat-value {
                font-size: 1.2rem;
            }

            .stat-label {
                font-size: 0.75rem;
            }

            .profile-info {
                padding: 15px;
            }

            .info-label {
                font-size: 0.75rem;
                margin-bottom: 5px;
            }

            .info-value {
                font-size: 0.9rem;
                padding: 8px 12px;
                margin-bottom: 15px;
            }

            .btn-edit {
                padding: 8px 20px;
                font-size: 0.85rem;
            }

            .card {
                margin: 0 -0.5rem;
                border-radius: 15px;
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
                        <a class="nav-link active" href="profil.php">
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
        <div class="card">
            <div class="profile-header">
                <?php
                $profileImage = isset($pelanggan->foto) ? "../" . $pelanggan->foto : "../assets/images/default-profile.jpg";
                ?>
                <img src="<?php echo htmlspecialchars($profileImage); ?>" alt="Profile" class="profile-img">
                <h2 class="profile-name"><?php echo htmlspecialchars($pelanggan->nama); ?></h2>
                <div class="membership-badge">
                    <i class="bi bi-star-fill"></i> Member
                </div>
                <div class="profile-stats">
                    <div class="stat-item">
                        <div class="stat-value">
                            <?php 
                            $totalPesanan = $pesananCollection->count(['pelanggan_id' => $_SESSION['pelanggan_id']]);
                            echo $totalPesanan;
                            ?>
                        </div>
                        <div class="stat-label">Pesanan</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-value">
                            <?php 
                            if (isset($pelanggan->tanggal_daftar)) {
                                echo date('Y', $pelanggan->tanggal_daftar->toDateTime()->getTimestamp());
                            } else {
                                echo "N/A";
                            }
                            ?>
                        </div>
                        <div class="stat-label">Bergabung</div>
                    </div>
                </div>
            </div>
            <div class="profile-info">
                <div class="row">
                    <div class="col-md-6">
                        <div class="info-label">Email</div>
                        <div class="info-value"><?php echo htmlspecialchars($pelanggan->email); ?></div>
                    </div>
                    <div class="col-md-6">
                        <div class="info-label">Nomor Telepon</div>
                        <div class="info-value"><?php echo htmlspecialchars($pelanggan->telepon); ?></div>
                    </div>
                    <div class="col-12">
                        <div class="info-label">Alamat</div>
                        <div class="info-value"><?php echo htmlspecialchars($pelanggan->alamat); ?></div>
                    </div>
                    <div class="col-12 text-center mt-4">
                        <button class="btn btn-edit" onclick="location.href='edit_profil.php'">
                            <i class="bi bi-pencil"></i> Edit Profil
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 