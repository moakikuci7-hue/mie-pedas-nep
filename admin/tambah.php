<?php include 'koneksi.php'; ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Tambah Menu Baru | Mie Padeh</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { font-family: 'Poppins', sans-serif; background-color: #f4f7f6; padding: 50px; }
        .card-form { max-width: 500px; margin: auto; background: white; padding: 30px; border-radius: 15px; box-shadow: 0 10px 30px rgba(0,0,0,0.05); }
        .btn-padeh { background: #dc3545; color: white; width: 100%; border-radius: 10px; padding: 12px; border: none; font-weight: 600; }
    </style>
</head>
<body>

    <div class="card-form">
        <h4 class="fw-bold mb-4 text-center">Tambah Menu Baru</h4>
        
        <!-- PENTING: Harus pakai enctype="multipart/form-data" untuk upload foto -->
        <form action="proses_tambah.php" method="POST" enctype="multipart/form-data">
            <div class="mb-3">
                <label class="small text-muted">Nama Menu</label>
                <input type="text" name="nama_menu" class="form-control" placeholder="Contoh: Mie Padeh Level 10" required>
            </div>

            <div class="mb-3">
                <label class="small text-muted">Kategori</label>
                <select name="kategori" class="form-select">
                    <option value="Makanan">Makanan</option>
                    <option value="Minuman">Minuman</option>
                    <option value="Snack">Snack</option>
                </select>
            </div>

            <div class="mb-3">
                <label class="small text-muted">Harga (Rp)</label>
                <input type="number" name="harga" class="form-control" placeholder="15000" required>
            </div>

            <div class="mb-3">
                <label class="small text-muted">Stok Awal</label>
                <input type="number" name="stok" class="form-control" placeholder="50" required>
            </div>

            <div class="mb-4">
                <label class="small text-muted">Foto Menu</label>
                <input type="file" name="foto" class="form-control" required>
                <small class="text-muted" style="font-size: 0.7rem;">*Wajib upload foto menu</small>
            </div>

            <button type="submit" name="submit" class="btn btn-padeh">SIMPAN MENU</button>
            <div class="text-center mt-3">
                <a href="index.php" class="text-muted text-decoration-none small">Batal</a>
            </div>
        </form>
    </div>

</body>
</html>