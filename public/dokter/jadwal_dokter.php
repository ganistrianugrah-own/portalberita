<?php
include '../../config/koneksi.php';
include '../../assets/header.php'; // Pastikan header hanya navbar, tanpa <html>/<body>

// Pilihan hari dari query string
$filter_hari = $_GET['hari'] ?? '';

// Ambil jadwal dokter
if($filter_hari){
    $stmt = $pdo->prepare("SELECT * FROM jadwal_dokter WHERE hari=? ORDER BY jam_mulai");
    $stmt->execute([$filter_hari]);
} else {
    $stmt = $pdo->query("SELECT * FROM jadwal_dokter ORDER BY FIELD(hari,'Senin','Selasa','Rabu','Kamis','Jumat','Sabtu','Minggu'), jam_mulai");
}
$jadwals = $stmt->fetchAll(PDO::FETCH_ASSOC);

$hari_list = ['Senin','Selasa','Rabu','Kamis','Jumat','Sabtu','Minggu'];
?>

<div class="container my-5 pt-5"> <!-- pt-5 supaya navbar fixed tidak menutupi -->
    <h2 class="mb-4 text-center">Jadwal Dokter</h2>

    <!-- Filter Hari -->
    <div class="mb-4 text-center">
        <form method="GET" class="d-inline-flex gap-2 align-items-center">
            <label for="hari">Pilih Hari:</label>
            <select name="hari" id="hari" class="form-select" style="width:auto;">
                <option value="">Semua Hari</option>
                <?php foreach($hari_list as $h): ?>
                    <option value="<?= $h ?>" <?= $filter_hari==$h?'selected':'' ?>><?= $h ?></option>
                <?php endforeach; ?>
            </select>
            <button class="btn btn-success">Tampilkan</button>
        </form>
    </div>

    <?php if(count($jadwals) > 0): ?>
    <div class="table-responsive">
        <table class="table table-bordered table-striped align-middle text-center">
            <thead class="table-success">
                <tr>
                    <th>Foto</th>
                    <th>Nama Dokter</th>
                    <th>Spesialisasi</th>
                    <th>Hari</th>
                    <th>Jam Praktik</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($jadwals as $j): ?>
                <tr>
                    <td>
                        <?php 
                        $fotoPath = "../../assets/img/dokter/".$j['foto_dokter'];
                        if(!empty($j['foto_dokter']) && file_exists($fotoPath)): ?>
                            <img src="<?= $fotoPath ?>" alt="<?= $j['nama_dokter'] ?>" width="60" class="rounded-circle">
                        <?php else: ?>
                            <img src="../../assets/img/default.png" alt="No Foto" width="60" class="rounded-circle">
                        <?php endif; ?>
                    </td>
                    <td><?= htmlspecialchars($j['nama_dokter']) ?></td>
                    <td><?= htmlspecialchars($j['spesialisasi']) ?></td>
                    <td><?= $j['hari'] ?></td>
                    <td><?= date('H:i', strtotime($j['jam_mulai'])) ?> - <?= date('H:i', strtotime($j['jam_selesai'])) ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <?php else: ?>
        <div class="alert alert-info text-center">Belum ada jadwal dokter tersedia.</div>
    <?php endif; ?>
</div>

<?php include '../../assets/wa.php'; ?>

<?php include '../../assets/footer.php'; ?>