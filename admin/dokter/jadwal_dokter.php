<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: ../login.php?msg=error&text=Silakan login terlebih dahulu!");
    exit;
}

include '../../config/koneksi.php';
include '../../admin/header_admin.php'; // Pastikan header hanya navbar, tanpa <html>/<body>

// Ambil filter pencarian
$hariFilter = $_GET['hari'] ?? '';

// Query data dokter
if ($hariFilter) {
    $stmt = $pdo->prepare("SELECT * FROM jadwal_dokter WHERE hari = ? ORDER BY id DESC");
    $stmt->execute([$hariFilter]);
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
} else {
    $data = $pdo->query("SELECT * FROM jadwal_dokter ORDER BY id DESC")->fetchAll(PDO::FETCH_ASSOC);
}

// Daftar hari
$hariList = ["Senin","Selasa","Rabu","Kamis","Jumat","Sabtu","Minggu"];
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Jadwal Dokter</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body class="bg-light">
<div class="container py-5">
    <div class="d-flex justify-content-between mb-3 align-items-center">
        <h2>Jadwal Dokter</h2>
        <a href="tambah_jadwal_dokter.php" class="btn btn-success">+ Tambah Dokter</a>
    </div>

    <!-- 🔍 Filter Hari -->
    <form method="GET" class="row g-2 mb-4">
        <div class="col-md-3">
            <select name="hari" class="form-select">
                <option value="">Semua Hari</option>
                <?php foreach ($hariList as $h): ?>
                    <option value="<?= $h ?>" <?= ($hariFilter == $h) ? 'selected' : '' ?>><?= $h ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col-md-2">
            <button type="submit" class="btn btn-primary w-100">Cari</button>
        </div>
        <?php if ($hariFilter): ?>
        <div class="col-md-2">
            <a href="jadwal_dokter.php" class="btn btn-secondary w-100">Reset</a>
        </div>
        <?php endif; ?>
    </form>

    <table class="table table-bordered table-hover bg-white shadow-sm">
        <thead class="table-success text-center">
            <tr>
                <th>No</th>
                <th>Foto</th>
                <th>Nama Dokter</th>
                <th>Spesialisasi</th>
                <th>Hari</th>
                <th>Jam</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($data): ?>
                <?php $no=1; foreach($data as $d): ?>
                <tr>
                    <td class="text-center"><?= $no++ ?></td>
                    <td class="text-center">
                        <?php if($d['foto_dokter']): ?>
                            <img src="../../assets/img/dokter/<?= htmlspecialchars($d['foto_dokter']) ?>" width="60" class="rounded">
                        <?php endif; ?>
                    </td>
                    <td><?= htmlspecialchars($d['nama_dokter']) ?></td>
                    <td><?= htmlspecialchars($d['spesialisasi']) ?></td>
                    <td><?= htmlspecialchars($d['hari']) ?></td>
                    <td><?= $d['jam_mulai'] ?> - <?= $d['jam_selesai'] ?></td>
                    <td class="text-center">
                        <a href="edit_jadwal_dokter.php?id=<?= $d['id'] ?>" class="btn btn-warning btn-sm">Edit</a>
                        <button type="button" class="btn btn-danger btn-sm" onclick="confirmHapus(<?= $d['id'] ?>)">Hapus</button>
                    </td>
                </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="7" class="text-center text-muted">Tidak ada data dokter untuk hari ini.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<!-- SweetAlert Notifikasi -->
<script>
function confirmHapus(id) {
    Swal.fire({
        title: 'Yakin ingin menghapus?',
        text: 'Data ini akan dihapus permanen!',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Ya, hapus',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = 'hapus_jadwal_dokter.php?id=' + id;
        }
    });
}

document.addEventListener("DOMContentLoaded", function() {
    const url = new URLSearchParams(window.location.search);
    const msg = url.get('msg');
    const text = url.get('text');

    if (msg === 'deleted') {
        Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: text || 'Data dokter berhasil dihapus!',
            timer: 2000,
            showConfirmButton: false
        }).then(() => {
            window.location.href = 'jadwal_dokter.php';
        });
    } else if (msg === 'success') {
        Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: text || 'Data berhasil disimpan!',
            timer: 2000,
            showConfirmButton: false
        });
    } else if (msg === 'error') {
        Swal.fire({
            icon: 'error',
            title: 'Gagal!',
            text: text || 'Terjadi kesalahan!',
            confirmButtonColor: '#d33'
        });
    }
});
</script>
</body>
</html>
