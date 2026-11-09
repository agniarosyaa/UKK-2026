
<?php
session_start();

if (!isset($_SESSION['login'])) {
    header("Location: login.php");
    exit;
}

$role = strtolower(trim($_SESSION['role'] ?? ''));

if (!in_array($role, ['admin', 'guru'], true)) {
    echo "Anda tidak memiliki akses";
    exit;
}

include "config/koneksi.php";

/* ==========================================
   AMBIL TAHUN AJARAN AKTIF
========================================== */

$query_tahun = "
    SELECT id
    FROM t_tahun_ajaran
    WHERE status_aktif = 1
    ORDER BY id DESC
    LIMIT 1
";

$result_tahun = mysqli_query($koneksi, $query_tahun);

if (!$result_tahun) {
    die("Gagal mengambil tahun ajaran: " . mysqli_error($koneksi));
}

$tahun_aktif = mysqli_fetch_assoc($result_tahun);
$tahun_ajaran_id = $tahun_aktif['id'] ?? 0;

/* ==========================================
   REKAP TOTAL POIN SETIAP SISWA
   KELAS DIAMBIL DARI PENEMPATAN AKTIF
========================================== */

$query = "
    SELECT
        ps.siswa_id,
        COALESCE(s.nama, ps.nama_siswa) AS nama_siswa,
        COALESCE(k.nama, '-') AS nama_kelas,
        COUNT(ps.id) AS jumlah_pelanggaran,
        COALESCE(SUM(ps.poin), 0) AS total_poin

    FROM t_pelanggaran_siswa ps

    LEFT JOIN t_siswa s
        ON s.id = ps.siswa_id

    LEFT JOIN t_kelas_siswa ks
        ON ks.siswa_id = ps.siswa_id
        AND ks.tahun_ajaran_id = ?
        AND ks.status_aktif = 1

    LEFT JOIN t_kelas k
        ON k.id = ks.kelas_id

    GROUP BY
        ps.siswa_id,
        s.nama,
        ps.nama_siswa,
        k.id,
        k.nama

    ORDER BY total_poin DESC, nama_siswa ASC
";

$stmt = mysqli_prepare($koneksi, $query);

if (!$stmt) {
    die("Gagal menyiapkan query: " . mysqli_error($koneksi));
}

mysqli_stmt_bind_param($stmt, "i", $tahun_ajaran_id);
mysqli_stmt_execute($stmt);

$data = mysqli_stmt_get_result($stmt);

if (!$data) {
    die("Gagal mengambil data rekap poin.");
}

/* ==========================================
   RINGKASAN
========================================== */

$total_siswa = mysqli_num_rows($data);
$total_catatan = 0;
$total_poin_semua = 0;

while ($ringkasan = mysqli_fetch_assoc($data)) {
    $total_catatan += (int) $ringkasan['jumlah_pelanggaran'];
    $total_poin_semua += (int) $ringkasan['total_poin'];
}

/* ==========================================
   JALANKAN KEMBALI QUERY UNTUK TABEL
========================================== */

mysqli_stmt_execute($stmt);
$data = mysqli_stmt_get_result($stmt);

