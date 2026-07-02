<?php
// index.php - Halaman Utama Frontend Velox Co
require_once 'config/koneksi.php';

// Ambil kategori
$kategoris = $koneksi->query("SELECT * FROM kategori ORDER BY nama_kategori ASC");

// Ambil produk berdasarkan filter kategori
$filter_kategori = isset($_GET['kategori']) ? (int)$_GET['kategori'] : 0;
$search = isset($_GET['search']) ? trim($_GET['search']) : '';

$sql_produk = "SELECT p.*, k.nama_kategori FROM produk p 
               LEFT JOIN kategori k ON p.id_kategori = k.id WHERE 1=1";

if ($filter_kategori > 0) {
    $sql_produk .= " AND p.id_kategori = " . $filter_kategori;
}
if (!empty($search)) {
    $search_safe = $koneksi->real_escape_string($search);
    $sql_produk .= " AND (p.nama_produk LIKE '%$search_safe%' OR p.deskripsi LIKE '%$search_safe%')";
}

$sql_produk .= " ORDER BY p.created_at DESC";
$produks = $koneksi->query($sql_produk);

// Count produk
$total_produk = $koneksi->query("SELECT COUNT(*) as total FROM produk")->fetch_assoc()['total'];
$total_kategori = $koneksi->query("SELECT COUNT(*) as total FROM kategori")->fetch_assoc()['total'];
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="description" content="Velox Co - Toko kaos streetwear premium. Temukan koleksi oversized tee, graphic tee, dan berbagai pilihan kaos streetwear terbaik.">
  <title>Velox Co - Premium Streetwear Store</title>
  <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css">
  <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

<!-- ============= NAVBAR ============= -->
<nav class="navbar-velox">
  <div class="container">
    <div class="d-flex align-items-center justify-content-between w-100">
      <a href="index.php" class="navbar-brand-velox">VELOX <span>CO</span></a>

      <!-- Desktop Nav -->
      <div class="d-none d-lg-flex align-items-center gap-2">
        <a href="#beranda" class="nav-link-velox active">Home</a>
        <a href="#produk" class="nav-link-velox">Produk</a>
        <a href="#testimoni" class="nav-link-velox">Testimoni</a>
        <a href="#kontak" class="nav-link-velox">Kontak</a>
      </div>

      <div class="d-flex align-items-center gap-3">
        <?php if (isset($_GET['search'])): ?>
        <form class="d-none d-md-flex" method="GET">
          <input type="text" name="search" class="form-control form-control-velox" placeholder="Cari produk..." value="<?= htmlspecialchars($search) ?>" style="width: 200px; padding: 0.5rem 0.75rem; font-size: 0.82rem;">
        </form>
        <?php else: ?>
        <button onclick="document.getElementById('searchBar').classList.toggle('d-none')" style="background: none; border: none; color: #666; font-size: 1.2rem; cursor: pointer;">🔍</button>
        <?php endif; ?>
        <!-- Mobile menu button -->
        <button class="d-lg-none" id="mobileMenuBtn" style="background: none; border: 1px solid #333; border-radius: 4px; padding: 0.4rem 0.6rem; color: #aaa; cursor: pointer;">☰</button>
      </div>
    </div>

    <!-- Search Bar -->
    <div id="searchBar" class="d-none mt-2">
      <form method="GET">
        <div class="d-flex gap-2">
          <input type="text" name="search" class="form-control form-control-velox flex-grow-1" placeholder="Cari produk..." value="<?= htmlspecialchars($search) ?>">
          <button type="submit" class="btn-velox-primary" style="padding: 0.5rem 1rem; border-radius: 4px; white-space: nowrap;">Cari</button>
        </div>
      </form>
    </div>

    <!-- Mobile Nav -->
    <div id="mobileMenu" class="d-lg-none d-none mt-3">
      <div class="d-flex flex-column gap-1 pb-2">
        <a href="#beranda" class="nav-link-velox">Home</a>
        <a href="#produk" class="nav-link-velox">Produk</a>
        <a href="#testimoni" class="nav-link-velox">Testimoni</a>
        <a href="#kontak" class="nav-link-velox">Kontak</a>
      </div>
    </div>
  </div>
</nav>

<script>
document.getElementById('mobileMenuBtn')?.addEventListener('click', () => {
  document.getElementById('mobileMenu').classList.toggle('d-none');
});
</script>

