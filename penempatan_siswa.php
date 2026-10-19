<?php
session_start();

if (!isset($_SESSION['login'])) {
    header("Location: login.php");
    exit;
}

if (strtolower($_SESSION['role'] ?? '') != 'admin') {
    echo "Anda tidak memiliki akses";
    exit;
}

include "config/koneksi.php";

/* HAPUS PENEMPATAN SISWA */
if (isset($_GET['hapus'])) {
    $id = (int) $_GET['hapus'];

    $stmt = mysqli_prepare(
        $koneksi,
        "DELETE FROM t_kelas_siswa WHERE id = ?"
    );

    mysqli_stmt_bind_param($stmt, "i", $id);

    if (mysqli_stmt_execute($stmt)) {
        mysqli_stmt_close($stmt);
        header("Location: penempatan_siswa.php?pesan=hapus");
        exit;
    }

    mysqli_stmt_close($stmt);
    header("Location: penempatan_siswa.php?pesan=gagal");
    exit;
}

/* AMBIL DATA PENEMPATAN SISWA */
$query = "
    SELECT
        ks.id,
        s.nama AS nama_siswa,
        k.nama AS nama_kelas,
        ta.nama AS tahun_ajaran
    FROM t_kelas_siswa ks
    LEFT JOIN t_siswa s ON ks.siswa_id = s.id
    LEFT JOIN t_kelas k ON ks.kelas_id = k.id
    LEFT JOIN t_tahun_ajaran ta ON ks.tahun_ajaran_id = ta.id
    ORDER BY ks.id DESC
";

$result = mysqli_query($koneksi, $query);

if (!$result) {
    die("Gagal mengambil data: " . mysqli_error($koneksi));
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Penempatan Siswa</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css"
          rel="stylesheet">

    <!-- DataTables Bootstrap CSS -->
    <link href="https://cdn.datatables.net/2.3.4/css/dataTables.bootstrap5.min.css"
          rel="stylesheet">
</head>

<body class="bg-light">

<div class="container-fluid">
    <div class="row">

        <!-- SIDEBAR -->
        <aside class="col-md-3 col-lg-2 bg-dark min-vh-100 p-3">

            <h5 class="text-white mb-4">Sistem Pelanggaran</h5>

            <nav class="nav nav-pills flex-column gap-2">

                <a class="nav-link text-white" href="dashboard.php">Dashboard</a>
                <a class="nav-link text-white" href="siswa.php">Kelola Siswa</a>
                <a class="nav-link text-white" href="guru.php">Kelola Guru</a>
                <a class="nav-link text-white" href="kelas.php">Kelola Kelas</a>
                <a class="nav-link text-white" href="tahun_ajaran.php">Tahun Ajaran</a>
                <a class="nav-link active" href="penempatan_siswa.php">Penempatan Siswa</a>
                <a class="nav-link text-white" href="wali_kelas.php">Wali Kelas</a>
                <a class="nav-link text-white" href="kategori_pelanggaran.php">Kategori Pelanggaran</a>
                <a class="nav-link text-white" href="jenis_pelanggaran.php">Jenis Pelanggaran</a>
                <a class="nav-link text-white" href="laporan.php">Laporan</a>

                <hr class="text-secondary">

                <a class="nav-link text-danger"
                   href="logout.php"
                   onclick="return confirm('Yakin ingin logout?')">
                    Logout
                </a>

            </nav>
        </aside>

        <!-- KONTEN UTAMA -->
        <main class="col-md-9 col-lg-10 p-4">

            <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-4">

                <div>
                    <h3 class="mb-1">Penempatan Siswa</h3>
                    <p class="text-muted mb-0">
                        Mengatur kelas siswa berdasarkan tahun ajaran.
                    </p>
                </div>

                <a href="tambah_penempatan_siswa.php" class="btn btn-primary">
                    + Tambah Penempatan
                </a>

            </div>

            <!-- NOTIFIKASI -->
            <?php if (isset($_GET['pesan']) && $_GET['pesan'] == 'hapus') { ?>
                <div class="alert alert-success">
                    Data berhasil dihapus.
                </div>
            <?php } ?>

            <?php if (isset($_GET['pesan']) && $_GET['pesan'] == 'gagal') { ?>
                <div class="alert alert-danger">
                    Data gagal dihapus.
                </div>
            <?php } ?>

            <!-- TABEL PENEMPATAN SISWA -->
            <div class="card shadow-sm">
                <div class="card-body">

                    <div class="table-responsive">

                        <table id="tabelPenempatan"
                               class="table table-bordered table-hover align-middle">

                            <thead class="table-dark">
                                <tr>
                                    <th>No</th>
                                    <th>Nama Siswa</th>
                                    <th>Kelas</th>
                                    <th>Tahun Ajaran</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>

                            <tbody>

                            <?php
                            $no = 1;

                            if (mysqli_num_rows($result) > 0) {
                                while ($row = mysqli_fetch_assoc($result)) {
                            ?>

                                <tr>
                                    <td><?= $no++; ?></td>

                                    <td>
                                        <?= htmlspecialchars($row['nama_siswa'] ?? '-'); ?>
                                    </td>

                                    <td>
                                        <?= htmlspecialchars($row['nama_kelas'] ?? '-'); ?>
                                    </td>

                                    <td>
                                        <?= htmlspecialchars($row['tahun_ajaran'] ?? '-'); ?>
                                    </td>

                                    <td class="text-nowrap">

                                        <a href="edit_penempatan_siswa.php?id=<?= (int) $row['id']; ?>"
                                           class="btn btn-primary btn-sm">
                                            Edit
                                        </a>

                                        <a href="penempatan_siswa.php?hapus=<?= (int) $row['id']; ?>"
                                           class="btn btn-danger btn-sm"
                                           onclick="return confirm('Yakin ingin menghapus penempatan siswa ini?')">
                                            Hapus
                                        </a>

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

        </main>

    </div>
</div>

<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>

<!-- DataTables JS -->
<script src="https://cdn.datatables.net/2.3.4/js/dataTables.min.js"></script>
<script src="https://cdn.datatables.net/2.3.4/js/dataTables.bootstrap5.min.js"></script>

<!-- AKTIFKAN DATATABLES -->
<script>
$(document).ready(function () {

    $('#tabelPenempatan').DataTable({
        pageLength: 10,
        lengthMenu: [5, 10, 25, 50],
        searching: true,
        ordering: true,

        columnDefs: [
            {
                orderable: false,
                targets: [0, 4]
            }
        ],

        language: {
            search: "Cari:",
            lengthMenu: "Tampilkan _MENU_ data",
            info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
            infoEmpty: "Tidak ada data",
            infoFiltered: "(difilter dari _MAX_ data)",
            zeroRecords: "Data tidak ditemukan",
            emptyTable: "Belum ada data penempatan siswa",

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
