<?php
session_start();
include 'koneksi_pembeli.php';

// 1. SATPAM: Harus login
if (!isset($_SESSION['pelanggan'])) {
    header("Location: login.php");
    exit;
}

// 2. Ambil ID Pesanan & Datanya
$id_pesanan = mysqli_real_escape_string($koneksi, $_GET['id']);
$ambil = mysqli_query($koneksi, "SELECT * FROM pesanan WHERE id_pesanan = '$id_pesanan'");
$det = mysqli_fetch_assoc($ambil);

// 3. Proteksi: Cek kepemilikan pesanan
if ($det['id_pelanggan'] !== $_SESSION['pelanggan']['id_pelanggan']) {
    echo "<script>alert('Akses ilegal!'); location='riwayat.php';</script>";
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Konfirmasi Pembayaran | Mie Padeh</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        body { font-family: 'Poppins', sans-serif; background-color: #fdfdfd; padding-top: 50px; }
        .card-pay { background: white; border-radius: 25px; padding: 40px; box-shadow: 0 15px 35px rgba(0,0,0,0.05); border: 1px solid #eee; max-width: 550px; margin: auto; }
        .bank-info { background: #fff5f5; border: 1px dashed #dc3545; border-radius: 15px; padding: 20px; }
        .btn-kirim { background: #dc3545; color: white; border-radius: 12px; padding: 12px; font-weight: 600; width: 100%; border: none; transition: 0.3s; }
        .btn-kirim:hover { background: #c82333; transform: translateY(-2px); box-shadow: 0 5px 15px rgba(220, 53, 69, 0.3); }
        .text-padeh { color: #dc3545; }
        .qris-box { background: white; padding: 10px; border-radius: 10px; border: 1px solid #ddd; display: inline-block; }
    </style>
</head>
<body>

<div class="container mb-5">
    <div class="card-pay">
        <div class="text-center mb-4">
            <h3 class="fw-bold">Konfirmasi <span class="text-padeh">Bayar</span></h3>
            <p class="text-muted small">Pesanan <b>#INV-<?= $id_pesanan; ?></b> menunggu pembayaran Anda.</p>
        </div>

        <!-- TOTAL TAGIHAN -->
        <div class="text-center mb-4 p-3 border rounded-3 bg-light">
            <small class="text-muted d-block small">TOTAL YANG HARUS DIBAYAR</small>
            <h2 class="fw-bold text-danger m-0">Rp <?= number_format($det['total_bayar']); ?></h2>
        </div>

        <!-- INSTRUKSI PEMBAYARAN DINAMIS -->
        <div class="bank-info mb-4 text-center">
            <p class="small fw-bold text-danger mb-3 text-uppercase">
                <i class="fa fa-wallet me-2"></i> Metode: <?= $det['metode_pembayaran']; ?>
            </p>

            <?php if ($det['metode_pembayaran'] == 'Transfer Bank (BCA)'): ?>
                <!-- JIKA PILIH BCA -->
                <h6 class="mb-1 fw-bold text-dark">BANK BCA</h6>
                <h3 class="fw-bold text-dark mb-1">123-456-7890</h3>
                <p class="small text-muted mb-0">A/N MIE PADEH INDONESIA</p>

            <?php elseif ($det['metode_pembayaran'] == 'E-Wallet (OVO/Gopay)'): ?>
                <!-- JIKA PILIH E-WALLET -->
                <h6 class="mb-1 fw-bold text-dark">OVO / GOPAY / DANA</h6>
                <h4 class="fw-bold text-dark mb-3">0812-3456-7890</h4>
                <div class="qris-box mb-2">
                    <!-- Contoh QRIS Otomatis -->
                    <img src="https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=MiePadehPayment" width="150">
                </div>
                <p class="small text-muted d-block">Silakan Scan QRIS atau transfer ke nomor di atas.</p>

            <?php else: ?>
                <!-- JIKA PILIH COD -->
                <h6 class="mb-1 fw-bold text-dark">BAYAR DI TEMPAT (COD)</h6>
                <p class="small text-muted">Silakan siapkan uang pas saat kurir datang atau datang langsung ke outlet kami.</p>
            <?php endif; ?>
        </div>

        <!-- Form Upload -->
        <form method="POST" enctype="multipart/form-data">
            <div class="mb-4">
                <label class="small fw-bold mb-2">UPLOAD BUKTI TRANSFER (JPG/PNG)</label>
                <input type="file" name="bukti" class="form-control shadow-none" required>
                <small class="text-muted" style="font-size: 0.65rem;">*Max file 2MB. Pastikan foto terlihat jelas.</small>
            </div>

            <button type="submit" name="kirim" class="btn btn-kirim shadow">
                KIRIM BUKTI PEMBAYARAN <i class="fa fa-paper-plane ms-2"></i>
            </button>
            
            <div class="text-center mt-3">
                <a href="riwayat.php" class="text-muted small text-decoration-none">Bayar Nanti / Kembali</a>
            </div>
        </form>

        <?php 
        if (isset($_POST['kirim'])) {
            $nama_bukti = $_FILES['bukti']['name'];
            $lokasi_bukti = $_FILES['bukti']['tmp_name'];
            $ekstensi = strtolower(pathinfo($nama_bukti, PATHINFO_EXTENSION));

            // Validasi format
            if (!in_array($ekstensi, ['jpg', 'jpeg', 'png'])) {
                echo "<script>alert('Gagal! Hanya boleh upload foto (JPG/PNG).');</script>";
            } else {
                // Beri nama file unik
                $nama_f = date("YmdHis") . "_" . $id_pesanan . "." . $ekstensi;
                
                // Pindahkan ke folder assets/bukti/
                if (move_uploaded_file($lokasi_bukti, "assets/bukti/" . $nama_f)) {
                    mysqli_query($koneksi, "UPDATE pesanan SET bukti_bayar='$nama_f', status='Sudah Kirim Bukti' WHERE id_pesanan='$id_pesanan'");
                    echo "<script>alert('Terima kasih! Bukti telah terkirim.'); location='riwayat.php';</script>";
                } else {
                    echo "<script>alert('Gagal upload! Pastikan folder assets/bukti sudah Anda buat.');</script>";
                }
            }
        }
        ?>
    </div>
</div>

<footer class="text-center text-muted small py-4">
    &copy; 2024 Mie Padeh Official.
</footer>

</body>
</html>