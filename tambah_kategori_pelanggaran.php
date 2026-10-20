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

$error = "";

$nama = "";
$deskripsi = "";

if (isset($_POST['simpan'])) {
    $nama = trim($_POST['nama'] ?? '');
    $deskripsi = trim($_POST['deskripsi'] ?? '');

    if ($nama === '' || $deskripsi === '') {
        $error = "Nama dan deskripsi wajib diisi.";
    } else {
        $stmt = mysqli_prepare(
            $koneksi,
            "INSERT INTO t_pelanggaran_kategori
            (nama, deskripsi, status_aktif, created_at, update_at)
            VALUES (?, ?, 1, NOW(), NOW())"
        );

        if ($stmt) {
            mysqli_stmt_bind_param($stmt, "ss", $nama, $deskripsi);

            if (mysqli_stmt_execute($stmt)) {
                mysqli_stmt_close($stmt);
                header("Location: kategori_pelanggaran.php?pesan=tambah");
                exit;
            } else {
                $error = "Gagal menyimpan data kategori pelanggaran.";
            }

            mysqli_stmt_close($stmt);
        } else {
            $error = "Terjadi kesalahan saat menyiapkan penyimpanan data.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Kategori Pelanggaran</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

<div class="container-fluid">
    <div class="row">

        <!-- SIDEBAR -->
        <aside class="col-md-3 col-lg-2 bg-dark min-vh-100 p-3">
            <h4 class="text-white mb-4">Sistem Pelanggaran</h4>

            <nav class="nav nav-pills flex-column gap-2">
                <a href="dashboard.php" class="nav-link text-white">Dashboard</a>
                <a href="siswa.php" class="nav-link text-white">Kelola Siswa</a>
                <a href="guru.php" class="nav-link text-white">Kelola Guru</a>
                <a href="kelas.php" class="nav-link text-white">Kelola Kelas</a>
                <a href="tahun_ajaran.php" class="nav-link text-white">Tahun Ajaran</a>
                <a href="penempatan_siswa.php" class="nav-link text-white">Penempatan Siswa</a>
                <a href="wali_kelas.php" class="nav-link text-white">Wali Kelas</a>
                <a href="kategori_pelanggaran.php" class="nav-link active">Kategori Pelanggaran</a>
                <a href="jenis_pelanggaran.php" class="nav-link text-white">Jenis Pelanggaran</a>
                <a href="laporan.php" class="nav-link text-white">Laporan</a>

                <hr class="text-secondary">

                <a href="about_me.php" class="nav-link text-white">About Me</a>
                <a href="logout.php" class="nav-link text-danger"
                   onclick="return confirm('Yakin ingin logout?')">
                    Logout
                </a>
            </nav>
        </aside>

        <!-- KONTEN UTAMA -->
        <main class="col-md-9 col-lg-10 p-4">

            <h2 class="mb-3">Tambah Kategori Pelanggaran</h2>


            <div class="card shadow-sm">

                <div class="card-header bg-primary text-white py-3">
                    <h5 class="mb-0">Form Tambah Kategori Pelanggaran</h5>
                </div>

                <div class="card-body p-4">

                    <?php if ($error !== '') { ?>
                        <div class="alert alert-danger">
                            <?= htmlspecialchars($error); ?>
                        </div>
                    <?php } ?>

                    <form method="POST" action="">

                        <div class="mb-3">
                            <label for="nama" class="form-label">
                                Nama Kategori
                            </label>

                            <input
                                type="text"
                                name="nama"
                                id="nama"
                                class="form-control"
                                value="<?= htmlspecialchars($nama); ?>"
                                placeholder="Masukkan nama kategori"
                                required
                            >
                        </div>

                        <div class="mb-4">
                            <label for="deskripsi" class="form-label">
                                Deskripsi
                            </label>

                            <textarea
                                name="deskripsi"
                                id="deskripsi"
                                class="form-control"
                                rows="4"
                                placeholder="Masukkan deskripsi kategori"
                                required
                            ><?= htmlspecialchars($deskripsi); ?></textarea>
                        </div>

                        <div class="d-flex flex-wrap gap-2">
                            <button type="submit" name="simpan" class="btn btn-primary">
                                Simpan
                            </button>

                            <a href="kategori_pelanggaran.php" class="btn btn-secondary">
                             Kembali
                            </a>
                        </div>

                    </form>

                </div>
            </div>

        </main>

    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
