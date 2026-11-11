
<?php
session_start();

if (!isset($_SESSION['login']) || $_SESSION['login'] !== true) {
    header("Location: login.php");
    exit;
}

include "config/koneksi.php";

// ==========================================
// CEK ROLE
// ==========================================
$role = strtolower(trim($_SESSION['role'] ?? ''));
$role = str_replace([' ', '-'], '_', $role);

if (!in_array($role, ['admin', 'guru', 'wali_kelas'], true)) {
    exit("Anda tidak memiliki akses.");
}

// ==========================================
// USER LOGIN
// ==========================================
$user_id = (int)(
    $_SESSION['user_id']
    ?? $_SESSION['id_user']
    ?? $_SESSION['id']
    ?? 0
);

// ==========================================
// TENTUKAN MODE WALI KELAS
// ADMIN TETAP ADMIN
// GURU YANG DITUGASKAN SEBAGAI WALI
// MENGGUNAKAN FILTER KELAS WALI
// ==========================================
$mode_wali_kelas = false;

if ($role === 'wali_kelas') {
    $mode_wali_kelas = true;
} elseif ($role === 'guru' && $user_id > 0) {

    $sqlCekWali = "
        SELECT 1
        FROM t_wali_kelas wk
        INNER JOIN t_guru g
            ON g.id = wk.guru_id
        INNER JOIN t_tahun_ajaran ta
            ON ta.id = wk.tahun_ajaran_id
        WHERE g.user_id = ?
          AND wk.status_aktif = 1
          AND ta.status_aktif = 1
          AND CURDATE() BETWEEN wk.tanggal_mulai
                            AND wk.tanggal_selesai
        LIMIT 1
    ";

    $stmtCekWali = mysqli_prepare($koneksi, $sqlCekWali);

    if (!$stmtCekWali) {
        exit("Pemeriksaan penugasan gagal: " .
            htmlspecialchars(mysqli_error($koneksi)));
    }

    mysqli_stmt_bind_param($stmtCekWali, "i", $user_id);
    mysqli_stmt_execute($stmtCekWali);

    $resultCekWali = mysqli_stmt_get_result($stmtCekWali);

    if ($resultCekWali && mysqli_num_rows($resultCekWali) > 0) {
        $mode_wali_kelas = true;
    }

    mysqli_stmt_close($stmtCekWali);
}

// ==========================================
// QUERY DATA PELANGGARAN
// ADMIN : SEMUA DATA YANG BELUM SELESAI
// GURU BIASA : SEMUA DATA YANG BELUM SELESAI
// WALI : HANYA KELAS TANGGUNG JAWAB
//         DAN BELUM SELESAI
// ==========================================

$query = "
    SELECT
        ps.*,

        COALESCE(
            NULLIF(NULLIF(TRIM(p.nama), ''), '0'),
            NULLIF(NULLIF(TRIM(ps.nama_pelanggaran), ''), '0'),
            '-'
        ) AS tampilan_nama_pelanggaran

    FROM t_pelanggaran_siswa ps

    LEFT JOIN t_pelanggaran p
        ON ps.pelanggaran_id = p.id

    WHERE LOWER(TRIM(COALESCE(ps.status, ''))) <> 'selesai'
";

// ==========================================
// FILTER WALI KELAS
// ==========================================

if ($mode_wali_kelas) {

    if ($user_id <= 0) {
        exit("ID pengguna tidak ditemukan.");
    }

    $query .= "
        AND EXISTS (
            SELECT 1
            FROM t_wali_kelas wk

            INNER JOIN t_guru g
                ON g.id = wk.guru_id

            INNER JOIN t_tahun_ajaran ta
                ON ta.id = wk.tahun_ajaran_id

            WHERE g.user_id = ?
              AND wk.kelas_id = ps.kelas_id
              AND wk.tahun_ajaran_id = ps.tahun_ajaran_id
              AND wk.status_aktif = 1
              AND ta.status_aktif = 1
              AND CURDATE() BETWEEN wk.tanggal_mulai
                                AND wk.tanggal_selesai
        )
    ";
}

// ==========================================
// URUTKAN DATA
// ==========================================

