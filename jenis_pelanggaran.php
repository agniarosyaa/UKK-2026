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

$pesan = "";

/* TAMBAH JENIS PELANGGARAN */
if (isset($_POST['tambah'])) {
    $kategori_id = (int) $_POST['pelanggaran_kategori_id'];
    $kode = mysqli_real_escape_string($koneksi, $_POST['kode']);
    $nama = mysqli_real_escape_string($koneksi, $_POST['nama']);
    $poin = (int) $_POST['poin'];
    $deskripsi = mysqli_real_escape_string(
        $koneksi,
        $_POST['deskripsi'] ?? ''
    );

    $query = "INSERT INTO t_pelanggaran
        (pelanggaran_kategori_id, kode, nama, poin, deskripsi)
        VALUES
        ('$kategori_id', '$kode', '$nama', '$poin', '$deskripsi')";

    if (mysqli_query($koneksi, $query)) {
        header("Location: jenis_pelanggaran.php?pesan=tambah");
        exit;
    } else {
        $pesan = "Gagal menambahkan data: " . mysqli_error($koneksi);
    }
}

/* EDIT JENIS PELANGGARAN */
if (isset($_POST['edit'])) {
    $id = (int) $_POST['id'];
    $kategori_id = (int) $_POST['pelanggaran_kategori_id'];
    $kode = mysqli_real_escape_string($koneksi, $_POST['kode']);
    $nama = mysqli_real_escape_string($koneksi, $_POST['nama']);
    $poin = (int) $_POST['poin'];
    $deskripsi = mysqli_real_escape_string(
        $koneksi,
        $_POST['deskripsi'] ?? ''
    );

    $query = "UPDATE t_pelanggaran SET
        pelanggaran_kategori_id = '$kategori_id',
        kode = '$kode',
        nama = '$nama',
        poin = '$poin',
        deskripsi = '$deskripsi'
        WHERE id = '$id'";

    if (mysqli_query($koneksi, $query)) {
        header("Location: jenis_pelanggaran.php?pesan=edit");
        exit;
    } else {
        $pesan = "Gagal mengubah data: " . mysqli_error($koneksi);
    }
}

/* HAPUS JENIS PELANGGARAN */
if (isset($_GET['hapus'])) {
    $id = (int) $_GET['hapus'];

    $query = "DELETE FROM t_pelanggaran WHERE id = '$id'";

    if (mysqli_query($koneksi, $query)) {
        header("Location: jenis_pelanggaran.php?pesan=hapus");
        exit;
    } else {
        $pesan = "Gagal menghapus data. Data mungkin masih digunakan.";
    }
}

/* PENCARIAN */
$cari = trim($_GET['cari'] ?? '');
$cari_sql = mysqli_real_escape_string($koneksi, $cari);

