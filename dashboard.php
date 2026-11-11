
<?php
session_start();

/* ==========================================
   CEK LOGIN
========================================== */
if (!isset($_SESSION['login']) || $_SESSION['login'] !== true) {
    header("Location: login.php");
    exit;
}

include "config/koneksi.php";

/* ==========================================
   CEK ROLE
========================================== */
$role = strtolower(trim($_SESSION['role'] ?? ''));
$role = str_replace([' ', '-'], '_', $role);

if (!in_array($role, ['admin', 'guru', 'wali_kelas'], true)) {
    exit("Anda tidak memiliki akses.");
}

/* ==========================================
   INISIALISASI DATA
========================================== */
$tampilan_wali_kelas = false;

$user_id = (int)(
    $_SESSION['user_id']
    ?? $_SESSION['id_user']
    ?? $_SESSION['id']
    ?? 0
);

$guru_id = (int)($_SESSION['guru_id'] ?? 0);

$nama_wali = $_SESSION['name'] ?? $_SESSION['nama'] ?? 'Pengguna';

$daftar_kelas = [];

$total_siswa = 0;
$total_pelanggaran = 0;
$total_poin = 0;

$error_wali = '';

/* ==========================================
   ADMIN
========================================== */
if ($role === 'admin') {
    $tampilan_wali_kelas = false;
}

