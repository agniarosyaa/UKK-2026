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

/* TAMBAH DATA */
if (isset($_POST['tambah'])) {

    $nama = $_POST['nama'];
    $tingkat = $_POST['tingkat'];
    $jurusan = $_POST['jurusan'];

    mysqli_query($koneksi, "INSERT INTO t_kelas
        (nama, tingkat, jurusan, status_aktif, created_at, update_at)
        VALUES
        ('$nama', '$tingkat', '$jurusan', 1, NOW(), NOW())");

    header("Location: kelas.php");
    exit;
}

/* EDIT DATA */
if (isset($_POST['edit'])) {

    $id = $_POST['id'];
    $nama = $_POST['nama'];
    $tingkat = $_POST['tingkat'];
    $jurusan = $_POST['jurusan'];

    mysqli_query($koneksi, "UPDATE t_kelas SET
        nama='$nama',
        tingkat='$tingkat',
        jurusan='$jurusan',
        update_at=NOW()
        WHERE id='$id'");

    header("Location: kelas.php");
    exit;
}

/* HAPUS DATA */
if (isset($_GET['hapus'])) {

    $id = $_GET['hapus'];

    mysqli_query($koneksi, "DELETE FROM t_kelas WHERE id='$id'");

    header("Location: kelas.php");
    exit;
}

/* DATA KELAS */
$data = mysqli_query($koneksi, "SELECT * FROM t_kelas ORDER BY id ASC");
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Data Kelas</title>

    <!-- BOOTSTRAP -->
    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css"
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

        <!-- SIDEBAR SAMA SEPERTI DASHBOARD -->
        <div class="col-md-3 col-lg-2 bg-dark min-vh-100 p-3">

            <h4 class="text-white mb-4">
                Sistem Pelanggaran
            </h4>

            <ul class="nav nav-pills flex-column">

                <li class="nav-item mb-2">
                    <a href="dashboard.php" class="nav-link text-white">
                        Dashboard
                    </a>
                </li>

                <!-- MENU KHUSUS ADMIN -->
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
                        <a href="kelas.php" class="nav-link active">
                            Kelola Kelas
                        </a>
                    </li>

                    <li class="nav-item mb-2">
                        <a href="tahun_ajaran.php" class="nav-link text-white">
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

                <!-- MENU KHUSUS GURU -->
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

            <h2>Data Kelas</h2>

            <a href="dashboard.php" class="btn btn-secondary mb-3">
                Kembali ke Dashboard
            </a>

            <button class="btn btn-primary mb-3"
                    data-bs-toggle="modal"
                    data-bs-target="#modalTambah">
                + Tambah Kelas
            </button>

            <!-- TABEL KELAS -->
            <div class="table-responsive">

                <table class="table table-bordered table-hover align-middle">

                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama</th>
                            <th>Tingkat</th>
                            <th>Jurusan</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>

                    <tbody>

                    <?php
                    $no = 1;

                    while ($row = mysqli_fetch_assoc($data)) {
                    ?>

                        <tr>
                            <td><?= $no++; ?></td>

                            <td>
                                <?= htmlspecialchars($row['nama']); ?>
                            </td>

                            <td>
                                <?= htmlspecialchars($row['tingkat']); ?>
                            </td>

                            <td>
                                <?= htmlspecialchars($row['jurusan']); ?>
                            </td>

                            <td>
                                <?php if ($row['status_aktif'] == 1) { ?>
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
                                        data-bs-target="#modalEdit<?= $row['id']; ?>">
                                    Edit
                                </button>

                                <a href="kelas.php?hapus=<?= $row['id']; ?>"
                                   class="btn btn-danger btn-sm"
                                   onclick="return confirm('Yakin ingin menghapus data ini?')">
                                    Hapus
                                </a>
                            </td>
                        </tr>

                        <!-- MODAL EDIT -->
                        <div class="modal fade"
                             id="modalEdit<?= $row['id']; ?>"
                             tabindex="-1">

                            <div class="modal-dialog">
                                <div class="modal-content">

                                    <form method="POST">

                                        <div class="modal-header">
                                            <h5 class="modal-title">
                                                Edit Kelas
                                            </h5>

                                            <button type="button"
                                                    class="btn-close"
                                                    data-bs-dismiss="modal">
                                            </button>
                                        </div>

                                        <div class="modal-body">

                                            <input type="hidden"
                                                   name="id"
                                                   value="<?= $row['id']; ?>">

                                            <div class="mb-3">
                                                <label>Nama Kelas</label>
                                                <input type="text"
                                                       name="nama"
                                                       class="form-control"
                                                       value="<?= htmlspecialchars($row['nama']); ?>"
                                                       required>
                                            </div>

                                            <div class="mb-3">
                                                <label>Tingkat</label>
                                                <input type="number"
                                                       name="tingkat"
                                                       class="form-control"
                                                       value="<?= htmlspecialchars($row['tingkat']); ?>"
                                                       required>
                                            </div>

                                            <div class="mb-3">
                                                <label>Jurusan</label>
                                                <input type="text"
                                                       name="jurusan"
                                                       class="form-control"
                                                       value="<?= htmlspecialchars($row['jurusan']); ?>"
                                                       required>
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

<!-- MODAL TAMBAH -->
<div class="modal fade" id="modalTambah" tabindex="-1">

    <div class="modal-dialog">
        <div class="modal-content">

            <form method="POST">

                <div class="modal-header">
                    <h5 class="modal-title">Tambah Kelas</h5>

                    <button type="button"
                            class="btn-close"
                            data-bs-dismiss="modal">
                    </button>
                </div>

                <div class="modal-body">

                    <div class="mb-3">
                        <label>Nama Kelas</label>
                        <input type="text"
                               name="nama"
                               class="form-control"
                               required>
                    </div>

                    <div class="mb-3">
                        <label>Tingkat</label>
                        <input type="number"
                               name="tingkat"
                               class="form-control"
                               required>
                    </div>

                    <div class="mb-3">
                        <label>Jurusan</label>
                        <input type="text"
                               name="jurusan"
                               class="form-control"
                               required>
                    </div>

                </div>

                <div class="modal-footer">

                    <button type="button"
                            class="btn btn-secondary"
                            data-bs-dismiss="modal">
                        Batal
                    </button>

                    <button type="submit"
                            name="tambah"
                            class="btn btn-primary">
                        Simpan
                    </button>

                </div>

            </form>
        </div>
    </div>
</div>

<!-- BOOTSTRAP JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
