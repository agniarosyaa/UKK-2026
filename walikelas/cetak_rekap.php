
<?php
session_start();

if (!isset($_SESSION['login'])) {
    header("Location: ../login.php");
    exit;
}

include "../config/koneksi.php";

/* ==========================================
   CEK ROLE
========================================== */
$role = strtolower(trim($_SESSION['role'] ?? ''));

if ($role !== 'wali_kelas') {
    die("Anda tidak memiliki akses ke halaman ini.");
}

/* ==========================================
   AMBIL ID USER LOGIN
========================================== */
$user_id = $_SESSION['user_id']
    ?? $_SESSION['id_user']
    ?? $_SESSION['id']
    ?? null;

if (!$user_id) {
    die("ID akun tidak ditemukan. Silakan login kembali.");
}

$user_id = (int) $user_id;

/* ==========================================
   AMBIL DATA GURU
========================================== */
$stmtGuru = $koneksi->prepare("
    SELECT id, nama
    FROM t_guru
    WHERE user_id = ?
    LIMIT 1
");

if (!$stmtGuru) {
    die("Query data guru gagal: " . htmlspecialchars($koneksi->error));
}

$stmtGuru->bind_param("i", $user_id);
$stmtGuru->execute();

$dataGuru = $stmtGuru->get_result()->fetch_assoc();
$stmtGuru->close();

if (!$dataGuru) {
    die("Data guru tidak ditemukan.");
}

$guru_id = (int) $dataGuru['id'];
$nama_wali = $dataGuru['nama'];

/* ==========================================
   AMBIL TAHUN AJARAN AKTIF
========================================== */
$stmtTahun = $koneksi->prepare("
    SELECT id, nama
    FROM t_tahun_ajaran
    WHERE status_aktif = 1
    ORDER BY id DESC
    LIMIT 1
");

if (!$stmtTahun) {
    die("Query tahun ajaran gagal: " . htmlspecialchars($koneksi->error));
}

$stmtTahun->execute();

$tahun = $stmtTahun->get_result()->fetch_assoc();
$stmtTahun->close();

if (!$tahun) {
    die("Tahun ajaran aktif belum tersedia.");
}

$tahun_id = (int) $tahun['id'];

/* ==========================================
   AMBIL KELAS WALI KELAS
========================================== */
$stmtKelas = $koneksi->prepare("
    SELECT
        wk.kelas_id,
        k.nama AS nama_kelas
    FROM t_wali_kelas wk
    INNER JOIN t_kelas k
        ON k.id = wk.kelas_id
    WHERE wk.guru_id = ?
      AND wk.tahun_ajaran_id = ?
      AND wk.status_aktif = 1
    ORDER BY wk.id DESC
    LIMIT 1
");

if (!$stmtKelas) {
    die("Query kelas wali gagal: " . htmlspecialchars($koneksi->error));
}

$stmtKelas->bind_param("ii", $guru_id, $tahun_id);
$stmtKelas->execute();

$dataKelas = $stmtKelas->get_result()->fetch_assoc();
$stmtKelas->close();

if (!$dataKelas) {
    die("Anda belum memiliki penugasan wali kelas yang aktif.");
}

$kelas_id = (int) $dataKelas['kelas_id'];
$nama_kelas = $dataKelas['nama_kelas'];

/* ==========================================
   AMBIL DATA PELANGGARAN
   NAMA PELANGGARAN DIAMBIL DARI
   TABEL t_pelanggaran
========================================== */
$stmtRekap = $koneksi->prepare("
    SELECT
        ps.id,
        ps.tanggal,
        s.nama AS nama_siswa,
        k.nama AS nama_kelas,
        p.nama AS nama_pelanggaran,
        ps.poin,
        ps.keterangan,
        ps.tindakan,
        ps.status

    FROM t_pelanggaran_siswa ps

    INNER JOIN t_siswa s
        ON s.id = ps.siswa_id
        AND s.status_aktif = 1

    INNER JOIN t_kelas_siswa ks
        ON ks.siswa_id = ps.siswa_id
        AND ks.tahun_ajaran_id = ps.tahun_ajaran_id
        AND ks.kelas_id = ?
        AND ks.status_aktif = 1

    INNER JOIN t_kelas k
        ON k.id = ks.kelas_id

    INNER JOIN t_tahun_ajaran ta
        ON ta.id = ks.tahun_ajaran_id
        AND ta.status_aktif = 1

    LEFT JOIN t_pelanggaran p
        ON p.id = ps.pelanggaran_id

    WHERE ps.tahun_ajaran_id = ?

    ORDER BY ps.tanggal DESC, ps.id DESC
");

if (!$stmtRekap) {
    die("Query rekap gagal: " . htmlspecialchars($koneksi->error));
}

$stmtRekap->bind_param("ii", $kelas_id, $tahun_id);
$stmtRekap->execute();

$resultRekap = $stmtRekap->get_result();

$data_rekap = [];
$total_pelanggaran = 0;
$total_poin = 0;

while ($row = $resultRekap->fetch_assoc()) {
    $data_rekap[] = $row;
    $total_pelanggaran++;
    $total_poin += (int) $row['poin'];
}

$stmtRekap->close();

/* ==========================================
   FORMAT DATA
========================================== */
$tanggal_cetak = date('d-m-Y');

function e($value)
{
    return htmlspecialchars(
        (string) ($value ?? ''),
        ENT_QUOTES,
        'UTF-8'
    );
}
?>

<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Cetak Rekap Pelanggaran</title>

    <!-- BOOTSTRAP -->
    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >

    <style>
        /* LAPORAN */
        .laporan {
            background: #fff;
            border: 1px solid #dee2e6;
            border-radius: 7px;
            padding: 28px;
        }

        .judul-laporan {
            text-align: center;
            margin-bottom: 20px;
        }

        .judul-laporan h4 {
            font-weight: bold;
        }

        .table th {
            vertical-align: middle;
            white-space: nowrap;
        }

        .table td {
            vertical-align: middle;
        }

        /* CETAK */
        @media print {

            body {
                background: white !important;
            }

            aside,
            .no-print {
                display: none !important;
            }

            main {
                width: 100% !important;
                margin: 0 !important;
                padding: 0 !important;
            }

            .laporan {
                border: none !important;
                padding: 0 !important;
            }

            .table {
                font-size: 11px;
            }

            .table thead th {
                background: #212529 !important;
                color: white !important;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }

            @page {
                size: landscape;
                margin: 1cm;
            }
        }
    </style>

</head>

<body>

<div class="container-fluid">

    <div class="row">

        <!-- SIDEBAR -->
        <aside class="col-md-3 col-lg-2 bg-dark min-vh-100 p-3 position-fixed top-0 start-0">

            <h5 class="text-white mb-4">
                Sistem Pelanggaran
            </h5>

            <ul class="nav nav-pills flex-column gap-2">

                <li class="nav-item">
                    <a href="../dashboard.php" class="nav-link text-white">
                        Dashboard
                    </a>
                </li>

                <li class="nav-item">
                    <a href="data_siswa.php" class="nav-link text-white">
                        Data Siswa
                    </a>
                </li>

                <li class="nav-item">
                    <a href="rekap_pelanggaran.php" class="nav-link text-white">
                        Rekap Pelanggaran
                    </a>
                </li>

                <li class="nav-item">
                    <a href="cetak_rekap.php" class="nav-link active">
                        Cetak Rekap
                    </a>
                </li>

                <hr class="text-secondary">

                <li class="nav-item">
                    <a href="../about_me.php" class="nav-link text-white">
                        About Me
                    </a>
                </li>

                <li class="nav-item">
                    <a
                        href="../logout.php"
                        class="nav-link text-danger"
                        onclick="return confirm('Yakin ingin logout?')"
                    >
                        Logout
                    </a>
                </li>

            </ul>

        </aside>

        <!-- KONTEN UTAMA -->
        <main class="col-md-9 col-lg-10 offset-md-3 offset-lg-2 p-4">

            <!-- JUDUL HALAMAN -->
            <h2 class="mb-2">
                Cetak Rekap Pelanggaran
            </h2>

            <p class="text-muted">
                Selamat datang,
                <strong><?= e($nama_wali); ?></strong>
            </p>

            <!-- TOMBOL -->
            <div class="d-flex gap-2 mb-3 no-print">

                <a
                    href="rekap_pelanggaran.php"
                    class="btn btn-secondary"
                >
                    Kembali
                </a>

                <button
                    type="button"
                    onclick="window.print()"
                    class="btn btn-primary"
                >
                    Cetak Laporan
                </button>

            </div>

            <!-- LAPORAN -->
            <div class="laporan">

                <!-- JUDUL LAPORAN -->
                <div class="judul-laporan">

                    <h4>
                        LAPORAN REKAP PELANGGARAN SISWA
                    </h4>

                    <h6>
                        SISTEM PELANGGARAN SISWA
                    </h6>

                    <hr>

                </div>

                <!-- IDENTITAS -->
                <div class="row mb-3">

                    <div class="col-md-6">

                        <p class="mb-1">
                            <strong>Wali Kelas:</strong>
                            <?= e($nama_wali); ?>
                        </p>

                        <p class="mb-1">
                            <strong>Kelas:</strong>
                            <?= e($nama_kelas); ?>
                        </p>

                        <p class="mb-1">
                            <strong>Tahun Ajaran:</strong>
                            <?= e($tahun['nama']); ?>
                        </p>

                    </div>

                    <div class="col-md-6 text-md-end">

                        <p>
                            <strong>Tanggal Cetak:</strong>
                            <?= e($tanggal_cetak); ?>
                        </p>

                    </div>

                </div>

                <!-- TOTAL PELANGGARAN DAN POIN -->
                <div class="row g-3 mb-4">

                    <div class="col-12 col-md-6">

                        <div class="card shadow-sm h-100">

                            <div class="card-body">

                                <h6 class="text-muted mb-2">
                                    Total Pelanggaran
                                </h6>

                                <h3 class="mb-0">
                                    <?= $total_pelanggaran; ?>
                                </h3>

                            </div>

                        </div>

                    </div>

                    <div class="col-12 col-md-6">

                        <div class="card shadow-sm h-100">

                            <div class="card-body">

                                <h6 class="text-muted mb-2">
                                    Total Poin
                                </h6>

                                <h3 class="mb-0">
                                    <?= $total_poin; ?>
                                </h3>

                            </div>

                        </div>

                    </div>

                </div>

                <!-- TABEL DATA -->
                <div class="table-responsive">

                    <table class="table table-bordered table-striped table-hover">

                        <thead class="table-dark">

                            <tr>
                                <th>No</th>
                                <th>Tanggal</th>
                                <th>Nama Siswa</th>
                                <th>Kelas</th>
                                <th>Jenis Pelanggaran</th>
                                <th>Poin</th>
                                <th>Keterangan</th>
                                <th>Tindakan</th>
                                <th>Status</th>
                            </tr>

                        </thead>

                        <tbody>

                            <?php if (count($data_rekap) > 0): ?>

                                <?php $no = 1; ?>

                                <?php foreach ($data_rekap as $row): ?>

                                    <tr>

                                        <td>
                                            <?= $no++; ?>
                                        </td>

                                        <td>
                                            <?= !empty($row['tanggal'])
                                                ? e(date('d-m-Y', strtotime($row['tanggal'])))
                                                : '-'; ?>
                                        </td>

                                        <td>
                                            <?= e($row['nama_siswa']); ?>
                                        </td>

                                        <td>
                                            <?= e($row['nama_kelas']); ?>
                                        </td>

                                        <td>
                                            <?= e($row['nama_pelanggaran'] ?: '-'); ?>
                                        </td>

                                        <td class="text-center">
                                            <?= (int) $row['poin']; ?>
                                        </td>

                                        <td>
                                            <?= e($row['keterangan']); ?>
                                        </td>

                                        <td>
                                            <?= e($row['tindakan']); ?>
                                        </td>

                                        <td>
                                            <?php if (($row['status'] ?? '') === 'Selesai'): ?>

                                                <span class="badge bg-success">
                                                    Selesai
                                                </span>

                                            <?php elseif (($row['status'] ?? '') === 'Diproses'): ?>

                                                <span class="badge bg-warning text-dark">
                                                    Diproses
                                                </span>

                                            <?php else: ?>

                                                <?= e($row['status']); ?>

                                            <?php endif; ?>
                                        </td>

                                    </tr>

                                <?php endforeach; ?>

                            <?php else: ?>

                                <tr>
                                    <td colspan="9" class="text-center">
                                        Belum ada data pelanggaran untuk kelas
                                        <?= e($nama_kelas); ?>.
                                    </td>
                                </tr>

                            <?php endif; ?>

                        </tbody>

                        <?php if ($total_pelanggaran > 0): ?>

                            <tfoot>

                                <tr class="fw-bold">

                                    <td colspan="5" class="text-end">
                                        Total Poin
                                    </td>

                                    <td class="text-center">
                                        <?= $total_poin; ?>
                                    </td>

                                    <td colspan="3"></td>

                                </tr>

                            </tfoot>

                        <?php endif; ?>

                    </table>

                </div>

                <!-- TANDA TANGAN -->
                <div class="row mt-5">

                    <div class="col-7"></div>

                    <div class="col-5 text-center">

                        <p>
                            Mengetahui,<br>
                            Wali Kelas <?= e($nama_kelas); ?>
                        </p>

                        <br><br><br>

                        <p>
                            <strong>
                                <?= e($nama_wali); ?>
                            </strong>
                        </p>

                    </div>

                </div>

            </div>

        </main>

    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
