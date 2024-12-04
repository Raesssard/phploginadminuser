<?php

session_start();
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
        // Query untuk mencari pengguna berdasarkan email
        $stmt = $conn->prepare("SELECT email, password, role FROM user_admin WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();

            // Validasi password
            if (hash('sha512', $password) === $user['password']) {
                $_SESSION['email'] = $user['email'];
                $_SESSION['role'] = $user['role'];

                // Arahkan ke dashboard sesuai role
                if ($user['role'] === 'admin') {
                    header("Location: dashboard_admin.php");
                } elseif ($user['role'] === 'owner') {
                    header("Location: dashboard_owner.php");
                } else {
                    header("Location: dashboard_user.php");
                }
                exit();
            } else {
                $error = "Password salah!";
            }
        } else {
            $error = "Email tidak terdaftar!";
        }
    } else {
        $error = "Format email tidak valid!";
    }
}

?>




<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #000000, #001f3f);
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            color: #ffffff;
        }
        .login-container {
            background: rgba(255, 255, 255, 0.1);
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.3);
            padding: 30px;
            width: 100%;
            max-width: 400px;
            backdrop-filter: blur(10px);
        }
        .form-control {
            margin-bottom: 15px;
            background: rgba(255, 255, 255, 0.2);
            border: none;
            color: #ffffff;
        }
        .form-control::placeholder {
            color: #d1d1d1;
        }
        .form-control:focus {
            outline: none;
            box-shadow: 0 0 5px #007bff;
            background: rgba(255, 255, 255, 0.3);
        }
        .btn-primary {
            width: 100%;
            background-color: #007bff;
            border: none;
        }
        .btn-primary:hover {
            background-color: #0056b3;
        }
        .error-message {
            color: #ff4d4d;
            font-size: 0.9em;
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h2 class="text-center mb-4">Login</h2>
        <form method="POST">
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" name="email" id="email" class="form-control" placeholder="Masukkan email" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" name="password" id="password" class="form-control" placeholder="Masukkan password" required>
            </div>
            <button type="submit" class="btn btn-primary">Login</button>
        </form>
        <?php if (isset($error)) { echo "<p class='error-message text-center'>$error</p>"; } ?>
    </div>
</body>
</html>
