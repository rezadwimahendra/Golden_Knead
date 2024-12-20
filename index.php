<?php
session_start();
require_once 'koneksi.php';

// Redirect jika sudah login
if (isset($_SESSION['pelanggan_id'])) {
    header("Location: user/beranda.php");
    exit();
}

// Fungsi untuk membersihkan session messages setelah ditampilkan
function clearMessages() {
    if(isset($_SESSION['error'])) {
        unset($_SESSION['error']);
    }
    if(isset($_SESSION['success'])) {
        unset($_SESSION['success']); 
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Golden Knead - Premium Bakery menyajikan roti dan kue berkualitas tinggi">
    <meta name="keywords" content="bakery, roti, kue, premium, golden knead">
    <title>Golden Knead - Premium Bakery</title>
    
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="assets/images/favicon.ico">
    
    <!-- CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <!-- Pindahkan CSS ke file terpisah: styles.css -->
    <link rel="stylesheet" href="assets/css/styles.css">
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg fixed-top">
        <div class="container">
            <a class="navbar-brand" href="index.php">
                <i class="bi bi-bread-slice"></i> Golden Knead
            </a>
            <div class="ms-auto">
                <button class="btn btn-login me-2" data-bs-toggle="modal" data-bs-target="#loginModal">
                    <i class="bi bi-box-arrow-in-right"></i> Masuk
                </button>
                <button class="btn btn-register" data-bs-toggle="modal" data-bs-target="#registerModal">
                    <i class="bi bi-person-plus"></i> Daftar
                </button>
            </div>
        </div>
    </nav>

    <!-- Alert Messages -->
    <?php if (isset($_SESSION['error']) || isset($_SESSION['success'])): ?>
    <div class="container alert-container">
        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <?= htmlspecialchars($_SESSION['error']) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <?= htmlspecialchars($_SESSION['success']) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>
        <?php clearMessages(); ?>
    </div>
    <?php endif; ?>

    <!-- Hero Section -->
    <div class="hero-section">
        <div class="container text-center">
            <h1 class="display-4 mb-4">Selamat Datang di Golden Knead</h1>
            <p class="lead mb-4">Nikmati kelezatan roti produk kami yang dibuat dengan cinta dan bahan berkualitas</p>
            <button type="button" class="btn btn-register btn-lg" data-bs-toggle="modal" data-bs-target="#registerModal">
                <i class="bi bi-person-plus"></i> Mulai Berbelanja
            </button>
        </div>
    </div>

    <!-- Main Content -->
    <main class="container-fluid px-0">
        <!-- Featured Products Section -->
        <section class="products-section">
            <div class="container">
                <h2 class="section-title">Produk Unggulan Kami</h2>
                <div class="row g-4">
                    <div class="col-md-4">
                        <div class="card">
                            <div class="product-badge">Bestseller</div>
                            <div class="card-img-wrapper">
                                <img src="assets/images/donut-premium.jpg" class="card-img-top" alt="Premium Donut">
                            </div>
                            <div class="card-body">
                                <h5 class="card-title">Premium Artisan Donuts</h5>
                                <p class="card-text">Donat lembut dengan berbagai pilihan topping premium. Dibuat segar setiap hari dengan bahan berkualitas tinggi.</p>
                                <p class="product-price">Rp 5.000 - Rp 10.000</p>
                                <button class="btn btn-order w-100" data-bs-toggle="modal" data-bs-target="#loginModal">
                                    <i class="bi bi-bag-plus"></i> Pesan Sekarang
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card">
                            <div class="product-badge">New Arrival</div>
                            <div class="card-img-wrapper">
                                <img src="assets/images/brownies-premium.png" class="card-img-top" alt="Premium Brownies">
                            </div>
                            <div class="card-body">
                                <h5 class="card-title">Signature Brownies</h5>
                                <p class="card-text">Brownies premium dengan cokelat Belgium berkualitas tinggi. Tekstur yang lembut dan rasa yang mewah.</p>
                                <p class="product-price">Rp 45.000 - Rp 75.000</p>
                                <button class="btn btn-order w-100" data-bs-toggle="modal" data-bs-target="#loginModal">
                                    <i class="bi bi-bag-plus"></i> Pesan Sekarang
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card">
                            <div class="product-badge">Chef's Choice</div>
                            <div class="card-img-wrapper">
                                <img src="assets/images/bread-premium.jpg" class="card-img-top" alt="Artisan Bread">
                            </div>
                            <div class="card-body">
                                <h5 class="card-title">Sourdough</h5>
                                <p class="card-text">Roti sourdough autentik yang difermentasi selama 24 jam untuk rasa yang kaya dan tekstur yang sempurna.</p>
                                <p class="product-price">Rp 10.000 - Rp 165.000</p>
                                <button class="btn btn-order w-100" data-bs-toggle="modal" data-bs-target="#loginModal">
                                    <i class="bi bi-bag-plus"></i> Pesan Sekarang
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Testimonial Section -->
        <section class="testimonial-section py-5">
            <div class="container">
                <h2 class="section-title text-center mb-5">Testimoni Pelanggan</h2>
                <div class="row g-4">
                    <div class="col-md-4">
                        <div class="testimonial-card">
                            <div class="testimonial-content">
                                <div class="rating mb-3">
                                    <i class="bi bi-star-fill text-warning"></i>
                                    <i class="bi bi-star-fill text-warning"></i>
                                    <i class="bi bi-star-fill text-warning"></i>
                                    <i class="bi bi-star-fill text-warning"></i>
                                    <i class="bi bi-star-fill text-warning"></i>
                                </div>
                                <p class="testimonial-text">"Roti yang luar biasa enak! Teksturnya lembut dan rasanya pas. Saya selalu membeli roti di sini untuk acara keluarga."</p>
                                <div class="testimonial-author">
                                    <div class="author-info">
                                        <h5 class="author-name">Sarah Wijaya</h5>
                                        <p class="author-title">Pelanggan Setia</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="testimonial-card">
                            <div class="testimonial-content">
                                <div class="rating mb-3">
                                    <i class="bi bi-star-fill text-warning"></i>
                                    <i class="bi bi-star-fill text-warning"></i>
                                    <i class="bi bi-star-fill text-warning"></i>
                                    <i class="bi bi-star-fill text-warning"></i>
                                    <i class="bi bi-star-fill text-warning"></i>
                                </div>
                                <p class="testimonial-text">"Premium Artisan Donuts-nya sangat recommended! Topping-nya bervariasi dan rasa manisnya pas. Pelayanannya juga ramah."</p>
                                <div class="testimonial-author">
                                    <div class="author-info">
                                        <h5 class="author-name">Budi Santoso</h5>
                                        <p class="author-title">Food Enthusiast</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="testimonial-card">
                            <div class="testimonial-content">
                                <div class="rating mb-3">
                                    <i class="bi bi-star-fill text-warning"></i>
                                    <i class="bi bi-star-fill text-warning"></i>
                                    <i class="bi bi-star-fill text-warning"></i>
                                    <i class="bi bi-star-fill text-warning"></i>
                                    <i class="bi bi-star-half text-warning"></i>
                                </div>
                                <p class="testimonial-text">"Signature Brownies-nya menjadi favorit keluarga. Cokelatnya premium dan teksturnya sempurna. Pasti akan pesan lagi!"</p>
                                <div class="testimonial-author">
                                    <div class="author-info">
                                        <h5 class="author-name">Linda Kusuma</h5>
                                        <p class="author-title">Ibu Rumah Tangga</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="row">
                <div class="col-md-6 mb-4">
                    <h5><i class="bi bi-bread-slice"></i> Golden Knead</h5>
                    <p>Toko roti dengan kualitas terbaik sejak 2023.</p>
                </div>
                <div class="col-md-6 mb-4">
                    <h5>Kontak Kami</h5>
                    <p>
                        <i class="bi bi-geo-alt"></i> Jl. Roti No. 123, Jakarta<br>
                        <i class="bi bi-telephone"></i> (021) 123-4567<br>
                        <i class="bi bi-envelope"></i> info@goldenknead.com
                    </p>
                </div>
            </div>
            <hr class="bg-white">
            <div class="text-center">
                <p class="mb-0">&copy; 2023 Golden Knead. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <!-- Modals -->
    <?php include 'components/auth_modals.php'; ?>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Debug: Log semua elemen modal yang ditemukan
            console.log('Modal elements:', document.querySelectorAll('.modal'));
            
            // Debug: Log tombol register
            var registerBtn = document.querySelector('[data-bs-target="#registerModal"]');
            console.log('Register button:', registerBtn);
            
            // Debug: Log modal register
            var registerModal = document.getElementById('registerModal');
            console.log('Register modal:', registerModal);

            // Inisialisasi modal register
            if (registerModal) {
                var bsRegisterModal = new bootstrap.Modal(registerModal);
                
                // Tambahkan event listener ke tombol
                if (registerBtn) {
                    registerBtn.addEventListener('click', function(e) {
                        e.preventDefault();
                        console.log('Register button clicked');
                        bsRegisterModal.show();
                    });
                }
            }

            // Auto-hide alerts
            setTimeout(function() {
                const alerts = document.querySelectorAll('.alert');
                alerts.forEach(function(alert) {
                    const bsAlert = new bootstrap.Alert(alert);
                    bsAlert.close();
                });
            }, 5000);
        });
    </script>
</body>
</html>
<?php clearMessages(); ?> 