
<?php
session_start();

if (!isset($_SESSION['login']) || $_SESSION['login'] !== true) {
    header("Location: ../login.php");
    exit;
}

include "../config/koneksi.php";

/* =====================================
   CEK ROLE
===================================== */
$role = strtolower(trim($_SESSION['role'] ?? ''));
$role = str_replace([' ', '-'], '_', $role);

if (!in_array($role, ['guru', 'wali_kelas'], true)) {
    exit("Anda tidak memiliki akses ke halaman ini.");
}

/* =====================================
   AMBIL ID USER LOGIN
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

/* FALLBACK ID GURU */
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

$kelas_wali = [];

while ($row = $resultKelas->fetch_assoc()) {
    $kelas_wali[] = $row;
}

$stmtKelas->close();

if (empty($kelas_wali)) {
    exit("Anda belum memiliki penugasan sebagai Wali Kelas yang aktif.");
}

/* =====================================
   FILTER KELAS DAN STATUS
===================================== */
$filter_kelas = trim($_GET['kelas'] ?? '');
$filter_status = trim($_GET['status'] ?? '');

$kelas_filter_id = 0;
$tahun_filter_id = 0;

if ($filter_kelas !== '') {
    $pecah_filter = explode('|', $filter_kelas);

    if (count($pecah_filter) === 2) {
        $kelas_filter_id = (int) $pecah_filter[0];
        $tahun_filter_id = (int) $pecah_filter[1];
    }
}

/* VALIDASI FILTER KELAS */
$filter_valid = false;

if ($kelas_filter_id > 0 && $tahun_filter_id > 0) {
    foreach ($kelas_wali as $kelas) {
        if (
            (int) $kelas['kelas_id'] === $kelas_filter_id &&
            (int) $kelas['tahun_ajaran_id'] === $tahun_filter_id
        ) {
            $filter_valid = true;
            break;
        }
    }
}

if (!$filter_valid) {
    $kelas_filter_id = 0;
    $tahun_filter_id = 0;
    $filter_kelas = '';
}

/* VALIDASI STATUS */
if (!in_array($filter_status, ['', 'Diproses', 'Selesai'], true)) {
    $filter_status = '';
}

/* =====================================
   DATA REKAP PELANGGARAN
===================================== */
$data_pelanggaran = [];
$total_pelanggaran = 0;
$total_poin = 0;

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

        COALESCE(
            NULLIF(s.nama, ''),
            NULLIF(ps.nama_siswa, ''),
            '-'
        ) AS nama_siswa,

        COALESCE(
            NULLIF(k.nama, ''),
            NULLIF(ps.nama_kelas, ''),
            '-'
        ) AS nama_kelas,

        COALESCE(
            NULLIF(ps.nama_pelanggaran, ''),
            NULLIF(p.nama, ''),
            '-'
        ) AS nama_pelanggaran,

        ps.poin,
        ps.keterangan,
        ps.tindakan,
        ps.status,
        ps.kelas_id,
        ps.tahun_ajaran_id

    FROM t_pelanggaran_siswa ps

    LEFT JOIN t_siswa s
        ON s.id = ps.siswa_id

    LEFT JOIN t_kelas k
        ON k.id = ps.kelas_id

    LEFT JOIN t_pelanggaran p
        ON p.id = ps.pelanggaran_id

    WHERE (
        " . implode(" OR ", $kondisi_kelas) . "
    )
";

/* FILTER KELAS */
if ($kelas_filter_id > 0 && $tahun_filter_id > 0) {
    $sql .= "
        AND ps.kelas_id = ?
        AND ps.tahun_ajaran_id = ?
    ";

    $params[] = $kelas_filter_id;
    $params[] = $tahun_filter_id;

    $types .= "ii";
}

/* FILTER STATUS */
if ($filter_status !== '') {
    $sql .= " AND ps.status = ?";

    $params[] = $filter_status;
    $types .= "s";
}

$sql .= " ORDER BY ps.tanggal DESC, ps.id DESC";

/* EKSEKUSI QUERY */
$stmtData = $koneksi->prepare($sql);

if (!$stmtData) {
    exit("Query rekap gagal: " . htmlspecialchars($koneksi->error));
}

$stmtData->bind_param($types, ...$params);
$stmtData->execute();

$resultData = $stmtData->get_result();

while ($row = $resultData->fetch_assoc()) {
    $data_pelanggaran[] = $row;

    $total_pelanggaran++;
    $total_poin += (int) $row['poin'];
}

$stmtData->close();

