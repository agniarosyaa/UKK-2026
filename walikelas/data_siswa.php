
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
$user_id = (int)(
    $_SESSION['user_id']
    ?? $_SESSION['id_user']
    ?? $_SESSION['id']
    ?? 0
);

$guru_id = 0;
$nama_guru = $_SESSION['name'] ?? $_SESSION['nama'] ?? 'Wali Kelas';

/* =====================================
   AMBIL DATA GURU
===================================== */
if ($user_id > 0) {

    $stmtGuru = mysqli_prepare(
        $koneksi,
        "SELECT id, nama
         FROM t_guru
         WHERE user_id = ?
         LIMIT 1"
    );

    if (!$stmtGuru) {
        exit("Query guru gagal: " . htmlspecialchars(mysqli_error($koneksi)));
    }

    mysqli_stmt_bind_param($stmtGuru, "i", $user_id);
    mysqli_stmt_execute($stmtGuru);

    $resultGuru = mysqli_stmt_get_result($stmtGuru);
    $dataGuru = mysqli_fetch_assoc($resultGuru);

    mysqli_stmt_close($stmtGuru);

    if ($dataGuru) {
        $guru_id = (int)$dataGuru['id'];
        $nama_guru = $dataGuru['nama'];
    }
}

/* =====================================
   FALLBACK ID GURU DARI SESSION
===================================== */
if ($guru_id <= 0 && !empty($_SESSION['guru_id'])) {

    $guru_id = (int)$_SESSION['guru_id'];

    $stmtGuru = mysqli_prepare(
        $koneksi,
        "SELECT id, nama
         FROM t_guru
         WHERE id = ?
         LIMIT 1"
    );

    mysqli_stmt_bind_param($stmtGuru, "i", $guru_id);
    mysqli_stmt_execute($stmtGuru);

    $resultGuru = mysqli_stmt_get_result($stmtGuru);
    $dataGuru = mysqli_fetch_assoc($resultGuru);

    mysqli_stmt_close($stmtGuru);

    if ($dataGuru) {
        $nama_guru = $dataGuru['nama'];
    } else {
        $guru_id = 0;
    }
}

if ($guru_id <= 0) {
    exit("Data guru tidak ditemukan. Periksa hubungan akun login dengan data guru.");
}

/* =====================================
   AMBIL PENUGASAN WALI KELAS AKTIF
===================================== */
$sqlKelas = "
    SELECT DISTINCT
        wk.id,
        wk.kelas_id,
        k.nama AS nama_kelas,
        wk.tahun_ajaran_id,
        ta.nama AS nama_tahun_ajaran,
        wk.tanggal_mulai,
        wk.tanggal_selesai
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
";

$stmtKelas = mysqli_prepare($koneksi, $sqlKelas);

if (!$stmtKelas) {
    exit("Query kelas gagal: " . htmlspecialchars(mysqli_error($koneksi)));
}

mysqli_stmt_bind_param($stmtKelas, "i", $guru_id);
mysqli_stmt_execute($stmtKelas);

$resultKelas = mysqli_stmt_get_result($stmtKelas);

$kelas_wali = [];

while ($row = mysqli_fetch_assoc($resultKelas)) {
    $kelas_wali[] = $row;
}

mysqli_stmt_close($stmtKelas);

/* =====================================
   CEK PENUGASAN
===================================== */
if (empty($kelas_wali)) {

    if ($role === 'guru') {
        exit("Anda tidak memiliki penugasan Wali Kelas yang aktif.");
    }

    $error = "Anda belum memiliki penugasan Wali Kelas yang aktif.";

} else {

    $error = '';
}

/* =====================================
   PERSIAPAN DATA SISWA
===================================== */
$resultSiswa = false;
$total_siswa = 0;

