
<?php
session_start();

if (!isset($_SESSION['login']) || $_SESSION['login'] !== true) {
    header("Location: ../login.php");
    exit;
}

include "../config/koneksi.php";

// ==========================================
// CEK ROLE
// HALAMAN KHUSUS WALI KELAS
// ==========================================
$role = strtolower(trim($_SESSION['role'] ?? ''));

if ($role !== 'wali_kelas') {
    exit("Anda tidak memiliki akses ke halaman ini.");
}

// ==========================================
// USER LOGIN
// ==========================================
$user_id = (int) (
    $_SESSION['user_id']
    ?? $_SESSION['id_user']
    ?? 0
);

if ($user_id <= 0) {
    exit("ID user tidak ditemukan. Silakan login kembali.");
}

// ==========================================
// AMBIL NAMA WALI KELAS
// ==========================================
$nama_wali = $_SESSION['nama'] ?? 'Wali Kelas';

$stmtNama = mysqli_prepare($koneksi, "
    SELECT nama
    FROM t_guru
    WHERE user_id = ?
    LIMIT 1
");

if ($stmtNama) {
    mysqli_stmt_bind_param($stmtNama, "i", $user_id);
    mysqli_stmt_execute($stmtNama);

    $hasilNama = mysqli_stmt_get_result($stmtNama);

    if ($dataNama = mysqli_fetch_assoc($hasilNama)) {
        $nama_wali = $dataNama['nama'];
    }

    mysqli_stmt_close($stmtNama);
}

// ==========================================
// AMBIL KELAS TANGGUNG JAWAB WALI KELAS
// ==========================================
$queryKelas = "
    SELECT DISTINCT
        k.id,
        k.nama

    FROM t_wali_kelas wk

    INNER JOIN t_guru g
        ON g.id = wk.guru_id

    INNER JOIN t_kelas k
        ON k.id = wk.kelas_id

    WHERE g.user_id = ?
      AND wk.status_aktif = 1

    ORDER BY k.nama ASC
";

$stmtKelas = mysqli_prepare($koneksi, $queryKelas);

if (!$stmtKelas) {
    die("Gagal mengambil kelas: " . mysqli_error($koneksi));
}

mysqli_stmt_bind_param($stmtKelas, "i", $user_id);
mysqli_stmt_execute($stmtKelas);

$resultKelas = mysqli_stmt_get_result($stmtKelas);

$daftar_kelas = [];

while ($kelas = mysqli_fetch_assoc($resultKelas)) {
    $daftar_kelas[] = $kelas;
}

mysqli_stmt_close($stmtKelas);

// ==========================================
// FILTER LAPORAN
// ==========================================
$filter_kelas = isset($_GET['kelas'])
    ? (int) $_GET['kelas']
    : 0;

$filter_status = trim($_GET['status'] ?? '');

$tanggal_awal = trim($_GET['tanggal_awal'] ?? '');
$tanggal_akhir = trim($_GET['tanggal_akhir'] ?? '');

// ==========================================
// VALIDASI KELAS YANG DIPILIH
// ==========================================
$id_kelas_valid = array_column($daftar_kelas, 'id');

if ($filter_kelas > 0 && !in_array($filter_kelas, $id_kelas_valid)) {
    $filter_kelas = 0;
}

// ==========================================
// VALIDASI STATUS
// ==========================================
if (!in_array($filter_status, ['', 'Diproses', 'Selesai'], true)) {
    $filter_status = '';
}

// ==========================================
// VALIDASI TANGGAL
// ==========================================
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
    $tanggal_akhir = '';
}

