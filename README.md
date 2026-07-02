# Velox Co - Premium Streetwear Store

Website toko online kaos streetwear menggunakan PHP, MySQL, dan Bootstrap 5 Offline.

---

## 🚀 Cara Menjalankan

### 1. Persiapan
- Pastikan **XAMPP** sudah terinstall dan berjalan
- Aktifkan **Apache** dan **MySQL** di XAMPP Control Panel

### 2. Setup Database (2 cara):

#### Cara A - Otomatis (Mudah):
Buka browser → `http://localhost/SOAL_UAS/setup.php`
→ Klik jalankan, tunggu hingga selesai
→ **Hapus `setup.php` setelah setup berhasil**

#### Cara B - Manual via phpMyAdmin:
1. Buka `http://localhost/phpmyadmin`
2. Buat database baru dengan nama `dbtoko`
3. Import file `database.sql`

### 3. Buka Website
- **Frontend Toko**: `http://localhost/SOAL_UAS/`
- **Admin Login**: `http://localhost/SOAL_UAS/login.php`

---

## 🔐 Akun Admin Default
| Field | Value |
|-------|-------|
| Username | `admin` |
| Password | `admin123` |

---

## 📁 Struktur Folder

```
SOAL_UAS/
├── admin/
│   ├── includes/
│   │   ├── sidebar.php
│   │   └── topbar.php
│   ├── kategori/
│   │   ├── kategori.php    ← Daftar kategori
│   │   ├── tambah.php      ← Tambah kategori
│   │   ├── edit.php        ← Edit kategori
│   │   └── hapus.php       ← Hapus kategori
│   ├── produk/
│   │   ├── produk.php      ← Daftar produk
│   │   ├── tambah.php      ← Tambah produk
│   │   ├── edit.php        ← Edit produk
│   │   └── hapus.php       ← Hapus produk
│   └── index.php           ← Dashboard admin
├── assets/
│   ├── bootstrap/
│   │   ├── css/bootstrap.min.css
│   │   └── js/bootstrap.bundle.min.js
│   ├── css/style.css
│   ├── js/main.js
│   └── img/                ← Folder upload gambar produk
├── config/
│   └── koneksi.php         ← Koneksi database MySQLi
├── database.sql            ← Schema database
├── index.php               ← Halaman utama toko
├── detail.php              ← Detail produk
├── login.php               ← Login admin
├── logout.php              ← Logout
└── setup.php               ← Setup database (hapus setelah digunakan)
```

---

## ✨ Fitur Website

### Frontend
- Hero section animasi dengan statistik toko
- Marquee banner promosi
- Filter produk per kategori
- Pencarian produk
- Grid produk dengan hover effect
- Halaman detail produk lengkap
- Produk terkait
- Pilihan ukuran
- 6 testimoni pelanggan
- Informasi kontak + newsletter
- Footer lengkap
- Responsive untuk mobile & desktop

### Admin Panel
- Dashboard dengan 4 statistik card
- CRUD Kategori (Tambah, Lihat, Edit, Hapus)
- CRUD Produk (Tambah, Lihat, Edit, Hapus)
- Upload gambar produk (JPG/PNG/JPEG, max 2MB)
- Pencarian produk
- Sidebar navigasi modern
- Session login & logout

---

## 🗄️ Database

- **Database**: `dbtoko`
- **Tabel**: `user`, `kategori`, `produk`
- **Relasi**: `produk.id_kategori` → `kategori.id` (Foreign Key)
- **Koneksi**: PHP MySQLi (`config/koneksi.php`)

---

© 2025 Velox Co - Premium Streetwear Indonesia
