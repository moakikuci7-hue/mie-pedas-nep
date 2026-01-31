<?php
session_start();
include 'koneksi.php';

if (isset($_POST['update'])) {
    $id = $_SESSION['admin']['id_admin'];
    $user = $_POST['username'];
    $nama = $_POST['nama_lengkap'];
    $pass = $_POST['password'];

    if (!empty($pass)) {
        // Jika password diisi
        mysqli_query($koneksi, "UPDATE admin SET username='$user', nama_lengkap='$nama', password='$pass' WHERE id_admin='$id'");
    } else {
        // Jika password kosong
        mysqli_query($koneksi, "UPDATE admin SET username='$user', nama_lengkap='$nama' WHERE id_admin='$id'");
    }

    // Update session agar nama di dashboard langsung berubah
    $_SESSION['admin']['nama_lengkap'] = $nama;
    $_SESSION['admin']['username'] = $user;

    echo "<script>alert('Profil berhasil diperbarui!'); location='index.php';</script>";
}
?>