<?php
session_start();
require_once '../koneksi.php';

if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'];
    $nama = $_POST['nama'];
    $deskripsi = $_POST['deskripsi'];
    $harga = (int)$_POST['harga'];
    $status = $_POST['status'];

    // Cari produk yang akan diupdate
    $produk = $produkCollection->findOne(['_id' => new MongoDB\BSON\ObjectId($id)]);
    if (!$produk) {
        $_SESSION['error'] = "Produk tidak ditemukan!";
        header("Location: produk.php");
        exit();
    }

    $update = [
        'nama' => $nama,
        'deskripsi' => $deskripsi,
        'harga' => $harga,
        'status' => $status
    ];

    // Jika ada upload gambar baru
    if (isset($_FILES['gambar']) && $_FILES['gambar']['size'] > 0) {
        $target_dir = "../assets/images/products/";
        if (!file_exists($target_dir)) {
            mkdir($target_dir, 0777, true);
        }

        $file_extension = strtolower(pathinfo($_FILES["gambar"]["name"], PATHINFO_EXTENSION));
        $new_filename = uniqid() . '.' . $file_extension;
        $target_file = $target_dir . $new_filename;
        $uploadOk = 1;

        // Cek jika file adalah gambar
        $check = getimagesize($_FILES["gambar"]["tmp_name"]);
        if($check === false) {
            $_SESSION['error'] = "File bukan gambar.";
            $uploadOk = 0;
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
            header("Location: edit_produk.php?id=" . $id);
            exit();
        } else {
            if (move_uploaded_file($_FILES["gambar"]["tmp_name"], $target_file)) {
                // Hapus gambar lama jika ada
                if (isset($produk->gambar) && file_exists("../" . $produk->gambar)) {
                    unlink("../" . $produk->gambar);
                }
                
                $update['gambar'] = "assets/images/products/" . $new_filename;
            } else {
                $_SESSION['error'] = "Gagal mengupload gambar.";
                header("Location: edit_produk.php?id=" . $id);
                exit();
            }
        }
    }

    $result = $produkCollection->updateOne(
        ['_id' => new MongoDB\BSON\ObjectId($id)],
        ['$set' => $update]
    );

    if ($result->getModifiedCount() > 0) {
        $_SESSION['success'] = "Produk berhasil diperbarui!";
        header("Location: produk.php");
    } else {
        $_SESSION['error'] = "Tidak ada perubahan pada produk.";
        header("Location: edit_produk.php?id=" . $id);
    }
} else {
    $_SESSION['error'] = "Metode tidak diizinkan!";
    header("Location: produk.php");
}
exit();
?> 