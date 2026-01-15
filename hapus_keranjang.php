<?php
session_start();
$id_menu = $_GET['id'];
unset($_SESSION['keranjang'][$id_menu]);

echo "<script>alert('Menu telah dihapus dari keranjang');</script>";
echo "<script>location='keranjang.php';</script>";
?>