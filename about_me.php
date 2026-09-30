<?php

session_start();

if (!isset($_SESSION['login'])) {
    header("Location: login.php");
    exit;
}

?>

<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>About Me</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css"
        rel="stylesheet">

    <style>

        body {
            background-color: #f5f6fa;
        }

        .profile-card {
            max-width: 600px;
            margin: 60px auto;
        }

        .profile-header {
            background-color: #344D7D;
            color: white;
        }

    </style>

</head>

<body>

<div class="container">

    <div class="card shadow profile-card">

        <div class="card-header profile-header text-center">
            <h3>About Me</h3>
        </div>

        <div class="card-body">

            <h4 class="text-center mb-4">
                Halo, saya <?= $_SESSION['nama']; ?> 
            </h4>

            <p>
                <b>Nama:</b> Agnia
            </p>

            <p>
                <b>Status:</b> Pelajar
            </p>

            <p>
                <b>Jurusan:</b> Rekayasa Perangkat Lunak (RPL)
            </p>

            <p>
                <b>Sekolah:</b> SMK Muhammadiyah
            </p>

            <p>
                <b>Kelas:</b> XII RPL 2
            </p>

            <hr>

            <h5>Tentang Saya</h5>

            <p>
                Saya adalah seorang pelajar jurusan Rekayasa Perangkat Lunak
                yang sedang belajar tentang pemrograman, database, dan
                pembuatan aplikasi.
            </p>

            <p>
                Saya ingin terus mengembangkan kemampuan saya di bidang
                teknologi dan pemrograman serta mendapatkan pengalaman
                baru melalui berbagai tugas dan proyek.
            </p>

            <hr>

            <h5>Keahlian</h5>

            <ul>
                <li>HTML dan CSS</li>
                <li>PHP</li>
                <li>MySQL</li>
                <li>Bootstrap</li>
                <li>Database</li>
            </ul>

            <div class="text-center mt-4">

                <a href="dashboard.php"
                   class="btn btn-primary">
                    Kembali ke Dashboard
                </a>

            </div>

        </div>

    </div>

</div>

</body>

</html>