<?php
session_start();

// Periksa apakah user sudah login
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'user') {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard User</title>
    <!-- Menyertakan Bootstrap CSS dari CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #000000, #001f3f);
            font-family: Arial, sans-serif;
            color: #ffffff;
            margin: 0;
            padding: 0;
        }

        .navbar {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.2);
        }

        .navbar .nav-link {
            color: #ffffff;
            font-weight: bold;
        }

        .navbar .nav-link:hover {
            color: #007bff;
        }

        .container {
            margin-top: 50px;
        }

        .card {
            background: rgba(255, 255, 255, 0.1);
            border: none;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.3);
            color: #ffffff;
        }

        .card:hover {
            transform: scale(1.02);
            transition: 0.3s ease-in-out;
        }

        .btn-primary {
            background-color: #007bff;
            border: none;
        }

        .btn-primary:hover {
            background-color: #0056b3;
        }
    </style>
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="dashboard_user.php">Dashboard User</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" href="tabel_buku_user.php">Tabel Buku</a>
                    </li>
                </ul>
            </div>
            <div class="d-flex">
                <a href="logout.php" class="btn btn-danger">Logout</a>
            </div>
        </div>
    </nav>

    <div class="container">
        <div class="row">
            <div class="col-md-4">
                <div class="card p-3 mb-4">
                    <h5 class="card-title">Dashboard User</h5>
                    <p class="card-text">Kembali ke halaman utama Dashboard User.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card p-3 mb-4">
                    <h5 class="card-title">Tabel Buku</h5>
                    <p class="card-text">Lihat data buku</p>
                </div>
            </div>
        </div>
    </div>
</body>

</html>
