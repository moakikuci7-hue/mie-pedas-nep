<?php
session_start();
if (!isset($_SESSION['admin'])) { header("Location: ../login.php"); exit; }
include 'koneksi.php';

$id_admin = $_SESSION['admin']['id_admin'];
$query = mysqli_query($koneksi, "SELECT * FROM admin WHERE id_admin = '$id_admin'");
$data = mysqli_fetch_assoc($query);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Profil Saya | Admin Mie Padeh</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { font-family: 'Poppins', sans-serif; background-color: #f4f7f6; }
        .card-profil { max-width: 600px; margin: 50px auto; background: white; border-radius: 20px; padding: 40px; box-shadow: 0 10px 30px rgba(0,0,0,0.05); }
        .btn-update { background: #333; color: white; border-radius: 10px; padding: 12px; border: none; width: 100%; font-weight: 600; }
    </style>
</head>
<body>
    <div class="container">
        <div class="card-profil">
            <h4 class="fw-bold mb-4 text-center">Edit Profil Admin</h4>
            <form action="proses_profil.php" method="POST">
                <div class="mb-3">
                    <label class="small text-muted">Username</label>
                    <input type="text" name="username" class="form-control" value="<?= $data['username']; ?>" required>
                </div>
                <div class="mb-3">
                    <label class="small text-muted">Nama Lengkap</label>
                    <input type="text" name="nama_lengkap" class="form-control" value="<?= $data['nama_lengkap']; ?>" required>
                </div>
                <div class="mb-4">
                    <label class="small text-muted">Password Baru</label>
                    <input type="password" name="password" class="form-control" placeholder="Kosongkan jika tidak ingin ganti password">
                </div>
                <button type="submit" name="update" class="btn btn-update">SIMPAN PERUBAHAN</button>
                <div class="text-center mt-3">
                    <a href="index.php" class="text-muted small text-decoration-none">Kembali ke Dashboard</a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>