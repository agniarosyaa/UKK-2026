
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

$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

if ($id <= 0) {
    die("ID guru tidak valid.");
}

/* AMBIL DATA GURU */
$stmt = mysqli_prepare(
    $koneksi,
    "SELECT * FROM t_guru WHERE id = ?"
);

mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);
$data = mysqli_fetch_assoc($result);

mysqli_stmt_close($stmt);

if (!$data) {
    die("Data guru tidak ditemukan.");
}

$pesan_error = "";

/* UPDATE DATA GURU */
if (isset($_POST['update'])) {

    $nip = trim($_POST['nip'] ?? '');
    $nama = trim($_POST['nama'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $user_id = (int) ($_POST['user_id'] ?? 0);
    $status_aktif = (int) ($_POST['status_aktif'] ?? -1);

    if (
        $nip == '' ||
        $nama == '' ||
        $email == '' ||
        $user_id <= 0 ||
        !in_array($status_aktif, [0, 1], true)
    ) {

        $pesan_error = "Semua data wajib diisi dengan benar.";

    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {

        $pesan_error = "Format email tidak valid.";

    } else {

        /* CEK USER ID */
        $cekUser = mysqli_prepare(
            $koneksi,
            "SELECT id FROM t_users WHERE id = ?"
        );

        mysqli_stmt_bind_param($cekUser, "i", $user_id);
        mysqli_stmt_execute($cekUser);

        $hasilUser = mysqli_stmt_get_result($cekUser);
        $userAda = mysqli_num_rows($hasilUser) > 0;

        mysqli_stmt_close($cekUser);

        if (!$userAda) {

            $pesan_error = "ID pengguna tidak ditemukan.";

        } else {

            /* UPDATE DATA GURU */
            $update = mysqli_prepare(
                $koneksi,
                "UPDATE t_guru
                 SET nip = ?,
                     nama = ?,
                     email = ?,
                     user_id = ?,
                     status_aktif = ?
                 WHERE id = ?"
            );

            if ($update) {

                mysqli_stmt_bind_param(
                    $update,
                    "sssiii",
                    $nip,
                    $nama,
                    $email,
                    $user_id,
                    $status_aktif,
                    $id
                );

                if (mysqli_stmt_execute($update)) {

                    mysqli_stmt_close($update);

                    header("Location: guru.php?pesan=edit");
                    exit;

                } else {

                    $pesan_error = "Gagal mengubah data: " .
                                   mysqli_stmt_error($update);

                    mysqli_stmt_close($update);
                }

            } else {

                $pesan_error = "Query gagal: " .
                               mysqli_error($koneksi);
            }
        }
    }

    /* SIMPAN DATA FORM JIKA TERJADI ERROR */
    $data['nip'] = $nip;
    $data['nama'] = $nama;
    $data['email'] = $email;
    $data['user_id'] = $user_id;
    $data['status_aktif'] = $status_aktif;
}

/* AMBIL ID PENGGUNA */
$users = mysqli_query(
    $koneksi,
    "SELECT id FROM t_users ORDER BY id ASC"
);

if (!$users) {
    die("Gagal mengambil data pengguna: " . mysqli_error($koneksi));
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Edit Guru</title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css"
        rel="stylesheet">
</head>

<body class="bg-light">

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

                <li class="nav-item">
                    <a href="siswa.php" class="nav-link text-white">
                        Kelola Siswa
                    </a>
                </li>

                <li class="nav-item">
                    <a href="guru.php" class="nav-link active">
                        Kelola Guru
                    </a>
                </li>

                <li class="nav-item">
                    <a href="kelas.php" class="nav-link text-white">
                        Kelola Kelas
                    </a>
                </li>

                <li class="nav-item">
                    <a href="tahun_ajaran.php" class="nav-link text-white">
                        Tahun Ajaran
                    </a>
                </li>

                <li class="nav-item">
                    <a href="penempatan_siswa.php" class="nav-link text-white">
                        Penempatan Siswa
                    </a>
                </li>

                <li class="nav-item">
                    <a href="wali_kelas.php" class="nav-link text-white">
                        Wali Kelas
                    </a>
                </li>

                <li class="nav-item">
                    <a href="kategori_pelanggaran.php" class="nav-link text-white">
                        Kategori Pelanggaran
                    </a>
                </li>

                <li class="nav-item">
                    <a href="jenis_pelanggaran.php" class="nav-link text-white">
                        Jenis Pelanggaran
                    </a>
                </li>

                <li class="nav-item">
                    <a href="laporan.php" class="nav-link text-white">
                        Laporan
                    </a>
                </li>

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

            <h2 class="mb-4">Edit Guru</h2>

            <div class="card shadow-sm">

                <div class="card-header bg-primary text-white py-3">
                    <h5 class="mb-0">Form Edit Guru</h5>
                </div>

                <div class="card-body p-4">

                    <?php if ($pesan_error != '') { ?>
                        <div class="alert alert-danger">
                            <?= htmlspecialchars($pesan_error); ?>
                        </div>
                    <?php } ?>

                    <form method="POST">

                        <!-- NIP -->
                        <div class="mb-3">
                            <label class="form-label">NIP</label>

                            <input
                                type="text"
                                name="nip"
                                class="form-control"
                                value="<?= htmlspecialchars($data['nip'] ?? ''); ?>"
                                required>
                        </div>

                        <!-- NAMA -->
                        <div class="mb-3">
                            <label class="form-label">Nama Guru</label>

                            <input
                                type="text"
                                name="nama"
                                class="form-control"
                                value="<?= htmlspecialchars($data['nama'] ?? ''); ?>"
                                required>
                        </div>

                        <!-- EMAIL -->
                        <div class="mb-3">
                            <label class="form-label">Email</label>

                            <input
                                type="email"
                                name="email"
                                class="form-control"
                                value="<?= htmlspecialchars($data['email'] ?? ''); ?>"
                                required>
                        </div>

                        <!-- AKUN PENGGUNA -->
                        <div class="mb-3">
                            <label class="form-label">
                                ID Akun Pengguna
                            </label>

                            <select
                                name="user_id"
                                class="form-select"
                                required>

                                <option value="">
                                    -- Pilih ID Pengguna --
                                </option>

                                <?php while ($u = mysqli_fetch_assoc($users)) { ?>

                                    <option
                                        value="<?= (int) $u['id']; ?>"
                                        <?= (int)($data['user_id'] ?? 0) == (int)$u['id'] ? 'selected' : ''; ?>>

                                        ID Pengguna <?= (int)$u['id']; ?>

                                    </option>

                                <?php } ?>

                            </select>

                            <small class="text-muted">
                                Pilih ID yang tersedia di tabel t_users.
                            </small>
                        </div>

                        <!-- STATUS -->
                        <div class="mb-3">
                            <label class="form-label">Status</label>

                            <select
                                name="status_aktif"
                                class="form-select"
                                required>

                                <option value="1"
                                    <?= ($data['status_aktif'] ?? 0) == 1 ? 'selected' : ''; ?>>
                                    Aktif
                                </option>

                                <option value="0"
                                    <?= ($data['status_aktif'] ?? 0) == 0 ? 'selected' : ''; ?>>
                                    Tidak Aktif
                                </option>

                            </select>
                        </div>

                        <!-- TOMBOL -->
                        <button
                            type="submit"
                            name="update"
                            class="btn btn-primary">
                            Simpan Perubahan
                        </button>

                        <a href="guru.php" class="btn btn-secondary">
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
