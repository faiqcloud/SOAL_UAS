<?php
// admin/includes/topbar.php
$page_title = $page_title ?? 'Admin';
?>
<header class="admin-topbar">
  <div class="d-flex align-items-center gap-3">
    <button id="sidebarToggle" class="d-lg-none" style="background: none; border: 1px solid #2a2a2a; border-radius: 4px; padding: 0.4rem 0.6rem; color: #666; cursor: pointer;">☰</button>
    <div class="topbar-title"><?= htmlspecialchars($page_title) ?></div>
  </div>
  <div class="topbar-user">
    <div class="topbar-avatar"><?= strtoupper(substr($_SESSION['admin_username'] ?? 'A', 0, 1)) ?></div>
    <div>
      <div class="topbar-username"><?= htmlspecialchars($_SESSION['admin_nama'] ?? 'Admin') ?></div>
      <div style="font-size: 0.7rem; color: #333;">Administrator</div>
    </div>
  </div>
</header>
