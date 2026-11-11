
<?php
session_start();

if (!isset($_SESSION['login']) || $_SESSION['login'] !== true) {
    header("Location: ../login.php");
    exit;
}

include "../config/koneksi.php";

/* CEK ROLE */
$role = strtolower(trim($_SESSION['role'] ?? ''));
$role = str_replace([' ', '-'], '_', $role);

if (!in_array($role, ['guru', 'wali_kelas'], true)) {
    exit("Anda tidak memiliki akses.");
}

/* AMBIL USER ID */
$user_id = (int) (
    $_SESSION['user_id']
    ?? $_SESSION['id_user']
    ?? $_SESSION['id']
    ?? 0
);

/* AMBIL DATA GURU */
$guru = null;

if ($user_id > 0) {
    $stmt = $koneksi->prepare("
        SELECT id, nama
        FROM t_guru
        WHERE user_id = ?
        LIMIT 1
    ");

    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $guru = $stmt->get_result()->fetch_assoc();
    $stmt->close();
}

/* FALLBACK GURU ID */
if (!$guru && !empty($_SESSION['guru_id'])) {
    $guru_id_session = (int) $_SESSION['guru_id'];

    $stmt = $koneksi->prepare("
        SELECT id, nama
        FROM t_guru
        WHERE id = ?
        LIMIT 1
    ");

    $stmt->bind_param("i", $guru_id_session);
    $stmt->execute();
    $guru = $stmt->get_result()->fetch_assoc();
    $stmt->close();
}

if (!$guru) {
    exit("Data guru tidak ditemukan.");
}

$guru_id = (int) $guru['id'];
$nama_guru = $guru['nama'];

/* AMBIL KELAS TANGGUNG JAWAB */
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

if (empty($kelas_wali)) {
    exit("Tidak ada penugasan Wali Kelas yang aktif.");
}

/* FILTER */
$filter_kelas = (int) ($_GET['kelas'] ?? 0);
$tanggal_awal = trim($_GET['tanggal_awal'] ?? '');
$tanggal_akhir = trim($_GET['tanggal_akhir'] ?? '');
$filter_status = trim($_GET['status'] ?? '');

if (
    $filter_kelas > 0 &&
    !in_array($filter_kelas, $kelas_id, true)
) {
    $filter_kelas = 0;
}

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

if (!in_array($filter_status, ['', 'Diproses', 'Selesai'], true)) {
    $filter_status = '';
}

/* QUERY DATA LAPORAN */
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
        ps.status

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

$sql .= " ORDER BY ps.tanggal DESC, ps.id DESC";

$stmtData = $koneksi->prepare($sql);

if (!$stmtData) {
    exit("Query laporan gagal: " . htmlspecialchars($koneksi->error));
}

$stmtData->bind_param($types, ...$params);
$stmtData->execute();

$resultData = $stmtData->get_result();

$data_laporan = [];
$total_poin = 0;
$total_selesai = 0;
$total_diproses = 0;

while ($row = $resultData->fetch_assoc()) {
    $data_laporan[] = $row;

    $total_poin += (int) $row['poin'];

    if ($row['status'] === 'Selesai') {
        $total_selesai++;
    } elseif ($row['status'] === 'Diproses') {
        $total_diproses++;
    }
}

$stmtData->close();

function tanggalIndonesia($tanggal)
{
    if (empty($tanggal)) {
        return '-';
    }

    return date('d-m-Y', strtotime($tanggal));
}

$nama_kelas_cetak = [];

foreach ($kelas_wali as $kelas) {
    if (
        $filter_kelas === 0 ||
        $filter_kelas === (int) $kelas['kelas_id']
    ) {
        $nama_kelas_cetak[] = $kelas['nama_kelas'];
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Cetak Rekap Pelanggaran</title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >

    <style>
        @media print {
            .no-print {
                display: none !important;
            }

            body {
                font-size: 11px;
            }

            table {
                width: 100% !important;
            }

            @page {
                size: landscape;
                margin: 12mm;
            }
        }
    </style>
</head>

<body>

<div class="container-fluid p-4">

    <div class="d-flex justify-content-between align-items-center mb-3 no-print">
        <a href="laporan.php" class="btn btn-secondary">
            Kembali
        </a>

        <button onclick="window.print()" class="btn btn-primary">
            Cetak Sekarang
        </button>
    </div>

    <div class="text-center mb-4">
        <h4 class="fw-bold">LAPORAN REKAP PELANGGARAN SISWA</h4>
        <h6>SISTEM PELANGGARAN SISWA</h6>
        <hr>
    </div>

    <table class="table table-borderless table-sm">
        <tr>
            <td width="150">Nama Wali Kelas</td>
            <td>: <?= htmlspecialchars($nama_guru) ?></td>
        </tr>

        <tr>
            <td>Kelas Tanggung Jawab</td>
            <td>: <?= htmlspecialchars(implode(', ', $nama_kelas_cetak)) ?></td>
        </tr>

        <tr>
            <td>Periode</td>
            <td>:
                <?= $tanggal_awal ? tanggalIndonesia($tanggal_awal) : 'Semua tanggal' ?>
                s.d.
                <?= $tanggal_akhir ? tanggalIndonesia($tanggal_akhir) : 'Sekarang' ?>
            </td>
        </tr>

        <tr>
            <td>Status</td>
            <td>: <?= htmlspecialchars($filter_status ?: 'Semua Status') ?></td>
        </tr>
    </table>

    <div class="row mb-3">
        <div class="col-4">
            <strong>Total Pelanggaran:</strong>
            <?= count($data_laporan) ?>
        </div>

        <div class="col-4">
            <strong>Total Poin:</strong>
            <?= $total_poin ?>
        </div>

        <div class="col-4">
            <strong>Selesai:</strong>
            <?= $total_selesai ?>
            |
            <strong>Diproses:</strong>
            <?= $total_diproses ?>
        </div>
    </div>

    <div class="table-responsive">
        <table class="table table-bordered table-sm">
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
                <?php if (empty($data_laporan)): ?>
                    <tr>
                        <td colspan="10" class="text-center">
                            Tidak ada data laporan.
                        </td>
                    </tr>
                <?php else: ?>
                    <?php $no = 1; ?>

                    <?php foreach ($data_laporan as $data): ?>
                        <tr>
                            <td><?= $no++ ?></td>
                            <td><?= tanggalIndonesia($data['tanggal']) ?></td>
                            <td><?= htmlspecialchars($data['nis'] ?? '-') ?></td>
                            <td><?= htmlspecialchars($data['nama_siswa'] ?? '-') ?></td>
                            <td><?= htmlspecialchars($data['nama_kelas'] ?? '-') ?></td>
                            <td><?= htmlspecialchars($data['nama_pelanggaran'] ?? '-') ?></td>
                            <td><?= (int) $data['poin'] ?></td>
                            <td><?= htmlspecialchars($data['keterangan'] ?? '-') ?></td>
                            <td><?= htmlspecialchars($data['tindakan'] ?? '-') ?></td>
                            <td><?= htmlspecialchars($data['status'] ?? '-') ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <div class="text-end mt-4">
        <p>
            Dicetak pada: <?= date('d-m-Y H:i') ?>
        </p>

        <br><br>

        <p>
            Wali Kelas,
            <br><br><br><br>
            <strong>
                <?= htmlspecialchars($nama_guru) ?>
            </strong>
        </p>
    </div>

</div>

</body>
</html>
