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
    $kategori_id = (int) $_POST['pelanggaran_kategori_id'];
    $kode = mysqli_real_escape_string($koneksi, $_POST['kode']);
    $nama = mysqli_real_escape_string($koneksi, $_POST['nama']);
    $poin = (int) $_POST['poin'];
    $deskripsi = mysqli_real_escape_string($koneksi, $_POST['deskripsi']);

    $query = "INSERT INTO t_pelanggaran
        (pelanggaran_kategori_id, kode, nama, poin, deskripsi,
         status_aktif, created_at, update_at)
        VALUES
        ('$kategori_id', '$kode', '$nama', '$poin', '$deskripsi',
         1, NOW(), NOW())";

    if (!mysqli_query($koneksi, $query)) {
        die("Gagal menambahkan data: " . mysqli_error($koneksi));
    }

    header("Location: jenis_pelanggaran.php");
    exit;
}

/* EDIT DATA */
if (isset($_POST['edit'])) {
    $id = (int) $_POST['id'];
    $kategori_id = (int) $_POST['pelanggaran_kategori_id'];
    $kode = mysqli_real_escape_string($koneksi, $_POST['kode']);
    $nama = mysqli_real_escape_string($koneksi, $_POST['nama']);
    $poin = (int) $_POST['poin'];
    $deskripsi = mysqli_real_escape_string($koneksi, $_POST['deskripsi']);
    $status_aktif = (int) $_POST['status_aktif'];

    $query = "UPDATE t_pelanggaran SET
        pelanggaran_kategori_id='$kategori_id',
        kode='$kode',
        nama='$nama',
        poin='$poin',
        deskripsi='$deskripsi',
        status_aktif='$status_aktif',
        update_at=NOW()
        WHERE id='$id'";

    if (!mysqli_query($koneksi, $query)) {
        die("Gagal mengubah data: " . mysqli_error($koneksi));
    }

    header("Location: jenis_pelanggaran.php");
    exit;
}

/* HAPUS DATA */
if (isset($_GET['hapus'])) {
    $id = (int) $_GET['hapus'];

    if (!mysqli_query($koneksi, "DELETE FROM t_pelanggaran WHERE id='$id'")) {
        die("Gagal menghapus data. Data mungkin masih digunakan pada tabel lain.");
    }

    header("Location: jenis_pelanggaran.php");
    exit;
}

/* DATA KATEGORI */
$kategori = mysqli_query(
    $koneksi,
    "SELECT id, nama FROM t_pelanggaran_kategori ORDER BY id ASC"
);

if (!$kategori) {
    die("Query kategori gagal: " . mysqli_error($koneksi));
}

$daftarKategori = [];

while ($kat = mysqli_fetch_assoc($kategori)) {
    $daftarKategori[] = $kat;
}

/* DATA JENIS PELANGGARAN */
$data = mysqli_query($koneksi, "
    SELECT p.*, k.nama AS nama_kategori
    FROM t_pelanggaran p
    LEFT JOIN t_pelanggaran_kategori k
        ON p.pelanggaran_kategori_id = k.id
    ORDER BY p.id ASC
");

if (!$data) {
    die("Query data gagal: " . mysqli_error($koneksi));
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Kelola Jenis Pelanggaran</title>

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
                    <a href="kategori_pelanggaran.php" class="nav-link text-white">
                        Kategori Pelanggaran
                    </a>
                </li>

                <li class="nav-item mb-2">
                    <a href="jenis_pelanggaran.php" class="nav-link active">
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
        <main class="col-md-9 col-lg-10 content-area">

            <h2>Data Jenis Pelanggaran</h2>

            <a href="dashboard.php" class="btn btn-secondary mb-3">
                Kembali ke Dashboard
            </a>

            <button class="btn btn-primary mb-3"
                    data-bs-toggle="modal"
                    data-bs-target="#modalTambah">
                + Tambah Jenis Pelanggaran
            </button>

            <!-- TABEL DATA -->
            <div class="table-responsive">

                <table class="table table-bordered table-hover align-middle">

                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Kategori</th>
                            <th>Kode</th>
                            <th>Nama</th>
                            <th>Poin</th>
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
                                <?= htmlspecialchars($row['nama_kategori'] ?? '-'); ?>
                            </td>

                            <td>
                                <?= htmlspecialchars($row['kode']); ?>
                            </td>

                            <td>
                                <?= htmlspecialchars($row['nama']); ?>
                            </td>

                            <td>
                                <?= htmlspecialchars($row['poin']); ?>
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

                                <!-- HAPUS MERAH -->
                                <a href="jenis_pelanggaran.php?hapus=<?= $row['id']; ?>"
                                   class="btn btn-danger btn-sm"
                                   onclick="return confirm('Yakin ingin menghapus jenis pelanggaran ini?')">
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
                                                Edit Jenis Pelanggaran
                                            </h5>

                                            <button type="button"
                                                    class="btn-close"
                                                    data-bs-dismiss="modal"></button>
                                        </div>

                                        <div class="modal-body">

                                            <input type="hidden"
                                                   name="id"
                                                   value="<?= $row['id']; ?>">

                                            <div class="mb-3">
                                                <label class="form-label">Kategori Pelanggaran</label>

                                                <select name="pelanggaran_kategori_id"
                                                        class="form-select"
                                                        required>
                                                    <?php foreach ($daftarKategori as $kat) { ?>
                                                        <option
                                                            value="<?= $kat['id']; ?>"
                                                            <?= $row['pelanggaran_kategori_id'] == $kat['id'] ? 'selected' : ''; ?>>
                                                            <?= htmlspecialchars($kat['nama']); ?>
                                                        </option>
                                                    <?php } ?>
                                                </select>
                                            </div>

                                            <div class="mb-3">
                                                <label class="form-label">Kode</label>
                                                <input type="text"
                                                       name="kode"
                                                       class="form-control"
                                                       value="<?= htmlspecialchars($row['kode']); ?>"
                                                       required>
                                            </div>

                                            <div class="mb-3">
                                                <label class="form-label">Nama Pelanggaran</label>
                                                <input type="text"
                                                       name="nama"
                                                       class="form-control"
                                                       value="<?= htmlspecialchars($row['nama']); ?>"
                                                       required>
                                            </div>

                                            <div class="mb-3">
                                                <label class="form-label">Poin</label>
                                                <input type="number"
                                                       name="poin"
                                                       class="form-control"
                                                       min="0"
                                                       value="<?= htmlspecialchars($row['poin']); ?>"
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
                    <h5 class="modal-title">Tambah Jenis Pelanggaran</h5>

                    <button type="button"
                            class="btn-close"
                            data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">

                    <div class="mb-3">
                        <label class="form-label">Kategori Pelanggaran</label>

                        <select name="pelanggaran_kategori_id"
                                class="form-select"
                                required>
                            <option value="">-- Pilih Kategori --</option>

                            <?php foreach ($daftarKategori as $kat) { ?>
                                <option value="<?= $kat['id']; ?>">
                                    <?= htmlspecialchars($kat['nama']); ?>
                                </option>
                            <?php } ?>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Kode</label>
                        <input type="text"
                               name="kode"
                               class="form-control"
                               required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Nama Pelanggaran</label>
                        <input type="text"
                               name="nama"
                               class="form-control"
                               required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Poin</label>
                        <input type="number"
                               name="poin"
                               class="form-control"
                               min="0"
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
