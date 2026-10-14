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

    $tahun_ajaran_id = $_POST['tahun_ajaran_id'];
    $kelas_id = $_POST['kelas_id'];
    $guru_id = $_POST['guru_id'];
    $tanggal_mulai = $_POST['tanggal_mulai'];
    $tanggal_selesai = $_POST['tanggal_selesai'];
    $status_aktif = $_POST['status_aktif'];

    $query = "INSERT INTO t_wali_kelas
        (tahun_ajaran_id, kelas_id, guru_id, tanggal_mulai,
         tanggal_selesai, status_aktif, created_at, update_at)
        VALUES
        ('$tahun_ajaran_id', '$kelas_id', '$guru_id',
         '$tanggal_mulai', '$tanggal_selesai', '$status_aktif',
         NOW(), NOW())";

    if (!mysqli_query($koneksi, $query)) {
        die("Gagal menambah data: " . mysqli_error($koneksi));
    }

    header("Location: wali_kelas.php");
    exit;
}

/* EDIT DATA */
if (isset($_POST['edit'])) {

    $id = $_POST['id'];
    $tahun_ajaran_id = $_POST['tahun_ajaran_id'];
    $kelas_id = $_POST['kelas_id'];
    $guru_id = $_POST['guru_id'];
    $tanggal_mulai = $_POST['tanggal_mulai'];
    $tanggal_selesai = $_POST['tanggal_selesai'];
    $status_aktif = $_POST['status_aktif'];

    $query = "UPDATE t_wali_kelas SET
        tahun_ajaran_id='$tahun_ajaran_id',
        kelas_id='$kelas_id',
        guru_id='$guru_id',
        tanggal_mulai='$tanggal_mulai',
        tanggal_selesai='$tanggal_selesai',
        status_aktif='$status_aktif',
        update_at=NOW()
        WHERE id='$id'";

    if (!mysqli_query($koneksi, $query)) {
        die("Gagal mengubah data: " . mysqli_error($koneksi));
    }

    header("Location: wali_kelas.php");
    exit;
}

/* HAPUS DATA */
if (isset($_GET['hapus'])) {

    $id = $_GET['hapus'];

    if (!mysqli_query($koneksi, "DELETE FROM t_wali_kelas WHERE id='$id'")) {
        die("Gagal menghapus data: " . mysqli_error($koneksi));
    }

    header("Location: wali_kelas.php");
    exit;
}

/* DATA WALI KELAS */
$data = mysqli_query(
    $koneksi,
    "SELECT * FROM t_wali_kelas ORDER BY id ASC"
);

if (!$data) {
    die("Query gagal: " . mysqli_error($koneksi));
}

/* DATA PILIHAN UNTUK FORM */
$tahun_ajaran = mysqli_query(
    $koneksi,
    "SELECT * FROM t_tahun_ajaran ORDER BY id ASC"
);

$kelas = mysqli_query(
    $koneksi,
    "SELECT * FROM t_kelas ORDER BY id ASC"
);

