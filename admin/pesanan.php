<?php include 'koneksi.php'; ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Pesanan | Admin Mie Padeh</title>
    
    <!-- Link Font & Icon -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        body { font-family: 'Poppins', sans-serif; background-color: #f4f7f6; color: #444; }
        
        /* Sidebar Menu (Sama dengan index.php) */
        .sidebar { height: 100vh; width: 250px; position: fixed; background: #ffffff; border-right: 1px solid #eee; padding-top: 20px; }
        .sidebar-brand { padding: 10px 30px; font-weight: 600; font-size: 1.2rem; color: #dc3545; }
        .sidebar a { padding: 12px 30px; text-decoration: none; display: block; color: #777; transition: 0.3s; }
        .sidebar a:hover, .sidebar a.active { background: #fff5f5; color: #dc3545; border-right: 4px solid #dc3545; }
        
        /* Area Konten */
        .content { margin-left: 250px; padding: 40px; }
        .table-container { background: white; border-radius: 12px; padding: 25px; box-shadow: 0 4px 10px rgba(0,0,0,0.03); }
        
        /* Status Badge */
        .badge-selesai { background: #e6fcf5; color: #0ca678; padding: 5px 12px; border-radius: 20px; font-size: 0.8rem; }
        .badge-pending { background: #fff9db; color: #f08c00; padding: 5px 12px; border-radius: 20px; font-size: 0.8rem; }
        .btn-detail { border: 1px solid #eee; color: #777; border-radius: 8px; font-size: 0.85rem; transition: 0.3s; }
        .btn-detail:hover { background: #f8f9fa; color: #dc3545; }
    </style>
</head>
<body>

    <!-- SIDEBAR -->
    <div class="sidebar">
        <div class="sidebar-brand mb-4">
            <i class="fa-solid fa-pepper-hot"></i> MIE PADEH
        </div>
        <a href="index.php"><i class="fa fa-home me-2"></i> Dashboard</a>
        <a href="#"><i class="fa fa-utensils me-2"></i> Menu Makanan</a>
        <a href="pesanan.php" class="active"><i class="fa fa-shopping-cart me-2"></i> Daftar Pesanan</a>
        <a href="#"><i class="fa fa-user me-2"></i> Admin</a>
        <a href="#" class="mt-5 text-muted"><i class="fa fa-sign-out-alt me-2"></i> Logout</a>
    </div>

    <!-- MAIN CONTENT -->
    <div class="content">
        <div class="mb-4">
            <h3 class="fw-bold mb-0">Daftar Pesanan</h3>
            <p class="text-muted small">Pantau semua pesanan masuk dari pelanggan.</p>
        </div>

        <div class="table-container">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr style="font-size: 0.85rem; color: #888; text-transform: uppercase;">
                            <th>No. Nota</th>
                            <th>Tanggal</th>
                            <th>Nama Pelanggan</th>
                            <th>Total Bayar</th>
                            <th>Status</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        // Ambil data dari tabel pesanan
                        $query = mysqli_query($koneksi, "SELECT * FROM pesanan ORDER BY id_pesanan DESC");
                        while ($row = mysqli_fetch_assoc($query)) {
                            // Cek status untuk warna badge
                            $status_class = ($row['status'] == 'Selesai') ? 'badge-selesai' : 'badge-pending';
                        ?>
                        <tr style="font-size: 0.95rem;">
                            <td class="fw-bold text-muted">#INV-<?= $row['id_pesanan']; ?></td>
                            <td><?= date('d M Y', strtotime($row['tanggal_pesanan'])); ?></td>
                            <td class="fw-medium"><?= $row['nama_pelanggan']; ?></td>
                            <td>Rp <?= number_format($row['total_bayar'], 0, ',', '.'); ?></td>
                            <td><span class="<?= $status_class; ?>"><?= $row['status']; ?></span></td>
                            <td class="text-center">
                                <a href="#" class="btn btn-sm btn-detail px-3">
                                    <i class="fa fa-eye me-1"></i> Detail
                                </a>
                            </td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</body>
</html>