<?php include 'koneksi_pembeli.php'; ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Akun Pelanggan | Mie Padeh</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { font-family: 'Poppins', sans-serif; background: #fdfdfd; height: 100vh; display: flex; align-items: center; justify-content: center; }
        .register-container { max-width: 500px; width: 100%; background: white; padding: 40px; border-radius: 25px; box-shadow: 0 15px 35px rgba(0,0,0,0.05); border: 1px solid #eee; }
        .btn-padeh { background: #dc3545; color: white; border-radius: 12px; padding: 12px; border: none; width: 100%; font-weight: 600; transition: 0.3s; }
        .btn-padeh:hover { background: #c82333; transform: translateY(-2px); }
        .form-control { border-radius: 12px; padding: 12px; background: #f9f9f9; border: 1px solid #eee; }
        .form-control:focus { box-shadow: none; border-color: #dc3545; background: white; }
    </style>
</head>
<body>

<div class="register-container">
    <div class="text-center mb-4">
        <h3 class="fw-bold text-danger"><i class="fa-solid fa-user-plus"></i> DAFTAR AKUN</h3>
        <p class="text-muted small">Gabung sekarang dan nikmati Mie Padeh favoritmu!</p>
    </div>

    <form method="POST">
        <div class="mb-3">
            <label class="small text-muted fw-bold">NAMA LENGKAP</label>
            <input type="text" name="nama" class="form-control" placeholder="Contoh: Budi Santoso" required>
        </div>
        <div class="mb-3">
            <label class="small text-muted fw-bold">EMAIL</label>
            <input type="email" name="email" class="form-control" placeholder="budi@example.com" required>
        </div>
        <div class="mb-3">
            <label class="small text-muted fw-bold">PASSWORD</label>
            <input type="password" name="password" class="form-control" placeholder="******" required>
        </div>
        <div class="mb-4">
            <label class="small text-muted fw-bold">NO. WHATSAPP / TELEPON</label>
            <input type="text" name="telepon" class="form-control" placeholder="0812xxxxxx" required>
        </div>

        <button type="submit" name="daftar" class="btn btn-padeh shadow-sm">DAFTAR SEKARANG</button>
        
        <div class="text-center mt-4">
            <p class="small text-muted">Sudah punya akun? <a href="login.php" class="text-danger text-decoration-none fw-bold">Login di sini</a></p>
            <a href="index_pembeli.php" class="small text-secondary text-decoration-none"><i class="fa fa-arrow-left"></i> Kembali ke Menu Utama</a>
        </div>
    </form>

    <?php 
    if (isset($_POST['daftar'])) {
        // Membersihkan input agar aman
        $nama = mysqli_real_escape_string($koneksi, $_POST['nama']);
        $email = mysqli_real_escape_string($koneksi, $_POST['email']);
        $pass = mysqli_real_escape_string($koneksi, $_POST['password']);
        $telp = mysqli_real_escape_string($koneksi, $_POST['telepon']);

        // 1. Cek apakah email sudah terdaftar sebelumnya
        $ambil = mysqli_query($koneksi, "SELECT * FROM pelanggan WHERE email='$email'");
        $yangcocok = mysqli_num_rows($ambil);

        if ($yangcocok == 1) {
            echo "<script>alert('Gagal! Email ini sudah terdaftar. Silakan gunakan email lain.');</script>";
        } else {
            // 2. Simpan data ke database
            $query = "INSERT INTO pelanggan (email, password, nama_pelanggan, telepon) 
                      VALUES ('$email', '$pass', '$nama', '$telp')";
            $hasil = mysqli_query($koneksi, $query);

            if ($hasil) {
                // KIRIM KE LOGIN DENGAN PESAN SUKSES
                echo "<script>alert('Pendaftaran Berhasil! Silakan Login.'); location='login.php?pesan=sukses_daftar';</script>";
            } else {
                echo "<script>alert('Daftar gagal: " . mysqli_error($koneksi) . "');</script>";
            }
        }
    }
    ?>
</div>

</body>
</html>