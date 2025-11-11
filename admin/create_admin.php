<?php
include '../config/koneksi.php';

$username = 'admin';          // ubah sesuai keinginan
$passwordPlain = 'admin123';  // ubah sesuai keinginan

$hash = password_hash($passwordPlain, PASSWORD_DEFAULT);

$stmt = $pdo->prepare("INSERT INTO admin (username, password) VALUES (?, ?)");
try {
    $stmt->execute([$username, $hash]);
    echo "✅ Admin berhasil dibuat!<br>";
    echo "Username: <b>$username</b><br>Password: <b>$passwordPlain</b><br>";
    echo "<br><b>⚠️ HAPUS file create_admin.php setelah ini!</b>";
} catch (PDOException $e) {
    echo "❌ Gagal: " . $e->getMessage();
}
