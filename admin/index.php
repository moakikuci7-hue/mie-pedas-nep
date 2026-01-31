<?php
// 1. Jalankan session di baris paling atas
session_start();

// 2. SATPAM: Cek apakah sudah login sebagai admin
// Jika tidak ada session admin, tendang ke login.php di folder utama (naik 1 folder)
if (!isset($_SESSION['admin'])) {
    header("Location: ../login.php");
    exit;
}

// 3. Panggil koneksi database (Pastikan file koneksi.php ada di dalam folder admin)
include 'koneksi.php'; 
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Mie Padeh | Dashboard</title>
    
    <!-- Link CSS Bootstrap & Font Awesome -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        body { font-family: 'Poppins', sans-serif; background-color: #f4f7f6; color: #444; }
        .sidebar { height: 100vh; width: 250px; position: fixed; background: #ffffff; border-right: 1px solid #eee; padding-top: 20px; z-index: 1000; }
        .sidebar-brand { padding: 10px 30px; font-weight: 600; font-size: 1.2rem; color: #dc3545; }
        .sidebar a { padding: 12px 30px; text-decoration: none; display: block; color: #777; transition: 0.3s; }
        .sidebar a:hover, .sidebar a.active { background: #fff5f5; color: #dc3545; border-right: 4px solid #dc3545; }
        .content { margin-left: 250px; padding: 40px; }
        .card-stats { border: none; border-radius: 12px; box-shadow: 0 4px 10px rgba(0,0,0,0.03); background: white; }
        .table-container { background: white; border-radius: 12px; padding: 25px; box-shadow: 0 4px 10px rgba(0,0,0,0.03); }
        .btn-padeh { background: #dc3545; color: white; border-radius: 8px; font-size: 0.9rem; padding: 8px 18px; border: none; }
        .btn-padeh:hover { background: #c82333; color: white; }
        .badge-kategori { background: #fff5f5; color: #dc3545; padding: 5px 12px; border-radius: 20px; font-size: 0.8rem; font-weight: 600; }
        .admin-info { background: #f8f9fa; margin: 10px 20px; padding: 10px; border-radius: 10px; border: 1px solid #eee; font-size: 0.85rem; }
    </style>
</head>
<body>

    <!-- SIDEBAR -->
    <div class="sidebar">
        <div class="sidebar-brand mb-2 text-center text-danger">
            <i class="fa-solid fa-pepper-hot"></i> MIE PADEH
        </div>
        
        <div class="admin-info text-center mb-4">
            <small class="text-muted">Admin Aktif:</small><br>
            <span class="fw-bold"><?= $_SESSION['admin']['nama_lengkap']; ?></span>
        </div>

        <a href="index.php" class="active"><i class="fa fa-home me-2"></i> Dashboard</a>
        <a href="index.php"><i class="fa fa-utensils me-2"></i> Menu Makanan</a>
        <a href="pesanan.php"><i class="fa fa-shopping-cart me-2"></i> Pesanan</a>
        <a href="profil.php"><i class="fa fa-user-circle me-2"></i> Profil Saya</a>
        
        <a href="logout.php" class="mt-5 text-danger fw-bold" onclick="return confirm('Yakin ingin keluar?')">
            <i class="fa-solid fa-sign-out-alt me-2"></i> Logout
        </a>
    </div>

    <!-- MAIN CONTENT -->
    <div class="content">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h3 class="fw-bold mb-0">Dashboard</h3>
                <p class="text-muted small">Ringkasan data operasional hari ini.</p>
            </div>
            <a href="tambah.php" class="btn btn-padeh shadow-sm"><i class="fa fa-plus me-2"></i> Tambah Menu</a>
        </div>

        <!-- STATISTIK -->
        <div class="row mb-4">
            <div class="col-md-4">
                <div class="card card-stats p-3 border-start border-danger border-4">
                    <p class="text-muted mb-1 small fw-bold text-uppercase">Total Menu</p>
                    <?php 
                        $res_menu = mysqli_query($koneksi, "SELECT count(*) as total FROM menu");
                        $data_menu = mysqli_fetch_assoc($res_menu);
                    ?>
                    <h3 class="fw-bold mb-0 text-danger"><?= $data_menu['total']; ?></h3>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card card-stats p-3 border-start border-primary border-4">
                    <p class="text-muted mb-1 small fw-bold text-uppercase">Total Pesanan</p>
                    <?php 
                        $res_order = mysqli_query($koneksi, "SELECT count(*) as total FROM pesanan");
                        $data_order = mysqli_fetch_assoc($res_order);
                    ?>
                    <h3 class="fw-bold mb-0 text-primary"><?= $data_order['total']; ?></h3>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card card-stats p-3 border-start border-success border-4">
                    <p class="text-muted mb-1 small fw-bold text-uppercase">Total Pendapatan</p>
                    <?php 
                        $res_money = mysqli_query($koneksi, "SELECT SUM(total_bayar) as total FROM pesanan");
                        $data_money = mysqli_fetch_assoc($res_money);
                    ?>
                    <h3 class="fw-bold mb-0 text-success">Rp <?= number_format($data_money['total'] ?? 0, 0, ',', '.'); ?></h3>
                </div>
            </div>
        </div>

        <!-- TABEL MENU -->
        <div class="table-container">
            <h5 class="fw-bold mb-4">Daftar Menu</h5>
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr style="font-size: 0.85rem; color: #888;">
                            <th>NO</th>
                            <th>NAMA MENU</th>
                            <th>KATEGORI</th>
                            <th>HARGA</th>
                            <th>STOK</th>
                            <th class="text-center">AKSI</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $no = 1;
                        $query = mysqli_query($koneksi, "SELECT * FROM menu");
                        while ($row = mysqli_fetch_assoc($query)) {
                        ?>
                        <tr style="font-size: 0.95rem;">
                            <td><?= $no++; ?></td>
                            <td class="fw-medium">
                                <img src="../assets/<?= $row['foto']; ?>" width="40" height="40" class="rounded me-2" style="object-fit: cover;" onerror="this.src='https://placehold.co/40/gray/white?text=Mie'">
                                <?= $row['nama_menu']; ?>
                            </td>
                            <td><span class="badge-kategori"><?= $row['kategori']; ?></span></td>
                            <td>Rp <?= number_format($row['harga'], 0, ',', '.'); ?></td>
                            <td><?= $row['stok']; ?> <small class="text-muted">pcs</small></td>
                            <td class="text-center">
                                <a href="edit.php?id=<?= $row['id_menu']; ?>" class="btn btn-sm text-primary border-0"><i class="fa fa-edit"></i></a>
                                <a href="hapus.php?id=<?= $row['id_menu']; ?>" onclick="return confirm('Hapus menu ini?')" class="btn btn-sm text-danger border-0"><i class="fa fa-trash"></i></a>
                            </td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS (Agar menu responsif berfungsi) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>