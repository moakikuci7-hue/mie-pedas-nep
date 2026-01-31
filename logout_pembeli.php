<?php
session_start();

// Menghapus data login pelanggan saja
unset($_SESSION['pelanggan']);

// Jika ingin sekalian mengosongkan keranjang saat logout, aktifkan baris di bawah ini:
// unset($_SESSION['keranjang']);

echo "<script>alert('Anda telah berhasil logout.');</script>";
echo "<script>location='index_pembeli.php';</script>";
?>