$guru = mysqli_query(
    $koneksi,
    "SELECT * FROM t_guru ORDER BY id ASC"
);
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Kelola Wali Kelas</title>

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
                        <a href="wali_kelas.php" class="nav-link active">
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

            <h2>Data Wali Kelas</h2>

            <a href="dashboard.php" class="btn btn-secondary mb-3">
                Kembali ke Dashboard
            </a>

            <button class="btn btn-primary mb-3"
                    data-bs-toggle="modal"
                    data-bs-target="#modalTambah">
                + Tambah Wali Kelas
            </button>

            <!-- TABEL WALI KELAS -->
            <div class="table-responsive">

                <table class="table table-bordered table-hover align-middle">

                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Tahun Ajaran ID</th>
                            <th>Kelas ID</th>
                            <th>Guru ID</th>
                            <th>Tanggal Mulai</th>
                            <th>Tanggal Selesai</th>
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
                            <td><?= htmlspecialchars($row['tahun_ajaran_id']); ?></td>
                            <td><?= htmlspecialchars($row['kelas_id']); ?></td>
                            <td><?= htmlspecialchars($row['guru_id']); ?></td>
                            <td><?= htmlspecialchars($row['tanggal_mulai']); ?></td>
                            <td><?= htmlspecialchars($row['tanggal_selesai']); ?></td>

                            <td>
                                <?php if ($row['status_aktif'] == 1) { ?>
                                    <span class="badge bg-success">Aktif</span>
                                <?php } else { ?>
                                    <span class="badge bg-secondary">Tidak Aktif</span>
                                <?php } ?>
                            </td>

                            <td>
                                <button type="button"
                                        class="btn btn-primary btn-sm"
                                        data-bs-toggle="modal"
                                        data-bs-target="#modalEdit<?= $row['id']; ?>">
                                    Edit
                                </button>

                                <a href="wali_kelas.php?hapus=<?= $row['id']; ?>"
                                   class="btn btn-danger btn-sm"
                                   onclick="return confirm('Yakin ingin menghapus data wali kelas ini?')">
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
                                            <h5 class="modal-title">Edit Wali Kelas</h5>

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
                                                <label class="form-label">Tahun Ajaran</label>
                                                <select name="tahun_ajaran_id"
                                                        class="form-select"
                                                        required>
                                                    <?php
                                                    mysqli_data_seek($tahun_ajaran, 0);
                                                    while ($ta = mysqli_fetch_assoc($tahun_ajaran)) {
                                                    ?>
                                                        <option value="<?= $ta['id']; ?>"
                                                            <?= $ta['id'] == $row['tahun_ajaran_id'] ? 'selected' : ''; ?>>
                                                            ID Tahun Ajaran: <?= $ta['id']; ?>
                                                        </option>
                                                    <?php } ?>
                                                </select>
                                            </div>

                                            <div class="mb-3">
                                                <label class="form-label">Kelas</label>
                                                <select name="kelas_id"
                                                        class="form-select"
                                                        required>
                                                    <?php
                                                    mysqli_data_seek($kelas, 0);
                                                    while ($k = mysqli_fetch_assoc($kelas)) {
                                                    ?>
                                                        <option value="<?= $k['id']; ?>"
                                                            <?= $k['id'] == $row['kelas_id'] ? 'selected' : ''; ?>>
                                                            ID Kelas: <?= $k['id']; ?>
                                                        </option>
                                                    <?php } ?>
                                                </select>
                                            </div>

                                            <div class="mb-3">
                                                <label class="form-label">Guru</label>
                                                <select name="guru_id"
                                                        class="form-select"
                                                        required>
                                                    <?php
                                                    mysqli_data_seek($guru, 0);
                                                    while ($g = mysqli_fetch_assoc($guru)) {
                                                    ?>
                                                        <option value="<?= $g['id']; ?>"
                                                            <?= $g['id'] == $row['guru_id'] ? 'selected' : ''; ?>>
                                                            ID Guru: <?= $g['id']; ?>
                                                        </option>
                                                    <?php } ?>
                                                </select>
                                            </div>

                                            <div class="mb-3">
                                                <label class="form-label">Tanggal Mulai</label>
                                                <input type="date"
                                                       name="tanggal_mulai"
                                                       class="form-control"
                                                       value="<?= htmlspecialchars($row['tanggal_mulai']); ?>"
                                                       required>
                                            </div>

                                            <div class="mb-3">
                                                <label class="form-label">Tanggal Selesai</label>
                                                <input type="date"
                                                       name="tanggal_selesai"
                                                       class="form-control"
                                                       value="<?= htmlspecialchars($row['tanggal_selesai']); ?>"
                                                       required>
                                            </div>

                                            <div class="mb-3">
                                                <label class="form-label">Status</label>
                                                <select name="status_aktif"
                                                        class="form-select"
                                                        required>
                                                    <option value="1"
                                                        <?= $row['status_aktif'] == 1 ? 'selected' : ''; ?>>
                                                        Aktif
                                                    </option>
                                                    <option value="0"
                                                        <?= $row['status_aktif'] == 0 ? 'selected' : ''; ?>>
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

<!-- MODAL TAMBAH -->
<div class="modal fade" id="modalTambah" tabindex="-1">

    <div class="modal-dialog">
        <div class="modal-content">

            <form method="POST">

                <div class="modal-header">
                    <h5 class="modal-title">Tambah Wali Kelas</h5>

                    <button type="button"
                            class="btn-close"
                            data-bs-dismiss="modal">
                    </button>
                </div>

                <div class="modal-body">

                    <div class="mb-3">
                        <label class="form-label">Tahun Ajaran</label>
                        <select name="tahun_ajaran_id" class="form-select" required>
                            <option value="">Pilih Tahun Ajaran</option>
                            <?php
                            mysqli_data_seek($tahun_ajaran, 0);
                            while ($ta = mysqli_fetch_assoc($tahun_ajaran)) {
                            ?>
                                <option value="<?= $ta['id']; ?>">
                                    ID Tahun Ajaran: <?= $ta['id']; ?>
                                </option>
                            <?php } ?>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Kelas</label>
                        <select name="kelas_id" class="form-select" required>
                            <option value="">Pilih Kelas</option>
                            <?php
                            mysqli_data_seek($kelas, 0);
                            while ($k = mysqli_fetch_assoc($kelas)) {
                            ?>
                                <option value="<?= $k['id']; ?>">
                                    ID Kelas: <?= $k['id']; ?>
                                </option>
                            <?php } ?>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Guru</label>
                        <select name="guru_id" class="form-select" required>
                            <option value="">Pilih Guru</option>
                            <?php
                            mysqli_data_seek($guru, 0);
                            while ($g = mysqli_fetch_assoc($guru)) {
                            ?>
                                <option value="<?= $g['id']; ?>">
                                    ID Guru: <?= $g['id']; ?>
                                </option>
                            <?php } ?>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Tanggal Mulai</label>
                        <input type="date"
                               name="tanggal_mulai"
                               class="form-control"
                               required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Tanggal Selesai</label>
                        <input type="date"
                               name="tanggal_selesai"
                               class="form-control"
                               required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Status</label>
                        <select name="status_aktif" class="form-select" required>
                            <option value="1">Aktif</option>
                            <option value="0">Tidak Aktif</option>
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
