<?php 
session_start();
include 'koneksi_pembeli.php'; 

// Jika keranjang kosong, arahkan balik ke menu
if (empty($_SESSION['keranjang']) || !isset($_SESSION['keranjang'])) {
    echo "<script>alert('Keranjang kosong, silakan pilih menu dulu!');</script>";
    echo "<script>location='index_pembeli.php';</script>";
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Keranjang Belanja | Mie Padeh</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Poppins', sans-serif; background-color: #fdfdfd; }
        .table-container { background: white; border-radius: 20px; padding: 30px; box-shadow: 0 10px 30px rgba(0,0,0,0.05); }
        .btn-checkout { background: #dc3545; color: white; border-radius: 10px; padding: 10px 30px; border: none; }
    </style>
</head>
<body>

<div class="container py-5">
    <h2 class="fw-bold mb-4 text-center">Keranjang Belanja Anda</h2>
    
    <div class="table-container">
        <table class="table align-middle">
            <thead>
                <tr>
                    <th>Menu</th>
                    <th>Harga</th>
                    <th>Jumlah</th>
                    <th>Subtotal</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $total_belanja = 0;
                foreach ($_SESSION['keranjang'] as $id_menu => $jumlah): 
                    $query = mysqli_query($koneksi, "SELECT * FROM menu WHERE id_menu = '$id_menu'");
                    $row = mysqli_fetch_assoc($query);
                    $subtotal = $row['harga'] * $jumlah;
                ?>
                <tr>
                    <td><h6 class="fw-bold mb-0"><?= $row['nama_menu']; ?></h6></td>
                    <td>Rp <?= number_format($row['harga']); ?></td>
                    <td><?= $jumlah; ?></td>
                    <td class="fw-bold">Rp <?= number_format($subtotal); ?></td>
                    <td>
                        <a href="hapus_keranjang.php?id=<?= $id_menu; ?>" class="btn btn-sm btn-outline-danger">Batal</a>
                    </td>
                </tr>
                <?php 
                $total_belanja += $subtotal;
                endforeach; 
                ?>
            </tbody>
            <tfoot>
                <tr>
                    <th colspan="3" class="text-end py-4"><h5>Total Bayar:</h5></th>
                    <th colspan="2" class="py-4"><h5 class="text-danger fw-bold">Rp <?= number_format($total_belanja); ?></h5></th>
                </tr>
            </tfoot>
        </table>
        
        <div class="d-flex justify-content-between mt-4">
            <a href="index_pembeli.php" class="btn btn-outline-secondary">Tambah Menu Lain</a>
            <a href="checkout.php" class="btn btn-checkout fw-bold">Lanjut Pembayaran (Checkout)</a>
        </div>
    </div>
</div>

</body>
</html>