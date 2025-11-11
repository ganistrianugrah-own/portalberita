<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: ../login.php?msg=error&text=Silakan login terlebih dahulu!");
    exit;
}
include '../../config/koneksi.php';
include '../../admin/header_admin.php';

// Ambil data berita berdasarkan ID
$id = $_GET['id'] ?? 0;
$stmt = $pdo->prepare("SELECT * FROM berita WHERE id = ?");
$stmt->execute([$id]);
$berita = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$berita) {
    die("Berita tidak ditemukan!");
}

// Update data
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $judul = $_POST['judul'] ?? '';
    $ringkasan = $_POST['ringkasan'] ?? '';
    $isi = $_POST['isi'] ?? '';

    // --- Upload gambar baru (jika ada)
    $gambar = $berita['gambar'];
    if (!empty($_FILES['gambar']['name'])) {
        $targetFolder = "../../assets/img/berita/";
        if (!is_dir($targetFolder)) mkdir($targetFolder, 0777, true);

        // Hapus gambar lama
        if (!empty($gambar) && file_exists($targetFolder . $gambar)) {
            unlink($targetFolder . $gambar);
        }

        // Simpan gambar baru
        $gambar = time() . '_' . basename($_FILES['gambar']['name']);
        move_uploaded_file($_FILES['gambar']['tmp_name'], $targetFolder . $gambar);
    }

    // --- Upload lampiran baru (jika ada)
    $lampiran = $berita['lampiran'];
    if (!empty($_FILES['lampiran']['name'])) {
        $targetFolderLampiran = "../../assets/lampiran/";
        if (!is_dir($targetFolderLampiran)) mkdir($targetFolderLampiran, 0777, true);

        // Hapus lampiran lama
        if (!empty($lampiran) && file_exists($targetFolderLampiran . $lampiran)) {
            unlink($targetFolderLampiran . $lampiran);
        }

        // Simpan lampiran baru
        $lampiran = time() . '_' . basename($_FILES['lampiran']['name']);
        move_uploaded_file($_FILES['lampiran']['tmp_name'], $targetFolderLampiran . $lampiran);
    }

    // Update database
    $stmt = $pdo->prepare("UPDATE berita SET judul=?, ringkasan=?, isi=?, gambar=?, lampiran=? WHERE id=?");
    $result = $stmt->execute([$judul, $ringkasan, $isi, $gambar, $lampiran, $id]);

    if ($result) {
        header("Location: edit_berita.php?id=$id&msg=success");
        exit;
    } else {
        header("Location: edit_berita.php?id=$id&msg=error");
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Edit Berita</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body class="bg-light">

<div class="container py-5">
    <h2 class="mb-4">Edit Berita</h2>

    <form method="POST" enctype="multipart/form-data">
        <div class="mb-3">
            <label class="form-label">Judul Berita</label>
            <input type="text" name="judul" class="form-control" value="<?= htmlspecialchars($berita['judul']) ?>" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Ringkasan</label>
            <textarea name="ringkasan" class="form-control" rows="2" required><?= htmlspecialchars($berita['ringkasan']) ?></textarea>
        </div>

        <div class="mb-3">
            <label class="form-label">Isi Berita</label>
            <textarea name="isi" class="form-control" rows="5" required><?= htmlspecialchars($berita['isi']) ?></textarea>
        </div>

        <div class="mb-3">
            <label class="form-label">Gambar Sekarang:</label><br>
            <?php if (!empty($berita['gambar'])): ?>
                <img src="../../assets/img/berita/<?= htmlspecialchars($berita['gambar']) ?>" width="150" class="rounded mb-2">
            <?php else: ?>
                <p><em>Tidak ada gambar</em></p>
            <?php endif; ?>
            <input type="file" name="gambar" class="form-control" accept="image/*">
        </div>

        <div class="mb-3">
            <label class="form-label">Lampiran Sekarang:</label><br>
            <?php if (!empty($berita['lampiran'])): ?>
                <a href="../../assets/lampiran/<?= htmlspecialchars($berita['lampiran']) ?>" target="_blank"><?= htmlspecialchars($berita['lampiran']) ?></a>
            <?php else: ?>
                <p><em>Tidak ada lampiran</em></p>
            <?php endif; ?>
            <input type="file" name="lampiran" class="form-control" accept=".pdf,.doc,.docx,.xlsx">
        </div>

        <button type="submit" class="btn btn-success">Simpan Perubahan</button>
        <a href="list_berita.php" class="btn btn-secondary">Kembali</a>
    </form>
</div>

<script>
<?php if (isset($_GET['msg'])): ?>
    const msg = "<?= $_GET['msg'] ?>";
    if (msg === "success") {
        Swal.fire({
            icon: "success",
            title: "Berhasil!",
            text: "Berita berhasil diperbarui!",
            timer: 2000,
            showConfirmButton: false
        }).then(() => {
            window.location.href = "list_berita.php";
        });
    } else if (msg === "error") {
        Swal.fire({
            icon: "error",
            title: "Gagal!",
            text: "Terjadi kesalahan saat memperbarui berita."
        });
    }
<?php endif; ?>
</script>

</body>
</html>
