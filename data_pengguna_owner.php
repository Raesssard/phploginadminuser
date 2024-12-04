<?php
session_start();
include 'db.php'; // Pastikan koneksi database sudah benar

// Periksa apakah admin sudah login
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'owner') {
    header("Location: login.php");
    exit();
}

// Query untuk mengambil data pengguna dari tabel user_admin_plain (dengan password asli)
$query = "SELECT id, email, password, role FROM user_admin_plain";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Pengguna</title>
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
        .table-container {
            background: rgba(255, 255, 255, 0.1);
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.3);
        }
        .table {
            color: #ffffff;
            background: rgba(255, 255, 255, 0.05);
            border-collapse: collapse;
        }
        .table th {
            background: rgba(255, 255, 255, 0.2);
            color: #ffffff;
        }
        .table th, .table td {
            padding: 10px;
            border: 1px solid rgba(255, 255, 255, 0.2);
            text-align: center;
        }
        .btn-primary {
            background-color: #007bff;
            border: none;
        }
        .btn-primary:hover {
            background-color: #0056b3;
        }
        .btn-add-user {
            background-color: #28a745;
            border: none;
        }
        .btn-add-user:hover {
            background-color: #1e7e34;
        }
    </style>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark">
    <div class="container-fluid">
        <a class="navbar-brand" href="dashboard_owner.php">Dashboard Owner</a>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav">
            <li class="nav-item">
                        <a class="nav-link" href="data_pengguna_owner.php">Data Pengguna</a>
                    </li>
                <li class="nav-item">
                    <a class="nav-link" href="tabel_buku_owner.php">Tabel Buku</a>
                </li>
            </ul>
        </div>
        <a href="logout.php" class="btn btn-danger">Logout</a>
    </div>
</nav>
    <div class="container">
        <h2 class="text-center mb-4">Data Pengguna</h2>
        <div class="table-container">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Email</th>
                        <th>Password (Asli)</th> <!-- Menampilkan password asli -->
                        <th>Role</th>
                    </tr>
                </thead>
                <tbody>
    <?php
    if ($result->num_rows > 0) {
        // Menampilkan data pengguna dari hasil query
        while ($row = $result->fetch_assoc()) {
            echo "<tr>
                    <td>{$row['id']}</td>
                    <td>{$row['email']}</td>
                    <td>{$row['password']}</td> <!-- Menampilkan password asli -->
                    <td>{$row['role']}</td>
                  </tr>";
        }
    } else {
        echo "<tr><td colspan='5'>Tidak ada data pengguna.</td></tr>";
    }
    ?>
</tbody>

            </table>
           
        </div>
    </div>
</body>
</html>
