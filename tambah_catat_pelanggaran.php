
<?php
session_start();

if (!isset($_SESSION['login'])) {
    header("Location: login.php");
    exit;
}

include "config/koneksi.php";

$role = strtolower($_SESSION['role'] ?? '');

if (!in_array($role, ['admin', 'guru'])) {
    exit("Anda tidak memiliki akses");
}

$error = '';

if (isset($_POST['simpan'])) {
    $tanggal = $_POST['tanggal'] ?? '';
    $nama_siswa = trim($_POST['nama_siswa'] ?? '');
    $nama_pelanggaran = trim($_POST['nama_pelanggaran'] ?? '');
    $poin = (int) ($_POST['poin'] ?? 0);
    $tindakan = trim($_POST['tindakan'] ?? '');
    $status = $_POST['status'] ?? '';

    if (
        $tanggal == '' ||
        $nama_siswa == '' ||
        $nama_pelanggaran == '' ||
        $poin < 0 ||
        $tindakan == '' ||
        $status == ''
    ) {
        $error = "Semua kolom wajib diisi dengan benar.";
    } else {
        $query = "INSERT INTO t_pelanggaran_siswa
                  (tanggal, nama_siswa, nama_pelanggaran, poin, tindakan, status)
                  VALUES (?, ?, ?, ?, ?, ?)";

        $stmt = mysqli_prepare($koneksi, $query);

        if ($stmt) {
            mysqli_stmt_bind_param(
                $stmt,
                "sssiss",
                $tanggal,
                $nama_siswa,
                $nama_pelanggaran,
                $poin,
                $tindakan,
                $status
            );

            if (mysqli_stmt_execute($stmt)) {
                mysqli_stmt_close($stmt);
                header("Location: catatan_pelanggaran.php?pesan=tambah");
                exit;
            } else {
                $error = "Gagal menyimpan data: " . mysqli_stmt_error($stmt);
            }

            mysqli_stmt_close($stmt);
        } else {
            $error = "Gagal menyiapkan query: " . mysqli_error($koneksi);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Tambah Catat Pelanggaran</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css"
          rel="stylesheet">
</head>

<body>

<div class="container-fluid">
    <div class="row">

        <!-- SIDEBAR -->
        <aside class="col-md-3 col-lg-2 bg-dark min-vh-100 p-3">

            <h5 class="text-white mb-4">
                Sistem Pelanggaran
            </h5>

            <ul class="nav nav-pills flex-column gap-2">

                <li class="nav-item">
                    <a href="dashboard.php" class="nav-link text-white">
                        Dashboard
                    </a>
                </li>

                <?php if ($role == 'admin') { ?>

                    <li class="nav-item">
                        <a href="siswa.php" class="nav-link text-white">Kelola Siswa</a>
                    </li>

                    <li class="nav-item">
                        <a href="guru.php" class="nav-link text-white">Kelola Guru</a>
                    </li>

                    <li class="nav-item">
                        <a href="kelas.php" class="nav-link text-white">Kelola Kelas</a>
                    </li>

                    <li class="nav-item">
                        <a href="tahun_ajaran.php" class="nav-link text-white">Tahun Ajaran</a>
                    </li>

                    <li class="nav-item">
                        <a href="penempatan_siswa.php" class="nav-link text-white">Penempatan Siswa</a>
                    </li>

                    <li class="nav-item">
                        <a href="wali_kelas.php" class="nav-link text-white">Wali Kelas</a>
                    </li>

                    <li class="nav-item">
                        <a href="kategori_pelanggaran.php" class="nav-link text-white">Kategori Pelanggaran</a>
                    </li>

                    <li class="nav-item">
                        <a href="jenis_pelanggaran.php" class="nav-link text-white">Jenis Pelanggaran</a>
                    </li>

                    <li class="nav-item">
                        <a href="catat_pelanggaran.php" class="nav-link active">Catat Pelanggaran</a>
                    </li>

                    <li class="nav-item">
                        <a href="laporan.php" class="nav-link text-white">Laporan</a>
                    </li>

                <?php } ?>

                <?php if ($role == 'guru') { ?>

                    <li class="nav-item">
                        <a href="catat_pelanggaran.php" class="nav-link active">
                            Catat Pelanggaran
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="tindakan.php" class="nav-link text-white">Tindakan</a>
                    </li>

                    <li class="nav-item">
                        <a href="riwayat.php" class="nav-link text-white">Riwayat</a>
                    </li>

                    <li class="nav-item">
                        <a href="rekap_poin.php" class="nav-link text-white">Rekap Poin</a>
                    </li>

                <?php } ?>

                <hr class="text-secondary">

                <li class="nav-item">
                    <a href="about_me.php" class="nav-link text-white">
                        About Me
                    </a>
                </li>

                <li class="nav-item">
                    <a href="logout.php"
                       class="nav-link text-danger"
                       onclick="return confirm('Yakin ingin logout?')">
                        Logout
                    </a>
                </li>

            </ul>
        </aside>

        <!-- KONTEN UTAMA -->
        <main class="col-md-9 col-lg-10 p-4">

            <h2 class="mb-2">Tambah Catat Pelanggaran</h2>

            <p class="text-secondary mb-4">
                Silakan isi data pelanggaran siswa.
            </p>

            <?php if ($error != '') { ?>
                <div class="alert alert-danger">
                    <?= htmlspecialchars($error); ?>
                </div>
            <?php } ?>

            <div class="card shadow-sm">

                <div class="card-header bg-primary text-white">
                    Form Tambah Catat Pelanggaran
                </div>

                <div class="card-body">

                    <form method="POST">

                        <div class="mb-3">
                            <label for="tanggal" class="form-label">Tanggal</label>

                            <input
                                type="date"
                                name="tanggal"
                                id="tanggal"
                                class="form-control"
                                value="<?= htmlspecialchars($_POST['tanggal'] ?? date('Y-m-d')); ?>"
                                required
                            >
                        </div>

                        <div class="mb-3">
                            <label for="nama_siswa" class="form-label">Nama Siswa</label>

                            <input
                                type="text"
                                name="nama_siswa"
                                id="nama_siswa"
                                class="form-control"
                                value="<?= htmlspecialchars($_POST['nama_siswa'] ?? ''); ?>"
                                required
                            >
                        </div>

                        <div class="mb-3">
                            <label for="nama_pelanggaran" class="form-label">
                                Nama Pelanggaran
                            </label>

                            <input
                                type="text"
                                name="nama_pelanggaran"
                                id="nama_pelanggaran"
                                class="form-control"
                                value="<?= htmlspecialchars($_POST['nama_pelanggaran'] ?? ''); ?>"
                                required
                            >
                        </div>

                        <div class="mb-3">
                            <label for="poin" class="form-label">Poin Pelanggaran</label>

                            <input
                                type="number"
                                name="poin"
                                id="poin"
                                class="form-control"
                                min="0"
                                value="<?= htmlspecialchars($_POST['poin'] ?? ''); ?>"
                                required
                            >
                        </div>

                        <div class="mb-3">
                            <label for="tindakan" class="form-label">Tindakan</label>

                            <input
                                type="text"
                                name="tindakan"
                                id="tindakan"
                                class="form-control"
                                value="<?= htmlspecialchars($_POST['tindakan'] ?? ''); ?>"
                                required
                            >
                        </div>

                        <div class="mb-4">
                            <label for="status" class="form-label">Status</label>

                            <select name="status" id="status" class="form-select" required>

                                <option value="Belum Ditindaklanjuti"
                                    <?= ($_POST['status'] ?? 'Belum Ditindaklanjuti') == 'Belum Ditindaklanjuti' ? 'selected' : ''; ?>>
                                    Belum Ditindaklanjuti
                                </option>

                                <option value="Diproses"
                                    <?= ($_POST['status'] ?? '') == 'Diproses' ? 'selected' : ''; ?>>
                                    Diproses
                                </option>

                                <option value="Selesai"
                                    <?= ($_POST['status'] ?? '') == 'Selesai' ? 'selected' : ''; ?>>
                                    Selesai
                                </option>

                            </select>
                        </div>

                        <button type="submit" name="simpan" class="btn btn-primary">
                            Simpan
                        </button>

                        <a href="catat_pelanggaran.php" class="btn btn-secondary">
                            Kembali
                        </a>

                    </form>

                </div>
            </div>

        </main>

    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
