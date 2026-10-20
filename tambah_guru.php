<?php
session_start();

if (!isset($_SESSION['login'])) {
    header("Location: login.php");
    exit;
}

if (strtolower($_SESSION['role'] ?? '') != 'admin') {
    exit("Anda tidak memiliki akses");
}

include "config/koneksi.php";

$pesan = "";

$nip = "";
$nama = "";
$email = "";
$status_aktif = "1";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nip = trim($_POST['nip'] ?? '');
    $nama = trim($_POST['nama'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $konfirmasi_password = $_POST['konfirmasi_password'] ?? '';
    $status_aktif = $_POST['status_aktif'] ?? '1';

    if (
        $nip == '' ||
        $nama == '' ||
        $email == '' ||
        $password == '' ||
        $konfirmasi_password == ''
    ) {
        $pesan = "Semua data wajib diisi.";

    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $pesan = "Format email tidak valid.";

    } elseif ($password !== $konfirmasi_password) {
        $pesan = "Konfirmasi password tidak sama.";

    } elseif (!in_array($status_aktif, ['0', '1'], true)) {
        $pesan = "Status tidak valid.";

    } else {
        // Periksa NIP yang sudah terdaftar
        $cek_nip = mysqli_prepare(
            $koneksi,
            "SELECT id FROM t_guru WHERE nip = ?"
        );

        mysqli_stmt_bind_param($cek_nip, "s", $nip);
        mysqli_stmt_execute($cek_nip);
        mysqli_stmt_store_result($cek_nip);

        // Periksa email yang sudah digunakan
        $cek_email = mysqli_prepare(
            $koneksi,
            "SELECT id FROM t_users WHERE email = ?"
        );

        mysqli_stmt_bind_param($cek_email, "s", $email);
        mysqli_stmt_execute($cek_email);
        mysqli_stmt_store_result($cek_email);

        if (mysqli_stmt_num_rows($cek_nip) > 0) {
            $pesan = "NIP sudah terdaftar.";

        } elseif (mysqli_stmt_num_rows($cek_email) > 0) {
            $pesan = "Email sudah digunakan.";

        } else {
            // Password disimpan dalam bentuk hash
            $password_hash = password_hash(
                $password,
                PASSWORD_DEFAULT
            );

            mysqli_begin_transaction($koneksi);

            try {
                // Simpan akun pengguna
                $stmt_user = mysqli_prepare(
                    $koneksi,
                    "INSERT INTO t_users
                    (name, email, password, role, created_at, update_at)
                    VALUES (?, ?, ?, 'guru', NOW(), NOW())"
                );

                mysqli_stmt_bind_param(
                    $stmt_user,
                    "sss",
                    $nama,
                    $email,
                    $password_hash
                );

                if (!mysqli_stmt_execute($stmt_user)) {
                    throw new Exception("Gagal menyimpan akun pengguna.");
                }

                $user_id = mysqli_insert_id($koneksi);

                // Simpan data guru dan hubungkan ke akun
                $stmt_guru = mysqli_prepare(
                    $koneksi,
                    "INSERT INTO t_guru
                    (nip, nama, email, status_aktif, user_id, created_at, update_at)
                    VALUES (?, ?, ?, ?, ?, NOW(), NOW())"
                );

                $status_int = (int) $status_aktif;

                mysqli_stmt_bind_param(
                    $stmt_guru,
                    "sssii",
                    $nip,
                    $nama,
                    $email,
                    $status_int,
                    $user_id
                );

                if (!mysqli_stmt_execute($stmt_guru)) {
                    throw new Exception("Gagal menyimpan data guru.");
                }

                mysqli_commit($koneksi);

                header("Location: guru.php?pesan=tambah_berhasil");
                exit;

            } catch (Throwable $e) {
                mysqli_rollback($koneksi);

                $pesan = "Data gagal disimpan. Periksa struktur database dan pastikan email serta NIP belum digunakan.";
            }
        }

        mysqli_stmt_close($cek_nip);
        mysqli_stmt_close($cek_email);
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Tambah Guru</title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >
</head>

<body class="bg-light">

<div class="container-fluid">
    <div class="row">

        <!-- SIDEBAR -->
        <aside class="col-md-3 col-lg-2 bg-dark min-vh-100 p-3">
            <h5 class="text-white mb-4">Sistem Pelanggaran</h5>

            <ul class="nav nav-pills flex-column gap-1">
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
                <li class="nav-item">
                    <a href="about_me.php" class="nav-link text-white">
                        About Me
                    </a>
                </li>

                <hr class="text-secondary">

                <li class="nav-item">
                    <a href="logout.php"
                       class="nav-link text-danger"
                       onclick="return confirm('Yakin ingin logout?')">
                        Logout
                    </a>
                </li>
            </ul>
        </aside>

        <!-- FORM TAMBAH GURU -->
        <main class="col-md-9 col-lg-10 p-4">

            <h2 class="mb-3">Tambah Guru</h2>

            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white py-3">
                    Form Tambah Guru
                </div>

                <div class="card-body p-4">

                    <?php if ($pesan != ''): ?>
                        <div class="alert alert-danger">
                            <?= htmlspecialchars($pesan) ?>
                        </div>
                    <?php endif; ?>

                    <form method="POST">

                        <div class="mb-3">
                            <label class="form-label">NIP</label>
                            <input type="text" name="nip"
                                   class="form-control"
                                   value="<?= htmlspecialchars($nip) ?>"
                                   required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Nama Guru</label>
                            <input type="text" name="nama"
                                   class="form-control"
                                   value="<?= htmlspecialchars($nama) ?>"
                                   required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" name="email"
                                   class="form-control"
                                   value="<?= htmlspecialchars($email) ?>"
                                   required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Password</label>
                            <input type="password" name="password"
                                   class="form-control"
                                   autocomplete="new-password"
                                   required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Konfirmasi Password</label>
                            <input type="password"
                                   name="konfirmasi_password"
                                   class="form-control"
                                   autocomplete="new-password"
                                   required>
                        </div>

                        <div class="mb-4">
                            <label class="form-label">Status Aktif</label>
                            <select name="status_aktif"
                                    class="form-select" required>
                                <option value="1"
                                    <?= $status_aktif === '1' ? 'selected' : '' ?>>
                                    Aktif
                                </option>
                                <option value="0"
                                    <?= $status_aktif === '0' ? 'selected' : '' ?>>
                                    Tidak Aktif
                                </option>
                            </select>
                        </div>

                        <button type="submit" class="btn btn-primary">
                            Simpan
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
