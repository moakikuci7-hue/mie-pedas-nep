<?php 
session_start(); 
include 'koneksi_pembeli.php'; 

// 1. Hitung total item di keranjang untuk badge navbar
$jumlah_keranjang = 0;
if (isset($_SESSION['keranjang']) && is_array($_SESSION['keranjang'])) {
    foreach ($_SESSION['keranjang'] as $id => $jumlah) {
        $jumlah_keranjang += $jumlah;
    }
}

// 2. Logika Filter Kategori
$kategori_pilihan = isset($_GET['kat']) ? $_GET['kat'] : '';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mie Padeh | Menu Pelanggan</title>
    
    <!-- Google Fonts & Bootstrap -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        :root { --primary: #dc3545; --dark: #1a1a1a; }
        body { font-family: 'Poppins', sans-serif; background-color: #fdfdfd; overflow-x: hidden; }
        
        /* Navbar */
        .navbar { background: white; box-shadow: 0 2px 10px rgba(0,0,0,0.05); padding: 15px 0; }
        .navbar-brand { font-weight: 700; color: var(--primary) !important; font-size: 1.5rem; }
        
        /* Hero Section */
        .hero { 
            background: linear-gradient(rgba(0,0,0,0.6), rgba(0,0,0,0.6)), url('https://images.unsplash.com/photo-1552611052-33e04de081de?auto=format&fit=crop&w=1350&q=80');
            background-size: cover; background-position: center; height: 35vh;
            display: flex; align-items: center; justify-content: center; color: white; text-align: center;
        }

        /* Menu Card */
        .card-menu { border: none; border-radius: 20px; overflow: hidden; transition: 0.4s; box-shadow: 0 10px 30px rgba(0,0,0,0.05); height: 100%; background: white; }
        .card-menu:hover { transform: translateY(-10px); }
        .menu-img { height: 220px; width: 100%; object-fit: cover; background-color: #f8f9fa; }
        
        .price { color: var(--primary); font-weight: 700; font-size: 1.2rem; }
        .btn-order { background: var(--primary); color: white; border-radius: 30px; border: none; padding: 12px; transition: 0.3s; text-decoration: none; display: block; font-weight: 600; text-align: center; }
        .btn-order:hover { background: var(--dark); color: white; }
        
        .badge-kat { background: #fff5f5; color: var(--primary); border-radius: 20px; padding: 5px 15px; font-size: 0.75rem; font-weight: 600; }
        
        /* Tombol WA Floating */
        .btn-wa { position: fixed; bottom: 30px; right: 30px; background: #25d366; color: white; width: 60px; height: 60px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 30px; box-shadow: 0 5px 15px rgba(0,0,0,0.2); z-index: 1000; text-decoration: none; }
        
        /* Filter Style */
        .filter-btn { border-radius: 20px; padding: 8px 25px; margin: 5px; font-weight: 500; transition: 0.3s; border: 1px solid #ddd; color: #555; text-decoration: none; display: inline-block; }
        .filter-btn:hover, .filter-btn.active { background: var(--primary); color: white; border-color: var(--primary); }
    </style>
</head>
<body>

    <!-- NAVBAR -->
    <nav class="navbar navbar-expand-lg sticky-top">
        <div class="container">
            <a class="navbar-brand" href="index_pembeli.php"><i class="fa-solid fa-pepper-hot"></i> MIE PADEH</a>
            
            <div class="ms-auto d-flex align-items-center">
                <!-- Icon Keranjang -->
                <a href="keranjang.php" class="text-dark position-relative me-4 text-decoration-none">
                    <i class="fa fa-shopping-cart fs-5"></i>
                    <?php if ($jumlah_keranjang > 0): ?>
                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="font-size: 0.6rem;"><?= $jumlah_keranjang; ?></span>
                    <?php endif; ?>
                </a>

                <!-- Logika Login Pelanggan -->
                <?php if (isset($_SESSION['pelanggan'])): ?>
                    <div class="dropdown">
                        <a class="text-danger fw-bold text-decoration-none dropdown-toggle" href="#" id="userDrop" data-bs-toggle="dropdown">
                            Halo, <?= $_SESSION['pelanggan']['nama_pelanggan']; ?>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end shadow border-0 mt-2">
                            <!-- LINK EDIT PROFIL -->
                            <li><a class="dropdown-item small" href="profil_pembeli.php"><i class="fa fa-user-edit me-2"></i> Edit Profil</a></li>
                            <!-- LINK RIWAYAT -->
                            <li><a class="dropdown-item small" href="riwayat.php"><i class="fa fa-history me-2"></i> Riwayat Pesanan</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item small text-danger" href="logout_pembeli.php"><i class="fa fa-sign-out-alt me-2"></i> Logout</a></li>
                        </ul>
                    </div>
                <?php else: ?>
                    <!-- Tombol Jika Belum Login -->
                    <a href="login.php" class="btn btn-sm btn-outline-danger px-3 rounded-pill fw-bold me-2">Login</a>
                    <a href="daftar.php" class="btn btn-sm btn-danger px-3 rounded-pill fw-bold">Daftar</a>
                <?php endif; ?>
            </div>
        </div>
    </nav>

    <!-- HERO SECTION -->
    <header class="hero">
        <div class="container">
            <h1 class="display-5 fw-bold text-white">Level Pedasmu, Pilihanmu!</h1>
            <p class="lead text-white small">Bumbu Rahasia, Pedasnya Nagih.</p>
        </div>
    </header>

    <!-- FILTER KATEGORI -->
    <div class="container mt-5 text-center">
        <a href="index_pembeli.php" class="filter-btn <?= $kategori_pilihan == '' ? 'active' : '' ?>">Semua Menu</a>
        <a href="index_pembeli.php?kat=Makanan" class="filter-btn <?= $kategori_pilihan == 'Makanan' ? 'active' : '' ?>">Makanan</a>
        <a href="index_pembeli.php?kat=Minuman" class="filter-btn <?= $kategori_pilihan == 'Minuman' ? 'active' : '' ?>">Minuman</a>
    </div>

    <!-- MENU LIST -->
    <section class="py-5">
        <div class="container">
            <div class="row g-4">
                <?php
                // Query dengan Filter Kategori
                $sql = "SELECT * FROM menu";
                if ($kategori_pilihan != '') {
                    $sql .= " WHERE kategori = '$kategori_pilihan'";
                }
                $query = mysqli_query($koneksi, $sql);
                
                while ($row = mysqli_fetch_assoc($query)) {
                ?>
                <div class="col-lg-4 col-md-6">
                    <div class="card card-menu">
                        <!-- Gambar dari folder assets -->
                        <img src="assets/<?= $row['foto']; ?>" class="menu-img" alt="<?= $row['nama_menu']; ?>" onerror="this.src='https://placehold.co/600x400?text=Mie+Padeh'">
                        
                        <div class="card-body p-4 text-center">
                            <span class="badge badge-kat mb-3"><?= $row['kategori']; ?></span>
                            <h5 class="fw-bold mb-2"><?= $row['nama_menu']; ?></h5>
                            <p class="price mb-4">Rp <?= number_format($row['harga'], 0, ',', '.'); ?></p>
                            
                            <!-- Link ke beli.php -->
                            <a href="beli.php?id=<?= $row['id_menu']; ?>" class="btn btn-order w-100">
                                <i class="fa fa-cart-plus me-2"></i> Tambah ke Keranjang
                            </a>
                        </div>
                    </div>
                </div>
                <?php } ?>
            </div>
        </div>
    </section>

    <!-- Tombol WhatsApp Floating -->
    <a href="https://wa.me/628123456789" class="btn-wa" target="_blank">
        <i class="fab fa-whatsapp"></i>
    </a>

    <footer class="bg-dark text-white py-4 text-center mt-5">
        <p class="small mb-0 text-secondary">&copy; 2026 Mie Padeh nep Project. Crafted with Fauzan.R.</p>
    </footer>

    <!-- Bootstrap JS Bundle (Wajib ada agar dropdown berfungsi) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>