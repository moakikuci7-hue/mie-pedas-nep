<?php
session_start();

// Hanya menghapus session admin
unset($_SESSION['admin']);

// Lempar balik ke login.php di folder utama (naik 1 level)
echo "<script>alert('Anda telah logout dari Admin Panel');</script>";
echo "<script>location='../login.php';</script>";
?>