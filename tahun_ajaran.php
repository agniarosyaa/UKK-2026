<?php

session_start();

if (!isset($_SESSION['login'])) {
    header("Location: login.php");
    exit;
}

if ($_SESSION['role'] != 'admin') {
    echo "Anda tidak memiliki akses";
    exit;
}

include "config/koneksi.php";

/* EDIT DATA */
if (isset($_POST['edit'])) {

    $id = $_POST['id'];
    $nama = $_POST['nama'];
    $tanggal_mulai = $_POST['tanggal_mulai'];
    $tanggal_selesai = $_POST['tanggal_selesai'];
    $status_aktif = $_POST['status_aktif'];

    mysqli_query($koneksi, "UPDATE t_tahun_ajaran SET
        nama='$nama',
        tanggal_mulai='$tanggal_mulai',
        tanggal_selesai='$tanggal_selesai',
        status_aktif='$status_aktif'
        WHERE id='$id'");

    header("Location: tahun_ajaran.php");
    exit;
}

/* AMBIL DATA TAHUN AJARAN */
$query = "SELECT * FROM t_tahun_ajaran ORDER BY id ASC";
$result = mysqli_query($koneksi, $query);

if (!$result) {
    die("Query gagal: " . mysqli_error($koneksi));
}

?>

<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Kelola Tahun Ajaran</title>

    <!-- BOOTSTRAP -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css"
          rel="stylesheet">

    <!-- FONT DISAMAKAN DENGAN SISWA -->
    <style>
        body,
        body * {
            font-family: var(--bs-body-font-family) !important;
        }
    </style>

</head>

<body>

<div class="container-fluid">
    <div class="row">

        <!-- SIDEBAR -->
        <div class="col-md-3 col-lg-2 bg-dark min-vh-100 p-3">

            <h4 class="text-white mb-4">Sistem Pelanggaran</h4>

            <ul class="nav nav-pills flex-column">

                <li class="nav-item mb-2">
                    <a href="dashboard.php" class="nav-link text-white">
                        Dashboard
                    </a>
                </li>

                <?php if ($_SESSION['role'] == 'admin') { ?>

                    <li class="nav-item mb-2">
                        <a href="siswa.php" class="nav-link text-white">
                            Kelola Siswa
                        </a>
                    </li>

                    <li class="nav-item mb-2">
                        <a href="guru.php" class="nav-link text-white">
                            Kelola Guru
                        </a>
                    </li>

                    <li class="nav-item mb-2">
                        <a href="kelas.php" class="nav-link text-white">
                            Kelola Kelas
                        </a>
                    </li>

                    <li class="nav-item mb-2">
                        <a href="tahun_ajaran.php" class="nav-link active">
                            Tahun Ajaran
                        </a>
                    </li>

                    <li class="nav-item mb-2">
                        <a href="penempatan_siswa.php" class="nav-link text-white">
                            Penempatan Siswa
                        </a>
                    </li>

                    <li class="nav-item mb-2">
                        <a href="wali_kelas.php" class="nav-link text-white">
                            Wali Kelas
                        </a>
                    </li>

                    <li class="nav-item mb-2">
                        <a href="kategori_pelanggaran.php" class="nav-link text-white">
                            Kategori Pelanggaran
                        </a>
                    </li>

                    <li class="nav-item mb-2">
                        <a href="jenis_pelanggaran.php" class="nav-link text-white">
                            Jenis Pelanggaran
                        </a>
                    </li>

                    <li class="nav-item mb-2">
                        <a href="laporan.php" class="nav-link text-white">
                            Laporan
                        </a>
                    </li>

                <?php } ?>

                <?php if ($_SESSION['role'] == 'guru') { ?>

                    <li class="nav-item mb-2">
                        <a href="catat_pelanggaran.php" class="nav-link text-white">
                            Catat Pelanggaran
                        </a>
                    </li>

                    <li class="nav-item mb-2">
                        <a href="tindakan.php" class="nav-link text-white">
                            Tindakan
                        </a>
                    </li>

                    <li class="nav-item mb-2">
                        <a href="riwayat.php" class="nav-link text-white">
                            Riwayat
                        </a>
                    </li>

                    <li class="nav-item mb-2">
                        <a href="rekap_poin.php" class="nav-link text-white">
                            Rekap Poin
                        </a>
                    </li>

                <?php } ?>

                <hr class="text-secondary">

                <li class="nav-item mb-2">
                    <a href="about_me.php" class="nav-link text-white">
                        About Me
                    </a>
                </li>

                <li class="nav-item">
                    <a href="logout.php" class="nav-link text-danger">
                        Logout
                    </a>
                </li>

            </ul>

        </div>

        <!-- KONTEN UTAMA -->
        <main class="col-md-9 col-lg-10 p-4">

            <h2>Data Tahun Ajaran</h2>

            <a href="dashboard.php" class="btn btn-secondary mb-3">
                Kembali ke Dashboard
            </a>

            <a href="tambah_tahun_ajaran.php" class="btn btn-primary mb-3">
                + Tambah Tahun Ajaran
            </a>

            <!-- TABEL TAHUN AJARAN -->
            <div class="table-responsive">

                <table class="table table-bordered table-hover align-middle">

                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama</th>
                            <th>Tanggal Mulai</th>
                            <th>Tanggal Selesai</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>

                    <tbody>

                    <?php
                    $no = 1;

                    while ($data = mysqli_fetch_assoc($result)) {
                    ?>

                        <tr>

                            <td><?= $no++; ?></td>

                            <td>
                                <?= htmlspecialchars($data['nama']); ?>
                            </td>

                            <td>
                                <?= htmlspecialchars($data['tanggal_mulai']); ?>
                            </td>

                            <td>
                                <?= htmlspecialchars($data['tanggal_selesai']); ?>
                            </td>

                            <td>
                                <?php if ($data['status_aktif'] == 1) { ?>
                                    <span class="badge bg-success">
                                        Aktif
                                    </span>
                                <?php } else { ?>
                                    <span class="badge bg-secondary">
                                        Tidak Aktif
                                    </span>
                                <?php } ?>
                            </td>

                            <!-- AKSI EDIT DAN HAPUS -->
                            <td>

                                <button type="button"
                                        class="btn btn-primary btn-sm"
                                        data-bs-toggle="modal"
                                        data-bs-target="#modalEdit<?= $data['id']; ?>">
                                    Edit
                                </button>

                                <a href="hapus_tahun_ajaran.php?id=<?= $data['id']; ?>"
                                   class="btn btn-danger btn-sm"
                                   onclick="return confirm('Yakin ingin menghapus tahun ajaran ini?')">
                                    Hapus
                                </a>

                            </td>

                        </tr>

                        <!-- MODAL EDIT -->
                        <div class="modal fade"
                             id="modalEdit<?= $data['id']; ?>"
                             tabindex="-1">

                            <div class="modal-dialog">

                                <div class="modal-content">

                                    <form method="POST">

                                        <div class="modal-header">
                                            <h5 class="modal-title">
                                                Edit Tahun Ajaran
                                            </h5>

                                            <button type="button"
                                                    class="btn-close"
                                                    data-bs-dismiss="modal">
                                            </button>
                                        </div>

                                        <div class="modal-body">

                                            <input type="hidden"
                                                   name="id"
                                                   value="<?= $data['id']; ?>">

                                            <div class="mb-3">
                                                <label class="form-label">
                                                    Nama Tahun Ajaran
                                                </label>

                                                <input type="text"
                                                       name="nama"
                                                       class="form-control"
                                                       value="<?= htmlspecialchars($data['nama']); ?>"
                                                       required>
                                            </div>

                                            <div class="mb-3">
                                                <label class="form-label">
                                                    Tanggal Mulai
                                                </label>

                                                <input type="date"
                                                       name="tanggal_mulai"
                                                       class="form-control"
                                                       value="<?= htmlspecialchars($data['tanggal_mulai']); ?>"
                                                       required>
                                            </div>

                                            <div class="mb-3">
                                                <label class="form-label">
                                                    Tanggal Selesai
                                                </label>

                                                <input type="date"
                                                       name="tanggal_selesai"
                                                       class="form-control"
                                                       value="<?= htmlspecialchars($data['tanggal_selesai']); ?>"
                                                       required>
                                            </div>

                                            <div class="mb-3">
                                                <label class="form-label">
                                                    Status
                                                </label>

                                                <select name="status_aktif"
                                                        class="form-select"
                                                        required>
                                                    <option value="1"
                                                        <?= $data['status_aktif'] == 1 ? 'selected' : ''; ?>>
                                                        Aktif
                                                    </option>

                                                    <option value="0"
                                                        <?= $data['status_aktif'] == 0 ? 'selected' : ''; ?>>
                                                        Tidak Aktif
                                                    </option>
                                                </select>
                                            </div>

                                        </div>

                                        <div class="modal-footer">

                                            <button type="button"
                                                    class="btn btn-secondary"
                                                    data-bs-dismiss="modal">
                                                Batal
                                            </button>

                                            <button type="submit"
                                                    name="edit"
                                                    class="btn btn-primary">
                                                Simpan Perubahan
                                            </button>

                                        </div>

                                    </form>

                                </div>

                            </div>

                        </div>

                    <?php } ?>

                    </tbody>

                </table>

            </div>

        </main>

    </div>
</div>

<!-- BOOTSTRAP JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
