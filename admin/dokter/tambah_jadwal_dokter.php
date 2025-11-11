<?php
include '../../config/koneksi.php';
include '../../admin/header_admin.php';

$msg = $_GET['msg'] ?? '';
$text = $_GET['text'] ?? '';

if(isset($_POST['submit'])){
    $nama_dokter = $_POST['nama_dokter'];
    $spesialisasi = $_POST['spesialisasi'];
    $hari = $_POST['hari'];
    $jam_mulai = $_POST['jam_mulai'];
    $jam_selesai = $_POST['jam_selesai'];

    // Upload foto
    $foto_dokter = '';
    if(isset($_FILES['foto_dokter']) && $_FILES['foto_dokter']['name'] != ''){
        $folder = "../../assets/img/dokter/";
        if(!is_dir($folder)) mkdir($folder, 0777, true);

        $filename = time().'_'.basename($_FILES['foto_dokter']['name']);
        $target = $folder.$filename;

        if(move_uploaded_file($_FILES['foto_dokter']['tmp_name'], $target)){
            $foto_dokter = $filename;
        } else {
            header("Location: tambah_jadwal_dokter.php?msg=error&text=Gagal upload gambar!");
            exit;
        }
    }

    // Simpan ke database
    $stmt = $pdo->prepare("INSERT INTO jadwal_dokter (nama_dokter, spesialisasi, hari, jam_mulai, jam_selesai, foto_dokter) VALUES (?,?,?,?,?,?)");
    $result = $stmt->execute([$nama_dokter, $spesialisasi, $hari, $jam_mulai, $jam_selesai, $foto_dokter]);

    if($result){
        header("Location: tambah_jadwal_dokter.php?msg=success&text=Jadwal dokter berhasil ditambahkan!");
        exit;
    } else {
        header("Location: tambah_jadwal_dokter.php?msg=error&text=Gagal menambahkan jadwal dokter!");
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Tambah Jadwal Dokter</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<!-- SweetAlert -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
<div class="container my-5">
    <h2 class="mb-4 text-center">Tambah Jadwal Dokter</h2>

    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card border-success shadow-sm">
                <div class="card-body">
                    <form method="POST" enctype="multipart/form-data">
                        <div class="mb-3">
                            <label class="form-label">Nama Dokter</label>
                            <input type="text" class="form-control" name="nama_dokter" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Spesialisasi</label>
                            <input type="text" class="form-control" name="spesialisasi" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Hari</label>
                            <select class="form-select" name="hari" required>
                                <option value="">Pilih Hari</option>
                                <?php 
                                $hariList = ["Senin","Selasa","Rabu","Kamis","Jumat","Sabtu","Minggu"];
                                foreach($hariList as $h) echo "<option>$h</option>";
                                ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Jam Praktik</label>
                            <div class="d-flex gap-2">
                                <input type="time" class="form-control" name="jam_mulai" required>
                                <span class="align-self-center">s/d</span>
                                <input type="time" class="form-control" name="jam_selesai" required>
                            </div>
                        </div>
                        <div class="mb-3 text-center">
                            <label class="form-label">Foto Dokter</label>
                            <input type="file" class="form-control" name="foto_dokter" id="foto_dokter" accept="image/*">
                            <div class="mt-3">
                                <img id="preview" src="" alt="Preview Foto" class="img-fluid rounded shadow-sm" style="max-width: 200px; display:none;">
                            </div>
                        </div>
                        <button type="submit" name="submit" class="btn btn-success w-100">Simpan Jadwal</button>
                    </form>
                </div>
            </div>
            <div class="mt-3 text-center">
                <a href="jadwal_dokter.php" class="btn btn-outline-secondary">Kembali ke Jadwal Dokter</a>
            </div>
        </div>
    </div>
</div>

<script>
// === Preview Otomatis Foto ===
document.getElementById('foto_dokter').addEventListener('change', function(e){
    const file = e.target.files[0];
    const preview = document.getElementById('preview');
    if(file){
        const reader = new FileReader();
        reader.onload = function(ev){
            preview.src = ev.target.result;
            preview.style.display = 'block';
        }
        reader.readAsDataURL(file);
    } else {
        preview.style.display = 'none';
    }
});

// === SweetAlert untuk notifikasi ===
document.addEventListener("DOMContentLoaded", () => {
    const msg = "<?= $msg ?>";
    const text = "<?= addslashes($text) ?>";

    if(msg === "success"){
        Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: text,
            confirmButtonColor: '#198754',
        }).then(() => {
            window.location.href = 'jadwal_dokter.php';
        });
    } else if(msg === "error"){
        Swal.fire({
            icon: 'error',
            title: 'Gagal!',
            text: text,
            confirmButtonColor: '#d33',
        });
    }
});
</script>
</body>
</html>
