<?php
session_start();
// Ambil ID menu dari tombol yang diklik
$id_menu = $_GET['id'];

// Jika menu sudah ada di keranjang, jumlahnya ditambah 1
if (isset($_SESSION['keranjang'][$id_menu])) {
    $_SESSION['keranjang'][$id_menu] += 1;
} 
// Jika belum ada, maka dianggap beli 1
else {
    $_SESSION['keranjang'][$id_menu] = 1;
}

// Setelah masuk keranjang, langsung arahkan ke halaman keranjang
echo "<script>alert('Menu telah masuk ke keranjang belanja');</script>";
echo "<script>location='keranjang.php';</script>";
?>