<?php
// admin/produk/tambah.php - Tambah Produk
session_start();
if (!isset($_SESSION['admin_id'])) { header('Location: ../../login.php'); exit(); }
require_once '../../config/koneksi.php';

$page_title = 'Tambah Produk';
$error = '';

$kategoris = $koneksi->query("SELECT * FROM kategori ORDER BY nama_kategori ASC");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_kategori  = (int)($_POST['id_kategori'] ?? 0);
    $nama_produk  = trim($_POST['nama_produk'] ?? '');
    $harga        = (int)($_POST['harga'] ?? 0);
    $stok         = (int)($_POST['stok'] ?? 0);
    $deskripsi    = trim($_POST['deskripsi'] ?? '');
    $gambar_nama  = '';

    // Validasi
    if ($id_kategori <= 0 || empty($nama_produk) || $harga <= 0) {
        $error = 'Kategori, nama produk, dan harga wajib diisi dengan benar!';
    } else {
        // Upload gambar
        if (isset($_FILES['gambar']) && $_FILES['gambar']['error'] !== UPLOAD_ERR_NO_FILE) {
            if ($_FILES['gambar']['error'] !== UPLOAD_ERR_OK) {
                $error = 'Gagal upload gambar. Error code: ' . $_FILES['gambar']['error'];
            } else {
                $allowed = ['image/jpeg', 'image/jpg', 'image/png'];
                $file_type = $_FILES['gambar']['type'];
                $file_size = $_FILES['gambar']['size'];
                $file_ext  = strtolower(pathinfo($_FILES['gambar']['name'], PATHINFO_EXTENSION));

                if (!in_array($file_type, $allowed)) {
                    $error = 'Format gambar harus JPG, JPEG, atau PNG!';
                } elseif ($file_size > 2 * 1024 * 1024) {
                    $error = 'Ukuran gambar maksimal 2MB!';
                } else {
                    $gambar_nama = 'produk_' . time() . '_' . uniqid() . '.' . $file_ext;
                    $upload_path = __DIR__ . '/../../assets/img/' . $gambar_nama;
                    if (!move_uploaded_file($_FILES['gambar']['tmp_name'], $upload_path)) {
                        $error = 'Gagal mengupload gambar. Pastikan folder assets/img dapat ditulis.';
                        $gambar_nama = '';
                    }
                }
            }
        }

        if (empty($error)) {
            $stmt = $koneksi->prepare("INSERT INTO produk (id_kategori, nama_produk, harga, stok, gambar, deskripsi) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->bind_param('isiiss', $id_kategori, $nama_produk, $harga, $stok, $gambar_nama, $deskripsi);
            if ($stmt->execute()) {
                $_SESSION['success'] = 'Produk berhasil ditambahkan!';
                header('Location: produk.php');
                exit();
            } else {
                $error = 'Gagal menyimpan produk. Coba lagi.';
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Tambah Produk - Velox Co Admin</title>
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
        <h2 style="font-family: var(--font-display); font-size: 1.5rem; letter-spacing: 3px; color: white; margin-bottom: 2rem;">TAMBAH PRODUK</h2>

        <form method="POST" action="" enctype="multipart/form-data">
          <div class="row g-4">
            <!-- Kolom Kiri -->
            <div class="col-lg-8">
              <div class="mb-4">
                <label class="form-label-velox" for="nama_produk">Nama Produk <span style="color: var(--accent);">*</span></label>
                <input 
                  type="text" name="nama_produk" id="nama_produk"
                  class="form-control form-control-velox"
                  placeholder="Contoh: Velox Urban Oversized Black"
                  value="<?= htmlspecialchars($_POST['nama_produk'] ?? '') ?>"
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
                    <option value="<?= $k['id'] ?>" <?= (($_POST['id_kategori'] ?? '') == $k['id']) ? 'selected' : '' ?>>
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
                    placeholder="Contoh: 189000"
                    value="<?= htmlspecialchars($_POST['harga'] ?? '') ?>"
                    min="0" required
                  >
                </div>
              </div>

              <div class="mb-4">
                <label class="form-label-velox" for="stok">Stok</label>
                <input 
                  type="number" name="stok" id="stok"
                  class="form-control form-control-velox"
                  placeholder="Jumlah stok tersedia"
                  value="<?= htmlspecialchars($_POST['stok'] ?? '0') ?>"
                  min="0"
                >
              </div>

              <div class="mb-4">
                <label class="form-label-velox" for="deskripsi">Deskripsi Produk</label>
                <textarea 
                  name="deskripsi" id="deskripsi"
                  class="form-control form-control-velox"
                  placeholder="Deskripsi lengkap produk, bahan, ukuran, dll..."
                  rows="5"
                ><?= htmlspecialchars($_POST['deskripsi'] ?? '') ?></textarea>
              </div>
            </div>

            <!-- Kolom Kanan - Upload Gambar -->
            <div class="col-lg-4">
              <label class="form-label-velox">Gambar Produk</label>
              <div style="background: #0d0d0d; border: 2px dashed #2a2a2a; border-radius: var(--radius); padding: 1.5rem; text-align: center; cursor: pointer; transition: all 0.3s;" 
                   onclick="document.getElementById('gambar').click()"
                   onmouseover="this.style.borderColor='#ff3c3c'"
                   onmouseout="this.style.borderColor='#2a2a2a'">
                <img id="imagePreview" src="" alt="" style="display: none; max-width: 100%; max-height: 200px; border-radius: 8px; margin-bottom: 1rem; object-fit: cover;">
                <div id="uploadIcon" style="font-size: 2.5rem; margin-bottom: 0.5rem;">📷</div>
                <div id="uploadText" style="font-family: var(--font-ui); font-size: 0.78rem; color: #444; letter-spacing: 1px;">Klik untuk pilih gambar</div>
                <div style="font-size: 0.7rem; color: #333; margin-top: 0.4rem;">JPG, JPEG, PNG · Maks 2MB</div>
                <input type="file" name="gambar" id="gambar" accept="image/jpeg,image/jpg,image/png" style="display: none;">
              </div>
              <div style="font-size: 0.72rem; color: #2a2a2a; margin-top: 0.5rem; text-align: center;">
                Gambar opsional. Jika tidak diupload, akan tampil placeholder.
              </div>
            </div>
          </div>

          <hr style="border-color: #1e1e1e; margin: 1.5rem 0;">
          <div class="d-flex gap-3">
            <button type="submit" class="btn-velox btn-velox-success">✅ Simpan Produk</button>
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
      text.textContent = file.name;
    };
    reader.readAsDataURL(file);
  }
});
</script>
</body>
</html>
