<?php
session_start();

if (!isset($_SESSION['login']) || $_SESSION['login'] !== true) {
    header("Location: login.php");
    exit;
}

include "config/koneksi.php";

$role = strtolower(trim($_SESSION['role'] ?? ''));
$role = str_replace([' ', '-'], '_', $role);

if (!in_array($role, ['admin', 'guru', 'wali_kelas'], true)) {
    exit("Anda tidak memiliki akses.");
}

$error = '';

function e($value) {
    return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
}

$user_id = (int)(
    $_SESSION['user_id']
    ?? $_SESSION['id_user']
    ?? $_SESSION['id']
    ?? 0
);

$guru_id = 0;
$namaGuruLogin = '';

$daftarKelas = [];
$daftarSiswa = [];

/* =====================================
   CARI GURU BERDASARKAN USER LOGIN
===================================== */

if (in_array($role, ['guru', 'wali_kelas'], true)) {

    if ($user_id > 0) {

        $stmtGuruLogin = mysqli_prepare($koneksi, "
            SELECT id, nama
            FROM t_guru
            WHERE user_id = ?
              AND status_aktif = 1
            LIMIT 1
        ");

        if (!$stmtGuruLogin) {
            exit("Query guru gagal: " . e(mysqli_error($koneksi)));
        }

        mysqli_stmt_bind_param($stmtGuruLogin, "i", $user_id);
        mysqli_stmt_execute($stmtGuruLogin);

        $dataGuruLogin = mysqli_fetch_assoc(
            mysqli_stmt_get_result($stmtGuruLogin)
        );

        mysqli_stmt_close($stmtGuruLogin);

        if ($dataGuruLogin) {
            $guru_id = (int)$dataGuruLogin['id'];
            $namaGuruLogin = $dataGuruLogin['nama'];
        }
    }

    /* CADANGAN GURU ID */
    if ($guru_id <= 0 && !empty($_SESSION['guru_id'])) {

        $guru_id = (int)$_SESSION['guru_id'];

        $stmtGuruLogin = mysqli_prepare($koneksi, "
            SELECT id, nama
            FROM t_guru
            WHERE id = ?
              AND status_aktif = 1
            LIMIT 1
        ");

        if ($stmtGuruLogin) {

            mysqli_stmt_bind_param(
                $stmtGuruLogin,
                "i",
                $guru_id
            );

            mysqli_stmt_execute($stmtGuruLogin);

            $dataGuruLogin = mysqli_fetch_assoc(
                mysqli_stmt_get_result($stmtGuruLogin)
            );

            mysqli_stmt_close($stmtGuruLogin);

            if ($dataGuruLogin) {
                $namaGuruLogin = $dataGuruLogin['nama'];
            }
        }
    }
}

/* =====================================
   TAHUN AJARAN AKTIF
===================================== */

$tahun = mysqli_query($koneksi, "
    SELECT id, nama
    FROM t_tahun_ajaran
    WHERE status_aktif = 1
    LIMIT 1
");

if (!$tahun) {
    exit(
        "Gagal mengambil tahun ajaran: "
        . e(mysqli_error($koneksi))
    );
}

$tahunAktif = mysqli_fetch_assoc($tahun);
$tahun_id = (int)($tahunAktif['id'] ?? 0);

/* =====================================
   DETEKSI MODE WALI KELAS
===================================== */

$mode_wali_kelas = ($role === 'wali_kelas');

if ($role === 'guru' && $guru_id > 0 && $tahun_id > 0) {

    $stmtModeWali = mysqli_prepare($koneksi, "
        SELECT id
        FROM t_wali_kelas
        WHERE guru_id = ?
          AND tahun_ajaran_id = ?
          AND status_aktif = 1
          AND CURDATE() BETWEEN tanggal_mulai AND tanggal_selesai
        LIMIT 1
    ");

    if (!$stmtModeWali) {
        exit(
            "Gagal memeriksa tugas Wali Kelas: "
            . e(mysqli_error($koneksi))
        );
    }

    mysqli_stmt_bind_param(
        $stmtModeWali,
        "ii",
        $guru_id,
        $tahun_id
    );

    mysqli_stmt_execute($stmtModeWali);

    $dataModeWali = mysqli_fetch_assoc(
        mysqli_stmt_get_result($stmtModeWali)
    );

    mysqli_stmt_close($stmtModeWali);

    if ($dataModeWali) {
        $mode_wali_kelas = true;
    }
}

/* =====================================
   AMBIL KELAS
===================================== */

if ($tahun_id > 0) {

    if ($mode_wali_kelas) {

        if ($guru_id <= 0) {

            $error = "Data guru untuk akun Wali Kelas tidak ditemukan.";

        } else {

            $queryKelas = mysqli_prepare($koneksi, "
                SELECT DISTINCT
                    k.id,
                    k.nama
                FROM t_wali_kelas wk
                INNER JOIN t_kelas k
                    ON k.id = wk.kelas_id
                INNER JOIN t_tahun_ajaran ta
                    ON ta.id = wk.tahun_ajaran_id
                WHERE wk.guru_id = ?
                  AND wk.tahun_ajaran_id = ?
                  AND wk.status_aktif = 1
                  AND ta.status_aktif = 1
                  AND k.status_aktif = 1
                  AND CURDATE() BETWEEN wk.tanggal_mulai
                                    AND wk.tanggal_selesai
                ORDER BY k.nama ASC
            ");

            if (!$queryKelas) {
                exit(
                    "Query kelas gagal: "
                    . e(mysqli_error($koneksi))
                );
            }

            mysqli_stmt_bind_param(
                $queryKelas,
                "ii",
                $guru_id,
                $tahun_id
            );

            mysqli_stmt_execute($queryKelas);

            $hasilKelas = mysqli_stmt_get_result($queryKelas);

            while ($row = mysqli_fetch_assoc($hasilKelas)) {
                $daftarKelas[] = $row;
            }

            mysqli_stmt_close($queryKelas);

            if (
                empty($daftarKelas)
                && $error === ''
            ) {
                $error =
                    "Tidak ada kelas yang menjadi tanggung jawab Anda "
                    . "pada tahun ajaran aktif.";
            }
        }

    } else {

        $queryKelas = mysqli_prepare($koneksi, "
            SELECT DISTINCT
                k.id,
                k.nama
            FROM t_kelas k
            INNER JOIN t_kelas_siswa ks
                ON ks.kelas_id = k.id
            WHERE ks.tahun_ajaran_id = ?
              AND ks.status_aktif = 1
              AND k.status_aktif = 1
            ORDER BY k.nama ASC
        ");

        if (!$queryKelas) {
            exit(
                "Query kelas gagal: "
                . e(mysqli_error($koneksi))
            );
        }

        mysqli_stmt_bind_param(
            $queryKelas,
            "i",
            $tahun_id
        );

        mysqli_stmt_execute($queryKelas);

        $hasilKelas = mysqli_stmt_get_result($queryKelas);

        while ($row = mysqli_fetch_assoc($hasilKelas)) {
            $daftarKelas[] = $row;
        }

        mysqli_stmt_close($queryKelas);
    }
}

/* =====================================
   AMBIL DATA SISWA
===================================== */

if ($tahun_id > 0) {

    if ($mode_wali_kelas && $guru_id > 0) {

        $querySiswa = mysqli_prepare($koneksi, "
            SELECT DISTINCT
                s.id,
                s.nama,
                s.nis,
                ks.kelas_id
            FROM t_siswa s
            INNER JOIN t_kelas_siswa ks
                ON ks.siswa_id = s.id
            INNER JOIN t_kelas k
                ON k.id = ks.kelas_id
            INNER JOIN t_wali_kelas wk
                ON wk.kelas_id = ks.kelas_id
               AND wk.tahun_ajaran_id = ks.tahun_ajaran_id
            INNER JOIN t_tahun_ajaran ta
                ON ta.id = wk.tahun_ajaran_id
            WHERE wk.guru_id = ?
              AND wk.tahun_ajaran_id = ?
              AND wk.status_aktif = 1
              AND ta.status_aktif = 1
              AND CURDATE() BETWEEN wk.tanggal_mulai
                                AND wk.tanggal_selesai
              AND ks.status_aktif = 1
              AND s.status_aktif = 1
              AND k.status_aktif = 1
            ORDER BY s.nama ASC
        ");

        if (!$querySiswa) {
            exit(
                "Query siswa gagal: "
                . e(mysqli_error($koneksi))
            );
        }

        mysqli_stmt_bind_param(
            $querySiswa,
            "ii",
            $guru_id,
            $tahun_id
        );

    } else {

        $querySiswa = mysqli_prepare($koneksi, "
            SELECT DISTINCT
                s.id,
                s.nama,
                s.nis,
                ks.kelas_id
            FROM t_siswa s
            INNER JOIN t_kelas_siswa ks
                ON ks.siswa_id = s.id
            INNER JOIN t_kelas k
                ON k.id = ks.kelas_id
            WHERE ks.tahun_ajaran_id = ?
              AND ks.status_aktif = 1
              AND s.status_aktif = 1
              AND k.status_aktif = 1
            ORDER BY s.nama ASC
        ");

        if (!$querySiswa) {
            exit(
                "Query siswa gagal: "
                . e(mysqli_error($koneksi))
            );
        }

        mysqli_stmt_bind_param(
            $querySiswa,
            "i",
            $tahun_id
        );
    }

    mysqli_stmt_execute($querySiswa);

    $hasilSiswa = mysqli_stmt_get_result($querySiswa);

    while ($row = mysqli_fetch_assoc($hasilSiswa)) {
        $daftarSiswa[] = $row;
    }

    mysqli_stmt_close($querySiswa);
}

/* =====================================
   AMBIL DATA PELANGGARAN
===================================== */

$daftarPelanggaran = mysqli_query($koneksi, "
    SELECT id, nama, poin
    FROM t_pelanggaran
    WHERE status_aktif = 1
    ORDER BY nama ASC
");

if (!$daftarPelanggaran) {
    exit(
        "Query pelanggaran gagal: "
        . e(mysqli_error($koneksi))
    );
}

/* =====================================
   PROSES SIMPAN
===================================== */

if (isset($_POST['simpan'])) {

    $tanggal = trim($_POST['tanggal'] ?? '');
    $kelas_id = (int)($_POST['kelas_id'] ?? 0);
    $siswa_id = (int)($_POST['siswa_id'] ?? 0);

    $nama_pelanggaran = trim(
        $_POST['nama_pelanggaran'] ?? ''
    );

    $keterangan = trim(
        $_POST['keterangan'] ?? ''
    );

    $tindakan = trim(
        $_POST['tindakan'] ?? ''
    );

    $status = trim(
        $_POST['status'] ?? ''
    );

    /* POIN TIDAK DIAMBIL DARI INPUT */
    $poin = 0;

    if (!$tahun_id) {

        $error = "Tahun ajaran aktif belum tersedia.";

    } elseif ($guru_id <= 0 || $namaGuruLogin === '') {

        $error = "Data guru yang sedang login tidak ditemukan.";

    } elseif (
        $tanggal === '' ||
        $kelas_id <= 0 ||
        $siswa_id <= 0 ||
        $nama_pelanggaran === '' ||
        $keterangan === '' ||
        $tindakan === '' ||
        !in_array(
            $status,
            [
                'Belum Ditindaklanjuti',
                'Diproses',
                'Selesai'
            ],
            true
        )
    ) {

        $error = "Semua kolom wajib diisi dengan benar.";

    } else {

        try {

            /* =====================================
               VALIDASI KELAS WALI KELAS
            ===================================== */

            if ($mode_wali_kelas) {

                $stmtCekKelas = mysqli_prepare($koneksi, "
                    SELECT wk.id
                    FROM t_wali_kelas wk
                    INNER JOIN t_tahun_ajaran ta
                        ON ta.id = wk.tahun_ajaran_id
                    WHERE wk.guru_id = ?
                      AND wk.kelas_id = ?
                      AND wk.tahun_ajaran_id = ?
                      AND wk.status_aktif = 1
                      AND ta.status_aktif = 1
                      AND CURDATE() BETWEEN wk.tanggal_mulai
                                        AND wk.tanggal_selesai
                    LIMIT 1
                ");

                if (!$stmtCekKelas) {
                    throw new Exception(mysqli_error($koneksi));
                }

                mysqli_stmt_bind_param(
                    $stmtCekKelas,
                    "iii",
                    $guru_id,
                    $kelas_id,
                    $tahun_id
                );

                mysqli_stmt_execute($stmtCekKelas);

                $cekKelas = mysqli_fetch_assoc(
                    mysqli_stmt_get_result($stmtCekKelas)
                );

                mysqli_stmt_close($stmtCekKelas);

                if (!$cekKelas) {

                    throw new Exception(
                        "Anda tidak memiliki hak untuk mencatat "
                        . "pelanggaran pada kelas tersebut."
                    );
                }
            }

            /* =====================================
               CARI SISWA
            ===================================== */

            $stmtSiswa = mysqli_prepare($koneksi, "
                SELECT
                    s.id AS siswa_id,
                    s.nama AS nama_siswa,
                    ks.kelas_id,
                    k.nama AS nama_kelas
                FROM t_siswa s
                INNER JOIN t_kelas_siswa ks
                    ON ks.siswa_id = s.id
                INNER JOIN t_kelas k
                    ON k.id = ks.kelas_id
                WHERE s.id = ?
                  AND ks.kelas_id = ?
                  AND ks.tahun_ajaran_id = ?
                  AND ks.status_aktif = 1
                  AND s.status_aktif = 1
                  AND k.status_aktif = 1
                LIMIT 1
            ");

            if (!$stmtSiswa) {
                throw new Exception(mysqli_error($koneksi));
            }

            mysqli_stmt_bind_param(
                $stmtSiswa,
                "iii",
                $siswa_id,
                $kelas_id,
                $tahun_id
            );

            mysqli_stmt_execute($stmtSiswa);

            $dataSiswa = mysqli_fetch_assoc(
                mysqli_stmt_get_result($stmtSiswa)
            );

            mysqli_stmt_close($stmtSiswa);

            /* =====================================
               CARI PELANGGARAN + POIN
            ===================================== */

            $stmtPelanggaran = mysqli_prepare($koneksi, "
                SELECT
                    id,
                    nama,
                    pelanggaran_kategori_id,
                    poin
                FROM t_pelanggaran
                WHERE nama = ?
                  AND status_aktif = 1
                LIMIT 1
            ");

            if (!$stmtPelanggaran) {
                throw new Exception(mysqli_error($koneksi));
            }

            mysqli_stmt_bind_param(
                $stmtPelanggaran,
                "s",
                $nama_pelanggaran
            );

            mysqli_stmt_execute($stmtPelanggaran);

            $dataPelanggaran = mysqli_fetch_assoc(
                mysqli_stmt_get_result($stmtPelanggaran)
            );

            mysqli_stmt_close($stmtPelanggaran);

            /* =====================================
               VALIDASI
            ===================================== */

            if (!$dataSiswa) {

                throw new Exception(
                    "Siswa tidak ditemukan pada kelas yang dipilih."
                );

            }

            if (!$dataPelanggaran) {

                throw new Exception(
                    "Nama pelanggaran tidak ditemukan."
                );
            }

            /* DATA SISWA */

            $siswa_id = (int)$dataSiswa['siswa_id'];
            $nama_siswa = $dataSiswa['nama_siswa'];

            $kelas_id = (int)$dataSiswa['kelas_id'];
            $nama_kelas = $dataSiswa['nama_kelas'];

            /* DATA PELANGGARAN */

            $pelanggaran_id = (int)$dataPelanggaran['id'];

            $nama_pelanggaran_db =
                $dataPelanggaran['nama'];

            $kategori_id =
                (int)$dataPelanggaran['pelanggaran_kategori_id'];

            /* POIN OTOMATIS DARI DATABASE */

            $poin = (int)$dataPelanggaran['poin'];

            /* DATA GURU */

            $guru_id_input = $guru_id;
            $nama_guru_db = $namaGuruLogin;

            /* =====================================
               SIMPAN
            ===================================== */

            $queryInsert = "
                INSERT INTO t_pelanggaran_siswa (
                    tahun_ajaran_id,
                    siswa_id,
                    nama_siswa,
                    kelas_id,
                    nama_kelas,
                    pelanggaran_id,
                    nama_pelanggaran,
                    pelanggaran_kategori_id,
                    guru_id,
                    nama_guru,
                    tanggal,
                    keterangan,
                    poin,
                    tindakan,
                    status
                )
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
            ";

            $stmtInsert = mysqli_prepare(
                $koneksi,
                $queryInsert
            );

            if (!$stmtInsert) {
                throw new Exception(
                    "Gagal menyiapkan penyimpanan: "
                    . mysqli_error($koneksi)
                );
            }

            mysqli_stmt_bind_param(
                $stmtInsert,
                "iisisiisiississ",
                $tahun_id,
                $siswa_id,
                $nama_siswa,
                $kelas_id,
                $nama_kelas,
                $pelanggaran_id,
                $nama_pelanggaran_db,
                $kategori_id,
                $guru_id_input,
                $nama_guru_db,
                $tanggal,
                $keterangan,
                $poin,
                $tindakan,
                $status
            );

            if (!mysqli_stmt_execute($stmtInsert)) {

                throw new Exception(
                    "Gagal menyimpan data: "
                    . mysqli_stmt_error($stmtInsert)
                );
            }

            mysqli_stmt_close($stmtInsert);

            header(
                "Location: tambah_catat_pelanggaran.php?pesan=tambah"
            );

            exit;

        } catch (Throwable $e) {

            $error = $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Tambah Catat Pelanggaran</title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css"
        rel="stylesheet">

</head>

<body>

<div class="container-fluid">

    <div class="row">

        <!-- SIDEBAR -->
        <aside
            class="col-md-3 col-lg-2 bg-dark text-white p-3 position-fixed top-0 bottom-0 start-0 overflow-auto">

            <h5 class="text-white mb-4">
                Sistem Pelanggaran
            </h5>

            <ul class="nav nav-pills flex-column gap-2">

                <li class="nav-item">
                    <a href="dashboard.php"
                       class="nav-link text-white">
                        Dashboard
                    </a>
                </li>

                <?php if ($role === 'admin') { ?>

                    <li class="nav-item">
                        <a href="siswa.php"
                           class="nav-link text-white">
                            Kelola Siswa
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="guru.php"
                           class="nav-link text-white">
                            Kelola Guru
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="kelas.php"
                           class="nav-link text-white">
                            Kelola Kelas
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="tahun_ajaran.php"
                           class="nav-link text-white">
                            Tahun Ajaran
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="penempatan_siswa.php"
                           class="nav-link text-white">
                            Penempatan Siswa
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="wali_kelas.php"
                           class="nav-link text-white">
                            Wali Kelas
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="kategori_pelanggaran.php"
                           class="nav-link text-white">
                            Kategori Pelanggaran
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="jenis_pelanggaran.php"
                           class="nav-link text-white">
                            Jenis Pelanggaran
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="catat_pelanggaran.php"
                           class="nav-link active">
                            Catat Pelanggaran
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="laporan.php"
                           class="nav-link text-white">
                            Laporan
                        </a>
                    </li>

                <?php } elseif ($mode_wali_kelas) { ?>

                    <li class="nav-item">
                        <a href="catat_pelanggaran.php"
                           class="nav-link active">
                            Catat Pelanggaran
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="walikelas/data_siswa.php"
                           class="nav-link text-white">
                            Data Siswa
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="walikelas/rekap_pelanggaran.php"
                           class="nav-link text-white">
                            Rekap Pelanggaran
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="walikelas/laporan.php"
                           class="nav-link text-white">
                            Laporan
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="riwayat.php"
                           class="nav-link text-white">
                            Riwayat
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="rekap_poin.php"
                           class="nav-link text-white">
                            Rekap Poin
                        </a>
                    </li>

                <?php } elseif ($role === 'guru') { ?>

                    <li class="nav-item">
                        <a href="catat_pelanggaran.php"
                           class="nav-link active">
                            Catat Pelanggaran
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="tindakan.php"
                           class="nav-link text-white">
                            Tindakan
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="riwayat.php"
                           class="nav-link text-white">
                            Riwayat
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="rekap_poin.php"
                           class="nav-link text-white">
                            Rekap Poin
                        </a>
                    </li>

                <?php } ?>

                <hr class="text-secondary">

                <li class="nav-item">
                    <a href="about_me.php"
                       class="nav-link text-white">
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


        <!-- KONTEN -->
        <main class="col-md-9 col-lg-10 p-4 offset-md-3 offset-lg-2">

            <h2 class="mb-2">
                Tambah Catat Pelanggaran
            </h2>

            <p class="text-secondary mb-4">
                Silakan isi data pelanggaran siswa.
            </p>


            <?php if ($tahunAktif) { ?>

                <div class="alert alert-info">

                    Tahun Ajaran Aktif:
                    <strong>
                        <?= e($tahunAktif['nama']); ?>
                    </strong>

                </div>

            <?php } else { ?>

                <div class="alert alert-warning">
                    Belum ada tahun ajaran yang aktif.
                </div>

            <?php } ?>


            <?php if (
                isset($_GET['pesan'])
                && $_GET['pesan'] === 'tambah'
            ) { ?>

                <div class="alert alert-success alert-dismissible fade show">

                    Data pelanggaran berhasil ditambahkan.

                    <button
                        type="button"
                        class="btn-close"
                        data-bs-dismiss="alert">
                    </button>

                </div>

            <?php } ?>


            <?php if ($error !== '') { ?>

                <div class="alert alert-danger">
                    <?= e($error); ?>
                </div>

            <?php } ?>


            <div class="card shadow-sm">

                <div class="card-header bg-primary text-white">

                    Form Tambah Catat Pelanggaran

                </div>

                <div class="card-body">

                    <form method="POST">

                        <!-- TANGGAL -->
                        <div class="mb-3">

                            <label class="form-label">
                                Tanggal
                            </label>

                            <input
                                type="date"
                                name="tanggal"
                                class="form-control"
                                value="<?= e(
                                    $_POST['tanggal']
                                    ?? date('Y-m-d')
                                ); ?>"
                                required>

                        </div>


                        <!-- KELAS -->
                        <div class="mb-3">

                            <label class="form-label">
                                Nama Kelas
                            </label>

                            <select
                                name="kelas_id"
                                id="kelas_id"
                                class="form-select"
                                required>

                                <option value="">
                                    -- Pilih Kelas --
                                </option>

                                <?php foreach (
                                    $daftarKelas as $kelas
                                ) { ?>

                                    <option
                                        value="<?= e($kelas['id']); ?>"
                                        <?= (
                                            ($_POST['kelas_id'] ?? '')
                                            == $kelas['id']
                                        )
                                            ? 'selected'
                                            : ''; ?>>

                                        <?= e($kelas['nama']); ?>

                                    </option>

                                <?php } ?>

                            </select>

                        </div>


                        <!-- SISWA -->
                        <div class="mb-3">

                            <label class="form-label">
                                Nama Siswa
                            </label>

                            <select
                                name="siswa_id"
                                id="siswa_id"
                                class="form-select"
                                required>

                                <option value="">
                                    -- Pilih Nama Siswa --
                                </option>

                                <?php foreach (
                                    $daftarSiswa as $siswa
                                ) { ?>

                                    <option
                                        value="<?= e($siswa['id']); ?>"
                                        data-kelas="<?= e(
                                            $siswa['kelas_id']
                                        ); ?>">

                                        <?= e($siswa['nama']); ?>
                                        - NIS:
                                        <?= e($siswa['nis']); ?>

                                    </option>

                                <?php } ?>

                            </select>

                            <small class="text-secondary">
                                Pilih kelas terlebih dahulu untuk
                                menampilkan siswa.
                            </small>

                        </div>


                        <!-- NAMA PELANGGARAN -->
                        <div class="mb-3">

                            <label class="form-label">
                                Nama Pelanggaran
                            </label>

                            <select
                                name="nama_pelanggaran"
                                id="nama_pelanggaran"
                                class="form-select"
                                required>

                                <option value="">
                                    -- Pilih Pelanggaran --
                                </option>

                                <?php
                                mysqli_data_seek(
                                    $daftarPelanggaran,
                                    0
                                );

                                while (
                                    $pelanggaran =
                                    mysqli_fetch_assoc(
                                        $daftarPelanggaran
                                    )
                                ) {
                                ?>

                                    <option
                                        value="<?= e(
                                            $pelanggaran['nama']
                                        ); ?>"
                                        data-poin="<?= (int)$pelanggaran['poin']; ?>">

                                        <?= e(
                                            $pelanggaran['nama']
                                        ); ?>

                                    </option>

                                <?php } ?>

                            </select>

                        </div>


                        <!-- POIN OTOMATIS -->
                        <div class="mb-3">

                            <label class="form-label">
                                Poin Pelanggaran
                            </label>

                            <input
                                type="number"
                                name="poin"
                                id="poin"
                                class="form-control"
                                min="0"
                                readonly
                                required
                                placeholder="Poin otomatis muncul">

                        </div>


                        <!-- GURU -->
                        <div class="mb-3">

                            <label class="form-label">
                                Nama Guru
                            </label>

                            <input
                                type="text"
                                name="nama_guru"
                                class="form-control"
                                value="<?= e(
                                    $namaGuruLogin
                                ); ?>"
                                readonly
                                required>

                        </div>


                        <!-- KETERANGAN -->
                        <div class="mb-3">

                            <label class="form-label">
                                Keterangan
                            </label>

                            <textarea
                                name="keterangan"
                                class="form-control"
                                rows="3"
                                placeholder="Masukkan keterangan pelanggaran"
                                required><?= e(
                                    $_POST['keterangan'] ?? ''
                                ); ?></textarea>

                        </div>


                        <!-- TINDAKAN -->
                        <div class="mb-3">

                            <label class="form-label">
                                Tindakan
                            </label>

                            <textarea
                                name="tindakan"
                                class="form-control"
                                rows="3"
                                placeholder="Masukkan tindakan yang diberikan"
                                required><?= e(
                                    $_POST['tindakan'] ?? ''
                                ); ?></textarea>

                        </div>


                        <!-- STATUS -->
                        <div class="mb-4">

                            <label class="form-label">
                                Status
                            </label>

                            <select
                                name="status"
                                class="form-select"
                                required>

                                <option value="Diproses">
                                    Diproses
                                </option>

                                <option value="Selesai">
                                    Selesai
                                </option>

                            </select>

                        </div>


                        <!-- TOMBOL -->
                        <button
                            type="submit"
                            name="simpan"
                            class="btn btn-primary"
                            <?= (
                                !$tahun_id
                                ||
                                (
                                    $mode_wali_kelas
                                    && empty($daftarKelas)
                                )
                            )
                                ? 'disabled'
                                : ''; ?>>

                            Simpan

                        </button>

                        <a
                            href="dashboard.php"
                            class="btn btn-secondary">

                            Kembali

                        </a>

                    </form>

                </div>

            </div>

        </main>

    </div>

</div>


<script>

/* ==========================================
   FILTER SISWA BERDASARKAN KELAS
========================================== */

document.addEventListener('DOMContentLoaded', function () {

    const pilihKelas =
        document.getElementById('kelas_id');

    const pilihSiswa =
        document.getElementById('siswa_id');

    const semuaSiswa =
        Array.from(pilihSiswa.options).slice(1);

    function filterSiswa() {

        const kelasDipilih =
            pilihKelas.value;

        pilihSiswa.innerHTML = '';

        const placeholder =
            document.createElement('option');

        placeholder.value = '';

        placeholder.textContent =
            kelasDipilih
                ? '-- Pilih Nama Siswa --'
                : '-- Pilih Kelas Terlebih Dahulu --';

        pilihSiswa.appendChild(placeholder);

        semuaSiswa.forEach(function (option) {

            if (
                option.dataset.kelas === kelasDipilih
                &&
                kelasDipilih !== ''
            ) {

                pilihSiswa.appendChild(
                    option.cloneNode(true)
                );

            }

        });
    }

    pilihKelas.addEventListener(
        'change',
        filterSiswa
    );

    filterSiswa();


    /* ==========================================
       POIN OTOMATIS
    ========================================== */

    const pilihPelanggaran =
        document.getElementById(
            'nama_pelanggaran'
        );

    const inputPoin =
        document.getElementById(
            'poin'
        );

    pilihPelanggaran.addEventListener(
        'change',
        function () {

            const selectedOption =
                this.options[this.selectedIndex];

            if (
                this.value !== ''
                &&
                selectedOption
            ) {

                inputPoin.value =
                    selectedOption.dataset.poin || '';

            } else {

                inputPoin.value = '';

            }

        }
    );

});

</script>


<script
    src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js">
</script>

</body>

</html>