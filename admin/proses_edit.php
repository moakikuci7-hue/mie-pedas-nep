<?php
include 'koneksi.php';

if (isset($_POST['update'])) {
    $id    = $_POST['id_menu'];
    $nama  = $_POST['nama_menu'];
    $kate  = $_POST['kategori'];
    $harg  = $_POST['harga'];
    $stok  = $_POST['stok'];

    // 1. Ambil info foto dari form
    $nama_foto = $_FILES['foto']['name'];
    $lokasi_foto = $_FILES['foto']['tmp_name'];

    // JIKA ADA FOTO BARU YANG DIUNGGAH
    if (!empty($nama_foto)) {
        
        // A. Beri nama unik agar tidak bentrok (contoh: 172345_mie.jpg)
        $nama_foto_baru = time() . "_" . $nama_foto;

        // B. Hapus foto lama dari folder assets agar tidak jadi sampah
        $ambil_data = mysqli_query($koneksi, "SELECT foto FROM menu WHERE id_menu = '$id'");
        $data_lama = mysqli_fetch_assoc($ambil_data);
        $foto_lama = $data_lama['foto'];

        // Cek jika filenya ada di folder, lalu hapus (kecuali foto default)
        if (file_exists("../assets/$foto_lama") && $foto_lama != 'default.jpg') {
            unlink("../assets/$foto_lama");
        }

        // C. Pindahkan foto baru ke folder assets
        move_uploaded_file($lokasi_foto, "../assets/$nama_foto_baru");

        // D. Update database dengan nama foto baru
        $query = "UPDATE menu SET 
                    nama_menu = '$nama', 
                    kategori = '$kate', 
                    harga = '$harg', 
                    stok = '$stok',
                    foto = '$nama_foto_baru' 
                  WHERE id_menu = '$id'";

    } else {
        // JIKA TIDAK ADA FOTO BARU, update data lainnya saja tanpa menyentuh kolom foto
        $query = "UPDATE menu SET 
                    nama_menu = '$nama', 
                    kategori = '$kate', 
                    harga = '$harg', 
                    stok = '$stok' 
                  WHERE id_menu = '$id'";
    }
    
    $hasil = mysqli_query($koneksi, $query);

    if ($hasil) {
        // Jika sukses, lempar balik ke dashboard
        header("Location: index.php?status=sukses");
    } else {
        echo "Gagal update: " . mysqli_error($koneksi);
    }
}
?>