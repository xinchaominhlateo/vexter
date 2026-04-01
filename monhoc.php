<?php
session_start();
require_once 'connect.php';

if (!isset($_SESSION['TenDangNhap'])) {
    header("Location: login.php");
    exit();
}

$thongbao = "";

// Xử lý Thêm Môn học
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $mamon = $_POST['MaMon'];
    $tenmon = $_POST['TenMon'];
    // Khối có thể để trống (NULL) cho các môn học chung toàn trường
    $khoi = empty($_POST['Khoi']) ? "NULL" : $_POST['Khoi'];
    $hinhthuc = $_POST['HinhThucDanhGia'];

    $sql_insert = "INSERT INTO monhoc (MaMon, TenMon, Khoi, HinhThucDanhGia) VALUES ('$mamon', '$tenmon', $khoi, '$hinhthuc')";
    if ($conn->query($sql_insert) === TRUE) {
        $thongbao = "<span style='color:green;'>Thêm môn học thành công!</span>";
    } else {
        $thongbao = "<span style='color:red;'>Lỗi: " . $conn->error . "</span>";
    }
}

// Lấy danh sách Môn học
$sql_mon = "SELECT * FROM monhoc";
$result_mon = $conn->query($sql_mon);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Quản lý Môn học</title>
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
    <h2>QUẢN LÝ MÔN HỌC</h2>
    
    <div class="form-add">
        <h3>Thêm môn học mới</h3>
        <?php if($thongbao != "") echo "<p>$thongbao</p>"; ?>
        <form method="POST" action="">
            <input type="number" name="MaMon" placeholder="Mã Môn (VD: 6)" required>
            <input type="text" name="TenMon" placeholder="Tên Môn (VD: Sinh học)" required>
            <input type="number" name="Khoi" placeholder="Khối (để trống nếu học chung)">
            <select name="HinhThucDanhGia">
                <option value="ChoDiem">Cho điểm</option>
                <option value="NhanXet">Nhận xét</option>
            </select>
            <button type="submit" class="btn">Thêm</button>
        </form>
    </div>

    <table>
        <tr>
            <th>Mã Môn</th>
            <th>Tên Môn</th>
            <th>Khối</th>
            <th>Hình thức đánh giá</th>
            <th>Hành động</th>
        </tr>
        <?php
        if ($result_mon->num_rows > 0) {
            while($row = $result_mon->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . $row['MaMon'] . "</td>";
                echo "<td>" . $row['TenMon'] . "</td>";
                echo "<td>" . ($row['Khoi'] ? $row['Khoi'] : 'Chung') . "</td>";
                echo "<td>" . ($row['HinhThucDanhGia'] == 'ChoDiem' ? 'Cho điểm' : 'Nhận xét') . "</td>";
                echo "<td><a href='#' style='color:red;'>Xóa</a></td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='5' style='text-align:center;'>Chưa có dữ liệu.</td></tr>";
        }
        ?>
    </table>
</body>
</html>