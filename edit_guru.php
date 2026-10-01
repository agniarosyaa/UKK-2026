<?php

include "config/koneksi.php";

$id = $_GET['id'];

$query = "SELECT * FROM t_guru WHERE id = '$id'";
$result = mysqli_query($koneksi, $query);

$data = mysqli_fetch_assoc($result);

if (!$data) {
    die("Data guru tidak ditemukan.");
}

if (isset($_POST['update'])) {

    $nip = $_POST['nip'];
    $nama = $_POST['nama'];
    $email = $_POST['email'];
    $user_id = $_POST['user_id'];
    $status_aktif = $_POST['status_aktif'];

    $query = "UPDATE t_guru SET
                nip = '$nip',
                nama = '$nama',
                email = '$email',
                user_id = '$user_id',
                status_aktif = '$status_aktif'
              WHERE id = '$id'";

    $result = mysqli_query($koneksi, $query);

    if ($result) {
        header("Location: guru.php");
        exit;
    } else {
        echo "Data gagal diubah: " . mysqli_error($koneksi);
    }
}

?>

<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Edit Guru</title>

    <style>

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            padding: 30px;
            font-family: Arial, Helvetica, sans-serif;
            background-color: #ffffff;
            color: #111827;
        }

        .container {
            max-width: 700px;
            margin: 0 auto;
        }

        h1 {
            font-size: 30px;
            margin-bottom: 20px;
            font-weight: 500;
        }

        .form-box {
            border: 1px solid #dee2e6;
            padding: 25px;
            border-radius: 5px;
        }

        .form-group {
            margin-bottom: 15px;
        }

        label {
            display: block;
            margin-bottom: 7px;
            font-weight: bold;
            font-size: 14px;
        }

        input,
        select {
            width: 100%;
            padding: 10px;
            border: 1px solid #ced4da;
            border-radius: 5px;
            font-size: 14px;
        }

        input:focus,
        select:focus {
            outline: none;
            border-color: #0d6efd;
        }

        .button-area {
            margin-top: 20px;
        }

        .btn {
            display: inline-block;
            padding: 9px 14px;
            border-radius: 5px;
            text-decoration: none;
            color: white;
            font-size: 14px;
            border: none;
            cursor: pointer;
        }

        .btn-update {
            background-color: #0d6efd;
        }

        .btn-update:hover {
            background-color: #0b5ed7;
        }

        .btn-kembali {
            background-color: #6c757d;
            margin-left: 5px;
        }

        .btn-kembali:hover {
            background-color: #5c636a;
        }

    </style>

</head>

<body>

<div class="container">

    <h1>Edit Guru</h1>

    <div class="form-box">

        <form method="POST">

            <div class="form-group">

                <label>NIP</label>

                <input
                    type="text"
                    name="nip"
                    value="<?= htmlspecialchars($data['nip']); ?>"
                    required
                >

            </div>

            <div class="form-group">

                <label>Nama Guru</label>

                <input
                    type="text"
                    name="nama"
                    value="<?= htmlspecialchars($data['nama']); ?>"
                    required
                >

            </div>

            <div class="form-group">

                <label>Email</label>

                <input
                    type="email"
                    name="email"
                    value="<?= htmlspecialchars($data['email']); ?>"
                    required
                >

            </div>

            <div class="form-group">

                <label>User ID</label>

                <input
                    type="number"
                    name="user_id"
                    value="<?= htmlspecialchars($data['user_id']); ?>"
                    required
                >

            </div>

            <div class="form-group">

                <label>Status</label>

                <select name="status_aktif" required>

                    <option value="1"
                        <?= $data['status_aktif'] == 1 ? 'selected' : ''; ?>>
                        Aktif
                    </option>

                    <option value="0"
                        <?= $data['status_aktif'] == 0 ? 'selected' : ''; ?>>
                        Tidak Aktif
                    </option>

                </select>

            </div>

            <div class="button-area">

                <button
                    type="submit"
                    name="update"
                    class="btn btn-update">
                    Update
                </button>

                <a
                    href="guru.php"
                    class="btn btn-kembali">
                    Kembali
                </a>

            </div>

        </form>

    </div>

</div>

</body>

</html>