<?php
$host = 'localhost';
$user = 'root';
$pass = '';           // Password MySQL (kosongkan jika tidak ada password)
$db = 'admin_user';

$conn = new mysqli($host, $user, $pass, $db);

// Cek koneksi
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>