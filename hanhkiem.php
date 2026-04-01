<?php
session_start();
require_once 'connect.php';

if (!isset($_SESSION['TenDangNhap'])) {
    header("Location: login.php");
    exit();
}

$thongbao = "";

// Xử lý lưu hạnh kiểm
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $mahs = $_POST['MaHS'];
    $hocky = $_POST['HocKy'];
    $xeploai = $_POST['XepLoai'];
    $nhanxet = $_POST['NhanXet'];

    // Tự động tạo Mã Hạnh Kiểm mới 
// Bỏ MaHK khỏi câu INSERT vì CSDL đã tự động tăng
    $sql_insert = "INSERT INTO hanhkiem (MaHS, HocKy, XepLoai, NhanXet) 
                   VALUES ($mahs, $hocky, '$xeploai', '$nhanxet')";    
    if ($conn->query($sql_insert) === TRUE) {
        $thongbao = "<span style='color:green;'>Lưu đánh giá hạnh kiểm thành công!</span>";
    } else {
        $thongbao = "<span style='color:red;'>Lỗi: " . $conn->error . "</span>";
    }
}

// Lấy danh sách học sinh cho Dropdown
$sql_hs = "SELECT MaHS, HoTen FROM hocsinh";
$result_hs = $conn->query($sql_hs);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Quản lý Hạnh kiểm</title>
    <style>
        body { font-family: Arial; padding: 20px; }
        .form-container { max-width: 500px; background: #f9f9f9; padding: 20px; border-radius: 8px; margin: auto; border: 1px solid #ccc;}
        .form-group { margin-bottom: 15px; display: flex; align-items: center;}
        .form-group label { width: 120px; font-weight: bold;}
        .form-group input, .form-group select, .form-group textarea { flex: 1; padding: 8px; box-sizing: border-box; }
        .btn-luu { background: #d3d3d3; color: black; padding: 10px 30px; border: 1px solid #999; cursor: pointer; float: right; margin-top: 10px;}
        .header-title { text-align: center; font-size: 20px; font-weight: bold; margin-bottom: 20px; text-transform: uppercase;}
    </style>
</head>
<body>
    <a href="index.php" style="padding: 8px 15px; background: #555; color: white; text-decoration: none; border-radius: 4px;">&larr; Về Trang chủ</a>
    
    <div class="form-container" style="margin-top: 30px;">
        <div class="header-title">QUẢN LÝ HẠNH KIỂM</div>
        <div style="text-align: center; margin-bottom: 15px;">
            <?php if($thongbao != "") echo $thongbao; ?>
        </div>

        <form method="POST" action="">
            <div class="form-group">
                <label>Học sinh:</label>
                <select name="MaHS" required>
                    <option value="">-- Chọn học sinh --</option>
                    <?php
                    if ($result_hs->num_rows > 0) {
                        while($row_hs = $result_hs->fetch_assoc()) {
                            echo "<option value='".$row_hs['MaHS']."'>".$row_hs['MaHS']." - ".$row_hs['HoTen']."</option>";
                        }
                    }
                    ?>
                </select>
            </div>

            <div class="form-group">
                <label>Học kỳ:</label>
                <select name="HocKy" required>
                    <option value="1">Học kỳ 1</option>
                    <option value="2">Học kỳ 2</option>
                </select>
            </div>

            <div class="form-group">
                <label>Xếp loại:</label>
                <select name="XepLoai" required>
                    <option value="Tốt">Tốt</option>
                    <option value="Khá">Khá</option>
                    <option value="Trung bình">Trung bình</option>
                    <option value="Yếu">Yếu</option>
                </select>
            </div>

            <div class="form-group">
                <label>Nhận xét:</label>
                <textarea name="NhanXet" rows="3" maxlength="200" placeholder="Giáo viên ghi nhận xét..."></textarea>
            </div>

            <div style="overflow: auto;">
                <button type="submit" class="btn-luu">Lưu</button>
            </div>
        </form>
    </div>
</body>
</html>