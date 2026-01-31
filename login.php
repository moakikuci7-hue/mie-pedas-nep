<?php 
session_start();
include 'koneksi_pembeli.php'; // Pastikan file koneksi ada di folder utama
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Multiuser | Mie Padeh</title>
    <!-- Google Fonts & Bootstrap -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        body { font-family: 'Poppins', sans-serif; background: #fdfdfd; height: 100vh; display: flex; align-items: center; justify-content: center; }
        .login-container { max-width: 450px; width: 100%; background: white; padding: 40px; border-radius: 25px; box-shadow: 0 15px 35px rgba(0,0,0,0.05); border: 1px solid #eee; }
        .btn-padeh { background: #dc3545; color: white; border-radius: 12px; padding: 12px; border: none; width: 100%; font-weight: 600; transition: 0.3s; }
        .btn-padeh:hover { background: #c82333; transform: translateY(-2px); }
        .form-control { border-radius: 12px; padding: 12px; background: #f9f9f9; border: 1px solid #eee; }
        .form-control:focus { background: white; border-color: #dc3545; box-shadow: none; }
        .role-select { background: #fff5f5; border: 1px solid #ffdada; border-radius: 15px; padding: 15px; margin-bottom: 25px; }
    </style>
</head>
<body>

<div class="login-container">
    <div class="text-center mb-4">
        <h3 class="fw-bold text-danger"><i class="fa-solid fa-pepper-hot"></i> MIE PADEH</h3>
        <p class="text-muted small">Silakan masuk untuk mulai memesan</p>
    </div>

    <!-- PESAN NOTIFIKASI -->
    <?php 
    if (isset($_GET['pesan'])) {
        if ($_GET['pesan'] == "sukses_daftar") {
            echo "<div class='alert alert-success small text-center rounded-pill'>Pendaftaran sukses! Silakan login.</div>";
        }
    }
    ?>

    <form method="POST">
        <!-- PILIHAN ROLE (ADMIN / PELANGGAN) -->
        <div class="role-select">
            <label class="small fw-bold text-danger mb-2 d-block text-center">MASUK SEBAGAI:</label>
            <div class="d-flex justify-content-around">
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="role" id="pelanggan" value="pelanggan" checked>
                    <label class="form-check-label small fw-bold" for="pelanggan">Pelanggan</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="role" id="admin" value="admin">
                    <label class="form-check-label small fw-bold" for="admin">Admin</label>
                </div>
            </div>
        </div>

        <div class="mb-3">
            <label class="small text-muted fw-bold">USERNAME / EMAIL</label>
            <input type="text" name="user_email" class="form-control" placeholder="Masukkan akun Anda" required>
        </div>
        <div class="mb-4">
            <label class="small text-muted fw-bold">PASSWORD</label>
            <input type="password" name="password" class="form-control" placeholder="******" required>
        </div>

        <button type="submit" name="login" class="btn btn-padeh shadow-sm">MASUK SEKARANG</button>
        
        <div class="text-center mt-4">
            <p class="small text-muted">Belum punya akun? <a href="daftar.php" class="text-danger text-decoration-none fw-bold">Daftar Pelanggan</a></p>
            <hr>
            <a href="index_pembeli.php" class="small text-secondary text-decoration-none"><i class="fa fa-arrow-left me-1"></i> Kembali ke Menu Utama</a>
        </div>
    </form>

    <?php 
    if (isset($_POST['login'])) {
        $user_email = mysqli_real_escape_string($koneksi, $_POST['user_email']);
        $password = mysqli_real_escape_string($koneksi, $_POST['password']);
        $role = $_POST['role'];

        if ($role == 'admin') {
            // Logika Login Admin
            $query = mysqli_query($koneksi, "SELECT * FROM admin WHERE username='$user_email' AND password='$password'");
            if (mysqli_num_rows($query) == 1) {
                $_SESSION['admin'] = mysqli_fetch_assoc($query);
                echo "<script>alert('Login Admin Berhasil!'); location='admin/index.php';</script>";
            } else {
                echo "<div class='alert alert-danger mt-3 small text-center rounded-pill'>Akun Admin tidak ditemukan!</div>";
            }
        } else {
            // Logika Login Pelanggan
            $query = mysqli_query($koneksi, "SELECT * FROM pelanggan WHERE email='$user_email' AND password='$password'");
            if (mysqli_num_rows($query) == 1) {
                $_SESSION['pelanggan'] = mysqli_fetch_assoc($query);
                echo "<script>alert('Login Pelanggan Berhasil!'); location='index_pembeli.php';</script>";
            } else {
                echo "<div class='alert alert-danger mt-3 small text-center rounded-pill'>Akun Pelanggan tidak ditemukan!</div>";
            }
        }
    }
    ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>