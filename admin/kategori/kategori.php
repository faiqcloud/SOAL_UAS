<?php
// admin/kategori/kategori.php - Daftar Kategori
session_start();
if (!isset($_SESSION['admin_id'])) { header('Location: ../../login.php'); exit(); }
require_once '../../config/koneksi.php';

$page_title = 'Data Kategori';
$kategoris = $koneksi->query("SELECT k.*, COUNT(p.id) as jumlah_produk FROM kategori k LEFT JOIN produk p ON k.id = p.id_kategori GROUP BY k.id ORDER BY k.created_at DESC");

$success = $_SESSION['success'] ?? '';
$error   = $_SESSION['error'] ?? '';
unset($_SESSION['success'], $_SESSION['error']);
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Data Kategori - Velox Co Admin</title>
  <link rel="stylesheet" href="../../assets/bootstrap/css/bootstrap.min.css">
  <link rel="stylesheet" href="../../assets/css/style.css">
</head>
<body>
<div class="admin-wrapper">
  <?php include '../includes/sidebar.php'; ?>
  <div class="admin-main">
    <?php include '../includes/topbar.php'; ?>
    <div class="admin-content">

      <?php if ($success): ?>
      <div class="alert-velox-success alert-auto-hide mb-3">✅ <?= htmlspecialchars($success) ?></div>
      <?php endif; ?>
      <?php if ($error): ?>
      <div class="alert-velox alert-auto-hide mb-3">⚠️ <?= htmlspecialchars($error) ?></div>
      <?php endif; ?>

      <div class="admin-table-wrapper">
        <div class="admin-table-header">
          <div class="admin-table-title">Daftar Kategori</div>
          <a href="tambah.php" class="btn-velox btn-velox-success">+ Tambah Kategori</a>
        </div>
        <div class="table-responsive">
          <table class="table table-dark-velox mb-0">
            <thead>
              <tr>
                <th width="50">#</th>
                <th>Nama Kategori</th>
                <th>Deskripsi</th>
                <th>Jumlah Produk</th>
                <th>Dibuat</th>
                <th width="150">Aksi</th>
              </tr>
            </thead>
            <tbody>
              <?php if ($kategoris && $kategoris->num_rows > 0): ?>
                <?php $no = 1; while ($k = $kategoris->fetch_assoc()): ?>
                <tr>
                  <td><?= $no++ ?></td>
                  <td style="color: #ddd; font-weight: 600;"><?= htmlspecialchars($k['nama_kategori']) ?></td>
                  <td style="color: #555; font-size: 0.82rem; max-width: 200px;">
                    <?= htmlspecialchars(substr($k['deskripsi'] ?? '-', 0, 60)) ?><?= strlen($k['deskripsi'] ?? '') > 60 ? '...' : '' ?>
                  </td>
                  <td>
                    <span style="background: rgba(255,60,60,0.1); color: var(--accent); padding: 0.2rem 0.75rem; border-radius: 50px; font-size: 0.78rem; font-weight: 600;">
                      <?= $k['jumlah_produk'] ?> produk
                    </span>
                  </td>
                  <td style="font-size: 0.78rem; color: #444;"><?= date('d M Y', strtotime($k['created_at'])) ?></td>
                  <td>
                    <div class="d-flex gap-2">
                      <a href="edit.php?id=<?= $k['id'] ?>" class="btn-velox btn-velox-warning" style="font-size: 0.72rem; padding: 0.3rem 0.7rem;">Edit</a>
                      <a href="hapus.php?id=<?= $k['id'] ?>" class="btn-velox btn-velox-danger btn-delete-confirm" style="font-size: 0.72rem; padding: 0.3rem 0.7rem;">Hapus</a>
                    </div>
                  </td>
                </tr>
                <?php endwhile; ?>
              <?php else: ?>
                <tr>
                  <td colspan="6" style="text-align: center; color: #333; padding: 3rem;">
                    <div style="font-size: 2rem; margin-bottom: 0.5rem;">🏷️</div>
                    Belum ada kategori. <a href="tambah.php" style="color: var(--accent);">Tambah sekarang</a>
                  </td>
                </tr>
              <?php endif; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>
<script src="../../assets/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="../../assets/js/main.js"></script>
</body>
</html>
