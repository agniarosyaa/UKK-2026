
<?php
session_start();

if (!isset($_SESSION['login'])) {
    header("Location: ../login.php");
    exit;
}

include "../config/koneksi.php";

$role = strtolower(trim($_SESSION['role'] ?? ''));

if ($role != 'wali_kelas') {
    exit("Anda tidak memiliki akses ke halaman ini.");
}

/* AMBIL ID USER LOGIN */
$user_id = $_SESSION['user_id']
    ?? $_SESSION['id_user']
    ?? $_SESSION['id']
    ?? null;

if (!$user_id) {
    exit("ID akun tidak ditemukan. Periksa session pada login.php.");
}

/* CARI DATA GURU */
$stmtGuru = mysqli_prepare(
    $koneksi,
    "SELECT id, nama
     FROM t_guru
     WHERE user_id = ?
     LIMIT 1"
);

mysqli_stmt_bind_param($stmtGuru, "i", $user_id);
mysqli_stmt_execute($stmtGuru);

$resultGuru = mysqli_stmt_get_result($stmtGuru);
$dataGuru = mysqli_fetch_assoc($resultGuru);

if (!$dataGuru) {
    exit("Data guru tidak ditemukan.");
}

$guru_id = (int) $dataGuru['id'];
$nama_guru = $dataGuru['nama'];

/* AMBIL KELAS YANG DITUGASKAN */
$stmtKelas = mysqli_prepare(
    $koneksi,
    "SELECT
        wk.kelas_id,
        k.nama AS nama_kelas
     FROM t_wali_kelas wk
     JOIN t_kelas k ON k.id = wk.kelas_id
     JOIN t_tahun_ajaran ta ON ta.id = wk.tahun_ajaran_id
     WHERE wk.guru_id = ?
       AND wk.status_aktif = 1
       AND ta.status_aktif = 1
     ORDER BY k.nama ASC"
);

mysqli_stmt_bind_param($stmtKelas, "i", $guru_id);
mysqli_stmt_execute($stmtKelas);

$resultKelas = mysqli_stmt_get_result($stmtKelas);

$kelas_wali = [];
$kelas_id = [];

while ($row = mysqli_fetch_assoc($resultKelas)) {
    $kelas_wali[] = $row;
    $kelas_id[] = (int) $row['kelas_id'];
}

if (count($kelas_id) == 0) {
    $error = "Anda belum memiliki penugasan wali kelas yang aktif.";
    $resultSiswa = false;
} else {

    /*
       DATA SISWA HANYA DARI KELAS WALI KELAS LOGIN
    */
    $placeholders = implode(',', array_fill(0, count($kelas_id), '?'));
    $types = str_repeat('i', count($kelas_id));

    $sql = "
        SELECT DISTINCT
            s.id,
            s.nis,
            s.nisn,
            s.nama,
            s.jenis_kelamin,
            s.tanggal_lahir,
            s.alamat,
            s.status_aktif,
            k.nama AS nama_kelas
        FROM t_siswa s
        JOIN t_kelas_siswa ks
            ON ks.siswa_id = s.id
        JOIN t_kelas k
            ON k.id = ks.kelas_id
        WHERE ks.kelas_id IN ($placeholders)
        ORDER BY s.nama ASC
    ";

    $stmtSiswa = mysqli_prepare($koneksi, $sql);

    mysqli_stmt_bind_param($stmtSiswa, $types, ...$kelas_id);
    mysqli_stmt_execute($stmtSiswa);

    $resultSiswa = mysqli_stmt_get_result($stmtSiswa);
    $error = '';
}

/* HITUNG JUMLAH SISWA */
$total_siswa = $resultSiswa ? mysqli_num_rows($resultSiswa) : 0;

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
                    <a href="siswa.php" class="nav-link active">
                        Data Siswa
                    </a>
                </li>

                <li class="nav-item">
                    <a href="rekap_pelanggaran.php" class="nav-link text-white">
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

            <h2>Data Siswa</h2>

            <p>
                Selamat datang,
                <b><?= htmlspecialchars($nama_guru); ?></b>
            </p>

            <?php if (!empty($error)) { ?>

                <div class="alert alert-warning">
                    <?= htmlspecialchars($error); ?>
                </div>

            <?php } else { ?>

                <!-- INFORMASI KELAS -->
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

                <!-- JUMLAH SISWA -->
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
                                                <?= htmlspecialchars($siswa['nis']); ?>
                                            </td>

                                            <td>
                                                <?= htmlspecialchars($siswa['nisn']); ?>
                                            </td>

                                            <td>
                                                <?= htmlspecialchars($siswa['nama']); ?>
                                            </td>

                                            <td>
                                                <?= htmlspecialchars($siswa['jenis_kelamin']); ?>
                                            </td>

                                            <td>
                                                <?= htmlspecialchars($siswa['tanggal_lahir']); ?>
                                            </td>

                                            <td>
                                                <?= htmlspecialchars($siswa['nama_kelas']); ?>
                                            </td>

                                            <td>
                                                <?php if ($siswa['status_aktif'] == 1) { ?>
                                                    <span class="badge bg-success">
                                                        Aktif
                                                    </span>
                                                <?php } else { ?>
                                                    <span class="badge bg-secondary">
                                                        Tidak Aktif
                                                    </span>
                                                <?php } ?>
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
});
</script>

</body>
</html>