<!-- ============= HERO SECTION ============= -->
<section class="hero-section" id="beranda">
  <div class="container py-5">
    <div class="row align-items-center min-vh-100 py-5">
      <div class="col-lg-6 py-5">
        <div class="hero-badge">🔥 New Collection 2025</div>
        <h1 class="hero-title">
          WEAR YOUR
          <span class="line-accent">STREET</span>
          STORY
        </h1>
        <p class="hero-subtitle">
          Premium streetwear yang berbicara tentang identitasmu.<br>
          Kaos berkualitas tinggi, desain eksklusif, harga terjangkau.
        </p>
        <div class="hero-cta">
          <a href="#produk" class="btn-velox-primary">Shop Now →</a>
          <a href="#testimoni" class="btn-velox-outline">Lihat Review</a>
        </div>
        <div class="hero-stats">
          <div class="hero-stat-item">
            <div class="hero-stat-number"><?= $total_produk ?>+</div>
            <div class="hero-stat-label">Produk</div>
          </div>
          <div class="hero-stat-item">
            <div class="hero-stat-number"><?= $total_kategori ?></div>
            <div class="hero-stat-label">Kategori</div>
          </div>
          <div class="hero-stat-item">
            <div class="hero-stat-number">100%</div>
            <div class="hero-stat-label">Original</div>
          </div>
          <div class="hero-stat-item">
            <div class="hero-stat-number">5★</div>
            <div class="hero-stat-label">Rating</div>
          </div>
        </div>
      </div>
      <div class="col-lg-6 d-none d-lg-block">
        <div class="hero-visual">
          <div class="hero-visual-card">
            <div class="hero-floating-badge top-right">NEW DROP</div>
            <div class="hero-floating-badge bottom-left">★ 5.0 RATED</div>
            <div style="text-align: center; padding: 3rem 2rem;">
              <div style="font-family: var(--font-display); font-size: 8rem; line-height: 1; color: #1a1a1a; letter-spacing: -5px;">
                VLX
              </div>
              <div style="font-family: var(--font-display); font-size: 1.5rem; letter-spacing: 8px; color: #ff3c3c; margin-top: -1rem;">
                STREETWEAR
              </div>
              <div style="margin-top: 2rem; display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                <?php
                $featured = $koneksi->query("SELECT nama_produk, harga FROM produk LIMIT 2");
                while ($fp = $featured->fetch_assoc()):
                ?>
                <div style="background: #1a1a1a; border-radius: 8px; padding: 1rem; text-align: left; border: 1px solid #2a2a2a;">
                  <div style="font-size: 0.7rem; color: #ff3c3c; font-family: var(--font-ui); letter-spacing: 1px; font-weight: 600; margin-bottom: 0.3rem;">BESTSELLER</div>
                  <div style="font-size: 0.82rem; color: #ddd; font-weight: 600;"><?= htmlspecialchars(substr($fp['nama_produk'], 0, 20)) ?>...</div>
                  <div style="font-family: var(--font-display); font-size: 1.2rem; color: white; margin-top: 0.3rem;">Rp <?= number_format($fp['harga'], 0, ',', '.') ?></div>
                </div>
                <?php endwhile; ?>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- ============= MARQUEE ============= -->
<div class="marquee-section">
  <div class="marquee-track" id="marqueeTrack">
    <?php for($i=0; $i<3; $i++): ?>
    <span class="marquee-item">VELOX CO</span>
    <span class="marquee-item">●</span>
    <span class="marquee-item">STREETWEAR</span>
    <span class="marquee-item">●</span>
    <span class="marquee-item">PREMIUM QUALITY</span>
    <span class="marquee-item">●</span>
    <span class="marquee-item">NEW COLLECTION</span>
    <span class="marquee-item">●</span>
    <span class="marquee-item">FREE ONGKIR</span>
    <span class="marquee-item">●</span>
    <span class="marquee-item">LIMITED STOCK</span>
    <span class="marquee-item">●</span>
    <?php endfor; ?>
  </div>
</div>
<script>
// Duplicate marquee items for seamless loop
const track = document.getElementById('marqueeTrack');
if (track) track.innerHTML += track.innerHTML;
</script>

<!-- ============= FEATURES ============= -->
<section class="features-section">
  <div class="container">
    <div class="row g-0">
      <div class="col-6 col-md-3">
        <div class="feature-item">
          <div class="feature-icon">🚚</div>
          <div class="feature-title">Free Ongkir</div>
          <div class="feature-desc">Gratis ongkos kirim untuk semua pembelian di atas Rp 200rb</div>
        </div>
      </div>
      <div class="col-6 col-md-3">
        <div class="feature-item">
          <div class="feature-icon">✅</div>
          <div class="feature-title">100% Original</div>
          <div class="feature-desc">Semua produk dijamin original dan berkualitas premium</div>
        </div>
      </div>
      <div class="col-6 col-md-3">
        <div class="feature-item">
          <div class="feature-icon">🔄</div>
          <div class="feature-title">Easy Return</div>
          <div class="feature-desc">Pengembalian barang mudah dalam 7 hari jika ada cacat produksi</div>
        </div>
      </div>
      <div class="col-6 col-md-3">
        <div class="feature-item">
          <div class="feature-icon">🛡️</div>
          <div class="feature-title">Secure Payment</div>
          <div class="feature-desc">Pembayaran aman dengan berbagai metode pilihan</div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- ============= PRODUCTS SECTION ============= -->
