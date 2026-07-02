<?php
// login.php
session_start();

// Jika sudah login, redirect ke dashboard
if (isset($_SESSION['admin_id'])) {
    header('Location: admin/index.php');
    exit();
}

require_once 'config/koneksi.php';

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if (empty($username) || empty($password)) {
        $error = 'Username dan password harus diisi!';
    } else {
        $stmt = $koneksi->prepare("SELECT * FROM user WHERE username = ?");
        $stmt->bind_param('s', $username);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['admin_id']       = $user['id'];
            $_SESSION['admin_username'] = $user['username'];
            $_SESSION['admin_nama']     = $user['nama_lengkap'];
            header('Location: admin/index.php');
            exit();
        } else {
            $error = 'Username atau password salah!';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login Admin - Velox Co</title>
  <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css">
  <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
<div class="login-page">
  <div class="login-card">
    <div class="text-center mb-4">
      <div class="login-title">VELOX <span>CO</span></div>
      <div class="login-subtitle">Admin Dashboard</div>
    </div>

    <?php if ($error): ?>
    <div class="alert-velox alert-auto-hide">
      <?= htmlspecialchars($error) ?>
    </div>
    <?php endif; ?>

    <?php if ($success): ?>
    <div class="alert-velox-success alert-auto-hide">
      <?= htmlspecialchars($success) ?>
    </div>
    <?php endif; ?>

    <form method="POST" action="">
      <div class="mb-3">
        <label class="form-label-velox">Username</label>
        <input 
          type="text" 
          name="username" 
          id="username"
          class="form-control form-control-velox" 
          placeholder="Masukkan username"
          value="<?= htmlspecialchars($_POST['username'] ?? '') ?>"
          required
          autocomplete="username"
        >
      </div>
      <div class="mb-4">
        <label class="form-label-velox">Password</label>
        <input 
          type="password" 
          name="password" 
          id="password"
          class="form-control form-control-velox" 
          placeholder="Masukkan password"
          required
          autocomplete="current-password"
        >
      </div>
      <button type="submit" class="btn-velox-primary w-100" style="justify-content: center; border-radius: 6px;">
        Masuk Dashboard
      </button>
    </form>

    <div class="text-center mt-4">
      <a href="index.php" style="color: #444; font-size: 0.8rem; letter-spacing: 1px; font-family: var(--font-ui);">
        ← Kembali ke Toko
      </a>
    </div>

    <div class="text-center mt-3" style="font-size: 0.72rem; color: #2a2a2a; letter-spacing: 1px;">
      Default: admin / admin123
    </div>
  </div>
</div>
<script src="assets/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="assets/js/main.js"></script>
</body>
</html>
