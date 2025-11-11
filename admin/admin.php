<?php
include '../config/koneksi.php';
include 'header_admin.php'; // Pastikan header hanya navbar, tanpa <html>/<body>
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Pilih Menu | Klinik Anisa Kutagandok</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light d-flex justify-content-center align-items-center vh-100">

    <div class="container text-center">
        <h2 class="mb-5 text-success fw-bold">Website Klinik Anisa Kutagandok</h2>
        <div class="row justify-content-center g-4">
            <div class="col-10 col-md-4">
                <a href="../admin/dokter/jadwal_dokter.php" class="text-decoration-none">
                    <div class="p-5 bg-success text-white rounded-4 shadow-lg menu-box">
                        <h3 class="fw-bold mb-2">UPDATE JADWAL DOKTER</h3>
                        <p class="mb-0">Masuk ke panel dokter</p>
                    </div>
                </a>
            </div>
            <div class="col-10 col-md-4">
                <a href="../admin/news/list_berita.php" class="text-decoration-none">
                    <div class="p-5 bg-primary text-white rounded-4 shadow-lg menu-box">
                        <h3 class="fw-bold mb-2">UPDATE WEB BERITA</h3>
                        <p class="mb-0">Masuk ke panel berita</p>
                    </div>
                </a>
            </div>
        </div>
    </div>

<style>
    .menu-box {
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    .menu-box:hover {
        transform: translateY(-5px);
        box-shadow: 0 0 20px rgba(0,0,0,0.2);
    }
</style>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
