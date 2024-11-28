<?php
session_start();
include 'db.php'; // Pastikan koneksi database sudah benar

// Periksa apakah admin sudah login
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];
    $role = $_POST['role'];

    // Validasi data input (misalnya memastikan email tidak kosong)
    if (!empty($email) && !empty($password) && !empty($role)) {
        // Simpan ke tabel user_admin_plain (password asli)
        $stmt_plain = $conn->prepare("INSERT INTO user_admin_plain (email, password, role) VALUES (?, ?, ?)");
        $stmt_plain->bind_param("sss", $email, $password, $role);
        
        // Hash password untuk disimpan di tabel user_admin
        $hashed_password = hash('sha512', $password);
        
        // Simpan ke tabel user_admin (password hashed)
        $stmt_admin = $conn->prepare("INSERT INTO user_admin (email, password, role) VALUES (?, ?, ?)");
        $stmt_admin->bind_param("sss", $email, $hashed_password, $role);
        
        // Menjalankan kedua query
        if ($stmt_plain->execute() && $stmt_admin->execute()) {
            $success = "User berhasil ditambahkan!";
        } else {
            $error = "Gagal menambahkan user.";
        }
    } else {
        $error = "Semua kolom harus diisi!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah User</title>
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
        .form-container {
            background: rgba(255, 255, 255, 0.1);
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.3);
        }
        .form-group {
            margin-bottom: 20px;
        }
        .btn-primary {
            background-color: #007bff;
            border: none;
        }
        .btn-primary:hover {
            background-color: #0056b3;
        }
        .btn-back {
            background-color: #6c757d;
            border: none;
        }
        .btn-back:hover {
            background-color: #5a6268;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="dashboard_admin.php">Dashboard Admin</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" href="data_pengguna.php">Data Pengguna</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="tabel_buku.php">Tabel Buku</a>
                    </li>
                </ul>
            </div>
            <div class="d-flex">
                <a href="logout.php" class="btn btn-danger">Logout</a>
            </div>
        </div>
    </nav>
    <div class="container">
        <h2 class="text-center mb-4">Tambah User</h2>
        <div class="form-container">
            <!-- Menampilkan pesan error atau sukses -->
            <?php if (isset($error)) { echo "<div class='alert alert-danger'>$error</div>"; } ?>
            <?php if (isset($success)) { echo "<div class='alert alert-success'>$success</div>"; } ?>

            <form method="POST" action="tambah_user.php">
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" class="form-control" id="email" name="email" required>
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" class="form-control" id="password" name="password" required>
                </div>
                <div class="form-group">
                    <label for="role">Role</label>
                    <select class="form-control" id="role" name="role" required>
                        <option value="admin">admin</option>
                        <option value="user">user</option>
                    </select>
                </div>
                <div class="form-group d-flex justify-content-between mt-3">
                    <a href="data_pengguna.php" class="btn btn-back">Kembali</a>
                    <button type="submit" class="btn btn-primary">Tambah User</button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
         