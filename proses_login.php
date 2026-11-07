
<?php
session_start();

include "config/koneksi.php";

// CEK REQUEST POST
if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    header("Location: login.php");
    exit;
}

// AMBIL DATA LOGIN
$email = trim($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';

// VALIDASI INPUT
if ($email == '' || $password == '') {
    $_SESSION['error'] = "Email dan password harus diisi.";
    header("Location: login.php");
    exit;
}

// CARI DATA USER
$stmt = mysqli_prepare(
    $koneksi,
    "SELECT * FROM t_users WHERE email = ? LIMIT 1"
);

if (!$stmt) {
    die("Query login gagal: " . mysqli_error($koneksi));
}

mysqli_stmt_bind_param($stmt, "s", $email);
mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);
$user = mysqli_fetch_assoc($result);

mysqli_stmt_close($stmt);

// CEK USER
if (!$user) {
    $_SESSION['error'] = "Email atau password salah.";
    header("Location: login.php");
    exit;
}

// VALIDASI PASSWORD
$password_db = $user['password'] ?? '';

$password_cocok = password_verify($password, $password_db);

// PASSWORD LAMA (PLAINTEXT)
if (!$password_cocok && hash_equals((string) $password_db, $password)) {
    $password_cocok = true;
}

if (!$password_cocok) {
    $_SESSION['error'] = "Email atau password salah.";
    header("Location: login.php");
    exit;
}

// AMBIL ROLE USER
$role = strtolower(trim($user['role'] ?? ''));

// IZINKAN ADMIN DAN GURU SAJA
if (!in_array($role, ['admin', 'guru'], true)) {
    $_SESSION['error'] = "Role pengguna tidak valid.";
    header("Location: login.php");
    exit;
}

// BUAT SESSION BARU
session_regenerate_id(true);

$_SESSION['login'] = true;
$_SESSION['role'] = $role;
$_SESSION['user_id'] = $user['id'] ?? 0;
$_SESSION['nama'] = $user['nama'] ?? $user['name'] ?? $email;
$_SESSION['name'] = $_SESSION['nama'];
$_SESSION['email'] = $email;

// ARAHKAN KE DASHBOARD
header("Location: dashboard.php");
exit;
