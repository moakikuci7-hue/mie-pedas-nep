<?php
$host = "localhost";
$user = "root";
$pass = "";
$db   = "mie_padeh"; // Sesuaikan dengan nama database tadi

$koneksi = mysqli_connect($host, $user, $pass, $db);

if (!$koneksi) {
    die("Koneksi ke database gagal: " . mysqli_connect_error());
}
?>