<section class="products-section" id="produk">
  <div class="container">
    <div class="section-header">
      <div class="section-label">Our Collection</div>
      <h2 class="section-title">PRODUK PILIHAN</h2>
      <p class="section-subtitle">Temukan kaos streetwear terbaik dari koleksi eksklusif Velox Co</p>
    </div>

    <!-- Category Tabs -->
    <div class="category-tabs">
      <a href="index.php" class="cat-tab <?= $filter_kategori === 0 && empty($search) ? 'active' : '' ?>">Semua</a>
      <?php
      $kategoris->data_seek(0);
      while ($kat = $kategoris->fetch_assoc()):
      ?>
      <a href="?kategori=<?= $kat['id'] ?>" class="cat-tab <?= $filter_kategori === (int)$kat['id'] ? 'active' : '' ?>">
        <?= htmlspecialchars($kat['nama_kategori']) ?>
      </a>
      <?php endwhile; ?>
    </div>

    <?php if (!empty($search)): ?>
    <div style="text-align: center; margin-bottom: 2rem;">
      <span style="color: #666; font-size: 0.85rem; font-family: var(--font-ui);">
        Hasil pencarian untuk "<strong style="color: #aaa;"><?= htmlspecialchars($search) ?></strong>"
        &nbsp;|&nbsp;
        <a href="index.php" style="color: var(--accent);">Hapus Filter</a>
      </span>
    </div>
    <?php endif; ?>

    <!-- Products Grid -->
    <div class="row g-4">
      <?php if ($produks && $produks->num_rows > 0): ?>
        <?php while ($produk = $produks->fetch_assoc()): ?>
        <div class="col-6 col-md-4 col-lg-3">
          <div class="product-card">
            <div class="product-img-wrapper">
              <?php if ($produk['gambar'] && file_exists('assets/img/' . $produk['gambar'])): ?>
                <img src="assets/img/<?= htmlspecialchars($produk['gambar']) ?>" alt="<?= htmlspecialchars($produk['nama_produk']) ?>">
              <?php else: ?>
                <div class="product-img-placeholder">
                  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1">
                    <path d="M20.59 13.41l-7.17 7.17a2 2 0 01-2.83 0L2 12V2h10l8.59 8.59a2 2 0 010 2.82z"/>
                    <line x1="7" y1="7" x2="7.01" y2="7"/>
                  </svg>
                  <span>VELOX CO</span>
                </div>
              <?php endif; ?>
              <div class="product-badge"><?= htmlspecialchars($produk['nama_kategori']) ?></div>
              <?php if ($produk['stok'] > 0): ?>
                <div class="product-badge badge-stok">Stok: <?= $produk['stok'] ?></div>
              <?php else: ?>
                <div class="product-badge badge-habis">Habis</div>
              <?php endif; ?>
              <div class="product-overlay">
                <a href="detail.php?id=<?= $produk['id'] ?>" class="btn-quick-view">Lihat Detail</a>
              </div>
            </div>
            <div class="product-info">
              <div class="product-category-tag"><?= htmlspecialchars($produk['nama_kategori']) ?></div>
              <div class="product-name"><?= htmlspecialchars($produk['nama_produk']) ?></div>
              <div class="product-price">Rp <?= number_format($produk['harga'], 0, ',', '.') ?></div>
              <div class="product-stock">Stok: <?= $produk['stok'] ?> pcs</div>
            </div>
          </div>
        </div>
        <?php endwhile; ?>
      <?php else: ?>
        <div class="col-12 text-center py-5">
          <div style="font-size: 3rem; margin-bottom: 1rem;">🔍</div>
          <div style="color: #555; font-family: var(--font-ui); font-size: 0.9rem; letter-spacing: 1px;">
            Tidak ada produk ditemukan.
          </div>
          <a href="index.php" style="color: var(--accent); font-size: 0.85rem; margin-top: 0.5rem; display: inline-block;">Lihat semua produk</a>
        </div>
      <?php endif; ?>
    </div>
  </div>
