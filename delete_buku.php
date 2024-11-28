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

    // Query untuk menghapus buku berdasarkan ID
    $query = "DELETE FROM buku WHERE no = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        header("Location: tabel_buku.php");
        exit();
    } else {
        echo "Gagal menghapus data buku.";
    }
} else {
    die("ID buku tidak ditemukan.");
}
?>
