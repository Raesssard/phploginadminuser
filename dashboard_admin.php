<?php
session_start();
if (!isset($_SESSION['email']) || $_SESSION['role'] !== 'admin') {
    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard</title>
    
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
            background: url('mantap.jpeg') no-repeat center center fixed; /* Gambar kucing lucu */
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
</head>
<body>
    <h2>Welcome to Admin Dashboard, <?php echo $_SESSION['email']; ?>!</h2>
    <p>Ini adalah halaman khusus admin.</p>
    <a href="logout.php">Logout</a>
</body>
</html>
