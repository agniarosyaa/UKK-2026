
<?php
session_start();

if (!isset($_SESSION['login'])) {
    header("Location: login.php");
    exit;
}

if (strtolower($_SESSION['role'] ?? '') != 'guru') {
    exit("Anda tidak memiliki akses");
}

include "config/koneksi.php";

/* AMBIL NOTIFIKASI */
$notifikasi = $_SESSION['notifikasi'] ?? null;
unset($_SESSION['notifikasi']);

/* AMBIL DATA TINDAKAN */
$data = mysqli_query(
    $koneksi,
    "SELECT id, nama_siswa, nama_kelas,
            nama_pelanggaran, tanggal, poin, tindakan, status
     FROM t_pelanggaran_siswa
     ORDER BY tanggal DESC, id DESC"
);

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

        <!-- SIDEBAR TETAP -->
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

            <h1 class="mb-2">Tindakan Pelanggaran</h1>

            <!-- NOTIFIKASI -->
            <?php if ($notifikasi) { ?>
                <div class="alert alert-<?= htmlspecialchars($notifikasi['tipe']); ?> alert-dismissible fade show"
                     role="alert"
                     id="pesan">

                    <?= htmlspecialchars($notifikasi['pesan']); ?>

                    <button type="button"
                            class="btn-close"
                            data-bs-dismiss="alert"
                            aria-label="Close"></button>
                </div>
            <?php } ?>

            <p class="text-secondary mb-4">
                Kelola tindakan dan status pelanggaran siswa.
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

                                            <td><?= $no++ ?></td>

                                            <td>
                                                <?= htmlspecialchars($row['nama_siswa'] ?? '') ?>
                                            </td>

                                            <td>
                                                <?= htmlspecialchars($row['nama_kelas'] ?? '') ?>
                                            </td>

                                            <td>
                                                <?= htmlspecialchars($row['nama_pelanggaran'] ?? '') ?>
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

                                                <?php if (($row['status'] ?? '') == 'Selesai'): ?>

                                                    <span class="badge bg-success">
                                                        Selesai
                                                    </span>

                                                <?php else: ?>

                                                    <span class="badge bg-warning text-dark">
                                                        <?= htmlspecialchars($row['status'] ?? 'Diproses') ?>
                                                    </span>

                                                <?php endif; ?>

                                            </td>

                                            <!-- TOMBOL AKSI -->
                                            <td>

                                                <a href="edit_tindakan.php?id=<?= (int)$row['id'] ?>"
                                                   class="btn btn-primary btn-sm">
                                                    Edit
                                                </a>

                                                <a href="hapus_tindakan.php?id=<?= (int)$row['id'] ?>"
                                                   class="btn btn-danger btn-sm"
                                                   onclick="return confirm('Yakin ingin menghapus tindakan ini?')">
                                                    Hapus
                                                </a>

                                            </td>

                                        </tr>

                                    <?php endwhile; ?>

                                <?php else: ?>

                                    <tr>
                                        <td colspan="9"
                                            class="text-center text-secondary py-4">
                                            Belum ada data pelanggaran siswa.
                                        </td>
                                    </tr>

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
