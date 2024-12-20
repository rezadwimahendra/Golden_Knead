<!-- Login Modal -->
<div class="modal fade" id="loginModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Masuk</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form action="user/proses_login.php" method="POST">
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Kata Sandi</label>
                        <input type="password" class="form-control" id="password" name="password" required>
                    </div>
                    <button type="submit" class="btn btn-order w-100">
                        <i class="bi bi-box-arrow-in-right"></i> Masuk
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Register Modal -->
<div class="modal fade" id="registerModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Daftar Akun Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form action="user/proses_daftar.php" method="POST">
                    <div class="mb-3">
                        <label for="nama" class="form-label">Nama Lengkap</label>
                        <input type="text" class="form-control" id="nama" name="nama" 
                               oninvalid="this.setCustomValidity('Mohon isi bidang ini')"
                               oninput="this.setCustomValidity('')"
                               required>
                    </div>
                    <div class="mb-3">
                        <label for="email_daftar" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email_daftar" name="email"
                               oninvalid="this.setCustomValidity('Mohon isi bidang ini')"
                               oninput="this.setCustomValidity('')"
                               required>
                    </div>
                    <div class="mb-3">
                        <label for="password_daftar" class="form-label">Kata Sandi</label>
                        <input type="password" class="form-control" id="password_daftar" name="password"
                               oninvalid="this.setCustomValidity('Mohon isi bidang ini')"
                               oninput="this.setCustomValidity('')"
                               required>
                    </div>
                    <div class="mb-3">
                        <label for="telepon" class="form-label">Nomor Telepon</label>
                        <input type="tel" class="form-control" id="telepon" name="telepon"
                               oninvalid="this.setCustomValidity('Mohon isi bidang ini')"
                               oninput="this.setCustomValidity('')"
                               required>
                    </div>
                    <div class="mb-3">
                        <label for="alamat" class="form-label">Alamat Lengkap</label>
                        <textarea class="form-control" id="alamat" name="alamat" rows="3"
                                  oninvalid="this.setCustomValidity('Mohon isi bidang ini')"
                                  oninput="this.setCustomValidity('')"
                                  required></textarea>
                    </div>
                    <button type="submit" class="btn btn-order w-100">
                        <i class="bi bi-person-plus"></i> Daftar Sekarang
                    </button>
                </form>
            </div>
        </div>
    </div>
</div> 