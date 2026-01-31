<?php 
session_start();
include 'koneksi_pembeli.php'; 

// Jika keranjang benar-benar tidak ada atau isinya kosong
if (!isset($_SESSION['keranjang']) || empty($_SESSION['keranjang'])) {
    echo "<script>alert('Keranjang belanja kosong, silakan pilih menu dulu!');</script>";
    echo "<script>location='index_pembeli.php';</script>";
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Keranjang Belanja | Mie Padeh</title>
    <!-- Google Fonts & Bootstrap -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        body { font-family: 'Poppins', sans-serif; background-color: #fdfdfd; padding-top: 50px; }
        .table-container { background: white; border-radius: 25px; padding: 40px; box-shadow: 0 15px 35px rgba(0,0,0,0.05); border: 1px solid #eee; }
        .img-cart { width: 70px; height: 70px; object-fit: cover; border-radius: 12px; }
        .btn-checkout { background: #dc3545; color: white; border-radius: 12px; padding: 12px 30px; border: none; font-weight: 600; transition: 0.3s; }
        .btn-checkout:hover { background: #c82333; transform: translateY(-2px); box-shadow: 0 5px 15px rgba(220, 53, 69, 0.3); }
        .text-padeh { color: #dc3545; }
    </style>
</head>
<body>

<div class="container mb-5">
    <div class="text-center mb-5">
        <h2 class="fw-bold">Keranjang <span class="text-padeh">Belanja</span></h2>
        <p class="text-muted small">Periksa kembali pesanan Mie Padeh favorit Anda sebelum checkout.</p>
    </div>
    
    <div class="table-container">
        <div class="table-responsive">
            <table class="table align-middle">
                <thead class="text-muted small uppercase">
                    <tr>
                        <th>MENU</th>
                        <th>HARGA</th>
                        <th>JUMLAH</th>
                        <th>SUBTOTAL</th>
                        <th class="text-center">AKSI</th>
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
                        <td>
                            <div class="d-flex align-items-center">
                                <!-- Foto Menu dari folder Assets -->
                                <img src="assets/<?= $row['foto']; ?>" class="img-cart me-3 border" onerror="this.src='https://placehold.co/100x100?text=Mie'">
                                <div>
                                    <h6 class="fw-bold mb-0"><?= $row['nama_menu']; ?></h6>
                                    <small class="text-muted"><?= $row['kategori']; ?></small>
                                </div>
                            </div>
                        </td>
                        <td>Rp <?= number_format($row['harga']); ?></td>
                        <td>
                            <span class="badge bg-light text-dark border px-3 py-2"><?= $jumlah; ?> Porsi</span>
                        </td>
                        <td class="fw-bold text-padeh">Rp <?= number_format($subtotal); ?></td>
                        <td class="text-center">
                            <!-- Tombol Hapus dengan Icon -->
                            <a href="hapus_keranjang.php?id=<?= $id_menu; ?>" class="btn btn-sm btn-outline-danger border-0" onclick="return confirm('Hapus menu ini dari keranjang?')">
                                <i class="fa fa-trash-can fa-lg"></i>
                            </a>
                        </td>
                    </tr>
                    <?php 
                    $total_belanja += $subtotal;
                    endforeach; 
                    ?>
                </tbody>
            </table>
        </div>

        <div class="row mt-5 align-items-center">
            <div class="col-md-6">
                <a href="index_pembeli.php" class="btn btn-link text-decoration-none text-muted small px-0">
                    <i class="fa fa-arrow-left me-2"></i> Tambah Menu Lainnya
                </a>
            </div>
            <div class="col-md-6 text-end">
                <h6 class="text-muted mb-1">Total Yang Harus Dibayar:</h6>
                <h3 class="fw-bold text-padeh mb-4">Rp <?= number_format($total_belanja); ?></h3>
                
                <a href="checkout.php" class="btn btn-checkout shadow">
                    Lanjut Checkout <i class="fa fa-arrow-right ms-2"></i>
                </a>
            </div>
        </div>
    </div>
</div>

<footer class="bg-white py-4 text-center mt-5 border-top">
    <p class="small mb-0 text-muted">&copy; 2024 Mie Padeh Project. Pedasnya Juara!</p>
</footer>

</body>
</html>