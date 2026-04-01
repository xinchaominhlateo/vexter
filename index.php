<?php
session_start();
require_once 'connect.php';

if (!isset($_SESSION['TenDangNhap'])) {
    header("Location: login.php"); 
    exit();
}

// 1. Thống kê dữ liệu tổng quan
$tong_hs = $conn->query("SELECT COUNT(*) AS total FROM hocsinh")->fetch_assoc()['total'];
$tong_gv = $conn->query("SELECT COUNT(*) AS total FROM giaovien")->fetch_assoc()['total'];
$tong_lop = $conn->query("SELECT COUNT(*) AS total FROM lop")->fetch_assoc()['total'];
$tong_mon = $conn->query("SELECT COUNT(*) AS total FROM monhoc")->fetch_assoc()['total'];

// 2. Thống kê dữ liệu cho Biểu đồ (Nam / Nữ)
$tong_nam = $conn->query("SELECT COUNT(*) AS total FROM hocsinh WHERE GioiTinh = 'Nam'")->fetch_assoc()['total'];
$tong_nu = $conn->query("SELECT COUNT(*) AS total FROM hocsinh WHERE GioiTinh = 'Nữ'")->fetch_assoc()['total'];
// Nếu chưa có dữ liệu, gán mặc định bằng 0 để tránh lỗi biểu đồ
$tong_nam = $tong_nam ? $tong_nam : 0;
$tong_nu = $tong_nu ? $tong_nu : 0;
?>

<!DOCTYPE html>
<html>
<head>
    <title>Trang chủ - Quản lý học sinh THPT</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body { font-family: Arial, sans-serif; margin: 0; display: flex; background-color: #f4f6f9; }
        
        /* CSS Sidebar */
        .sidebar { width: 220px; background: #343a40; color: white; height: 100vh; padding-top: 20px; position: fixed;}
        .sidebar h3 { text-align: center; margin-bottom: 20px; letter-spacing: 2px; color: #ffc107;}
        .sidebar a { display: block; color: #c2c7d0; padding: 15px 20px; text-decoration: none; border-bottom: 1px solid #4f5962; transition: 0.3s;}
        .sidebar a:hover { background: #494e53; color: white; border-left: 4px solid #ffc107; }
        
        /* CSS Nội dung chính */
        .content { margin-left: 220px; padding: 30px; width: calc(100% - 220px); }
        .header-title { font-size: 24px; font-weight: bold; margin-bottom: 20px; color: #333; border-bottom: 2px solid #ddd; padding-bottom: 10px;}
        
        /* CSS Cards thống kê */
        .dashboard-grid { display: flex; gap: 20px; margin-bottom: 30px; }
        .card { flex: 1; padding: 20px; border-radius: 8px; color: white; box-shadow: 0 4px 8px rgba(0,0,0,0.1); }
        .card h3 { margin: 0; font-size: 36px; }
        .card p { margin: 10px 0 0 0; font-size: 16px; text-transform: uppercase; font-weight: bold; opacity: 0.9;}
        
        .bg-blue { background: #17a2b8; }
        .bg-green { background: #28a745; }
        .bg-orange { background: #ffc107; color: #333; }
        .bg-red { background: #dc3545; }

        /* CSS Khu vực chức năng & Biểu đồ */
        .bottom-grid { display: flex; gap: 20px; }
        .quick-actions, .chart-container { background: white; padding: 20px; border-radius: 8px; box-shadow: 0 4px 8px rgba(0,0,0,0.05); flex: 1;}
        .quick-actions h4, .chart-container h4 { margin-top: 0; color: #333; border-bottom: 1px solid #eee; padding-bottom: 10px; }
        
        .action-btn { display: block; padding: 12px 20px; background: #007bff; color: white; text-decoration: none; border-radius: 4px; margin-bottom: 10px; transition: 0.2s; text-align: center; font-weight: bold;}
        .action-btn:hover { background: #0056b3; }
        .btn-green { background: #28a745; } .btn-green:hover { background: #218838; }
        .btn-purple { background: #6f42c1; } .btn-purple:hover { background: #5a32a3; }
    </style>
</head>
<body>

<div class="sidebar">
    <h3>VERTEX</h3>
    <a href="index.php">🏠 TRANG CHỦ</a>
    <a href="hocsinh.php">👨‍🎓 HỌC SINH</a>
    <a href="giaovien.php">👨‍🏫 GIÁO VIÊN</a>
    <a href="lop.php">🏫 LỚP HỌC</a>
    <a href="monhoc.php">📚 MÔN HỌC</a>
    <a href="diem.php">📝 ĐIỂM SỐ</a>
    <a href="hanhkiem.php">⭐ HẠNH KIỂM</a>
    <a href="hocphi.php">💰 HỌC PHÍ</a>
    <a href="taikhoan.php">⚙️ TÀI KHOẢN</a>
    <a href="logout.php">🚪 ĐĂNG XUẤT</a>
</div>

<div class="content">
    <div class="header-title">HỆ THỐNG QUẢN LÝ HỌC SINH THPT VERTEX</div>
    <p style="font-size: 18px;">Xin chào quản trị viên: <strong><?php echo $_SESSION['TenDangNhap']; ?></strong> !</p>

    <div class="dashboard-grid">
        <div class="card bg-blue">
            <h3><?php echo $tong_hs; ?></h3>
            <p>Tổng Học Sinh</p>
        </div>
        <div class="card bg-green">
            <h3><?php echo $tong_gv; ?></h3>
            <p>Tổng Giáo Viên</p>
        </div>
        <div class="card bg-orange">
            <h3><?php echo $tong_lop; ?></h3>
            <p>Số Lớp Học</p>
        </div>
        <div class="card bg-red">
            <h3><?php echo $tong_mon; ?></h3>
            <p>Môn Giảng Dạy</p>
        </div>
    </div>

    <div class="bottom-grid">
        <div class="chart-container" style="max-width: 400px;">
            <h4>📊 Tỉ lệ Giới tính Học sinh</h4>
            <canvas id="genderChart"></canvas>
        </div>

        <div class="quick-actions">
            <h4>⚡ Thao Tác Nhanh</h4>
            <a href="them_hocsinh.php" class="action-btn">+ Thêm Học Sinh Mới</a>
            <a href="diem.php" class="action-btn btn-green">📝 Nhập Điểm Số</a>
            <a href="hocphi.php" class="action-btn btn-purple">💰 Thu Học Phí</a>
            <a href="hocsinh.php" class="action-btn" style="background:#6c757d;">🖨️ In Phiếu Liên Lạc</a>
        </div>
    </div>
</div>

<script>
    const ctx = document.getElementById('genderChart');
    new Chart(ctx, {
        type: 'pie', // Loại biểu đồ tròn
        data: {
            labels: ['Nam', 'Nữ'],
            datasets: [{
                data: [<?php echo $tong_nam; ?>, <?php echo $tong_nu; ?>], // Dữ liệu lấy từ PHP
                backgroundColor: [
                    '#36a2eb', // Màu xanh cho Nam
                    '#ff6384'  // Màu hồng cho Nữ
                ],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { position: 'bottom' }
            }
        }
    });
</script>

</body>
</html>