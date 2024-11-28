<?php
include 'db.php'; // Pastikan koneksi sudah benar

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Query untuk mengambil gambar berdasarkan ID
    $query = "SELECT cover FROM buku WHERE no = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($cover);
    $stmt->fetch();

    // Set header untuk memberi tahu browser ini adalah gambar PNG
    header("Content-Type: image/png");
    echo $cover;
}
?>
