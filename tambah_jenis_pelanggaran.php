
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

$kategori = mysqli_query(
    $koneksi,
    "SELECT id, nama FROM t_pelanggaran_kategori ORDER BY nama ASC"
);

if (isset($_POST['simpan'])) {
    $kategori_id = (int)($_POST['pelanggaran_kategori_id'] ?? 0);
    $kode = trim($_POST['kode'] ?? '');
    $nama = trim($_POST['nama'] ?? '');
    $poin = (int)($_POST['poin'] ?? 0);
    $deskripsi = trim($_POST['deskripsi'] ?? '');

    if ($kategori_id <= 0 || $kode === '' || $nama === '' || $poin < 0) {
        $error = "Lengkapi data dengan benar.";
    } else {
        $stmt = mysqli_prepare(
            $koneksi,
            "INSERT INTO t_pelanggaran
             (pelanggaran_kategori_id, kode, nama, poin, deskripsi)
             VALUES (?, ?, ?, ?, ?)"
        );

        mysqli_stmt_bind_param(
            $stmt, "issis",
            $kategori_id, $kode, $nama, $poin, $deskripsi
        );

        if (mysqli_stmt_execute($stmt)) {
            header("Location: jenis_pelanggaran.php?pesan=tambah");
            exit;
        }

        $error = "Gagal menyimpan data: " . mysqli_error($koneksi);
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Jenis Pelanggaran</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">
<div class="container-fluid">
    <div class="row">

        <aside class="col-md-3 col-lg-2 bg-dark min-vh-100 p-3">
            <h4 class="text-white mb-4">Sistem Pelanggaran</h4>
            <ul class="nav nav-pills flex-column gap-2">
                <li class="nav-item"><a href="dashboard.php" class="nav-link text-white">Dashboard</a></li>
                <li class="nav-item"><a href="siswa.php" class="nav-link text-white">Kelola Siswa</a></li>
                <li class="nav-item"><a href="guru.php" class="nav-link text-white">Kelola Guru</a></li>
                <li class="nav-item"><a href="kelas.php" class="nav-link text-white">Kelola Kelas</a></li>
                <li class="nav-item"><a href="tahun_ajaran.php" class="nav-link text-white">Tahun Ajaran</a></li>
                <li class="nav-item"><a href="penempatan_siswa.php" class="nav-link text-white">Penempatan Siswa</a></li>
                <li class="nav-item"><a href="wali_kelas.php" class="nav-link text-white">Wali Kelas</a></li>
                <li class="nav-item"><a href="kategori_pelanggaran.php" class="nav-link text-white">Kategori Pelanggaran</a></li>
                <li class="nav-item"><a href="jenis_pelanggaran.php" class="nav-link active">Jenis Pelanggaran</a></li>
                <li class="nav-item"><a href="laporan.php" class="nav-link text-white">Laporan</a></li>
                <hr class="text-secondary">
                <li class="nav-item"><a href="about_me.php" class="nav-link text-white">About Me</a></li>
                <li class="nav-item"><a href="logout.php" class="nav-link text-danger" onclick="return confirm('Yakin ingin logout?')">Logout</a></li>
            </ul>
        </aside>

        <main class="col-md-9 col-lg-10 p-4">
            <h2 class="mb-4">Tambah Jenis Pelanggaran</h2>

            <a href="jenis_pelanggaran.php" class="btn btn-secondary mb-3">Kembali</a>

            <div class="card shadow-sm">
                <div class="card-body">

                    <?php if (isset($error)) { ?>
                        <div class="alert alert-danger"><?= htmlspecialchars($error); ?></div>
                    <?php } ?>

                    <form method="POST">
                        <div class="mb-3">
                            <label class="form-label">Kategori Pelanggaran</label>
                            <select name="pelanggaran_kategori_id" class="form-select" required>
                                <option value="">-- Pilih Kategori --</option>
                                <?php while ($k = mysqli_fetch_assoc($kategori)) { ?>
                                    <option value="<?= (int)$k['id']; ?>"
                                        <?= (($_POST['pelanggaran_kategori_id'] ?? '') == $k['id']) ? 'selected' : ''; ?>>
                                        <?= htmlspecialchars($k['nama']); ?>
                                    </option>
                                <?php } ?>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Kode Pelanggaran</label>
                            <input type="text" name="kode" class="form-control"
                                   value="<?= htmlspecialchars($_POST['kode'] ?? ''); ?>" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Nama Pelanggaran</label>
                            <input type="text" name="nama" class="form-control"
                                   value="<?= htmlspecialchars($_POST['nama'] ?? ''); ?>" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Poin</label>
                            <input type="number" name="poin" class="form-control"
                                   min="0" value="<?= htmlspecialchars($_POST['poin'] ?? ''); ?>" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Deskripsi</label>
                            <textarea name="deskripsi" class="form-control" rows="3"><?= htmlspecialchars($_POST['deskripsi'] ?? ''); ?></textarea>
                        </div>

                        <button type="submit" name="simpan" class="btn btn-primary">Simpan</button>
                        <a href="jenis_pelanggaran.php" class="btn btn-secondary">Batal</a>
                    </form>

                </div>
            </div>
        </main>
    </div>
</div>
</body>
</html>