$query = "SELECT * FROM t_pelanggaran
          WHERE kode LIKE '%$cari_sql%'
             OR nama LIKE '%$cari_sql%'
             OR deskripsi LIKE '%$cari_sql%'
             OR CAST(poin AS CHAR) LIKE '%$cari_sql%'
          ORDER BY id ASC";

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
    <title>Jenis Pelanggaran</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css"
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
                <a class="nav-link active" href="jenis_pelanggaran.php">Jenis Pelanggaran</a>
                <a class="nav-link text-white" href="laporan.php">Laporan</a>

                <hr class="text-secondary">

                <a class="nav-link text-white" href="about_me.php">About Me</a>

                <a class="nav-link text-danger"
                   href="logout.php"
                   onclick="return confirm('Yakin ingin logout?')">
                    Logout
                </a>
            </nav>
        </aside>

        <!-- KONTEN UTAMA -->
        <main class="col-md-9 col-lg-10 p-4">

            <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4">
                <div>
                    <h3 class="mb-1">Jenis Pelanggaran</h3>
                    <p class="text-secondary mb-0">
                        Kelola data jenis pelanggaran siswa.
                    </p>
                </div>

                <button type="button"
                        class="btn btn-primary"
                        data-bs-toggle="modal"
                        data-bs-target="#modalTambah">
                    + Tambah Jenis Pelanggaran
                </button>
            </div>

            <!-- PESAN -->
            <?php if ($pesan != '') { ?>
                <div class="alert alert-danger">
                    <?= htmlspecialchars($pesan); ?>
                </div>
            <?php } ?>

            <?php if (($_GET['pesan'] ?? '') == 'tambah') { ?>
                <div class="alert alert-success">
                    Data jenis pelanggaran berhasil ditambahkan.
                </div>
            <?php } ?>

            <?php if (($_GET['pesan'] ?? '') == 'edit') { ?>
                <div class="alert alert-success">
                    Data jenis pelanggaran berhasil diubah.
                </div>
            <?php } ?>

            <?php if (($_GET['pesan'] ?? '') == 'hapus') { ?>
                <div class="alert alert-success">
                    Data jenis pelanggaran berhasil dihapus.
                </div>
            <?php } ?>

            <!-- PENCARIAN -->
            <div class="card border-0 shadow-sm mb-3">
                <div class="card-body">
                    <form method="GET" action="jenis_pelanggaran.php"
                          class="row g-2">
                        <div class="col-md-9">
                            <input type="text"
                                   name="cari"
                                   class="form-control"
                                   placeholder="Cari kode, nama, poin, atau deskripsi..."
                                   value="<?= htmlspecialchars($cari); ?>">
                        </div>

                        <div class="col-md-3 d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                Cari
                            </button>

                            <a href="jenis_pelanggaran.php"
                               class="btn btn-secondary">
                                Reset
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            <!-- TABEL -->
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover align-middle">
                            <thead class="table-dark">
                                <tr>
                                    <th>No</th>
                                    <th>Kategori ID</th>
                                    <th>Kode</th>
                                    <th>Nama Pelanggaran</th>
                                    <th>Poin</th>
                                    <th>Deskripsi</th>
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
                                    <td><?= htmlspecialchars($row['pelanggaran_kategori_id']); ?></td>
                                    <td><?= htmlspecialchars($row['kode']); ?></td>
                                    <td><?= htmlspecialchars($row['nama']); ?></td>
                                    <td><?= htmlspecialchars($row['poin']); ?></td>
                                    <td><?= htmlspecialchars($row['deskripsi'] ?? ''); ?></td>
                                    <td class="text-nowrap">
                                        <button type="button"
                                            class="btn btn-primary btn-sm"
                                            data-bs-toggle="modal"
                                            data-bs-target="#modalEdit"
                                            data-id="<?= (int) $row['id']; ?>"
                                            data-kategori="<?= (int) $row['pelanggaran_kategori_id']; ?>"
                                            data-kode="<?= htmlspecialchars($row['kode'], ENT_QUOTES); ?>"
                                            data-nama="<?= htmlspecialchars($row['nama'], ENT_QUOTES); ?>"
                                            data-poin="<?= (int) $row['poin']; ?>"
                                            data-deskripsi="<?= htmlspecialchars($row['deskripsi'] ?? '', ENT_QUOTES); ?>">
                                            Edit
                                        </button>

                                        <a href="jenis_pelanggaran.php?hapus=<?= (int) $row['id']; ?>"
                                           class="btn btn-danger btn-sm"
                                           onclick="return confirm('Yakin ingin menghapus jenis pelanggaran ini?')">
                                            Hapus
                                        </a>
                                    </td>
                                </tr>
                            <?php
                                }
                            } else {
                            ?>
                                <tr>
                                    <td colspan="7" class="text-center text-secondary py-4">
                                        <?= $cari !== ''
                                            ? 'Data yang dicari tidak ditemukan.'
                                            : 'Belum ada data jenis pelanggaran.'; ?>
                                    </td>
                                </tr>
                            <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </main>
    </div>
</div>

<!-- MODAL TAMBAH -->
<div class="modal fade" id="modalTambah" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="jenis_pelanggaran.php">
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Jenis Pelanggaran</h5>
                    <button type="button" class="btn-close"
                            data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>

                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">ID Kategori Pelanggaran</label>
                        <input type="number" name="pelanggaran_kategori_id"
                               class="form-control" min="1" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Kode Pelanggaran</label>
                        <input type="text" name="kode"
                               class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Nama Pelanggaran</label>
                        <input type="text" name="nama"
                               class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Poin</label>
                        <input type="number" name="poin"
                               class="form-control" min="0" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Deskripsi</label>
                        <textarea name="deskripsi"
                                  class="form-control" rows="3"></textarea>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary"
                            data-bs-dismiss="modal">Batal</button>
                    <button type="submit" name="tambah"
                            class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- MODAL EDIT -->
<div class="modal fade" id="modalEdit" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="jenis_pelanggaran.php">
                <input type="hidden" name="id" id="edit_id">

                <div class="modal-header">
                    <h5 class="modal-title">Edit Jenis Pelanggaran</h5>
                    <button type="button" class="btn-close"
                            data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>

                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">ID Kategori Pelanggaran</label>
                        <input type="number" name="pelanggaran_kategori_id"
                               id="edit_kategori" class="form-control"
                               min="1" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Kode Pelanggaran</label>
                        <input type="text" name="kode"
                               id="edit_kode" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Nama Pelanggaran</label>
                        <input type="text" name="nama"
                               id="edit_nama" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Poin</label>
                        <input type="number" name="poin"
                               id="edit_poin" class="form-control"
                               min="0" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Deskripsi</label>
                        <textarea name="deskripsi"
                                  id="edit_deskripsi"
                                  class="form-control" rows="3"></textarea>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary"
                            data-bs-dismiss="modal">Batal</button>
                    <button type="submit" name="edit"
                            class="btn btn-primary">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>

<script>
const modalEdit = document.getElementById('modalEdit');

modalEdit.addEventListener('show.bs.modal', function (event) {
    const tombol = event.relatedTarget;

    document.getElementById('edit_id').value = tombol.dataset.id;
    document.getElementById('edit_kategori').value = tombol.dataset.kategori;
    document.getElementById('edit_kode').value = tombol.dataset.kode;
    document.getElementById('edit_nama').value = tombol.dataset.nama;
    document.getElementById('edit_poin').value = tombol.dataset.poin;
    document.getElementById('edit_deskripsi').value = tombol.dataset.deskripsi;
});
</script>

</body>
</html>
