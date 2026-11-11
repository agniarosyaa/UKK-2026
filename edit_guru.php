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

/* ==========================================
   AMBIL DATA GURU
========================================== */
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

$nip = $data['nip'] ?? '';
$nama = $data['nama'] ?? '';
$email = $data['email'] ?? '';
$user_id = (int) ($data['user_id'] ?? 0);
$status_aktif = (int) ($data['status_aktif'] ?? 1);

$password = "";
$konfirmasi_password = "";

/* ==========================================
   UPDATE DATA GURU
========================================== */
if (isset($_POST['update'])) {

    $nip = trim($_POST['nip'] ?? '');
    $nama = trim($_POST['nama'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $user_id = (int) ($_POST['user_id'] ?? 0);
    $status_aktif = (int) ($_POST['status_aktif'] ?? -1);

    $password = $_POST['password'] ?? '';
    $konfirmasi_password = $_POST['konfirmasi_password'] ?? '';

    /* ==========================================
       VALIDASI
    ========================================== */
    if (
        $nip == '' ||
        $nama == '' ||
        $email == '' ||
        $user_id <= 0 ||
        $password == '' ||
        $konfirmasi_password == ''
    ) {

        $pesan_error = "Semua data wajib diisi dengan benar.";

    } elseif (!ctype_digit($nip) || strlen($nip) > 18) {

        $pesan_error = "NIP harus berupa angka dan maksimal 18 digit.";

    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {

        $pesan_error = "Format email tidak valid.";

    } elseif (strlen($password) > 8) {

        $pesan_error = "Password maksimal 8 karakter.";

    } elseif (strlen($konfirmasi_password) > 8) {

        $pesan_error = "Konfirmasi password maksimal 8 karakter.";

    } elseif ($password !== $konfirmasi_password) {

        $pesan_error = "Konfirmasi password tidak sama.";

    } elseif (!in_array($status_aktif, [0, 1], true)) {

        $pesan_error = "Status tidak valid.";

    } else {

        /* ==========================================
           CEK ID USER
        ========================================== */
        $cekUser = mysqli_prepare(
            $koneksi,
            "SELECT id FROM t_users WHERE id = ?"
        );

        mysqli_stmt_bind_param(
            $cekUser,
            "i",
            $user_id
        );

        mysqli_stmt_execute($cekUser);

        $hasilUser = mysqli_stmt_get_result($cekUser);

        $userAda = mysqli_num_rows($hasilUser) > 0;

        mysqli_stmt_close($cekUser);

        if (!$userAda) {

            $pesan_error = "ID pengguna tidak ditemukan.";

        } else {

            /* ==========================================
               CEK NIP MILIK GURU LAIN
            ========================================== */
            $cekNip = mysqli_prepare(
                $koneksi,
                "SELECT id FROM t_guru WHERE nip = ? AND id != ?"
            );

            mysqli_stmt_bind_param(
                $cekNip,
                "si",
                $nip,
                $id
            );

            mysqli_stmt_execute($cekNip);

            $hasilNip = mysqli_stmt_get_result($cekNip);

            $nipAda = mysqli_num_rows($hasilNip) > 0;

            mysqli_stmt_close($cekNip);

            if ($nipAda) {

                $pesan_error = "NIP sudah digunakan oleh guru lain.";

            } else {

                /* ==========================================
                   CEK EMAIL MILIK USER LAIN
                ========================================== */
                $cekEmail = mysqli_prepare(
                    $koneksi,
                    "SELECT id FROM t_users WHERE email = ? AND id != ?"
                );

                mysqli_stmt_bind_param(
                    $cekEmail,
                    "si",
                    $email,
                    $user_id
                );

                mysqli_stmt_execute($cekEmail);

                $hasilEmail = mysqli_stmt_get_result($cekEmail);

                $emailAda = mysqli_num_rows($hasilEmail) > 0;

                mysqli_stmt_close($cekEmail);

                if ($emailAda) {

                    $pesan_error = "Email sudah digunakan oleh pengguna lain.";

                } else {

                    /* ==========================================
                       HASH PASSWORD
                    ========================================== */
                    $password_hash = password_hash(
                        $password,
                        PASSWORD_DEFAULT
                    );

                    /* ==========================================
                       UPDATE T_USERS
                    ========================================== */
                    $updateUser = mysqli_prepare(
                        $koneksi,
                        "UPDATE t_users
                         SET nama = ?,
                             email = ?,
                             password = ?,
                             role = 'guru',
                             update_at = NOW()
                         WHERE id = ?"
                    );

                    if (!$updateUser) {

                        $pesan_error = "Query update pengguna gagal: " .
                                       mysqli_error($koneksi);

                    } else {

                        mysqli_stmt_bind_param(
                            $updateUser,
                            "sssi",
                            $nama,
                            $email,
                            $password_hash,
                            $user_id
                        );

                        if (!mysqli_stmt_execute($updateUser)) {

                            $pesan_error = "Gagal mengubah akun pengguna: " .
                                           mysqli_stmt_error($updateUser);

                            mysqli_stmt_close($updateUser);

                        } else {

                            mysqli_stmt_close($updateUser);

                            /* ==========================================
                               UPDATE T_GURU
                            ========================================== */
                            $updateGuru = mysqli_prepare(
                                $koneksi,
                                "UPDATE t_guru
                                 SET nip = ?,
                                     nama = ?,
                                     email = ?,
                                     user_id = ?,
                                     status_aktif = ?,
                                     update_at = NOW()
                                 WHERE id = ?"
                            );

                            if (!$updateGuru) {

                                $pesan_error = "Query update guru gagal: " .
                                               mysqli_error($koneksi);

                            } else {

                                mysqli_stmt_bind_param(
                                    $updateGuru,
                                    "sssiii",
                                    $nip,
                                    $nama,
                                    $email,
                                    $user_id,
                                    $status_aktif,
                                    $id
                                );

                                if (mysqli_stmt_execute($updateGuru)) {

                                    $_SESSION['notifikasi'] = [
                                        'tipe' => 'success',
                                        'pesan' => 'Data guru berhasil diperbarui!'
                                    ];

                                    mysqli_stmt_close($updateGuru);

                                    header("Location: guru.php");
                                    exit;

                                } else {

                                    $pesan_error = "Gagal mengubah data guru: " .
                                                   mysqli_stmt_error($updateGuru);

                                    mysqli_stmt_close($updateGuru);
                                }
                            }
                        }
                    }
                }
            }
        }
    }

    /* ==========================================
       SIMPAN DATA FORM JIKA ERROR
    ========================================== */
    $data['nip'] = $nip;
    $data['nama'] = $nama;
    $data['email'] = $email;
    $data['user_id'] = $user_id;
    $data['status_aktif'] = $status_aktif;
}

/* ==========================================
   AMBIL ID PENGGUNA
========================================== */
$users = mysqli_query(
    $koneksi,
    "SELECT id FROM t_users WHERE role = 'guru' ORDER BY id ASC"
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

            <h2 class="mb-4">
                Edit Guru
            </h2>

            <div class="card shadow-sm">

                <div class="card-header bg-primary text-white py-3">
                    <h5 class="mb-0">
                        Form Edit Guru
                    </h5>
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

                            <label class="form-label">
                                NIP
                            </label>

                            <input
                                type="text"
                                name="nip"
                                id="nip"
                                class="form-control"
                                value="<?= htmlspecialchars($data['nip'] ?? ''); ?>"
                                maxlength="18"
                                inputmode="numeric"
                                required>

                        </div>

                        <!-- NAMA -->
                        <div class="mb-3">

                            <label class="form-label">
                                Nama Guru
                            </label>

                            <input
                                type="text"
                                name="nama"
                                class="form-control"
                                value="<?= htmlspecialchars($data['nama'] ?? ''); ?>"
                                required>

                        </div>

                        <!-- EMAIL -->
                        <div class="mb-3">

                            <label class="form-label">
                                Email
                            </label>

                            <input
                                type="email"
                                name="email"
                                class="form-control"
                                value="<?= htmlspecialchars($data['email'] ?? ''); ?>"
                                required>

                        </div>

                        <!-- PASSWORD -->
                        <div class="mb-3">

                            <label class="form-label">
                                Password
                            </label>

                            <input
                                type="password"
                                name="password"
                                id="password"
                                class="form-control"
                                maxlength="8"
                                autocomplete="new-password"
                                required>

                        </div>

                        <!-- KONFIRMASI PASSWORD -->
                        <div class="mb-3">

                            <label class="form-label">
                                Konfirmasi Password
                            </label>

                            <input
                                type="password"
                                name="konfirmasi_password"
                                id="konfirmasi_password"
                                class="form-control"
                                maxlength="8"
                                autocomplete="new-password"
                                required>

                        </div>

                        <!-- ID AKUN PENGGUNA -->
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

                            <label class="form-label">
                                Status
                            </label>

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

                        <a
                            href="guru.php"
                            class="btn btn-secondary">
                            Kembali
                        </a>

                    </form>

                </div>

            </div>

        </main>

    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>

<script>
document.getElementById('nip').addEventListener('input', function () {
    this.value = this.value.replace(/[^0-9]/g, '').slice(0, 18);
});

document.getElementById('password').addEventListener('input', function () {
    this.value = this.value.slice(0, 8);
});

document.getElementById('konfirmasi_password').addEventListener('input', function () {
    this.value = this.value.slice(0, 8);
});
</script>

</body>
</html>