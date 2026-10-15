<?php
session_start();

if (!isset($_SESSION['login'])) {
    header("Location: login.php");
    exit;
}

if (strtolower($_SESSION['role'] ?? '') != 'guru') {
    echo "Anda tidak memiliki akses";
    exit;
}

include "config/koneksi.php";

/* AMBIL DATA RIWAYAT PELANGGARAN */
$query = "SELECT
            id,
            nama_siswa,
            nama_kelas,
            nama_pelanggaran,
            tanggal,
            keterangan,
            poin,
            tindakan,
            status
          FROM t_pelanggaran_siswa
          ORDER BY tanggal DESC, id DESC";

$data = mysqli_query($koneksi, $query);

if (!$data) {
    die("Gagal mengambil data riwayat: " . mysqli_error($koneksi));
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Riwayat Pelanggaran</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css"
          rel="stylesheet">
</head>

<body class="bg-body-tertiary">

<div class="container-fluid">
    <div class="row">

        <!-- SIDEBAR GURU -->
        <aside class="col-md-3 col-lg-2 bg-dark min-vh-100 p-3">

            <h5 class="text-white mb-4">
                Sistem Pelanggaran
            </h5>

            <nav class="nav nav-pills flex-column gap-2">

                <a href="dashboard.php"
                   class="nav-link text-white">
                    Dashboard
                </a>

                <a href="catat_pelanggaran.php"
                   class="nav-link text-white">
                    Catat Pelanggaran
                </a>

                <a href="tindakan.php"
                   class="nav-link text-white">
                    Tindakan
                </a>

                <a href="riwayat.php"
                   class="nav-link active">
                    Riwayat
                </a>

                <a href="rekap_poin.php"
                   class="nav-link text-white">
                    Rekap Poin
                </a>

                <hr class="text-secondary">

                <a href="about_me.php"
                   class="nav-link text-white">
                    About Me
                </a>

                <a href="logout.php"
                   class="nav-link text-danger">
                    Logout
                </a>

            </nav>
        </aside>

        <!-- KONTEN UTAMA -->
        <main class="col-md-9 col-lg-10 p-4">

            <h2 class="mb-1">Riwayat Pelanggaran</h2>

            <p class="text-secondary mb-4">
                Daftar catatan pelanggaran siswa yang tersimpan.
            </p>

            <!-- RINGKASAN -->
            <div class="row g-3 mb-4">

                <div class="col-md-4">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body">
                            <p class="text-secondary mb-1">
                                Total Catatan
                            </p>

                            <h3 class="mb-0">
                                <?= mysqli_num_rows($data); ?>
                            </h3>
                        </div>
                    </div>
                </div>

            </div>

            <!-- TABEL RIWAYAT -->
            <div class="card border-0 shadow-sm">

                <div class="card-header bg-white py-3">
                    <h5 class="mb-0">
                        Data Riwayat Pelanggaran
                    </h5>
                </div>

                <div class="card-body">

                    <div class="table-responsive">

                        <table class="table table-bordered table-hover align-middle">

                            <thead class="table-light">
                                <tr>
                                    <th>No</th>
                                    <th>Nama Siswa</th>
                                    <th>Kelas</th>
                                    <th>Jenis Pelanggaran</th>
                                    <th>Tanggal</th>
                                    <th>Poin</th>
                                    <th>Tindakan</th>
                                    <th>Status</th>
                                    <th>Keterangan</th>
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
                                            <?= htmlspecialchars($row['nama_pelanggaran'] ?? '-'); ?>
                                        </td>

                                        <td>
                                            <?= htmlspecialchars($row['tanggal'] ?? '-'); ?>
                                        </td>

                                        <td>
                                            <span class="badge bg-primary">
                                                <?= (int) $row['poin']; ?> poin
                                            </span>
                                        </td>

                                        <td>
                                            <?= htmlspecialchars($row['tindakan'] ?? '') ?: '-'; ?>
                                        </td>

                                        <td>
                                            <?php
                                            $status = $row['status'] ?? '';

                                            if (strtolower($status) == 'selesai') {
                                                echo '<span class="badge bg-success">Selesai</span>';
                                            } elseif (strtolower($status) == 'diproses') {
                                                echo '<span class="badge bg-warning text-dark">Diproses</span>';
                                            } else {
                                                echo '<span class="badge bg-secondary">'
                                                    . htmlspecialchars($status ?: '-')
                                                    . '</span>';
                                            }
                                            ?>
                                        </td>

                                        <td>
                                            <?= htmlspecialchars($row['keterangan'] ?? '-') ?>
                                        </td>
                                    </tr>

                                <?php endwhile; ?>

                            <?php else: ?>

                                <tr>
                                    <td colspan="9"
                                        class="text-center text-secondary py-4">
                                        Belum ada riwayat pelanggaran.
                                    </td>
                                </tr>

                            <?php endif; ?>

                            </tbody>

                        </table>

                    </div>

                    <a href="dashboard.php" class="btn btn-secondary mt-2">
                        Kembali ke Dashboard
                    </a>

                </div>
            </div>

        </main>

    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
