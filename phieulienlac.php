<?php
session_start();
require_once 'connect.php';

// Kiểm tra xem có truyền Mã HS vào không
if (!isset($_GET['MaHS'])) {
    echo "<h2 style='text-align:center; color:red;'>Lỗi: Vui lòng chọn một học sinh để in phiếu!</h2>";
    echo "<div style='text-align:center;'><a href='hocsinh.php'>Quay lại danh sách</a></div>";
    exit();
}

$mahs = $_GET['MaHS'];

// 1. LẤY THÔNG TIN HỌC SINH VÀ LỚP (Câu lệnh "bất tử" chống lỗi database)
$sql_hs = "SELECT hs.*, 
            (SELECT l.TenLop FROM quatrinhhoc qt JOIN lop l ON qt.MaLop = l.MaLop WHERE qt.MaHS = hs.MaHS LIMIT 1) AS TenLop
           FROM hocsinh hs 
           WHERE hs.MaHS = '$mahs'";
$result_hs = $conn->query($sql_hs);
$info_hs = $result_hs->fetch_assoc();

if (!$info_hs) {
    die("<h3 style='color:red; text-align:center; margin-top:50px;'>Không tìm thấy dữ liệu của học sinh này trong hệ thống!</h3>");
}

// 2. LẤY ĐIỂM SỐ CÁC MÔN
$sql_diem = "SELECT d.*, m.TenMon, m.HinhThucDanhGia 
             FROM diem d 
             JOIN monhoc m ON d.MaMon = m.MaMon 
             WHERE d.MaHS = '$mahs'";
$result_diem = $conn->query($sql_diem);

$danhSachDiem = [];
$tongDiem = 0; $soMonChoDiem = 0;
$diem_toan = 0; $diem_van = 0; $diem_anh = 0;
$diem_thap_nhat = 10;
$check_nhan_xet = true; 

if ($result_diem && $result_diem->num_rows > 0) {
    while($row = $result_diem->fetch_assoc()) {
        $danhSachDiem[] = $row;
        if($row['HinhThucDanhGia'] == 'ChoDiem') {
            $tongDiem += $row['DiemTB'];
            $soMonChoDiem++;
            
            if ($row['TenMon'] == 'Toán') $diem_toan = $row['DiemTB'];
            if ($row['TenMon'] == 'Văn') $diem_van = $row['DiemTB'];
            if ($row['TenMon'] == 'Ngoại ngữ') $diem_anh = $row['DiemTB']; 
            
            if ($row['DiemTB'] < $diem_thap_nhat) $diem_thap_nhat = $row['DiemTB'];
        } else {
            if ($row['KetQuaNhanXet'] != 'Đạt') $check_nhan_xet = false;
        }
    }
}

// Tính ĐTB
$dtb_tat_ca = ($soMonChoDiem > 0) ? round($tongDiem / $soMonChoDiem, 1) : 0;

// Xếp loại học lực
$hoc_luc = "Chưa Đạt"; 
if ($soMonChoDiem > 0) {
    if ($dtb_tat_ca >= 8.0 && ($diem_toan >= 8.0 || $diem_van >= 8.0 || $diem_anh >= 8.0) && $diem_thap_nhat >= 6.5 && $check_nhan_xet) {
        $hoc_luc = "Tốt";
    } elseif ($dtb_tat_ca >= 6.5 && ($diem_toan >= 6.5 || $diem_van >= 6.5 || $diem_anh >= 6.5) && $diem_thap_nhat >= 5.0 && $check_nhan_xet) {
        $hoc_luc = "Khá";
    } elseif ($dtb_tat_ca >= 5.0 && ($diem_toan >= 5.0 || $diem_van >= 5.0 || $diem_anh >= 5.0) && $diem_thap_nhat >= 3.5 && $check_nhan_xet) {
        $hoc_luc = "Đạt";
    }
} else {
    $hoc_luc = "Chưa có điểm";
}

// 3. LẤY HẠNH KIỂM
$sql_hk = "SELECT XepLoai FROM hanhkiem WHERE MaHS = '$mahs' ORDER BY MaHK DESC LIMIT 1";
$result_hk = $conn->query($sql_hk);
$hanh_kiem = ($result_hk && $result_hk->num_rows > 0) ? $result_hk->fetch_assoc()['XepLoai'] : "Chưa đánh giá";

?>

