<?php
include '../assets/header.php';
include '../config/koneksi.php'; // file koneksi database

// Ambil 3 berita terbaru
$stmt = $pdo->query("SELECT * FROM berita ORDER BY tanggal DESC LIMIT 3");
$beritaList = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!-- HERO / Jumbotron -->
<section class="py-5 text-white" style="background: linear-gradient(90deg,#009970,#00b894);">
  <div class="container text-center">
    <h1 class="display-5 fw-bold">Selamat Datang di Website Klinik Annisa</h1>
    <p class="lead">Temukan berita terbaru, informasi klinik, dan kontak kami di sini.</p>
  </div>
</section>

<!-- Rangkuman Berita / News -->
<section class="py-5 bg-light">
  <div class="container">
    <h2 class="mb-4">Berita Terbaru</h2>
    <div class="row g-4">
      <?php foreach ($beritaList as $berita): ?>
        <div class="col-md-4">
          <div class="card h-100 shadow-sm">
            <img src="../assets/img/berita/<?= htmlspecialchars($berita['gambar']) ?>" class="card-img-top" alt="<?= htmlspecialchars($berita['judul']) ?>">
            <div class="card-body">
              <h5 class="card-title"><?= htmlspecialchars($berita['judul']) ?></h5>
              <p class="card-text"><?= htmlspecialchars($berita['ringkasan']) ?></p>
              <a href="news/detail.php?id=<?= $berita['id'] ?>" class="btn btn-success btn-sm">Baca Selengkapnya</a>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- Tentang -->
<section class="py-5">
  <div class="container">
    <h2 class="mb-4">Tentang Kami</h2>
    <p>Klinik Annisa Kutagandok menyediakan layanan kesehatan berkualitas untuk seluruh masyarakat. Kami berkomitmen untuk memberikan informasi terbaru seputar klinik, kesehatan, dan kegiatan kami melalui portal ini.</p>
    <a href="#" class="btn btn-success">Selengkapnya</a>
  </div>
</section>

<!-- Kontak -->
<section class="py-5 bg-light">
  <div class="container">
    <h2 class="mb-4">Kontak Kami</h2>
    <div class="row">
      <div class="col-md-4 mb-3">
        <div class="card p-3 h-100">
          <h5>Alamat</h5>
          <p>Jl. Contoh No.123, Kutagandok</p>
        </div>
      </div>
      <div class="col-md-4 mb-3">
        <div class="card p-3 h-100">
          <h5>Email</h5>
          <p>info@klinikannisa.com</p>
        </div>
      </div>
      <div class="col-md-4 mb-3">
        <div class="card p-3 h-100">
          <h5>Telepon / WA</h5>
          <p>+62 812 3456 7890</p>
        </div>
      </div>
    </div>
    <a href="#" class="btn btn-success mt-3">Hubungi Kami</a>
  </div>
</section>


<?php include '../assets/wa.php'; ?>

<?php include '../assets/footer.php'; ?>
