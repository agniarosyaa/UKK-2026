
<?php
session_start();

if (!isset($_SESSION['login']) || $_SESSION['login'] !== true) {
    header("Location: login.php");
    exit;
}

include "config/koneksi.php";

$role = strtolower(trim($_SESSION['role'] ?? ''));

if (!in_array($role, ['admin', 'guru', 'wali_kelas'], true)) {
    exit("Anda tidak memiliki akses");
}

/* =========================================
   DATA DASHBOARD WALI KELAS
========================================= */

$nama_wali = $_SESSION['name'] ?? 'Wali Kelas';
$daftar_kelas = [];
$total_siswa = 0;
$total_pelanggaran = 0;
$total_poin = 0;
$error_wali = '';
$guru_id = 0;

if ($role === 'wali_kelas') {

    $user_id = (int) (
        $_SESSION['user_id']
        ?? $_SESSION['id_user']
        ?? $_SESSION['id']
        ?? 0
    );

    if ($user_id <= 0) {
        $error_wali = "ID akun tidak ditemukan.";
    } else {

        // Cari data guru berdasarkan akun login
        $stmtGuru = mysqli_prepare(
            $koneksi,
            "SELECT id, nama
             FROM t_guru
             WHERE user_id = ?
             LIMIT 1"
        );

        if (!$stmtGuru) {
            die("Query guru gagal: " . mysqli_error($koneksi));
        }

        mysqli_stmt_bind_param($stmtGuru, "i", $user_id);
        mysqli_stmt_execute($stmtGuru);

        $resultGuru = mysqli_stmt_get_result($stmtGuru);
        $dataGuru = mysqli_fetch_assoc($resultGuru);

        mysqli_stmt_close($stmtGuru);

        if (!$dataGuru) {
            $error_wali = "Data guru untuk akun ini tidak ditemukan.";
        } else {

            $guru_id = (int) $dataGuru['id'];
            $nama_wali = $dataGuru['nama'];

            // Ambil kelas yang ditugaskan kepada wali kelas
            $stmtKelas = mysqli_prepare(
                $koneksi,
                "SELECT
                    wk.id,
                    wk.kelas_id,
                    k.nama AS nama_kelas
                 FROM t_wali_kelas wk
                 JOIN t_kelas k
                    ON k.id = wk.kelas_id
                 JOIN t_tahun_ajaran ta
                    ON ta.id = wk.tahun_ajaran_id
                 WHERE wk.guru_id = ?
                   AND wk.status_aktif = 1
                   AND ta.status_aktif = 1
                 ORDER BY k.nama ASC"
            );

            if (!$stmtKelas) {
                die("Query kelas gagal: " . mysqli_error($koneksi));
            }

            mysqli_stmt_bind_param($stmtKelas, "i", $guru_id);
            mysqli_stmt_execute($stmtKelas);

            $resultKelas = mysqli_stmt_get_result($stmtKelas);

            while ($kelas = mysqli_fetch_assoc($resultKelas)) {
                $daftar_kelas[] = $kelas;
            }

            mysqli_stmt_close($stmtKelas);

            if (count($daftar_kelas) === 0) {

                $error_wali = "Belum ada penugasan wali kelas yang aktif.";

            } else {

                // Hitung siswa hanya dari kelas tugasnya
                $stmtSiswa = mysqli_prepare(
                    $koneksi,
                    "SELECT COUNT(DISTINCT ks.siswa_id) AS total
                     FROM t_kelas_siswa ks
                     JOIN t_wali_kelas wk
                        ON wk.kelas_id = ks.kelas_id
                     JOIN t_tahun_ajaran ta
                        ON ta.id = wk.tahun_ajaran_id
                     WHERE wk.guru_id = ?
                       AND wk.status_aktif = 1
                       AND ta.status_aktif = 1"
                );

                if (!$stmtSiswa) {
                    die("Query siswa gagal: " . mysqli_error($koneksi));
                }

                mysqli_stmt_bind_param($stmtSiswa, "i", $guru_id);
                mysqli_stmt_execute($stmtSiswa);

                $resultSiswa = mysqli_stmt_get_result($stmtSiswa);
                $dataSiswa = mysqli_fetch_assoc($resultSiswa);

                $total_siswa = (int) ($dataSiswa['total'] ?? 0);

                mysqli_stmt_close($stmtSiswa);

                // Hitung pelanggaran kelas yang ditugaskan
                $stmtPelanggaran = mysqli_prepare(
                    $koneksi,
                    "SELECT
                        COUNT(DISTINCT ps.id) AS total_pelanggaran,
                        COALESCE(SUM(ps.poin), 0) AS total_poin
                     FROM t_pelanggaran_siswa ps
                     JOIN t_wali_kelas wk
                        ON wk.kelas_id = ps.kelas_id
                     JOIN t_tahun_ajaran ta
                        ON ta.id = wk.tahun_ajaran_id
                     WHERE wk.guru_id = ?
                       AND wk.status_aktif = 1
                       AND ta.status_aktif = 1"
                );

                if (!$stmtPelanggaran) {
                    die("Query pelanggaran gagal: " . mysqli_error($koneksi));
                }

                mysqli_stmt_bind_param(
                    $stmtPelanggaran,
                    "i",
                    $guru_id
                );

                mysqli_stmt_execute($stmtPelanggaran);

                $resultPelanggaran = mysqli_stmt_get_result($stmtPelanggaran);
                $dataPelanggaran = mysqli_fetch_assoc($resultPelanggaran);

                $total_pelanggaran = (int) (
                    $dataPelanggaran['total_pelanggaran'] ?? 0
                );

                $total_poin = (int) (
                    $dataPelanggaran['total_poin'] ?? 0
                );

                mysqli_stmt_close($stmtPelanggaran);
            }
        }
    }
}

