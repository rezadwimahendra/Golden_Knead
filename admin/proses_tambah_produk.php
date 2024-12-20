<?php
session_start();
require_once '../koneksi.php';

if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama = $_POST['nama'];
    $deskripsi = $_POST['deskripsi'];
    $harga = (int)$_POST['harga'];
    $status = $_POST['status'];

    // Upload gambar
    $target_dir = "../assets/images/products/";
    if (!file_exists($target_dir)) {
        mkdir($target_dir, 0777, true);
    }

    $file_extension = strtolower(pathinfo($_FILES["gambar"]["name"], PATHINFO_EXTENSION));
    $new_filename = uniqid() . '.' . $file_extension;
    $target_file = $target_dir . $new_filename;
    $uploadOk = 1;

    // Cek jika file adalah gambar
    if(isset($_FILES["gambar"])) {
        $check = getimagesize($_FILES["gambar"]["tmp_name"]);
        if($check === false) {
            $_SESSION['error'] = "File bukan gambar.";
            $uploadOk = 0;
        }
    }

    // Cek ukuran file (max 5MB)
    if ($_FILES["gambar"]["size"] > 5000000) {
        $_SESSION['error'] = "Ukuran file terlalu besar (max 5MB).";
        $uploadOk = 0;
    }

    // Hanya izinkan format tertentu
    if($file_extension != "jpg" && $file_extension != "png" && $file_extension != "jpeg") {
        $_SESSION['error'] = "Hanya file JPG, JPEG, & PNG yang diizinkan.";
        $uploadOk = 0;
    }

    if ($uploadOk == 0) {
        header("Location: produk.php");
        exit();
    } else {
        if (move_uploaded_file($_FILES["gambar"]["tmp_name"], $target_file)) {
            $gambar_path = "assets/images/products/" . $new_filename;
            
            $produk = [
                'nama' => $nama,
                'deskripsi' => $deskripsi,
                'harga' => $harga,
                'gambar' => $gambar_path,
                'status' => $status,
                'tanggal_dibuat' => new MongoDB\BSON\UTCDateTime(time() * 1000)
            ];

            $result = $produkCollection->insertOne($produk);

            if ($result->getInsertedCount() > 0) {
                $_SESSION['success'] = "Produk berhasil ditambahkan!";
            } else {
                $_SESSION['error'] = "Gagal menambahkan produk.";
                unlink($target_file); // Hapus file jika gagal insert ke database
            }
        } else {
            $_SESSION['error'] = "Gagal mengupload gambar.";
        }
    }
} else {
    $_SESSION['error'] = "Metode tidak diizinkan!";
}

header("Location: produk.php");
exit();
?> 