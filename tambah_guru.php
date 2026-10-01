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

if (isset($_POST['simpan'])) {

    $nip = $_POST['nip'];
    $nama = $_POST['nama'];
    $email = $_POST['email'];
    $user_id = $_POST['user_id'];

    $query = "INSERT INTO t_guru
              (nip, nama, email, status_aktif, user_id, created_at, update_at)
              VALUES
              ('$nip', '$nama', '$email', 1, '$user_id', NOW(), NOW())";

    $result = mysqli_query($koneksi, $query);

    if ($result) {
        header("Location: guru.php");
        exit;
    } else {
        echo "Gagal menambahkan data: " . mysqli_error($koneksi);
    }
}

$users = mysqli_query(
    $koneksi,
    "SELECT id, name, email FROM t_users WHERE role='guru' ORDER BY id ASC"
);

?>

<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">
    <title>Tambah Guru</title>

    <style>

        body {
            margin: 0;
            padding: 30px;
            font-family: Arial, Helvetica, sans-serif;
            background-color: #ffffff;
        }

        .container {
            max-width: 600px;
            margin: auto;
        }

        h1 {
            font-size: 30px;
            font-weight: 500;
        }

        label {
            display: block;
            margin-bottom: 6px;
        }

        input,
        select {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ced4da;
            border-radius: 5px;
        }

        .btn {
            display: inline-block;
            padding: 9px 14px;
            border: none;
            border-radius: 5px;
            color: white;
            text-decoration: none;
            cursor: pointer;
        }

        .btn-simpan {
            background-color: #0d6efd;
        }

        .btn-kembali {
            background-color: #6c757d;
        }

    </style>

</head>

<body>

<div class="container">

    <h1>Tambah Guru</h1>

    <form method="POST">

        <label>NIP</label>
        <input type="text" name="nip" required>

        <label>Nama</label>
        <input type="text" name="nama" required>

        <label>Email</label>
        <input type="email" name="email" required>

        <label>Akun Guru</label>

        <select name="user_id" required>

            <option value="">-- Pilih Akun Guru --</option>

            <?php while ($user = mysqli_fetch_assoc($users)) { ?>

                <option value="<?= $user['id']; ?>">
                    <?= htmlspecialchars($user['name']); ?>
                    - <?= htmlspecialchars($user['email']); ?>
                </option>

            <?php } ?>

        </select>

        <button type="submit"
                name="simpan"
                class="btn btn-simpan">

            Simpan

        </button>

        <a href="guru.php"
           class="btn btn-kembali">

            Kembali

        </a>

    </form>

</div>

</body>

</html>