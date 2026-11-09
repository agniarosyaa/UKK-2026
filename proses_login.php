
<?php
session_start();

include "config/koneksi.php";

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: login.php");
    exit;
}

$email = trim($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';

if ($email === '' || $password === '') {
    $_SESSION['error'] = "Email dan password wajib diisi.";
    header("Location: login.php");
    exit;
}

$query = "SELECT * FROM t_users WHERE email = ? LIMIT 1";
$stmt = mysqli_prepare($koneksi, $query);

if (!$stmt) {
    die("Query gagal: " . mysqli_error($koneksi));
}

mysqli_stmt_bind_param($stmt, "s", $email);
mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);
$user = mysqli_fetch_assoc($result);
mysqli_stmt_close($stmt);

if (!$user) {
    $_SESSION['error'] = "Email atau password salah.";
    header("Location: login.php");
    exit;
}

$password_db = $user['password'] ?? '';

$info_hash = password_get_info($password_db);

if ($info_hash['algo'] !== null) {
    $password_valid = password_verify($password, $password_db);
} else {
    $password_valid = hash_equals($password_db, $password);
}

if (!$password_valid) {
    $_SESSION['error'] = "Email atau password salah.";
    header("Location: login.php");
    exit;
}

$role = strtolower(trim($user['role'] ?? ''));

$role_diizinkan = ['admin', 'guru', 'wali_kelas'];

if (!in_array($role, $role_diizinkan, true)) {
    $_SESSION['error'] = "Role tidak memiliki akses.";
    header("Location: login.php");
    exit;
}

// Ubah password teks biasa menjadi hash
if ($info_hash['algo'] === null) {
    $hash_baru = password_hash($password, PASSWORD_DEFAULT);

    $update = mysqli_prepare(
        $koneksi,
        "UPDATE t_users SET password = ? WHERE id = ?"
    );

    if ($update) {
        mysqli_stmt_bind_param(
            $update,
            "si",
            $hash_baru,
            $user['id']
        );

        mysqli_stmt_execute($update);
        mysqli_stmt_close($update);
    }
}

session_regenerate_id(true);

$_SESSION['login'] = true;
$_SESSION['user_id'] = $user['id'];
$_SESSION['id_user'] = $user['id'];
$_SESSION['role'] = $role;
$_SESSION['email'] = $user['email'];

header("Location: dashboard.php");
exit;
