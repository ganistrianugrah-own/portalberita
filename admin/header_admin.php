<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Portal Berita</title>

  <!-- Bootstrap 5 -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

  <!-- Custom CSS -->
  <link rel="stylesheet" href="/portal-berita/assets/css/header.css">

  <script>
    // Realtime tanggal & waktu
    function updateDateTime() {
      const now = new Date();
      const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
      document.getElementById('date').textContent = now.toLocaleDateString('id-ID', options);
      document.getElementById('time').textContent = now.toLocaleTimeString('id-ID');
    }
    setInterval(updateDateTime, 1000);
    window.onload = updateDateTime;
  </script>
</head>

<body>

<!-- HEADER NAVBAR -->
<nav class="navbar navbar-expand-lg navbar-dark gradient-header fixed-top shadow">
  <div class="container-fluid d-flex align-items-center">
    <!-- Logo + Nama Klinik + Waktu -->
    <a class="navbar-brand d-flex align-items-center" href="/portal-berita/admin/admin.php">
      <img src="/portal-berita/assets/img/logo.png" alt="Logo" width="45" height="45" class="me-2">
      <div class="d-flex flex-column justify-content-center" style="line-height: 1.2;">
        <span class="fw-bold mb-0 text-white" style="font-size: 1.3rem;">Klinik Annisa Kutagandok</span>
        <div class="d-flex align-items-center gap-1" style="font-size: 0.8rem;">
          <small class="text-white" id="date"></small>
          <span class="text-white">|</span>
          <small class="text-white" id="time"></small>
        </div>
      </div>
    </a>

    <!-- Tombol toggle (mobile) -->
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
      <span class="navbar-toggler-icon"></span>
    </button>

    <!-- Menu Navigasi -->
    <?php
// Dapatkan nama file saat ini
$currentPage = basename($_SERVER['PHP_SELF']);
?>

<div class="collapse navbar-collapse" id="navbarNav">
  <ul class="navbar-nav ms-auto">
    <li class="nav-item">
      <a class="nav-link <?= $currentPage == 'admin.php' ? 'active' : '' ?>" href="/portal-berita/admin/admin.php">BERANDA</a>
    </li>

        <li class="nav-item">
      <a class="nav-link <?= $currentPage == 'logout.php' ? 'active' : '' ?>" href="/portal-berita/admin/logout.php">LOGOUT</a>
    </li>

  </ul>
</div>

  </div>
</nav>

<!-- Spacer biar konten gak ketutup header -->
<div style="height: 80px;"></div>

</body>
</html>
