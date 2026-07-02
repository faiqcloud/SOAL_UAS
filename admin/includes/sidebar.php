<?php
// admin/includes/sidebar.php
$current_page = basename($_SERVER['PHP_SELF']);
$current_dir  = basename(dirname($_SERVER['PHP_SELF']));


$base_url = "/SOAL_UAS"; 
$admin_url = $base_url . "/admin";
?>

<aside class="admin-sidebar" id="adminSidebar">
  <div class="sidebar-brand">
    <div class="sidebar-brand-name">VELOX <span>CO</span></div>
    <div class="sidebar-brand-sub">Admin Panel</div>
  </div>

  <nav class="sidebar-nav">
    <div class="sidebar-section-label">Main</div>

    <a href="<?= $admin_url ?>/index.php"
       class="sidebar-item <?= ($current_dir === 'admin' && $current_page === 'index.php') ? 'active' : '' ?>">
      <span class="icon">⬛</span> Dashboard
    </a>

    <div class="sidebar-section-label">Manajemen Data</div>

    <a href="<?= $admin_url ?>/kategori/kategori.php" 
       class="sidebar-item <?= ($current_dir === 'kategori') ? 'active' : '' ?>">
      <span class="icon">🏷️</span> Data Kategori
    </a>

    <a href="<?= $admin_url ?>/produk/produk.php" 
       class="sidebar-item <?= ($current_dir === 'produk') ? 'active' : '' ?>">
      <span class="icon">👕</span> Data Produk
    </a>

    <div class="sidebar-section-label">Website</div>

    <a href="<?= $base_url ?>/index.php" target="_blank" class="sidebar-item">
      <span class="icon">🌐</span> Lihat Toko
    </a>
  </nav>

  <div class="sidebar-logout">
    <a href="<?= $base_url ?>/logout.php" class="btn-velox btn-velox-danger w-100" style="justify-content: center; text-decoration: none;">
      <span>⏻</span> Logout
    </a>
    <div style="text-align: center; margin-top: 0.75rem; font-size: 0.72rem; color: #2a2a2a; font-family: var(--font-ui);">
      <?= htmlspecialchars($_SESSION['admin_nama'] ?? 'Admin') ?>
    </div>
  </div>
</aside>