<?php
session_start();
include 'koneksi_pembeli.php';

if (!isset($_SESSION['pelanggan'])) { header("Location: login.php"); exit; }
if (empty($_SESSION['keranjang'])) { header("Location: index_pembeli.php"); exit; }

// Hitung total belanja
$total_belanja = 0;
foreach ($_SESSION['keranjang'] as $id_menu => $jumlah) {
    $ambil = mysqli_query($koneksi, "SELECT * FROM menu WHERE id_menu = '$id_menu'");
    $row = mysqli_fetch_assoc($ambil);
    $total_belanja += ($row['harga'] * $jumlah);
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Checkout | Mie Padeh</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { font-family: 'Poppins', sans-serif; background: #fdfdfd; padding-top: 30px; }
        .card-checkout { background: white; border-radius: 20px; padding: 30px; box-shadow: 0 10px 30px rgba(0,0,0,0.05); border: none; }
        .btn-bayar { background: #dc3545; color: white; border-radius: 12px; padding: 15px; border: none; width: 100%; font-weight: 600; }
        .method-box { background: #fff5f5; padding: 15px; border-radius: 12px; border: 1px dashed #dc3545; }
    </style>
</head>
<body>

<div class="container mb-5">
    <div class="row">
        <div class="col-md-7 mb-4">
            <div class="card-checkout h-100">
                <h5 class="fw-bold mb-4 border-bottom pb-2">Ringkasan Belanja</h5>
                <table class="table table-borderless align-middle">
                    <?php foreach ($_SESSION['keranjang'] as $id_menu => $jumlah): 
                        $ambil = mysqli_query($koneksi, "SELECT * FROM menu WHERE id_menu = '$id_menu'");
                        $row = mysqli_fetch_assoc($ambil);
                    ?>
                    <tr>
                        <td class="fw-bold"><?= $row['nama_menu']; ?> <small class="text-muted">x<?= $jumlah; ?></small></td>
                        <td class="text-end">Rp <?= number_format($row['harga'] * $jumlah); ?></td>
                    </tr>
                    <?php endforeach; ?>
                    <tr class="border-top">
                        <td class="fw-bold pt-3">Total Bayar</td>
                        <td class="text-end pt-3 fw-bold text-danger fs-5">Rp <?= number_format($total_belanja); ?></td>
                    </tr>
                </table>
            </div>
        </div>

        <div class="col-md-5">
            <div class="card-checkout">
                <h5 class="fw-bold mb-4 border-bottom pb-2">Metode Pembayaran</h5>
                <form method="POST">
                    <div class="mb-3">
                        <label class="small text-muted fw-bold">PILIH CARA BAYAR</label>
                        <select name="metode" class="form-select form-select-lg" style="font-size: 0.9rem;" required>
                            <option value="">-- Pilih Metode --</option>
                            <option value="Transfer Bank (BCA)">Transfer Bank BCA</option>
                            <option value="E-Wallet (OVO/Gopay)">QRIS / Gopay / OVO</option>
                            <option value="Bayar di Toko (COD)">Bayar di Tempat (COD)</option>
                        </select>
                    </div>

                    <div class="method-box mb-4 mt-4">
                        <small class="text-muted d-block mb-2">Informasi Pembeli:</small>
                        <h6 class="fw-bold m-0"><?= $_SESSION['pelanggan']['nama_pelanggan']; ?></h6>
                        <small class="text-muted"><?= $_SESSION['pelanggan']['telepon']; ?></small>
                    </div>
                    
                    <button name="proses" class="btn btn-bayar shadow">KONFIRMASI & PESAN SEKARANG</button>
                </form>

                <?php 
                if (isset($_POST['proses'])) {
                    $id_pelanggan = $_SESSION['pelanggan']['id_pelanggan'];
                    $tgl_pesan = date("Y-m-d H:i:s");
                    $metode = $_POST['metode'];

                    // 1. Simpan ke tabel pesanan (Termasuk metode pembayaran)
                    mysqli_query($koneksi, "INSERT INTO pesanan (id_pelanggan, tanggal_pesanan, total_bayar, metode_pembayaran, status) 
                                            VALUES ('$id_pelanggan', '$tgl_pesan', '$total_belanja', '$metode', 'Pending')");
                    
                    $id_pesanan_baru = mysqli_insert_id($koneksi);

                    // 2. Simpan rincian & Kurangi Stok
                    foreach ($_SESSION['keranjang'] as $id_menu => $jumlah) {
                        $ambil_m = mysqli_query($koneksi, "SELECT harga FROM menu WHERE id_menu = '$id_menu'");
                        $row_m = mysqli_fetch_assoc($ambil_m);
                        $sub = $row_m['harga'] * $jumlah;

                        mysqli_query($koneksi, "INSERT INTO detail_pesanan (id_pesanan, id_menu, jumlah, subtotal) VALUES ('$id_pesanan_baru', '$id_menu', '$jumlah', '$sub')");
                        mysqli_query($koneksi, "UPDATE menu SET stok = stok - $jumlah WHERE id_menu = '$id_menu'");
                    }

                    unset($_SESSION['keranjang']);
                    echo "<script>alert('Pesanan Berhasil via $metode!'); location='riwayat.php';</script>";
                }
                ?>
            </div>
        </div>
    </div>
</div>

</body>
</html>