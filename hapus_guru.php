
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

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: guru.php");
    exit;
}

$id = (int) $_GET['id'];

// Cek riwayat pelanggaran guru
$stmt = mysqli_prepare(
    $koneksi,
    "SELECT COUNT(*) AS total
     FROM t_pelanggaran_siswa
     WHERE guru_id = ?"
);

mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);
$data = mysqli_fetch_assoc($result);

mysqli_stmt_close($stmt);

if ($data['total'] > 0) {

    // Nonaktifkan guru jika masih memiliki riwayat
    $stmt = mysqli_prepare(
        $koneksi,
        "UPDATE t_guru SET status_aktif = 0 WHERE id = ?"
    );

    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    echo "<script>
        alert('Guru memiliki riwayat pelanggaran. Status guru berhasil dinonaktifkan.');
        window.location.href = 'guru.php';
    </script>";
    exit;
}

// Hapus permanen jika tidak memiliki riwayat
$stmt = mysqli_prepare(
    $koneksi,
    "DELETE FROM t_guru WHERE id = ?"
);

mysqli_stmt_bind_param($stmt, "i", $id);

if (mysqli_stmt_execute($stmt)) {
    mysqli_stmt_close($stmt);

    header("Location: guru.php");
    exit;
}

mysqli_stmt_close($stmt);

echo "<script>
    alert('Data guru gagal dihapus.');
    window.location.href = 'guru.php';
</script>";
exit;
?>
