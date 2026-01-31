<?php
session_start();
include 'koneksi_pembeli.php';

// SATPAM: Harus login dulu
if (!isset($_SESSION['pelanggan'])) { 
    header("Location: login.php"); 
    exit; 
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Riwayat Belanja | Mie Padeh</title>
    <!-- Google Fonts & Bootstrap -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        body { font-family: 'Poppins', sans-serif; background-color: #fdfdfd; padding-top: 50px; }
        .card-riwayat { background: white; border-radius: 25px; padding: 40px; box-shadow: 0 15px 35px rgba(0,0,0,0.05); border: 1px solid #eee; }
        .text-padeh { color: #dc3545; }
        .badge-pending { background: #fff9db; color: #f08c00; }
        .badge-proses { background: #e7f5ff; color: #1c7ed6; }
        .badge-selesai { background: #e6fcf5; color: #0ca678; }
        .badge-batal { background: #fff5f5; color: #dc3545; }
        .badge-custom { padding: 8px 15px; border-radius: 20px; font-size: 0.75rem; font-weight: 600; }
        .btn-bayar { background: #dc3545; color: white; font-weight: 600; border-radius: 10px; font-size: 0.8rem; }
        .btn-bayar:hover { background: #c82333; color: white; }
    </style>
</head>
<body>

<div class="container mb-5">
    <div class="text-center mb-5">
        <h2 class="fw-bold">Riwayat <span class="text-padeh">Pesanan</span></h2>
        <p class="text-muted small">Halo <b><?= $_SESSION['pelanggan']['nama_pelanggan']; ?></b>, pantau status Mie Padeh favoritmu di sini.</p>
    </div>

    <div class="card-riwayat">
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="text-muted small uppercase">
                    <tr>
                        <th>ID NOTA</th>
                        <th>TANGGAL</th>
                        <th>TOTAL</th>
                        <th>PEMBAYARAN</th>
                        <th>STATUS</th>
                        <th class="text-center">AKSI</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $id_pelanggan = $_SESSION['pelanggan']['id_pelanggan'];
                    $ambil = mysqli_query($koneksi, "SELECT * FROM pesanan WHERE id_pelanggan = '$id_pelanggan' ORDER BY id_pesanan DESC");
                    
                    if (mysqli_num_rows($ambil) == 0) {
                        echo "<tr><td colspan='6' class='text-center py-5 text-muted small'>Belum ada pesanan. <a href='index_pembeli.php' class='text-danger'>Yuk pesan sekarang!</a></td></tr>";
                    }

                    while($row = mysqli_fetch_assoc($ambil)){
                        // Logika Badge Status
                        $status = $row['status'];
                        $class = "badge-pending";
                        if($status == "Sudah Kirim Bukti") $class = "badge-proses";
                        if($status == "Selesai") $class = "badge-selesai";
                        if($status == "Dibatalkan") $class = "badge-batal";
                    ?>
                    <tr style="font-size: 0.9rem;">
                        <td class="fw-bold text-muted">#INV-<?= $row['id_pesanan']; ?></td>
                        <td><small><?= date('d M Y, H:i', strtotime($row['tanggal_pesanan'])); ?></small></td>
                        <td class="fw-bold text-padeh">Rp <?= number_format($row['total_bayar']); ?></td>
                        <td><small class="text-muted fw-bold"><?= $row['metode_pembayaran']; ?></small></td>
                        <td><span class="badge-custom <?= $class; ?>"><?= $status; ?></span></td>
                        <td class="text-center">
                            
                            <?php if ($status == "Pending"): ?>
                                <!-- JIKA BELUM BAYAR -->
                                <a href="pembayaran.php?id=<?= $row['id_pesanan']; ?>" class="btn btn-sm btn-bayar px-3 shadow-sm">
                                    <i class="fa fa-wallet me-1"></i> Bayar Sekarang
                                </a>
                            <?php elseif ($status == "Sudah Kirim Bukti"): ?>
                                <!-- JIKA SUDAH UPLOAD -->
                                <button class="btn btn-sm btn-light border px-3 rounded-pill disabled small" style="font-size: 0.75rem;">
                                    Menunggu Verifikasi
                                </button>
                            <?php else: ?>
                                <!-- JIKA SELESAI / BATAL -->
                                <a href="nota.php?id=<?= $row['id_pesanan']; ?>" class="btn btn-sm btn-light border px-3 rounded-pill shadow-sm">
                                    <i class="fa fa-receipt me-1 text-danger"></i> Lihat Nota
                                </a>
                            <?php endif; ?>

                        </td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>

        <div class="text-center mt-4">
            <a href="index_pembeli.php" class="btn btn-link text-decoration-none text-muted small">
                <i class="fa fa-arrow-left me-2"></i> Kembali Belanja Mie Padeh
            </a>
        </div>
    </div>
</div>

<footer class="text-center text-muted small py-4 mt-5">
    &copy; 2024 Mie Padeh Project. Crafted with Passion.
</footer>

</body>
</html>