<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: ../login.php?msg=error&text=Silakan login terlebih dahulu!");
    exit;
}
include '../../config/koneksi.php';
include '../../admin/header_admin.php';

$status = '';
$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $judul = $_POST['judul'] ?? '';
    $ringkasan = $_POST['ringkasan'] ?? '';
    $isi = $_POST['isi'] ?? '';

    // Upload gambar
    $gambar = '';
    if (!empty($_FILES['gambar']['name'])) {
        $gambar = time() . '_' . basename($_FILES['gambar']['name']);
        $targetFolder = "../../assets/img/berita/";
        if (!is_dir($targetFolder)) mkdir($targetFolder, 0777, true);
        if (!move_uploaded_file($_FILES['gambar']['tmp_name'], $targetFolder . $gambar)) {
            $status = 'error';
            $message = 'Gagal mengunggah gambar!';
        }
    }

    // Upload lampiran
    $lampiran = '';
    if (!empty($_FILES['lampiran']['name'])) {
        $lampiran = time() . '_' . basename($_FILES['lampiran']['name']);
        $targetFolderLampiran = "../../assets/lampiran/";
        if (!is_dir($targetFolderLampiran)) mkdir($targetFolderLampiran, 0777, true);
        if (!move_uploaded_file($_FILES['lampiran']['tmp_name'], $targetFolderLampiran . $lampiran)) {
            $status = 'error';
            $message = 'Gagal mengunggah lampiran!';
        }
    }

    // Simpan ke database
    if (!$status) {
        $stmt = $pdo->prepare("INSERT INTO berita (judul, ringkasan, isi, gambar, lampiran) VALUES (?, ?, ?, ?, ?)");
        $result = $stmt->execute([$judul, $ringkasan, $isi, $gambar, $lampiran]);
        if ($result) {
            $status = 'success';
            $message = 'Berita berhasil diupload!';
        } else {
            $status = 'error';
            $message = 'Terjadi kesalahan saat upload berita.';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Upload Berita - Admin</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body class="bg-light">

<div class="container py-5">
    <h2 class="mb-4">Upload Berita Baru</h2>

    <form method="POST" enctype="multipart/form-data">
        <div class="mb-3">
            <label class="form-label">Judul Berita</label>
            <input type="text" name="judul" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Ringkasan</label>
            <textarea name="ringkasan" class="form-control" rows="2" required></textarea>
        </div>
        <div class="mb-3">
            <label class="form-label">Isi Berita</label>
            <textarea name="isi" class="form-control" rows="5" required></textarea>
        </div>
        <div class="mb-3">
            <label class="form-label">Gambar (opsional)</label>
            <input type="file" name="gambar" class="form-control" accept="image/*">
        </div>
        <div class="mb-3">
            <label class="form-label">Lampiran (opsional)</label>
            <input type="file" name="lampiran" class="form-control" accept=".pdf,.doc,.docx,.xlsx">
        </div>
        <button type="submit" class="btn btn-success">Upload Berita</button>
    </form>

    <hr class="my-5">
    <a href="list_berita.php" class="btn btn-primary">Lihat Semua Berita</a>
</div>

<?php if ($status): ?>
<script>
Swal.fire({
    icon: '<?= $status ?>',
    title: '<?= $status === "success" ? "Berhasil!" : "Gagal!" ?>',
    text: '<?= $message ?>',
    showConfirmButton: false,
    timer: 2000
}).then(() => {
    if ('<?= $status ?>' === 'success') {
        window.location.href = 'list_berita.php';
    }
});
</script>
<?php endif; ?>

</body>
</html>
