
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

// Periksa ID jenis pelanggaran
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: jenis_pelanggaran.php");
    exit;
}

$id = (int) $_GET['id'];

// Periksa apakah data tersedia
$stmt = mysqli_prepare(
    $koneksi,
    "SELECT id FROM t_pelanggaran WHERE id = ?"
);

mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);
$data = mysqli_fetch_assoc($result);

mysqli_stmt_close($stmt);

if (!$data) {
    header("Location: jenis_pelanggaran.php");
    exit;
}

// Hapus jenis pelanggaran
try {
    $stmt = mysqli_prepare(
        $koneksi,
        "DELETE FROM t_pelanggaran WHERE id = ?"
    );

    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);

    $jumlah = mysqli_stmt_affected_rows($stmt);

    mysqli_stmt_close($stmt);

    if ($jumlah > 0) {
        header("Location: jenis_pelanggaran.php?pesan=hapus");
    } else {
        header("Location: jenis_pelanggaran.php?pesan=gagal_hapus");
    }
    exit;

} catch (mysqli_sql_exception $e) {
    if (isset($stmt)) {
        mysqli_stmt_close($stmt);
    }

    header("Location: jenis_pelanggaran.php?pesan=gagal_hapus");
    exit;
}
?>
