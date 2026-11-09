
<?php
session_start();

if (!isset($_SESSION['login'])) {
    header("Location: ../login.php");
    exit;
}

include "../config/koneksi.php";

/* =====================================
   CEK ROLE WALI KELAS
===================================== */
$role = strtolower(trim($_SESSION['role'] ?? ''));

if ($role !== 'wali_kelas') {
    exit("Anda tidak memiliki akses ke halaman ini.");
}

/* =====================================
   AMBIL ID USER LOGIN
===================================== */
$user_id = $_SESSION['user_id']
    ?? $_SESSION['id_user']
    ?? $_SESSION['id']
    ?? null;

if (!$user_id) {
    exit("ID pengguna tidak ditemukan. Silakan login kembali.");
}

/* =====================================
   AMBIL DATA GURU
===================================== */
$stmtGuru = $koneksi->prepare("
    SELECT id, nama
    FROM t_guru
    WHERE user_id = ?
    LIMIT 1
");

$stmtGuru->bind_param("i", $user_id);
$stmtGuru->execute();
$guru = $stmtGuru->get_result()->fetch_assoc();
$stmtGuru->close();

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
        k.nama AS nama_kelas
    FROM t_wali_kelas wk
    JOIN t_kelas k ON k.id = wk.kelas_id
    JOIN t_tahun_ajaran ta ON ta.id = wk.tahun_ajaran_id
    WHERE wk.guru_id = ?
      AND wk.status_aktif = 1
      AND ta.status_aktif = 1
    ORDER BY k.nama ASC
");

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
   FILTER
===================================== */
$filter_kelas = isset($_GET['kelas'])
    ? (int) $_GET['kelas']
    : 0;

$filter_status = trim($_GET['status'] ?? '');

/* Pastikan kelas filter memang kelas tanggung jawab */
if ($filter_kelas > 0 && !in_array($filter_kelas, $kelas_id, true)) {
    $filter_kelas = 0;
}

if (!in_array($filter_status, ['', 'Diproses', 'Selesai'], true)) {
    $filter_status = '';
}

/* =====================================
   QUERY DATA PELANGGARAN
===================================== */
$data_pelanggaran = [];
$total_pelanggaran = 0;
$total_poin = 0;

if (!empty($kelas_id)) {

    $placeholders = implode(',', array_fill(0, count($kelas_id), '?'));

    $sql = "
        SELECT
            ps.id,
            ps.tanggal,
            ps.nama_siswa,
            ps.nama_kelas,
            ps.nama_pelanggaran,
            ps.poin,
            ps.keterangan,
            ps.tindakan,
            ps.status
        FROM t_pelanggaran_siswa ps
        WHERE ps.kelas_id IN ($placeholders)
    ";

    $params = $kelas_id;
    $types = str_repeat('i', count($kelas_id));

    if ($filter_kelas > 0) {
        $sql .= " AND ps.kelas_id = ?";
        $params[] = $filter_kelas;
        $types .= "i";
    }

    if ($filter_status !== '') {
        $sql .= " AND ps.status = ?";
        $params[] = $filter_status;
        $types .= "s";
    }

    $sql .= " ORDER BY ps.tanggal DESC, ps.id DESC";

    $stmtData = $koneksi->prepare($sql);
    $stmtData->bind_param($types, ...$params);
    $stmtData->execute();

    $resultData = $stmtData->get_result();

    while ($row = $resultData->fetch_assoc()) {
        $data_pelanggaran[] = $row;
        $total_pelanggaran++;
        $total_poin += (int) $row['poin'];
    }

    $stmtData->close();
}

/* =====================================
   HELPER FORMAT TANGGAL
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

    <title>Rekap Pelanggaran Siswa</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">

    <link href="https://cdn.datatables.net/2.3.4/css/dataTables.bootstrap5.min.css" rel="stylesheet">
</head>

<body>

<div class="container-fluid">
    <div class="row">

        <!-- SIDEBAR -->
        <aside class="col-md-3 col-lg-2 bg-dark min-vh-100 p-3 position-fixed top-0 start-0">

            <h5 class="text-white mb-4">Sistem Pelanggaran</h5>

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
                    <a href="rekap_pelanggaran.php" class="nav-link active">
                        Rekap Pelanggaran
                    </a>
                </li>

                <li class="nav-item">
                    <a href="cetak_rekap.php" class="nav-link text-white">
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

            <h2 class="mb-2">Rekap Pelanggaran Siswa</h2>

            <p class="text-muted">
                Selamat datang, <strong><?= htmlspecialchars($nama_guru) ?></strong>
            </p>

            <!-- KELAS TANGGUNG JAWAB -->
            <div class="card shadow-sm mb-4">
                <div class="card-body">

                    <h6 class="text-muted mb-2">Kelas Tanggung Jawab</h6>

                    <?php if (!empty($kelas_wali)): ?>

                        <strong>
                            <?= htmlspecialchars(
                                implode(', ', array_column($kelas_wali, 'nama_kelas'))
                            ) ?>
                        </strong>

                    <?php else: ?>

                        <span class="text-danger">
                            Belum ada kelas yang ditugaskan.
                        </span>

                    <?php endif; ?>

                </div>
            </div>

            <!-- KARTU TOTAL SEJAJAR -->
            <div class="row g-3 mb-4">

                <div class="col-12 col-md-6">
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

                <div class="col-12 col-md-6">
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
                               class="table table-bordered table-striped table-hover">

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

                                            <?php else: ?>

                                                <span class="badge bg-warning text-dark">
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

    $('#tabelRekap').DataTable({
        pageLength: 10,
        lengthMenu: [5, 10, 25, 50],

        language: {
            search: "Cari:",
            lengthMenu: "Tampilkan _MENU_ data",
            info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
            infoEmpty: "Tidak ada data",
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
