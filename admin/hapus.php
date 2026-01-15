<?php
include 'koneksi.php';

// Ambil ID dari URL
$id = $_GET['id'];

// Hapus data dari database
$query = "DELETE FROM menu WHERE id_menu = '$id'";
$hasil = mysqli_query($koneksi, $query);

if ($hasil) {
    header("Location: index.php?status=terhapus");
} else {
    echo "Gagal menghapus data";
}
?>