<?php
// 1. Cek session agar tidak bentrok
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// 2. SATPAM: Proteksi Admin
if (!isset($_SESSION['admin'])) {
    header("Location: ../login.php");
    exit;
}

include 'koneksi.php'; 
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Pesanan | Admin Mie Padeh</title>
    
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
        .table-container { background: white; border-radius: 12px; padding: 25px; box-shadow: 0 4px 10px rgba(0,0,0,0.03); }
        
        /* Status Badge Admin */
        .badge-pending { background: #fff9db; color: #f08c00; padding: 5px 12px; border-radius: 20px; font-size: 0.75rem; font-weight: 600; }
        .badge-proses { background: #e7f5ff; color: #1c7ed6; padding: 5px 12px; border-radius: 20px; font-size: 0.75rem; font-weight: 600; }
        .badge-selesai { background: #e6fcf5; color: #0ca678; padding: 5px 12px; border-radius: 20px; font-size: 0.75rem; font-weight: 600; }
        .badge-batal { background: #fff5f5; color: #dc3545; padding: 5px 12px; border-radius: 20px; font-size: 0.75rem; font-weight: 600; }
        
        .text-metode { font-size: 0.7rem; font-weight: 700; color: #888; background: #f8f9fa; padding: 4px 8px; border-radius: 6px; text-transform: uppercase; }
        .bukti-ada { color: #0ca678; font-size: 0.9rem; }
    </style>
</head>
<body>

    <!-- SIDEBAR -->
    <div class="sidebar">
        <div class="sidebar-brand mb-4 text-center">
            <i class="fa-solid fa-pepper-hot"></i> MIE PADEH
        </div>
        <a href="index.php"><i class="fa fa-home me-2"></i> Dashboard</a>
        <a href="index.php"><i class="fa fa-utensils me-2"></i> Menu Makanan</a>
        <a href="pesanan.php" class="active"><i class="fa fa-shopping-cart me-2"></i> Pesanan</a>
        <a href="profil.php"><i class="fa fa-user me-2"></i> Profil Saya</a>
        <a href="logout.php" class="mt-5 text-danger fw-bold"><i class="fa-solid fa-sign-out-alt me-2"></i> Logout</a>
    </div>

    <!-- MAIN CONTENT -->
    <div class="content">
        <div class="mb-4">
            <h3 class="fw-bold mb-0">Manajemen Pesanan</h3>
            <p class="text-muted small">Cek bukti pembayaran dan perbarui status pengiriman.</p>
        </div>

        <div class="table-container text-center">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr style="font-size: 0.8rem; color: #888; text-transform: uppercase; letter-spacing: 1px;">
                            <th>Nota</th>
                            <th>Tanggal</th>
                            <th>Nama Pelanggan</th>
                            <th>Total</th>
                            <th>Metode</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        // Query JOIN untuk ambil nama pelanggan
                        $query = mysqli_query($koneksi, "SELECT pesanan.*, pelanggan.nama_pelanggan 
                                                        FROM pesanan 
                                                        JOIN pelanggan ON pesanan.id_pelanggan = pelanggan.id_pelanggan 
                                                        ORDER BY pesanan.id_pesanan DESC");
                        
                        while ($row = mysqli_fetch_assoc($query)) {
                            $status = $row['status'];
                            $class = 'badge-pending';
                            if($status == 'Sudah Kirim Bukti') $class = 'badge-proses';
                            if($status == 'Selesai') $class = 'badge-selesai';
                            if($status == 'Dibatalkan') $class = 'badge-batal';
                        ?>
                        <tr style="font-size: 0.85rem;">
                            <td class="fw-bold text-muted">#<?= $row['id_pesanan']; ?></td>
                            <td><small><?= date('d/m/y H:i', strtotime($row['tanggal_pesanan'])); ?></small></td>
                            <td class="fw-medium text-start">
                                <?= $row['nama_pelanggan']; ?>
                                <!-- Indikator jika ada bukti bayar -->
                                <?php if(!empty($row['bukti_bayar'])): ?>
                                    <i class="fa-solid fa-camera bukti-ada ms-1" title="Sudah upload bukti"></i>
                                <?php endif; ?>
                            </td>
                            <td class="fw-bold text-danger">Rp <?= number_format($row['total_bayar']); ?></td>
                            <td><span class="text-metode"><?= $row['metode_pembayaran']; ?></span></td>
                            <td><span class="<?= $class; ?>"><?= $status; ?></span></td>
                            <td>
                                <a href="detail_pesanan.php?id=<?= $row['id_pesanan']; ?>" class="btn btn-sm btn-light border fw-bold" style="font-size: 0.75rem;">
                                    LIHAT & PROSES
                                </a>
                            </td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>