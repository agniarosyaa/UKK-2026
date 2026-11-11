
<?php
session_start();

if (!isset($_SESSION['login']) || $_SESSION['login'] !== true) {
    header("Location: ../login.php");
    exit;
}

include "../config/koneksi.php";

/* =====================================
   CEK ROLE
   ADMIN TETAP ADMIN
===================================== */
$role = strtolower(trim($_SESSION['role'] ?? ''));
$role = str_replace([' ', '-'], '_', $role);

if (!in_array($role, ['guru', 'wali_kelas'], true)) {
    exit("Anda tidak memiliki akses ke halaman ini.");
}

/* =====================================
   AMBIL ID USER
===================================== */
$user_id = (int) (
    $_SESSION['user_id']
    ?? $_SESSION['id_user']
    ?? $_SESSION['id']
    ?? 0
);

/* =====================================
   AMBIL DATA GURU
===================================== */
$guru = null;

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
    $guru = $stmtGuru->get_result()->fetch_assoc();
    $stmtGuru->close();
}

/* FALLBACK ID GURU DARI SESSION */
if (!$guru && !empty($_SESSION['guru_id'])) {
    $guru_id_session = (int) $_SESSION['guru_id'];

    $stmtGuru = $koneksi->prepare("
        SELECT id, nama
        FROM t_guru
        WHERE id = ?
        LIMIT 1
    ");

    if (!$stmtGuru) {
        exit("Query guru gagal: " . htmlspecialchars($koneksi->error));
    }

    $stmtGuru->bind_param("i", $guru_id_session);
    $stmtGuru->execute();
    $guru = $stmtGuru->get_result()->fetch_assoc();
    $stmtGuru->close();
}

if (!$guru) {
    exit("Data guru tidak ditemukan.");
}

$guru_id = (int) $guru['id'];
$nama_guru = $guru['nama'];

/* =====================================
   AMBIL KELAS TANGGUNG JAWAB
===================================== */
$stmtKelas = $koneksi->prepare("
    SELECT DISTINCT
        wk.kelas_id,
        wk.tahun_ajaran_id,
        k.nama AS nama_kelas
    FROM t_wali_kelas wk
    INNER JOIN t_kelas k
        ON k.id = wk.kelas_id
    INNER JOIN t_tahun_ajaran ta
        ON ta.id = wk.tahun_ajaran_id
    WHERE wk.guru_id = ?
      AND wk.status_aktif = 1
      AND ta.status_aktif = 1
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

$kelas_wali = [];
$kelas_id = [];

while ($row = $resultKelas->fetch_assoc()) {
    $kelas_wali[] = $row;
    $kelas_id[] = (int) $row['kelas_id'];
}

$stmtKelas->close();

/* =====================================
   CEK PENUGASAN
===================================== */
if (empty($kelas_wali)) {
    exit("Anda belum memiliki penugasan sebagai Wali Kelas yang aktif.");
}

/* =====================================
   FILTER LAPORAN
===================================== */
$filter_kelas = (int) ($_GET['kelas'] ?? 0);
$tanggal_awal = trim($_GET['tanggal_awal'] ?? '');
$tanggal_akhir = trim($_GET['tanggal_akhir'] ?? '');
$filter_status = trim($_GET['status'] ?? '');

/* VALIDASI KELAS */
if (
    $filter_kelas > 0 &&
    !in_array($filter_kelas, $kelas_id, true)
) {
    $filter_kelas = 0;
}

/* VALIDASI TANGGAL */
if (
    $tanggal_awal !== '' &&
    !preg_match('/^\d{4}-\d{2}-\d{2}$/', $tanggal_awal)
) {
    $tanggal_awal = '';
}

if (
    $tanggal_akhir !== '' &&
    !preg_match('/^\d{4}-\d{2}-\d{2}$/', $tanggal_akhir)
) {
    $tanggal_akhir = '';
}

if (
    $tanggal_awal !== '' &&
    $tanggal_akhir !== '' &&
    $tanggal_awal > $tanggal_akhir
) {
    $tanggal_awal = '';
    $tanggal_akhir = '';
}

/* VALIDASI STATUS */
if (!in_array($filter_status, ['', 'Diproses', 'Selesai'], true)) {
    $filter_status = '';
}

/* =====================================
   QUERY LAPORAN PELANGGARAN
===================================== */
$data_laporan = [];

$total_pelanggaran = 0;
$total_poin = 0;
$total_selesai = 0;
$total_diproses = 0;

if (!empty($kelas_wali)) {

    $kondisi_kelas = [];
    $params = [];
    $types = '';

    foreach ($kelas_wali as $kelas) {

        $kondisi_kelas[] = "
            (
                ps.kelas_id = ?
                AND ps.tahun_ajaran_id = ?
            )
        ";

        $params[] = (int) $kelas['kelas_id'];
        $params[] = (int) $kelas['tahun_ajaran_id'];

        $types .= "ii";
    }

    $sql = "
        SELECT
            ps.id,
            ps.tanggal,
            s.nis,
            s.nisn,
            s.nama AS nama_siswa,
            k.nama AS nama_kelas,

            COALESCE(
                NULLIF(p.nama, ''),
                NULLIF(ps.nama_pelanggaran, ''),
                '-'
            ) AS nama_pelanggaran,

            ps.poin,
            ps.keterangan,
            ps.tindakan,
            ps.status,
            ps.nama_guru

        FROM t_pelanggaran_siswa ps

        INNER JOIN t_siswa s
            ON s.id = ps.siswa_id
            AND s.status_aktif = 1

        INNER JOIN t_kelas_siswa ks
            ON ks.siswa_id = ps.siswa_id
            AND ks.kelas_id = ps.kelas_id
            AND ks.tahun_ajaran_id = ps.tahun_ajaran_id
            AND ks.status_aktif = 1

        INNER JOIN t_kelas k
            ON k.id = ps.kelas_id

        INNER JOIN t_tahun_ajaran ta
            ON ta.id = ps.tahun_ajaran_id
            AND ta.status_aktif = 1

        LEFT JOIN t_pelanggaran p
            ON p.id = ps.pelanggaran_id

        WHERE (
            " . implode(" OR ", $kondisi_kelas) . "
        )
    ";

    /* FILTER KELAS */
    if ($filter_kelas > 0) {
        $sql .= " AND ps.kelas_id = ?";

        $params[] = $filter_kelas;
        $types .= "i";
    }

    /* FILTER TANGGAL AWAL */
    if ($tanggal_awal !== '') {
        $sql .= " AND ps.tanggal >= ?";

        $params[] = $tanggal_awal;
        $types .= "s";
    }

    /* FILTER TANGGAL AKHIR */
    if ($tanggal_akhir !== '') {
        $sql .= " AND ps.tanggal <= ?";

        $params[] = $tanggal_akhir;
        $types .= "s";
    }

    /* FILTER STATUS */
    if ($filter_status !== '') {
        $sql .= " AND ps.status = ?";

        $params[] = $filter_status;
        $types .= "s";
    }

    $sql .= "
        ORDER BY ps.tanggal DESC, ps.id DESC
    ";

    $stmtData = $koneksi->prepare($sql);

    if (!$stmtData) {
        exit("Query laporan gagal: " . htmlspecialchars($koneksi->error));
    }

    $stmtData->bind_param($types, ...$params);
    $stmtData->execute();

    $resultData = $stmtData->get_result();

    while ($row = $resultData->fetch_assoc()) {

        $data_laporan[] = $row;

        $total_pelanggaran++;
        $total_poin += (int) $row['poin'];

        if ($row['status'] === 'Selesai') {
            $total_selesai++;
        } elseif ($row['status'] === 'Diproses') {
            $total_diproses++;
        }
    }

    $stmtData->close();
}

/* =====================================
   FORMAT TANGGAL
===================================== */
function tanggalIndonesia($tanggal)
{
    if (empty($tanggal)) {
        return '-';
    }

    return date('d-m-Y', strtotime($tanggal));
}
?>

<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Laporan Pelanggaran Siswa</title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >

    <link
        href="https://cdn.datatables.net/2.3.4/css/dataTables.bootstrap5.min.css"
        rel="stylesheet"
    >

    <style>
        @media print {

            .sidebar,
            .filter-laporan,
            .btn-cetak,
            .dataTables_length,
            .dataTables_filter,
            .dataTables_info,
            .dataTables_paginate {
                display: none !important;
            }

            main {
                margin-left: 0 !important;
                width: 100% !important;
                padding: 10px !important;
            }

            .container-fluid {
                padding: 0;
            }

            .card {
                box-shadow: none !important;
            }

            table {
                width: 100% !important;
                font-size: 10px;
            }

            body {
                font-size: 12px;
            }
        }
    </style>

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
            <a href="../dashboard.php" class="nav-link text-white">
                Dashboard
            </a>
        </li>

        <li class="nav-item">
            <a href="../catat_pelanggaran.php" class="nav-link text-white">
                Catat Pelanggaran
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
            <a href="laporan.php" class="nav-link active">
                Laporan
            </a>
        </li>

        <li class="nav-item">
            <a href="../riwayat.php" class="nav-link text-white">
                Riwayat
            </a>
        </li>

        <li class="nav-item">
            <a href="../rekap_poin.php" class="nav-link text-white">
                Rekap Poin
            </a>
        </li>

        <hr class="text-secondary">

        <li class="nav-item">
            <a href="../about_me.php" class="nav-link text-white">
                About Me
            </a>
        </li>

        <li class="nav-item">
            <a href="../logout.php"
               class="nav-link text-danger"
               onclick="return confirm('Yakin ingin logout?')">
                Logout
            </a>
        </li>

    </ul>

</aside>


        <!-- KONTEN -->
   <main class="col-md-9 col-lg-10 offset-md-3 offset-lg-2 p-4">
    
            <h2 class="mb-2">
                Laporan Pelanggaran Siswa
            </h2>

            <p class="text-muted">
                Selamat datang,
                <strong><?= htmlspecialchars($nama_guru) ?></strong>
            </p>

            <!-- KELAS TANGGUNG JAWAB -->
            <div class="card shadow-sm mb-4">

                <div class="card-body">

                    <h6 class="text-muted mb-2">
                        Kelas Tanggung Jawab
                    </h6>

                    <strong>
                        <?= htmlspecialchars(
                            implode(', ', array_column($kelas_wali, 'nama_kelas'))
                        ) ?>
                    </strong>

                </div>

            </div>

            <!-- RINGKASAN LAPORAN -->
            <div class="row g-3 mb-4">

                <div class="col-12 col-md-6 col-lg-3">

                    <div class="card shadow-sm h-100">

                        <div class="card-body">

                            <h6 class="text-muted">
                                Total Pelanggaran
                            </h6>

                            <h3 class="mb-0">
                                <?= $total_pelanggaran ?>
                            </h3>

                        </div>

                    </div>

                </div>

                <div class="col-12 col-md-6 col-lg-3">

                    <div class="card shadow-sm h-100">

                        <div class="card-body">

                            <h6 class="text-muted">
                                Total Poin
                            </h6>

                            <h3 class="mb-0">
                                <?= $total_poin ?>
                            </h3>

                        </div>

                    </div>

                </div>

                <div class="col-12 col-md-6 col-lg-3">

                    <div class="card shadow-sm h-100">

                        <div class="card-body">

                            <h6 class="text-muted">
                                Selesai
                            </h6>

                            <h3 class="mb-0 text-success">
                                <?= $total_selesai ?>
                            </h3>

                        </div>

                    </div>

                </div>

                <div class="col-12 col-md-6 col-lg-3">

                    <div class="card shadow-sm h-100">

                        <div class="card-body">

                            <h6 class="text-muted">
                                Diproses
                            </h6>

                            <h3 class="mb-0 text-warning">
                                <?= $total_diproses ?>
                            </h3>

                        </div>

                    </div>

                </div>

            </div>

            <!-- FILTER LAPORAN -->
            <div class="card shadow-sm mb-4 filter-laporan">

                <div class="card-body">

                    <h5 class="mb-3">
                        Filter Laporan
                    </h5>

                    <form method="GET" class="row g-3">

                        <div class="col-md-6">

                            <label class="form-label">
                                Pilih Kelas
                            </label>

                            <select name="kelas" class="form-select">

                                <option value="0">
                                    Semua Kelas Tanggung Jawab
                                </option>

                                <?php foreach ($kelas_wali as $kelas): ?>

                                    <option
                                        value="<?= (int) $kelas['kelas_id'] ?>"
                                        <?= $filter_kelas == $kelas['kelas_id'] ? 'selected' : '' ?>
                                    >
                                        <?= htmlspecialchars($kelas['nama_kelas']) ?>
                                    </option>

                                <?php endforeach; ?>

                            </select>

                        </div>

                        <div class="col-md-6">

                            <label class="form-label">
                                Status Pelanggaran
                            </label>

                            <select name="status" class="form-select">

                                <option value="">
                                    Semua Status
                                </option>

                                <option
                                    value="Diproses"
                                    <?= $filter_status === 'Diproses' ? 'selected' : '' ?>
                                >
                                    Diproses
                                </option>

                                <option
                                    value="Selesai"
                                    <?= $filter_status === 'Selesai' ? 'selected' : '' ?>
                                >
                                    Selesai
                                </option>

                            </select>

                        </div>

                        <div class="col-md-6">

                            <label class="form-label">
                                Tanggal Awal
                            </label>

                            <input
                                type="date"
                                name="tanggal_awal"
                                class="form-control"
                                value="<?= htmlspecialchars($tanggal_awal) ?>"
                            >

                        </div>

                        <div class="col-md-6">

                            <label class="form-label">
                                Tanggal Akhir
                            </label>

                            <input
                                type="date"
                                name="tanggal_akhir"
                                class="form-control"
                                value="<?= htmlspecialchars($tanggal_akhir) ?>"
                            >

                        </div>

                        <div class="col-12">

                            <button type="submit" class="btn btn-primary">
                                Tampilkan Laporan
                            </button>

                            <a
                                href="laporan.php"
                                class="btn btn-secondary"
                            >
                                Reset
                            </a>

                        </div>

                    </form>

                </div>

            </div>

            <!-- TABEL LAPORAN -->
            <div class="card shadow-sm mb-4">

                <div class="card-body">

                    <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">

                        <h5 class="mb-0">
                            Data Laporan Pelanggaran
                        </h5>
<a
    href="cetak_rekap.php?<?= htmlspecialchars(http_build_query($_GET)) ?>"
    target="_blank"
    class="btn btn-success btn-cetak"
>
    Cetak Rekap
</a>
                    </div>

                    <div class="mb-3 text-muted">

                        <small>
                            Periode:
                            <?= $tanggal_awal !== '' ? tanggalIndonesia($tanggal_awal) : 'Semua tanggal' ?>
                            sampai
                            <?= $tanggal_akhir !== '' ? tanggalIndonesia($tanggal_akhir) : 'Sekarang' ?>
                        </small>

                    </div>

                    <div class="table-responsive">

                        <table
                            id="tabelLaporan"
                            class="table table-bordered table-striped table-hover"
                        >

                            <thead class="table-dark">

                                <tr>
                                    <th>No</th>
                                    <th>Tanggal</th>
                                    <th>NIS</th>
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

                                <?php $no = 1; ?>

                                <?php foreach ($data_laporan as $data): ?>

                                    <tr>

                                        <td><?= $no++ ?></td>

                                        <td>
                                            <?= tanggalIndonesia($data['tanggal']) ?>
                                        </td>

                                        <td>
                                            <?= htmlspecialchars($data['nis'] ?? '-') ?>
                                        </td>

                                        <td>
                                            <?= htmlspecialchars($data['nama_siswa'] ?? '-') ?>
                                        </td>

                                        <td>
                                            <?= htmlspecialchars($data['nama_kelas'] ?? '-') ?>
                                        </td>

                                        <td>
                                            <?= htmlspecialchars($data['nama_pelanggaran'] ?? '-') ?>
                                        </td>

                                        <td>
                                            <?= (int) $data['poin'] ?>
                                        </td>

                                        <td>
                                            <?= htmlspecialchars($data['keterangan'] ?? '-') ?>
                                        </td>

                                        <td>
                                            <?= htmlspecialchars($data['tindakan'] ?? '-') ?>
                                        </td>

                                        <td>

                                            <?php if (($data['status'] ?? '') === 'Selesai'): ?>

                                                <span class="badge bg-success">
                                                    Selesai
                                                </span>

                                            <?php elseif (($data['status'] ?? '') === 'Diproses'): ?>

                                                <span class="badge bg-warning text-dark">
                                                    Diproses
                                                </span>

                                            <?php else: ?>

                                                <span class="badge bg-secondary">
                                                    <?= htmlspecialchars($data['status'] ?? '-') ?>
                                                </span>

                                            <?php endif; ?>

                                        </td>

                                    </tr>

                                <?php endforeach; ?>

                            </tbody>

                        </table>

                    </div>

                </div>

            </div>

        </main>

    </div>

</div>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>

<script src="https://cdn.datatables.net/2.3.4/js/dataTables.min.js"></script>

<script src="https://cdn.datatables.net/2.3.4/js/dataTables.bootstrap5.min.js"></script>

<script>
$(document).ready(function () {

    $('#tabelLaporan').DataTable({
        pageLength: 10,
        lengthMenu: [5, 10, 25, 50],

        language: {
            search: "Cari:",
            lengthMenu: "Tampilkan _MENU_ data",
            info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
            infoEmpty: "Tidak ada data",
            zeroRecords: "Data laporan tidak ditemukan",
            emptyTable: "Belum ada laporan pelanggaran",

            paginate: {
                first: "Pertama",
                last: "Terakhir",
                next: "Selanjutnya",
                previous: "Sebelumnya"
            }
        }
    });

});
</script>

</body>
</html>
