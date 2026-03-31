<?php
session_start();
require_once 'connect.php';

if (!isset($_SESSION['TenDangNhap'])) {
    header("Location: login.php");
    exit();
}

$thongbao = "";

// Xử lý khi người dùng bấm nút Lưu
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $mahs = $_POST['MaHS'];
    $hoten = $_POST['HoTen'];
    $ngaysinh = $_POST['NgaySinh'];
    $gioitinh = $_POST['GioiTinh'];
    $diachi = $_POST['DiaChi'];
    $dienthoai = $_POST['DienThoai'];
    $email = $_POST['Email'];
    $malop = empty($_POST['MaLop']) ? "NULL" : $_POST['MaLop']; // Nếu không chọn lớp thì để NULL

    // Lệnh thêm vào CSDL bảng hocsinh 
    $sql_insert_hs = "INSERT INTO hocsinh (MaHS, HoTen, NgaySinh, GioiTinh, DiaChi, DienThoai, Email) 
                   VALUES ('$mahs', '$hoten', '$ngaysinh', '$gioitinh', '$diachi', '$dienthoai', '$email')";
    
    if ($conn->query($sql_insert_hs) === TRUE) {
        // Nếu chọn lớp, thêm tiếp vào bảng quatrinhhoc
        if ($malop != "NULL") {
            // Tạm định sẵn HocKy 1 và Năm Học hiện tại, em có thể làm form nhập thêm sau
            $sql_insert_qt = "INSERT INTO quatrinhhoc (MaHS, MaLop, HocKy, NamHoc) VALUES ('$mahs', $malop, 1, '2025-2026')";
            $conn->query($sql_insert_qt);
        }
        
        // Thêm thành công thì quay về trang danh sách
        header("Location: hocsinh.php");
        exit();
    } else {
        $thongbao = "Lỗi khi thêm: " . $conn->error;
    }
}

// Lấy danh sách lớp để đưa vào Dropdown chọn Lớp 
$sql_lop = "SELECT * FROM lop";
$result_lop = $conn->query($sql_lop);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Thêm Học Sinh</title>
    <style>
        body { font-family: Arial; padding: 20px; }
        .form-container { max-width: 500px; background: #f9f9f9; padding: 20px; border-radius: 8px; margin: auto; }
        .form-group { margin-bottom: 15px; }
        .form-group label { display: block; margin-bottom: 5px; font-weight: bold;}
        .form-group input, .form-group select { width: 100%; padding: 8px; box-sizing: border-box; }
        .btn-luu { background: #4CAF50; color: white; padding: 10px 15px; border: none; cursor: pointer; }
        .btn-huy { background: #ccc; color: black; padding: 10px 15px; text-decoration: none; display: inline-block; margin-right: 10px;}
    </style>
</head>
<body>
    <div class="form-container">
        <h2 style="text-align: center;">THÊM HỌC SINH</h2>
        <?php if($thongbao != "") echo "<p style='color:red;'>$thongbao</p>"; ?>
        
        <form method="POST" action="">
            <div class="form-group">
                <label>Mã học sinh (*):</label>
                <input type="number" name="MaHS" required>
            </div>
            <div class="form-group">
                <label>Họ tên (*):</label>
                <input type="text" name="HoTen" maxlength="100" required>
            </div>
            <div class="form-group">
                <label>Ngày sinh:</label>
                <input type="date" name="NgaySinh" required>
            </div>
            <div class="form-group">
                <label>Giới tính:</label>
                <select name="GioiTinh">
                    <option value="Nam">Nam</option>
                    <option value="Nữ">Nữ</option>
                </select>
            </div>
            <div class="form-group">
                <label>Địa chỉ:</label>
                <input type="text" name="DiaChi" maxlength="200">
            </div>
            <div class="form-group">
                <label>Số điện thoại:</label>
                <input type="text" name="DienThoai" maxlength="15">
            </div>
            <div class="form-group">
                <label>Email:</label>
                <input type="email" name="Email" maxlength="100">
            </div>
            <div class="form-group">
                <label>Lớp:</label>
                <select name="MaLop">
                    <option value="">-- Chọn lớp --</option>
                    <?php
                    if ($result_lop && $result_lop->num_rows > 0) {
                        while($row_lop = $result_lop->fetch_assoc()) {
                            echo "<option value='".$row_lop['MaLop']."'>".$row_lop['TenLop']."</option>";
                        }
                    }
                    ?>
                </select>
            </div>
            
            <div style="text-align: center; margin-top: 20px;">
                <a href="hocsinh.php" class="btn-huy">Hủy</a>
                <button type="submit" class="btn-luu">Lưu</button>
            </div>
        </form>
    </div>
</body>
</html>