<?php
session_start();
require_once 'connect.php'; 

$error = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Lọc dữ liệu chống SQL Injection
    $username = mysqli_real_escape_string($conn, $_POST['TenDangNhap']);
    $password = $_POST['MatKhau'];

    // Chỉ tìm tên đăng nhập
    $sql = "SELECT * FROM taikhoan WHERE TenDangNhap = '$username'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        
        // Dùng password_verify để kiểm tra mật khẩu đã băm
        if (password_verify($password, $row['MatKhau'])) {
            $_SESSION['TenDangNhap'] = $row['TenDangNhap'];
            $_SESSION['VaiTro'] = $row['VaiTro']; 
            
            header("Location: index.php"); 
            exit();
        } else {
            $error = "Mật khẩu không đúng!";
        }
    } else {
        $error = "Tên đăng nhập không tồn tại!";
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
        input { width: 90%; padding: 10px; margin: 10px 0; box-sizing: border-box;}
        button { padding: 10px 20px; cursor: pointer; margin-top: 10px;}
        .error { color: red; font-weight: bold; }
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
            <button type="submit" style="background: #4CAF50; color: white; border: none; width: 100%; font-size: 16px;">Đăng nhập</button>
        </form>
    </div>
</body>
</html>