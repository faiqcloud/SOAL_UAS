<?php
// admin/index.php - Dashboard Admin
session_start();
if (!isset($_SESSION['admin_id'])) {
    header('Location: ../login.php');
    exit();
}
require_once '../config/koneksi.php';

// Stats
$total_produk   = $koneksi->query("SELECT COUNT(*) as t FROM produk")->fetch_assoc()['t'];
$total_kategori = $koneksi->query("SELECT COUNT(*) as t FROM kategori")->fetch_assoc()['t'];
$total_stok     = $koneksi->query("SELECT SUM(stok) as t FROM produk")->fetch_assoc()['t'] ?? 0;
$nilai_inventori= $koneksi->query("SELECT SUM(harga * stok) as t FROM produk")->fetch_assoc()['t'] ?? 0;

// Produk terbaru
$latest_produk = $koneksi->query("SELECT p.*, k.nama_kategori FROM produk p LEFT JOIN kategori k ON p.id_kategori = k.id ORDER BY p.created_at DESC LIMIT 5");

$active_menu = 'dashboard';
$page_title  = 'Dashboard';
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dashboard - Velox Co Admin</title>
  <link rel="stylesheet" href="../assets/bootstrap/css/bootstrap.min.css">
  <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
<div class="admin-wrapper">

  <!-- Sidebar -->
  <?php include 'includes/sidebar.php'; ?>

  <!-- Main Content -->
  <div class="admin-main">
    <!-- Topbar -->
    <?php include 'includes/topbar.php'; ?>

    <!-- Content -->
    <div class="admin-content">
      <!-- Stat Cards -->
      <div class="row g-4 mb-4">
        <div class="col-6 col-xl-3">
          <div class="stat-card">
            <div class="stat-icon">👕</div>
            <div class="stat-value"><?= $total_produk ?></div>
            <div class="stat-label">Total Produk</div>
          </div>
        </div>
        <div class="col-6 col-xl-3">
          <div class="stat-card">
            <div class="stat-icon">🏷️</div>
            <div class="stat-value"><?= $total_kategori ?></div>
            <div class="stat-label">Total Kategori</div>
          </div>
        </div>
        <div class="col-6 col-xl-3">
          <div class="stat-card">
            <div class="stat-icon">📦</div>
            <div class="stat-value"><?= number_format($total_stok) ?></div>
            <div class="stat-label">Total Stok</div>
          </div>
        </div>
        <div class="col-6 col-xl-3">
          <div class="stat-card">
            <div class="stat-icon">💰</div>
            <div class="stat-value" style="font-size: 1.5rem;">Rp <?= number_format($nilai_inventori/1000000, 1) ?>M</div>
            <div class="stat-label">Nilai Inventori</div>
          </div>
        </div>
      </div>

      <!-- Latest Products Table -->
      <div class="admin-table-wrapper">
        <div class="admin-table-header">
          <div class="admin-table-title">Produk Terbaru</div>
          <a href="produk/produk.php" class="btn-velox btn-velox-secondary" style="font-size: 0.75rem;">Lihat Semua →</a>
        </div>
        <div class="table-responsive">
          <table class="table table-dark-velox mb-0">
            <thead>
              <tr>
                <th>#</th>
                <th>Gambar</th>
                <th>Nama Produk</th>
                <th>Kategori</th>
                <th>Harga</th>
                <th>Stok</th>
                <th>Aksi</th>
              </tr>
            </thead>
            <tbody>
              <?php if ($latest_produk && $latest_produk->num_rows > 0): ?>
                <?php $no = 1; while ($p = $latest_produk->fetch_assoc()): ?>
                <tr>
                  <td><?= $no++ ?></td>
                  <td>
                    <?php if ($p['gambar'] && file_exists('../assets/img/' . $p['gambar'])): ?>
                      <img src="../assets/img/<?= htmlspecialchars($p['gambar']) ?>" alt="" class="product-thumb">
                    <?php else: ?>
                      <div class="product-thumb-placeholder">👕</div>
                    <?php endif; ?>
                  </td>
                  <td style="color: #ddd; font-weight: 500;"><?= htmlspecialchars($p['nama_produk']) ?></td>
                  <td><span style="background: rgba(255,60,60,0.1); color: var(--accent); padding: 0.2rem 0.6rem; border-radius: 4px; font-size: 0.75rem; font-family: var(--font-ui);"><?= htmlspecialchars($p['nama_kategori']) ?></span></td>
                  <td style="color: #27ae60; font-weight: 600;">Rp <?= number_format($p['harga'], 0, ',', '.') ?></td>
                  <td><?= $p['stok'] ?></td>
                  <td>
                    <a href="produk/edit.php?id=<?= $p['id'] ?>" class="btn-velox btn-velox-warning" style="font-size: 0.72rem; padding: 0.3rem 0.7rem;">Edit</a>
                  </td>
                </tr>
                <?php endwhile; ?>
              <?php else: ?>
                <tr>
                  <td colspan="7" style="text-align: center; color: #333; padding: 2rem;">Belum ada produk</td>
                </tr>
              <?php endif; ?>
            </tbody>
          </table>
        </div>
      </div>

      <!-- Quick Links -->
      <div class="row g-3 mt-2">
        <div class="col-md-6">
          <div class="stat-card" style="text-align: center;">
            <div style="font-size: 2rem; margin-bottom: 0.75rem;">🏷️</div>
            <div style="color: #ddd; font-family: var(--font-ui); font-size: 0.9rem; font-weight: 600; margin-bottom: 0.5rem;">Kelola Kategori</div>
            <div style="color: #444; font-size: 0.8rem; margin-bottom: 1rem;">Tambah, ubah, atau hapus kategori produk</div>
            <a href="kategori/kategori.php" class="btn-velox btn-velox-secondary">Buka Kategori →</a>
          </div>
        </div>
        <div class="col-md-6">
          <div class="stat-card" style="text-align: center;">
            <div style="font-size: 2rem; margin-bottom: 0.75rem;">👕</div>
            <div style="color: #ddd; font-family: var(--font-ui); font-size: 0.9rem; font-weight: 600; margin-bottom: 0.5rem;">Kelola Produk</div>
            <div style="color: #444; font-size: 0.8rem; margin-bottom: 1rem;">Tambah, ubah, atau hapus data produk</div>
            <a href="produk/produk.php" class="btn-velox btn-velox-secondary">Buka Produk →</a>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<script src="../assets/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="../assets/js/main.js"></script>
</body>
</html>
