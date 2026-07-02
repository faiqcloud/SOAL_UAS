<?php
// admin/produk/produk.php - Daftar Produk
session_start();
if (!isset($_SESSION['admin_id'])) { header('Location: ../../login.php'); exit(); }
require_once '../../config/koneksi.php';

$page_title = 'Data Produk';

$search = trim($_GET['search'] ?? '');
$sql = "SELECT p.*, k.nama_kategori FROM produk p LEFT JOIN kategori k ON p.id_kategori = k.id";
if (!empty($search)) {
    $s = $koneksi->real_escape_string($search);
    $sql .= " WHERE p.nama_produk LIKE '%$s%' OR k.nama_kategori LIKE '%$s%'";
}
$sql .= " ORDER BY p.created_at DESC";
$produks = $koneksi->query($sql);

$success = $_SESSION['success'] ?? '';
$error   = $_SESSION['error'] ?? '';
unset($_SESSION['success'], $_SESSION['error']);
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Data Produk - Velox Co Admin</title>
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
        <div class="admin-table-header" style="flex-wrap: wrap; gap: 0.75rem;">
          <div class="admin-table-title">Daftar Produk (<?= $produks ? $produks->num_rows : 0 ?>)</div>
          <div class="d-flex gap-2 align-items-center flex-wrap">
            <form method="GET" class="d-flex gap-2">
              <input type="text" name="search" class="form-control form-control-velox" placeholder="Cari produk..." value="<?= htmlspecialchars($search) ?>" style="font-size: 0.82rem; padding: 0.4rem 0.75rem; width: 200px;">
              <button type="submit" class="btn-velox btn-velox-secondary" style="font-size: 0.75rem; white-space: nowrap;">Cari</button>
              <?php if (!empty($search)): ?>
              <a href="produk.php" class="btn-velox btn-velox-secondary" style="font-size: 0.75rem;">Reset</a>
              <?php endif; ?>
            </form>
            <a href="tambah.php" class="btn-velox btn-velox-success">+ Tambah Produk</a>
          </div>
        </div>
        <div class="table-responsive">
          <table class="table table-dark-velox mb-0">
            <thead>
              <tr>
                <th width="40">#</th>
                <th width="60">Foto</th>
                <th>Nama Produk</th>
                <th>Kategori</th>
                <th>Harga</th>
                <th>Stok</th>
                <th width="160">Aksi</th>
              </tr>
            </thead>
            <tbody>
              <?php if ($produks && $produks->num_rows > 0): ?>
                <?php $no = 1; while ($p = $produks->fetch_assoc()): ?>
                <tr>
                  <td><?= $no++ ?></td>
                  <td>
                    <?php if ($p['gambar'] && file_exists('../../assets/img/' . $p['gambar'])): ?>
                      <img src="../../assets/img/<?= htmlspecialchars($p['gambar']) ?>" alt="" class="product-thumb">
                    <?php else: ?>
                      <div class="product-thumb-placeholder">👕</div>
                    <?php endif; ?>
                  </td>
                  <td>
                    <div style="color: #ddd; font-weight: 600; font-size: 0.88rem;"><?= htmlspecialchars($p['nama_produk']) ?></div>
                    <div style="color: #333; font-size: 0.75rem; margin-top: 0.2rem;"><?= htmlspecialchars(substr($p['deskripsi'] ?? '', 0, 50)) ?>...</div>
                  </td>
                  <td>
                    <span style="background: rgba(255,60,60,0.08); color: var(--accent); padding: 0.2rem 0.6rem; border-radius: 4px; font-size: 0.75rem; font-family: var(--font-ui); white-space: nowrap;">
                      <?= htmlspecialchars($p['nama_kategori']) ?>
                    </span>
                  </td>
                  <td style="color: #27ae60; font-weight: 600; white-space: nowrap;">Rp <?= number_format($p['harga'], 0, ',', '.') ?></td>
                  <td>
                    <?php if ($p['stok'] <= 5): ?>
                      <span style="color: #e74c3c; font-weight: 600;"><?= $p['stok'] ?></span>
                      <span style="font-size: 0.7rem; color: #c0392b; display: block;">⚠️ Hampir habis</span>
                    <?php else: ?>
                      <span style="color: #aaa;"><?= $p['stok'] ?></span>
                    <?php endif; ?>
                  </td>
                  <td>
                    <div class="d-flex gap-1">
                      <a href="edit.php?id=<?= $p['id'] ?>" class="btn-velox btn-velox-warning" style="font-size: 0.7rem; padding: 0.3rem 0.6rem;">Edit</a>
                      <a href="hapus.php?id=<?= $p['id'] ?>" class="btn-velox btn-velox-danger btn-delete-confirm" style="font-size: 0.7rem; padding: 0.3rem 0.6rem;">Hapus</a>
                    </div>
                  </td>
                </tr>
                <?php endwhile; ?>
              <?php else: ?>
                <tr>
                  <td colspan="7" style="text-align: center; color: #333; padding: 3rem;">
                    <div style="font-size: 2rem; margin-bottom: 0.5rem;">👕</div>
                    <?php if (!empty($search)): ?>
                      Produk "<?= htmlspecialchars($search) ?>" tidak ditemukan. <a href="produk.php" style="color: var(--accent);">Reset</a>
                    <?php else: ?>
                      Belum ada produk. <a href="tambah.php" style="color: var(--accent);">Tambah sekarang</a>
                    <?php endif; ?>
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
