<?php
// detail.php - Halaman Detail Produk
require_once 'config/koneksi.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($id <= 0) {
    header('Location: index.php');
    exit();
}

$stmt = $koneksi->prepare("SELECT p.*, k.nama_kategori FROM produk p LEFT JOIN kategori k ON p.id_kategori = k.id WHERE p.id = ?");
$stmt->bind_param('i', $id);
$stmt->execute();
$result = $stmt->get_result();
$produk = $result->fetch_assoc();

if (!$produk) {
    header('Location: index.php');
    exit();
}

// Produk terkait (kategori sama)
$related = $koneksi->prepare("SELECT p.*, k.nama_kategori FROM produk p LEFT JOIN kategori k ON p.id_kategori = k.id WHERE p.id_kategori = ? AND p.id != ? LIMIT 4");
$related->bind_param('ii', $produk['id_kategori'], $id);
$related->execute();
$related_products = $related->get_result();
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="description" content="<?= htmlspecialchars(substr($produk['deskripsi'], 0, 150)) ?>">
  <title><?= htmlspecialchars($produk['nama_produk']) ?> - Velox Co</title>
  <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css">
  <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

<!-- NAVBAR -->
<nav class="navbar-velox">
  <div class="container">
    <div class="d-flex align-items-center justify-content-between w-100">
      <a href="index.php" class="navbar-brand-velox">VELOX <span>CO</span></a>
      <div class="d-none d-lg-flex align-items-center gap-2">
        <a href="index.php#beranda" class="nav-link-velox">Home</a>
        <a href="index.php#produk" class="nav-link-velox">Produk</a>
        <a href="index.php#testimoni" class="nav-link-velox">Testimoni</a>
        <a href="index.php#kontak" class="nav-link-velox">Kontak</a>
      </div>
      <a href="login.php" class="btn-velox-primary" style="padding: 0.5rem 1.25rem; border-radius: 4px; font-size: 0.75rem;">Admin</a>
    </div>
  </div>
</nav>