/* =========================================
   DASHBOARD ADMIN DAN GURU
========================================= */

$query = "SELECT * FROM t_pelanggaran_siswa ORDER BY tanggal DESC";
$result = mysqli_query($koneksi, $query);
$error = $result ? '' : mysqli_error($koneksi);

?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Dashboard Sistem Pelanggaran</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css"
          rel="stylesheet">
</head>

<body>

<div class="container-fluid">
    <div class="row">

        <!-- SIDEBAR -->
        <aside class="col-md-3 col-lg-2 bg-dark min-vh-100 p-3 position-fixed top-0 start-0 overflow-auto">

            <h5 class="text-white mb-4">
                Sistem Pelanggaran
            </h5>

            <ul class="nav nav-pills flex-column gap-2">

                <li class="nav-item">
                    <a href="dashboard.php"
                       class="nav-link active">
                        Dashboard
                    </a>
                </li>

                <?php if ($role === 'admin') { ?>

                    <li class="nav-item">
                        <a href="siswa.php" class="nav-link text-white">
                            Kelola Siswa
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="guru.php" class="nav-link text-white">
                            Kelola Guru
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="kelas.php" class="nav-link text-white">
                            Kelola Kelas
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="tahun_ajaran.php" class="nav-link text-white">
                            Tahun Ajaran
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="penempatan_siswa.php" class="nav-link text-white">
                            Penempatan Siswa
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="wali_kelas.php" class="nav-link text-white">
                            Wali Kelas
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="kategori_pelanggaran.php" class="nav-link text-white">
                            Kategori Pelanggaran
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="jenis_pelanggaran.php" class="nav-link text-white">
                            Jenis Pelanggaran
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="laporan.php" class="nav-link text-white">
                            Laporan
                        </a>
                    </li>

                <?php } elseif ($role === 'guru') { ?>

                    <li class="nav-item">
                        <a href="catat_pelanggaran.php" class="nav-link text-white">
                            Catat Pelanggaran
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="tindakan.php" class="nav-link text-white">
                            Tindakan
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="riwayat.php" class="nav-link text-white">
                            Riwayat
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="rekap_poin.php" class="nav-link text-white">
                            Rekap Poin
                        </a>
                    </li>

                <?php } elseif ($role === 'wali_kelas') { ?>

                    <li class="nav-item">
                        <a href="walikelas/data_siswa.php" class="nav-link text-white">
                            Data Siswa
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="walikelas/rekap_pelanggaran.php" class="nav-link text-white">
                            Rekap Pelanggaran
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="walikelas/cetak_rekap.php" class="nav-link text-white">
                            Cetak Rekap
                        </a>
                    </li>

                <?php } ?>

                <hr class="text-secondary">

                <li class="nav-item">
                    <a href="about_me.php" class="nav-link text-white">
                        About Me
                    </a>
                </li>

                <li class="nav-item">
                    <a href="logout.php"
                       class="nav-link text-danger"
                       onclick="return confirm('Yakin ingin logout?')">
                        Logout
                    </a>
                </li>

            </ul>

        </aside>

        <!-- KONTEN UTAMA -->
        <main class="col-md-9 col-lg-10 offset-md-3 offset-lg-2 p-4">

            <!-- DASHBOARD ADMIN -->
            <?php if ($role === 'admin') { ?>

                <h2>Dashboard Admin</h2>

                <p>
                    Selamat datang,
                    <b><?= htmlspecialchars($_SESSION['name'] ?? 'Admin'); ?></b>
                </p>

                <div class="row">

                    <?php
                    $menuAdmin = [
                        ['Data Siswa', 'Kelola data siswa.', 'siswa.php'],
                        ['Data Guru', 'Kelola data guru.', 'guru.php'],
                        ['Data Kelas', 'Kelola data kelas.', 'kelas.php'],
                        ['Data Tahun Ajaran', 'Kelola data tahun ajaran.', 'tahun_ajaran.php'],
                        ['Data Penempatan Siswa', 'Kelola penempatan siswa.', 'penempatan_siswa.php'],
                        ['Data Wali Kelas', 'Kelola data wali kelas.', 'wali_kelas.php'],
                        ['Data Kategori Pelanggaran', 'Kelola kategori pelanggaran.', 'kategori_pelanggaran.php'],
                        ['Data Jenis Pelanggaran', 'Kelola jenis pelanggaran.', 'jenis_pelanggaran.php'],
                        ['Laporan', 'Melihat laporan pelanggaran siswa.', 'laporan.php']
                    ];

                    foreach ($menuAdmin as $menu) {
                    ?>

                        <div class="col-md-4 mb-3">
                            <div class="card shadow-sm">
                                <div class="card-body">
                                    <h5 class="card-title">
                                        <?= htmlspecialchars($menu[0]); ?>
                                    </h5>

                                    <p class="card-text">
                                        <?= htmlspecialchars($menu[1]); ?>
                                    </p>

                                    <a href="<?= htmlspecialchars($menu[2]); ?>"
                                       class="btn btn-primary">
                                        Lihat Data
                                    </a>
                                </div>
                            </div>
                        </div>

                    <?php } ?>

                </div>

            <!-- DASHBOARD GURU -->
            <?php } elseif ($role === 'guru') { ?>

                <h2>Dashboard Guru</h2>

                <p>
                    Selamat datang,
                    <b><?= htmlspecialchars($_SESSION['name'] ?? 'Guru'); ?></b>
                </p>

                <div class="row">

                    <?php
                    $menuGuru = [
                        ['Catat Pelanggaran', 'Mencatat pelanggaran siswa.', 'catat_pelanggaran.php'],
                        ['Tindakan', 'Mengelola tindakan terhadap pelanggaran.', 'tindakan.php'],
                        ['Riwayat', 'Melihat riwayat pelanggaran.', 'riwayat.php'],
                        ['Rekap Poin', 'Melihat rekap poin.', 'rekap_poin.php']
                    ];

                    foreach ($menuGuru as $menu) {
                    ?>

                        <div class="col-md-4 mb-3">
                            <div class="card shadow-sm">
                                <div class="card-body">
                                    <h5 class="card-title">
                                        <?= htmlspecialchars($menu[0]); ?>
                                    </h5>

                                    <p class="card-text">
                                        <?= htmlspecialchars($menu[1]); ?>
                                    </p>

                                    <a href="<?= htmlspecialchars($menu[2]); ?>"
                                       class="btn btn-primary">
                                        Buka
                                    </a>
                                </div>
                            </div>
                        </div>

                    <?php } ?>

                </div>

            <!-- DASHBOARD WALI KELAS -->
            <?php } elseif ($role === 'wali_kelas') { ?>

                <h2>Dashboard Wali Kelas</h2>

                <p>
                    Selamat datang,
                    <b><?= htmlspecialchars($nama_wali); ?></b>
                </p>

                <?php if ($error_wali !== '') { ?>

                    <div class="alert alert-warning">
                        <?= htmlspecialchars($error_wali); ?>
                    </div>

                <?php } else { ?>

                    <!-- KELAS TANGGUNG JAWAB -->
                    <div class="mb-4">

                        <h5>Kelas Tanggung Jawab</h5>

                        <?php foreach ($daftar_kelas as $kelas) { ?>

                            <span class="badge bg-primary me-2 mb-2 p-2">
                                <?= htmlspecialchars($kelas['nama_kelas']); ?>
                            </span>

                        <?php } ?>

                    </div>

                    <!-- RINGKASAN -->
                    <div class="row">

                        <div class="col-md-4 mb-3">
                            <div class="card shadow-sm">
                                <div class="card-body">
                                    <h6 class="text-muted">Jumlah Siswa</h6>
                                    <h2><?= number_format($total_siswa); ?></h2>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4 mb-3">
                            <div class="card shadow-sm">
                                <div class="card-body">
                                    <h6 class="text-muted">Total Pelanggaran</h6>
                                    <h2><?= number_format($total_pelanggaran); ?></h2>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4 mb-3">
                            <div class="card shadow-sm">
                                <div class="card-body">
                                    <h6 class="text-muted">Total Poin</h6>
                                    <h2><?= number_format($total_poin); ?></h2>
                                </div>
                            </div>
                        </div>

                    </div>

                    <!-- MENU WALI KELAS -->
                    <h5 class="mt-4 mb-3">Menu Wali Kelas</h5>

                    <div class="row">

                        <div class="col-md-4 mb-3">
                            <div class="card shadow-sm h-100">
                                <div class="card-body">
                                    <h5>Data Siswa</h5>

                                    <p>
                                        Melihat data siswa kelas sendiri.
                                    </p>

                                    <a href="walikelas/data_siswa.php"
                                       class="btn btn-primary">
                                        Lihat Data
                                    </a>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4 mb-3">
                            <div class="card shadow-sm h-100">
                                <div class="card-body">
                                    <h5>Rekap Pelanggaran</h5>

                                    <p>
                                        Melihat rekap pelanggaran siswa kelas sendiri.
                                    </p>

                                    <a href="walikelas/rekap_pelanggaran.php"
                                       class="btn btn-primary">
                                        Lihat Rekap
                                    </a>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4 mb-3">
                            <div class="card shadow-sm h-100">
                                <div class="card-body">
                                    <h5>Cetak Rekap</h5>

                                    <p>
                                        Mencetak laporan pelanggaran kelas sendiri.
                                    </p>

                                    <a href="walikelas/cetak_rekap.php"
                                       class="btn btn-success">
                                        Cetak Rekap
                                    </a>
                                </div>
                            </div>
                        </div>

                    </div>

                <?php } ?>

            <?php } ?>

        </main>

    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
