<?php
session_start();
require_once 'connect.php';

// Kiểm tra đăng nhập
if (!isset($_SESSION['TenDangNhap'])) {
    header("Location: login.php");
    exit();
}

// Xử lý Tìm kiếm 
$search = "";
if (isset($_GET['search'])) {
    $search = $_GET['search'];
    // Cập nhật SQL: Kết nối từ hocsinh -> quatrinhhoc -> lop
    $sql = "SELECT hocsinh.*, lop.TenLop 
            FROM hocsinh 
            LEFT JOIN quatrinhhoc ON hocsinh.MaHS = quatrinhhoc.MaHS 
            LEFT JOIN lop ON quatrinhhoc.MaLop = lop.MaLop 
            WHERE hocsinh.HoTen LIKE '%$search%' OR hocsinh.MaHS LIKE '%$search%'
            GROUP BY hocsinh.MaHS"; 
} else {
    $sql = "SELECT hocsinh.*, lop.TenLop 
            FROM hocsinh 
            LEFT JOIN quatrinhhoc ON hocsinh.MaHS = quatrinhhoc.MaHS 
            LEFT JOIN lop ON quatrinhhoc.MaLop = lop.MaLop
            GROUP BY hocsinh.MaHS";
}

// THỰC THI CÂU LỆNH SQL (Dòng em bị thiếu)
$result = $conn->query($sql);

?>

<!DOCTYPE html>
<html>
<head>
    <title>Quản lý học sinh</title>
    <style>
        body { font-family: Arial; padding: 20px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        .btn { padding: 8px 15px; text-decoration: none; background: #4CAF50; color: white; border-radius: 4px; }
        .search-box { margin-bottom: 20px; }
    </style>
</head>
<body>
    <a href="index.php" class="btn" style="background: #555;">&larr; Về Trang chủ</a>
    <h2>QUẢN LÝ HỌC SINH</h2>
    
    <div class="search-box">
        <form method="GET" action="">
            <input type="text" name="search" placeholder="Nhập tên hoặc mã HS..." value="<?php echo $search; ?>" style="padding: 8px; width: 250px;">
            <button type="submit" class="btn" style="background: #008CBA;">Tìm kiếm</button>
            <a href="them_hocsinh.php" class="btn" style="float: right;">+ Thêm học sinh</a>
        </form>
    </div>

<table>
        <tr>
            <th>Mã HS</th>
            <th>Họ Tên</th>
            <th>Ngày Sinh</th>
            <th>Giới Tính</th>
            <th>Địa Chỉ</th>
            <th>Lớp</th>
            <th>Hành động</th>
        </tr>
        <?php
        if ($result && $result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . $row['MaHS'] . "</td>";
                echo "<td>" . $row['HoTen'] . "</td>";
                echo "<td>" . date('d/m/Y', strtotime($row['NgaySinh'])) . "</td>";
                echo "<td>" . $row['GioiTinh'] . "</td>";
                echo "<td>" . $row['DiaChi'] . "</td>";
                echo "<td>" . ($row['TenLop'] ? $row['TenLop'] : 'Chưa xếp lớp') . "</td>";
                
                // ĐÂY LÀ CHỖ CHÈN NÚT IN PHIẾU VÀO CÙNG VỚI SỬA/XÓA
                echo "<td>
                        <a href='phieulienlac.php?MaHS=" . $row['MaHS'] . "' target='_blank' style='color:green; font-weight:bold;'>🖨️ In Phiếu</a> | 
                        <a href='#'>Sửa</a> | 
                        <a href='#' style='color:red;'>Xóa</a>
                      </td>";
                
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='7' style='text-align:center;'>Chưa có dữ liệu học sinh.</td></tr>";
        }
        ?>
    </table>
    </table>
</body>
</html>