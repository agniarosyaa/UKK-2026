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

$nip = "";
$nama = "";
$email = "";
$user_id = "";

if (isset($_POST['simpan'])) {
    $nip = trim($_POST['nip'] ?? '');
    $nama = trim($_POST['nama'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $user_id = $_POST['user_id'] ?? '';

    if ($nip == "" || $nama == "" || $email == "" || $user_id == "") {
        $error = "Semua data wajib diisi.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Format email tidak valid.";
    } else {
        $query = "INSERT INTO t_guru
                  (nip, nama, email, status_aktif, user_id, created_at, update_at)
                  VALUES (?, ?, ?, 1, ?, NOW(), NOW())";

        $stmt = mysqli_prepare($koneksi, $query);

        if ($stmt) {
            $id_user = (int) $user_id;

            mysqli_stmt_bind_param(
                $stmt,
                "sssi",
                $nip,
                $nama,
                $email,
                $id_user
            );

            if (mysqli_stmt_execute($stmt)) {
                mysqli_stmt_close($stmt);
                header("Location: guru.php");
                exit;
            } else {
                $error = "Gagal menambahkan data guru. Pastikan NIP, email, dan akun belum digunakan.";
            }

            mysqli_stmt_close($stmt);
        } else {
            $error = "Query gagal diproses.";
        }
    }
}

$users = mysqli_query(
    $koneksi,
    "SELECT id, name, email
     FROM t_users
     WHERE role = 'guru'
     ORDER BY id ASC"
);
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Tambah Guru</title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css"
        rel="stylesheet">
</head>

<body>

<div class="container-fluid">
    <div class="row">

        <!-- SIDEBAR -->
        <aside class="col-md-3 col-lg-2 bg-dark min-vh-100 p-3">

            <h4 class="text-white mb-4">Sistem Pelanggaran</h4>

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

        <!-- KONTEN -->
        <main class="col-md-9 col-lg-10 p-4">

            <h2 class="mb-4">Tambah Guru</h2>

            <div class="card shadow-sm">

                <div class="card-header bg-primary text-white">
                    Form Tambah Guru
                </div>

                <div class="card-body">

                    <?php if ($error != "") { ?>
                        <div class="alert alert-danger">
                            <?= htmlspecialchars($error); ?>
                        </div>
                    <?php } ?>

                    <form method="POST">

                        <div class="mb-3">
                            <label for="nip" class="form-label">NIP</label>
                            <input
                                type="text"
                                name="nip"
                                id="nip"
                                class="form-control"
                                value="<?= htmlspecialchars($nip); ?>"
                                required>
                        </div>

                        <div class="mb-3">
                            <label for="nama" class="form-label">Nama Guru</label>
                            <input
                                type="text"
                                name="nama"
                                id="nama"
                                class="form-control"
                                value="<?= htmlspecialchars($nama); ?>"
                                required>
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input
                                type="email"
                                name="email"
                                id="email"
                                class="form-control"
                                value="<?= htmlspecialchars($email); ?>"
                                required>
                        </div>

                        <div class="mb-3">
                            <label for="user_id" class="form-label">
                                Akun Guru
                            </label>

                            <select
                                name="user_id"
                                id="user_id"
                                class="form-select"
                                required>

                                <option value="">
                                    -- Pilih Akun Guru --
                                </option>

                                <?php if ($users) { ?>
                                    <?php while ($user = mysqli_fetch_assoc($users)) { ?>

                                        <option
                                            value="<?= (int) $user['id']; ?>"
                                            <?= (string) $user_id === (string) $user['id'] ? 'selected' : ''; ?>>

                                            <?= htmlspecialchars($user['name']); ?>
                                            - <?= htmlspecialchars($user['email']); ?>

                                        </option>

                                    <?php } ?>
                                <?php } ?>

                            </select>
                        </div>

                        <button
                            type="submit"
                            name="simpan"
                            class="btn btn-primary">
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
