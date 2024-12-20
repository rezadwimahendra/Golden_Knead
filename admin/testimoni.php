<?php
session_start();
require_once '../koneksi.php';

// Cek jika admin belum login
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

// Ambil data testimoni
$testimoni = $testimoniCollection->find();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Testimoni - Golden Knead</title>
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
            margin-bottom: 30px;
        }

        .card-header {
            background: linear-gradient(135deg, var(--primary-color), var(--soft-color));
            color: white;
            border-radius: 20px 20px 0 0 !important;
            padding: 20px 25px;
        }

        .btn-add {
            background: linear-gradient(135deg, var(--tertiary-color), #ff7f50);
            color: white;
            border: none;
            padding: 12px 25px;
            border-radius: 25px;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .btn-add:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.15);
            color: white;
        }

        .table {
            margin-bottom: 0;
        }

        .table th {
            font-weight: 600;
            color: var(--tertiary-color);
            border-bottom: 2px solid var(--accent-color);
        }

        .table td {
            vertical-align: middle;
        }

        .rating {
            color: #ffc107;
        }

        .btn-action {
            padding: 8px 15px;
            border-radius: 20px;
            font-size: 0.9rem;
            margin: 0 5px;
        }

        .btn-edit {
            background: var(--accent-color);
            color: #2c3e50;
        }

        .btn-delete {
            background: #dc3545;
            color: white;
        }

        .btn-action:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }

        @media (max-width: 768px) {
            .page-title {
                font-size: 2rem;
            }

            .card-header {
                padding: 15px 20px;
            }

            .btn-add {
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
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="page-title mb-0">Kelola Testimoni</h1>
            <button class="btn btn-add" data-bs-toggle="modal" data-bs-target="#addTestimoniModal">
                <i class="bi bi-plus-lg"></i> Tambah Testimoni
            </button>
        </div>

        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Daftar Testimoni</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Nama</th>
                                <th>Peran</th>
                                <th>Rating</th>
                                <th>Testimoni</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($testimoni as $item): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($item->nama); ?></td>
                                <td><?php echo htmlspecialchars($item->peran); ?></td>
                                <td>
                                    <div class="rating">
                                        <?php
                                        $rating = $item->rating;
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
                                </td>
                                <td><?php echo htmlspecialchars($item->testimoni); ?></td>
                                <td>
                                    <button class="btn btn-action btn-edit" onclick="editTestimoni('<?php echo $item->_id; ?>')">
                                        <i class="bi bi-pencil"></i>
                                    </button>
                                    <button class="btn btn-action btn-delete" onclick="deleteTestimoni('<?php echo $item->_id; ?>')">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Testimoni Modal -->
    <div class="modal fade" id="addTestimoniModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Testimoni</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form action="proses_tambah_testimoni.php" method="POST">
                        <div class="mb-3">
                            <label for="nama" class="form-label">Nama Pelanggan</label>
                            <input type="text" class="form-control" id="nama" name="nama" required>
                        </div>
                        <div class="mb-3">
                            <label for="peran" class="form-label">Peran/Status</label>
                            <input type="text" class="form-control" id="peran" name="peran" required>
                        </div>
                        <div class="mb-3">
                            <label for="rating" class="form-label">Rating</label>
                            <select class="form-select" id="rating" name="rating" required>
                                <option value="5">5 Bintang</option>
                                <option value="4.5">4.5 Bintang</option>
                                <option value="4">4 Bintang</option>
                                <option value="3.5">3.5 Bintang</option>
                                <option value="3">3 Bintang</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="testimoni" class="form-label">Testimoni</label>
                            <textarea class="form-control" id="testimoni" name="testimoni" rows="4" required></textarea>
                        </div>
                        <button type="submit" class="btn btn-add w-100">
                            <i class="bi bi-plus-lg"></i> Tambah Testimoni
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function editTestimoni(id) {
            // Implementasi edit testimoni
            window.location.href = 'edit_testimoni.php?id=' + id;
        }

        function deleteTestimoni(id) {
            if (confirm('Apakah Anda yakin ingin menghapus testimoni ini?')) {
                window.location.href = 'proses_hapus_testimoni.php?id=' + id;
            }
        }
    </script>
</body>
</html> 