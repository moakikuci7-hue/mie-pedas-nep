🍜 Dokumentasi Mie Pedas NEP

Selamat datang di panduan resmi proyek Mie Pedas NEP. Aplikasi ini adalah platform e-commerce kuliner modern berbasis web.

🔗 Informasi Repositori

GitHub: moakikuci7-hue/mie-pedas-nep

Developer: Moakikuci7-hue

Stack: PHP Native, MySQL, Bootstrap 5, FontAwesome.

1. Ringkasan Proyek

Mie Pedas NEP dirancang untuk mendigitalkan proses pemesanan makanan secara online. Sistem ini mengintegrasikan manajemen inventaris di sisi Admin dan kemudahan berbelanja di sisi Pelanggan, lengkap dengan sistem konfirmasi pembayaran.

2. Fitur Utama
💂 Sisi Admin (Backend)

Dashboard Statistik: Pantau total menu, jumlah pesanan, dan total pendapatan harian secara otomatis.

Manajemen Menu (CRUD): Tambah, edit, dan hapus menu dengan dukungan upload foto produk asli.

Verifikasi Pesanan: Cek rincian pesanan dan validasi bukti pembayaran (foto struk) dari pelanggan.

Manajemen Status: Ubah status transaksi menjadi Selesai untuk memperbarui laporan keuangan.

Security: Proteksi halaman manajemen menggunakan sistem login admin.

🛍️ Sisi Pelanggan (Frontend)

Sistem Akun: Pendaftaran dan login pelanggan untuk melacak riwayat belanja.

Filter Menu: Fitur penyaringan makanan atau minuman berdasarkan kategori.

Keranjang Belanja: Memungkinkan pelanggan memilih banyak menu sebelum checkout.

Checkout & Pembayaran: Integrasi pemilihan metode bayar (BCA, OVO, Gopay, COD).

Konfirmasi Transaksi: Fitur upload bukti transfer langsung dari halaman riwayat.

Nota Digital: Lihat rincian belanja dan cetak nota sebagai bukti pembelian.

3. Struktur Database

Proyek ini berjalan di atas database mie_padeh dengan tabel sebagai berikut:

Nama Tabel	Fungsi
admin	Data login pengelola warung.
pelanggan	Data akun pembeli (email, pass, no hp).
menu	Katalog produk, harga, stok, dan foto.
pesanan	Data transaksi utama dan status bayar.
detail_pesanan	Rincian item menu dalam satu transaksi.
4. Cara Instalasi (Localhost)

Clone Proyek

code
Bash
download
content_copy
expand_less
git clone https://github.com/moakikuci7-hue/mie-pedas-nep.git

Persiapan Folder
Pindahkan folder hasil clone ke direktori C:/xampp/htdocs/.

Setup Database

Buka phpMyAdmin.

Buat database baru bernama mie_padeh.

Import file .sql yang tersedia di dalam repositori.

Konfigurasi Koneksi
Edit file koneksi_pembeli.php dan admin/koneksi.php sesuai dengan kredensial database lokal Anda.

Akses Browser
Buka alamat http://localhost/mie-pedas-nep/index.php.

5. Deployment (Hosting)

Untuk menjalankan web ini secara online:

Pastikan server hosting mendukung PHP 7.4+ dan MySQL.

Update konfigurasi koneksi database sesuai data dari panel hosting.

Penting: Atur izin akses (Permissions) folder assets/bukti/ menjadi 777 agar server mengizinkan upload file dari pembeli.