</section>

<!-- ============= TESTIMONI ============= -->
<section class="testimonial-section" id="testimoni">
  <div class="container">
    <div class="section-header">
      <div class="section-label">Customer Reviews</div>
      <h2 class="section-title">APA KATA MEREKA</h2>
      <p class="section-subtitle">Lebih dari 500+ pelanggan puas dengan produk Velox Co</p>
    </div>
    <div class="row g-4">
      <div class="col-md-4">
        <div class="testimonial-card">
          <div class="testimonial-stars">★★★★★</div>
          <div class="testimonial-text">
            "Kualitas bahan benar-benar premium! Oversized tee dari Velox Co udah jadi favorit aku. Setiap kali pakai selalu dapet banyak compliment. Worth every penny!"
          </div>
          <div class="testimonial-author">
            <div class="testimonial-avatar">R</div>
            <div>
              <div class="testimonial-name">Rizky Pratama</div>
              <div class="testimonial-role">Pelanggan Setia · Jakarta</div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-md-4">
        <div class="testimonial-card">
          <div class="testimonial-stars">★★★★★</div>
          <div class="testimonial-text">
            "Graphic tee-nya sick banget! Desainnya unik dan gak pasaran. Pengiriman juga cepet, packaging rapi. Pasti bakal repeat order terus!"
          </div>
          <div class="testimonial-author">
            <div class="testimonial-avatar">A</div>
            <div>
              <div class="testimonial-name">Ayu Maharani</div>
              <div class="testimonial-role">Fashion Enthusiast · Bandung</div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-md-4">
        <div class="testimonial-card">
          <div class="testimonial-stars">★★★★★</div>
          <div class="testimonial-text">
            "Udah nyobain banyak brand streetwear lokal, tapi Velox Co yang paling worth it. Harganya reasonable buat kualitas segini. Recommended banget!"
          </div>
          <div class="testimonial-author">
            <div class="testimonial-avatar">D</div>
            <div>
              <div class="testimonial-name">Daffa Akbar</div>
              <div class="testimonial-role">Streetwear Collector · Surabaya</div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-md-4">
        <div class="testimonial-card">
          <div class="testimonial-stars">★★★★★</div>
          <div class="testimonial-text">
            "Crop tee-nya fit banget di badan aku. Bahan adem dan gak gerah meski seharian dipakai. Customer servicenya juga ramah dan responsif."
          </div>
          <div class="testimonial-author">
            <div class="testimonial-avatar">S</div>
            <div>
              <div class="testimonial-name">Sinta Dewi</div>
              <div class="testimonial-role">Content Creator · Yogyakarta</div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-md-4">
        <div class="testimonial-card">
          <div class="testimonial-stars">★★★★☆</div>
          <div class="testimonial-text">
            "Long sleeve navy-nya keren abis! Cocok banget buat hangout atau cafe-hopping. Temen-temen sampe nanya belinya dimana. Puas banget!"
          </div>
          <div class="testimonial-author">
            <div class="testimonial-avatar">F</div>
            <div>
              <div class="testimonial-name">Fajar Nugroho</div>
              <div class="testimonial-role">Mahasiswa · Malang</div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-md-4">
        <div class="testimonial-card">
          <div class="testimonial-stars">★★★★★</div>
          <div class="testimonial-text">
            "Sudah order lebih dari 5x dan selalu puas! Kualitas konsisten, desain fresh tiap koleksi baru. Velox Co emang gak ada lawannya buat streetwear lokal!"
          </div>
          <div class="testimonial-author">
            <div class="testimonial-avatar">N</div>
            <div>
              <div class="testimonial-name">Nadia Putri</div>
              <div class="testimonial-role">Loyal Customer · Medan</div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- ============= CONTACT ============= -->
