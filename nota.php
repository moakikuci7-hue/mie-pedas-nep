<?php
session_start();
include 'koneksi_pembeli.php';

// 1. Ambil ID Pesanan dari URL
$id_nota = $_GET['id'];

// 2. Ambil data pesanan + data pelanggan (JOIN)
$ambil = mysqli_query($koneksi, "SELECT * FROM pesanan JOIN pelanggan 
    ON pesanan.id_pelanggan = pelanggan.id_pelanggan 
    WHERE pesanan.id_pesanan = '$id_nota'");
$detail = mysqli_fetch_assoc($ambil);

// SATPAM: Jika pembeli nakal mau lihat nota orang lain, tendang balik
if ($detail['id_pelanggan'] !== $_SESSION['pelanggan']['id_pelanggan']) {
    echo "<script>location='riwayat.php';</script>";
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Nota Pesanan #<?= $id_nota; ?> | Mie Padeh</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Poppins', sans-serif; background: #f4f7f6; padding: 50px 0; }
        .card-nota { max-width: 700px; margin: auto; background: white; border-radius: 20px; padding: 40px; border: none; box-shadow: 0 10px 30px rgba(0,0,0,0.05); }
        .line { border-top: 2px dashed #eee; margin: 20px 0; }
    </style>
</head>
<body>

<div class="container">
    <div class="card-nota">
        <!-- Header Nota -->
        <div class="row align-items-center">
            <div class="col-6">
                <h3 class="fw-bold text-danger m-0">MIE PADEH</h3>
                <small class="text-muted small">Pesanan #INV-<?= $id_nota; ?></small>
            </div>
            <div class="col-6 text-end">
                <p class="small text-muted mb-0">Tanggal Terbit:</p>
                <h6 class="fw-bold"><?= date('d F Y', strtotime($detail['tanggal_pesanan'])); ?></h6>
            </div>
        </div>

        <div class="line"></div>

        <!-- Info Pelanggan -->
        <div class="row mb-4">
            <div class="col-md-6">
                <p class="small text-muted mb-1">Dipesan Oleh:</p>
                <h6 class="fw-bold m-0"><?= $detail['nama_pelanggan']; ?></h6>
                <p class="small text-muted"><?= $detail['telepon']; ?></p>
            </div>
            <div class="col-md-6 text-end">
                <p class="small text-muted mb-1">Metode Pembayaran:</p>
                <h6 class="fw-bold text-uppercase"><?= $detail['metode_pembayaran']; ?></h6>
                <span class="badge bg-success small">STATUS: <?= $detail['status']; ?></span>
            </div>
        </div>

        <!-- Tabel Rincian Menu -->
        <table class="table table-borderless align-middle">
            <thead class="text-muted small border-bottom">
                <tr>
                    <th>MENU</th>
                    <th class="text-center">HARGA</th>
                    <th class="text-center">QTY</th>
                    <th class="text-end">SUBTOTAL</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                // Ambil data menu yang dibeli dari tabel detail_pesanan
                $ambil_item = mysqli_query($koneksi, "SELECT * FROM detail_pesanan JOIN menu 
                    ON detail_pesanan.id_menu = menu.id_menu 
                    WHERE detail_pesanan.id_pesanan = '$id_nota'");
                while($item = mysqli_fetch_assoc($ambil_item)):
                ?>
                <tr>
                    <td class="fw-bold py-3"><?= $item['nama_menu']; ?></td>
                    <td class="text-center">Rp <?= number_format($item['harga']); ?></td>
                    <td class="text-center"><?= $item['jumlah']; ?></td>
                    <td class="text-end fw-bold">Rp <?= number_format($item['subtotal']); ?></td>
                </tr>
                <?php endwhile; ?>
            </tbody>
            <tfoot class="border-top">
                <tr>
                    <th colspan="3" class="text-end py-4">Total Belanja:</th>
                    <th class="text-end py-4 text-danger fs-5">Rp <?= number_format($detail['total_bayar']); ?></th>
                </tr>
            </tfoot>
        </table>

        <div class="line"></div>
        
        <!-- Footer Nota -->
        <div class="text-center">
            <p class="small text-muted mb-4 text-uppercase fw-bold" style="letter-spacing: 2px;">-- Terima Kasih Telah Memesan --</p>
            <div class="d-print-none">
                <button onclick="window.print()" class="btn btn-outline-danger px-4 rounded-pill fw-bold btn-sm me-2">
                    <i class="fa fa-print"></i> Cetak Nota
                </button>
                <a href="riwayat.php" class="btn btn-danger px-4 rounded-pill fw-bold btn-sm">
                    Kembali Ke Riwayat
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Font Awesome -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</body>
</html>