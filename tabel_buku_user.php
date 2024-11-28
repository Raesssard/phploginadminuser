<?php
session_start();
include 'db.php'; // Pastikan koneksi database sudah benar

// Periksa apakah user sudah login
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'user') {
    header("Location: login.php");
    exit();
}

// Inisialisasi variabel pencarian
$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$filter = isset($_GET['filter']) ? $_GET['filter'] : 'judul'; // Default filter: judul

// Validasi filter untuk menghindari kolom SQL injection
$valid_filters = ['judul', 'penerbit', 'pengarang', 'tahun'];
if (!in_array($filter, $valid_filters)) {
    $filter = 'judul';
}

// Query dasar
$query = "SELECT no, judul, penerbit, pengarang, tahun, LENGTH(cover) AS ukuran_cover FROM buku";
$params = [];
$searchCondition = "";

// Tambahkan kondisi pencarian jika ada input
if ($search !== '') {
    $searchCondition = " WHERE $filter LIKE ?";
    $params = ["%$search%"];
}

// Gabungkan query dengan kondisi
$query .= $searchCondition;

// Gunakan prepared statements
$stmt = $conn->prepare($query);
if (!empty($params)) {
    $stmt->bind_param("s", ...$params);
}
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tabel Buku</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #000000, #001f3f);
            font-family: Arial, sans-serif;
            color: #ffffff;
        }
        .navbar {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
        }
        .navbar .nav-link {
            color: #ffffff;
        }
        .table-container {
            background: rgba(255, 255, 255, 0.1);
            padding: 20px;
            border-radius: 10px;
        }
        .table {
            color: #ffffff;
            background: rgba(255, 255, 255, 0.05);
        }
        .cover-img {
            width: 100px;
            height: 150px;
            object-fit: cover;
        }
        .search-bar {
            width: 204;
            margin-bottom: 20px;
        }
        .dropdown-filter {
            margin-bottom: 10px;
        }
        .form-select, .form-control, .btn {
            width: 204px;
            height: 36px;
            font-size: 14px;
        }
    </style>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark">
    <div class="container-fluid">
        <a class="navbar-brand" href="dashboard_user.php">Dashboard User</a>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" href="tabel_buku_user.php">Tabel Buku</a>
                </li>
            </ul>
        </div>
        <a href="logout.php" class="btn btn-danger">Logout</a>
    </div>
</nav>


<h2 class="text-center mb-4 mt-5">Tabel Buku</h2>
<div class="container mt-5">
 <!-- Search Form -->
    <form method="GET" action="" class="search-bar">
        <!-- Dropdown Filter -->
        <div class="dropdown-filter">
            <select name="filter" class="form-select">
                <option value="judul" <?= $filter === 'judul' ? 'selected' : '' ?>>Judul</option>
                <option value="penerbit" <?= $filter === 'penerbit' ? 'selected' : '' ?>>Penerbit</option>
                <option value="pengarang" <?= $filter === 'pengarang' ? 'selected' : '' ?>>Pengarang</option>
                <option value="tahun" <?= $filter === 'tahun' ? 'selected' : '' ?>>Tahun</option>
            </select>
        </div>
        <!-- Search Input -->
        <div class="input-group">
            <input type="text" name="search" class="form-control" placeholder="Cari buku..." value="<?= htmlspecialchars($search) ?>">
            <button type="submit" class="btn btn-primary">Cari</button>
        </div>
    </form>
</div>

<div class="container">
    

   

    <div class="table-container">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Judul</th>
                    <th>Penerbit</th>
                    <th>Pengarang</th>
                    <th>Tahun</th>
                    <th>Cover</th>
                </tr>
            </thead>
            <tbody>
<?php
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo "<tr>
                <td>{$row['no']}</td>
                <td>{$row['judul']}</td>
                <td>{$row['penerbit']}</td>
                <td>{$row['pengarang']}</td>
                <td>{$row['tahun']}</td>
                <td><img src='tampil_cover.php?id={$row['no']}' class='cover-img' alt='Cover'></td>
              </tr>";
    }
} else {
    echo "<tr><td colspan='6'>Tidak ada data buku yang ditemukan.</td></tr>";
}
?>
            </tbody>
        </table>
    </div>
</div>
</body>
</html>
