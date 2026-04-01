<?php
session_start();
require_once 'connect.php';

if (!isset($_SESSION['TenDangNhap'])) {
    header("Location: login.php");
    exit();
}

$thongbao = "";
$user_hientai = $_SESSION['TenDangNhap'];

// Xử lý đổi mật khẩu
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['MatKhauMoi'])) {
// Băm mật khẩu ra chuỗi ký tự loằng ngoằng
    $matkhaumoi = password_hash($_POST['MatKhauMoi'], PASSWORD_DEFAULT);
    
    $sql_update = "UPDATE taikhoan SET MatKhau = '$matkhaumoi' WHERE TenDangNhap = '$user_hientai'";    if ($conn->query($sql_update) === TRUE) {
        $thongbao = "<span style='color:green;'>Đổi mật khẩu thành công!</span>";
    } else {
        $thongbao = "<span style='color:red;'>Lỗi: " . $conn->error . "</span>";
    }
}

// Lấy danh sách tài khoản
$sql_tk = "SELECT TenDangNhap, VaiTro FROM taikhoan";
$result_tk = $conn->query($sql_tk);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Quản lý Tài khoản</title>
    <style>
        body { font-family: Arial; padding: 20px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        .btn { padding: 8px 15px; background: #4CAF50; color: white; border-radius: 4px; border: none; cursor: pointer; text-decoration: none;}
        .form-doimk { background: #f9f9f9; padding: 15px; border: 1px solid #ddd; margin-bottom: 20px; max-width: 400px;}
    </style>
</head>
<body>
    <a href="index.php" class="btn" style="background: #555;">&larr; Về Trang chủ</a>
    <h2>QUẢN LÝ TÀI KHOẢN</h2>
    
    <div class="form-doimk">
        <h3>Hồ sơ của tôi: <?php echo $user_hientai; ?></h3>
        <?php if($thongbao != "") echo "<p>$thongbao</p>"; ?>
        <form method="POST" action="">
            <div style="margin-bottom: 10px;">
                <label>Mật khẩu mới:</label><br>
                <input type="password" name="MatKhauMoi" required style="width: 100%; padding: 8px; margin-top: 5px; box-sizing: border-box;">
            </div>
            <button type="submit" class="btn">Đổi mật khẩu</button>
        </form>
    </div>

    <h3>Danh sách tài khoản hệ thống</h3>
    <table>
        <tr>
            <th>Tên đăng nhập</th>
            <th>Vai trò</th>
        </tr>
        <?php
        if ($result_tk->num_rows > 0) {
            while($row = $result_tk->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . $row['TenDangNhap'] . "</td>";
                echo "<td>" . $row['VaiTro'] . "</td>";
                echo "</tr>";
            }
        }
        ?>
    </table>
</body>
</html>