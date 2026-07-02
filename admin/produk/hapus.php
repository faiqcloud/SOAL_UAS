<?php
// admin/produk/hapus.php - Hapus Produk
session_start();
if (!isset($_SESSION['admin_id'])) { header('Location: ../../login.php'); exit(); }
require_once '../../config/koneksi.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($id <= 0) { header('Location: produk.php'); exit(); }

// Ambil data produk
$stmt = $koneksi->prepare("SELECT * FROM produk WHERE id = ?");
$stmt->bind_param('i', $id);
$stmt->execute();
$produk = $stmt->get_result()->fetch_assoc();

if (!$produk) {
    $_SESSION['error'] = 'Produk tidak ditemukan!';
    header('Location: produk.php');
    exit();
}

// Hapus gambar jika ada
if ($produk['gambar'] && file_exists('../../assets/img/' . $produk['gambar'])) {
    unlink('../../assets/img/' . $produk['gambar']);
}

// Hapus produk dari database
$stmt = $koneksi->prepare("DELETE FROM produk WHERE id = ?");
$stmt->bind_param('i', $id);

if ($stmt->execute()) {
    $_SESSION['success'] = 'Produk "' . $produk['nama_produk'] . '" berhasil dihapus!';
} else {
    $_SESSION['error'] = 'Gagal menghapus produk!';
}

header('Location: produk.php');
exit();
?>