/* ==========================================
   AMBIL DATA GURU
========================================== */
if (in_array($role, ['guru', 'wali_kelas'], true)) {

    if ($user_id > 0) {

        $stmtGuru = $koneksi->prepare("
            SELECT id, nama
            FROM t_guru
            WHERE user_id = ?
            LIMIT 1
        ");

        if (!$stmtGuru) {
            exit("Query guru gagal: " . htmlspecialchars($koneksi->error));
        }

        $stmtGuru->bind_param("i", $user_id);
        $stmtGuru->execute();

        $dataGuru = $stmtGuru->get_result()->fetch_assoc();

        $stmtGuru->close();

        if ($dataGuru) {
            $guru_id = (int)$dataGuru['id'];
            $nama_wali = $dataGuru['nama'];
        }
    }

    /* ======================================
       FALLBACK GURU ID
    ====================================== */
    if ($guru_id > 0 && !isset($dataGuru)) {

        $stmtGuru = $koneksi->prepare("
            SELECT id, nama
            FROM t_guru
            WHERE id = ?
            LIMIT 1
        ");

        if (!$stmtGuru) {
            exit("Query guru gagal: " . htmlspecialchars($koneksi->error));
        }

        $stmtGuru->bind_param("i", $guru_id);
        $stmtGuru->execute();

        $dataGuru = $stmtGuru->get_result()->fetch_assoc();

        $stmtGuru->close();

        if ($dataGuru) {
            $nama_wali = $dataGuru['nama'];
        } else {
            $guru_id = 0;
        }
    }

    /* ======================================
       CEK PENUGASAN WALI KELAS AKTIF
    ====================================== */
    if ($guru_id > 0) {

        $stmtCekWali = $koneksi->prepare("
            SELECT COUNT(*) AS total
            FROM t_wali_kelas wk
            INNER JOIN t_tahun_ajaran ta
                ON ta.id = wk.tahun_ajaran_id
            INNER JOIN t_kelas k
                ON k.id = wk.kelas_id
            WHERE wk.guru_id = ?
              AND wk.status_aktif = 1
              AND ta.status_aktif = 1
              AND k.status_aktif = 1
              AND CURDATE() BETWEEN wk.tanggal_mulai
                                AND wk.tanggal_selesai
        ");

        if (!$stmtCekWali) {
            exit("Query penugasan gagal: " . htmlspecialchars($koneksi->error));
        }

        $stmtCekWali->bind_param("i", $guru_id);
        $stmtCekWali->execute();

        $dataCekWali = $stmtCekWali->get_result()->fetch_assoc();

        $stmtCekWali->close();

        if ((int)($dataCekWali['total'] ?? 0) > 0) {
            $tampilan_wali_kelas = true;
        }
    }

    /* ======================================
       ROLE WALI KELAS WAJIB PUNYA PENUGASAN
    ====================================== */
    if ($role === 'wali_kelas') {

        $tampilan_wali_kelas = true;

        if ($guru_id <= 0) {
            $error_wali = "Data guru untuk akun ini tidak ditemukan.";
        }
    }
}

/* ==========================================
   DATA DASHBOARD WALI KELAS
========================================== */
if ($tampilan_wali_kelas && $guru_id > 0) {

    /* ======================================
       DAFTAR KELAS TANGGUNG JAWAB
    ====================================== */
    $stmtKelas = $koneksi->prepare("
        SELECT
            wk.id,
            wk.kelas_id,
            wk.tahun_ajaran_id,
            k.nama AS nama_kelas,
            ta.nama AS nama_tahun_ajaran
        FROM t_wali_kelas wk
        INNER JOIN t_kelas k
            ON k.id = wk.kelas_id
        INNER JOIN t_tahun_ajaran ta
            ON ta.id = wk.tahun_ajaran_id
        WHERE wk.guru_id = ?
          AND wk.status_aktif = 1
          AND ta.status_aktif = 1
          AND k.status_aktif = 1
          AND CURDATE() BETWEEN wk.tanggal_mulai
                            AND wk.tanggal_selesai
        ORDER BY k.nama ASC
    ");

    if (!$stmtKelas) {
        exit("Query kelas gagal: " . htmlspecialchars($koneksi->error));
    }

    $stmtKelas->bind_param("i", $guru_id);
    $stmtKelas->execute();

    $resultKelas = $stmtKelas->get_result();

    while ($kelas = $resultKelas->fetch_assoc()) {
        $daftar_kelas[] = $kelas;
    }

    $stmtKelas->close();

    if (empty($daftar_kelas)) {

        $error_wali = "Belum ada penugasan Wali Kelas yang aktif.";

    } else {

        /* ======================================
           HITUNG JUMLAH SISWA
        ====================================== */
        $stmtSiswa = $koneksi->prepare("
            SELECT COUNT(DISTINCT ks.siswa_id) AS total
            FROM t_kelas_siswa ks
            INNER JOIN t_wali_kelas wk
                ON wk.kelas_id = ks.kelas_id
               AND wk.tahun_ajaran_id = ks.tahun_ajaran_id
            INNER JOIN t_tahun_ajaran ta
                ON ta.id = wk.tahun_ajaran_id
            INNER JOIN t_siswa s
                ON s.id = ks.siswa_id
            WHERE wk.guru_id = ?
              AND wk.status_aktif = 1
              AND ta.status_aktif = 1
              AND CURDATE() BETWEEN wk.tanggal_mulai
                                AND wk.tanggal_selesai
              AND ks.status_aktif = 1
              AND s.status_aktif = 1
        ");

        if (!$stmtSiswa) {
            exit("Query siswa gagal: " . htmlspecialchars($koneksi->error));
        }

        $stmtSiswa->bind_param("i", $guru_id);
        $stmtSiswa->execute();

        $dataSiswa = $stmtSiswa->get_result()->fetch_assoc();

        $total_siswa = (int)($dataSiswa['total'] ?? 0);

        $stmtSiswa->close();

        /* ======================================
           HITUNG PELANGGARAN DAN POIN
        ====================================== */
        $stmtPelanggaran = $koneksi->prepare("
            SELECT
                COUNT(ps.id) AS total_pelanggaran,
                COALESCE(SUM(ps.poin), 0) AS total_poin
            FROM t_pelanggaran_siswa ps
            WHERE EXISTS (
                SELECT 1
                FROM t_wali_kelas wk
                INNER JOIN t_tahun_ajaran ta
                    ON ta.id = wk.tahun_ajaran_id
                WHERE wk.guru_id = ?
                  AND wk.kelas_id = ps.kelas_id
                  AND wk.tahun_ajaran_id = ps.tahun_ajaran_id
                  AND wk.status_aktif = 1
                  AND ta.status_aktif = 1
                  AND CURDATE() BETWEEN wk.tanggal_mulai
                                    AND wk.tanggal_selesai
            )
        ");

        if (!$stmtPelanggaran) {
            exit("Query pelanggaran gagal: " . htmlspecialchars($koneksi->error));
        }

        $stmtPelanggaran->bind_param("i", $guru_id);
        $stmtPelanggaran->execute();

        $dataPelanggaran = $stmtPelanggaran->get_result()->fetch_assoc();

        $total_pelanggaran = (int)($dataPelanggaran['total_pelanggaran'] ?? 0);
        $total_poin = (int)($dataPelanggaran['total_poin'] ?? 0);

        $stmtPelanggaran->close();
    }
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Dashboard Sistem Pelanggaran</title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >
</head>

<body class="bg-body-tertiary">

<div class="container-fluid">
    <div class="row">

        <!-- =================================
             SIDEBAR
        ================================== -->
        <aside class="col-md-3 col-lg-2 bg-dark min-vh-100 p-3 position-fixed top-0 start-0 overflow-auto">

            <h5 class="text-white mb-4">
                Sistem Pelanggaran
            </h5>

            <ul class="nav nav-pills flex-column gap-2">

                <li class="nav-item">
                    <a href="dashboard.php" class="nav-link active">
                        Dashboard
                    </a>
                </li>

                <!-- MENU ADMIN -->
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

                <!-- MENU GURU BIASA -->
                <?php } elseif ($role === 'guru' && !$tampilan_wali_kelas) { ?>

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

                <!-- MENU WALI KELAS -->
                <?php } elseif ($tampilan_wali_kelas) { ?>

                    <li class="nav-item">
                        <a href="catat_pelanggaran.php" class="nav-link text-white">
                            Catat Pelanggaran
                        </a>
                    </li>

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
                        <a href="walikelas/laporan.php" class="nav-link text-white">
                            Laporan
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="riwayat.php" class="nav-link text-white">
                            Riwayat
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="walikelas/rekap_poin.php" class="nav-link text-white">
                            Rekap Poin
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
                    <a
                        href="logout.php"
                        class="nav-link text-danger"
                        onclick="return confirm('Yakin ingin logout?')"
                    >
                        Logout
                    </a>
                </li>

            </ul>

        </aside>

        <!-- =================================
             KONTEN UTAMA
        ================================== -->
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
                            <div class="card shadow-sm h-100">
                                <div class="card-body">
                                    <h5 class="card-title">
                                        <?= htmlspecialchars($menu[0]); ?>
                                    </h5>

                                    <p class="card-text">
                                        <?= htmlspecialchars($menu[1]); ?>
                                    </p>

                                    <a href="<?= htmlspecialchars($menu[2]); ?>" class="btn btn-primary">
                                        Lihat Data
                                    </a>
                                </div>
                            </div>
                        </div>

                    <?php } ?>

                </div>

            <!-- DASHBOARD GURU -->
            <?php } elseif ($role === 'guru' && !$tampilan_wali_kelas) { ?>

                <h2>Dashboard Guru</h2>

                <p>
                    Selamat datang,
                    <b><?= htmlspecialchars($nama_wali); ?></b>
                </p>

                <div class="row">

                    <?php
                    $menuGuru = [
                        ['Catat Pelanggaran', 'Mencatat pelanggaran siswa.', 'catat_pelanggaran.php'],
                        ['Tindakan', 'Mengelola tindakan terhadap pelanggaran.', 'tindakan.php'],
                        ['Riwayat', 'Melihat riwayat pelanggaran.', 'riwayat.php'],
                        ['Rekap Poin', 'Melihat rekap poin pelanggaran yang dicatat guru.', 'rekap_poin.php']
                    ];

                    foreach ($menuGuru as $menu) {
                    ?>

                        <div class="col-md-4 mb-3">
                            <div class="card shadow-sm h-100">
                                <div class="card-body">
                                    <h5 class="card-title">
                                        <?= htmlspecialchars($menu[0]); ?>
                                    </h5>

                                    <p class="card-text">
                                        <?= htmlspecialchars($menu[1]); ?>
                                    </p>

                                    <a href="<?= htmlspecialchars($menu[2]); ?>" class="btn btn-primary">
                                        Buka
                                    </a>
                                </div>
                            </div>
                        </div>

                    <?php } ?>

                </div>

            <!-- DASHBOARD WALI KELAS -->
            <?php } elseif ($tampilan_wali_kelas) { ?>

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
                            <div class="card shadow-sm h-100">
                                <div class="card-body">
                                    <h6 class="text-muted">Jumlah Siswa</h6>
                                    <h2><?= number_format($total_siswa); ?></h2>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4 mb-3">
                            <div class="card shadow-sm h-100">
                                <div class="card-body">
                                    <h6 class="text-muted">Total Pelanggaran</h6>
                                    <h2><?= number_format($total_pelanggaran); ?></h2>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4 mb-3">
                            <div class="card shadow-sm h-100">
                                <div class="card-body">
                                    <h6 class="text-muted">Total Poin</h6>
                                    <h2><?= number_format($total_poin); ?></h2>
                                </div>
                            </div>
                        </div>

                    </div>

                    <!-- MENU WALI KELAS -->
                    <div class="row mt-3">

                        <div class="col-md-4 mb-3">
                            <div class="card shadow-sm h-100">
                                <div class="card-body">
                                    <h5>Catat Pelanggaran</h5>
                                    <p>Mencatat pelanggaran siswa pada kelas tanggung jawab.</p>
                                    <a href="catat_pelanggaran.php" class="btn btn-primary">
                                        Buka
                                    </a>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4 mb-3">
                            <div class="card shadow-sm h-100">
                                <div class="card-body">
                                    <h5>Data Siswa</h5>
                                    <p>Melihat data siswa pada kelas tanggung jawab.</p>
                                    <a href="walikelas/data_siswa.php" class="btn btn-primary">
                                        Buka
                                    </a>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4 mb-3">
                            <div class="card shadow-sm h-100">
                                <div class="card-body">
                                    <h5>Rekap Pelanggaran</h5>
                                    <p>Melihat rekap pelanggaran siswa.</p>
                                    <a href="walikelas/rekap_pelanggaran.php" class="btn btn-primary">
                                        Buka
                                    </a>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4 mb-3">
                            <div class="card shadow-sm h-100">
                                <div class="card-body">
                                    <h5>Laporan</h5>
                                    <p>Melihat laporan pelanggaran siswa.</p>
                                    <a href="walikelas/laporan.php" class="btn btn-primary">
                                        Buka
                                    </a>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4 mb-3">
                            <div class="card shadow-sm h-100">
                                <div class="card-body">
                                    <h5>Riwayat</h5>
                                    <p>Melihat riwayat catatan pelanggaran.</p>
                                    <a href="riwayat.php" class="btn btn-primary">
                                        Buka
                                    </a>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4 mb-3">
                            <div class="card shadow-sm h-100">
                                <div class="card-body">
                                    <h5>Rekap Poin</h5>
                                    <p>Melihat jumlah poin siswa pada kelas tanggung jawab.</p>
                                    <a href="walikelas/rekap_poin.php" class="btn btn-primary">
                                        Buka
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
