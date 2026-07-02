<?php
// admin/produk/edit.php - Edit Produk
session_start();
if (!isset($_SESSION['admin_id'])) { header('Location: ../../login.php'); exit(); }
require_once '../../config/koneksi.php';

$page_title = 'Edit Produk';
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

$kategoris = $koneksi->query("SELECT * FROM kategori ORDER BY nama_kategori ASC");
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_kategori = (int)($_POST['id_kategori'] ?? 0);
    $nama_produk = trim($_POST['nama_produk'] ?? '');
    $harga       = (int)($_POST['harga'] ?? 0);
    $stok        = (int)($_POST['stok'] ?? 0);
    $deskripsi   = trim($_POST['deskripsi'] ?? '');
    $gambar_nama = $produk['gambar']; // Keep old image by default

    if ($id_kategori <= 0 || empty($nama_produk) || $harga <= 0) {
        $error = 'Kategori, nama produk, dan harga wajib diisi dengan benar!';
    } else {
        // Upload gambar baru jika ada
        if (isset($_FILES['gambar']) && $_FILES['gambar']['error'] !== UPLOAD_ERR_NO_FILE) {
            if ($_FILES['gambar']['error'] !== UPLOAD_ERR_OK) {
                $error = 'Gagal upload gambar. Error code: ' . $_FILES['gambar']['error'];
            } else {
                $allowed  = ['image/jpeg', 'image/jpg', 'image/png'];
                $file_type = $_FILES['gambar']['type'];
                $file_size = $_FILES['gambar']['size'];
                $file_ext  = strtolower(pathinfo($_FILES['gambar']['name'], PATHINFO_EXTENSION));

                if (!in_array($file_type, $allowed)) {
                    $error = 'Format gambar harus JPG, JPEG, atau PNG!';
                } elseif ($file_size > 2 * 1024 * 1024) {
                    $error = 'Ukuran gambar maksimal 2MB!';
                } else {
                    $new_name    = 'produk_' . time() . '_' . uniqid() . '.' . $file_ext;
                    $upload_path = __DIR__ . '/../../assets/img/' . $new_name;
                    if (move_uploaded_file($_FILES['gambar']['tmp_name'], $upload_path)) {
                        // Hapus gambar lama jika ada
                        if ($gambar_nama && file_exists(__DIR__ . '/../../assets/img/' . $gambar_nama)) {
                            unlink(__DIR__ . '/../../assets/img/' . $gambar_nama);
                        }
                        $gambar_nama = $new_name;
                    } else {
                        $error = 'Gagal mengupload gambar baru. Pastikan folder assets/img dapat ditulis.';
                    }
                }
            }
        }

        if (empty($error)) {
            $stmt = $koneksi->prepare("UPDATE produk SET id_kategori=?, nama_produk=?, harga=?, stok=?, gambar=?, deskripsi=? WHERE id=?");
            $stmt->bind_param('isiissi', $id_kategori, $nama_produk, $harga, $stok, $gambar_nama, $deskripsi, $id);
            if ($stmt->execute()) {
                $_SESSION['success'] = 'Produk berhasil diperbarui!';
                header('Location: produk.php');
                exit();
            } else {
                $error = 'Gagal memperbarui produk.';
            }
        }
    }

    // Update local for re-display
    $produk['id_kategori']  = $_POST['id_kategori'] ?? $produk['id_kategori'];
    $produk['nama_produk']  = $nama_produk;
    $produk['harga']        = $harga;
    $produk['stok']         = $stok;
    $produk['deskripsi']    = $deskripsi;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Edit Produk - Velox Co Admin</title>
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
        <a href="produk.php" style="color: #555; font-family: var(--font-ui); font-size: 0.8rem; letter-spacing: 1px; text-transform: uppercase;">← Kembali ke Produk</a>
      </div>

      <?php if ($error): ?>
      <div class="alert-velox alert-auto-hide mb-3">⚠️ <?= htmlspecialchars($error) ?></div>
      <?php endif; ?>

      <div class="admin-form-wrapper">
        <h2 style="font-family: var(--font-display); font-size: 1.5rem; letter-spacing: 3px; color: white; margin-bottom: 0.5rem;">EDIT PRODUK</h2>
        <div style="font-size: 0.78rem; color: #333; margin-bottom: 2rem;">ID: #<?= $id ?></div>

        <form method="POST" action="" enctype="multipart/form-data">
          <div class="row g-4">
            <!-- Kolom Kiri -->
            <div class="col-lg-8">
              <div class="mb-4">
                <label class="form-label-velox" for="nama_produk">Nama Produk <span style="color: var(--accent);">*</span></label>
                <input 
                  type="text" name="nama_produk" id="nama_produk"
                  class="form-control form-control-velox"
                  value="<?= htmlspecialchars($produk['nama_produk']) ?>"
                  required
                >
              </div>

              <div class="row g-3 mb-4">
                <div class="col-md-6">
                  <label class="form-label-velox" for="id_kategori">Kategori <span style="color: var(--accent);">*</span></label>
                  <select name="id_kategori" id="id_kategori" class="form-select form-select-velox" required>
                    <option value="">-- Pilih Kategori --</option>
                    <?php
                    $kategoris->data_seek(0);
                    while ($k = $kategoris->fetch_assoc()):
                    ?>
                    <option value="<?= $k['id'] ?>" <?= $produk['id_kategori'] == $k['id'] ? 'selected' : '' ?>>
                      <?= htmlspecialchars($k['nama_kategori']) ?>
                    </option>
                    <?php endwhile; ?>
                  </select>
                </div>
                <div class="col-md-6">
                  <label class="form-label-velox" for="harga">Harga (Rp) <span style="color: var(--accent);">*</span></label>
                  <input 
                    type="number" name="harga" id="harga"
                    class="form-control form-control-velox"
                    value="<?= htmlspecialchars($produk['harga']) ?>"
                    min="0" required
                  >
                </div>
              </div>

              <div class="mb-4">
                <label class="form-label-velox" for="stok">Stok</label>
                <input 
                  type="number" name="stok" id="stok"
                  class="form-control form-control-velox"
                  value="<?= htmlspecialchars($produk['stok']) ?>"
                  min="0"
                >
              </div>

              <div class="mb-4">
                <label class="form-label-velox" for="deskripsi">Deskripsi Produk</label>
                <textarea 
                  name="deskripsi" id="deskripsi"
                  class="form-control form-control-velox"
                  rows="5"
                ><?= htmlspecialchars($produk['deskripsi'] ?? '') ?></textarea>
              </div>
            </div>

            <!-- Kolom Kanan - Gambar -->
            <div class="col-lg-4">
              <label class="form-label-velox">Gambar Produk</label>

              <!-- Gambar saat ini -->
              <?php if ($produk['gambar'] && file_exists('../../assets/img/' . $produk['gambar'])): ?>
              <div style="margin-bottom: 1rem; text-align: center;">
                <div style="font-size: 0.72rem; color: #444; margin-bottom: 0.5rem; font-family: var(--font-ui); letter-spacing: 1px;">GAMBAR SAAT INI</div>
                <img src="../../assets/img/<?= htmlspecialchars($produk['gambar']) ?>" 
                     alt="Current" 
                     style="max-width: 100%; max-height: 200px; border-radius: 8px; border: 1px solid #2a2a2a; object-fit: cover;">
              </div>
              <?php endif; ?>

              <div style="background: #0d0d0d; border: 2px dashed #2a2a2a; border-radius: var(--radius); padding: 1.5rem; text-align: center; cursor: pointer; transition: all 0.3s;"
                   onclick="document.getElementById('gambar').click()"
                   onmouseover="this.style.borderColor='#ff3c3c'"
                   onmouseout="this.style.borderColor='#2a2a2a'">
                <img id="imagePreview" src="" alt="" style="display: none; max-width: 100%; max-height: 150px; border-radius: 8px; margin-bottom: 0.5rem; object-fit: cover;">
                <div id="uploadIcon" style="font-size: 2rem; margin-bottom: 0.5rem;">🔄</div>
                <div id="uploadText" style="font-family: var(--font-ui); font-size: 0.75rem; color: #444; letter-spacing: 1px;">
                  <?= $produk['gambar'] ? 'Klik untuk ganti gambar' : 'Klik untuk pilih gambar' ?>
                </div>
                <div style="font-size: 0.7rem; color: #333; margin-top: 0.3rem;">JPG, JPEG, PNG · Maks 2MB</div>
                <input type="file" name="gambar" id="gambar" accept="image/jpeg,image/jpg,image/png" style="display: none;">
              </div>
            </div>
          </div>

          <hr style="border-color: #1e1e1e; margin: 1.5rem 0;">
          <div class="d-flex gap-3">
            <button type="submit" class="btn-velox btn-velox-warning">✏️ Update Produk</button>
            <a href="produk.php" class="btn-velox btn-velox-secondary">Batal</a>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
<script src="../../assets/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="../../assets/js/main.js"></script>
<script>
document.getElementById('gambar').addEventListener('change', function() {
  const file = this.files[0];
  if (file) {
    const reader = new FileReader();
    reader.onload = function(e) {
      const preview = document.getElementById('imagePreview');
      const icon    = document.getElementById('uploadIcon');
      const text    = document.getElementById('uploadText');
      preview.src   = e.target.result;
      preview.style.display = 'block';
      icon.style.display = 'none';
      text.textContent = '✅ ' + file.name;
    };
    reader.readAsDataURL(file);
  }
});
</script>
</body>
</html>
