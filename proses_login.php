
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

// Cari akun berdasarkan email
$sql = "SELECT * FROM t_users WHERE email = ? LIMIT 1";
$stmt = mysqli_prepare($koneksi, $sql);

if (!$stmt) {
    die("Kesalahan query: " . mysqli_error($koneksi));
}

mysqli_stmt_bind_param($stmt, "s", $email);
mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);
$user = mysqli_fetch_assoc($result);
mysqli_stmt_close($stmt);

if (!$user) {
    $_SESSION['error'] = "Email tidak ditemukan.";
    header("Location: login.php");
    exit;
}

// Verifikasi password
$password_db = $user['password'];

$info_hash = password_get_info($password_db);

if ($info_hash['algo'] !== null) {
    $password_valid = password_verify($password, $password_db);
} else {
    $password_valid = hash_equals($password_db, $password);
}

if (!$password_valid) {
    $_SESSION['error'] = "Password yang dimasukkan salah.";
    header("Location: login.php");
    exit;
}

// Ambil role asli
$role_asli = strtolower(trim($user['role']));

// Role admin tidak boleh berubah
if ($role_asli === 'admin') {
    $role_final = 'admin';

} elseif ($role_asli === 'guru' || $role_asli === 'wali_kelas') {

    // Cari data guru berdasarkan user_id
    $sql_guru = "
        SELECT id
        FROM t_guru
        WHERE user_id = ?
        AND status_aktif = 1
        LIMIT 1
    ";

    $stmt_guru = mysqli_prepare($koneksi, $sql_guru);
    mysqli_stmt_bind_param($stmt_guru, "i", $user['id']);
    mysqli_stmt_execute($stmt_guru);

    $result_guru = mysqli_stmt_get_result($stmt_guru);
    $guru = mysqli_fetch_assoc($result_guru);
    mysqli_stmt_close($stmt_guru);

    if (!$guru) {
        $_SESSION['error'] = "Data guru tidak ditemukan atau tidak aktif.";
        header("Location: login.php");
        exit;
    }

    // Cek penugasan wali kelas aktif
    $sql_wali = "
        SELECT id
        FROM t_wali_kelas
        WHERE guru_id = ?
        AND status_aktif = 1
        AND tanggal_mulai <= CURDATE()
        AND tanggal_selesai >= CURDATE()
        LIMIT 1
    ";

    $stmt_wali = mysqli_prepare($koneksi, $sql_wali);
    mysqli_stmt_bind_param($stmt_wali, "i", $guru['id']);
    mysqli_stmt_execute($stmt_wali);

    $result_wali = mysqli_stmt_get_result($stmt_wali);
    $wali = mysqli_fetch_assoc($result_wali);
    mysqli_stmt_close($stmt_wali);

    if ($wali) {
        $role_final = 'wali_kelas';
    } else {
        $role_final = 'guru';
    }

} else {
    $_SESSION['error'] = "Role akun tidak memiliki akses.";
    header("Location: login.php");
    exit;
}

// Upgrade password teks biasa menjadi hash
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

// Buat session login
session_regenerate_id(true);

$_SESSION['login'] = true;
$_SESSION['user_id'] = $user['id'];
$_SESSION['id_user'] = $user['id'];
$_SESSION['role'] = $role_final;
$_SESSION['email'] = $user['email'];
$_SESSION['nama'] = $user['nama'];

// Arahkan ke dashboard
header("Location: dashboard.php");
exit;
