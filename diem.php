<?php
session_start();
require_once 'connect.php';

// Kiểm tra đăng nhập
if (!isset($_SESSION['TenDangNhap'])) {
    header("Location: login.php");
    exit();
}

$thongbao = "";

// XỬ LÝ LƯU ĐIỂM VÀ TÍNH TOÁN
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $mahs = $_POST['MaHS'];
    $mamon = $_POST['MaMon'];
    $hocky = $_POST['HocKy'];
    
    // Lấy điểm từ form
    $diemkttx = $_POST['DiemKTTX']; 
    $diemgiuaky = $_POST['DiemGiuaKy'];
    $diemcuoiky = $_POST['DiemCuoiKy'];

    // Tính điểm trung bình môn
    // Công thức: (KTTX + Giữa kỳ * 2 + Cuối kỳ * 3) / 6
    $diemtb = ($diemkttx + ($diemgiuaky * 2) + ($diemcuoiky * 3)) / 6;
    $diemtb = round($diemtb, 1); // Làm tròn 1 chữ số thập phân

    // Khắc phục lỗi thiếu Auto_Increment của MaDiem trong CSDL
$ketquanhanxet = empty($_POST['KetQuaNhanXet']) ? "NULL" : "'" . $_POST['KetQuaNhanXet'] . "'";    $row_max = $result_max->fetch_assoc();
    // Lệnh Insert vào bảng diem
   $ketquanhanxet = empty($_POST['KetQuaNhanXet']) ? "NULL" : "'" . $_POST['KetQuaNhanXet'] . "'";

$sql_insert = "INSERT INTO diem (MaHS, MaMon, HocKy, DiemKTTX, DiemGiuaKy, DiemCuoiKy, DiemTB, KetQuaNhanXet) 
                   VALUES ($mahs, $mamon, $hocky, $diemkttx, $diemgiuaky, $diemcuoiky, $diemtb, $ketquanhanxet)";    if ($conn->query($sql_insert) === TRUE) {
        $thongbao = "<span style='color:green; font-weight:bold;'>Lưu điểm thành công! Điểm trung bình là: $diemtb</span>";
    } else {
        $thongbao = "<span style='color:red;'>Lỗi khi lưu: " . $conn->error . "</span>";
    }
}

// Lấy danh sách Học sinh và Môn học để hiển thị dạng Dropdown (Tránh nhập sai mã)
$sql_hs = "SELECT MaHS, HoTen FROM hocsinh";
$result_hs = $conn->query($sql_hs);

$sql_mon = "SELECT MaMon, TenMon, Khoi FROM monhoc ORDER BY Khoi ASC, TenMon ASC";
$result_mon = $conn->query($sql_mon);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Quản lý Điểm</title>
    <style>
        body { font-family: Arial; padding: 20px; }
        .form-container { max-width: 500px; background: #f9f9f9; padding: 20px; border-radius: 8px; margin: auto; border: 1px solid #ccc;}
        .form-group { margin-bottom: 15px; display: flex; align-items: center;}
        .form-group label { width: 150px; font-weight: bold;}
        .form-group input, .form-group select { flex: 1; padding: 8px; box-sizing: border-box; }
        .btn-luu { background: #d3d3d3; color: black; padding: 10px 30px; border: 1px solid #999; cursor: pointer; float: right; margin-top: 10px;}
        .header-title { text-align: center; font-size: 20px; font-weight: bold; margin-bottom: 20px; text-transform: uppercase;}
    </style>
</head>
<body>
    <a href="index.php" style="padding: 8px 15px; background: #555; color: white; text-decoration: none; border-radius: 4px;">&larr; Về Trang chủ</a>
    
    <div class="form-container" style="margin-top: 30px;">
        <div class="header-title">QUẢN LÝ ĐIỂM</div>
        
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
                <label>Môn học:</label>
                <select name="MaMon" required>
                    <option value="">-- Chọn môn học --</option>
                    <?php
                    if ($result_mon && $result_mon->num_rows > 0) {
                        $current_khoi = "";
                        while($row_mon = $result_mon->fetch_assoc()) {
                            // Tạo tên Khối để gom nhóm
                            $khoi = $row_mon['Khoi'] ? "Khối " . $row_mon['Khoi'] : "Khối Chung";
                            
                            // Nếu chuyển sang khối mới thì tạo nhóm optgroup mới
                            if ($khoi != $current_khoi) {
                                if ($current_khoi != "") echo "</optgroup>";
                                echo "<optgroup label='$khoi'>";
                                $current_khoi = $khoi;
                            }
                            echo "<option value='".$row_mon['MaMon']."'>".$row_mon['TenMon']."</option>";
                        }
                        if ($current_khoi != "") echo "</optgroup>";
                    } else {
                        echo "<option value=''>Chưa có môn học (Hãy thêm trong CSDL)</option>";
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
                <label>Điểm KTTX:</label>
                <input type="number" step="0.1" min="0" max="10" name="DiemKTTX" style="width: 100px; flex: none;" required>
            </div>
            
            <div class="form-group">
                <label>Điểm giữa kỳ:</label>
                <input type="number" step="0.1" min="0" max="10" name="DiemGiuaKy" style="width: 100px; flex: none;" required>
            </div>
            
            <div class="form-group">
                <label>Điểm cuối kỳ:</label>
                <input type="number" step="0.1" min="0" max="10" name="DiemCuoiKy" style="width: 100px; flex: none;" required>
            </div>
            <div class="form-group">
    <label>Nhận xét (Nếu có):</label>
    <select name="KetQuaNhanXet">
        <option value="">-- Dành cho môn nhận xét --</option>
        <option value="Đạt">Đạt</option>
        <option value="Chưa Đạt">Chưa Đạt</option>
    </select>
</div>

            <div class="form-group" style="margin-top: 20px;">
                <input type="checkbox" checked disabled style="width: auto; flex: none; margin-right: 10px;">
                <label style="width: auto;">Tính điểm trung bình (Tự động)</label>
            </div>

            <div style="overflow: auto;">
                <button type="submit" class="btn-luu">Lưu</button>
            </div>
        </form>
    </div>
</body>
</html>