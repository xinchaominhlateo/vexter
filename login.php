<?php
session_start();
require_once 'connect.php'; // Gọi file kết nối Database

$error = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['TenDangNhap'];
    $password = $_POST['MatKhau'];

    // Truy vấn kiểm tra tài khoản [cite: 104-109]
    $sql = "SELECT * FROM taikhoan WHERE TenDangNhap = '$username' AND MatKhau = '$password'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $_SESSION['TenDangNhap'] = $row['TenDangNhap'];
        $_SESSION['VaiTro'] = $row['VaiTro']; // Lưu vai trò để phân quyền sau này [cite: 108]
        
        header("Location: index.php"); // Chuyển hướng về trang chủ
        exit();
    } else {
        $error = "Tên đăng nhập hoặc mật khẩu không đúng!";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Đăng nhập - Quản lý học sinh</title>
    <style>
        body { font-family: Arial; display: flex; justify-content: center; align-items: center; height: 100vh; background-color: #f4f4f4;}
        .login-box { background: white; padding: 20px; border-radius: 8px; box-shadow: 0 0 10px rgba(0,0,0,0.1); width: 300px; text-align: center;}
        input { width: 90%; padding: 10px; margin: 10px 0; }
        button { padding: 10px 20px; cursor: pointer; }
        .error { color: red; }
    </style>
</head>
<body>
    <div class="login-box">
        <h2>HỆ THỐNG QUẢN LÝ HỌC SINH</h2>
        <h3>ĐĂNG NHẬP</h3>
        <?php if($error != '') echo "<p class='error'>$error</p>"; ?>
        <form method="POST" action="">
            <input type="text" name="TenDangNhap" placeholder="Tên đăng nhập" required>
            <input type="password" name="MatKhau" placeholder="Mật khẩu" required>
            <br>
            <button type="reset">Đặt lại</button>
            <button type="submit">Đăng nhập</button>
        </form>
    </div>
</body>
</html>