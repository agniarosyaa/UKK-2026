
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

$query = "SELECT * FROM t_pelanggaran_siswa ORDER BY tanggal DESC";
$result = mysqli_query($koneksi, $query);

if (!$result) {
    die("Gagal mengambil data laporan: " . mysqli_error($koneksi));
}

$total_data = mysqli_num_rows($result);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Pelanggaran Siswa</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css"
          rel="stylesheet">

    <!-- CSS DataTables -->
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
                <a class="nav-link text-white" href="penempatan_siswa.php">Penempatan Siswa</a>
                <a class="nav-link text-white" href="wali_kelas.php">Wali Kelas</a>
                <a class="nav-link text-white" href="kategori_pelanggaran.php">Kategori Pelanggaran</a>
                <a class="nav-link text-white" href="jenis_pelanggaran.php">Jenis Pelanggaran</a>
                <a class="nav-link active" href="laporan.php">Laporan</a>
                <a class="nav-link text-white" href="about_me.php">About Me</a>

                <hr class="border-secondary">

                <a class="nav-link text-danger" href="logout.php"
                   onclick="return confirm('Yakin ingin logout?')">
                    Logout
                </a>
            </nav>

        </aside>

        <!-- KONTEN -->
        <main class="col-md-9 col-lg-10 p-3 p-md-4">

            <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-4">
                <div>
                    <h3 class="mb-1">Laporan Pelanggaran Siswa</h3>
                    <p class="text-muted mb-0">
                        Rekap seluruh data pelanggaran siswa
                    </p>
                </div>

                <button onclick="window.print()"
                        class="btn btn-primary d-print-none">
                    Cetak Laporan
                </button>
            </div>

            <div class="card border-0 shadow-sm">
                <div class="card-body">

                    <div class="table-responsive">
                        <table id="tabelLaporan"
                               class="table table-bordered table-hover align-middle">

                            <thead class="table-dark">
                                <tr>
                                    <th>No</th>
                                    <th>Nama Siswa</th>
                                    <th>Kelas</th>
                                    <th>Nama Guru</th>
                                    <th>Jenis Pelanggaran</th>
                                    <th>Kategori ID</th>
                                    <th>Tanggal</th>
                                    <th>Keterangan</th>
                                    <th>Poin</th>
                                    <th>Tindakan</th>
                                    <th>Status</th>
                                </tr>
                            </thead>

                            <tbody>
                            <?php
                            $no = 1;

                            while ($row = mysqli_fetch_assoc($result)) {
                            ?>
                                <tr>
                                    <td><?= $no++; ?></td>

                                    <td>
                                        <?= htmlspecialchars($row['nama_siswa'] ?? ''); ?>
                                    </td>

                                    <td>
                                        <?= htmlspecialchars($row['nama_kelas'] ?? ''); ?>
                                    </td>

                                    <td>
                                        <?= htmlspecialchars($row['nama_guru'] ?? ''); ?>
                                    </td>

                                    <td>
                                        <?= htmlspecialchars($row['nama_pelanggaran'] ?? ''); ?>
                                    </td>

                                    <td>
                                        <?= htmlspecialchars($row['pelanggaran_kategori_id'] ?? ''); ?>
                                    </td>

                                    <td>
                                        <?= !empty($row['tanggal'])
                                            ? date('d-m-Y', strtotime($row['tanggal']))
                                            : '-'; ?>
                                    </td>

                                    <td>
                                        <?= htmlspecialchars($row['keterangan'] ?? ''); ?>
                                    </td>

                                    <td>
                                        <?= htmlspecialchars($row['poin'] ?? ''); ?>
                                    </td>

                                    <td>
                                        <?= htmlspecialchars($row['tindakan'] ?? ''); ?>
                                    </td>

                                    <td>
                                        <?php
                                        $status = $row['status'] ?? '';

                                        if ($status == 'Selesai') {
                                            echo '<span class="badge bg-success">'
                                                . htmlspecialchars($status)
                                                . '</span>';
                                        } elseif ($status == 'Diproses') {
                                            echo '<span class="badge bg-warning text-dark">'
                                                . htmlspecialchars($status)
                                                . '</span>';
                                        } else {
                                            echo '<span class="badge bg-secondary">'
                                                . htmlspecialchars($status ?: '-')
                                                . '</span>';
                                        }
                                        ?>
                                    </td>
                                </tr>
                            <?php
                            }
                            ?>
                            </tbody>

                        </table>
                    </div>

                    <p class="text-muted mb-0 mt-3">
                        Total data pelanggaran:
                        <strong><?= $total_data; ?></strong>
                    </p>

                </div>
            </div>

        </main>
    </div>
</div>

<!-- JavaScript -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.datatables.net/2.3.4/js/dataTables.min.js"></script>
<script src="https://cdn.datatables.net/2.3.4/js/dataTables.bootstrap5.min.js"></script>

<script>
$(document).ready(function () {
    $('#tabelLaporan').DataTable({
        pageLength: 10,
        lengthMenu: [5, 10, 25, 50],
        searching: true,
        ordering: true,
        order: [[6, 'desc']],
        columnDefs: [
            {
                orderable: false,
                targets: 0
            }
        ],
        language: {
            search: "Cari:",
            lengthMenu: "Tampilkan _MENU_ data",
            info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
            infoEmpty: "Tidak ada data",
            infoFiltered: "(difilter dari _MAX_ data)",
            zeroRecords: "Data tidak ditemukan",
            emptyTable: "Belum ada data pelanggaran.",
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
