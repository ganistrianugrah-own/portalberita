<?php
include '../../config/koneksi.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Ambil foto dokter untuk dihapus
    $stmt = $pdo->prepare("SELECT foto_dokter FROM jadwal_dokter WHERE id = ?");
    $stmt->execute([$id]);
    $data = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($data) {
        // Hapus data
        $hapus = $pdo->prepare("DELETE FROM jadwal_dokter WHERE id = ?");
        $hapus->execute([$id]);

        // Hapus file foto
        if (!empty($data['foto_dokter'])) {
            $path = "../../assets/img/dokter/" . $data['foto_dokter'];
            if (file_exists($path)) unlink($path);
        }

        header("Location: jadwal_dokter.php?msg=deleted&text=Data dokter berhasil dihapus!");
        exit;
    } else {
        header("Location: jadwal_dokter.php?msg=error&text=Data dokter tidak ditemukan!");
        exit;
    }
} else {
    header("Location: jadwal_dokter.php?msg=error&text=Permintaan tidak valid!");
    exit;
}
?>
