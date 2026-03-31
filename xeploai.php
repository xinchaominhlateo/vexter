<?php
// Gọi kết nối Database
require_once 'connect.php';


$mahs = 4901009; 

// 1. Truy vấn lấy toàn bộ điểm và thông tin môn học của HS này
$sql = "SELECT d.DiemTB, d.KetQuaNhanXet, m.TenMon, m.HinhThucDanhGia 
        FROM diem d 
        JOIN monhoc m ON d.MaMon = m.MaMon 
        WHERE d.MaHS = $mahs";
$result = $conn->query($sql);

$danhSachDiem = [];
$tongDiem = 0;
$soMonChoDiem = 0;

// 2. Chuyển dữ liệu SQL thành mảng và tính tổng điểm
if ($result && $result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $danhSachDiem[] = $row;
        // Chỉ cộng điểm những môn có chấm điểm số
        if($row['HinhThucDanhGia'] == 'ChoDiem') {
            $tongDiem += $row['DiemTB'];
            $soMonChoDiem++;
        }
    }
}

// 3. Tính Điểm trung bình chung
$dtb_tat_ca = ($soMonChoDiem > 0) ? round($tongDiem / $soMonChoDiem, 1) : 0;

// 4. Khởi tạo các biến để kiểm tra điều kiện
$diem_toan = 0; $diem_van = 0; $diem_anh = 0;
$diem_thap_nhat = 10;
$check_nhan_xet = true; 

// 5. Chạy vòng lặp phân tích điểm từng môn
foreach ($danhSachDiem as $mon) {
    if ($mon['HinhThucDanhGia'] == 'ChoDiem') {
        if ($mon['TenMon'] == 'Toán') $diem_toan = $mon['DiemTB'];
        if ($mon['TenMon'] == 'Văn') $diem_van = $mon['DiemTB'];
        if ($mon['TenMon'] == 'Ngoại ngữ') $diem_anh = $mon['DiemTB']; 
        
        // Tìm môn có điểm thấp nhất
        if ($mon['DiemTB'] < $diem_thap_nhat) {
            $diem_thap_nhat = $mon['DiemTB'];
        }
    } else {
        // Kiểm tra môn Nhận xét
        if ($mon['KetQuaNhanXet'] != 'Đạt') {
            $check_nhan_xet = false;
        }
    }
}

// 6. THUẬT TOÁN XẾP LOẠI HỌC LỰC MỚI (TỐT, KHÁ, ĐẠT)
$hoc_luc = "Chưa Đạt"; 

if ($dtb_tat_ca >= 8.0 && ($diem_toan >= 8.0 || $diem_van >= 8.0 || $diem_anh >= 8.0) && $diem_thap_nhat >= 6.5 && $check_nhan_xet == true) {
    $hoc_luc = "Tốt";
} 
elseif ($dtb_tat_ca >= 6.5 && ($diem_toan >= 6.5 || $diem_van >= 6.5 || $diem_anh >= 6.5) && $diem_thap_nhat >= 5.0 && $check_nhan_xet == true) {
    $hoc_luc = "Khá";
} 
elseif ($dtb_tat_ca >= 5.0 && ($diem_toan >= 5.0 || $diem_van >= 5.0 || $diem_anh >= 5.0) && $diem_thap_nhat >= 3.5 && $check_nhan_xet == true) {
    $hoc_luc = "Đạt";
}

// 7. In kết quả ra 
echo "<h2>KẾT QUẢ XẾP LOẠI CỦA HỌC SINH MÃ: $mahs</h2>";
echo "<p>- Điểm trung bình tất cả các môn: <strong>$dtb_tat_ca</strong></p>";
echo "<p>- Điểm số thấp nhất trong các môn: <strong>$diem_thap_nhat</strong></p>";
echo "<p>- Môn nhận xét đều Đạt: <strong>" . ($check_nhan_xet ? "Có" : "Không") . "</strong></p>";
echo "<h3 style='color: green;'>=> Kết luận Học Lực: $hoc_luc</h3>";
?>