if (!$data) {
    die("Gagal mengambil data tabel.");
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Rekap Poin Siswa</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css"
          rel="stylesheet">

    <link href="https://cdn.datatables.net/2.3.4/css/dataTables.bootstrap5.min.css"
          rel="stylesheet">
</head>

<body class="bg-body-tertiary">

<div class="container-fluid">
    <div class="row">

        <!-- SIDEBAR -->
        <aside class="col-md-3 col-lg-2 bg-dark text-white p-3 position-fixed top-0 bottom-0 start-0 overflow-auto">

            <h5 class="text-white mb-4">
                Sistem Pelanggaran
            </h5>

            <nav class="nav nav-pills flex-column gap-2">

                <a href="dashboard.php"
                   class="nav-link text-white">
                    Dashboard
                </a>

                <?php if ($role == 'admin'): ?>

                    <a href="siswa.php"
                       class="nav-link text-white">
                        Kelola Siswa
                    </a>

                    <a href="guru.php"
                       class="nav-link text-white">
                        Kelola Guru
                    </a>

                    <a href="kelas.php"
                       class="nav-link text-white">
                        Kelola Kelas
                    </a>

                    <a href="tahun_ajaran.php"
                       class="nav-link text-white">
                        Tahun Ajaran
                    </a>

                    <a href="penempatan_siswa.php"
                       class="nav-link text-white">
                        Penempatan Siswa
                    </a>

                    <a href="wali_kelas.php"
                       class="nav-link text-white">
                        Wali Kelas
                    </a>

                    <a href="kategori_pelanggaran.php"
                       class="nav-link text-white">
                        Kategori Pelanggaran
                    </a>

                    <a href="jenis_pelanggaran.php"
                       class="nav-link text-white">
                        Jenis Pelanggaran
                    </a>

                    <a href="laporan.php"
                       class="nav-link text-white">
                        Laporan
                    </a>

                <?php elseif ($role == 'guru'): ?>

                    <a href="catat_pelanggaran.php"
                       class="nav-link text-white">
                        Catat Pelanggaran
                    </a>

                    <a href="tindakan.php"
                       class="nav-link text-white">
                        Tindakan
                    </a>

                    <a href="riwayat.php"
                       class="nav-link text-white">
                        Riwayat
                    </a>

                <?php endif; ?>

                <a href="rekap_poin.php"
                   class="nav-link active">
                    Rekap Poin
                </a>

                <hr class="text-secondary">

                <a href="about_me.php"
                   class="nav-link text-white">
                    About Me
                </a>

                <a href="logout.php"
                   class="nav-link text-danger"
                   onclick="return confirm('Yakin ingin logout?')">
                    Logout
                </a>

            </nav>
        </aside>

        <!-- KONTEN UTAMA -->
        <main class="col-md-9 col-lg-10 p-4 offset-md-3 offset-lg-2">

            <h2 class="mb-1">Rekap Poin Siswa</h2>

            <p class="text-secondary mb-4">
                Ringkasan jumlah pelanggaran dan total poin setiap siswa.
            </p>

            <!-- RINGKASAN -->
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
                                <?= number_format(
                                    $total_poin_semua,
                                    0,
                                    ',',
                                    '.'
                                ); ?>
                            </h3>

                        </div>
                    </div>
                </div>

            </div>

            <!-- TABEL REKAP -->
            <div class="card border-0 shadow-sm">

                <div class="card-header bg-primary text-white py-3">
                    Data Rekap Poin
                </div>

                <div class="card-body">

                    <div class="table-responsive">

                        <table id="tabelRekap"
                               class="table table-bordered table-hover align-middle">

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

                            <?php if (mysqli_num_rows($data) > 0): ?>

                                <?php $no = 1; ?>

                                <?php while ($row = mysqli_fetch_assoc($data)): ?>

                                    <tr>

                                        <td>
                                            <?= $no++; ?>
                                        </td>

                                        <td>
                                            <?= htmlspecialchars($row['nama_siswa'] ?? '-'); ?>
                                        </td>

                                        <td>
                                            <?= htmlspecialchars($row['nama_kelas'] ?? '-'); ?>
                                        </td>

                                        <td>
                                            <?= (int) $row['jumlah_pelanggaran']; ?> kali
                                        </td>

                                        <td>
                                            <span class="badge bg-primary">
                                                <?= number_format(
                                                    (int) $row['total_poin'],
                                                    0,
                                                    ',',
                                                    '.'
                                                ); ?> poin
                                            </span>
                                        </td>

                                    </tr>

                                <?php endwhile; ?>

                            <?php else: ?>

                                <tr>
                                    <td colspan="5"
                                        class="text-center text-secondary py-4">
                                        Belum ada data poin pelanggaran siswa.
                                    </td>
                                </tr>

                            <?php endif; ?>

                            </tbody>

                        </table>

                    </div>

                    <a href="dashboard.php"
                       class="btn btn-secondary mt-2">
                        Kembali ke Dashboard
                    </a>

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

            zeroRecords: "Data tidak ditemukan",

            emptyTable: "Belum ada data poin pelanggaran siswa",

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
