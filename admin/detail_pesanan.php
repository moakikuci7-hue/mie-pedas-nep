<?php
session_start();
// SATPAM: Proteksi Admin
if (!isset($_SESSION['admin'])) {
    header("Location: ../login.php");
    exit;
}
include 'koneksi.php';

// 1. Ambil ID Pesanan dari URL
$id_pesanan = $_GET['id'];

// 2. Ambil data pesanan + pelanggan (JOIN)
$ambil = mysqli_query($koneksi, "SELECT * FROM pesanan JOIN pelanggan 
    ON pesanan.id_pelanggan = pelanggan.id_pelanggan 
    WHERE pesanan.id_pesanan = '$id_pesanan'");
$detail = mysqli_fetch_assoc($ambil);

// Jika data tidak ditemukan
if (!$detail) {
    echo "<script>alert('Data pesanan tidak ditemukan!'); location='pesanan.php';</script>";
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Detail Pesanan #<?= $id_pesanan; ?> | Admin</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { font-family: 'Poppins', sans-serif; background-color: #f4f7f6; color: #444; }
        .sidebar { height: 100vh; width: 250px; position: fixed; background: #ffffff; border-right: 1px solid #eee; padding-top: 20px; }
        .sidebar-brand { padding: 10px 30px; font-weight: 600; font-size: 1.2rem; color: #dc3545; text-align: center; }
        .sidebar a { padding: 12px 30px; text-decoration: none; display: block; color: #777; transition: 0.3s; }
        .sidebar a:hover { background: #fff5f5; color: #dc3545; }
        .content { margin-left: 250px; padding: 40px; }
        .card-custom { background: white; border-radius: 15px; border: none; box-shadow: 0 5px 15px rgba(0,0,0,0.02); }
        .img-bukti { width: 100%; border-radius: 10px; border: 1px solid #eee; transition: 0.3s; }
        .img-bukti:hover { transform: scale(1.02); }
    </style>
</head>
<body>

    <!-- SIDEBAR -->
    <div class="sidebar">
        <div class="sidebar-brand mb-4 text-danger"><i class="fa-solid fa-pepper-hot"></i> MIE PADEH</div>
        <a href="index.php"><i class="fa fa-home me-2"></i> Dashboard</a>
        <a href="pesanan.php" class="active text-danger fw-bold"><i class="fa fa-shopping-cart me-2"></i> Pesanan</a>
        <a href="logout.php" class="mt-5 text-muted"><i class="fa fa-sign-out-alt me-2"></i> Logout</a>
    </div>

    <!-- MAIN CONTENT -->
    <div class="content">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3 class="fw-bold m-0">Proses Pesanan #<?= $id_pesanan; ?></h3>
            <a href="pesanan.php" class="btn btn-outline-secondary btn-sm rounded-pill px-3">Kembali</a>
        </div>

        <div class="row">
            <!-- INFO PESANAN -->
            <div class="col-md-7 mb-4">
                <div class="card card-custom p-4 h-100">
                    <h5 class="fw-bold mb-4 border-bottom pb-2">Daftar Belanja</h5>
                    <table class="table table-borderless align-middle">
                        <thead class="small text-muted border-bottom">
                            <tr>
                                <th>MENU</th>
                                <th>HARGA</th>
                                <th>QTY</th>
                                <th class="text-end">SUBTOTAL</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $total = 0;
                            $ambil_i = mysqli_query($koneksi, "SELECT * FROM detail_pesanan JOIN menu 
                                ON detail_pesanan.id_menu = menu.id_menu WHERE id_pesanan = '$id_pesanan'");
                            while($item = mysqli_fetch_assoc($ambil_i)):
                            ?>
                            <tr class="small">
                                <td class="fw-bold py-3"><?= $item['nama_menu']; ?></td>
                                <td>Rp <?= number_format($item['harga']); ?></td>
                                <td><?= $item['jumlah']; ?></td>
                                <td class="text-end fw-bold">Rp <?= number_format($item['subtotal']); ?></td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                        <tfoot class="border-top">
                            <tr>
                                <th colspan="3" class="text-end pt-4">Total Lunas:</th>
                                <th class="text-end pt-4 text-danger fs-5">Rp <?= number_format($detail['total_bayar']); ?></th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>

            <!-- BUKTI & UPDATE STATUS -->
            <div class="col-md-5 mb-4">
                <!-- BUKTI FOTO -->
                <div class="card card-custom p-4 mb-4">
                    <h5 class="fw-bold mb-3 border-bottom pb-2">Bukti Pembayaran</h5>
                    <?php if(!empty($detail['bukti_bayar'])): ?>
                        <a href="../assets/bukti/<?= $detail['bukti_bayar']; ?>" target="_blank">
                            <img src="../assets/bukti/<?= $detail['bukti_bayar']; ?>" class="img-bukti shadow-sm" alt="Foto Struk">
                        </a>
                        <small class="text-muted d-block text-center mt-2">*Klik gambar untuk melihat ukuran penuh</small>
                    <?php else: ?>
                        <div class="alert alert-warning text-center small m-0">Belum ada bukti yang diunggah.</div>
                    <?php endif; ?>
                </div>

                <!-- UPDATE STATUS -->
                <div class="card card-custom p-4">
                    <h5 class="fw-bold mb-3 border-bottom pb-2">Update Status</h5>
                    <form method="POST">
                        <select name="status" class="form-select mb-3 fw-bold bg-light">
                            <option value="Pending" <?= ($detail['status'] == 'Pending' ? 'selected' : ''); ?>>Pending (Belum Bayar)</option>
                            <option value="Sudah Kirim Bukti" <?= ($detail['status'] == 'Sudah Kirim Bukti' ? 'selected' : ''); ?>>Verifikasi Pembayaran</option>
                            <option value="Selesai" <?= ($detail['status'] == 'Selesai' ? 'selected' : ''); ?>>Selesai (Pesanan Lunas)</option>
                            <option value="Dibatalkan" <?= ($detail['status'] == 'Dibatalkan' ? 'selected' : ''); ?>>Dibatalkan</option>
                        </select>
                        <button name="update" class="btn btn-danger w-100 fw-bold shadow-sm py-2">UPDATE PESANAN</button>
                    </form>
                    <?php 
                    if (isset($_POST['update'])) {
                        $st = $_POST['status'];
                        mysqli_query($koneksi, "UPDATE pesanan SET status='$st' WHERE id_pesanan='$id_pesanan'");
                        echo "<script>alert('Status Berhasil Diperbarui!'); location='pesanan.php';</script>";
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
</body>
</html>