<!DOCTYPE html>
<html>
<head>
    <title>Phiếu Liên Lạc - <?php echo $info_hs['HoTen']; ?></title>
    <style>
        body { font-family: 'Times New Roman', serif; background: #e9ecef; }
        .a4-page { width: 21cm; min-height: 29.7cm; padding: 2cm; margin: 1cm auto; background: white; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
        .header { display: flex; justify-content: space-between; text-align: center; margin-bottom: 20px;}
        .title { text-align: center; font-size: 24px; font-weight: bold; margin: 20px 0; }
        .info-box { margin-bottom: 20px; font-size: 16px; line-height: 1.5; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        th, td { border: 1px solid #000; padding: 8px; text-align: center; }
        th { background-color: #f2f2f2; }
        .text-left { text-align: left; }
        .summary-box { border: 2px solid #000; padding: 15px; margin-bottom: 30px; }
        .signature { display: flex; justify-content: space-between; text-align: center; margin-top: 40px; }
        
        .btn-print-container { text-align: center; margin: 20px; }
        .btn-print { padding: 10px 20px; font-size: 18px; background: #28a745; color: white; border: none; cursor: pointer; border-radius: 5px; }
        @media print {
            body { background: white; margin: 0; }
            .a4-page { margin: 0; box-shadow: none; border: none; width: 100%; padding: 1cm; }
            .btn-print-container { display: none; }
        }
    </style>
</head>
<body>

    <div class="btn-print-container">
        <button class="btn-print" onclick="window.print()">🖨️ In Phiếu Liên Lạc Này</button>
        <br><br>
        <a href="hocsinh.php" style="text-decoration:none; color: #555;">&larr; Trở về danh sách</a>
    </div>

    <div class="a4-page">
        <div class="header">
            <div>
                <strong>SỞ GIÁO DỤC VÀ ĐÀO TẠO</strong><br>
                <strong>TRƯỜNG THPT VERTEX</strong><br>
                <hr style="width: 50%; border: 1px solid black;">
            </div>
            <div>
                <strong>CỘNG HÒA XÃ HỘI CHỦ NGHĨA VIỆT NAM</strong><br>
                <strong>Độc lập - Tự do - Hạnh phúc</strong><br>
                <hr style="width: 50%; border: 1px solid black;">
            </div>
        </div>

        <div class="title">PHIẾU LIÊN LẠC HỌC SINH<br><span style="font-size: 18px; font-weight: normal;">NĂM HỌC 2025 - 2026</span></div>

        <div class="info-box">
            Họ và tên học sinh: <strong><?php echo $info_hs['HoTen']; ?></strong> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Mã HS: <strong><?php echo $info_hs['MaHS']; ?></strong><br>
            Ngày sinh: <?php echo date('d/m/Y', strtotime($info_hs['NgaySinh'])); ?> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Giới tính: <?php echo $info_hs['GioiTinh']; ?><br>
            Lớp: <strong><?php echo $info_hs['TenLop'] ? $info_hs['TenLop'] : 'Chưa xếp lớp'; ?></strong>
        </div>

        <h3>I. KẾT QUẢ HỌC TẬP</h3>
        <table>
            <tr>
                <th>STT</th>
                <th class="text-left">Môn Học</th>
                <th>Hình thức</th>
                <th>Điểm KTTX</th>
                <th>Điểm Giữa Kỳ</th>
                <th>Điểm Cuối Kỳ</th>
                <th>ĐTB / Nhận Xét</th>
            </tr>
            <?php
            if (count($danhSachDiem) > 0) {
                $stt = 1;
                foreach($danhSachDiem as $mon) {
                    echo "<tr>";
                    echo "<td>" . $stt++ . "</td>";
                    echo "<td class='text-left'>" . $mon['TenMon'] . "</td>";
                    echo "<td>" . ($mon['HinhThucDanhGia'] == 'ChoDiem' ? 'Cho điểm' : 'Nhận xét') . "</td>";
                    echo "<td>" . $mon['DiemKTTX'] . "</td>";
                    echo "<td>" . $mon['DiemGiuaKy'] . "</td>";
                    echo "<td>" . $mon['DiemCuoiKy'] . "</td>";
                    
                    if ($mon['HinhThucDanhGia'] == 'ChoDiem') {
                        echo "<td><strong>" . $mon['DiemTB'] . "</strong></td>";
                    } else {
                        echo "<td><strong>" . $mon['KetQuaNhanXet'] . "</strong></td>";
                    }
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='7'>Chưa có điểm số nào được nhập.</td></tr>";
            }
            ?>
        </table>

        <div class="summary-box">
            <h3>II. KẾT QUẢ TỔNG HỢP</h3>
            <p>- Điểm trung bình các môn: <strong><?php echo $dtb_tat_ca; ?></strong></p>
            <p>- Xếp loại Học Lực: <strong style="color: blue; font-size: 18px;"><?php echo $hoc_luc; ?></strong></p>
            <p>- Xếp loại Hạnh Kiểm: <strong style="color: blue; font-size: 18px;"><?php echo $hanh_kiem; ?></strong></p>
        </div>

        <div class="signature">
            <div>
                <strong>CHỮ KÝ PHỤ HUYNH</strong><br>
                <i>(Ký và ghi rõ họ tên)</i>
            </div>
            <div>
                <strong>GIÁO VIÊN CHỦ NHIỆM</strong><br>
                <i>(Ký và ghi rõ họ tên)</i>
            </div>
        </div>
    </div>

</body>
</html>