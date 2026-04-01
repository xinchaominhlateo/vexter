<?php
session_start();
require_once 'connect.php';

if (!isset($_SESSION['TenDangNhap'])) {
    header("Location: login.php"); exit();
}

if (isset($_GET['id'])) {
    $id = mysqli_real_escape_string($conn, $_GET['id']);
    // Chạy lệnh xóa học sinh
    $sql = "DELETE FROM hocsinh WHERE MaHS = '$id'";
    $conn->query($sql);
}

// Xóa xong tự động quay về trang danh sách
header("Location: hocsinh.php");
exit();
?>