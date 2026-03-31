<?php
session_start();
require_once 'connect.php';

if (!isset($_SESSION['TenDangNhap'])) {
    header("Location: login.php");
    exit();
}

$thongbao = "";

// Xử lý Thêm Giáo viên
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $magv = $_POST['MaGV'];
    $hoten = $_POST['HoTen'];
    $dienthoai = $_POST['DienThoai'];
    $mamon = empty($_POST['MaMon']) ? "NULL" : $_POST['MaMon'];

    $sql_insert = "INSERT INTO giaovien (MaGV, HoTen, DienThoai, MaMon) VALUES ('$magv', '$hoten', '$dienthoai', $mamon)";
    if ($conn->query($sql_insert) === TRUE) {
        $thongbao = "<span style='color:green;'>Thêm giáo viên thành công!</span>";
    } else {
        $thongbao = "<span style='color:red;'>Lỗi: " . $conn->error . "</span>";
    }
}

// Lấy danh sách Giáo viên kết hợp Tên môn học [cite: 43-51, 93-99]
$sql_gv = "SELECT giaovien.MaGV, giaovien.HoTen, giaovien.DienThoai, monhoc.TenMon 
           FROM giaovien 
           LEFT JOIN monhoc ON giaovien.MaMon = monhoc.MaMon";
$result_gv = $conn->query($sql_gv);

// Lấy danh sách Môn học cho Dropdown
$result_mon = $conn->query("SELECT * FROM monhoc");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Quản lý Giáo viên</title>
    <style>
        body { font-family: Arial; padding: 20px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        .btn { padding: 8px 15px; background: #4CAF50; color: white; border-radius: 4px; border: none; cursor: pointer; text-decoration: none;}
        .form-add { background: #f9f9f9; padding: 15px; border: 1px solid #ddd; margin-bottom: 20px; }
    </style>
</head>
<body>
    <a href="index.php" class="btn" style="background: #555;">&larr; Về Trang chủ</a>
    <h2>QUẢN LÝ GIÁO VIÊN</h2>
    
    <div class="form-add">
        <h3>Thêm giáo viên mới</h3>
        <?php if($thongbao != "") echo "<p>$thongbao</p>"; ?>
        <form method="POST" action="">
            <input type="number" name="MaGV" placeholder="Mã GV" required>
            <input type="text" name="HoTen" placeholder="Họ Tên" required>
            <input type="text" name="DienThoai" placeholder="Số điện thoại">
            <select name="MaMon">
                <option value="">-- Chọn môn dạy --</option>
                <?php
                if ($result_mon->num_rows > 0) {
                    while($row = $result_mon->fetch_assoc()) {
                        echo "<option value='".$row['MaMon']."'>".$row['TenMon']."</option>";
                    }
                }
                ?>
            </select>
            <button type="submit" class="btn">Thêm</button>
        </form>
    </div>

    <table>
        <tr>
            <th>Mã GV</th>
            <th>Họ Tên</th>
            <th>Môn Dạy</th>
            <th>Số Điện Thoại</th>
            <th>Hành động</th>
        </tr>
        <?php
        if ($result_gv->num_rows > 0) {
            while($row = $result_gv->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . $row['MaGV'] . "</td>";
                echo "<td>" . $row['HoTen'] . "</td>";
                echo "<td>" . ($row['TenMon'] ? $row['TenMon'] : 'Chưa phân công') . "</td>";
                echo "<td>" . $row['DienThoai'] . "</td>";
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