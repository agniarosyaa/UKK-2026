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
$stmt = mysqli_prepare($koneksi, "SELECT * FROM t_guru WHERE id = ?");
mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);
$data = mysqli_fetch_assoc($result);

if (!$data) {
    die("Data guru tidak ditemukan.");
}

/* UPDATE DATA GURU */
if (isset($_POST['update'])) {

    $nip = trim($_POST['nip']);
    $nama = trim($_POST['nama']);
    $email = trim($_POST['email']);
    $user_id = (int) $_POST['user_id'];
    $status_aktif = (int) $_POST['status_aktif'];

    $update = mysqli_prepare(
        $koneksi,
        "UPDATE t_guru
         SET nip = ?, nama = ?, email = ?,
             user_id = ?, status_aktif = ?
         WHERE id = ?"
    );

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
        header("Location: guru.php");
        exit;
    } else {
        $pesan_error = "Data gagal diubah: " . mysqli_error($koneksi);
    }
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Edit Guru</title>

    <!-- BOOTSTRAP 5.3.8 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css"
          rel="stylesheet">
</head>

<body class="bg-light">

<div class="container py-5">

    <div class="row justify-content-center">

        <div class="col-md-8 col-lg-7">

            <div class="card shadow-sm">

                <div class="card-header bg-primary text-white py-3">
                    <h4 class="mb-0">Edit Guru</h4>
                </div>

                <div class="card-body p-4">

                    <?php if (isset($pesan_error)) { ?>
                        <div class="alert alert-danger">
                            <?= htmlspecialchars($pesan_error); ?>
                        </div>
                    <?php } ?>

                    <form method="POST">

                        <!-- NIP -->
                        <div class="mb-3">
                            <label for="nip" class="form-label">
                                NIP
                            </label>

                            <input
                                type="text"
                                id="nip"
                                name="nip"
                                class="form-control"
                                value="<?= htmlspecialchars($data['nip'] ?? ''); ?>"
                                required
                            >
                        </div>

                        <!-- NAMA -->
                        <div class="mb-3">
                            <label for="nama" class="form-label">
                                Nama Guru
                            </label>

                            <input
                                type="text"
                                id="nama"
                                name="nama"
                                class="form-control"
                                value="<?= htmlspecialchars($data['nama'] ?? ''); ?>"
                                required
                            >
                        </div>

                        <!-- EMAIL -->
                        <div class="mb-3">
                            <label for="email" class="form-label">
                                Email
                            </label>

                            <input
                                type="email"
                                id="email"
                                name="email"
                                class="form-control"
                                value="<?= htmlspecialchars($data['email'] ?? ''); ?>"
                                required
                            >
                        </div>

                        <!-- USER ID -->
                        <div class="mb-3">
                            <label for="user_id" class="form-label">
                                User ID
                            </label>

                            <input
                                type="number"
                                id="user_id"
                                name="user_id"
                                class="form-control"
                                value="<?= htmlspecialchars($data['user_id'] ?? ''); ?>"
                                required
                            >
                        </div>

                        <!-- STATUS -->
                        <div class="mb-4">
                            <label for="status_aktif" class="form-label">
                                Status
                            </label>

                            <select
                                id="status_aktif"
                                name="status_aktif"
                                class="form-select"
                                required
                            >
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
                        <div class="d-flex gap-2">

                            <button
                                type="submit"
                                name="update"
                                class="btn btn-primary"
                            >
                                Simpan Perubahan
                            </button>

                            <a href="guru.php" class="btn btn-secondary">
                                Kembali
                            </a>

                        </div>

                    </form>

                </div>
            </div>

        </div>

    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
