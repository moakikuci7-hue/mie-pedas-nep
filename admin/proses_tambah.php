<?php
include 'koneksi.php';

if (isset($_POST['submit'])) {
    $nama = $_POST['nama_menu'];
    $kate = $_POST['kategori'];
    $harg = $_POST['harga'];
    $stok = $_POST['stok'];

    // LOGIKA UPLOAD FOTO
    $nama_foto = $_FILES['foto']['name'];
    $lokasi_foto = $_FILES['foto']['tmp_name'];

    // Beri nama unik agar tidak bentrok
    $nama_foto_baru = time() . "_" . $nama_foto;

    // Pindahkan file ke folder assets
    if (move_uploaded_file($lokasi_foto, "../assets/" . $nama_foto_baru)) {
        
        // Masukkan data ke database
        $query = "INSERT INTO menu (nama_menu, kategori, harga, stok, foto) 
                  VALUES ('$nama', '$kate', '$harg', '$stok', '$nama_foto_baru')";
        
        $hasil = mysqli_query($koneksi, $query);

        if ($hasil) {
            header("Location: index.php?status=tambah-sukses");
        } else {
            echo "Gagal input ke database: " . mysqli_error($koneksi);
        }

    } else {
        echo "Gagal mengupload foto. Pastikan folder 'assets' sudah ada di luar folder admin.";
    }
}
?>