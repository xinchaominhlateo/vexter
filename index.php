<?php
session_start();
if (!isset($_SESSION['TenDangNhap'])) {
    header("Location: login.php"); // Chưa đăng nhập thì đuổi về trang login
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Trang chủ - Quản lý học sinh</title>
    <style>
        body { font-family: Arial; margin: 0; display: flex; }
        .sidebar { width: 200px; background: #333; color: white; height: 100vh; padding-top: 20px; }
        .sidebar a { display: block; color: white; padding: 15px; text-decoration: none; border-bottom: 1px solid #444; }
        .sidebar a:hover { background: #555; }
        .content { padding: 20px; width: 100%; }
    </style>
</head>
<body>

<div class="sidebar">
    <h3 style="text-align:center;">MENU</h3>
    <a href="index.php">TRANG CHỦ</a>
    <a href="hocsinh.php">HỌC SINH</a>
    <a href="giaovien.php">GIÁO VIÊN</a>
    <a href="lop.php">LỚP</a>
    <a href="monhoc.php">MÔN HỌC</a>
    <a href="diem.php">ĐIỂM</a>
    <a href="hanhkiem.php">HẠNH KIỂM</a>
    <a href="taikhoan.php">TÀI KHOẢN</a>
    <a href="logout.php">ĐĂNG XUẤT</a>
</div>

<div class="content">
    <h2>Xin chào, <?php echo $_SESSION['TenDangNhap']; ?> !</h2>
    <p>Chào mừng bạn đến với Hệ thống quản lý học sinh.</p>
    </div>

</body>
</html>