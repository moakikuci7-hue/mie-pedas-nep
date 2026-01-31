<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit;
}
include 'koneksi.php';
$id = $_GET['id'];
$query = mysqli_query($koneksi, "SELECT * FROM menu WHERE id_menu = '$id'");
$data = mysqli_fetch_assoc($query);
?>
<!DOCTYPE html>
<html lang="id">
<!-- ... (Sisa kodingan Form Edit kamu di bawahnya) ... -->
<?php 
include 'koneksi.php';
$id = $_GET['id'];
$query = mysqli_query($koneksi, "SELECT * FROM menu WHERE id_menu = '$id'");
$data = mysqli_fetch_assoc($query);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit Menu | Mie Padeh</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { font-family: 'Poppins', sans-serif; background-color: #f4f7f6; padding: 50px; }
        .card-form { max-width: 500px; margin: auto; background: white; padding: 30px; border-radius: 15px; box-shadow: 0 10px 30px rgba(0,0,0,0.05); }
        .btn-update { background: #2196F3; color: white; width: 100%; border-radius: 10px; padding: 12px; border: none; transition: 0.3s; }
        .btn-update:hover { background: #1976D2; }
        .img-preview { width: 100%; max-height: 200px; object-fit: cover; border-radius: 10px; margin-bottom: 10px; border: 1px solid #ddd; }
    </style>
</head>
<body>
    <div class="card-form">
        <h4 class="fw-bold mb-4 text-center">Edit Menu</h4>
        
        <!-- PENTING: Harus ada enctype="multipart/form-data" -->
        <form action="proses_edit.php" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="id_menu" value="<?= $data['id_menu']; ?>">

            <div class="mb-3">
                <label class="small text-muted">Nama Menu</label>
                <input type="text" name="nama_menu" class="form-control" value="<?= $data['nama_menu']; ?>" required>
            </div>

            <div class="mb-3">
                <label class="small text-muted">Kategori</label>
                <select name="kategori" class="form-select">
                    <option value="Makanan" <?= ($data['kategori'] == 'Makanan') ? 'selected' : ''; ?>>Makanan</option>
                    <option value="Minuman" <?= ($data['kategori'] == 'Minuman') ? 'selected' : ''; ?>>Minuman</option>
                    <option value="Snack" <?= ($data['kategori'] == 'Snack') ? 'selected' : ''; ?>>Snack</option>
                </select>
            </div>

            <div class="mb-3">
                <label class="small text-muted">Harga (Rp)</label>
                <input type="number" name="harga" class="form-control" value="<?= $data['harga']; ?>" required>
            </div>

            <div class="mb-3">
                <label class="small text-muted">Stok</label>
                <input type="number" name="stok" class="form-control" value="<?= $data['stok']; ?>" required>
            </div>

            <!-- INI BAGIAN YANG TADI BELUM ADA -->
            <div class="mb-4">
                <label class="small text-muted">Foto Menu</label><br>
                <!-- Menampilkan foto yang ada sekarang -->
                <img src="../assets/<?= $data['foto']; ?>" class="img-preview" alt="Foto Sekarang" onerror="this.src='https://placehold.co/400x200?text=Belum+Ada+Foto'">
                <input type="file" name="foto" class="form-control">
                <small class="text-muted" style="font-size: 0.75rem;">*Pilih foto baru jika ingin mengganti</small>
            </div>

            <button type="submit" name="update" class="btn btn-update fw-bold text-white">SIMPAN PERUBAHAN</button>
            <div class="text-center mt-3">
                <a href="index.php" class="text-muted text-decoration-none small">Batal</a>
            </div>
        </form>
    </div>
</body>
</html>