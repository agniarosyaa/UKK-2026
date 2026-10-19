
<?php
session_start();

if (!isset($_SESSION['login'])) {
    header("Location: login.php");
    exit;
}

if (strtolower($_SESSION['role'] ?? '') != 'admin') {
    echo "Anda tidak memiliki akses";
    exit;
}

include "config/koneksi.php";

// Periksa ID tahun ajaran
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: tahun_ajaran.php");
    exit;
}

$id = (int) $_GET['id'];

// Siapkan query hapus
$stmt = mysqli_prepare(
    $koneksi,
    "DELETE FROM t_tahun_ajaran WHERE id = ?"
);

if (!$stmt) {
    die("Gagal menyiapkan query hapus tahun ajaran.");
}

mysqli_stmt_bind_param($stmt, "i", $id);

try {
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    header("Location: tahun_ajaran.php?pesan=hapus");
    exit;

} catch (mysqli_sql_exception $e) {
    mysqli_stmt_close($stmt);

    header("Location: tahun_ajaran.php?pesan=gagal_hapus");
    exit;
}
?>
