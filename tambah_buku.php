<?php
session_start();
include 'db.php'; // Pastikan koneksi database sudah benar

// Periksa apakah admin sudah login
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

// Jika form disubmit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $judul = $_POST['judul'];
    $penerbit = $_POST['penerbit'];
    $pengarang = $_POST['pengarang'];
    $tahun = $_POST['tahun'];
    $cover = null;

    // Proses upload file cover
    if (isset($_FILES['cover']) && $_FILES['cover']['error'] === UPLOAD_ERR_OK) {
        $cover = file_get_contents($_FILES['cover']['tmp_name']);
    }

    // Simpan data ke database
    $stmt = $conn->prepare("INSERT INTO buku (judul, penerbit, pengarang, tahun, cover) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssis", $judul, $penerbit, $pengarang, $tahun, $cover);

    if ($stmt->execute()) {
        header("Location: tabel_buku.php?message=success");
        exit();
    } else {
        $error = "Gagal menyimpan data. Silakan coba lagi.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Buku</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2 class="text-center mb-4">Tambah Buku Baru</h2>
        <?php if (isset($error)): ?>
            <div class="alert alert-danger"><?= $error ?></div>
        <?php endif; ?>
        <form action="" method="POST" enctype="multipart/form-data">
            <div class="mb-3">
                <label for="judul" class="form-label">Judul Buku</label>
                <input type="text" name="judul" id="judul" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="penerbit" class="form-label">Penerbit</label>
                <input type="text" name="penerbit" id="penerbit" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="pengarang" class="form-label">Pengarang</label>
                <input type="text" name="pengarang" id="pengarang" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="tahun" class="form-label">Tahun Terbit</label>
                <input type="number" name="tahun" id="tahun" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="cover" class="form-label">Upload Cover</label>
                <input type="file" name="cover" id="cover" class="form-control">
            </div>
            <button type="submit" class="btn btn-primary">Simpan</button>
            <a href="tabel_buku.php" class="btn btn-secondary">Kembali</a>
        </form>
    </div>
</body>
</html>
