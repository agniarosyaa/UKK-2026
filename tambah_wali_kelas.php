
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

$error = "";

$tahun_ajaran = mysqli_query($koneksi, "SELECT * FROM t_tahun_ajaran ORDER BY id ASC");
$kelas = mysqli_query($koneksi, "SELECT * FROM t_kelas ORDER BY id ASC");
$guru = mysqli_query($koneksi, "SELECT * FROM t_guru ORDER BY id ASC");

if (!$tahun_ajaran || !$kelas || !$guru) {
    die("Gagal mengambil data pilihan: " . mysqli_error($koneksi));
}

$tahun_ajaran_id = "";
$kelas_id = "";
$guru_id = "";
$tanggal_mulai = "";
$tanggal_selesai = "";
$status_aktif = "1";

if (isset($_POST['tambah'])) {
    $tahun_ajaran_id = $_POST['tahun_ajaran_id'] ?? '';
    $kelas_id = $_POST['kelas_id'] ?? '';
    $guru_id = $_POST['guru_id'] ?? '';
    $tanggal_mulai = $_POST['tanggal_mulai'] ?? '';
    $tanggal_selesai = $_POST['tanggal_selesai'] ?? '';
    $status_aktif = $_POST['status_aktif'] ?? '1';

    if (
        $tahun_ajaran_id == '' || $kelas_id == '' || $guru_id == '' ||
        $tanggal_mulai == '' || $tanggal_selesai == ''
    ) {
        $error = "Semua data wajib diisi.";
    } elseif ($tanggal_selesai < $tanggal_mulai) {
        $error = "Tanggal selesai tidak boleh lebih awal dari tanggal mulai.";
    } else {
        $query = "INSERT INTO t_wali_kelas
                  (tahun_ajaran_id, kelas_id, guru_id, tanggal_mulai,
                   tanggal_selesai, status_aktif, created_at, update_at)
                  VALUES (?, ?, ?, ?, ?, ?, NOW(), NOW())";

        $stmt = mysqli_prepare($koneksi, $query);

        if ($stmt) {
            $ta_id = (int)$tahun_ajaran_id;
            $k_id = (int)$kelas_id;
            $g_id = (int)$guru_id;
            $status = (int)$status_aktif;

            mysqli_stmt_bind_param(
                $stmt,
                "iiissi",
                $ta_id,
                $k_id,
                $g_id,
                $tanggal_mulai,
                $tanggal_selesai,
                $status
            );

            if (mysqli_stmt_execute($stmt)) {
                mysqli_stmt_close($stmt);
                header("Location: wali_kelas.php?pesan=tambah");
                exit;
            } else {
                $error = "Gagal menambahkan data wali kelas.";
            }

            mysqli_stmt_close($stmt);
        } else {
            $error = "Query gagal diproses.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Wali Kelas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
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
                <li class="nav-item"><a href="wali_kelas.php" class="nav-link active">Wali Kelas</a></li>
                <li class="nav-item"><a href="kategori_pelanggaran.php" class="nav-link text-white">Kategori Pelanggaran</a></li>
                <li class="nav-item"><a href="jenis_pelanggaran.php" class="nav-link text-white">Jenis Pelanggaran</a></li>
                <li class="nav-item"><a href="laporan.php" class="nav-link text-white">Laporan</a></li>
                <hr class="text-secondary">
                <li class="nav-item"><a href="about_me.php" class="nav-link text-white">About Me</a></li>
                <li class="nav-item"><a href="logout.php" class="nav-link text-danger" onclick="return confirm('Yakin ingin logout?')">Logout</a></li>
            </ul>
        </aside>

        <main class="col-md-9 col-lg-10 p-4">
            <h2 class="mb-4">Tambah Wali Kelas</h2>

            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    Form Tambah Wali Kelas
                </div>

                <div class="card-body">
                    <?php if ($error != "") { ?>
                        <div class="alert alert-danger"><?= htmlspecialchars($error); ?></div>
                    <?php } ?>

                    <form method="POST">
                        <div class="mb-3">
                            <label class="form-label">Tahun Ajaran</label>
                            <select name="tahun_ajaran_id" class="form-select" required>
                                <option value="">Pilih Tahun Ajaran</option>
                                <?php while ($ta = mysqli_fetch_assoc($tahun_ajaran)) { ?>
                                    <option value="<?= $ta['id']; ?>" <?= (string)$tahun_ajaran_id === (string)$ta['id'] ? 'selected' : ''; ?>>
                                        <?= htmlspecialchars($ta['nama']); ?>
                                    </option>
                                <?php } ?>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Kelas</label>
                            <select name="kelas_id" class="form-select" required>
                                <option value="">Pilih Kelas</option>
                                <?php while ($k = mysqli_fetch_assoc($kelas)) { ?>
                                    <option value="<?= $k['id']; ?>" <?= (string)$kelas_id === (string)$k['id'] ? 'selected' : ''; ?>>
                                        <?= htmlspecialchars($k['nama']); ?>
                                    </option>
                                <?php } ?>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Guru</label>
                            <select name="guru_id" class="form-select" required>
                                <option value="">Pilih Guru</option>
                                <?php while ($g = mysqli_fetch_assoc($guru)) { ?>
                                    <option value="<?= $g['id']; ?>" <?= (string)$guru_id === (string)$g['id'] ? 'selected' : ''; ?>>
                                        <?= htmlspecialchars($g['nama']); ?>
                                    </option>
                                <?php } ?>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Tanggal Mulai</label>
                            <input type="date" name="tanggal_mulai" class="form-control"
                                   value="<?= htmlspecialchars($tanggal_mulai); ?>" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Tanggal Selesai</label>
                            <input type="date" name="tanggal_selesai" class="form-control"
                                   value="<?= htmlspecialchars($tanggal_selesai); ?>" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Status</label>
                            <select name="status_aktif" class="form-select" required>
                                <option value="1" <?= $status_aktif == '1' ? 'selected' : ''; ?>>Aktif</option>
                                <option value="0" <?= $status_aktif == '0' ? 'selected' : ''; ?>>Tidak Aktif</option>
                            </select>
                        </div>

                        <button type="submit" name="tambah" class="btn btn-primary">Simpan</button>
                        <a href="wali_kelas.php" class="btn btn-secondary">Kembali</a>
                    </form>
                </div>
            </div>
        </main>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
