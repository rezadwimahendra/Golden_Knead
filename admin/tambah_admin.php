<?php
session_start();
require_once '../koneksi.php';

// Data admin default
$admin = [
    'nama' => 'Admin',
    'email' => 'admin@goldenknead.com',
    'password' => password_hash('admin123', PASSWORD_DEFAULT)
];

// Cek apakah admin sudah ada
$existingAdmin = $adminCollection->findOne(['email' => $admin['email']]);

if (!$existingAdmin) {
    // Tambahkan admin baru
    $result = $adminCollection->insertOne($admin);
    if ($result->getInsertedCount() > 0) {
        echo "Admin berhasil ditambahkan!<br>";
        echo "Email: " . $admin['email'] . "<br>";
        echo "Password: admin123<br>";
        echo "<a href='login.php'>Klik di sini untuk login</a>";
    } else {
        echo "Gagal menambahkan admin.";
    }
} else {
    echo "Admin sudah ada dalam database.<br>";
    echo "<a href='login.php'>Klik di sini untuk login</a>";
}
?> 