<?php
// admin/kategori/hapus.php - Hapus Kategori
session_start();
if (!isset($_SESSION['admin_id'])) { header('Location: ../../login.php'); exit(); }
require_once '../../config/koneksi.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($id <= 0) {
    header('Location: kategori.php');
    exit();
}

// Cek apakah kategori ada
$stmt = $koneksi->prepare("SELECT * FROM kategori WHERE id = ?");
$stmt->bind_param('i', $id);
$stmt->execute();
$kategori = $stmt->get_result()->fetch_assoc();

if (!$kategori) {
    $_SESSION['error'] = 'Kategori tidak ditemukan!';
    header('Location: kategori.php');
    exit();
}

// Cek apakah ada produk yang menggunakan kategori ini
$cek = $koneksi->prepare("SELECT COUNT(*) as total FROM produk WHERE id_kategori = ?");
$cek->bind_param('i', $id);
$cek->execute();
$jumlah = $cek->get_result()->fetch_assoc()['total'];

if ($jumlah > 0) {
    $_SESSION['error'] = "Kategori tidak dapat dihapus karena masih digunakan oleh $jumlah produk!";
    header('Location: kategori.php');
    exit();
}

// Hapus kategori
$stmt = $koneksi->prepare("DELETE FROM kategori WHERE id = ?");
$stmt->bind_param('i', $id);
if ($stmt->execute()) {
    $_SESSION['success'] = 'Kategori berhasil dihapus!';
} else {
    $_SESSION['error'] = 'Gagal menghapus kategori!';
}

header('Location: kategori.php');
exit();
?>
