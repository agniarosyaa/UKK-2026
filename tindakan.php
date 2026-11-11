<?php
session_start();

if (!isset($_SESSION['login'])) {
    header("Location: login.php");
    exit;
}

if (strtolower(trim($_SESSION['role'] ?? '')) != 'guru') {
    exit("Anda tidak memiliki akses");
}

include "config/koneksi.php";

/* AMBIL NOTIFIKASI */
$notifikasi = $_SESSION['notifikasi'] ?? null;
unset($_SESSION['notifikasi']);

/* ==========================================
   AMBIL DATA TINDAKAN

   HANYA MENAMPILKAN:
   - Diproses
   - Belum Diproses

   TIDAK MENAMPILKAN:
   - Selesai
========================================== */

$sql = "
    SELECT
        ps.id,
        ps.nama_siswa,

        COALESCE(k.nama, ps.nama_kelas, '-') AS nama_kelas,

        COALESCE(
            NULLIF(NULLIF(TRIM(p.nama), ''), '0'),
            NULLIF(NULLIF(TRIM(ps.nama_pelanggaran), ''), '0'),
            '-'
        ) AS nama_pelanggaran,

        ps.tanggal,
        ps.poin,
        ps.tindakan,
        ps.status

    FROM t_pelanggaran_siswa ps

    LEFT JOIN t_kelas_siswa ks
        ON ks.siswa_id = ps.siswa_id
        AND ks.tahun_ajaran_id = ps.tahun_ajaran_id
        AND ks.status_aktif = 1

    LEFT JOIN t_kelas k
        ON k.id = ks.kelas_id

    LEFT JOIN t_pelanggaran p
        ON p.id = ps.pelanggaran_id

    WHERE LOWER(TRIM(COALESCE(ps.status, ''))) NOT IN ('', 'selesai')

    ORDER BY ps.tanggal DESC, ps.id DESC
";

$data = mysqli_query($koneksi, $sql);

if (!$data) {
    die("Gagal mengambil data: " . mysqli_error($koneksi));
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Tindakan Pelanggaran</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css"
          rel="stylesheet">

    <link href="https://cdn.datatables.net/2.3.4/css/dataTables.bootstrap5.min.css"
          rel="stylesheet">
</head>

<body>

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

                <li class="nav-item">
                    <a href="catat_pelanggaran.php" class="nav-link text-white">
                        Catat Pelanggaran
                    </a>
                </li>

                <li class="nav-item">
                    <a href="tindakan.php" class="nav-link active">
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

        <!-- KONTEN -->
        <main class="col-md-9 col-lg-10 p-4 offset-md-3 offset-lg-2">

            <h1 class="mb-2">
                Tindakan Pelanggaran
            </h1>

            <!-- NOTIFIKASI -->
            <?php if ($notifikasi) { ?>

                <div class="alert alert-<?= htmlspecialchars($notifikasi['tipe']); ?> alert-dismissible fade show"
                     role="alert"
                     id="pesan">

                    <?= htmlspecialchars($notifikasi['pesan']); ?>

                    <button type="button"
                            class="btn-close"
                            data-bs-dismiss="alert"
                            aria-label="Close">
                    </button>

                </div>

            <?php } ?>

            <p class="text-secondary mb-4">
                Kelola tindakan dan status pelanggaran siswa.
                Hanya menampilkan pelanggaran yang belum selesai ditangani.
            </p>

            <div class="card border-0 shadow-sm">

                <div class="card-header bg-primary text-white py-3">
                    Data Tindakan Pelanggaran
                </div>

                <div class="card-body p-4">

                    <div class="table-responsive">

                        <table id="tabelTindakan"
                               class="table table-bordered table-hover align-middle">

                            <thead class="table-light">
                                <tr>
                                    <th>No</th>
                                    <th>Nama Siswa</th>
                                    <th>Kelas</th>
                                    <th>Pelanggaran</th>
                                    <th>Tanggal</th>
                                    <th>Poin</th>
                                    <th>Tindakan</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>

                            <tbody>

                                <?php if (mysqli_num_rows($data) > 0): ?>

                                    <?php $no = 1; ?>

                                    <?php while ($row = mysqli_fetch_assoc($data)): ?>

                                        <tr>

                                            <td>
                                                <?= $no++ ?>
                                            </td>

                                            <td>
                                                <?= htmlspecialchars($row['nama_siswa'] ?? '') ?>
                                            </td>

                                            <td>
                                                <?= htmlspecialchars($row['nama_kelas'] ?? '-') ?>
                                            </td>

                                            <td>
                                                <?= htmlspecialchars($row['nama_pelanggaran'] ?? '-') ?>
                                            </td>

                                            <td>
                                                <?= htmlspecialchars($row['tanggal'] ?? '') ?>
                                            </td>

                                            <td>
                                                <span class="badge bg-primary">
                                                    <?= (int)$row['poin'] ?> poin
                                                </span>
                                            </td>

                                            <td>
                                                <?= htmlspecialchars($row['tindakan'] ?? '') ?: '-' ?>
                                            </td>

                                            <td>
<?php
$status = strtolower(trim($row['status'] ?? ''));

if ($status == 'diproses' || $status == 'proses') {
    $warna = 'bg-warning text-dark';
} elseif ($status == 'belum diproses' || $status == 'belum') {
    $warna = 'bg-secondary';
} else {
    $warna = 'bg-secondary';
}
?>

<span class="badge <?= $warna ?>">
    <?= htmlspecialchars($row['status'] ?? 'Belum Diproses') ?>
</span>

                                            </td>
<!-- TOMBOL AKSI -->
<td>
    <a href="edit_tindakan.php?id=<?= (int)$row['id'] ?>"
       class="btn btn-primary btn-sm">
        Tindakan
    </a>
</td>
                                        </tr>

                                    <?php endwhile; ?>

                                <?php endif; ?>

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

    $('#tabelTindakan').DataTable({

        pageLength: 10,

        lengthMenu: [5, 10, 25, 50],

        columnDefs: [
            {
                orderable: false,
                targets: [0, 8]
            }
        ],

        language: {

            search: "Cari:",

            lengthMenu: "Tampilkan _MENU_ data",

            info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",

            infoEmpty: "Tidak ada data",

            infoFiltered: "(difilter dari _MAX_ data)",

            zeroRecords: "Data tidak ditemukan",

            emptyTable: "Belum ada data pelanggaran siswa",

            paginate: {

                first: "Pertama",

                last: "Terakhir",

                next: "Selanjutnya",

                previous: "Sebelumnya"

            }

        }

    });

});

/* HILANGKAN NOTIFIKASI OTOMATIS */

setTimeout(function () {

    let pesan = document.getElementById('pesan');

    if (pesan) {
        let alert = bootstrap.Alert.getOrCreateInstance(pesan);
        alert.close();
    }

}, 2000);

</script>

</body>
</html>