// ==========================================
// QUERY LAPORAN WALI KELAS
// HANYA KELAS TANGGUNG JAWAB
// ==========================================
$query = "
    SELECT
        ps.id,

        COALESCE(
            NULLIF(TRIM(s.nis), ''),
            '-'
        ) AS nis,

        COALESCE(
            NULLIF(NULLIF(TRIM(ps.nama_siswa), ''), '0'),
            NULLIF(TRIM(s.nama), ''),
            '-'
        ) AS nama_siswa,

        COALESCE(
            NULLIF(NULLIF(TRIM(ps.nama_kelas), ''), '0'),
            NULLIF(TRIM(k.nama), ''),
            '-'
        ) AS nama_kelas,

        COALESCE(
            NULLIF(NULLIF(TRIM(p.nama), ''), '0'),
            NULLIF(NULLIF(TRIM(ps.nama_pelanggaran), ''), '0'),
            '-'
        ) AS nama_pelanggaran,

        ps.tanggal,
        ps.poin,
        ps.keterangan,
        ps.tindakan,
        ps.status

    FROM t_pelanggaran_siswa ps

    LEFT JOIN t_siswa s
        ON s.id = ps.siswa_id

    LEFT JOIN t_kelas k
        ON k.id = ps.kelas_id

    LEFT JOIN t_pelanggaran p
        ON p.id = ps.pelanggaran_id

    WHERE EXISTS (
        SELECT 1
        FROM t_wali_kelas wk

        INNER JOIN t_guru g
            ON g.id = wk.guru_id

        WHERE g.user_id = ?
          AND wk.kelas_id = ps.kelas_id
          AND wk.tahun_ajaran_id = ps.tahun_ajaran_id
          AND wk.status_aktif = 1
    )
";

$types = "i";
$params = [$user_id];

// ==========================================
// FILTER KELAS
// ==========================================
if ($filter_kelas > 0) {
    $query .= " AND ps.kelas_id = ? ";
    $types .= "i";
    $params[] = $filter_kelas;
}

// ==========================================
// FILTER STATUS
// ==========================================
if ($filter_status !== '') {
    $query .= " AND LOWER(TRIM(ps.status)) = LOWER(?) ";
    $types .= "s";
    $params[] = $filter_status;
}

// ==========================================
// FILTER TANGGAL AWAL
// ==========================================
if ($tanggal_awal !== '') {
    $query .= " AND ps.tanggal >= ? ";
    $types .= "s";
    $params[] = $tanggal_awal;
}

// ==========================================
// FILTER TANGGAL AKHIR
// ==========================================
if ($tanggal_akhir !== '') {
    $query .= " AND ps.tanggal <= ? ";
    $types .= "s";
    $params[] = $tanggal_akhir;
}

$query .= "
    ORDER BY ps.tanggal DESC, ps.id DESC
";

// ==========================================
// EKSEKUSI QUERY
// ==========================================
$stmt = mysqli_prepare($koneksi, $query);

if (!$stmt) {
    die("Gagal menyiapkan laporan: " . mysqli_error($koneksi));
}

mysqli_stmt_bind_param($stmt, $types, ...$params);
mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);

// ==========================================
// HITUNG TOTAL LAPORAN
// ==========================================
$rows = [];

$total_data = 0;
$total_poin = 0;
$total_selesai = 0;
$total_diproses = 0;

while ($row = mysqli_fetch_assoc($result)) {

    $rows[] = $row;

    $total_data++;

    $total_poin += (int) ($row['poin'] ?? 0);

    $status = strtolower(trim($row['status'] ?? ''));

    if ($status === 'selesai') {
        $total_selesai++;
    } elseif ($status === 'diproses') {
        $total_diproses++;
    }
}

// ==========================================
// NAMA KELAS TERPILIH
// ==========================================
$nama_kelas_terpilih = "Semua Kelas Tanggung Jawab";

foreach ($daftar_kelas as $kelas) {
    if ((int)$kelas['id'] === $filter_kelas) {
        $nama_kelas_terpilih = $kelas['nama'];
        break;
    }
}

