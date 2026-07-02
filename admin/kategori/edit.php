<?php
// admin/kategori/edit.php - Edit Kategori
session_start();
if (!isset($_SESSION['admin_id'])) { header('Location: ../../login.php'); exit(); }
require_once '../../config/koneksi.php';

$page_title = 'Edit Kategori';
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($id <= 0) {
    header('Location: kategori.php');
    exit();
}

// Ambil data kategori
$stmt = $koneksi->prepare("SELECT * FROM kategori WHERE id = ?");
$stmt->bind_param('i', $id);
$stmt->execute();
$kategori = $stmt->get_result()->fetch_assoc();

if (!$kategori) {
    $_SESSION['error'] = 'Kategori tidak ditemukan!';
    header('Location: kategori.php');
    exit();
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama      = trim($_POST['nama_kategori'] ?? '');
    $deskripsi = trim($_POST['deskripsi'] ?? '');

    if (empty($nama)) {
        $error = 'Nama kategori tidak boleh kosong!';
    } else {
        $stmt = $koneksi->prepare("UPDATE kategori SET nama_kategori = ?, deskripsi = ? WHERE id = ?");
        $stmt->bind_param('ssi', $nama, $deskripsi, $id);
        if ($stmt->execute()) {
            $_SESSION['success'] = 'Kategori berhasil diperbarui!';
            header('Location: kategori.php');
            exit();
        } else {
            $error = 'Gagal memperbarui data. Coba lagi.';
        }
    }
    // Update local variable for form re-display
    $kategori['nama_kategori'] = $nama;
    $kategori['deskripsi']     = $deskripsi;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Edit Kategori - Velox Co Admin</title>
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
        <h2 style="font-family: var(--font-display); font-size: 1.5rem; letter-spacing: 3px; color: white; margin-bottom: 0.5rem;">EDIT KATEGORI</h2>
        <div style="font-size: 0.78rem; color: #333; margin-bottom: 2rem;">ID: #<?= $id ?></div>

        <form method="POST" action="">
          <div class="mb-4">
            <label class="form-label-velox" for="nama_kategori">Nama Kategori <span style="color: var(--accent);">*</span></label>
            <input 
              type="text" 
              name="nama_kategori" 
              id="nama_kategori"
              class="form-control form-control-velox" 
              placeholder="Nama kategori"
              value="<?= htmlspecialchars($kategori['nama_kategori']) ?>"
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
            ><?= htmlspecialchars($kategori['deskripsi'] ?? '') ?></textarea>
          </div>
          <div class="d-flex gap-3">
            <button type="submit" class="btn-velox btn-velox-warning">✏️ Update Kategori</button>
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
