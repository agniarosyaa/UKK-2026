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

$siswa = mysqli_query($koneksi, "SELECT id, nama FROM t_siswa ORDER BY nama ASC");
$kelas = mysqli_query($koneksi, "SELECT id, nama FROM t_kelas ORDER BY nama ASC");
$tahun = mysqli_query($koneksi, "SELECT id, nama FROM t_tahun_ajaran ORDER BY id DESC");

if (!$siswa || !$kelas || !$tahun) {
    die("Gagal mengambil data pilihan: " . mysqli_error($koneksi));
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $siswa_id = (int) ($_POST['siswa_id'] ?? 0);
    $kelas_id = (int) ($_POST['kelas_id'] ?? 0);
    $tahun_ajaran_id = (int) ($_POST['tahun_ajaran_id'] ?? 0);

    if ($siswa_id <= 0 || $kelas_id <= 0 || $tahun_ajaran_id <= 0) {
        $error = "Semua pilihan wajib diisi.";
    } else {
        $stmt = mysqli_prepare(
            $koneksi,
            "INSERT INTO t_kelas_siswa
             (siswa_id, kelas_id, tahun_ajaran_id)
             VALUES (?, ?, ?)"
        );

        mysqli_stmt_bind_param(
            $stmt,
            "iii",
            $siswa_id,
            $kelas_id,
            $tahun_ajaran_id
        );

        if (mysqli_stmt_execute($stmt)) {
            mysqli_stmt_close($stmt);
            header("Location: penempatan_siswa.php?pesan=tambah");
            exit;
        }

        $error = "Gagal menyimpan data. Pastikan siswa belum ditempatkan pada kombinasi kelas dan tahun ajaran yang sama.";
        mysqli_stmt_close($stmt);
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Penempatan Siswa</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css"
          rel="stylesheet">
</head>
<body class="bg-light">

<div class="container-fluid">
    <div class="row">

        <!-- SIDEBAR -->
        <aside class="col-md-3 col-lg-2 bg-dark min-vh-100 p-3">
            <h5 class="text-white mb-4">Sistem Pelanggaran</h5>

            <nav class="nav nav-pills flex-column gap-2">
                <a class="nav-link text-white" href="dashboard.php">Dashboard</a>
                <a class="nav-link text-white" href="siswa.php">Kelola Siswa</a>
                <a class="nav-link text-white" href="guru.php">Kelola Guru</a>
                <a class="nav-link text-white" href="kelas.php">Kelola Kelas</a>
                <a class="nav-link text-white" href="tahun_ajaran.php">Tahun Ajaran</a>
                <a class="nav-link active" href="penempatan_siswa.php">Penempatan Siswa</a>
                <a class="nav-link text-white" href="wali_kelas.php">Wali Kelas</a>
                <a class="nav-link text-white" href="kategori_pelanggaran.php">Kategori Pelanggaran</a>
                <a class="nav-link text-white" href="jenis_pelanggaran.php">Jenis Pelanggaran</a>
                <a class="nav-link text-white" href="laporan.php">Laporan</a>
                <a class="nav-link text-white" href="about_me.php">About Me</a>

                <hr class="border-secondary">

                <a class="nav-link text-danger" href="logout.php"
                   onclick="return confirm('Yakin ingin logout?')">Logout</a>
            </nav>
        </aside>

        <!-- KONTEN -->
        <main class="col-md-9 col-lg-10 p-4">
            <h3 class="mb-1">Tambah Penempatan Siswa</h3>
            <p class="text-muted mb-4">
                Pilih siswa, kelas, dan tahun ajaran.
            </p>

            <?php if ($error != "") { ?>
                <div class="alert alert-danger">
                    <?= htmlspecialchars($error); ?>
                </div>
            <?php } ?>

            <div class="card shadow-sm">
                <div class="card-body">
                    <form method="POST">

                        <div class="mb-3">
                            <label for="siswa_id" class="form-label">Nama Siswa</label>
                            <select name="siswa_id" id="siswa_id"
                                    class="form-select" required>
                                <option value="">-- Pilih Siswa --</option>
                                <?php while ($row = mysqli_fetch_assoc($siswa)) { ?>
                                    <option value="<?= (int) $row['id']; ?>"
                                        <?= (($_POST['siswa_id'] ?? '') == $row['id']) ? 'selected' : ''; ?>>
                                        <?= htmlspecialchars($row['nama']); ?>
                                    </option>
                                <?php } ?>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="kelas_id" class="form-label">Kelas</label>
                            <select name="kelas_id" id="kelas_id"
                                    class="form-select" required>
                                <option value="">-- Pilih Kelas --</option>
                                <?php while ($row = mysqli_fetch_assoc($kelas)) { ?>
                                    <option value="<?= (int) $row['id']; ?>"
                                        <?= (($_POST['kelas_id'] ?? '') == $row['id']) ? 'selected' : ''; ?>>
                                        <?= htmlspecialchars($row['nama']); ?>
                                    </option>
                                <?php } ?>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="tahun_ajaran_id" class="form-label">
                                Tahun Ajaran
                            </label>
                            <select name="tahun_ajaran_id" id="tahun_ajaran_id"
                                    class="form-select" required>
                                <option value="">-- Pilih Tahun Ajaran --</option>
                                <?php while ($row = mysqli_fetch_assoc($tahun)) { ?>
                                    <option value="<?= (int) $row['id']; ?>"
                                        <?= (($_POST['tahun_ajaran_id'] ?? '') == $row['id']) ? 'selected' : ''; ?>>
                                        <?= htmlspecialchars($row['nama']); ?>
                                    </option>
                                <?php } ?>
                            </select>
                        </div>

                        <button type="submit" class="btn btn-primary">
                            Simpan
                        </button>

                        <a href="penempatan_siswa.php" class="btn btn-secondary">
                            Kembali
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
