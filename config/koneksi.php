<?php
// config/koneksi.php
// Konfigurasi koneksi database menggunakan PHP MySQLi

define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'dbtoko');

$koneksi = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

// Cek koneksi
if ($koneksi->connect_error) {
    die('<div style="font-family: Arial; text-align: center; padding: 50px;">
        <h2 style="color: red;">⚠️ Koneksi Database Gagal</h2>
        <p>' . $koneksi->connect_error . '</p>
        <p>Pastikan XAMPP sudah berjalan dan database <strong>dbtoko</strong> sudah dibuat.</p>
    </div>');
}

// Set charset
$koneksi->set_charset('utf8mb4');
?>
