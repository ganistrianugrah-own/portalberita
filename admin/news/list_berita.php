<?php
include '../../config/koneksi.php'; // koneksi database
include '../../admin/header_admin.php'; // Pastikan header hanya navbar, tanpa <html>/<body>

// Ambil pesan notifikasi dari query string (jika ada)
$msg = $_GET['msg'] ?? '';

// Hapus berita jika ada parameter delete
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];

    // Ambil nama file gambar & lampiran sebelum hapus
    $stmt = $pdo->prepare("SELECT gambar, lampiran FROM berita WHERE id = ?");
    $stmt->execute([$id]);
    $berita = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($berita) {
        // Hapus file gambar
        if ($berita['gambar'] && file_exists("../../assets/img/berita/".$berita['gambar'])) {
            unlink("../../assets/img/berita/".$berita['gambar']);
        }
        // Hapus file lampiran
        if ($berita['lampiran'] && file_exists("../../assets/lampiran/".$berita['lampiran'])) {
            unlink("../../assets/lampiran/".$berita['lampiran']);
        }

        // Hapus data dari database
        $stmt = $pdo->prepare("DELETE FROM berita WHERE id = ?");
        $stmt->execute([$id]);

        // Redirect ke halaman list dengan notifikasi
        header("Location: list_berita.php?msg=Berita berhasil dihapus");
        exit;
    }
}

// Ambil semua berita
$stmt = $pdo->query("SELECT * FROM berita ORDER BY tanggal DESC");
$beritaList = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Daftar Berita - Admin</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container py-5">
    <h2 class="mb-4">Daftar Berita</h2>

    <!-- Notifikasi -->
    <?php if($msg): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?= htmlspecialchars($msg) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <a href="upload_berita.php" class="btn btn-success mb-3">Tambah Berita Baru</a>

    <table class="table table-bordered table-striped">
        <thead class="table-dark">
            <tr>
                <th>#</th>
                <th>Judul</th>
                <th>Ringkasan</th>
                <th>Gambar</th>
                <th>Lampiran</th>
                <th>Tanggal</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($beritaList as $i => $b): ?>
            <tr>
                <td><?= $i+1 ?></td>
                <td><?= htmlspecialchars($b['judul']) ?></td>
                <td><?= htmlspecialchars($b['ringkasan']) ?></td>
                <td>
                    <?php if ($b['gambar']): ?>
                        <img src="../../assets/img/berita/<?= $b['gambar'] ?>" alt="Gambar" width="60">
                    <?php endif; ?>
                </td>
                <td>
                    <?php if ($b['lampiran']): ?>
                        <a href="../../assets/lampiran/<?= $b['lampiran'] ?>" target="_blank">Download</a>
                    <?php endif; ?>
                </td>
                <td><?= date("d M Y H:i", strtotime($b['tanggal'])) ?></td>
                <td>
                    <a href="edit_berita.php?id=<?= $b['id'] ?>" class="btn btn-sm btn-primary">Edit</a>
                    <a href="list_berita.php?delete=<?= $b['id'] ?>" class="btn btn-sm btn-danger"
                       onclick="return confirm('Yakin ingin hapus berita ini?')">Hapus</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