/* =====================================
   FORMAT TANGGAL
===================================== */
function tanggalIndonesia($tanggal)
{
    if (empty($tanggal)) {
        return '-';
    }

    $waktu = strtotime($tanggal);

    if ($waktu === false) {
        return '-';
    }

    return date('d-m-Y', $waktu);
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Rekap Pelanggaran Siswa</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css"
          rel="stylesheet">

    <link href="https://cdn.datatables.net/2.3.4/css/dataTables.bootstrap5.min.css"
          rel="stylesheet">

    <style>
        body {
            background-color: #f8f9fa;
            margin: 0;
        }

        /* SIDEBAR SAMA DENGAN DASHBOARD */
        .sidebar {
            width: 275px;
            min-width: 275px;
            max-width: 275px;
            height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
            bottom: 0;
            background-color: #212529;
            padding: 20px 15px;
            overflow-y: auto;
            box-sizing: border-box;
            z-index: 1000;
        }

        .sidebar .nav-link {
            color: white;
            border-radius: 6px;
        }

        .sidebar .nav-link:hover {
            background-color: #343a40;
        }

        .sidebar .nav-link.active {
            background-color: #0d6efd;
        }

        /* KONTEN UTAMA */
        .content {
            margin-left: 275px;
            width: calc(100% - 275px);
            min-height: 100vh;
            padding: 25px;
            box-sizing: border-box;
        }

        /* RESPONSIVE */
        @media (max-width: 767px) {
            .sidebar {
                position: relative;
                width: 100%;
                min-width: 100%;
                max-width: 100%;
                height: auto;
                min-height: auto;
            }

            .content {
                margin-left: 0;
                width: 100%;
                padding: 15px;
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
                    <a href="rekap_pelanggaran.php" class="nav-link active">
                        Rekap Pelanggaran
                    </a>
                </li>

                <li class="nav-item">
                    <a href="laporan.php" class="nav-link text-white">
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

        <!-- KONTEN UTAMA -->
    <main class="col-md-9 col-lg-10 offset-md-3 offset-lg-2 p-4">

            <h2 class="mb-2">
                Rekap Pelanggaran Siswa
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

            <!-- TOTAL -->
            <div class="row g-3 mb-4">

                <div class="col-md-6">
                    <div class="card shadow-sm h-100">
                        <div class="card-body">

                            <h6 class="text-muted mb-2">
                                Total Pelanggaran
                            </h6>

                            <h3 class="mb-0">
                                <?= $total_pelanggaran ?>
                            </h3>

                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="card shadow-sm h-100">
                        <div class="card-body">

                            <h6 class="text-muted mb-2">
                                Total Poin
                            </h6>

                            <h3 class="mb-0">
                                <?= $total_poin ?>
                            </h3>

                        </div>
                    </div>
                </div>

            </div>

            <!-- FILTER -->
            <div class="card shadow-sm mb-4">
                <div class="card-body">

                    <form method="GET" class="row g-3">

                        <div class="col-md-6">
                            <label class="form-label">
                                Pilih Kelas
                            </label>

                            <select name="kelas" class="form-select">

                                <option value="">
                                    Semua Kelas Tanggung Jawab
                                </option>

                                <?php foreach ($kelas_wali as $kelas): ?>

                                    <?php
                                    $nilai_kelas =
                                        (int) $kelas['kelas_id'] . '|' .
                                        (int) $kelas['tahun_ajaran_id'];
                                    ?>

                                    <option
                                        value="<?= htmlspecialchars($nilai_kelas) ?>"
                                        <?= $filter_kelas === $nilai_kelas ? 'selected' : '' ?>
                                    >
                                        <?= htmlspecialchars($kelas['nama_kelas']) ?>
                                        -
                                        <?= htmlspecialchars($kelas['nama_tahun_ajaran']) ?>
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

                                <option value="Diproses"
                                    <?= $filter_status === 'Diproses' ? 'selected' : '' ?>>
                                    Diproses
                                </option>

                                <option value="Selesai"
                                    <?= $filter_status === 'Selesai' ? 'selected' : '' ?>>
                                    Selesai
                                </option>

                            </select>
                        </div>

                        <div class="col-12">
                            <button type="submit" class="btn btn-primary">
                                Tampilkan
                            </button>

                            <a href="rekap_pelanggaran.php" class="btn btn-secondary">
                                Reset
                            </a>
                        </div>

                    </form>

                </div>
            </div>

            <!-- TABEL REKAP -->
            <div class="card shadow-sm mb-4">

                <div class="card-body">

                    <h5 class="mb-3">
                        Data Rekap Pelanggaran
                    </h5>

                    <div class="table-responsive">

                        <table id="tabelRekap"
                               class="table table-bordered table-striped table-hover align-middle">

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

                                <?php $no = 1; ?>

                                <?php foreach ($data_pelanggaran as $data): ?>

                                    <tr>
                                        <td><?= $no++ ?></td>

                                        <td>
                                            <?= tanggalIndonesia($data['tanggal']) ?>
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

<!-- JAVASCRIPT -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>

<script src="https://cdn.datatables.net/2.3.4/js/dataTables.min.js"></script>

<script src="https://cdn.datatables.net/2.3.4/js/dataTables.bootstrap5.min.js"></script>

<script>
$(document).ready(function () {

    $('#tabelRekap').DataTable({
        pageLength: 10,

        lengthMenu: [5, 10, 25, 50],

        language: {
            search: "Cari:",
            lengthMenu: "Tampilkan _MENU_ data",
            info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
            infoEmpty: "Tidak ada data",
            infoFiltered: "(difilter dari _MAX_ data)",
            zeroRecords: "Data pelanggaran tidak ditemukan",
            emptyTable: "Belum ada data pelanggaran",

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
