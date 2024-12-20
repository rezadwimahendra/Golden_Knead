<?php
session_start();
require_once '../koneksi.php';

// Cek jika admin belum login
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

// Ambil data testimoni yang akan diedit
if (isset($_GET['id'])) {
    try {
        $id = $_GET['id'];
        $testimoni = $testimoniCollection->findOne(['_id' => new MongoDB\BSON\ObjectId($id)]);
        
        if (!$testimoni) {
            $_SESSION['error'] = "Testimoni tidak ditemukan!";
            header("Location: testimoni.php");
            exit();
        }
    } catch (Exception $e) {
        $_SESSION['error'] = "Error: " . $e->getMessage();
        header("Location: testimoni.php");
        exit();
    }
} else {
    header("Location: testimoni.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Testimoni - Golden Knead</title>
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
        }

        .navbar-brand {
            color: white !important;
            font-weight: 700;
            font-size: 26px;
            font-family: 'Playfair Display', serif;
        }

        .nav-link {
            color: white !important;
            font-weight: 500;
            padding: 10px 20px !important;
            border-radius: 25px;
            transition: all 0.3s ease;
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

        .card {
            border: none;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
        }

        .card-header {
            background: linear-gradient(135deg, var(--primary-color), var(--soft-color));
            color: white;
            border-radius: 20px 20px 0 0 !important;
            padding: 20px 25px;
        }

        .btn-save {
            background: linear-gradient(135deg, var(--tertiary-color), #ff7f50);
            color: white;
            border: none;
            padding: 12px 25px;
            border-radius: 25px;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .btn-save:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.15);
            color: white;
        }

        .btn-cancel {
            background: #6c757d;
            color: white;
            border: none;
            padding: 12px 25px;
            border-radius: 25px;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .btn-cancel:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.15);
            color: white;
            background: #5a6268;
        }

        @media (max-width: 768px) {
            .page-title {
                font-size: 2rem;
            }

            .card-header {
                padding: 15px 20px;
            }

            .btn-save, .btn-cancel {
                padding: 10px 20px;
            }
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg fixed-top">
        <div class="container">
            <span class="navbar-brand">
                <i class="bi bi-shield-lock"></i> Admin Panel
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
                        <a class="nav-link" href="pelanggan.php">
                            <i class="bi bi-people"></i> Pelanggan
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="testimoni.php">
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
        <h1 class="page-title">Edit Testimoni</h1>

        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Form Edit Testimoni</h5>
            </div>
            <div class="card-body">
                <form action="proses_edit_testimoni.php" method="POST">
                    <input type="hidden" name="id" value="<?php echo $testimoni->_id; ?>">
                    
                    <div class="mb-3">
                        <label for="nama" class="form-label">Nama Pelanggan</label>
                        <input type="text" class="form-control" id="nama" name="nama" value="<?php echo htmlspecialchars($testimoni->nama); ?>" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="peran" class="form-label">Peran/Status</label>
                        <input type="text" class="form-control" id="peran" name="peran" value="<?php echo htmlspecialchars($testimoni->peran); ?>" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="rating" class="form-label">Rating</label>
                        <select class="form-select" id="rating" name="rating" required>
                            <option value="5" <?php echo $testimoni->rating == 5 ? 'selected' : ''; ?>>5 Bintang</option>
                            <option value="4.5" <?php echo $testimoni->rating == 4.5 ? 'selected' : ''; ?>>4.5 Bintang</option>
                            <option value="4" <?php echo $testimoni->rating == 4 ? 'selected' : ''; ?>>4 Bintang</option>
                            <option value="3.5" <?php echo $testimoni->rating == 3.5 ? 'selected' : ''; ?>>3.5 Bintang</option>
                            <option value="3" <?php echo $testimoni->rating == 3 ? 'selected' : ''; ?>>3 Bintang</option>
                        </select>
                    </div>
                    
                    <div class="mb-4">
                        <label for="testimoni" class="form-label">Testimoni</label>
                        <textarea class="form-control" id="testimoni" name="testimoni" rows="4" required><?php echo htmlspecialchars($testimoni->testimoni); ?></textarea>
                    </div>
                    
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-save">
                            <i class="bi bi-check-lg"></i> Simpan Perubahan
                        </button>
                        <a href="testimoni.php" class="btn btn-cancel">
                            <i class="bi bi-x-lg"></i> Batal
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 