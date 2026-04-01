<?php
session_start();
require_once 'connect.php';

if (!isset($_SESSION['TenDangNhap'])) {
    header("Location: login.php");
    exit();
}

$thongbao = "";

// Xử lý lưu Học phí
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $mahs = $_POST['MaHS'];
    $hocky = $_POST['HocKy'];
    $sotien = $_POST['SoTien'];
    $trangthai = $_POST['TrangThai'];

    $sql_insert = "INSERT INTO hocphi (MaHS, HocKy, SoTien, TrangThai) 
                   VALUES ($mahs, $hocky, $sotien, '$trangthai')";
    if ($conn->query($sql_insert) === TRUE) {
        $thongbao = "<span style='color:green;'>Lưu thông tin học phí thành công!</span>";
    } else {
        $thongbao = "<span style='color:red;'>Lỗi: " . $conn->error . "</span>";
    }
}

// Lấy danh sách học phí kèm tên học sinh
$sql_hp = "SELECT hp.*, hs.HoTen FROM hocphi hp JOIN hocsinh hs ON hp.MaHS = hs.MaHS ORDER BY hp.MaHP DESC";
$result_hp = $conn->query($sql_hp);

// Lấy danh sách HS cho Dropdown
$result_hs = $conn->query("SELECT MaHS, HoTen FROM hocsinh");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Quản lý Học Phí</title>
    <style>
        body { font-family: Arial; padding: 20px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        .btn { padding: 8px 15px; background: #4CAF50; color: white; border-radius: 4px; border: none; cursor: pointer; text-decoration: none;}
        .form-add { background: #f9f9f9; padding: 15px; border: 1px solid #ddd; margin-bottom: 20px; max-width: 600px;}
        .trang-thai-da-dong { color: green; font-weight: bold; }
        .trang-thai-chua-dong { color: red; font-weight: bold; }
    </style>
</head>
<body>
    <a href="index.php" class="btn" style="background: #555;">&larr; Về Trang chủ</a>
    <h2>QUẢN LÝ HỌC PHÍ</h2>
    
    <div class="form-add">
        <h3>Thu học phí</h3>
        <?php if($thongbao != "") echo "<p>$thongbao</p>"; ?>
        <form method="POST" action="">
            <select name="MaHS" required style="width: 100%; padding: 8px; margin-bottom: 10px;">
                <option value="">-- Chọn học sinh --</option>
                <?php
                if ($result_hs->num_rows > 0) {
                    while($row = $result_hs->fetch_assoc()) {
                        echo "<option value='".$row['MaHS']."'>".$row['MaHS']." - ".$row['HoTen']."</option>";
                    }
                }
                ?>
            </select>
            <select name="HocKy" required style="width: 100%; padding: 8px; margin-bottom: 10px;">
                <option value="1">Học kỳ 1</option>
                <option value="2">Học kỳ 2</option>
            </select>
            <input type="number" name="SoTien" placeholder="Số tiền (VD: 2000000)" required style="width: 100%; padding: 8px; margin-bottom: 10px; box-sizing: border-box;">
            <select name="TrangThai" required style="width: 100%; padding: 8px; margin-bottom: 15px;">
                <option value="Đã đóng">Đã đóng</option>
                <option value="Chưa đóng">Chưa đóng</option>
            </select>
            <button type="submit" class="btn" style="width: 100%;">Lưu Biên Lai</button>
        </form>
    </div>

    <h3>Danh sách thu tiền</h3>
    <table>
        <tr>
            <th>Mã Biên Lai</th>
            <th>Mã HS</th>
            <th>Họ Tên</th>
            <th>Học Kỳ</th>
            <th>Số Tiền (VNĐ)</th>
            <th>Trạng Thái</th>
        </tr>
        <?php
        if ($result_hp->num_rows > 0) {
            while($row = $result_hp->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . $row['MaHP'] . "</td>";
                echo "<td>" . $row['MaHS'] . "</td>";
                echo "<td>" . $row['HoTen'] . "</td>";
                echo "<td>" . $row['HocKy'] . "</td>";
                echo "<td>" . number_format($row['SoTien']) . "</td>";
                
                $class_tt = ($row['TrangThai'] == 'Đã đóng') ? 'trang-thai-da-dong' : 'trang-thai-chua-dong';
                echo "<td class='$class_tt'>" . $row['TrangThai'] . "</td>";
                
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='6' style='text-align:center;'>Chưa có dữ liệu học phí.</td></tr>";
        }
        ?>
    </table>
</body>
</html>