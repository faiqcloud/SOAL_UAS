<?php
// admin/kategori/tambah.php - Tambah Kategori
session_start();
if (!isset($_SESSION['admin_id'])) { header('Location: ../../login.php'); exit(); }
require_once '../../config/koneksi.php';

$page_title = 'Tambah Kategori';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama      = trim($_POST['nama_kategori'] ?? '');
    $deskripsi = trim($_POST['deskripsi'] ?? '');

    if (empty($nama)) {
        $error = 'Nama kategori tidak boleh kosong!';
    } else {
        $stmt = $koneksi->prepare("INSERT INTO kategori (nama_kategori, deskripsi) VALUES (?, ?)");
        $stmt->bind_param('ss', $nama, $deskripsi);
        if ($stmt->execute()) {
            $_SESSION['success'] = 'Kategori berhasil ditambahkan!';
            header('Location: kategori.php');
            exit();
        } else {
            $error = 'Gagal menyimpan data. Coba lagi.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Tambah Kategori - Velox Co Admin</title>
  <link rel="stylesheet" href="../../assets/bootstrap/css/bootstrap.min.css">
  <link rel="stylesheet" href="../../assets/css/style.css">
</head>
<body>
<div class="admin-wrapper">
  <?php include '../includes/sidebar.php'; ?>
  <div class="admin-main">
    <?php include '../includes/topbar.php'; ?>
    <div class="admin-content">
      <div class="mb-3">
        <a href="kategori.php" style="color: #555; font-family: var(--font-ui); font-size: 0.8rem; letter-spacing: 1px; text-transform: uppercase;">← Kembali ke Kategori</a>
      </div>

      <?php if ($error): ?>
      <div class="alert-velox alert-auto-hide mb-3">⚠️ <?= htmlspecialchars($error) ?></div>
      <?php endif; ?>

      <div class="admin-form-wrapper" style="max-width: 600px;">
        <h2 style="font-family: var(--font-display); font-size: 1.5rem; letter-spacing: 3px; color: white; margin-bottom: 2rem;">TAMBAH KATEGORI</h2>

        <form method="POST" action="">
          <div class="mb-4">
            <label class="form-label-velox" for="nama_kategori">Nama Kategori <span style="color: var(--accent);">*</span></label>
            <input 
              type="text" 
              name="nama_kategori" 
              id="nama_kategori"
              class="form-control form-control-velox" 
              placeholder="Contoh: Oversized Tee"
              value="<?= htmlspecialchars($_POST['nama_kategori'] ?? '') ?>"
              required
            >
          </div>
          <div class="mb-4">
            <label class="form-label-velox" for="deskripsi">Deskripsi</label>
            <textarea 
              name="deskripsi" 
              id="deskripsi"
              class="form-control form-control-velox" 
              placeholder="Deskripsi singkat kategori..."
              rows="4"
            ><?= htmlspecialchars($_POST['deskripsi'] ?? '') ?></textarea>
          </div>
          <div class="d-flex gap-3">
            <button type="submit" class="btn-velox btn-velox-success">✅ Simpan Kategori</button>
            <a href="kategori.php" class="btn-velox btn-velox-secondary">Batal</a>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
<script src="../../assets/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="../../assets/js/main.js"></script>
</body>
</html>
