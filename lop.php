<?php
session_start();
require_once 'connect.php';

if (!isset($_SESSION['TenDangNhap'])) {
    header("Location: login.php");
    exit();
}

$thongbao = "";

// Xử lý Thêm Lớp
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $malop = $_POST['MaLop'];
    $tenlop = $_POST['TenLop'];
    $khoi = $_POST['Khoi'];

    $sql_insert = "INSERT INTO lop (MaLop, TenLop, Khoi) VALUES ('$malop', '$tenlop', '$khoi')";
    if ($conn->query($sql_insert) === TRUE) {
        $thongbao = "<span style='color:green;'>Thêm lớp thành công!</span>";
    } else {
        $thongbao = "<span style='color:red;'>Lỗi: " . $conn->error . "</span>";
    }
}

// Lấy danh sách Lớp [cite: 291]
$sql_lop = "SELECT * FROM lop";
$result_lop = $conn->query($sql_lop);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Quản lý Lớp</title>
    <style>
        body { font-family: Arial; padding: 20px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        .btn { padding: 8px 15px; text-decoration: none; background: #4CAF50; color: white; border-radius: 4px; border: none; cursor: pointer;}
        .form-add { background: #f9f9f9; padding: 15px; border: 1px solid #ddd; margin-bottom: 20px; }
    </style>
</head>
<body>
    <a href="index.php" class="btn" style="background: #555;">&larr; Về Trang chủ</a>
    <h2>QUẢN LÝ LỚP HỌC</h2>
    
    <div class="form-add">
        <h3>Thêm lớp mới</h3>
        <?php if($thongbao != "") echo "<p>$thongbao</p>"; ?>
        <form method="POST" action="">
            <input type="number" name="MaLop" placeholder="Mã Lớp (VD: 1)" required>
            <input type="text" name="TenLop" placeholder="Tên Lớp (VD: 10A1)" required>
            <input type="number" name="Khoi" placeholder="Khối (VD: 10)" required>
            <button type="submit" class="btn">Thêm</button>
        </form>
    </div>

    <table>
        <tr>
            <th>Mã Lớp</th>
            <th>Tên Lớp</th>
            <th>Khối</th>
            <th>Hành động</th>
        </tr>
        <?php
        if ($result_lop->num_rows > 0) {
            while($row = $result_lop->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . $row['MaLop'] . "</td>";
                echo "<td>" . $row['TenLop'] . "</td>";
                echo "<td>" . $row['Khoi'] . "</td>";
                echo "<td><a href='#' style='color:red;'>Xóa</a></td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='4' style='text-align:center;'>Chưa có dữ liệu.</td></tr>";
        }
        ?>
    </table>
</body>
</html>