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

    // Query untuk mendapatkan data buku berdasarkan ID
    $query = "SELECT * FROM buku WHERE no = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
    } else {
        die("Buku tidak ditemukan.");
    }

    // Proses update data buku
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $judul = $_POST['judul'];
        $penerbit = $_POST['penerbit'];
        $pengarang = $_POST['pengarang'];
        $tahun = $_POST['tahun'];

        $update_query = "UPDATE buku SET judul = ?, penerbit = ?, pengarang = ?, tahun = ? WHERE no = ?";
        $stmt = $conn->prepare($update_query);
        $stmt->bind_param("ssssi", $judul, $penerbit, $pengarang, $tahun, $id);

        if ($stmt->execute()) {
            header("Location: tabel_buku.php");
            exit();
        } else {
            echo "Gagal memperbarui data.";
        }
    }
} else {
    die("ID buku tidak ditemukan.");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Buku</title>
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
    <h2 class="text-center mt-5">Edit Buku</h2>
    <form method="POST" class="mt-4">
        <div class="mb-3">
            <label for="judul" class="form-label">Judul</label>
            <input type="text" class="form-control" id="judul" name="judul" value="<?php echo $row['judul']; ?>" required>
        </div>
        <div class="mb-3">
            <label for="penerbit" class="form-label">Penerbit</label>
            <input type="text" class="form-control" id="penerbit" name="penerbit" value="<?php echo $row['penerbit']; ?>" required>
        </div>
        <div class="mb-3">
            <label for="pengarang" class="form-label">Pengarang</label>
            <input type="text" class="form-control" id="pengarang" name="pengarang" value="<?php echo $row['pengarang']; ?>" required>
        </div>
        <div class="mb-3">
            <label for="tahun" class="form-label">Tahun</label>
            <input type="text" class="form-control" id="tahun" name="tahun" value="<?php echo $row['tahun']; ?>" required>
        </div>

        <!-- Tombol Sejajar -->
        <div class="button-container">
            <button type="submit" class="btn btn-primary">Update Buku</button>
            <a href="tabel_buku.php" class="btn btn-success">Kembali</a>
        </div>
    </form>
</div>

</body>
</html>
