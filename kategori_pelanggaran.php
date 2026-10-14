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
    $nama = mysqli_real_escape_string($koneksi, $_POST['nama']);
    $deskripsi = mysqli_real_escape_string($koneksi, $_POST['deskripsi']);

    mysqli_query($koneksi, "INSERT INTO t_pelanggaran_kategori
        (nama, deskripsi, status_aktif, created_at, update_at)
        VALUES ('$nama', '$deskripsi', 1, NOW(), NOW())");

    header("Location: kategori_pelanggaran.php");
    exit;
}

/* EDIT DATA */
if (isset($_POST['edit'])) {
    $id = (int) $_POST['id'];
    $nama = mysqli_real_escape_string($koneksi, $_POST['nama']);
    $deskripsi = mysqli_real_escape_string($koneksi, $_POST['deskripsi']);
    $status_aktif = (int) $_POST['status_aktif'];

    mysqli_query($koneksi, "UPDATE t_pelanggaran_kategori SET
        nama='$nama',
        deskripsi='$deskripsi',
        status_aktif='$status_aktif',
        update_at=NOW()
        WHERE id='$id'");

    header("Location: kategori_pelanggaran.php");
    exit;
}

/* HAPUS DATA */
if (isset($_GET['hapus'])) {
    $id = (int) $_GET['hapus'];

    mysqli_query($koneksi, "DELETE FROM t_pelanggaran_kategori WHERE id='$id'");

    header("Location: kategori_pelanggaran.php");
    exit;
}

/* DATA KATEGORI */
$data = mysqli_query($koneksi, "SELECT * FROM t_pelanggaran_kategori ORDER BY id ASC");

if (!$data) {
    die("Query gagal: " . mysqli_error($koneksi));
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Kelola Kategori Pelanggaran</title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css"
        rel="stylesheet">

    <style>
        body {
            font-family: var(--bs-body-font-family);
            background-color: white;
            color: #111827;
        }

        .sidebar {
            min-height: 100vh;
        }

        .sidebar .nav-link {
            color: white;
            border-radius: 5px;
        }

        .sidebar .nav-link:hover {
            background-color: #495057;
        }

        .sidebar .nav-link.active {
            background-color: #0d6efd;
        }

        .content-area {
            padding: 30px;
            min-width: 0;
        }

        @media (max-width: 767px) {
            .content-area {
                padding: 20px 12px;
            }
        }
    </style>
</head>

<body>

<div class="container-fluid">
    <div class="row">

        <!-- SIDEBAR -->
        <div class="col-md-3 col-lg-2 bg-dark sidebar p-3">

            <h4 class="text-white mb-4">Sistem Pelanggaran</h4>

            <ul class="nav nav-pills flex-column">

                <li class="nav-item mb-2">
                    <a href="dashboard.php" class="nav-link text-white">
                        Dashboard
                    </a>
                </li>

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
                    <a href="wali_kelas.php" class="nav-link text-white">
                        Wali Kelas
                    </a>
                </li>

                <li class="nav-item mb-2">
                    <a href="kategori_pelanggaran.php" class="nav-link active">
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

            <h2>Data Kategori Pelanggaran</h2>

            <a href="dashboard.php" class="btn btn-secondary mb-3">
                Kembali ke Dashboard
            </a>

            <button class="btn btn-primary mb-3"
                    data-bs-toggle="modal"
                    data-bs-target="#modalTambah">
                + Tambah Kategori
            </button>

            <!-- TABEL KATEGORI -->
            <div class="table-responsive">

                <table class="table table-bordered table-hover align-middle">

                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama</th>
                            <th>Deskripsi</th>
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
                                <?= htmlspecialchars($row['deskripsi']); ?>
                            </td>

                            <td>
                                <?php if ($row['status_aktif'] == 1) { ?>
                                    <span class="badge bg-success">Aktif</span>
                                <?php } else { ?>
                                    <span class="badge bg-secondary">Tidak Aktif</span>
                                <?php } ?>
                            </td>

                            <td>
                                <!-- EDIT BIRU -->
                                <button type="button"
                                        class="btn btn-primary btn-sm"
                                        data-bs-toggle="modal"
                                        data-bs-target="#modalEdit<?= $row['id']; ?>">
                                    Edit
                                </button>

                                <!-- HAPUS -->
                                <a href="kategori_pelanggaran.php?hapus=<?= $row['id']; ?>"
                                   class="btn btn-danger btn-sm"
                                   onclick="return confirm('Yakin ingin menghapus kategori ini?')">
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
                                            <h5 class="modal-title">Edit Kategori Pelanggaran</h5>

                                            <button type="button"
                                                    class="btn-close"
                                                    data-bs-dismiss="modal"></button>
                                        </div>

                                        <div class="modal-body">

                                            <input type="hidden"
                                                   name="id"
                                                   value="<?= $row['id']; ?>">

                                            <div class="mb-3">
                                                <label class="form-label">Nama Kategori</label>
                                                <input type="text"
                                                       name="nama"
                                                       class="form-control"
                                                       value="<?= htmlspecialchars($row['nama']); ?>"
                                                       required>
                                            </div>

                                            <div class="mb-3">
                                                <label class="form-label">Deskripsi</label>
                                                <textarea name="deskripsi"
                                                          class="form-control"
                                                          rows="3"
                                                          required><?= htmlspecialchars($row['deskripsi']); ?></textarea>
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
                    <h5 class="modal-title">Tambah Kategori Pelanggaran</h5>

                    <button type="button"
                            class="btn-close"
                            data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">

                    <div class="mb-3">
                        <label class="form-label">Nama Kategori</label>
                        <input type="text"
                               name="nama"
                               class="form-control"
                               required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Deskripsi</label>
                        <textarea name="deskripsi"
                                  class="form-control"
                                  rows="3"
                                  required></textarea>
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

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
