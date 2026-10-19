
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

        mysqli_stmt_bind_param($stmt, "ss", $nama, $deskripsi);

        if (mysqli_stmt_execute($stmt)) {
            header("Location: kategori_pelanggaran.php?pesan=tambah");
            exit;
        }

        $error = "Gagal menyimpan data: " . mysqli_error($koneksi);
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

        <aside class="col-md-3 col-lg-2 bg-dark min-vh-100 p-3">
            <h4 class="text-white mb-4">Sistem Pelanggaran</h4>

            <ul class="nav nav-pills flex-column gap-2">
                <li class="nav-item"><a href="dashboard.php" class="nav-link text-white">Dashboard</a></li>
                <li class="nav-item"><a href="siswa.php" class="nav-link text-white">Kelola Siswa</a></li>
                <li class="nav-item"><a href="guru.php" class="nav-link text-white">Kelola Guru</a></li>
                <li class="nav-item"><a href="kelas.php" class="nav-link text-white">Kelola Kelas</a></li>
                <li class="nav-item"><a href="tahun_ajaran.php" class="nav-link text-white">Tahun Ajaran</a></li>
                <li class="nav-item"><a href="penempatan_siswa.php" class="nav-link text-white">Penempatan Siswa</a></li>
                <li class="nav-item"><a href="wali_kelas.php" class="nav-link text-white">Wali Kelas</a></li>
                <li class="nav-item"><a href="kategori_pelanggaran.php" class="nav-link active">Kategori Pelanggaran</a></li>
                <li class="nav-item"><a href="jenis_pelanggaran.php" class="nav-link text-white">Jenis Pelanggaran</a></li>
                <li class="nav-item"><a href="laporan.php" class="nav-link text-white">Laporan</a></li>
                <hr class="text-secondary">
                <li class="nav-item"><a href="about_me.php" class="nav-link text-white">About Me</a></li>
                <li class="nav-item"><a href="logout.php" class="nav-link text-danger" onclick="return confirm('Yakin ingin logout?')">Logout</a></li>
            </ul>
        </aside>

        <main class="col-md-9 col-lg-10 p-4">
            <h2 class="mb-4">Tambah Kategori Pelanggaran</h2>

            <a href="kategori_pelanggaran.php" class="btn btn-secondary mb-3">
                Kembali
            </a>

            <div class="card shadow-sm">
                <div class="card-body">

                    <?php if (isset($error)) { ?>
                        <div class="alert alert-danger">
                            <?= htmlspecialchars($error); ?>
                        </div>
                    <?php } ?>

                    <form method="POST">
                        <div class="mb-3">
                            <label class="form-label">Nama Kategori</label>
                            <input type="text" name="nama" class="form-control"
                                   value="<?= htmlspecialchars($_POST['nama'] ?? ''); ?>"
                                   required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Deskripsi</label>
                            <textarea name="deskripsi" class="form-control"
                                      rows="3" required><?= htmlspecialchars($_POST['deskripsi'] ?? ''); ?></textarea>
                        </div>

                        <button type="submit" name="simpan" class="btn btn-primary">
                            Simpan
                        </button>

                        <a href="kategori_pelanggaran.php" class="btn btn-secondary">
                            Batal
                        </a>
                    </form>

                </div>
            </div>
        </main>

    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
