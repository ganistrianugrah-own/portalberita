<?php
session_start();
include '../config/koneksi.php';

if (isset($_SESSION['admin'])) {
    header("Location: admin.php");
    exit;
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if ($username && $password) {
        $stmt = $pdo->prepare("SELECT * FROM admin WHERE username = ?");
        $stmt->execute([$username]);
        $admin = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($admin && password_verify($password, $admin['password'])) {
            $_SESSION['admin'] = $admin['username'];
            header("Location: admin.php?msg=success&text=Berhasil login!");
            exit;
        } else {
            $error = 'Username atau password salah!';
        }
    } else {
        $error = 'Harap isi username dan password!';
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="utf-8">
<title>Login Admin</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body class="bg-light d-flex flex-column justify-content-center align-items-center" style="height:100vh;">

    <!-- 🩺 Headline Tambahan -->
    <h2 class="text-success text-center mb-4 fw-bold">
        Website Klinik Anisa Kutagandok
    </h2>
<div class="card shadow p-4" style="width: 360px;">
    <h4 class="text-center mb-3">Login Admin</h4>
    <form method="POST">
        <div class="mb-3">
            <label class="form-label">Username</label>
            <input type="text" name="username" class="form-control" required autofocus>
        </div>
        <div class="mb-3">
            <label class="form-label">Password</label>
            <input type="password" name="password" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-success w-100">Login</button>
    </form>
</div>

<?php if ($error): ?>
<script>
Swal.fire({
  icon: 'error',
  title: 'Login Gagal',
  text: <?= json_encode($error) ?>,
  confirmButtonColor: '#d33'
});
</script>
<?php endif; ?>
</body>
</html>
