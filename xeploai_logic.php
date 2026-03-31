<?php
// Hàm này nhận vào một mảng chứa điểm tất cả các môn của 1 học sinh
function xepLoaiHocLuc($diemTB_TatCa, $diemToan, $diemVan, $diemAnh, $diemThapNhat, $tatCaNhanXetDeuDat) {
    
    // Điều kiện 1: 1 trong 3 môn chính >= 8.0
    $dieuKienMonChinh = ($diemToan >= 8.0 || $diemVan >= 8.0 || $diemAnh >= 8.0);
    
    // XÉT HỌC LỰC TỐT (GIỎI)
    if ($diemTB_TatCa >= 8.0 && $dieuKienMonChinh && $diemThapNhat >= 6.5 && $tatCaNhanXetDeuDat == true) {
        return "Tốt";
    }
    
    // Điều kiện xét HỌC LỰC KHÁ (Hạ mốc điểm xuống)
    $dieuKienMonChinh_Kha = ($diemToan >= 6.5 || $diemVan >= 6.5 || $diemAnh >= 6.5);
    if ($diemTB_TatCa >= 6.5 && $dieuKienMonChinh_Kha && $diemThapNhat >= 5.0 && $tatCaNhanXetDeuDat == true) {
        return "Khá";
    }
    
    // XÉT HỌC LỰC ĐẠT (Trung bình cũ)
    $dieuKienMonChinh_Dat = ($diemToan >= 5.0 || $diemVan >= 5.0 || $diemAnh >= 5.0);
    if ($diemTB_TatCa >= 5.0 && $dieuKienMonChinh_Dat && $diemThapNhat >= 3.5 && $tatCaNhanXetDeuDat == true) {
        return "Đạt";
    }

    return "Chưa Đạt";
}
?>