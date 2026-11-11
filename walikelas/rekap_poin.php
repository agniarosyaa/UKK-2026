```php
<?php
session_start();

/* =====================================
   CEK LOGIN
===================================== */
if (!isset($_SESSION['login']) || $_SESSION['login'] !== true) {
    header("Location: ../login.php");
    exit;
}

include "../config/koneksi.php";

/* =====================================
   NORMALISASI ROLE
===================================== */
$role = strtolower(trim($_SESSION['role'] ?? ''));
$role = str_replace([' ', '-'], '_', $role);

/*
   Role database hanya:
   - admin
   - guru

   Wali Kelas bukan role database.
*/
if ($role !== 'guru') {
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

if ($user_id <= 0) {
    exit("ID pengguna tidak ditemukan.");
}

/* =====================================
   AMBIL DATA GURU
===================================== */
$stmtGuru = $koneksi->prepare("
    SELECT id, nama
    FROM t_guru
    WHERE user_id = ?
      AND status_aktif = 1
    LIMIT 1
");

if (!$stmtGuru) {
    exit("Query guru gagal: " . htmlspecialchars($koneksi->error));
}

$stmtGuru->bind_param("i", $user_id);
$stmtGuru->execute();

$guru = $stmtGuru->get_result()->fetch_assoc();
$stmtGuru->close();

if (!$guru) {
    exit("Data guru tidak ditemukan.");
}

$guru_id = (int) $guru['id'];
$nama_guru = $guru['nama'];

if ($guru_id <= 0) {
    exit("ID guru tidak valid.");
}

/* =====================================
   CEK WALI KELAS AKTIF
===================================== */
$is_wali_kelas = false;

/*
   Utamakan session supaya sama dengan dashboard.
*/
if (
    !empty($_SESSION['is_wali_kelas']) ||
    !empty($_SESSION['akses_wali_kelas'])
) {
    $is_wali_kelas = true;
}

/*
   Kalau session belum tersedia, cek langsung
   dari database.
*/
if (!$is_wali_kelas) {

    $stmtCek = $koneksi->prepare("
        SELECT wk.id
        FROM t_wali_kelas wk
        INNER JOIN t_guru g
            ON g.id = wk.guru_id
        INNER JOIN t_tahun_ajaran ta
            ON ta.id = wk.tahun_ajaran_id
        INNER JOIN t_kelas k
            ON k.id = wk.kelas_id
        WHERE g.user_id = ?
          AND wk.status_aktif = 1
          AND ta.status_aktif = 1
          AND k.status_aktif = 1
        LIMIT 1
    ");

    if (!$stmtCek) {
        exit("Query penugasan gagal: " . htmlspecialchars($koneksi->error));
    }

    $stmtCek->bind_param("i", $user_id);
    $stmtCek->execute();

    $wali = $stmtCek->get_result()->fetch_assoc();
    $stmtCek->close();

    if ($wali) {
        $is_wali_kelas = true;
    }
}

/*
   Halaman ini khusus Wali Kelas.
*/
if (!$is_wali_kelas) {
    exit("Anda belum memiliki penugasan sebagai Wali Kelas yang aktif.");
}

/* =====================================
   AMBIL KELAS TANGGUNG JAWAB
===================================== */
$stmtKelas = $koneksi->prepare("
    SELECT DISTINCT
        wk.kelas_id,
        wk.tahun_ajaran_id,
        k.nama AS nama_kelas
    FROM t_wali_kelas wk
    INNER JOIN t_guru g
        ON g.id = wk.guru_id
    INNER JOIN t_kelas k
        ON k.id = wk.kelas_id
    INNER JOIN t_tahun_ajaran ta
        ON ta.id = wk.tahun_ajaran_id
    WHERE g.user_id = ?
      AND wk.status_aktif = 1
      AND ta.status_aktif = 1
      AND k.status_aktif = 1
    ORDER BY k.nama ASC
");

if (!$stmtKelas) {
    exit("Query kelas gagal: " . htmlspecialchars($koneksi->error));
}

$stmtKelas->bind_param("i", $user_id);
$stmtKelas->execute();

$resultKelas = $stmtKelas->get_result();

$kelas_wali = [];

while ($row = $resultKelas->fetch_assoc()) {
    $kelas_wali[] = $row;
}

$stmtKelas->close();

if (empty($kelas_wali)) {
    exit("Anda belum memiliki kelas tanggung jawab yang aktif.");
}

/* =====================================
   KONDISI KELAS DAN TAHUN AJARAN
===================================== */
$kondisi_kelas = [];
$params = [];
$types = "";

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

/* =====================================
   QUERY REKAP POIN SISWA
===================================== */
$sql = "
    SELECT
        rekap.siswa_id,

        COALESCE(
            NULLIF(s.nama, ''),
            NULLIF(rekap.nama_siswa, ''),
            '-'
        ) AS nama_siswa,

        COALESCE(k.nama, '-') AS nama_kelas,

        rekap.jumlah_pelanggaran,
        rekap.total_poin

    FROM (
        SELECT
            ps.siswa_id,
            ps.kelas_id,
            ps.tahun_ajaran_id,

            MAX(ps.nama_siswa) AS nama_siswa,

            COUNT(ps.id) AS jumlah_pelanggaran,

            COALESCE(SUM(ps.poin), 0) AS total_poin

        FROM t_pelanggaran_siswa ps

        WHERE (
            " . implode(" OR ", $kondisi_kelas) . "
        )

        GROUP BY
            ps.siswa_id,
            ps.kelas_id,
            ps.tahun_ajaran_id

    ) AS rekap

    LEFT JOIN t_siswa s
        ON s.id = rekap.siswa_id

    LEFT JOIN t_kelas k
        ON k.id = rekap.kelas_id

    ORDER BY
        rekap.total_poin DESC,
        nama_siswa ASC
";

$stmtData = $koneksi->prepare($sql);

if (!$stmtData) {
    exit("Query rekap poin gagal: " . htmlspecialchars($koneksi->error));
}

$stmtData->bind_param($types, ...$params);
$stmtData->execute();

$resultData = $stmtData->get_result();

$data_rekap = [];

$total_siswa = 0;
$total_catatan = 0;
$total_poin = 0;

while ($row = $resultData->fetch_assoc()) {

    $data_rekap[] = $row;

    $total_siswa++;
    $total_catatan += (int) $row['jumlah_pelanggaran'];
    $total_poin += (int) $row['total_poin'];
}

$stmtData->close();

/* =====================================
   NAMA KELAS WALI
===================================== */
$nama_kelas_wali = [];

foreach ($kelas_wali as $kelas) {
    $nama_kelas_wali[] = $kelas['nama_kelas'];
}

$nama_kelas_wali = array_unique($nama_kelas_wali);
?>

<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Rekap Poin Siswa</title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >

    <link
        href="https://cdn.datatables.net/2.3.4/css/dataTables.bootstrap5.min.css"
        rel="stylesheet"
    >

</head>

<body class="bg-body-tertiary">

<div class="container-fluid">

    <div class="row">

        <!-- SIDEBAR -->
        <aside
            class="col-md-3 col-lg-2 bg-dark text-white p-3 position-fixed top-0 bottom-0 start-0 overflow-auto"
        >

            <h5 class="text-white mb-4">
                Sistem Pelanggaran
            </h5>

            <nav class="nav nav-pills flex-column gap-2">

                <a
                    href="../dashboard.php"
                    class="nav-link text-white"
                >
                    Dashboard
                </a>

                <a
                    href="../catat_pelanggaran.php"
                    class="nav-link text-white"
                >
                    Catat Pelanggaran
                </a>

                <a
                    href="data_siswa.php"
                    class="nav-link text-white"
                >
                    Data Siswa
                </a>

                <a
                    href="rekap_pelanggaran.php"
                    class="nav-link text-white"
                >
                    Rekap Pelanggaran
                </a>

                <a
                    href="laporan.php"
                    class="nav-link text-white"
                >
                    Laporan
                </a>

                <a
                    href="../riwayat.php"
                    class="nav-link text-white"
                >
                    Riwayat
                </a>

                <a
                    href="rekap_poin.php"
                    class="nav-link active"
                >
                    Rekap Poin
                </a>

                <hr class="text-secondary">

                <a
                    href="../about_me.php"
                    class="nav-link text-white"
                >
                    About Me
                </a>

                <a
                    href="../logout.php"
                    class="nav-link text-danger"
                    onclick="return confirm('Yakin ingin logout?')"
                >
                    Logout
                </a>

            </nav>

        </aside>

        <!-- KONTEN UTAMA -->
        <main class="col-md-9 col-lg-10 p-4 offset-md-3 offset-lg-2">

            <h2 class="mb-1">
                Rekap Poin Siswa
            </h2>

            <p class="text-secondary mb-3">
                Ringkasan jumlah pelanggaran dan total poin setiap siswa.
            </p>

            <p class="text-secondary">

                Selamat datang,

                <strong>
                    <?= htmlspecialchars($nama_guru); ?>
                </strong>

            </p>

            <!-- KELAS TANGGUNG JAWAB -->
            <div class="card border-0 shadow-sm mb-3">

                <div class="card-body">

                    <p class="text-secondary mb-1">
                        Kelas Tanggung Jawab
                    </p>

                    <strong>
                        <?= htmlspecialchars(implode(', ', $nama_kelas_wali)); ?>
                    </strong>

                </div>

            </div>

            <!-- KARTU RINGKASAN -->
            <div class="row g-3 mb-4">

                <div class="col-md-4">

                    <div class="card border-0 shadow-sm h-100">

                        <div class="card-body">

                            <p class="text-secondary mb-1">
                                Siswa yang Memiliki Catatan
                            </p>

                            <h3 class="fw-bold mb-0">
                                <?= $total_siswa; ?>
                            </h3>

                        </div>

                    </div>

                </div>

                <div class="col-md-4">

                    <div class="card border-0 shadow-sm h-100">

                        <div class="card-body">

                            <p class="text-secondary mb-1">
                                Total Catatan Pelanggaran
                            </p>

                            <h3 class="fw-bold mb-0">
                                <?= $total_catatan; ?>
                            </h3>

                        </div>

                    </div>

                </div>

                <div class="col-md-4">

                    <div class="card border-0 shadow-sm h-100">

                        <div class="card-body">

                            <p class="text-secondary mb-1">
                                Total Poin Semua Catatan
                            </p>

                            <h3 class="fw-bold mb-0">
                                <?= number_format($total_poin, 0, ',', '.'); ?>
                            </h3>

                        </div>

                    </div>

                </div>

            </div>

            <!-- TABEL REKAP POIN -->
            <div class="card border-0 shadow-sm">

                <div class="card-header bg-primary text-white py-3">
                    Data Rekap Poin
                </div>

                <div class="card-body">

                    <div class="table-responsive">

                        <table
                            id="tabelRekap"
                            class="table table-bordered table-hover align-middle"
                            style="width:100%"
                        >

                            <thead class="table-light">

                                <tr>

                                    <th>No</th>

                                    <th>Nama Siswa</th>

                                    <th>Kelas</th>

                                    <th>Jumlah Pelanggaran</th>

                                    <th>Total Poin</th>

                                </tr>

                            </thead>

                            <tbody>

                                <?php $no = 1; ?>

                                <?php foreach ($data_rekap as $data): ?>

                                    <tr>

                                        <td>
                                            <?= $no++; ?>
                                        </td>

                                        <td>
                                            <?= htmlspecialchars($data['nama_siswa']); ?>
                                        </td>

                                        <td>
                                            <?= htmlspecialchars($data['nama_kelas']); ?>
                                        </td>

                                        <td>
                                            <?= (int) $data['jumlah_pelanggaran']; ?> kali
                                        </td>

                                        <td>

                                            <span class="badge bg-primary">

                                                <?= number_format(
                                                    (int) $data['total_poin'],
                                                    0,
                                                    ',',
                                                    '.'
                                                ); ?>

                                                poin

                                            </span>

                                        </td>

                                    </tr>

                                <?php endforeach; ?>

                            </tbody>

                        </table>

                    </div>

                    <?php if (empty($data_rekap)): ?>

                        <div class="alert alert-info mb-0">

                            Belum ada data pelanggaran pada kelas tanggung jawab Anda.

                        </div>

                    <?php endif; ?>

                    <a
                        href="../dashboard.php"
                        class="btn btn-secondary mt-3"
                    >
                        Kembali ke Dashboard
                    </a>

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

        columnDefs: [
            {
                orderable: false,
                targets: [0]
            }
        ],

        language: {

            search: "Cari:",

            lengthMenu: "Tampilkan _MENU_ data",

            info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",

            infoEmpty: "Tidak ada data",

            infoFiltered: "(difilter dari _MAX_ data)",

            zeroRecords: "Data siswa tidak ditemukan",

            emptyTable: "Belum ada data rekap poin",

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
```