// ==========================================
// PERIODE LAPORAN
// ==========================================
if ($tanggal_awal !== '' && $tanggal_akhir !== '') {
    $periode = date('d-m-Y', strtotime($tanggal_awal))
        . " sampai "
        . date('d-m-Y', strtotime($tanggal_akhir));
} elseif ($tanggal_awal !== '') {
    $periode = "Mulai " . date('d-m-Y', strtotime($tanggal_awal));
} elseif ($tanggal_akhir !== '') {
    $periode = "Sampai " . date('d-m-Y', strtotime($tanggal_akhir));
} else {
    $periode = "Semua tanggal";
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Laporan Pelanggaran Siswa</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css"
          rel="stylesheet">

    <link href="https://cdn.datatables.net/2.3.4/css/dataTables.bootstrap5.min.css"
          rel="stylesheet">

    <style>
        body {
            background: #f8f9fa;
        }

        .sidebar {
            width: 250px;
            z-index: 1000;
        }

        .main-content {
            margin-left: 250px;
        }

        @media (max-width: 767.98px) {
            .sidebar {
                width: 100%;
                position: relative !important;
            }

            .main-content {
                margin-left: 0;
            }
        }

        @media print {
            .sidebar,
            .no-print,
            .dataTables_length,
            .dataTables_filter,
            .dataTables_paginate,
            .dataTables_info {
                display: none !important;
            }

            .main-content {
                margin-left: 0 !important;
                width: 100% !important;
            }

            body {
                background: white !important;
            }
        }
    </style>
</head>

<body>

<div class="container-fluid">
    <div class="row">

        <!-- SIDEBAR -->
        <aside class="sidebar bg-dark text-white p-3 position-fixed top-0 bottom-0 start-0 overflow-auto">

            <h5 class="text-white mb-4">
                Sistem Pelanggaran
            </h5>

            <nav class="nav nav-pills flex-column gap-2">

                <a href="../dashboard.php" class="nav-link text-white">
                    Dashboard
                </a>

                <a href="data_siswa.php" class="nav-link text-white">
                    Data Siswa
                </a>

                <a href="rekap_pelanggaran.php" class="nav-link text-white">
                    Rekap Pelanggaran
                </a>

                <a href="laporan.php" class="nav-link active">
                    Laporan
                </a>

                <hr class="text-secondary">

                <a href="../about_me.php" class="nav-link text-white">
                    About Me
                </a>

                <a href="../logout.php"
                   class="nav-link text-danger"
                   onclick="return confirm('Yakin ingin logout?')">
                    Logout
                </a>

            </nav>

        </aside>

        <!-- KONTEN UTAMA -->
        <main class="main-content col-12 p-4">

            <!-- HEADER -->
            <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">

                <div>
                    <h3 class="fw-bold mb-1">
                        Laporan Pelanggaran Siswa
                    </h3>

                    <p class="text-muted mb-0">
                        Selamat datang,
                        <strong><?= htmlspecialchars($nama_wali); ?></strong>
                    </p>
                </div>

                <div class="d-flex gap-2 no-print">

                    <a href="laporan.php" class="btn btn-outline-secondary">
                        Reset Filter
                    </a>

                    <button type="button"
                            class="btn btn-primary"
                            onclick="window.print()">
                        Cetak Laporan
                    </button>

                </div>

            </div>

            <!-- KELAS TANGGUNG JAWAB -->
            <div class="card border-0 shadow-sm mb-4">

                <div class="card-body">

                    <small class="text-muted">
                        Kelas Tanggung Jawab
                    </small>

                    <h5 class="fw-bold mb-0">
                        <?php
                        if (count($daftar_kelas) > 0) {
                            echo htmlspecialchars(
                                $filter_kelas > 0
                                    ? $nama_kelas_terpilih
                                    : implode(', ', array_column($daftar_kelas, 'nama'))
                            );
                        } else {
                            echo "Belum ada kelas yang ditugaskan";
                        }
                        ?>
                    </h5>

                </div>

            </div>

            <!-- RINGKASAN -->
            <div class="row g-3 mb-4">

                <div class="col-12 col-sm-6 col-xl-3">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body">
                            <p class="text-muted mb-2">
                                Total Pelanggaran
                            </p>

                            <h3 class="fw-bold text-primary">
                                <?= number_format($total_data, 0, ',', '.'); ?>
                            </h3>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-sm-6 col-xl-3">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body">
                            <p class="text-muted mb-2">
                                Total Poin
                            </p>

                            <h3 class="fw-bold text-info">
                                <?= number_format($total_poin, 0, ',', '.'); ?>
                            </h3>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-sm-6 col-xl-3">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body">
                            <p class="text-muted mb-2">
                                Selesai
                            </p>

                            <h3 class="fw-bold text-success">
                                <?= number_format($total_selesai, 0, ',', '.'); ?>
                            </h3>
                        </div>
                    </div>
                </div>

                <div class="col-12 col-sm-6 col-xl-3">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body">
                            <p class="text-muted mb-2">
                                Diproses
                            </p>

                            <h3 class="fw-bold text-warning">
                                <?= number_format($total_diproses, 0, ',', '.'); ?>
                            </h3>
                        </div>
                    </div>
                </div>

            </div>

            <!-- FILTER LAPORAN -->
            <div class="card border-0 shadow-sm mb-4 no-print">

                <div class="card-header bg-white py-3">
                    <h5 class="fw-bold mb-0">
                        Filter Laporan
                    </h5>
                </div>

                <div class="card-body">

                    <form method="GET" action="laporan.php">

                        <div class="row g-3">

                            <!-- FILTER KELAS -->
                            <div class="col-12 col-md-3">

                                <label for="kelas" class="form-label">
                                    Pilih Kelas
                                </label>

                                <select name="kelas"
                                        id="kelas"
                                        class="form-select">

                                    <option value="0">
                                        Semua Kelas Tanggung Jawab
                                    </option>

                                    <?php foreach ($daftar_kelas as $kelas): ?>

                                        <option value="<?= (int)$kelas['id']; ?>"
                                            <?= $filter_kelas == $kelas['id'] ? 'selected' : ''; ?>>

                                            <?= htmlspecialchars($kelas['nama']); ?>

                                        </option>

                                    <?php endforeach; ?>

                                </select>

                            </div>

                            <!-- FILTER STATUS -->
                            <div class="col-12 col-md-3">

                                <label for="status" class="form-label">
                                    Status Pelanggaran
                                </label>

                                <select name="status"
                                        id="status"
                                        class="form-select">

                                    <option value="">
                                        Semua Status
                                    </option>

                                    <option value="Diproses"
                                        <?= $filter_status === 'Diproses' ? 'selected' : ''; ?>>
                                        Diproses
                                    </option>

                                    <option value="Selesai"
                                        <?= $filter_status === 'Selesai' ? 'selected' : ''; ?>>
                                        Selesai
                                    </option>

                                </select>

                            </div>

                            <!-- TANGGAL AWAL -->
                            <div class="col-12 col-md-3">

                                <label for="tanggal_awal" class="form-label">
                                    Tanggal Awal
                                </label>

                                <input type="date"
                                       name="tanggal_awal"
                                       id="tanggal_awal"
                                       class="form-control"
                                       value="<?= htmlspecialchars($tanggal_awal); ?>">

                            </div>

                            <!-- TANGGAL AKHIR -->
                            <div class="col-12 col-md-3">

                                <label for="tanggal_akhir" class="form-label">
                                    Tanggal Akhir
                                </label>

                                <input type="date"
                                       name="tanggal_akhir"
                                       id="tanggal_akhir"
                                       class="form-control"
                                       value="<?= htmlspecialchars($tanggal_akhir); ?>">

                            </div>

                            <div class="col-12">

                                <button type="submit" class="btn btn-primary">
                                    Terapkan Filter
                                </button>

                                <a href="laporan.php"
                                   class="btn btn-outline-secondary">
                                    Reset
                                </a>

                            </div>

                        </div>

                    </form>

                </div>

            </div>

            <!-- DATA LAPORAN -->
            <div class="card border-0 shadow-sm">

                <div class="card-header bg-primary text-white py-3">
                    Data Laporan Pelanggaran
                </div>

                <div class="card-body">

                    <div class="mb-3">
                        <small class="text-muted">
                            Kelas:
                            <strong><?= htmlspecialchars($nama_kelas_terpilih); ?></strong>
                            <br>

                            Periode:
                            <strong><?= htmlspecialchars($periode); ?></strong>
                        </small>
                    </div>

                    <div class="table-responsive">

                        <table id="tabelLaporan"
                               class="table table-bordered table-hover align-middle">

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

                                <?php if ($total_data > 0): ?>

                                    <?php $no = 1; ?>

                                    <?php foreach ($rows as $row): ?>

                                        <tr>

                                            <td><?= $no++; ?></td>

                                            <td>
                                                <?= !empty($row['tanggal'])
                                                    ? date('d-m-Y', strtotime($row['tanggal']))
                                                    : '-'; ?>
                                            </td>

                                            <td>
                                                <?= htmlspecialchars($row['nis']); ?>
                                            </td>

                                            <td>
                                                <?= htmlspecialchars($row['nama_siswa']); ?>
                                            </td>

                                            <td>
                                                <?= htmlspecialchars($row['nama_kelas']); ?>
                                            </td>

                                            <td>
                                                <?= htmlspecialchars($row['nama_pelanggaran']); ?>
                                            </td>

                                            <td>
                                                <?= (int)($row['poin'] ?? 0); ?>
                                            </td>

                                            <td>
                                                <?= htmlspecialchars($row['keterangan'] ?? '-'); ?>
                                            </td>

                                            <td>
                                                <?= htmlspecialchars($row['tindakan'] ?? '-') ?: '-'; ?>
                                            </td>

                                            <td>

                                                <?php
                                                $status = strtolower(trim($row['status'] ?? ''));

                                                if ($status === 'selesai') {
                                                    echo '<span class="badge bg-success">Selesai</span>';
                                                } elseif ($status === 'diproses') {
                                                    echo '<span class="badge bg-warning text-dark">Diproses</span>';
                                                } else {
                                                    echo '<span class="badge bg-secondary">'
                                                        . htmlspecialchars($row['status'] ?? '-')
                                                        . '</span>';
                                                }
                                                ?>

                                            </td>

                                        </tr>

                                    <?php endforeach; ?>

                                <?php else: ?>

                                    <tr>
                                        <td colspan="10"
                                            class="text-center text-muted py-4">
                                            Tidak ada data pelanggaran untuk filter yang dipilih.
                                        </td>
                                    </tr>

                                <?php endif; ?>

                            </tbody>

                        </table>

                    </div>

                    <div class="d-flex flex-wrap justify-content-between gap-2 mt-3">

                        <small class="text-muted">
                            Total data:
                            <strong><?= number_format($total_data, 0, ',', '.'); ?></strong>
                        </small>

                        <small class="text-muted">
                            Total poin:
                            <strong><?= number_format($total_poin, 0, ',', '.'); ?></strong>
                        </small>

                    </div>

                </div>

            </div>

            <div class="text-center text-muted small mt-4">
                Sistem Informasi Pelanggaran Siswa
            </div>

        </main>

    </div>
</div>

<!-- JAVASCRIPT -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>

<script src="https://cdn.datatables.net/2.3.4/js/dataTables.min.js"></script>

<script src="https://cdn.datatables.net/2.3.4/js/dataTables.bootstrap5.min.js"></script>

<script>
$(document).ready(function () {

    $('#tabelLaporan').DataTable({

        pageLength: 10,

        lengthMenu: [5, 10, 25, 50],

        order: [[1, 'desc']],

        columnDefs: [
            {
                targets: 0,
                orderable: false
            },
            {
                targets: [0, 1, 2, 6, 9],
                className: 'text-center'
            }
        ],

        language: {

            search: "Cari:",

            searchPlaceholder: "Cari laporan...",

            lengthMenu: "Tampilkan _MENU_ data",

            info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",

            infoEmpty: "Tidak ada data",

            infoFiltered: "(difilter dari _MAX_ data)",

            zeroRecords: "Data tidak ditemukan",

            emptyTable: "Belum ada data pelanggaran.",

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
