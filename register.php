<?php
include 'db.php';
session_start();

// Jika pengguna sudah login, arahkan mereka ke halaman sesuai peran
if (isset($_SESSION['email'])) {
    if ($_SESSION['role'] === 'admin') {
        header("Location: dashboard_admin.php");
    } else {
        header("Location: dashboard_user.php");
    }
    exit();
}

// Proses registrasi saat form disubmit
if (isset($_POST['submit'])) {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Validasi jika email sudah terdaftar
    $check_email = "SELECT email FROM user_admin WHERE email = ?";
    $stmt = mysqli_prepare($conn, $check_email);
    mysqli_stmt_bind_param($stmt, 's', $email);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if (mysqli_num_rows($result) > 0) {
        echo "<script>alert('Email sudah terdaftar, silakan gunakan email lain.');</script>";
    } else {
        // Validasi kecocokan password dan minimal panjang password
        if ($password !== $confirm_password) {
            echo "<script>alert('Password tidak cocok.');</script>";
        } elseif (strlen($password) < 6) {
            echo "<script>alert('Password harus memiliki setidaknya 6 karakter.');</script>";
        } else {
            // Hash password sebelum disimpan
            $hashed_password = hash('sha256', $password);

            // Masukkan user ke database dengan peran "user"
            $insert_user = "INSERT INTO user_admin (email, password, role) VALUES (?, ?, 'user')";
            $stmt = mysqli_prepare($conn, $insert_user);
            mysqli_stmt_bind_param($stmt, 'ss', $email, $hashed_password);

            if (mysqli_stmt_execute($stmt)) {
                echo "<script>alert('Registrasi berhasil! Silakan login.'); window.location.href = 'index.php';</script>";
            } else {
                echo "<script>alert('Registrasi gagal, coba lagi.');</script>";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
  
      
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Arial', sans-serif;
        }

        body {
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            background: url('OIP.jpeg') no-repeat center center fixed; /* Gambar kucing lucu */
            background-size: cover;
            padding: 20px;
            font-family: Arial, sans-serif;
        }

        .container {
            width: 100%;
            max-width: 400px;
            background: rgba(255, 255, 255, 0.9); /* Transparan agar latar belakang tetap terlihat */
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0px 8px 16px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        .login-text {
            font-size: 2rem;
            font-weight: 700;
            color: #333;
            margin-bottom: 20px;
        }

        .login-email {
            display: flex;
            flex-direction: column;
        }

        .input-group {
            margin-bottom: 15px;
            position: relative;
        }

        .input-group input {
            width: 100%;
            padding: 12px 15px;
            font-size: 1rem;
            border: 1px solid #ddd;
            border-radius: 5px;
            background: #f7f7f7;
            outline: none;
            transition: all 0.3s ease;
        }

        .input-group input:focus {
            border-color: #4c6ef5;
        }

        .btn {
            background-color: #4c6ef5;
            color: white;
            border: none;
            padding: 12px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 1.2rem;
            transition: background-color 0.3s ease;
        }

        .btn:hover {
            background-color: #357ae8;
        }

        .login-register-text {
            margin-top: 15px;
            font-size: 1rem;
        }

        .login-register-text a {
            color: #4c6ef5;
            text-decoration: none;
        }

        .login-register-text a:hover {
            text-decoration: underline;
        }

        /* Responsif */
        @media (max-width: 768px) {
            body {
                padding: 10px;
            }

            .container {
                padding: 30px;
                width: 90%;
            }
        }
    </style>
    <title>Register</title>
</head>
<body>
    <div class="container">
        <form action="" method="POST" class="login-email">
            <p class="login-text" style="font-size: 2rem; font-weight: 800;">User Register</p>
            <div class="input-group">
                <input type="email" placeholder="Email" name="email" required>
            </div>
            <div class="input-group">
                <input type="password" placeholder="Password" name="password" required>
            </div>
            <div class="input-group">
                <input type="password" placeholder="Confirm Password" name="confirm_password" required>
            </div>
            <div class="input-group">
                <button name="submit" class="btn">Register</button>
            </div>
            <p class="login-register-text">Sudah punya akun? <a href="index.php">Login</a>.</p>
        </form>
    </div>
</body>
</html>
