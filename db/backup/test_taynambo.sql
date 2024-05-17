-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Máy chủ: 127.0.0.1
-- Thời gian đã tạo: Th5 16, 2024 lúc 10:19 AM
-- Phiên bản máy phục vụ: 10.4.32-MariaDB
-- Phiên bản PHP: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Cơ sở dữ liệu: `test_taynambo`
--
CREATE DATABASE IF NOT EXISTS `test_taynambo` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `test_taynambo`;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `chucvu`
--

DROP TABLE IF EXISTS `chucvu`;
CREATE TABLE `chucvu` (
  `cv_id` bigint(20) NOT NULL,
  `cv_ma` varchar(255) NOT NULL,
  `cv_ten` varchar(500) NOT NULL,
  `cv_diengiai` varchar(2500) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `chucvu`
--

INSERT INTO `chucvu` (`cv_id`, `cv_ma`, `cv_ten`, `cv_diengiai`) VALUES
(1, 'CT', 'CHỦ TỊCH', 'CHỦ TỊCH CÔNG TY TNHH MTV XĂNG DẦU TÂY NAM BỘ'),
(2, 'GĐ', 'GIÁM ĐỐC', 'GIÁM ĐỐC CÔNG TY TNHH MTV XĂNG DẦU TÂY NAM BỘ'),
(3, 'PGĐ', 'PHÓ GIÁM ĐỐC', 'PHÓ GIÁM ĐỐC CÔNG TY TNHH MTV XĂNG DẦU TÂY NAM BỘ'),
(4, 'CTCĐ', 'CHỦ TỊCH CÔNG ĐOÀN', 'CHỦ TỊCH CÔNG ĐOÀN CÔNG TY TNHH MTV XĂNG DẦU TÂY NAM BỘ'),
(5, 'CV', 'CHUYÊN VIÊN', 'CHUYÊN VIÊN VĂN PHÒNG CÔNG TY TNHH MTV XĂNG DẦU TÂY NAM BỘ');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `nhanvien`
--

DROP TABLE IF EXISTS `nhanvien`;
CREATE TABLE `nhanvien` (
  `nv_id` bigint(20) NOT NULL,
  `nv_ma` varchar(255) NOT NULL,
  `nv_hoten` varchar(500) NOT NULL,
  `nv_gioitinh` varchar(2500) DEFAULT NULL,
  `nv_phongban_id` bigint(20) NOT NULL,
  `nv_chucvu_id` bigint(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `nhanvien`
--

INSERT INTO `nhanvien` (`nv_id`, `nv_ma`, `nv_hoten`, `nv_gioitinh`, `nv_phongban_id`, `nv_chucvu_id`) VALUES
(1, '731mandtm', 'ĐỖ THỊ MINH MẪN', 'NỮ', 6, 5),
(2, '731sonvt', 'VÕ THÁI SƠN', 'NAM', 8, 1),
(3, '731chinhnd', 'NGUYỄN ĐĂNG CHINH', 'NAM', 8, 2);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `phongban`
--

DROP TABLE IF EXISTS `phongban`;
CREATE TABLE `phongban` (
  `pb_id` bigint(20) NOT NULL,
  `pb_ma` varchar(255) NOT NULL,
  `pb_ten` varchar(500) NOT NULL,
  `pb_diengiai` varchar(2500) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `phongban`
--

INSERT INTO `phongban` (`pb_id`, `pb_ma`, `pb_ten`, `pb_diengiai`) VALUES
(1, 'PTCHC', 'Phòng tổ chức hành chính', 'Phòng quản lý các công việc về hành chính'),
(2, 'PKTXD', 'Phòng kỹ thuật xăng dầu', 'Phòng nghiên cứu kỹ thuật xăng dầu'),
(3, 'PDTCNVAT', 'Phòng đầu tư công nghệ và an toàn', 'Phòng đầu tư công nghệ'),
(4, 'PKD', 'Phòng kinh doanh', 'Phòng kinh doanh'),
(5, 'PKDTH', 'Phòng kinh doanh tổng hợp', 'Phòng kinh doanh tổng hợp'),
(6, 'PCNTTTDH', 'Phòng công nghệ thông tin tự động hóa', 'Phòng làm về CNTT'),
(7, 'PTCKT', 'Phòng tài chính kế toán', 'Phòng tài chính kế toán'),
(8, 'BGĐ', 'BAN GIÁM ĐỐC', 'BAN GIÁM ĐỐC CÔNG TY');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `vanban`
--

DROP TABLE IF EXISTS `vanban`;
CREATE TABLE `vanban` (
	`vb_id` BIGINT(20) NOT NULL,
	`vb_so` VARCHAR(255) NOT NULL COLLATE 'utf8mb4_unicode_ci',
	`vb_tieude` MEDIUMTEXT NOT NULL COLLATE 'utf8mb4_unicode_ci',
	`vb_trichyeu` MEDIUMTEXT NULL DEFAULT NULL COLLATE 'utf8mb4_unicode_ci',
	`vb_phongban_banhanh_id` BIGINT(20) NULL DEFAULT NULL,
	`vb_nguoiky_hoten` VARCHAR(500) NULL DEFAULT NULL COLLATE 'utf8mb4_unicode_ci',
	`vb_nguoiky_chucdanh` VARCHAR(500) NULL DEFAULT NULL COLLATE 'utf8mb4_unicode_ci',
	`vb_ngayky` DATETIME NULL DEFAULT NULL,
	`vb_ngayhieuluc` DATETIME NULL DEFAULT NULL,
	`vb_taptin_dinhkem` MEDIUMTEXT NULL DEFAULT NULL COLLATE 'utf8mb4_unicode_ci',
	`vb_lienquan` LONGTEXT NULL DEFAULT NULL COLLATE 'utf8mb4_unicode_ci',
	`vb_nguoiky_nhanvien_id` BIGINT(20) NULL DEFAULT NULL,
	`vb_nguoiky_chucvu_id` BIGINT(20) NULL DEFAULT NULL
)
COLLATE='utf8mb4_unicode_ci'
ENGINE=InnoDB
;


--
-- Đang đổ dữ liệu cho bảng `vanban`
--

INSERT INTO `vanban` (`vb_id`, `vb_so`, `vb_tieude`, `vb_trichyeu`, `vb_phongban_banhanh_id`, `vb_nguoiky_hoten`, `vb_nguoiky_chucdanh`, `vb_ngayky`, `vb_ngayhieuluc`, `vb_taptin_dinhkem`, `vb_lienquan`) VALUES
(1, '1521/PLXTNB-KD', 'QUYẾT ĐỊNH: Về việc giá bán lẻ xăng dầu', '- Căn cứ Luật Doanh nghiệp số 59/2020/QH14 có hiệu lực thi hành từ ngày 01/01/2021.\r\n- Căn cứ Điều lệ tổ chức và hoạt động của Công ty TNHH MTV xăng dầu Tây Nam Bộ được Hội đồng quản trị Tập đoàn xăng dầu Việt nam phê duyệt tại quyết định số 649/PLX-QĐ-HĐQT ngày 03/11/2022.\r\n- Căn cứ Quyết định số 678/PLX-QĐ-TGĐ ngày 11/11/2022 của Tập đoàn xăng dầu Việt nam về việc quy định giá bán lẻ các mặt hàng xăng dầu áp dụng từ 15h00 ngày 11/11/2022.', 4, 'Hồ Phú Triệu', 'PHÓ GIÁM ĐỐC', '2022-11-11 00:00:00', '2022-11-11 15:00:00', '20221126214130_1521 PLXTNB-QĐ Vv giá bán lẻ xăng dầu.pdf', NULL),
(2, '1202/PLXTNB-KTXD', 'V/v Lập lại barem bể K08 và K13 của Tổng kho Xăng dầu Miền Tây', 'Kính gửi: Tập toàn Xăng dầu Việt Nam\r\nCăn cứ kế hoạch bảo dưỡng lại hai bể K08 và K13 của Tổng kho xăng dầu Miền Tây đã được Tập đoàn phê duyệt. Trong tháng 07& 08/2022 Công ty đã thực hiện hút vét, súc rừa 02 bể K08 và K13 để bàn giao lại bể trống cho đơn vị thi công tiến hành công tác bão dưỡng và sơn lại bể.', 2, 'Nguyễn Ngọc Trọng', 'PHÓ GIÁM ĐỐC', '2022-08-26 00:00:00', NULL, '20221126214152_1202 PLXTNB Vv Lập lại barem bể K08 và K13 của Tổng kho Xăng dầu Miền Tây.pdf', NULL);

--
-- Chỉ mục cho các bảng đã đổ
--

--
-- Chỉ mục cho bảng `chucvu`
--
ALTER TABLE `chucvu`
  ADD PRIMARY KEY (`cv_id`);

--
-- Chỉ mục cho bảng `nhanvien`
--
ALTER TABLE `nhanvien`
  ADD PRIMARY KEY (`nv_id`) USING BTREE,
  ADD KEY `nv_chucvu_id` (`nv_chucvu_id`),
  ADD KEY `nv_phongban_id` (`nv_phongban_id`);

--
-- Chỉ mục cho bảng `phongban`
--
ALTER TABLE `phongban`
  ADD PRIMARY KEY (`pb_id`);

--
-- Chỉ mục cho bảng `vanban`
--
ALTER TABLE `vanban`
  ADD PRIMARY KEY (`vb_id`) USING BTREE,
  ADD KEY `FK_vanban_ phongban` (`vb_phongban_banhanh_id`);

--
-- AUTO_INCREMENT cho các bảng đã đổ
--

--
-- AUTO_INCREMENT cho bảng `chucvu`
--
ALTER TABLE `chucvu`
  MODIFY `cv_id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT cho bảng `nhanvien`
--
ALTER TABLE `nhanvien`
  MODIFY `nv_id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT cho bảng `phongban`
--
ALTER TABLE `phongban`
  MODIFY `pb_id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT cho bảng `vanban`
--
ALTER TABLE `vanban`
  MODIFY `vb_id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- Các ràng buộc cho các bảng đã đổ
--

--
-- Các ràng buộc cho bảng `nhanvien`
--
ALTER TABLE `nhanvien`
  ADD CONSTRAINT `nhanvien_ibfk_1` FOREIGN KEY (`nv_chucvu_id`) REFERENCES `chucvu` (`cv_id`),
  ADD CONSTRAINT `nhanvien_ibfk_2` FOREIGN KEY (`nv_phongban_id`) REFERENCES `phongban` (`pb_id`);

--
-- Các ràng buộc cho bảng `vanban`
--
ALTER TABLE `vanban`
  ADD CONSTRAINT `FK_vanban_ phongban` FOREIGN KEY (`vb_phongban_banhanh_id`) REFERENCES `phongban` (`pb_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `FK_vanban_chucvu` FOREIGN KEY (`vb_nguoiky_chucvu_id`) REFERENCES `chucvu` (`cv_id`) ON UPDATE NO ACTION ON DELETE NO ACTION,
	ADD CONSTRAINT `FK_vanban_nhanvien` FOREIGN KEY (`vb_nguoiky_nhanvien_id`) REFERENCES `nhanvien` (`nv_id`) ON UPDATE NO ACTION ON DELETE NO ACTION;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
