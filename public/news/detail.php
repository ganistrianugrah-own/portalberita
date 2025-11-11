<?php
include '../../assets/header.php';
include '../../config/koneksi.php'; // file koneksi database

// Ambil ID dari URL
$id = $_GET['id'] ?? 0;

// Ambil berita berdasarkan ID
$stmt = $pdo->prepare("SELECT * FROM berita WHERE id = ?");
$stmt->execute([$id]);
$berita = $stmt->fetch(PDO::FETCH_ASSOC);

// Jika berita tidak ditemukan, redirect ke index
if (!$berita) {
    header("Location: index.php");
    exit;
}
?>

<!-- HERO / Judul Berita -->
<section class="py-5 text-white" style="background: linear-gradient(90deg,#009970,#00b894);">
  <div class="container text-center">
    <h1 class="display-5 fw-bold"><?= htmlspecialchars($berita['judul']) ?></h1>
    <p class="lead"><?= date("d M Y", strtotime($berita['tanggal'])) ?></p>
  </div>
</section>

<!-- Konten Berita -->
<section class="py-5">
  <div class="container">
    <?php if (!empty($berita['gambar'])): ?>
      <img src="../../assets/img/berita/<?= htmlspecialchars($berita['gambar']) ?>" class="img-fluid mb-4" alt="<?= htmlspecialchars($berita['judul']) ?>">
    <?php endif; ?>

    <div class="mb-4">
      <?= nl2br(htmlspecialchars($berita['isi'])) ?>
    </div>

    <?php if (!empty($berita['lampiran'])): ?>
      <div class="mt-3">
        <a href="../../assets/lampiran/<?= htmlspecialchars($berita['lampiran']) ?>" class="btn btn-success" target="_blank">Download Lampiran</a>
      </div>
    <?php endif; ?>

    <a href="index.php" class="btn btn-secondary mt-4">Kembali ke Beranda</a>
  </div>
</section>

<?php include '../../assets/wa.php'; ?>

<?php include '../../assets/footer.php'; ?>