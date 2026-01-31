<?php
session_start();
include 'koneksi_pembeli.php';

if (!isset($_SESSION['pelanggan'])) { header("Location: login.php"); exit; }

$id_p = $_SESSION['pelanggan']['id_pelanggan'];
$ambil = mysqli_query($koneksi, "SELECT * FROM pelanggan WHERE id_pelanggan = '$id_p'");
$data = mysqli_fetch_assoc($ambil);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Profil Saya | Mie Padeh</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { font-family: 'Poppins', sans-serif; background: #fdfdfd; }
        .card-custom { max-width: 500px; margin: 50px auto; border-radius: 25px; border: none; box-shadow: 0 15px 35px rgba(0,0,0,0.05); }
        .btn-padeh { background: #dc3545; color: white; border-radius: 12px; font-weight: 600; padding: 12px; width: 100%; border: none; }
    </style>
</head>
<body>
    <div class="container">
        <div class="card card-custom p-4 mt-5">
            <h4 class="fw-bold text-danger text-center mb-4">Profil Akun Saya</h4>
            <form method="POST">
                <div class="mb-3">
                    <label class="small fw-bold">NAMA LENGKAP</label>
                    <input type="text" name="nama" class="form-control" value="<?= $data['nama_pelanggan']; ?>" required>
                </div>
                <div class="mb-3">
                    <label class="small fw-bold">EMAIL</label>
                    <input type="email" name="email" class="form-control" value="<?= $data['email']; ?>" required>
                </div>
                <div class="mb-3">
                    <label class="small fw-bold">NOMOR HP</label>
                    <input type="text" name="telepon" class="form-control" value="<?= $data['telepon']; ?>" required>
                </div>
                <div class="mb-4">
                    <label class="small fw-bold">GANTI PASSWORD</label>
                    <input type="password" name="password" class="form-control" placeholder="Isi jika ingin ganti">
                </div>
                <button name="update" class="btn btn-padeh shadow-sm">SIMPAN PERUBAHAN</button>
                <a href="index_pembeli.php" class="btn btn-link w-100 mt-2 text-decoration-none text-muted small">Kembali</a>
            </form>

            <?php
            if (isset($_POST['update'])) {
                $nama = $_POST['nama']; $email = $_POST['email'];
                $telp = $_POST['telepon']; $pass = $_POST['password'];

                if (!empty($pass)) {
                    mysqli_query($koneksi, "UPDATE pelanggan SET nama_pelanggan='$nama', email='$email', telepon='$telp', password='$pass' WHERE id_pelanggan='$id_p'");
                } else {
                    mysqli_query($koneksi, "UPDATE pelanggan SET nama_pelanggan='$nama', email='$email', telepon='$telp' WHERE id_pelanggan='$id_p'");
                }
                
                $_SESSION['pelanggan']['nama_pelanggan'] = $nama;
                echo "<script>alert('Data berhasil diubah!'); location='index_pembeli.php';</script>";
            }
            ?>
        </div>
    </div>
</body>
</html>