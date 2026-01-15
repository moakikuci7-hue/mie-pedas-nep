<?php 
session_start(); 
include 'koneksi_pembeli.php'; 

// Hitung total item di keranjang
$jumlah_keranjang = 0;
if (isset($_SESSION['keranjang'])) {
    foreach ($_SESSION['keranjang'] as $id => $jumlah) {
        $jumlah_keranjang += $jumlah;
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mie Padeh | Menu Pelanggan</title>
    
    <!-- Google Fonts & Bootstrap -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;700&family=Playfair+Display:wght@700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        :root { --primary: #dc3545; --dark: #1a1a1a; }
        body { font-family: 'Poppins', sans-serif; background-color: #fdfdfd; }
        
        .navbar { background: white; box-shadow: 0 2px 10px rgba(0,0,0,0.05); }
        .navbar-brand { font-weight: 700; color: var(--primary) !important; font-size: 1.5rem; }
        
        .hero { 
            background: linear-gradient(rgba(0,0,0,0.6), rgba(0,0,0,0.6)), url('https://images.unsplash.com/photo-1552611052-33e04de081de?auto=format&fit=crop&w=1350&q=80');
            background-size: cover; background-position: center; height: 45vh;
            display: flex; align-items: center; color: white; text-align: center;
        }

        .card-menu { 
            border: none; border-radius: 20px; overflow: hidden; transition: 0.4s; 
            box-shadow: 0 10px 30px rgba(0,0,0,0.05); height: 100%; background: white;
        }
        .card-menu:hover { transform: translateY(-10px); box-shadow: 0 15px 35px rgba(0,0,0,0.1); }
        
        .menu-img { 
            height: 220px; width: 100%; object-fit: cover; 
            background-color: #f8f9fa;
            display: block;
        }
        
        .price { color: var(--primary); font-weight: 700; font-size: 1.2rem; }
        .btn-order { background: var(--primary); color: white; border-radius: 30px; border: none; padding: 12px; transition: 0.3s; text-decoration: none; display: block; }
        .btn-order:hover { background: var(--dark); color: white; box-shadow: 0 5px 15px rgba(220, 53, 69, 0.3); }
        
        .badge-kat { background: #fff5f5; color: var(--primary); border-radius: 20px; padding: 5px 15px; font-size: 0.75rem; font-weight: 600; }
    </style>
</head>
<body>

    <!-- NAVBAR -->
    <nav class="navbar navbar-expand-lg sticky-top">
        <div class="container">
            <a class="navbar-brand" href="index_pembeli.php"><i class="fa-solid fa-pepper-hot"></i> MIE PADEH</a>
            <div class="ms-auto">
                <a href="keranjang.php" class="text-dark position-relative me-2 text-decoration-none">
                    <i class="fa fa-shopping-cart fs-4"></i>
                    <?php if ($jumlah_keranjang > 0): ?>
                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="font-size: 0.6rem;">
                            <?= $jumlah_keranjang; ?>
                        </span>
                    <?php endif; ?>
                </a>
            </div>
        </div>
    </nav>

    <!-- HERO -->
    <header class="hero">
        <div class="container text-center">
            <h1 class="display-4 fw-bold">Nikmati Level Pedasmu!</h1>
            <p class="lead">Mie Padeh - Pedasnya Nagih, Rasanya Autentik.</p>
        </div>
    </header>

    <!-- MENU LIST -->
    <section class="py-5">
        <div class="container">
            <h3 class="fw-bold mb-5 text-center">Daftar Menu Favorit</h3>
            <div class="row g-4">
                <?php
                $query = mysqli_query($koneksi, "SELECT * FROM menu");
                while ($row = mysqli_fetch_assoc($query)) {
                    
                    // --- BAGIAN YANG DIUBAH: AMBIL DARI FOLDER ASSETS ---
                    $foto_menu = $row['foto'];
                    if (empty($foto_menu)) {
                        $img_path = "https://placehold.co/600x400?text=Mie+Padeh"; // Jika di DB kosong
                    } else {
                        $img_path = "assets/" . $foto_menu; // Ambil file dari folder assets
                    }
                ?>
                <div class="col-md-4 col-sm-6">
                    <div class="card card-menu">
                        <!-- Memanggil gambar lokal abang -->
                        <img src="<?= $img_path; ?>" class="menu-img" alt="<?= $row['nama_menu']; ?>" onerror="this.src='https://placehold.co/600x400?text=Foto+Bermasalah'">
                        
                        <div class="card-body p-4 text-center">
                            <span class="badge badge-kat mb-3"><?= $row['kategori']; ?></span>
                            <h5 class="fw-bold mb-2"><?= $row['nama_menu']; ?></h5>
                            <p class="price mb-4">Rp <?= number_format($row['harga'], 0, ',', '.'); ?></p>
                            
                            <a href="beli.php?id=<?= $row['id_menu']; ?>" class="btn btn-order fw-bold">
                                <i class="fa fa-cart-plus me-2"></i> Tambah ke Keranjang
                            </a>
                        </div>
                    </div>
                </div>
                <?php } ?>
            </div>
        </div>
    </section>

    <footer class="bg-dark text-white py-4 text-center">
        <p class="small mb-0">&copy; 2024 Mie Padeh Project. Crafted with Passion.</p>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>