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

    // Query untuk mengambil data pengguna berdasarkan ID
    $query = "SELECT * FROM user_admin_plain WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        
        // Cek apakah pengguna yang ingin dihapus adalah admin
        if ($user['role'] === 'admin') {
            // Jika yang ingin dihapus adalah admin, beri peringatan dan batalkan penghapusan
            echo "<script>alert('Anda tidak bisa menghapus pengguna dengan role admin.'); window.location.href='data_pengguna.php';</script>";
            exit();
        }

        // Query untuk menghapus pengguna berdasarkan ID jika bukan admin
        $delete_query = "DELETE FROM user_admin_plain WHERE id = ?";
        $delete_quer = "DELETE FROM user_admin WHERE id = ?";
        $stmt = $conn->prepare($delete_query);
        $stmt->bind_param("i", $id);

        if ($stmt->execute()) {
            // Jika penghapusan berhasil, redirect ke halaman data pengguna
            header("Location: data_pengguna.php");
            exit();
        } else {
            echo "Gagal menghapus data pengguna.";
        }
    } else {
        echo "Pengguna tidak ditemukan.";
    }
} else {
    echo "ID pengguna tidak ditemukan.";
}
?>