$query .= "
    ORDER BY ps.tanggal DESC, ps.id DESC
";

// ==========================================
// EKSEKUSI QUERY
// ==========================================
if ($mode_wali_kelas) {

    $stmt = mysqli_prepare($koneksi, $query);

    if (!$stmt) {
        die("Query gagal: " .
            htmlspecialchars(mysqli_error($koneksi)));
    }

    mysqli_stmt_bind_param($stmt, "i", $user_id);
    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);

} else {

    $result = mysqli_query($koneksi, $query);
}

$error = $result ? '' : mysqli_error($koneksi);
?>

<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Catatan Pelanggaran</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css"
          rel="stylesheet">

    <link href="https://cdn.datatables.net/2.3.4/css/dataTables.bootstrap5.min.css"
          rel="stylesheet">

</head>

<body class="bg-light">

<div class="container-fluid">
    <div class="row">

        <!-- SIDEBAR -->
        <aside class="col-md-3 col-lg-2 bg-dark text-white p-3 position-fixed top-0 bottom-0 start-0 overflow-auto">

            <h5 class="text-white mb-4">
                Sistem Pelanggaran
            </h5>

            <ul class="nav nav-pills flex-column gap-2">

                <li class="nav-item">
                    <a href="dashboard.php" class="nav-link text-white">
                        Dashboard
                    </a>
                </li>

                <?php if ($role === 'admin') { ?>

                    <li class="nav-item">
                        <a href="siswa.php" class="nav-link text-white">
                            Kelola Siswa
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="guru.php" class="nav-link text-white">
                            Kelola Guru
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="kelas.php" class="nav-link text-white">
                            Kelola Kelas
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="tahun_ajaran.php" class="nav-link text-white">
                            Tahun Ajaran
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="penempatan_siswa.php" class="nav-link text-white">
                            Penempatan Siswa
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="wali_kelas.php" class="nav-link text-white">
                            Wali Kelas
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="kategori_pelanggaran.php" class="nav-link text-white">
                            Kategori Pelanggaran
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="jenis_pelanggaran.php" class="nav-link text-white">
                            Jenis Pelanggaran
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="laporan.php" class="nav-link text-white">
                            Laporan
                        </a>
                    </li>

                <?php } ?>

                <?php if ($mode_wali_kelas) { ?>

                    <li class="nav-item">
                        <a href="catat_pelanggaran.php" class="nav-link active">
                            Catat Pelanggaran
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="walikelas/data_siswa.php" class="nav-link text-white">
                            Data Siswa
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="walikelas/rekap_pelanggaran.php" class="nav-link text-white">
                            Rekap Pelanggaran
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="walikelas/laporan.php" class="nav-link text-white">
                            Laporan
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="riwayat.php" class="nav-link text-white">
                            Riwayat
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="rekap_poin.php" class="nav-link text-white">
                            Rekap Poin
                        </a>
                    </li>

                <?php } elseif ($role === 'guru') { ?>

                    <li class="nav-item">
                        <a href="catat_pelanggaran.php" class="nav-link active">
                            Catat Pelanggaran
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="tindakan.php" class="nav-link text-white">
                            Tindakan
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="riwayat.php" class="nav-link text-white">
                            Riwayat
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="rekap_poin.php" class="nav-link text-white">
                            Rekap Poin
                        </a>
                    </li>

                <?php } ?>

                <hr class="text-secondary">

                <li class="nav-item">
                    <a href="about_me.php" class="nav-link text-white">
                        About Me
                    </a>
                </li>

                <li class="nav-item">
                    <a href="logout.php"
                       class="nav-link text-danger"
                       onclick="return confirm('Yakin ingin logout?')">
                        Logout
                    </a>
                </li>

            </ul>

        </aside>

        <!-- KONTEN UTAMA -->
        <main class="col-md-9 col-lg-10 p-4 offset-md-3 offset-lg-2">

            <h2 class="mb-2">
                Catatan Pelanggaran Siswa
            </h2>

            <p class="text-secondary">
                <?php if ($mode_wali_kelas) { ?>
                    Daftar catatan pelanggaran siswa pada kelas tanggung jawab Anda.
                <?php } else { ?>
                    Daftar catatan pelanggaran siswa, baik yang masih diproses maupun yang sudah selesai.
                <?php } ?>
            </p>

            <div class="d-flex gap-2 mb-3 flex-wrap">

                <a href="dashboard.php" class="btn btn-secondary">
                    Kembali ke Dashboard
                </a>

                <?php if (in_array($role, ['admin', 'guru', 'wali_kelas'], true)) { ?>

                    <a href="tambah_catat_pelanggaran.php" class="btn btn-primary">
                        + Tambah Catat Pelanggaran
                    </a>

                <?php } ?>

            </div>

            <!-- NOTIFIKASI BERHASIL -->
            <?php if (isset($_GET['pesan']) && $_GET['pesan'] === 'tambah') { ?>

                <div class="alert alert-success alert-dismissible fade show">

                    Catatan pelanggaran berhasil ditambahkan.

                    <button type="button"
                            class="btn-close"
                            data-bs-dismiss="alert">
                    </button>

                </div>

            <?php } ?>

            <?php if ($error !== '') { ?>

                <div class="alert alert-danger">
                    Gagal mengambil data:
                    <?= htmlspecialchars($error); ?>
                </div>

            <?php } else { ?>

                <div class="card shadow-sm">

                    <div class="card-body">

                        <div class="table-responsive">

                            <table id="tabelPelanggaran"
                                   class="table table-bordered table-hover align-middle">

                                <thead class="table-light">

                                    <tr>
                                        <th>No</th>
                                        <th>Tanggal</th>
                                        <th>Nama Siswa</th>
                                        <th>Nama Kelas</th>
                                        <th>Nama Pelanggaran</th>
                                        <th>Poin</th>
                                        <th>Tindakan</th>
                                        <th>Status</th>
                                    </tr>

                                </thead>

                                <tbody>

                                <?php if (mysqli_num_rows($result) > 0) { ?>

                                    <?php $no = 1; ?>

                                    <?php while ($data = mysqli_fetch_assoc($result)) { ?>

                                        <tr>

                                            <td><?= $no++; ?></td>

                                            <td>
                                                <?= htmlspecialchars($data['tanggal'] ?? '-'); ?>
                                            </td>

                                            <td>
                                                <?= htmlspecialchars($data['nama_siswa'] ?? '-'); ?>
                                            </td>

                                            <td>
                                                <?= htmlspecialchars($data['nama_kelas'] ?? '-'); ?>
                                            </td>

                                            <td>
                                                <?= htmlspecialchars($data['tampilan_nama_pelanggaran'] ?? '-'); ?>
                                            </td>

                                            <td>
                                                <span class="badge bg-danger">
                                                    <?= htmlspecialchars((string)($data['poin'] ?? 0)); ?>
                                                    Poin
                                                </span>
                                            </td>

                                            <td>
                                                <?= htmlspecialchars($data['tindakan'] ?? '-'); ?>
                                            </td>

                                            <td>

                                                <?php
                                                $status = strtolower(trim($data['status'] ?? ''));

                                                if ($status === 'selesai') {
                                                    $warna = 'bg-success';
                                                } elseif (in_array($status, ['proses', 'diproses'], true)) {
                                                    $warna = 'bg-warning text-dark';
                                                } elseif (in_array($status, ['belum diproses', 'belum'], true)) {
                                                    $warna = 'bg-secondary';
                                                } else {
                                                    $warna = 'bg-primary';
                                                }
                                                ?>

                                                <span class="badge <?= $warna; ?>">
                                                    <?= htmlspecialchars($data['status'] ?? '-'); ?>
                                                </span>

                                            </td>

                                        </tr>

                                    <?php } ?>

                                <?php } else { ?>

                                    <tr>
                                        <td colspan="8" class="text-center">
                                            Belum ada catatan pelanggaran pada kelas tanggung jawab Anda.
                                        </td>
                                    </tr>

                                <?php } ?>

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

    $('#tabelPelanggaran').DataTable({

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

            emptyTable: "Belum ada catatan pelanggaran",

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
