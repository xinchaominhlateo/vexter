-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Máy chủ: 127.0.0.1
-- Thời gian đã tạo: Th4 01, 2026 lúc 05:44 AM
-- Phiên bản máy phục vụ: 10.4.32-MariaDB
-- Phiên bản PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Cơ sở dữ liệu: `quanlyhocsinhc3_vertex`
--

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `diem`
--

CREATE TABLE `diem` (
  `MaDiem` int(11) NOT NULL,
  `MaHS` int(11) DEFAULT NULL,
  `MaMon` int(11) DEFAULT NULL,
  `HocKy` int(11) DEFAULT NULL,
  `DiemMieng` float DEFAULT NULL,
  `DiemKTTX` float DEFAULT NULL,
  `DiemGiuaKy` float DEFAULT NULL,
  `DiemCuoiKy` float DEFAULT NULL,
  `DiemTB` float DEFAULT NULL,
  `KetQuaNhanXet` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `diem`
--

INSERT INTO `diem` (`MaDiem`, `MaHS`, `MaMon`, `HocKy`, `DiemMieng`, `DiemKTTX`, `DiemGiuaKy`, `DiemCuoiKy`, `DiemTB`, `KetQuaNhanXet`) VALUES
(1, 4901009, 1, 1, NULL, 10, 10, 10, 10, NULL);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `giaovien`
--

CREATE TABLE `giaovien` (
  `MaGV` int(11) NOT NULL,
  `MaTK` int(11) DEFAULT NULL,
  `HoTen` varchar(100) DEFAULT NULL,
  `NgaySinh` date DEFAULT NULL,
  `GioiTinh` varchar(10) DEFAULT NULL,
  `DienThoai` varchar(15) DEFAULT NULL,
  `Email` varchar(100) DEFAULT NULL,
  `MaMon` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `giaovien`
--

INSERT INTO `giaovien` (`MaGV`, `MaTK`, `HoTen`, `NgaySinh`, `GioiTinh`, `DienThoai`, `Email`, `MaMon`) VALUES
(4900011, NULL, 'Nguyễn Văn B', NULL, NULL, '11111111', NULL, NULL);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `hanhkiem`
--

CREATE TABLE `hanhkiem` (
  `MaHK` int(11) NOT NULL,
  `MaHS` int(11) DEFAULT NULL,
  `HocKy` int(11) DEFAULT NULL,
  `XepLoai` varchar(20) DEFAULT NULL,
  `NhanXet` varchar(200) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `hocphi`
--

CREATE TABLE `hocphi` (
  `MaHP` int(11) NOT NULL,
  `MaHS` int(11) NOT NULL,
  `HocKy` int(11) NOT NULL,
  `SoTien` int(11) NOT NULL DEFAULT 0,
  `TrangThai` varchar(50) DEFAULT 'Chưa đóng'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `hocphi`
--

INSERT INTO `hocphi` (`MaHP`, `MaHS`, `HocKy`, `SoTien`, `TrangThai`) VALUES
(1, 4901009, 1, 20000, 'Đã đóng');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `hocsinh`
--

CREATE TABLE `hocsinh` (
  `MaHS` int(11) NOT NULL,
  `HoTen` varchar(100) DEFAULT NULL,
  `NgaySinh` date DEFAULT NULL,
  `GioiTinh` varchar(10) DEFAULT NULL,
  `DiaChi` varchar(200) DEFAULT NULL,
  `DienThoai` varchar(15) DEFAULT NULL,
  `Email` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `hocsinh`
--

INSERT INTO `hocsinh` (`MaHS`, `HoTen`, `NgaySinh`, `GioiTinh`, `DiaChi`, `DienThoai`, `Email`) VALUES
(4901009, 'Nguyễn Văn AB', '1999-03-02', 'Nam', 'abcdw', '1234', 'nguyenteo8tuoi@gmail.com'),
(2147483647, 'Nguyễn Văn CNPM', '2090-02-12', 'Nam', '111', '1111', '');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `lop`
--

CREATE TABLE `lop` (
  `MaLop` int(11) NOT NULL,
  `MaGV` int(11) DEFAULT NULL,
  `TenLop` varchar(50) DEFAULT NULL,
  `Khoi` int(11) DEFAULT NULL,
  `NamHoc` varchar(20) DEFAULT NULL,
  `MaGVCN` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `monhoc`
--

CREATE TABLE `monhoc` (
  `MaMon` int(11) NOT NULL,
  `MaDiem` int(11) DEFAULT NULL,
  `MaGV` int(11) DEFAULT NULL,
  `TenMon` varchar(100) DEFAULT NULL,
  `SoTiet` int(11) DEFAULT NULL,
  `Khoi` int(11) DEFAULT NULL,
  `HinhThucDanhGia` varchar(50) DEFAULT 'Tính điểm'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `monhoc`
--

INSERT INTO `monhoc` (`MaMon`, `MaDiem`, `MaGV`, `TenMon`, `SoTiet`, `Khoi`, `HinhThucDanhGia`) VALUES
(1, NULL, NULL, 'Toán', NULL, NULL, 'Tính điểm'),
(2, NULL, NULL, 'Văn', NULL, NULL, 'Tính điểm'),
(3, NULL, NULL, 'Tin học', NULL, NULL, 'Tính điểm'),
(4, NULL, NULL, 'Hóa Học', NULL, NULL, 'Tính điểm'),
(5, NULL, NULL, 'Vật lý', NULL, NULL, 'Tính điểm');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `phanlop`
--

CREATE TABLE `phanlop` (
  `MaHS` int(11) NOT NULL,
  `MaLop` int(11) NOT NULL,
  `HocKy` int(11) NOT NULL,
  `NamHoc` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `quatrinhhoc`
--

CREATE TABLE `quatrinhhoc` (
  `MaHS` int(11) NOT NULL,
  `MaLop` int(11) NOT NULL,
  `NamHoc` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `taikhoan`
--

CREATE TABLE `taikhoan` (
  `MaTK` int(11) NOT NULL,
  `TenDangNhap` varchar(50) DEFAULT NULL,
  `MatKhau` varchar(50) DEFAULT NULL,
  `VaiTro` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `taikhoan`
--

INSERT INTO `taikhoan` (`MaTK`, `TenDangNhap`, `MatKhau`, `VaiTro`) VALUES
(1, 'admin', '$2y$10$O0O/H6qj2qZ9YqfXzV/u/O8T.Mh.x7X09/G3t3.d3.E', 'Admin');

--
-- Chỉ mục cho các bảng đã đổ
--

--
-- Chỉ mục cho bảng `diem`
--
ALTER TABLE `diem`
  ADD PRIMARY KEY (`MaDiem`),
  ADD KEY `FK_DIEM_HS` (`MaHS`),
  ADD KEY `FK_DIEM_MON` (`MaMon`);

--
-- Chỉ mục cho bảng `giaovien`
--
ALTER TABLE `giaovien`
  ADD PRIMARY KEY (`MaGV`),
  ADD KEY `FK_GV_TK` (`MaTK`);

--
-- Chỉ mục cho bảng `hanhkiem`
--
ALTER TABLE `hanhkiem`
  ADD PRIMARY KEY (`MaHK`),
  ADD KEY `FK_HK_HS` (`MaHS`);

--
-- Chỉ mục cho bảng `hocphi`
--
ALTER TABLE `hocphi`
  ADD PRIMARY KEY (`MaHP`),
  ADD KEY `MaHS` (`MaHS`);

--
-- Chỉ mục cho bảng `hocsinh`
--
ALTER TABLE `hocsinh`
  ADD PRIMARY KEY (`MaHS`);

--
-- Chỉ mục cho bảng `lop`
--
ALTER TABLE `lop`
  ADD PRIMARY KEY (`MaLop`),
  ADD KEY `FK_LOP_GV` (`MaGV`);

--
-- Chỉ mục cho bảng `monhoc`
--
ALTER TABLE `monhoc`
  ADD PRIMARY KEY (`MaMon`),
  ADD KEY `FK_MON_GV` (`MaGV`);

--
-- Chỉ mục cho bảng `phanlop`
--
ALTER TABLE `phanlop`
  ADD PRIMARY KEY (`MaHS`,`MaLop`,`HocKy`,`NamHoc`),
  ADD KEY `MaLop` (`MaLop`);

--
-- Chỉ mục cho bảng `quatrinhhoc`
--
ALTER TABLE `quatrinhhoc`
  ADD PRIMARY KEY (`MaHS`,`MaLop`,`NamHoc`),
  ADD KEY `FK_QTH_LOP` (`MaLop`);

--
-- Chỉ mục cho bảng `taikhoan`
--
ALTER TABLE `taikhoan`
  ADD PRIMARY KEY (`MaTK`);

--
-- AUTO_INCREMENT cho các bảng đã đổ
--

--
-- AUTO_INCREMENT cho bảng `diem`
--
ALTER TABLE `diem`
  MODIFY `MaDiem` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT cho bảng `hanhkiem`
--
ALTER TABLE `hanhkiem`
  MODIFY `MaHK` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `hocphi`
--
ALTER TABLE `hocphi`
  MODIFY `MaHP` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Các ràng buộc cho các bảng đã đổ
--

--
-- Các ràng buộc cho bảng `diem`
--
ALTER TABLE `diem`
  ADD CONSTRAINT `FK_DIEM_HS` FOREIGN KEY (`MaHS`) REFERENCES `hocsinh` (`MaHS`),
  ADD CONSTRAINT `FK_DIEM_MON` FOREIGN KEY (`MaMon`) REFERENCES `monhoc` (`MaMon`);

--
-- Các ràng buộc cho bảng `giaovien`
--
ALTER TABLE `giaovien`
  ADD CONSTRAINT `FK_GV_TK` FOREIGN KEY (`MaTK`) REFERENCES `taikhoan` (`MaTK`);

--
-- Các ràng buộc cho bảng `hanhkiem`
--
ALTER TABLE `hanhkiem`
  ADD CONSTRAINT `FK_HK_HS` FOREIGN KEY (`MaHS`) REFERENCES `hocsinh` (`MaHS`);

--
-- Các ràng buộc cho bảng `hocphi`
--
ALTER TABLE `hocphi`
  ADD CONSTRAINT `hocphi_ibfk_1` FOREIGN KEY (`MaHS`) REFERENCES `hocsinh` (`MaHS`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `lop`
--
ALTER TABLE `lop`
  ADD CONSTRAINT `FK_LOP_GV` FOREIGN KEY (`MaGV`) REFERENCES `giaovien` (`MaGV`);

--
-- Các ràng buộc cho bảng `monhoc`
--
ALTER TABLE `monhoc`
  ADD CONSTRAINT `FK_MON_GV` FOREIGN KEY (`MaGV`) REFERENCES `giaovien` (`MaGV`);

--
-- Các ràng buộc cho bảng `phanlop`
--
ALTER TABLE `phanlop`
  ADD CONSTRAINT `phanlop_ibfk_1` FOREIGN KEY (`MaHS`) REFERENCES `hocsinh` (`MaHS`),
  ADD CONSTRAINT `phanlop_ibfk_2` FOREIGN KEY (`MaLop`) REFERENCES `lop` (`MaLop`);

--
-- Các ràng buộc cho bảng `quatrinhhoc`
--
ALTER TABLE `quatrinhhoc`
  ADD CONSTRAINT `FK_QTH_HS` FOREIGN KEY (`MaHS`) REFERENCES `hocsinh` (`MaHS`) ON DELETE CASCADE,
  ADD CONSTRAINT `FK_QTH_LOP` FOREIGN KEY (`MaLop`) REFERENCES `lop` (`MaLop`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