<section class="contact-section" id="kontak">
  <div class="container">
    <div class="section-header">
      <div class="section-label">Reach Us</div>
      <h2 class="section-title">HUBUNGI KAMI</h2>
      <p class="section-subtitle">Ada pertanyaan? Kami siap membantu kamu</p>
    </div>
    <div class="row g-4 justify-content-center">
      <div class="col-6 col-md-3">
        <div class="contact-card">
          <div class="contact-icon">📍</div>
          <div class="contact-title">Lokasi</div>
          <div class="contact-value">Jl. Streetwear No. 88<br>Jakarta Selatan, DKI Jakarta</div>
        </div>
      </div>
      <div class="col-6 col-md-3">
        <div class="contact-card">
          <div class="contact-icon">📱</div>
          <div class="contact-title">WhatsApp</div>
          <div class="contact-value">+62 812 3456 7890</div>
        </div>
      </div>
      <div class="col-6 col-md-3">
        <div class="contact-card">
          <div class="contact-icon">📧</div>
          <div class="contact-title">Email</div>
          <div class="contact-value">hello@veloxco.id</div>
        </div>
      </div>
      <div class="col-6 col-md-3">
        <div class="contact-card">
          <div class="contact-icon">⏰</div>
          <div class="contact-title">Jam Operasional</div>
          <div class="contact-value">Senin - Sabtu<br>09:00 - 20:00 WIB</div>
        </div>
      </div>
    </div>

    <!-- CTA Banner -->
    <div class="text-center mt-5 pt-3">
      <div style="background: var(--card-bg); border: 1px solid var(--border); border-radius: var(--radius); padding: 3rem; position: relative; overflow: hidden;">
        <div style="position: absolute; top: 0; left: 0; right: 0; height: 3px; background: var(--gradient-accent);"></div>
        <div style="font-family: var(--font-display); font-size: 2.5rem; letter-spacing: 4px; color: white; margin-bottom: 0.5rem;">JOIN THE CREW</div>
        <div style="color: #555; font-size: 0.9rem; margin-bottom: 2rem;">Daftarkan email kamu dan dapatkan info koleksi terbaru & promo eksklusif</div>
        <div style="display: flex; max-width: 400px; margin: 0 auto; gap: 0.75rem;">
          <input type="email" placeholder="Email kamu..." class="form-control form-control-velox flex-grow-1">
          <button class="btn-velox-primary" style="white-space: nowrap; padding: 0.75rem 1.5rem; border-radius: 6px;">Subscribe</button>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- ============= FOOTER ============= -->
<footer class="footer-velox">
  <div class="container">
    <div class="row g-4">
      <div class="col-lg-4">
        <div class="footer-brand">VELOX <span>CO</span></div>
        <div class="footer-tagline">Premium Streetwear Indonesia</div>
        <p style="color: #333; font-size: 0.83rem; margin-top: 1rem; line-height: 1.8; max-width: 280px;">
          Velox Co hadir untuk kamu yang menghargai kualitas dalam setiap helai pakaian. Streetwear bukan sekadar mode, itu adalah ekspresi.
        </p>
        <div class="footer-social">
          <a href="#" class="social-btn">ig</a>
          <a href="#" class="social-btn">tw</a>
          <a href="#" class="social-btn">yt</a>
          <a href="#" class="social-btn">tt</a>
        </div>
      </div>
      <div class="col-6 col-lg-2">
        <div class="footer-heading">Produk</div>
        <?php
        $koneksi->query("SELECT * FROM kategori LIMIT 5")->data_seek ?? null;
        $footer_kat = $koneksi->query("SELECT * FROM kategori LIMIT 5");
        while ($fk = $footer_kat->fetch_assoc()):
        ?>
        <a href="index.php?kategori=<?= $fk['id'] ?>" class="footer-link"><?= htmlspecialchars($fk['nama_kategori']) ?></a>
        <?php endwhile; ?>
      </div>
      <div class="col-6 col-lg-2">
        <div class="footer-heading">Info</div>
        <a href="#" class="footer-link">Tentang Kami</a>
        <a href="#" class="footer-link">Cara Order</a>
        <a href="#" class="footer-link">Panduan Ukuran</a>
        <a href="#" class="footer-link">Kebijakan Return</a>
        <a href="#" class="footer-link">FAQ</a>
      </div>
      <div class="col-lg-4">
        <div class="footer-heading">Newsletter</div>
        <p style="color: #333; font-size: 0.82rem; margin-bottom: 1rem;">Dapatkan update koleksi terbaru</p>
        <div style="display: flex; gap: 0.5rem;">
          <input type="email" placeholder="email@example.com" class="form-control form-control-velox" style="font-size: 0.82rem;">
          <button class="btn-velox-primary" style="padding: 0.5rem 1rem; border-radius: 4px; font-size: 0.75rem; white-space: nowrap;">OK</button>
        </div>
        <div style="margin-top: 1.5rem;">
          <div class="footer-heading">Pembayaran</div>
          <div style="color: #333; font-size: 0.8rem;">Transfer Bank · E-Wallet · COD</div>
        </div>
      </div>
    </div>
    <hr class="footer-divider">
    <div class="footer-bottom">
      © 2025 <strong style="color: #555;">Velox Co</strong> — Premium Streetwear Indonesia. All rights reserved.
    </div>
  </div>
</footer>

<script src="assets/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="assets/js/main.js"></script>
</body>
</html>