/* =====================================
   AMBIL DATA SISWA
   BERDASARKAN KELAS DAN TAHUN AJARAN
===================================== */
if ($error === '') {

    $sqlSiswa = "
        SELECT DISTINCT
            s.id,
            s.nis,
            s.nisn,
            s.nama,
            s.jenis_kelamin,
            s.tanggal_lahir,
            s.alamat,
            s.status_aktif,
            k.nama AS nama_kelas,
            ks.kelas_id,
            ks.tahun_ajaran_id

        FROM t_kelas_siswa ks

        INNER JOIN t_wali_kelas wk
            ON wk.kelas_id = ks.kelas_id
           AND wk.tahun_ajaran_id = ks.tahun_ajaran_id

        INNER JOIN t_siswa s
            ON s.id = ks.siswa_id

        INNER JOIN t_kelas k
            ON k.id = ks.kelas_id

        INNER JOIN t_tahun_ajaran ta
            ON ta.id = wk.tahun_ajaran_id

        WHERE wk.guru_id = ?
          AND wk.status_aktif = 1
          AND ta.status_aktif = 1

          AND CURDATE() BETWEEN wk.tanggal_mulai
                            AND wk.tanggal_selesai

          AND ks.status_aktif = 1
          AND s.status_aktif = 1

        ORDER BY
            ks.kelas_id ASC,
            s.id ASC
    ";

    $stmtSiswa = mysqli_prepare($koneksi, $sqlSiswa);

    if (!$stmtSiswa) {
        exit("Query siswa gagal: " . htmlspecialchars(mysqli_error($koneksi)));
    }

    mysqli_stmt_bind_param($stmtSiswa, "i", $guru_id);
    mysqli_stmt_execute($stmtSiswa);

    $resultSiswa = mysqli_stmt_get_result($stmtSiswa);

    if (!$resultSiswa) {
        exit("Gagal mengambil data siswa: " . htmlspecialchars(mysqli_error($koneksi)));
    }

    $total_siswa = mysqli_num_rows($resultSiswa);
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Data Siswa - Wali Kelas</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css"
          rel="stylesheet">

    <link href="https://cdn.datatables.net/2.3.4/css/dataTables.bootstrap5.min.css"
          rel="stylesheet">
</head>

<body>

<div class="container-fluid">
    <div class="row">

        
<!-- SIDEBAR -->
<aside class="col-md-3 col-lg-2 bg-dark min-vh-100 p-3 position-fixed top-0 bottom-0 start-0 overflow-auto">

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
            <a href="data_siswa.php" class="nav-link active">
                Data Siswa
            </a>
        </li>

        <li class="nav-item">
            <a href="rekap_pelanggaran.php" class="nav-link text-white">
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


        <!-- KONTEN -->
        <main class="col-md-9 col-lg-10 offset-md-3 offset-lg-2 p-4">

            <h2>Data Siswa</h2>

            <p>
                Selamat datang,
                <b><?= htmlspecialchars($nama_guru); ?></b>
            </p>

            <?php if ($error !== '') { ?>

                <div class="alert alert-warning">
                    <?= htmlspecialchars($error); ?>
                </div>

            <?php } else { ?>

                <!-- KELAS TANGGUNG JAWAB -->
                <div class="card shadow-sm mb-4">
                    <div class="card-body">

                        <h5>Kelas Tanggung Jawab</h5>

                        <?php foreach ($kelas_wali as $kelas) { ?>

                            <span class="badge bg-primary p-2 me-2 mb-2">
                                <?= htmlspecialchars($kelas['nama_kelas']); ?>
                            </span>

                        <?php } ?>

                    </div>
                </div>

                <!-- TOTAL SISWA -->
                <div class="card shadow-sm mb-4">
                    <div class="card-body">

                        <h6 class="text-muted">Total Siswa</h6>

                        <h3>
                            <?= number_format($total_siswa); ?>
                        </h3>

                    </div>
                </div>

                <!-- TABEL SISWA -->
                <div class="card shadow-sm">

                    <div class="card-header bg-white">
                        <h5 class="mb-0">Daftar Siswa Kelas Saya</h5>
                    </div>

                    <div class="card-body">

                        <div class="table-responsive">

                            <table id="tabelSiswa"
                                   class="table table-bordered table-striped table-hover">

                                <thead class="table-dark">
                                    <tr>
                                        <th>No</th>
                                        <th>NIS</th>
                                        <th>NISN</th>
                                        <th>Nama Siswa</th>
                                        <th>Jenis Kelamin</th>
                                        <th>Tanggal Lahir</th>
                                        <th>Kelas</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>

                                <tbody>

                                    <?php
                                    $no = 1;

                                    if ($resultSiswa) {
                                        while ($siswa = mysqli_fetch_assoc($resultSiswa)) {
                                    ?>

                                        <tr>
                                            <td><?= $no++; ?></td>

                                            <td>
                                                <?= htmlspecialchars($siswa['nis'] ?? ''); ?>
                                            </td>

                                            <td>
                                                <?= htmlspecialchars($siswa['nisn'] ?? ''); ?>
                                            </td>

                                            <td>
                                                <?= htmlspecialchars($siswa['nama']); ?>
                                            </td>

                                            <td>
                                                <?= htmlspecialchars($siswa['jenis_kelamin'] ?? ''); ?>
                                            </td>

                                            <td>
                                                <?= htmlspecialchars($siswa['tanggal_lahir'] ?? ''); ?>
                                            </td>

                                            <td>
                                                <?= htmlspecialchars($siswa['nama_kelas']); ?>
                                            </td>

                                            <td>
                                                <span class="badge bg-success">
                                                    Aktif
                                                </span>
                                            </td>
                                        </tr>

                                    <?php
                                        }
                                    }
                                    ?>

                                </tbody>

                            </table>

                        </div>

                    </div>
                </div>

            <?php } ?>

        </main>

    </div>
</div>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>

<script src="https://cdn.datatables.net/2.3.4/js/dataTables.min.js"></script>

<script src="https://cdn.datatables.net/2.3.4/js/dataTables.bootstrap5.min.js"></script>

<script>
$(document).ready(function () {

    if ($('#tabelSiswa').length) {

        $('#tabelSiswa').DataTable({
            pageLength: 10,
            lengthMenu: [5, 10, 25, 50],

            language: {
                search: "Cari:",
                lengthMenu: "Tampilkan _MENU_ data",
                info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
                infoEmpty: "Tidak ada data",
                zeroRecords: "Data siswa tidak ditemukan",
                emptyTable: "Belum ada data siswa",

                paginate: {
                    first: "Pertama",
                    last: "Terakhir",
                    next: "Selanjutnya",
                    previous: "Sebelumnya"
                }
            }
        });

    }

});
</script>

</body>
</html>
