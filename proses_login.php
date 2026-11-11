<?php
session_start();
include "config/koneksi.php";

// ==========================================
// CEK REQUEST
// ==========================================
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

// ==========================================
// CARI AKUN
// ==========================================
$sql = "SELECT * FROM t_users WHERE email = ? LIMIT 1";
$stmt = mysqli_prepare($koneksi, $sql);

if (!$stmt) {
    die("Query akun gagal: " . mysqli_error($koneksi));
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

// ==========================================
// VERIFIKASI PASSWORD
// ==========================================
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

// ==========================================
// ROLE ASLI USER
// ==========================================
$role = strtolower(trim($user['role'] ?? ''));

// HANYA ADMIN DAN GURU
if (!in_array($role, ['admin', 'guru'], true)) {
    $_SESSION['error'] = "Role akun tidak memiliki akses.";
    header("Location: login.php");
    exit;
}

// ==========================================
// CEK DATA GURU
// ==========================================
$guru_id = 0;
$is_wali_kelas = false;
$nama_login = $user['nama'] ?? '';

// Hanya akun GURU yang dicek ke t_guru
if ($role === 'guru') {

    $sql_guru = "
        SELECT id, nama
        FROM t_guru
        WHERE user_id = ?
          AND status_aktif = 1
        LIMIT 1
    ";

    $stmt_guru = mysqli_prepare($koneksi, $sql_guru);

    if (!$stmt_guru) {
        die("Query guru gagal: " . mysqli_error($koneksi));
    }

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

    $guru_id = (int)$guru['id'];
    $nama_login = $guru['nama'];

    // ======================================
    // CEK PENUGASAN WALI KELAS
    // ======================================
    $sql_wali = "
        SELECT wk.id
        FROM t_wali_kelas wk
        INNER JOIN t_tahun_ajaran ta
            ON ta.id = wk.tahun_ajaran_id
        WHERE wk.guru_id = ?
          AND wk.status_aktif = 1
          AND ta.status_aktif = 1
          AND CURDATE() BETWEEN wk.tanggal_mulai
                            AND wk.tanggal_selesai
        LIMIT 1
    ";

    $stmt_wali = mysqli_prepare($koneksi, $sql_wali);

    if (!$stmt_wali) {
        die("Query wali kelas gagal: " . mysqli_error($koneksi));
    }

    mysqli_stmt_bind_param($stmt_wali, "i", $guru_id);
    mysqli_stmt_execute($stmt_wali);

    $result_wali = mysqli_stmt_get_result($stmt_wali);
    $wali = mysqli_fetch_assoc($result_wali);

    mysqli_stmt_close($stmt_wali);

    // Guru tetap role guru.
    // Jika memiliki penugasan wali kelas aktif,
    // maka is_wali_kelas menjadi true.
    $is_wali_kelas = ($wali !== null);
}

// ==========================================
// UPGRADE PASSWORD LAMA
// ==========================================
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

// ==========================================
// BUAT SESSION
// ==========================================
session_regenerate_id(true);

$_SESSION['login'] = true;

$_SESSION['user_id'] = (int)$user['id'];
$_SESSION['id_user'] = (int)$user['id'];

$_SESSION['role'] = $role;
$_SESSION['role_asli'] = $role;

$_SESSION['email'] = $user['email'];
$_SESSION['nama'] = $nama_login;
$_SESSION['name'] = $nama_login;

$_SESSION['guru_id'] = $guru_id;

$_SESSION['is_wali_kelas'] = $is_wali_kelas;
$_SESSION['akses_wali_kelas'] = $is_wali_kelas;

// ==========================================
// REDIRECT
// ==========================================
header("Location: dashboard.php");
exit;