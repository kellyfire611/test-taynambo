-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Server version:               10.4.22-MariaDB - mariadb.org binary distribution
-- Server OS:                    Win64
-- HeidiSQL Version:             11.3.0.6295
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


-- Dumping database structure for test_taynambo
CREATE DATABASE IF NOT EXISTS `test_taynambo` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci */;
USE `test_taynambo`;

-- Dumping structure for table test_taynambo.phongban
CREATE TABLE IF NOT EXISTS `phongban` (
  `pb_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `pb_ma` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `pb_ten` varchar(500) COLLATE utf8mb4_unicode_ci NOT NULL,
  `pb_diengiai` varchar(2500) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`pb_id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table test_taynambo.phongban: ~9 rows (approximately)
/*!40000 ALTER TABLE `phongban` DISABLE KEYS */;
INSERT INTO `phongban` (`pb_id`, `pb_ma`, `pb_ten`, `pb_diengiai`) VALUES
	(1, 'PTCHC', 'Phòng tổ chức hành chính', 'Phòng quản lý các công việc về hành chính'),
	(2, 'PKTXD', 'Phòng kỹ thuật xăng dầu', 'Phòng nghiên cứu kỹ thuật xăng dầu'),
	(3, 'PDTCNVAT', 'Phòng đầu tư công nghệ và an toàn', 'Phòng đầu tư công nghệ'),
	(4, 'PKD', 'Phòng kinh doanh', 'Phòng kinh doanh'),
	(5, 'PKDTH', 'Phòng kinh doanh tổng hợp', 'Phòng kinh doanh tổng hợp'),
	(6, 'PCNTTTDH', 'Phòng công nghệ thông tin tự động hóa', 'Phòng làm về CNTT'),
	(7, 'PTCKT', 'Phòng tài chính kế toán', 'Phòng tài chính kế toán');
/*!40000 ALTER TABLE `phongban` ENABLE KEYS */;

-- Dumping structure for table test_taynambo.vanban
CREATE TABLE IF NOT EXISTS `vanban` (
  `vb_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `vb_so` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `vb_tieude` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `vb_trichyeu` mediumtext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `vb_phongban_banhanh_id` bigint(20) DEFAULT NULL,
  `vb_nguoiky_hoten` varchar(500) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `vb_nguoiky_chucdanh` varchar(500) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `vb_ngayky` datetime DEFAULT NULL,
  `vb_ngayhieuluc` datetime DEFAULT NULL,
  `vb_taptin_dinhkem` mediumtext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `vb_lienquan` longtext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`vb_id`) USING BTREE,
  KEY `FK_vanban_ phongban` (`vb_phongban_banhanh_id`),
  CONSTRAINT `FK_vanban_ phongban` FOREIGN KEY (`vb_phongban_banhanh_id`) REFERENCES `phongban` (`pb_id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table test_taynambo.vanban: ~2 rows (approximately)
/*!40000 ALTER TABLE `vanban` DISABLE KEYS */;
INSERT INTO `vanban` (`vb_id`, `vb_so`, `vb_tieude`, `vb_trichyeu`, `vb_phongban_banhanh_id`, `vb_nguoiky_hoten`, `vb_nguoiky_chucdanh`, `vb_ngayky`, `vb_ngayhieuluc`, `vb_taptin_dinhkem`, `vb_lienquan`) VALUES
	(1, '1521/PLXTNB-KD', 'QUYẾT ĐỊNH: Về việc giá bán lẻ xăng dầu', '- Căn cứ Luật Doanh nghiệp số 59/2020/QH14 có hiệu lực thi hành từ ngày 01/01/2021.\r\n- Căn cứ Điều lệ tổ chức và hoạt động của Công ty TNHH MTV xăng dầu Tây Nam Bộ được Hội đồng quản trị Tập đoàn xăng dầu Việt nam phê duyệt tại quyết định số 649/PLX-QĐ-HĐQT ngày 03/11/2022.\r\n- Căn cứ Quyết định số 678/PLX-QĐ-TGĐ ngày 11/11/2022 của Tập đoàn xăng dầu Việt nam về việc quy định giá bán lẻ các mặt hàng xăng dầu áp dụng từ 15h00 ngày 11/11/2022.', 4, 'Hồ Phú Triệu', 'PHÓ GIÁM ĐỐC', '2022-11-11 00:00:00', '2022-11-11 15:00:00', '20221126214130_1521 PLXTNB-QĐ Vv giá bán lẻ xăng dầu.pdf', NULL),
	(2, '1202/PLXTNB-KTXD', 'V/v Lập lại barem bể K08 và K13 của Tổng kho Xăng dầu Miền Tây', 'Kính gửi: Tập toàn Xăng dầu Việt Nam\r\nCăn cứ kế hoạch bảo dưỡng lại hai bể K08 và K13 của Tổng kho xăng dầu Miền Tây đã được Tập đoàn phê duyệt. Trong tháng 07& 08/2022 Công ty đã thực hiện hút vét, súc rừa 02 bể K08 và K13 để bàn giao lại bể trống cho đơn vị thi công tiến hành công tác bão dưỡng và sơn lại bể.', 2, 'Nguyễn Ngọc Trọng', 'PHÓ GIÁM ĐỐC', '2022-08-26 00:00:00', NULL, '20221126214152_1202 PLXTNB Vv Lập lại barem bể K08 và K13 của Tổng kho Xăng dầu Miền Tây.pdf', NULL);
/*!40000 ALTER TABLE `vanban` ENABLE KEYS */;

/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
