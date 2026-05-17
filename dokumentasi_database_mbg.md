# Dokumentasi Alur Database & Kodingan MBG (Makanan Bergizi Gratis)

Aplikasi MBG dibangun menggunakan framework Laravel dengan konsep **MVC (Model-View-Controller)**. Berikut adalah penjelasan lengkap mengenai tabel database apa saja yang digunakan, bagaimana mereka saling terhubung, dan controller mana yang memanipulasinya.

---

## 🏗️ 1. Struktur Tabel Database Utama

Terdapat 6 tabel utama yang menggerakkan seluruh logika bisnis pada aplikasi ini:

### 1. Tabel `users`
*   **Fungsi:** Menyimpan data otentikasi login untuk semua peran (Admin, Petugas, Sekolah).
*   **Kolom Penting:** `name`, `email`, `password`, `role` (enum: 'admin', 'sekolah', 'petugas').
*   **Controller yang Memakai:** `AuthController`, `PenggunaController`.

### 2. Tabel `sekolah` (Penerima)
*   **Fungsi:** Menyimpan profil sekolah yang akan menerima makanan.
*   **Kolom Penting:** `user_id`, `npsn`, `nama_sekolah`, `alamat`, `penanggung_jawab`.
*   **Relasi:** *BelongsTo* `users`.
*   **Controller yang Memakai:** `PenerimaController` (Admin), `DashboardSekolahController`.

### 3. Tabel `petugas` (Kurir)
*   **Fungsi:** Menyimpan profil petugas pengantar makanan.
*   **Kolom Penting:** `user_id`, `nomor_pegawai`, `nomor_telepon`, `kendaraan`.
*   **Relasi:** *BelongsTo* `users`.
*   **Controller yang Memakai:** `PenerimaController` (Admin), `DashboardPetugasController`.

### 4. Tabel `distribusi` (Logistik Utama)
*   **Fungsi:** Ini adalah **tabel sentral**. Semua jadwal, status pengiriman, laporan kendala, dan foto bukti disimpan di sini.
*   **Kolom Penting:** 
    *   `sekolah_id`, `petugas_id`, `tanggal`, `target_porsi`, `menu_hari_ini`, `foto_menu`.
    *   `status_pengiriman` (Belum Dikirim, Dalam Perjalanan, Selesai).
    *   `keterangan_kendala` (Jika kurir melapor ban bocor dll).
    *   `porsi_diterima`, `foto_bukti`, `waktu_tiba`.
*   **Relasi:** *BelongsTo* `sekolah` & `petugas`.
*   **Controller yang Memakai:** Hampir semua controller mengakses tabel ini (`DistribusiController`, `DashboardPetugasController`, `DashboardSekolahController`, `LaporanController`).

### 5. Tabel `kritik_saran` (Feedback)
*   **Fungsi:** Menyimpan umpan balik pelayanan dari pihak sekolah untuk Dinas/Admin.
*   **Kolom Penting:** `sekolah_id`, `distribusi_id`, `kategori`, `rating`, `komentar`.
*   **Relasi:** *BelongsTo* `sekolah` & `distribusi`.
*   **Controller yang Memakai:** `DashboardSekolahController` (Menyimpan), `KritikSaranController` (Admin melihat).

### 6. Tabel `pengembalian_ompreng` (Aset)
*   **Fungsi:** Melacak wadah (ompreng) yang dikembalikan sekolah.
*   **Kolom Penting:** `distribusi_id`, `sekolah_id`, `jumlah_dikirim`, `jumlah_kembali`, `jumlah_rusak`, `kondisi` (Baik/Rusak), `foto_kondisi`, `catatan`.
*   **Relasi:** *BelongsTo* `sekolah` & `distribusi`.
*   **Controller yang Memakai:** `DashboardSekolahController` (Menyimpan), `OmprengController` (Admin melihat).

---

## 🔄 2. Alur Kodingan (Bagaimana Data Bergerak)

Berikut adalah ringkasan *"Lari ke mana saja data tersebut?"* berdasarkan kegiatan di aplikasi:

### A. Admin Membuat Jadwal Pengiriman (Distribusi)
1. Admin mengisi form di `/admin/distribusi/create`.
2. Form dikirim (POST) ke `DistribusiController@store`.
3. Kodingan akan menyimpan data ke tabel `distribusi` dengan status awal **"Belum Dikirim"**. Jika ada file `foto_menu`, file akan disimpan di folder `public/uploads/foto_menu`.

### B. Petugas Memulai Pengiriman & Lapor Kendala
1. Petugas membuka dashboard, `DashboardPetugasController` akan mengambil data `distribusi` yang statusnya "Belum Dikirim".
2. Saat petugas menekan **Mulai Pengiriman**, data diupdate, statusnya menjadi **"Dalam Perjalanan"**.
3. Jika petugas menekan **Lapor Kendala**, kodingan `laporKendala()` berjalan dan mengisi kolom `keterangan_kendala` di tabel `distribusi`.

### C. Sekolah Memantau & Konfirmasi (Lacak Pengiriman)
1. Saat sekolah membuka Lacak Pengiriman, `DashboardSekolahController@tracking` akan menarik data dari tabel `distribusi`. Jika ada `keterangan_kendala`, ia akan menampilkannya di peringatan merah.
2. Saat sekolah menekan **Konfirmasi Penerimaan**, kodingan `storeKonfirmasi()` berjalan:
   * Mengunggah gambar ke `public/uploads/bukti_terima/`.
   * Mengubah status `distribusi` menjadi **"Selesai"**.
   * Mencatat `waktu_tiba`.

### D. Sekolah Mengisi Umpan Balik & Ompreng
1. Form Umpan Balik akan di-POST ke `DashboardSekolahController@storeKritikSaran` lalu masuk ke tabel `kritik_saran`.
2. Form Ompreng akan di-POST ke `DashboardSekolahController@storePengembalianOmpreng`, menyimpan foto kondisi ke `public/uploads/ompreng/`, dan datanya masuk ke tabel `pengembalian_ompreng`.
3. Data dari kedua tabel ini kemudian ditarik oleh Admin melalui `KritikSaranController@index` dan `OmprengController@index` untuk dimunculkan di halaman monitoring Admin.

---

## 📂 3. Lokasi Folder Penting
Jika Anda ingin mengubah kodingan di masa depan, fokus pada folder ini:
*   **Tampilan (Views):** `resources/views/` (Terdapat folder `admin`, `petugas`, dan `sekolah`).
*   **Logika (Controllers):** `app/Http/Controllers/` (Otak dari aplikasi).
*   **Tabel (Models/Migrations):** `app/Models/` (Relasi) dan `database/migrations/` (Struktur kolom).
*   **Rute (URLs):** `routes/web.php` (Pengatur alamat link browser).
*   **File Gambar Uploads:** `public/uploads/` (Menyimpan semua foto menu, foto bukti, foto ompreng).
