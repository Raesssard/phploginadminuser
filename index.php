<?php
include 'db.php';
session_start();

if (isset($_SESSION['email'])) {
    // Arahkan berdasarkan peran user
    if ($_SESSION['role'] === 'admin') {
        header("Location: dashboard_admin.php");
    } else {
        header("Location: dashboard_user.php");
    }
    exit();
}

if (isset($_POST['submit'])) {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = $_POST['password'];

    $sql = "SELECT * FROM user_admin WHERE email = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, 's', $email);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);

        // Verifikasi password menggunakan password_verify
        if (hash('sha256', $password) === $row['password']) {
            $_SESSION['email'] = $row['email'];
            $_SESSION['role'] = $row['role'];

            // Arahkan berdasarkan peran
            if ($row['role'] === 'admin') {
                header("Location: dashboard_admin.php");
            } else {
                header("Location: dashboard_user.php");
            }
            exit();
        } else {
            echo "<script>alert('Email atau password salah!');</script>";
        }
    } else {
        echo "<script>alert('Email atau password salah!');</script>";
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
    <title>Login</title>
</head>
<body>
    <div class="container">
        <form action="" method="POST" class="login-email">
            <p class="login-text" style="font-size: 2rem; font-weight: 800;">Login</p>
            <div class="input-group">
                <input type="email" placeholder="Email" name="email" required>
            </div>
            <div class="input-group">
                <input type="password" placeholder="Password" name="password" required>
            </div>
            <div class="input-group">
                <button name="submit" class="btn">Login</button>
            </div>
            <p class="login-register-text">Belum punya akun? <a href="register.php">Daftar</a>.</p>
        </form>
    </div>
</body>
</html>