<!-- DETAIL PRODUK -->
<section class="product-detail-section">
  <div class="container">
    <!-- Breadcrumb -->
    <nav style="margin-bottom: 2rem;">
      <div style="display: flex; align-items: center; gap: 0.5rem; font-family: var(--font-ui); font-size: 0.78rem; color: #444;">
        <a href="index.php" style="color: #444;">Home</a>
        <span>›</span>
        <a href="index.php?kategori=<?= $produk['id_kategori'] ?>" style="color: #444;"><?= htmlspecialchars($produk['nama_kategori']) ?></a>
        <span>›</span>
        <span style="color: #777;"><?= htmlspecialchars(substr($produk['nama_produk'], 0, 30)) ?></span>
      </div>
    </nav>

    <div class="row g-5">
      <!-- Gambar Produk -->
      <div class="col-lg-5">
        <div class="detail-img-wrapper">
          <?php if ($produk['gambar'] && file_exists('assets/img/' . $produk['gambar'])): ?>
            <img src="assets/img/<?= htmlspecialchars($produk['gambar']) ?>" alt="<?= htmlspecialchars($produk['nama_produk']) ?>">
          <?php else: ?>
            <div class="product-img-placeholder" style="aspect-ratio: 3/4; background: linear-gradient(135deg, #1a1a1a 0%, #222 100%); display: flex; flex-direction: column; align-items: center; justify-content: center; color: #333;">
              <svg width="80" height="80" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="0.5">
                <path d="M20.59 13.41l-7.17 7.17a2 2 0 01-2.83 0L2 12V2h10l8.59 8.59a2 2 0 010 2.82z"/>
                <line x1="7" y1="7" x2="7.01" y2="7"/>
              </svg>
              <div style="font-family: var(--font-display); font-size: 3rem; letter-spacing: 8px; color: #2a2a2a; margin-top: 1rem;">VELOX</div>
              <div style="font-family: var(--font-display); font-size: 1rem; letter-spacing: 4px; color: rgba(255,60,60,0.3);">CO</div>
            </div>
          <?php endif; ?>
        </div>
      </div>

      <!-- Info Produk -->
      <div class="col-lg-7">
        <div class="detail-category"><?= htmlspecialchars($produk['nama_kategori']) ?></div>
        <h1 class="detail-title"><?= htmlspecialchars($produk['nama_produk']) ?></h1>
        
        <div class="d-flex align-items-center gap-3 mb-4">
          <div class="detail-price">Rp <?= number_format($produk['harga'], 0, ',', '.') ?></div>
          <?php if ($produk['stok'] > 0): ?>
            <span class="detail-stock-badge">● In Stock</span>
          <?php else: ?>
            <span class="detail-stock-badge" style="color: #e74c3c; border-color: rgba(231,76,60,0.3); background: rgba(231,76,60,0.1);">● Habis</span>
          <?php endif; ?>
        </div>

        <hr class="detail-divider">

        <!-- Deskripsi -->
        <div class="detail-desc">
          <?php if (!empty($produk['deskripsi'])): ?>
            <?= nl2br(htmlspecialchars($produk['deskripsi'])) ?>
          <?php else: ?>
            Produk streetwear premium dari Velox Co. Kualitas terjamin dengan bahan terbaik.
          <?php endif; ?>
        </div>

        <hr class="detail-divider">

        <!-- Detail Info -->
        <div class="row g-3 mb-4">
          <div class="col-6">
            <div style="background: #111; border: 1px solid #1e1e1e; border-radius: 8px; padding: 1rem; text-align: center;">
              <div style="font-family: var(--font-display); font-size: 1.8rem; color: white;"><?= $produk['stok'] ?></div>
              <div style="font-size: 0.72rem; color: #444; letter-spacing: 2px; text-transform: uppercase; font-family: var(--font-ui);">Stok Tersedia</div>
            </div>
          </div>
          <div class="col-6">
            <div style="background: #111; border: 1px solid #1e1e1e; border-radius: 8px; padding: 1rem; text-align: center;">
              <div style="font-family: var(--font-display); font-size: 1.8rem; color: white;">220</div>
              <div style="font-size: 0.72rem; color: #444; letter-spacing: 2px; text-transform: uppercase; font-family: var(--font-ui);">GSM Cotton</div>
            </div>
          </div>
        </div>

        <!-- Ukuran -->
        <div style="margin-bottom: 2rem;">
          <div style="font-family: var(--font-ui); font-size: 0.75rem; font-weight: 700; letter-spacing: 2px; text-transform: uppercase; color: #444; margin-bottom: 0.75rem;">Pilih Ukuran</div>
          <div style="display: flex; gap: 0.5rem; flex-wrap: wrap;">
            <?php foreach(['S','M','L','XL','XXL'] as $size): ?>
            <button style="width: 48px; height: 48px; background: #111; border: 1px solid #2a2a2a; border-radius: 6px; color: #777; font-family: var(--font-ui); font-size: 0.8rem; font-weight: 600; cursor: pointer; transition: all 0.2s;" 
              onmouseover="this.style.borderColor='#ff3c3c'; this.style.color='#ff3c3c';" 
              onmouseout="this.style.borderColor='#2a2a2a'; this.style.color='#777';">
              <?= $size ?>
            </button>
            <?php endforeach; ?>
          </div>
        </div>

        <!-- Tombol CTA -->
        <?php if ($produk['stok'] > 0): ?>
        <div class="d-flex gap-3" style="flex-wrap: wrap;">
          <button class="btn-velox-primary" style="flex: 1; justify-content: center; min-width: 160px; border-radius: 6px; padding: 1rem;">
            🛒 Tambah ke Keranjang
          </button>
          <button class="btn-velox-outline" style="flex: 1; justify-content: center; min-width: 160px; border-radius: 6px; padding: 1rem;">
            💚 Beli via WhatsApp
          </button>
        </div>
        <?php else: ?>
        <button class="btn-velox-outline" style="width: 100%; justify-content: center; border-radius: 6px; padding: 1rem; cursor: not-allowed; opacity: 0.5;" disabled>
          Stok Habis
        </button>
        <?php endif; ?>

        <div style="margin-top: 1.5rem; display: flex; gap: 2rem; flex-wrap: wrap;">
          <div style="display: flex; align-items: center; gap: 0.5rem; font-size: 0.78rem; color: #444;">
            <span>🚚</span> <span>Free ongkir &gt; Rp200rb</span>
          </div>
          <div style="display: flex; align-items: center; gap: 0.5rem; font-size: 0.78rem; color: #444;">
            <span>🔄</span> <span>Return 7 hari</span>
          </div>
          <div style="display: flex; align-items: center; gap: 0.5rem; font-size: 0.78rem; color: #444;">
            <span>✅</span> <span>100% Original</span>
          </div>
        </div>
      </div>
    </div>

    <!-- Produk Terkait -->
    <?php if ($related_products && $related_products->num_rows > 0): ?>
    <div class="mt-5 pt-5" style="border-top: 1px solid var(--border);">
      <div class="section-header" style="margin-bottom: 2rem; text-align: left;">
        <div class="section-label">Dari Kategori Sama</div>
        <h3 style="font-family: var(--font-display); font-size: 2rem; letter-spacing: 3px; color: white;">PRODUK TERKAIT</h3>
      </div>
      <div class="row g-4">
        <?php while ($rp = $related_products->fetch_assoc()): ?>
        <div class="col-6 col-md-3">
          <div class="product-card">
            <div class="product-img-wrapper">
              <?php if ($rp['gambar'] && file_exists('assets/img/' . $rp['gambar'])): ?>
                <img src="assets/img/<?= htmlspecialchars($rp['gambar']) ?>" alt="<?= htmlspecialchars($rp['nama_produk']) ?>">
              <?php else: ?>
                <div class="product-img-placeholder">
                  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" width="50" height="50" style="opacity: 0.15;">
                    <path d="M20.59 13.41l-7.17 7.17a2 2 0 01-2.83 0L2 12V2h10l8.59 8.59a2 2 0 010 2.82z"/>
                  </svg>
                  <span style="font-size: 0.9rem; margin-top: 0.5rem;">VELOX CO</span>
                </div>
              <?php endif; ?>
              <div class="product-overlay">
                <a href="detail.php?id=<?= $rp['id'] ?>" class="btn-quick-view">Lihat Detail</a>
              </div>
            </div>
            <div class="product-info">
              <div class="product-category-tag"><?= htmlspecialchars($rp['nama_kategori']) ?></div>
              <div class="product-name"><?= htmlspecialchars($rp['nama_produk']) ?></div>
              <div class="product-price">Rp <?= number_format($rp['harga'], 0, ',', '.') ?></div>
            </div>
          </div>
        </div>
        <?php endwhile; ?>
      </div>
    </div>
    <?php endif; ?>

    <!-- Tombol Kembali -->
    <div class="text-center mt-5">
      <a href="index.php" class="btn-velox-outline" style="border-radius: 6px; padding: 0.75rem 2rem;">
        ← Kembali ke Katalog
      </a>
    </div>
  </div>
</section>

<!-- FOOTER -->
<footer class="footer-velox" style="padding: 2rem 0;">
  <div class="container">
    <div class="footer-bottom">
      © 2025 <strong style="color: #555;">Velox Co</strong> — Premium Streetwear Indonesia. All rights reserved.
    </div>
  </div>
</footer>

<script src="assets/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="assets/js/main.js"></script>
</body>
</html>
