<?php
session_start();
include 'db.php'; // Pastikan koneksi database sudah benar

// Periksa apakah admin sudah login
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

// Cek apakah ada parameter ID di URL
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Query untuk mendapatkan data pengguna berdasarkan ID
    $query = "SELECT * FROM user_admin_plain WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
    } else {
        die("Pengguna tidak ditemukan.");
    }

    // Proses update data pengguna
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $email = $_POST['email'];
        $password = $_POST['password']; // Pastikan password sudah di-hash jika diperlukan
        $role = $_POST['role'];

        // Cek apakah role yang diubah adalah admin
        if ($row['role'] == 'admin') {
            // Jika role adalah admin, jangan izinkan perubahan role
            $role = 'admin'; // Setel kembali role ke admin agar tidak berubah
        }

        // Update data di user_admin_plain
        $update_query_plain = "UPDATE user_admin_plain SET email = ?, password = ?, role = ? WHERE id = ?";
        $stmt = $conn->prepare($update_query_plain);
        $stmt->bind_param("sssi", $email, $password, $role, $id);

        // Proses update data di user_admin (Tabel lain selain plain)
        $update_query_admin = "UPDATE user_admin SET email = ?, password = ?, role = ? WHERE id = ?";
        $stmt_admin = $conn->prepare($update_query_admin);
        $stmt_admin->bind_param("sssi", $email, $password, $role, $id);

        if ($stmt->execute() && $stmt_admin->execute()) {
            header("Location: data_pengguna.php");
            exit();
        } else {
            echo "Gagal memperbarui data pengguna.";
        }
    }
} else {
    die("ID pengguna tidak ditemukan.");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Pengguna</title>
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
        .btn-primary {
            background-color: #007bff;
            border: none;
        }
        .btn-primary:hover {
            background-color: #0056b3;
        }
        .btn-success {
            background-color: #28a745;
            border: none;
        }
        .btn-success:hover {
            background-color: #1e7e34;
        }
        .button-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 20px;
        }
    </style>
</head>
<body>

<div class="container">
    <h2 class="text-center mt-5">Edit Pengguna</h2>
    <form method="POST" class="mt-4">
        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" class="form-control" id="email" name="email" value="<?php echo $row['email']; ?>" required>
        </div>
        <div class="mb-3">
            <label for="password" class="form-label">Password</label>
            <input type="password" class="form-control" id="password" name="password" value="<?php echo $row['password']; ?>" required>
        </div>
        <div>
            <h6>Role Tidak Bisa diubah</h6>    
        </div>

        <!-- Tombol Sejajar -->
        <div class="button-container">
        <button type="submit" class="btn btn-primary">Update Pengguna</button>
            <a href="data_pengguna.php" class="btn btn-success">Kembali</a>
            
        </div>
    </form>
</div>

</body>
</html>

