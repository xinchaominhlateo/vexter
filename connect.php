<?php
$servername = "127.0.0.1";
$username = "root"; // Mặc định của XAMPP
$password = ""; // Mặc định thường để trống
$dbname = "quanlyhocsinhc3_vertex";

// Tạo kết nối
$conn = new mysqli($servername, $username, $password, $dbname);

// Kiểm tra kết nối
if ($conn->connect_error) {
  die("Kết nối thất bại: " . $conn->connect_error);
}
// Set charset để không lỗi tiếng Việt
mysqli_set_charset($conn, 'UTF8');
?>