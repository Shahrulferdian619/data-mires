# ************************************************************
# Sequel Pro SQL dump
# Version 4541
#
# http://www.sequelpro.com/
# https://github.com/sequelpro/sequelpro
#
# Host: 127.0.0.1 (MySQL 5.5.5-10.4.28-MariaDB)
# Database: mires_sistem_v2
# Generation Time: 2023-06-24 13:47:02 +0000
# ************************************************************


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


# Dump of table ability
# ------------------------------------------------------------

DROP TABLE IF EXISTS `ability`;

CREATE TABLE `ability` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `ability` varchar(191) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



# Dump of table ability_user
# ------------------------------------------------------------

DROP TABLE IF EXISTS `ability_user`;

CREATE TABLE `ability_user` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned NOT NULL,
  `ability_id` bigint(20) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



# Dump of table bukubank
# ------------------------------------------------------------

DROP TABLE IF EXISTS `bukubank`;

CREATE TABLE `bukubank` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `bank_id` bigint(20) unsigned NOT NULL,
  `tanggal` date NOT NULL,
  `nomer_sumber` varchar(191) NOT NULL,
  `nomer_ref` varchar(191) DEFAULT NULL,
  `tipe_transaksi` varchar(191) NOT NULL,
  `keterangan` text DEFAULT NULL,
  `nominal_mutasi` double NOT NULL,
  `tipe_mutasi` varchar(191) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `bukubank_bank_id_foreign` (`bank_id`),
  CONSTRAINT `bukubank_bank_id_foreign` FOREIGN KEY (`bank_id`) REFERENCES `coa` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

LOCK TABLES `bukubank` WRITE;
/*!40000 ALTER TABLE `bukubank` DISABLE KEYS */;

INSERT INTO `bukubank` (`id`, `bank_id`, `tanggal`, `nomer_sumber`, `nomer_ref`, `tipe_transaksi`, `keterangan`, `nominal_mutasi`, `tipe_mutasi`, `created_at`, `updated_at`)
VALUES
	(11,3,'2023-06-01','bbm/1000x',NULL,'Penerimaan','dana masuk x',250000,'Debit','2023-06-21 11:09:31','2023-06-21 11:09:31'),
	(12,3,'2023-06-30','bbk/1000x',NULL,'Pembayaran','bayar indihome dan listrik x',134,'Kredit','2023-06-21 11:11:25','2023-06-21 11:11:25');

/*!40000 ALTER TABLE `bukubank` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table bukubesar
# ------------------------------------------------------------

DROP TABLE IF EXISTS `bukubesar`;

CREATE TABLE `bukubesar` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `coa_id` bigint(20) unsigned NOT NULL,
  `sumber_id` bigint(20) unsigned NOT NULL,
  `tahun` varchar(191) NOT NULL,
  `tanggal` varchar(191) NOT NULL,
  `nomer_sumber` varchar(191) NOT NULL,
  `sumber_transaksi` varchar(191) NOT NULL,
  `nominal` double NOT NULL,
  `tipe_mutasi` varchar(191) NOT NULL,
  `keterangan` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `bukubesar_coa_id_foreign` (`coa_id`),
  CONSTRAINT `bukubesar_coa_id_foreign` FOREIGN KEY (`coa_id`) REFERENCES `coa` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

LOCK TABLES `bukubesar` WRITE;
/*!40000 ALTER TABLE `bukubesar` DISABLE KEYS */;

INSERT INTO `bukubesar` (`id`, `coa_id`, `sumber_id`, `tahun`, `tanggal`, `nomer_sumber`, `sumber_transaksi`, `nominal`, `tipe_mutasi`, `keterangan`, `created_at`, `updated_at`)
VALUES
	(32,4,8,'2023','2023-06-24','123','JURNAL_UMUM',10000,'D',NULL,'2023-06-24 12:54:44','2023-06-24 12:54:44'),
	(33,3,8,'2023','2023-06-24','123','JURNAL_UMUM',10000,'K',NULL,'2023-06-24 12:54:44','2023-06-24 12:54:44'),
	(34,5,9,'2023','2023-06-24','2222','JURNAL_UMUM',100000,'D',NULL,'2023-06-24 12:55:49','2023-06-24 12:55:49'),
	(35,6,9,'2023','2023-06-24','2222','JURNAL_UMUM',200000,'D',NULL,'2023-06-24 12:55:49','2023-06-24 12:55:49'),
	(36,4,9,'2023','2023-06-24','2222','JURNAL_UMUM',300000,'K',NULL,'2023-06-24 12:55:49','2023-06-24 12:55:49');

/*!40000 ALTER TABLE `bukubesar` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table coa
# ------------------------------------------------------------

DROP TABLE IF EXISTS `coa`;

CREATE TABLE `coa` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `coa_tipe_id` bigint(20) unsigned NOT NULL,
  `nomer_coa` varchar(191) NOT NULL,
  `nama_coa` varchar(191) NOT NULL,
  `keterangan` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `saldo_awal` double NOT NULL DEFAULT 0,
  `status_aktif` varchar(191) NOT NULL DEFAULT '1' COMMENT '1=aktif, 0=non-aktif',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `coa_nomer_coa_unique` (`nomer_coa`),
  UNIQUE KEY `coa_nama_coa_unique` (`nama_coa`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

LOCK TABLES `coa` WRITE;
/*!40000 ALTER TABLE `coa` DISABLE KEYS */;

INSERT INTO `coa` (`id`, `coa_tipe_id`, `nomer_coa`, `nama_coa`, `keterangan`, `saldo_awal`, `status_aktif`, `created_at`, `updated_at`)
VALUES
	(3,1,'1000.1.12222','Bank BCA klwjflwejfe','xxx',1111405296,'1','2021-11-11 00:00:00','2023-06-21 14:31:55'),
	(4,1,'1000. 2','Kas','',3354294,'1','2021-11-11 00:00:00','2022-02-24 00:00:00'),
	(5,13,'6100.1','Biaya Telepon, Internet, Pulsa','',0,'1','2021-11-11 00:00:00','2022-02-23 00:00:00'),
	(6,13,'6100.2','Biaya Iuran Sampah','',0,'1','2021-11-11 00:00:00','2021-11-11 00:00:00'),
	(7,13,'6100.3','Biaya Listrik','',0,'1','2021-11-16 00:00:00','2021-11-16 00:00:00'),
	(9,1,'1000.1.2','Bank Mandiri','',872,'1','2022-02-21 00:00:00','2022-02-24 00:00:00'),
	(11,1,'1000.1','Bank','',0,'1','2022-02-21 00:00:00','2022-02-21 00:00:00'),
	(13,2,'1100','Piutang Dagang','',0,'1','2022-02-21 00:00:00','2022-02-21 00:00:00'),
	(14,2,'1100.1','AR IDR','',0,'1','2022-02-21 00:00:00','2022-02-21 00:00:00'),
	(15,2,'1200','Uang Muka Pembelian','',0,'1','2022-02-21 00:00:00','2022-02-21 00:00:00'),
	(16,2,'1200.1','DP Pembelian','',0,'1','2022-02-21 00:00:00','2022-02-21 00:00:00'),
	(17,2,'1210','Piutang Lain-lain','Piutang Selain Penjualan dan DP Pembelian',0,'1','2022-02-21 00:00:00','2022-02-21 00:00:00'),
	(18,2,'1220','Piutang Karyawan','',0,'1','2022-02-21 00:00:00','2022-02-21 00:00:00'),
	(19,3,'1300','Persediaan Barang Dagang','',0,'1','2022-02-21 00:00:00','2022-02-21 00:00:00'),
	(20,3,'1300.1','Persediaan Barang Dagang FACE TONER BRIGHTENING 60ML','',0,'1','2022-02-21 00:00:00','2022-02-23 00:00:00'),
	(21,3,'1300.2','Persediaan Barang Dagang TWC KLT','',0,'1','2022-02-21 00:00:00','2022-02-21 00:00:00'),
	(22,3,'1300.3','Persediaan Barang Dagang LIPCREAM','',0,'1','2022-02-21 00:00:00','2022-02-21 00:00:00'),
	(23,3,'1300.4','Persediaan Barang Dagang LIP MATTE','',0,'1','2022-02-21 00:00:00','2022-02-21 00:00:00'),
	(24,3,'1300.5','Persediaan Barang Dagang LIPSCUP','',0,'1','2022-02-21 00:00:00','2022-02-21 00:00:00'),
	(25,3,'1300.6','Persediaan Barang Dagang SOFTLENS','',0,'1','2022-02-21 00:00:00','2022-02-21 00:00:00'),
	(26,3,'1300.7','Persediaan Barang Dagang CAIRAN SOFTLENS','',0,'1','2022-02-21 00:00:00','2022-02-21 00:00:00'),
	(27,3,'1300.8','Persediaan Barang Dagang PAKET NEW KLT BLACK','',0,'1','2022-02-21 00:00:00','2022-02-21 00:00:00'),
	(28,3,'1310','Barang Terkirim','',0,'1','2022-02-21 00:00:00','2022-02-21 00:00:00'),
	(29,3,'1320','Retur Pembelian','Retur Barang ke Supplier',0,'1','2022-02-21 00:00:00','2022-02-21 00:00:00'),
	(30,3,'1400','Persediaan Barang Penolong','',0,'1','2022-02-21 00:00:00','2022-02-21 00:00:00'),
	(31,3,'1400.1','Persediaan Sticker Barang Dagang','',0,'1','2022-02-21 00:00:00','2022-02-21 00:00:00'),
	(32,3,'1400.2','Persediaan Polymailer','',0,'1','2022-02-21 00:00:00','2022-02-21 00:00:00'),
	(33,3,'1400.3','Persediaan Paper Bag Ukuran S','',0,'1','2022-02-21 00:00:00','2022-02-21 00:00:00'),
	(34,3,'1400.4','Persediaan Paper Bag Ukuran M','',0,'1','2022-02-21 00:00:00','2022-02-21 00:00:00'),
	(35,3,'1400.5','Persediaan Paper Bag Ukuran L','',0,'1','2022-02-21 00:00:00','2022-02-21 00:00:00'),
	(36,3,'1400.6','Persediaan Ziplock','',0,'1','2022-02-21 00:00:00','2022-02-21 00:00:00'),
	(37,3,'1400.7','Persediaan Pouch','',0,'1','2022-02-21 00:00:00','2022-02-21 00:00:00'),
	(38,3,'1400.8','Persediaan Bubble Wrap','',0,'1','2022-02-21 00:00:00','2022-02-21 00:00:00'),
	(39,4,'1500','Biaya Dibayar Dimuka','',0,'1','2022-02-21 00:00:00','2022-02-21 00:00:00'),
	(40,4,'1500.1','Sewa Dibayar Dimuka','',0,'1','2022-02-21 00:00:00','2022-02-21 00:00:00'),
	(41,4,'1500.2','Asuransi Kendaraan','',0,'1','2022-02-21 00:00:00','2022-02-21 00:00:00'),
	(42,4,'1500.3','Asuransi Gedung atau Bangunan','',0,'1','2022-02-21 00:00:00','2022-02-21 00:00:00'),
	(43,4,'1600','PPN Masukan','PPN dari Pembelian',0,'1','2022-02-21 00:00:00','2022-02-21 00:00:00'),
	(44,7,'2000','Hutang','',0,'1','2022-02-21 00:00:00','2022-02-21 00:00:00'),
	(45,7,'2000.1','AP Supplier','',0,'1','2022-02-21 00:00:00','2022-02-21 00:00:00'),
	(46,7,'2000.2','Tax Payable 23 - Supplier','Nominal PPH 23 Supplier',0,'1','2022-02-21 00:00:00','2022-02-21 00:00:00'),
	(47,7,'2100','DP Penjualan','',0,'1','2022-02-21 00:00:00','2022-02-21 00:00:00'),
	(48,8,'2200','Hutang Pembelian Belum Ditagih','',0,'1','2022-02-21 00:00:00','2022-02-21 00:00:00'),
	(49,8,'2300','Hutang Pajak','',0,'1','2022-02-21 00:00:00','2022-02-21 00:00:00'),
	(50,8,'2300.1','PPN Keluaran','PPN Penjualan',0,'1','2022-02-21 00:00:00','2022-02-21 00:00:00'),
	(51,8,'2300.2','Tax Payable 21','',0,'1','2022-02-21 00:00:00','2022-02-21 00:00:00'),
	(52,8,'2300.3','Tax Payable 23','E-Billing Pembayaran PPH 23',0,'0','2022-02-21 00:00:00','2022-02-21 00:00:00'),
	(53,8,'2300.4','Tax Payable 4 (2)','',0,'1','2022-02-21 00:00:00','2022-02-21 00:00:00'),
	(54,8,'2300.5','Tax Payable PPN','Selisih antara Pajak Keluaran dikurangi Pajak Masukan, adalah Kurang Bayar ke Negara.',0,'1','2022-02-21 00:00:00','2022-02-21 00:00:00'),
	(55,8,'2400','Biaya Yang Masih Harus Dibayar','Jurnal Setiap Akhir Periode',0,'1','2022-02-22 00:00:00','2022-02-22 00:00:00'),
	(56,8,'2400.1','Biaya Gaji Sales','',0,'1','2022-02-22 00:00:00','2022-02-22 00:00:00'),
	(57,8,'2400.2','Biaya Gaji General & Adm','',0,'1','2022-02-22 00:00:00','2022-02-22 00:00:00'),
	(58,8,'2400.3','Biaya Air','',0,'1','2022-02-23 00:00:00','2022-02-23 00:00:00'),
	(60,8,'2400.5','Biaya Telpon','',0,'1','2022-02-23 00:00:00','2022-02-23 00:00:00'),
	(61,8,'2400.6','Biaya Iklan','',0,'1','2022-02-23 00:00:00','2022-02-23 00:00:00'),
	(62,8,'2400.7','Biaya Sewa','',0,'1','2022-02-23 00:00:00','2022-02-23 00:00:00'),
	(63,8,'2400.8','Biaya','Biaya yang masih harus dibayar lain-lainnya',0,'1','2022-02-23 00:00:00','2022-02-23 00:00:00'),
	(64,8,'2500','Hutang Aktiva','',0,'1','2022-02-23 00:00:00','2022-02-23 00:00:00'),
	(65,8,'2600','Hutang Lain-lain','',0,'1','2022-02-23 00:00:00','2022-02-23 00:00:00'),
	(66,10,'3000','Modal','',0,'1','2022-02-23 00:00:00','2022-02-23 00:00:00'),
	(67,10,'3100','Opening Balance','',0,'1','2022-02-23 00:00:00','2022-02-23 00:00:00'),
	(68,10,'3200','Deviden','',0,'1','2022-02-23 00:00:00','2022-02-23 00:00:00'),
	(69,10,'3300','Prive','',0,'1','2022-02-23 00:00:00','2022-02-23 00:00:00'),
	(70,10,'3400','Tambahan Modal Disetor','',0,'1','2022-02-23 00:00:00','2022-02-23 00:00:00'),
	(71,11,'4000','Pendapatan','',0,'1','2022-02-23 00:00:00','2022-02-23 00:00:00'),
	(72,11,'4000.1','Penjualan','',0,'1','2022-02-23 00:00:00','2022-02-23 00:00:00'),
	(73,11,'4000.2','Pendapatan/Penjualan Lain-lain','',0,'1','2022-02-23 00:00:00','2022-02-23 00:00:00'),
	(74,11,'4000.3','Disc Penjualan','',0,'1','2022-02-23 00:00:00','2022-02-23 00:00:00'),
	(75,11,'4000.4','Retur Penjualan','',0,'1','2022-02-23 00:00:00','2022-02-23 00:00:00'),
	(76,12,'5000','Harga Pokok Penjualan','',0,'1','2022-02-23 00:00:00','2022-02-23 00:00:00'),
	(77,12,'5100','Disc Pembelian','',0,'1','2022-02-23 00:00:00','2022-02-23 00:00:00'),
	(78,13,'6100','Beban Utiliti, Adm, Sewa & Lainnya','',0,'1','2022-02-23 00:00:00','2022-02-23 00:00:00'),
	(79,13,'6100.4','Biaya Air/PDAM','',0,'1','2022-02-23 00:00:00','2022-02-23 00:00:00'),
	(80,13,'6100.5','Biaya Pos & Materai','',0,'1','2022-02-23 00:00:00','2022-02-23 00:00:00'),
	(81,13,'6100.6','Biaya Perjalanan Dinas','',0,'1','2022-02-23 00:00:00','2022-02-23 00:00:00'),
	(82,13,'6100.7','Biaya Perlengkapan Kantor & ATK','',0,'1','2022-02-23 00:00:00','2022-02-23 00:00:00'),
	(83,13,'6100.8','Biaya STNK, KIR & Pajak Kendaraan','',0,'1','2022-02-23 00:00:00','2022-02-23 00:00:00'),
	(84,13,'6100.9','Biaya Donasi','Retribusi, Donasi, Baksos, dll',0,'1','2022-02-23 00:00:00','2022-02-23 00:00:00'),
	(86,13,'6100.11','Biaya Bahan Penolong atau Pembantu','Pembelian bahan penolong/pembantu untuk keperluan Packing, Biaya dos, Tinta untuk stempel dos, Design logo, Sticker, dll',0,'1','2022-02-23 00:00:00','2022-03-04 00:00:00'),
	(87,13,'6100.12','Biaya Ekspedisi & Ongkir Pembelian','',0,'1','2022-02-23 00:00:00','2022-02-23 00:00:00'),
	(88,13,'6100.13','Biaya Perijinan','',0,'1','2022-02-23 00:00:00','2022-02-23 00:00:00'),
	(89,13,'6100.14','Biaya Audit & Konsultan','',0,'1','2022-02-23 00:00:00','2022-02-23 00:00:00'),
	(90,13,'6100.15','Biaya Rumah Tangga Kantor','Gula, Garam, Kecap, Tissue, Air, dll',0,'1','2022-02-23 00:00:00','2022-02-23 00:00:00'),
	(91,13,'6100.16','Biaya Sewa Kendaraan','',0,'1','2022-02-23 00:00:00','2022-02-23 00:00:00'),
	(92,13,'6100.17','Biaya Asuransi Gedung','',0,'1','2022-02-23 00:00:00','2022-02-23 00:00:00'),
	(93,13,'6100.18','Biaya Asuransi Kendaraan','',0,'1','2022-02-23 00:00:00','2022-02-23 00:00:00'),
	(94,13,'6100.19','Biaya Sewa Gudang','',0,'1','2022-02-23 00:00:00','2022-02-23 00:00:00'),
	(96,13,'6100.21','Biaya Sewa Peralatan','',0,'1','2022-02-23 00:00:00','2022-02-23 00:00:00'),
	(97,13,'6100.22','Biaya Design','',0,'1','2022-02-23 00:00:00','2022-02-23 00:00:00'),
	(98,13,'6100.23','Biaya Instalasi','',0,'1','2022-02-23 00:00:00','2022-02-23 00:00:00'),
	(99,13,'6100.24','Biaya PBB & Retribusi Daerah','',0,'1','2022-02-23 00:00:00','2022-02-23 00:00:00'),
	(100,13,'6100.25','Biaya Perjalanan Dinas Non Nota Resmi','',0,'1','2022-02-23 00:00:00','2022-02-23 00:00:00'),
	(101,13,'6100.26','Biaya Sister Company','',0,'1','2022-02-23 00:00:00','2022-02-23 00:00:00'),
	(102,13,'6000','Biaya Pemasaran Umum & Adm','',0,'1','2022-02-23 00:00:00','2022-02-23 00:00:00'),
	(104,13,'6000.2','Biaya Komisi','',0,'1','2022-02-23 00:00:00','2022-02-23 00:00:00'),
	(105,13,'6000.3','Biaya Entertain','',0,'1','2022-02-23 00:00:00','2022-02-23 00:00:00'),
	(106,13,'6000.4','Bonus','',0,'1','2022-02-23 00:00:00','2022-02-23 00:00:00'),
	(107,13,'6000.5','Biaya Entertain Non Nota Resmi','',0,'1','2022-02-23 00:00:00','2022-02-23 00:00:00'),
	(108,13,'6000.6','Biaya Pemasaran Lainnya','',0,'1','2022-02-23 00:00:00','2022-02-23 00:00:00'),
	(109,13,'6200','Biaya Umum & Administrasi','',0,'1','2022-02-23 00:00:00','2022-02-23 00:00:00'),
	(110,13,'6000.7','Biaya Gaji Sales/Marketing','',0,'1','2022-02-23 00:00:00','2022-02-23 00:00:00'),
	(111,13,'6200.1','Biaya Gaji, Lembur, THR','',0,'1','2022-02-23 00:00:00','2022-02-23 00:00:00'),
	(112,13,'6200.2','Biaya Bonus Pesangon & Kompensasi','',0,'1','2022-02-23 00:00:00','2022-02-23 00:00:00'),
	(113,13,'6200.3','Biaya Upah & Honorer','Selain Karyawan PT Mires',0,'1','2022-02-23 00:00:00','2022-02-23 00:00:00'),
	(114,13,'6200.4','Biaya Pengobatan','Swab antigen, dll',0,'1','2022-02-23 00:00:00','2022-02-23 00:00:00'),
	(115,13,'6200.5','Biaya Bensin, Tol, & Parkir','',0,'1','2022-02-23 00:00:00','2022-02-23 00:00:00'),
	(116,13,'6200.6','Biaya Seragam','',0,'1','2022-02-23 00:00:00','2022-02-23 00:00:00'),
	(117,13,'6200.7','Biaya Gathering','',0,'1','2022-02-23 00:00:00','2022-02-23 00:00:00'),
	(118,13,'6300','Biaya Pemeliharaan','',0,'1','2022-02-23 00:00:00','2022-02-23 00:00:00'),
	(119,13,'6300.1','Biaya Pemeliharaan Gedung','',0,'1','2022-02-23 00:00:00','2022-02-23 00:00:00'),
	(120,13,'6300.2','Biaya Pemeliharaan Peralatan Kantor','',0,'1','2022-02-23 00:00:00','2022-02-23 00:00:00'),
	(121,13,'6300.3','Biaya Pemeliharaan Kendaraan','',0,'1','2022-02-23 00:00:00','2022-02-23 00:00:00'),
	(122,13,'6300.4','Biaya Pemeliharaan dan Instalasi Listrik','',0,'1','2022-02-23 00:00:00','2022-02-23 00:00:00'),
	(123,13,'6400','Biaya Penyusutan & Amortisasi','',0,'1','2022-02-23 00:00:00','2022-02-23 00:00:00'),
	(124,13,'6400.1','Biaya Penyusutan Gedung','',0,'1','2022-02-23 00:00:00','2022-02-23 00:00:00'),
	(125,13,'6400.2','Biaya Penyusutan Kendaraan','',0,'1','2022-02-23 00:00:00','2022-02-23 00:00:00'),
	(126,13,'6400.3','Biaya Penyusutan Inventaris Kantor','',0,'1','2022-02-23 00:00:00','2022-02-23 00:00:00'),
	(127,13,'6400.4','Biaya Penyusutan Instalasi Listrik','',0,'1','2022-02-23 00:00:00','2022-02-23 00:00:00'),
	(128,15,'7000','Pendapatan Diluar Usaha','',0,'1','2022-02-23 00:00:00','2022-02-23 00:00:00'),
	(129,15,'7000.1','Pendapatan Bunga','Pendapatan Bunga, Pajak Bunga',0,'1','2022-02-23 00:00:00','2022-02-23 00:00:00'),
	(130,15,'7000.2','Penjualan Inventory/Perlengkapan','',0,'1','2022-02-23 00:00:00','2022-02-23 00:00:00'),
	(131,15,'7000.3','Pendapatan Lain-lain','',0,'1','2022-02-23 00:00:00','2022-02-23 00:00:00'),
	(132,14,'7100','Biaya Diluar Usaha','',0,'1','2022-02-23 00:00:00','2022-02-23 00:00:00'),
	(133,14,'7100.1','Biaya Adm Bank & Transfer Bank','',0,'1','2022-02-23 00:00:00','2022-02-23 00:00:00'),
	(134,14,'7100.2','Beban Lain-lain','',0,'1','2022-02-23 00:00:00','2022-02-23 00:00:00'),
	(135,3,'1300.9','Persediaan Barang Dagang NIGHT CREAM BRIGHTENING 8.5G','',0,'1','2022-02-23 00:00:00','2022-02-23 00:00:00'),
	(137,3,'1300.11','Persediaan Barang Dagang DAY CREAM WITH UV FILTER 10G','',0,'1','2022-02-23 00:00:00','2022-02-23 00:00:00'),
	(138,4,'1700','Cash Back','',0,'1','2022-03-07 00:00:00','2022-03-07 00:00:00'),
	(139,13,'6100.27','Biaya kirim penjualan',NULL,0,'1','2023-05-14 18:30:31','2023-05-14 18:30:31'),
	(140,1,'5555','lorem','lorem ipsum',0,'1','2023-06-21 12:52:14','2023-06-21 12:52:14'),
	(141,1,'555555','lroem','lorem ipsum',0,'1','2023-06-21 12:53:30','2023-06-21 12:53:30');

/*!40000 ALTER TABLE `coa` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table coa_tipe
# ------------------------------------------------------------

DROP TABLE IF EXISTS `coa_tipe`;

CREATE TABLE `coa_tipe` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `coa_tipe` varchar(191) NOT NULL,
  `keterangan` varchar(191) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

LOCK TABLES `coa_tipe` WRITE;
/*!40000 ALTER TABLE `coa_tipe` DISABLE KEYS */;

INSERT INTO `coa_tipe` (`id`, `coa_tipe`, `keterangan`, `created_at`, `updated_at`)
VALUES
	(1,'Kas/Bank',NULL,'2021-11-08 20:11:05','2021-11-08 20:11:05'),
	(2,'Akun Piutang',NULL,'2021-11-08 20:11:05','2021-11-08 20:11:05'),
	(3,'Persediaan',NULL,'2021-11-08 20:11:05','2021-11-08 20:11:05'),
	(4,'Aktiva Lancar lainnya',NULL,'2021-11-08 20:11:05','2021-11-08 20:11:05'),
	(5,'Akumulasi Penyusutan',NULL,'2021-11-08 20:11:05','2021-11-08 20:11:05'),
	(6,'Aktiva Lainnya',NULL,'2021-11-08 20:11:05','2021-11-08 20:11:05'),
	(7,'Akun Hutang',NULL,'2021-11-08 20:11:05','2021-11-08 20:11:05'),
	(8,'Hutang Lancar lainnya',NULL,'2021-11-08 20:11:05','2021-11-08 20:11:05'),
	(9,'Hutang Jangka Panjang',NULL,'2021-11-08 20:11:05','2021-11-08 20:11:05'),
	(10,'Ekuitas',NULL,'2021-11-08 20:11:05','2021-11-08 20:11:05'),
	(11,'Pendapatan',NULL,'2021-11-08 20:11:05','2021-11-08 20:11:05'),
	(12,'Harga Pokok Penjualan',NULL,'2021-11-08 20:11:05','2021-11-08 20:11:05'),
	(13,'Beban',NULL,'2021-11-08 20:11:05','2021-11-08 20:11:05'),
	(14,'Beban lain-lain',NULL,'2021-11-08 20:11:05','2021-11-08 20:11:05'),
	(15,'Pendapatan lain',NULL,'2021-11-08 20:11:05','2021-11-08 20:11:05'),
	(16,'Aktiva Tetap',NULL,'2023-06-21 07:12:10','2023-06-21 07:12:10');

/*!40000 ALTER TABLE `coa_tipe` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table failed_jobs
# ------------------------------------------------------------

DROP TABLE IF EXISTS `failed_jobs`;

CREATE TABLE `failed_jobs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(191) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

LOCK TABLES `failed_jobs` WRITE;
/*!40000 ALTER TABLE `failed_jobs` DISABLE KEYS */;

INSERT INTO `failed_jobs` (`id`, `uuid`, `connection`, `queue`, `payload`, `exception`, `failed_at`)
VALUES
	(1,'55dbe5cb-6865-4334-9af8-1016d928614e','database','default','{\"uuid\":\"55dbe5cb-6865-4334-9af8-1016d928614e\",\"displayName\":\"App\\\\Jobs\\\\v2\\\\Generate\\\\GeneratePdfJob\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\v2\\\\Generate\\\\GeneratePdfJob\",\"command\":\"O:35:\\\"App\\\\Jobs\\\\v2\\\\Generate\\\\GeneratePdfJob\\\":12:{s:13:\\\"\\u0000*\\u0000nama_modul\\\";s:20:\\\"permintaan-pembelian\\\";s:7:\\\"\\u0000*\\u0000data\\\";a:2:{s:10:\\\"permintaan\\\";O:43:\\\"App\\\\Models\\\\v2\\\\Pembelian\\\\PermintaanPembelian\\\":30:{s:13:\\\"\\u0000*\\u0000connection\\\";s:12:\\\"second_mysql\\\";s:8:\\\"\\u0000*\\u0000table\\\";s:20:\\\"permintaan_pembelian\\\";s:10:\\\"\\u0000*\\u0000guarded\\\";a:0:{}s:13:\\\"\\u0000*\\u0000primaryKey\\\";s:2:\\\"id\\\";s:10:\\\"\\u0000*\\u0000keyType\\\";s:3:\\\"int\\\";s:12:\\\"incrementing\\\";b:1;s:7:\\\"\\u0000*\\u0000with\\\";a:0:{}s:12:\\\"\\u0000*\\u0000withCount\\\";a:0:{}s:19:\\\"preventsLazyLoading\\\";b:0;s:10:\\\"\\u0000*\\u0000perPage\\\";i:15;s:6:\\\"exists\\\";b:1;s:18:\\\"wasRecentlyCreated\\\";b:1;s:28:\\\"\\u0000*\\u0000escapeWhenCastingToString\\\";b:0;s:13:\\\"\\u0000*\\u0000attributes\\\";a:8:{s:15:\\\"tipe_permintaan\\\";s:1:\\\"1\\\";s:26:\\\"nomer_permintaan_pembelian\\\";s:7:\\\"pr\\/1000\\\";s:7:\\\"tanggal\\\";s:10:\\\"2023-05-28\\\";s:10:\\\"keterangan\\\";N;s:10:\\\"created_by\\\";i:1;s:10:\\\"updated_at\\\";s:19:\\\"2023-05-28 16:49:40\\\";s:10:\\\"created_at\\\";s:19:\\\"2023-05-28 16:49:40\\\";s:2:\\\"id\\\";i:43;}s:11:\\\"\\u0000*\\u0000original\\\";a:8:{s:15:\\\"tipe_permintaan\\\";s:1:\\\"1\\\";s:26:\\\"nomer_permintaan_pembelian\\\";s:7:\\\"pr\\/1000\\\";s:7:\\\"tanggal\\\";s:10:\\\"2023-05-28\\\";s:10:\\\"keterangan\\\";N;s:10:\\\"created_by\\\";i:1;s:10:\\\"updated_at\\\";s:19:\\\"2023-05-28 16:49:40\\\";s:10:\\\"created_at\\\";s:19:\\\"2023-05-28 16:49:40\\\";s:2:\\\"id\\\";i:43;}s:10:\\\"\\u0000*\\u0000changes\\\";a:0:{}s:8:\\\"\\u0000*\\u0000casts\\\";a:0:{}s:17:\\\"\\u0000*\\u0000classCastCache\\\";a:0:{}s:21:\\\"\\u0000*\\u0000attributeCastCache\\\";a:0:{}s:8:\\\"\\u0000*\\u0000dates\\\";a:0:{}s:13:\\\"\\u0000*\\u0000dateFormat\\\";N;s:10:\\\"\\u0000*\\u0000appends\\\";a:0:{}s:19:\\\"\\u0000*\\u0000dispatchesEvents\\\";a:0:{}s:14:\\\"\\u0000*\\u0000observables\\\";a:0:{}s:12:\\\"\\u0000*\\u0000relations\\\";a:0:{}s:10:\\\"\\u0000*\\u0000touches\\\";a:0:{}s:10:\\\"timestamps\\\";b:1;s:9:\\\"\\u0000*\\u0000hidden\\\";a:0:{}s:10:\\\"\\u0000*\\u0000visible\\\";a:0:{}s:11:\\\"\\u0000*\\u0000fillable\\\";a:0:{}}s:8:\\\"filename\\\";s:11:\\\"pr_1000.pdf\\\";}s:3:\\\"job\\\";N;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:19:\\\"chainCatchCallbacks\\\";N;s:5:\\\"delay\\\";N;s:11:\\\"afterCommit\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}}\"},\"clockwork_id\":\"1685267380-4319-1198231193\",\"clockwork_parent_id\":\"1685267380-3951-496488068\"}','ErrorException: strtoupper() expects parameter 1 to be string, array given in /Users/altama/Desktop/project/mires-sistem/vendor/dompdf/dompdf/src/Dompdf.php:462\nStack trace:\n#0 [internal function]: Illuminate\\Foundation\\Bootstrap\\HandleExceptions->handleError(2, \'strtoupper() ex...\', \'/Users/altama/D...\', 462, Array)\n#1 /Users/altama/Desktop/project/mires-sistem/vendor/dompdf/dompdf/src/Dompdf.php(462): strtoupper(Array)\n#2 /Users/altama/Desktop/project/mires-sistem/vendor/barryvdh/laravel-dompdf/src/PDF.php(92): Dompdf\\Dompdf->loadHtml(\'v2.pembelian.pe...\', Array)\n#3 /Users/altama/Desktop/project/mires-sistem/vendor/barryvdh/laravel-dompdf/src/Facade.php(31): Barryvdh\\DomPDF\\PDF->loadHTML(\'v2.pembelian.pe...\', Array)\n#4 /Users/altama/Desktop/project/mires-sistem/app/Jobs/v2/Generate/GeneratePdfJob.php(43): Barryvdh\\DomPDF\\Facade::__callStatic(\'loadHTML\', Array)\n#5 /Users/altama/Desktop/project/mires-sistem/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(36): App\\Jobs\\v2\\Generate\\GeneratePdfJob->handle()\n#6 /Users/altama/Desktop/project/mires-sistem/vendor/laravel/framework/src/Illuminate/Container/Util.php(40): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()\n#7 /Users/altama/Desktop/project/mires-sistem/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(93): Illuminate\\Container\\Util::unwrapIfClosure(Object(Closure))\n#8 /Users/altama/Desktop/project/mires-sistem/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(37): Illuminate\\Container\\BoundMethod::callBoundMethod(Object(Illuminate\\Foundation\\Application), Array, Object(Closure))\n#9 /Users/altama/Desktop/project/mires-sistem/vendor/laravel/framework/src/Illuminate/Container/Container.php(653): Illuminate\\Container\\BoundMethod::call(Object(Illuminate\\Foundation\\Application), Array, Array, NULL)\n#10 /Users/altama/Desktop/project/mires-sistem/vendor/laravel/framework/src/Illuminate/Bus/Dispatcher.php(128): Illuminate\\Container\\Container->call(Array)\n#11 /Users/altama/Desktop/project/mires-sistem/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(128): Illuminate\\Bus\\Dispatcher->Illuminate\\Bus\\{closure}(Object(App\\Jobs\\v2\\Generate\\GeneratePdfJob))\n#12 /Users/altama/Desktop/project/mires-sistem/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(103): Illuminate\\Pipeline\\Pipeline->Illuminate\\Pipeline\\{closure}(Object(App\\Jobs\\v2\\Generate\\GeneratePdfJob))\n#13 /Users/altama/Desktop/project/mires-sistem/vendor/laravel/framework/src/Illuminate/Bus/Dispatcher.php(132): Illuminate\\Pipeline\\Pipeline->then(Object(Closure))\n#14 /Users/altama/Desktop/project/mires-sistem/vendor/laravel/framework/src/Illuminate/Queue/CallQueuedHandler.php(120): Illuminate\\Bus\\Dispatcher->dispatchNow(Object(App\\Jobs\\v2\\Generate\\GeneratePdfJob), false)\n#15 /Users/altama/Desktop/project/mires-sistem/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(128): Illuminate\\Queue\\CallQueuedHandler->Illuminate\\Queue\\{closure}(Object(App\\Jobs\\v2\\Generate\\GeneratePdfJob))\n#16 /Users/altama/Desktop/project/mires-sistem/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(103): Illuminate\\Pipeline\\Pipeline->Illuminate\\Pipeline\\{closure}(Object(App\\Jobs\\v2\\Generate\\GeneratePdfJob))\n#17 /Users/altama/Desktop/project/mires-sistem/vendor/laravel/framework/src/Illuminate/Queue/CallQueuedHandler.php(122): Illuminate\\Pipeline\\Pipeline->then(Object(Closure))\n#18 /Users/altama/Desktop/project/mires-sistem/vendor/laravel/framework/src/Illuminate/Queue/CallQueuedHandler.php(70): Illuminate\\Queue\\CallQueuedHandler->dispatchThroughMiddleware(Object(Illuminate\\Queue\\Jobs\\DatabaseJob), Object(App\\Jobs\\v2\\Generate\\GeneratePdfJob))\n#19 /Users/altama/Desktop/project/mires-sistem/vendor/laravel/framework/src/Illuminate/Queue/Jobs/Job.php(98): Illuminate\\Queue\\CallQueuedHandler->call(Object(Illuminate\\Queue\\Jobs\\DatabaseJob), Array)\n#20 /Users/altama/Desktop/project/mires-sistem/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(428): Illuminate\\Queue\\Jobs\\Job->fire()\n#21 /Users/altama/Desktop/project/mires-sistem/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(378): Illuminate\\Queue\\Worker->process(\'database\', Object(Illuminate\\Queue\\Jobs\\DatabaseJob), Object(Illuminate\\Queue\\WorkerOptions))\n#22 /Users/altama/Desktop/project/mires-sistem/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(172): Illuminate\\Queue\\Worker->runJob(Object(Illuminate\\Queue\\Jobs\\DatabaseJob), \'database\', Object(Illuminate\\Queue\\WorkerOptions))\n#23 /Users/altama/Desktop/project/mires-sistem/vendor/laravel/framework/src/Illuminate/Queue/Console/WorkCommand.php(117): Illuminate\\Queue\\Worker->daemon(\'database\', \'default\', Object(Illuminate\\Queue\\WorkerOptions))\n#24 /Users/altama/Desktop/project/mires-sistem/vendor/laravel/framework/src/Illuminate/Queue/Console/WorkCommand.php(101): Illuminate\\Queue\\Console\\WorkCommand->runWorker(\'database\', \'default\')\n#25 /Users/altama/Desktop/project/mires-sistem/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(36): Illuminate\\Queue\\Console\\WorkCommand->handle()\n#26 /Users/altama/Desktop/project/mires-sistem/vendor/laravel/framework/src/Illuminate/Container/Util.php(40): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()\n#27 /Users/altama/Desktop/project/mires-sistem/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(93): Illuminate\\Container\\Util::unwrapIfClosure(Object(Closure))\n#28 /Users/altama/Desktop/project/mires-sistem/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(37): Illuminate\\Container\\BoundMethod::callBoundMethod(Object(Illuminate\\Foundation\\Application), Array, Object(Closure))\n#29 /Users/altama/Desktop/project/mires-sistem/vendor/laravel/framework/src/Illuminate/Container/Container.php(653): Illuminate\\Container\\BoundMethod::call(Object(Illuminate\\Foundation\\Application), Array, Array, NULL)\n#30 /Users/altama/Desktop/project/mires-sistem/vendor/laravel/framework/src/Illuminate/Console/Command.php(136): Illuminate\\Container\\Container->call(Array)\n#31 /Users/altama/Desktop/project/mires-sistem/vendor/symfony/console/Command/Command.php(298): Illuminate\\Console\\Command->execute(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Illuminate\\Console\\OutputStyle))\n#32 /Users/altama/Desktop/project/mires-sistem/vendor/laravel/framework/src/Illuminate/Console/Command.php(121): Symfony\\Component\\Console\\Command\\Command->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Illuminate\\Console\\OutputStyle))\n#33 /Users/altama/Desktop/project/mires-sistem/vendor/symfony/console/Application.php(1040): Illuminate\\Console\\Command->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#34 /Users/altama/Desktop/project/mires-sistem/vendor/symfony/console/Application.php(301): Symfony\\Component\\Console\\Application->doRunCommand(Object(Illuminate\\Queue\\Console\\WorkCommand), Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#35 /Users/altama/Desktop/project/mires-sistem/vendor/symfony/console/Application.php(171): Symfony\\Component\\Console\\Application->doRun(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#36 /Users/altama/Desktop/project/mires-sistem/vendor/laravel/framework/src/Illuminate/Console/Application.php(94): Symfony\\Component\\Console\\Application->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#37 /Users/altama/Desktop/project/mires-sistem/vendor/laravel/framework/src/Illuminate/Foundation/Console/Kernel.php(129): Illuminate\\Console\\Application->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#38 /Users/altama/Desktop/project/mires-sistem/artisan(37): Illuminate\\Foundation\\Console\\Kernel->handle(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#39 {main}','2023-05-28 16:49:46'),
	(2,'08a29e5d-5aff-4753-9fd9-c36ae22ecfa5','database','default','{\"uuid\":\"08a29e5d-5aff-4753-9fd9-c36ae22ecfa5\",\"displayName\":\"App\\\\Jobs\\\\v2\\\\Generate\\\\GeneratePdfJob\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\v2\\\\Generate\\\\GeneratePdfJob\",\"command\":\"O:35:\\\"App\\\\Jobs\\\\v2\\\\Generate\\\\GeneratePdfJob\\\":12:{s:13:\\\"\\u0000*\\u0000nama_modul\\\";s:20:\\\"permintaan-pembelian\\\";s:7:\\\"\\u0000*\\u0000data\\\";a:2:{s:10:\\\"permintaan\\\";O:43:\\\"App\\\\Models\\\\v2\\\\Pembelian\\\\PermintaanPembelian\\\":30:{s:13:\\\"\\u0000*\\u0000connection\\\";s:12:\\\"second_mysql\\\";s:8:\\\"\\u0000*\\u0000table\\\";s:20:\\\"permintaan_pembelian\\\";s:10:\\\"\\u0000*\\u0000guarded\\\";a:0:{}s:13:\\\"\\u0000*\\u0000primaryKey\\\";s:2:\\\"id\\\";s:10:\\\"\\u0000*\\u0000keyType\\\";s:3:\\\"int\\\";s:12:\\\"incrementing\\\";b:1;s:7:\\\"\\u0000*\\u0000with\\\";a:0:{}s:12:\\\"\\u0000*\\u0000withCount\\\";a:0:{}s:19:\\\"preventsLazyLoading\\\";b:0;s:10:\\\"\\u0000*\\u0000perPage\\\";i:15;s:6:\\\"exists\\\";b:1;s:18:\\\"wasRecentlyCreated\\\";b:1;s:28:\\\"\\u0000*\\u0000escapeWhenCastingToString\\\";b:0;s:13:\\\"\\u0000*\\u0000attributes\\\";a:8:{s:15:\\\"tipe_permintaan\\\";s:1:\\\"1\\\";s:26:\\\"nomer_permintaan_pembelian\\\";s:8:\\\"pr\\/10002\\\";s:7:\\\"tanggal\\\";s:10:\\\"2023-05-28\\\";s:10:\\\"keterangan\\\";N;s:10:\\\"created_by\\\";i:1;s:10:\\\"updated_at\\\";s:19:\\\"2023-05-28 16:51:31\\\";s:10:\\\"created_at\\\";s:19:\\\"2023-05-28 16:51:31\\\";s:2:\\\"id\\\";i:44;}s:11:\\\"\\u0000*\\u0000original\\\";a:8:{s:15:\\\"tipe_permintaan\\\";s:1:\\\"1\\\";s:26:\\\"nomer_permintaan_pembelian\\\";s:8:\\\"pr\\/10002\\\";s:7:\\\"tanggal\\\";s:10:\\\"2023-05-28\\\";s:10:\\\"keterangan\\\";N;s:10:\\\"created_by\\\";i:1;s:10:\\\"updated_at\\\";s:19:\\\"2023-05-28 16:51:31\\\";s:10:\\\"created_at\\\";s:19:\\\"2023-05-28 16:51:31\\\";s:2:\\\"id\\\";i:44;}s:10:\\\"\\u0000*\\u0000changes\\\";a:0:{}s:8:\\\"\\u0000*\\u0000casts\\\";a:0:{}s:17:\\\"\\u0000*\\u0000classCastCache\\\";a:0:{}s:21:\\\"\\u0000*\\u0000attributeCastCache\\\";a:0:{}s:8:\\\"\\u0000*\\u0000dates\\\";a:0:{}s:13:\\\"\\u0000*\\u0000dateFormat\\\";N;s:10:\\\"\\u0000*\\u0000appends\\\";a:0:{}s:19:\\\"\\u0000*\\u0000dispatchesEvents\\\";a:0:{}s:14:\\\"\\u0000*\\u0000observables\\\";a:0:{}s:12:\\\"\\u0000*\\u0000relations\\\";a:0:{}s:10:\\\"\\u0000*\\u0000touches\\\";a:0:{}s:10:\\\"timestamps\\\";b:1;s:9:\\\"\\u0000*\\u0000hidden\\\";a:0:{}s:10:\\\"\\u0000*\\u0000visible\\\";a:0:{}s:11:\\\"\\u0000*\\u0000fillable\\\";a:0:{}}s:8:\\\"filename\\\";s:12:\\\"pr_10002.pdf\\\";}s:3:\\\"job\\\";N;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:19:\\\"chainCatchCallbacks\\\";N;s:5:\\\"delay\\\";N;s:11:\\\"afterCommit\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}}\"},\"clockwork_id\":\"1685267491-7881-1526258979\",\"clockwork_parent_id\":\"1685267491-7502-449784190\"}','ErrorException: strtoupper() expects parameter 1 to be string, array given in /Users/altama/Desktop/project/mires-sistem/vendor/dompdf/dompdf/src/Dompdf.php:462\nStack trace:\n#0 [internal function]: Illuminate\\Foundation\\Bootstrap\\HandleExceptions->handleError(2, \'strtoupper() ex...\', \'/Users/altama/D...\', 462, Array)\n#1 /Users/altama/Desktop/project/mires-sistem/vendor/dompdf/dompdf/src/Dompdf.php(462): strtoupper(Array)\n#2 /Users/altama/Desktop/project/mires-sistem/vendor/barryvdh/laravel-dompdf/src/PDF.php(92): Dompdf\\Dompdf->loadHtml(\'v2.pembelian.pe...\', Array)\n#3 /Users/altama/Desktop/project/mires-sistem/vendor/barryvdh/laravel-dompdf/src/Facade.php(31): Barryvdh\\DomPDF\\PDF->loadHTML(\'v2.pembelian.pe...\', Array)\n#4 /Users/altama/Desktop/project/mires-sistem/app/Jobs/v2/Generate/GeneratePdfJob.php(43): Barryvdh\\DomPDF\\Facade::__callStatic(\'loadHTML\', Array)\n#5 /Users/altama/Desktop/project/mires-sistem/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(36): App\\Jobs\\v2\\Generate\\GeneratePdfJob->handle()\n#6 /Users/altama/Desktop/project/mires-sistem/vendor/laravel/framework/src/Illuminate/Container/Util.php(40): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()\n#7 /Users/altama/Desktop/project/mires-sistem/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(93): Illuminate\\Container\\Util::unwrapIfClosure(Object(Closure))\n#8 /Users/altama/Desktop/project/mires-sistem/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(37): Illuminate\\Container\\BoundMethod::callBoundMethod(Object(Illuminate\\Foundation\\Application), Array, Object(Closure))\n#9 /Users/altama/Desktop/project/mires-sistem/vendor/laravel/framework/src/Illuminate/Container/Container.php(653): Illuminate\\Container\\BoundMethod::call(Object(Illuminate\\Foundation\\Application), Array, Array, NULL)\n#10 /Users/altama/Desktop/project/mires-sistem/vendor/laravel/framework/src/Illuminate/Bus/Dispatcher.php(128): Illuminate\\Container\\Container->call(Array)\n#11 /Users/altama/Desktop/project/mires-sistem/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(128): Illuminate\\Bus\\Dispatcher->Illuminate\\Bus\\{closure}(Object(App\\Jobs\\v2\\Generate\\GeneratePdfJob))\n#12 /Users/altama/Desktop/project/mires-sistem/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(103): Illuminate\\Pipeline\\Pipeline->Illuminate\\Pipeline\\{closure}(Object(App\\Jobs\\v2\\Generate\\GeneratePdfJob))\n#13 /Users/altama/Desktop/project/mires-sistem/vendor/laravel/framework/src/Illuminate/Bus/Dispatcher.php(132): Illuminate\\Pipeline\\Pipeline->then(Object(Closure))\n#14 /Users/altama/Desktop/project/mires-sistem/vendor/laravel/framework/src/Illuminate/Queue/CallQueuedHandler.php(120): Illuminate\\Bus\\Dispatcher->dispatchNow(Object(App\\Jobs\\v2\\Generate\\GeneratePdfJob), false)\n#15 /Users/altama/Desktop/project/mires-sistem/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(128): Illuminate\\Queue\\CallQueuedHandler->Illuminate\\Queue\\{closure}(Object(App\\Jobs\\v2\\Generate\\GeneratePdfJob))\n#16 /Users/altama/Desktop/project/mires-sistem/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(103): Illuminate\\Pipeline\\Pipeline->Illuminate\\Pipeline\\{closure}(Object(App\\Jobs\\v2\\Generate\\GeneratePdfJob))\n#17 /Users/altama/Desktop/project/mires-sistem/vendor/laravel/framework/src/Illuminate/Queue/CallQueuedHandler.php(122): Illuminate\\Pipeline\\Pipeline->then(Object(Closure))\n#18 /Users/altama/Desktop/project/mires-sistem/vendor/laravel/framework/src/Illuminate/Queue/CallQueuedHandler.php(70): Illuminate\\Queue\\CallQueuedHandler->dispatchThroughMiddleware(Object(Illuminate\\Queue\\Jobs\\DatabaseJob), Object(App\\Jobs\\v2\\Generate\\GeneratePdfJob))\n#19 /Users/altama/Desktop/project/mires-sistem/vendor/laravel/framework/src/Illuminate/Queue/Jobs/Job.php(98): Illuminate\\Queue\\CallQueuedHandler->call(Object(Illuminate\\Queue\\Jobs\\DatabaseJob), Array)\n#20 /Users/altama/Desktop/project/mires-sistem/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(428): Illuminate\\Queue\\Jobs\\Job->fire()\n#21 /Users/altama/Desktop/project/mires-sistem/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(378): Illuminate\\Queue\\Worker->process(\'database\', Object(Illuminate\\Queue\\Jobs\\DatabaseJob), Object(Illuminate\\Queue\\WorkerOptions))\n#22 /Users/altama/Desktop/project/mires-sistem/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(172): Illuminate\\Queue\\Worker->runJob(Object(Illuminate\\Queue\\Jobs\\DatabaseJob), \'database\', Object(Illuminate\\Queue\\WorkerOptions))\n#23 /Users/altama/Desktop/project/mires-sistem/vendor/laravel/framework/src/Illuminate/Queue/Console/WorkCommand.php(117): Illuminate\\Queue\\Worker->daemon(\'database\', \'default\', Object(Illuminate\\Queue\\WorkerOptions))\n#24 /Users/altama/Desktop/project/mires-sistem/vendor/laravel/framework/src/Illuminate/Queue/Console/WorkCommand.php(101): Illuminate\\Queue\\Console\\WorkCommand->runWorker(\'database\', \'default\')\n#25 /Users/altama/Desktop/project/mires-sistem/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(36): Illuminate\\Queue\\Console\\WorkCommand->handle()\n#26 /Users/altama/Desktop/project/mires-sistem/vendor/laravel/framework/src/Illuminate/Container/Util.php(40): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()\n#27 /Users/altama/Desktop/project/mires-sistem/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(93): Illuminate\\Container\\Util::unwrapIfClosure(Object(Closure))\n#28 /Users/altama/Desktop/project/mires-sistem/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(37): Illuminate\\Container\\BoundMethod::callBoundMethod(Object(Illuminate\\Foundation\\Application), Array, Object(Closure))\n#29 /Users/altama/Desktop/project/mires-sistem/vendor/laravel/framework/src/Illuminate/Container/Container.php(653): Illuminate\\Container\\BoundMethod::call(Object(Illuminate\\Foundation\\Application), Array, Array, NULL)\n#30 /Users/altama/Desktop/project/mires-sistem/vendor/laravel/framework/src/Illuminate/Console/Command.php(136): Illuminate\\Container\\Container->call(Array)\n#31 /Users/altama/Desktop/project/mires-sistem/vendor/symfony/console/Command/Command.php(298): Illuminate\\Console\\Command->execute(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Illuminate\\Console\\OutputStyle))\n#32 /Users/altama/Desktop/project/mires-sistem/vendor/laravel/framework/src/Illuminate/Console/Command.php(121): Symfony\\Component\\Console\\Command\\Command->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Illuminate\\Console\\OutputStyle))\n#33 /Users/altama/Desktop/project/mires-sistem/vendor/symfony/console/Application.php(1040): Illuminate\\Console\\Command->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#34 /Users/altama/Desktop/project/mires-sistem/vendor/symfony/console/Application.php(301): Symfony\\Component\\Console\\Application->doRunCommand(Object(Illuminate\\Queue\\Console\\WorkCommand), Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#35 /Users/altama/Desktop/project/mires-sistem/vendor/symfony/console/Application.php(171): Symfony\\Component\\Console\\Application->doRun(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#36 /Users/altama/Desktop/project/mires-sistem/vendor/laravel/framework/src/Illuminate/Console/Application.php(94): Symfony\\Component\\Console\\Application->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#37 /Users/altama/Desktop/project/mires-sistem/vendor/laravel/framework/src/Illuminate/Foundation/Console/Kernel.php(129): Illuminate\\Console\\Application->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#38 /Users/altama/Desktop/project/mires-sistem/artisan(37): Illuminate\\Foundation\\Console\\Kernel->handle(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#39 {main}','2023-05-28 16:51:36'),
	(3,'5b62e4a6-0194-41ac-9c9f-1df6630b0f03','database','default','{\"uuid\":\"5b62e4a6-0194-41ac-9c9f-1df6630b0f03\",\"displayName\":\"App\\\\Jobs\\\\v2\\\\Generate\\\\GeneratePdfJob\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\v2\\\\Generate\\\\GeneratePdfJob\",\"command\":\"O:35:\\\"App\\\\Jobs\\\\v2\\\\Generate\\\\GeneratePdfJob\\\":12:{s:13:\\\"\\u0000*\\u0000nama_modul\\\";s:20:\\\"permintaan-pembelian\\\";s:7:\\\"\\u0000*\\u0000data\\\";a:2:{s:10:\\\"permintaan\\\";O:43:\\\"App\\\\Models\\\\v2\\\\Pembelian\\\\PermintaanPembelian\\\":30:{s:13:\\\"\\u0000*\\u0000connection\\\";s:12:\\\"second_mysql\\\";s:8:\\\"\\u0000*\\u0000table\\\";s:20:\\\"permintaan_pembelian\\\";s:10:\\\"\\u0000*\\u0000guarded\\\";a:0:{}s:13:\\\"\\u0000*\\u0000primaryKey\\\";s:2:\\\"id\\\";s:10:\\\"\\u0000*\\u0000keyType\\\";s:3:\\\"int\\\";s:12:\\\"incrementing\\\";b:1;s:7:\\\"\\u0000*\\u0000with\\\";a:0:{}s:12:\\\"\\u0000*\\u0000withCount\\\";a:0:{}s:19:\\\"preventsLazyLoading\\\";b:0;s:10:\\\"\\u0000*\\u0000perPage\\\";i:15;s:6:\\\"exists\\\";b:1;s:18:\\\"wasRecentlyCreated\\\";b:1;s:28:\\\"\\u0000*\\u0000escapeWhenCastingToString\\\";b:0;s:13:\\\"\\u0000*\\u0000attributes\\\";a:8:{s:15:\\\"tipe_permintaan\\\";s:1:\\\"1\\\";s:26:\\\"nomer_permintaan_pembelian\\\";s:7:\\\"PR\\/1111\\\";s:7:\\\"tanggal\\\";s:10:\\\"2023-05-28\\\";s:10:\\\"keterangan\\\";N;s:10:\\\"created_by\\\";i:1;s:10:\\\"updated_at\\\";s:19:\\\"2023-05-28 16:55:41\\\";s:10:\\\"created_at\\\";s:19:\\\"2023-05-28 16:55:41\\\";s:2:\\\"id\\\";i:46;}s:11:\\\"\\u0000*\\u0000original\\\";a:8:{s:15:\\\"tipe_permintaan\\\";s:1:\\\"1\\\";s:26:\\\"nomer_permintaan_pembelian\\\";s:7:\\\"PR\\/1111\\\";s:7:\\\"tanggal\\\";s:10:\\\"2023-05-28\\\";s:10:\\\"keterangan\\\";N;s:10:\\\"created_by\\\";i:1;s:10:\\\"updated_at\\\";s:19:\\\"2023-05-28 16:55:41\\\";s:10:\\\"created_at\\\";s:19:\\\"2023-05-28 16:55:41\\\";s:2:\\\"id\\\";i:46;}s:10:\\\"\\u0000*\\u0000changes\\\";a:0:{}s:8:\\\"\\u0000*\\u0000casts\\\";a:0:{}s:17:\\\"\\u0000*\\u0000classCastCache\\\";a:0:{}s:21:\\\"\\u0000*\\u0000attributeCastCache\\\";a:0:{}s:8:\\\"\\u0000*\\u0000dates\\\";a:0:{}s:13:\\\"\\u0000*\\u0000dateFormat\\\";N;s:10:\\\"\\u0000*\\u0000appends\\\";a:0:{}s:19:\\\"\\u0000*\\u0000dispatchesEvents\\\";a:0:{}s:14:\\\"\\u0000*\\u0000observables\\\";a:0:{}s:12:\\\"\\u0000*\\u0000relations\\\";a:0:{}s:10:\\\"\\u0000*\\u0000touches\\\";a:0:{}s:10:\\\"timestamps\\\";b:1;s:9:\\\"\\u0000*\\u0000hidden\\\";a:0:{}s:10:\\\"\\u0000*\\u0000visible\\\";a:0:{}s:11:\\\"\\u0000*\\u0000fillable\\\";a:0:{}}s:8:\\\"filename\\\";s:11:\\\"PR_1111.pdf\\\";}s:3:\\\"job\\\";N;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:19:\\\"chainCatchCallbacks\\\";N;s:5:\\\"delay\\\";N;s:11:\\\"afterCommit\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}}\"},\"clockwork_id\":\"1685267741-8438-82514483\",\"clockwork_parent_id\":\"1685267741-8054-496815223\"}','ErrorException: strtoupper() expects parameter 1 to be string, array given in /Users/altama/Desktop/project/mires-sistem/vendor/dompdf/dompdf/src/Dompdf.php:462\nStack trace:\n#0 [internal function]: Illuminate\\Foundation\\Bootstrap\\HandleExceptions->handleError(2, \'strtoupper() ex...\', \'/Users/altama/D...\', 462, Array)\n#1 /Users/altama/Desktop/project/mires-sistem/vendor/dompdf/dompdf/src/Dompdf.php(462): strtoupper(Array)\n#2 /Users/altama/Desktop/project/mires-sistem/vendor/barryvdh/laravel-dompdf/src/PDF.php(92): Dompdf\\Dompdf->loadHtml(\'v2.pembelian.pe...\', Array)\n#3 /Users/altama/Desktop/project/mires-sistem/vendor/barryvdh/laravel-dompdf/src/Facade.php(31): Barryvdh\\DomPDF\\PDF->loadHTML(\'v2.pembelian.pe...\', Array)\n#4 /Users/altama/Desktop/project/mires-sistem/app/Jobs/v2/Generate/GeneratePdfJob.php(43): Barryvdh\\DomPDF\\Facade::__callStatic(\'loadHTML\', Array)\n#5 /Users/altama/Desktop/project/mires-sistem/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(36): App\\Jobs\\v2\\Generate\\GeneratePdfJob->handle()\n#6 /Users/altama/Desktop/project/mires-sistem/vendor/laravel/framework/src/Illuminate/Container/Util.php(40): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()\n#7 /Users/altama/Desktop/project/mires-sistem/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(93): Illuminate\\Container\\Util::unwrapIfClosure(Object(Closure))\n#8 /Users/altama/Desktop/project/mires-sistem/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(37): Illuminate\\Container\\BoundMethod::callBoundMethod(Object(Illuminate\\Foundation\\Application), Array, Object(Closure))\n#9 /Users/altama/Desktop/project/mires-sistem/vendor/laravel/framework/src/Illuminate/Container/Container.php(653): Illuminate\\Container\\BoundMethod::call(Object(Illuminate\\Foundation\\Application), Array, Array, NULL)\n#10 /Users/altama/Desktop/project/mires-sistem/vendor/laravel/framework/src/Illuminate/Bus/Dispatcher.php(128): Illuminate\\Container\\Container->call(Array)\n#11 /Users/altama/Desktop/project/mires-sistem/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(128): Illuminate\\Bus\\Dispatcher->Illuminate\\Bus\\{closure}(Object(App\\Jobs\\v2\\Generate\\GeneratePdfJob))\n#12 /Users/altama/Desktop/project/mires-sistem/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(103): Illuminate\\Pipeline\\Pipeline->Illuminate\\Pipeline\\{closure}(Object(App\\Jobs\\v2\\Generate\\GeneratePdfJob))\n#13 /Users/altama/Desktop/project/mires-sistem/vendor/laravel/framework/src/Illuminate/Bus/Dispatcher.php(132): Illuminate\\Pipeline\\Pipeline->then(Object(Closure))\n#14 /Users/altama/Desktop/project/mires-sistem/vendor/laravel/framework/src/Illuminate/Queue/CallQueuedHandler.php(120): Illuminate\\Bus\\Dispatcher->dispatchNow(Object(App\\Jobs\\v2\\Generate\\GeneratePdfJob), false)\n#15 /Users/altama/Desktop/project/mires-sistem/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(128): Illuminate\\Queue\\CallQueuedHandler->Illuminate\\Queue\\{closure}(Object(App\\Jobs\\v2\\Generate\\GeneratePdfJob))\n#16 /Users/altama/Desktop/project/mires-sistem/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(103): Illuminate\\Pipeline\\Pipeline->Illuminate\\Pipeline\\{closure}(Object(App\\Jobs\\v2\\Generate\\GeneratePdfJob))\n#17 /Users/altama/Desktop/project/mires-sistem/vendor/laravel/framework/src/Illuminate/Queue/CallQueuedHandler.php(122): Illuminate\\Pipeline\\Pipeline->then(Object(Closure))\n#18 /Users/altama/Desktop/project/mires-sistem/vendor/laravel/framework/src/Illuminate/Queue/CallQueuedHandler.php(70): Illuminate\\Queue\\CallQueuedHandler->dispatchThroughMiddleware(Object(Illuminate\\Queue\\Jobs\\DatabaseJob), Object(App\\Jobs\\v2\\Generate\\GeneratePdfJob))\n#19 /Users/altama/Desktop/project/mires-sistem/vendor/laravel/framework/src/Illuminate/Queue/Jobs/Job.php(98): Illuminate\\Queue\\CallQueuedHandler->call(Object(Illuminate\\Queue\\Jobs\\DatabaseJob), Array)\n#20 /Users/altama/Desktop/project/mires-sistem/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(428): Illuminate\\Queue\\Jobs\\Job->fire()\n#21 /Users/altama/Desktop/project/mires-sistem/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(378): Illuminate\\Queue\\Worker->process(\'database\', Object(Illuminate\\Queue\\Jobs\\DatabaseJob), Object(Illuminate\\Queue\\WorkerOptions))\n#22 /Users/altama/Desktop/project/mires-sistem/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(172): Illuminate\\Queue\\Worker->runJob(Object(Illuminate\\Queue\\Jobs\\DatabaseJob), \'database\', Object(Illuminate\\Queue\\WorkerOptions))\n#23 /Users/altama/Desktop/project/mires-sistem/vendor/laravel/framework/src/Illuminate/Queue/Console/WorkCommand.php(117): Illuminate\\Queue\\Worker->daemon(\'database\', \'default\', Object(Illuminate\\Queue\\WorkerOptions))\n#24 /Users/altama/Desktop/project/mires-sistem/vendor/laravel/framework/src/Illuminate/Queue/Console/WorkCommand.php(101): Illuminate\\Queue\\Console\\WorkCommand->runWorker(\'database\', \'default\')\n#25 /Users/altama/Desktop/project/mires-sistem/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(36): Illuminate\\Queue\\Console\\WorkCommand->handle()\n#26 /Users/altama/Desktop/project/mires-sistem/vendor/laravel/framework/src/Illuminate/Container/Util.php(40): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()\n#27 /Users/altama/Desktop/project/mires-sistem/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(93): Illuminate\\Container\\Util::unwrapIfClosure(Object(Closure))\n#28 /Users/altama/Desktop/project/mires-sistem/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(37): Illuminate\\Container\\BoundMethod::callBoundMethod(Object(Illuminate\\Foundation\\Application), Array, Object(Closure))\n#29 /Users/altama/Desktop/project/mires-sistem/vendor/laravel/framework/src/Illuminate/Container/Container.php(653): Illuminate\\Container\\BoundMethod::call(Object(Illuminate\\Foundation\\Application), Array, Array, NULL)\n#30 /Users/altama/Desktop/project/mires-sistem/vendor/laravel/framework/src/Illuminate/Console/Command.php(136): Illuminate\\Container\\Container->call(Array)\n#31 /Users/altama/Desktop/project/mires-sistem/vendor/symfony/console/Command/Command.php(298): Illuminate\\Console\\Command->execute(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Illuminate\\Console\\OutputStyle))\n#32 /Users/altama/Desktop/project/mires-sistem/vendor/laravel/framework/src/Illuminate/Console/Command.php(121): Symfony\\Component\\Console\\Command\\Command->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Illuminate\\Console\\OutputStyle))\n#33 /Users/altama/Desktop/project/mires-sistem/vendor/symfony/console/Application.php(1040): Illuminate\\Console\\Command->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#34 /Users/altama/Desktop/project/mires-sistem/vendor/symfony/console/Application.php(301): Symfony\\Component\\Console\\Application->doRunCommand(Object(Illuminate\\Queue\\Console\\WorkCommand), Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#35 /Users/altama/Desktop/project/mires-sistem/vendor/symfony/console/Application.php(171): Symfony\\Component\\Console\\Application->doRun(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#36 /Users/altama/Desktop/project/mires-sistem/vendor/laravel/framework/src/Illuminate/Console/Application.php(94): Symfony\\Component\\Console\\Application->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#37 /Users/altama/Desktop/project/mires-sistem/vendor/laravel/framework/src/Illuminate/Foundation/Console/Kernel.php(129): Illuminate\\Console\\Application->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#38 /Users/altama/Desktop/project/mires-sistem/artisan(37): Illuminate\\Foundation\\Console\\Kernel->handle(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#39 {main}','2023-05-28 16:55:43'),
	(4,'9af9c319-6e09-4d13-a566-29341c2a3323','database','default','{\"uuid\":\"9af9c319-6e09-4d13-a566-29341c2a3323\",\"displayName\":\"App\\\\Jobs\\\\v2\\\\Generate\\\\GeneratePdfJob\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\v2\\\\Generate\\\\GeneratePdfJob\",\"command\":\"O:35:\\\"App\\\\Jobs\\\\v2\\\\Generate\\\\GeneratePdfJob\\\":12:{s:13:\\\"\\u0000*\\u0000nama_modul\\\";s:20:\\\"permintaan-pembelian\\\";s:7:\\\"\\u0000*\\u0000data\\\";a:2:{s:10:\\\"permintaan\\\";O:43:\\\"App\\\\Models\\\\v2\\\\Pembelian\\\\PermintaanPembelian\\\":30:{s:13:\\\"\\u0000*\\u0000connection\\\";s:12:\\\"second_mysql\\\";s:8:\\\"\\u0000*\\u0000table\\\";s:20:\\\"permintaan_pembelian\\\";s:10:\\\"\\u0000*\\u0000guarded\\\";a:0:{}s:13:\\\"\\u0000*\\u0000primaryKey\\\";s:2:\\\"id\\\";s:10:\\\"\\u0000*\\u0000keyType\\\";s:3:\\\"int\\\";s:12:\\\"incrementing\\\";b:1;s:7:\\\"\\u0000*\\u0000with\\\";a:0:{}s:12:\\\"\\u0000*\\u0000withCount\\\";a:0:{}s:19:\\\"preventsLazyLoading\\\";b:0;s:10:\\\"\\u0000*\\u0000perPage\\\";i:15;s:6:\\\"exists\\\";b:1;s:18:\\\"wasRecentlyCreated\\\";b:1;s:28:\\\"\\u0000*\\u0000escapeWhenCastingToString\\\";b:0;s:13:\\\"\\u0000*\\u0000attributes\\\";a:8:{s:15:\\\"tipe_permintaan\\\";s:1:\\\"1\\\";s:26:\\\"nomer_permintaan_pembelian\\\";s:7:\\\"pr\\/2222\\\";s:7:\\\"tanggal\\\";s:10:\\\"2023-05-28\\\";s:10:\\\"keterangan\\\";N;s:10:\\\"created_by\\\";i:1;s:10:\\\"updated_at\\\";s:19:\\\"2023-05-28 16:57:58\\\";s:10:\\\"created_at\\\";s:19:\\\"2023-05-28 16:57:58\\\";s:2:\\\"id\\\";i:47;}s:11:\\\"\\u0000*\\u0000original\\\";a:8:{s:15:\\\"tipe_permintaan\\\";s:1:\\\"1\\\";s:26:\\\"nomer_permintaan_pembelian\\\";s:7:\\\"pr\\/2222\\\";s:7:\\\"tanggal\\\";s:10:\\\"2023-05-28\\\";s:10:\\\"keterangan\\\";N;s:10:\\\"created_by\\\";i:1;s:10:\\\"updated_at\\\";s:19:\\\"2023-05-28 16:57:58\\\";s:10:\\\"created_at\\\";s:19:\\\"2023-05-28 16:57:58\\\";s:2:\\\"id\\\";i:47;}s:10:\\\"\\u0000*\\u0000changes\\\";a:0:{}s:8:\\\"\\u0000*\\u0000casts\\\";a:0:{}s:17:\\\"\\u0000*\\u0000classCastCache\\\";a:0:{}s:21:\\\"\\u0000*\\u0000attributeCastCache\\\";a:0:{}s:8:\\\"\\u0000*\\u0000dates\\\";a:0:{}s:13:\\\"\\u0000*\\u0000dateFormat\\\";N;s:10:\\\"\\u0000*\\u0000appends\\\";a:0:{}s:19:\\\"\\u0000*\\u0000dispatchesEvents\\\";a:0:{}s:14:\\\"\\u0000*\\u0000observables\\\";a:0:{}s:12:\\\"\\u0000*\\u0000relations\\\";a:0:{}s:10:\\\"\\u0000*\\u0000touches\\\";a:0:{}s:10:\\\"timestamps\\\";b:1;s:9:\\\"\\u0000*\\u0000hidden\\\";a:0:{}s:10:\\\"\\u0000*\\u0000visible\\\";a:0:{}s:11:\\\"\\u0000*\\u0000fillable\\\";a:0:{}}s:8:\\\"filename\\\";s:11:\\\"pr_2222.pdf\\\";}s:3:\\\"job\\\";N;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:19:\\\"chainCatchCallbacks\\\";N;s:5:\\\"delay\\\";N;s:11:\\\"afterCommit\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}}\"},\"clockwork_id\":\"1685267878-4309-1888838206\",\"clockwork_parent_id\":\"1685267878-3966-1800669110\"}','ErrorException: strtoupper() expects parameter 1 to be string, array given in /Users/altama/Desktop/project/mires-sistem/vendor/dompdf/dompdf/src/Dompdf.php:462\nStack trace:\n#0 [internal function]: Illuminate\\Foundation\\Bootstrap\\HandleExceptions->handleError(2, \'strtoupper() ex...\', \'/Users/altama/D...\', 462, Array)\n#1 /Users/altama/Desktop/project/mires-sistem/vendor/dompdf/dompdf/src/Dompdf.php(462): strtoupper(Array)\n#2 /Users/altama/Desktop/project/mires-sistem/vendor/barryvdh/laravel-dompdf/src/PDF.php(92): Dompdf\\Dompdf->loadHtml(\'v2.pembelian.pe...\', Array)\n#3 /Users/altama/Desktop/project/mires-sistem/vendor/barryvdh/laravel-dompdf/src/Facade.php(31): Barryvdh\\DomPDF\\PDF->loadHTML(\'v2.pembelian.pe...\', Array)\n#4 /Users/altama/Desktop/project/mires-sistem/app/Jobs/v2/Generate/GeneratePdfJob.php(43): Barryvdh\\DomPDF\\Facade::__callStatic(\'loadHTML\', Array)\n#5 /Users/altama/Desktop/project/mires-sistem/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(36): App\\Jobs\\v2\\Generate\\GeneratePdfJob->handle()\n#6 /Users/altama/Desktop/project/mires-sistem/vendor/laravel/framework/src/Illuminate/Container/Util.php(40): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()\n#7 /Users/altama/Desktop/project/mires-sistem/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(93): Illuminate\\Container\\Util::unwrapIfClosure(Object(Closure))\n#8 /Users/altama/Desktop/project/mires-sistem/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(37): Illuminate\\Container\\BoundMethod::callBoundMethod(Object(Illuminate\\Foundation\\Application), Array, Object(Closure))\n#9 /Users/altama/Desktop/project/mires-sistem/vendor/laravel/framework/src/Illuminate/Container/Container.php(653): Illuminate\\Container\\BoundMethod::call(Object(Illuminate\\Foundation\\Application), Array, Array, NULL)\n#10 /Users/altama/Desktop/project/mires-sistem/vendor/laravel/framework/src/Illuminate/Bus/Dispatcher.php(128): Illuminate\\Container\\Container->call(Array)\n#11 /Users/altama/Desktop/project/mires-sistem/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(128): Illuminate\\Bus\\Dispatcher->Illuminate\\Bus\\{closure}(Object(App\\Jobs\\v2\\Generate\\GeneratePdfJob))\n#12 /Users/altama/Desktop/project/mires-sistem/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(103): Illuminate\\Pipeline\\Pipeline->Illuminate\\Pipeline\\{closure}(Object(App\\Jobs\\v2\\Generate\\GeneratePdfJob))\n#13 /Users/altama/Desktop/project/mires-sistem/vendor/laravel/framework/src/Illuminate/Bus/Dispatcher.php(132): Illuminate\\Pipeline\\Pipeline->then(Object(Closure))\n#14 /Users/altama/Desktop/project/mires-sistem/vendor/laravel/framework/src/Illuminate/Queue/CallQueuedHandler.php(120): Illuminate\\Bus\\Dispatcher->dispatchNow(Object(App\\Jobs\\v2\\Generate\\GeneratePdfJob), false)\n#15 /Users/altama/Desktop/project/mires-sistem/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(128): Illuminate\\Queue\\CallQueuedHandler->Illuminate\\Queue\\{closure}(Object(App\\Jobs\\v2\\Generate\\GeneratePdfJob))\n#16 /Users/altama/Desktop/project/mires-sistem/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(103): Illuminate\\Pipeline\\Pipeline->Illuminate\\Pipeline\\{closure}(Object(App\\Jobs\\v2\\Generate\\GeneratePdfJob))\n#17 /Users/altama/Desktop/project/mires-sistem/vendor/laravel/framework/src/Illuminate/Queue/CallQueuedHandler.php(122): Illuminate\\Pipeline\\Pipeline->then(Object(Closure))\n#18 /Users/altama/Desktop/project/mires-sistem/vendor/laravel/framework/src/Illuminate/Queue/CallQueuedHandler.php(70): Illuminate\\Queue\\CallQueuedHandler->dispatchThroughMiddleware(Object(Illuminate\\Queue\\Jobs\\DatabaseJob), Object(App\\Jobs\\v2\\Generate\\GeneratePdfJob))\n#19 /Users/altama/Desktop/project/mires-sistem/vendor/laravel/framework/src/Illuminate/Queue/Jobs/Job.php(98): Illuminate\\Queue\\CallQueuedHandler->call(Object(Illuminate\\Queue\\Jobs\\DatabaseJob), Array)\n#20 /Users/altama/Desktop/project/mires-sistem/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(428): Illuminate\\Queue\\Jobs\\Job->fire()\n#21 /Users/altama/Desktop/project/mires-sistem/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(378): Illuminate\\Queue\\Worker->process(\'database\', Object(Illuminate\\Queue\\Jobs\\DatabaseJob), Object(Illuminate\\Queue\\WorkerOptions))\n#22 /Users/altama/Desktop/project/mires-sistem/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(172): Illuminate\\Queue\\Worker->runJob(Object(Illuminate\\Queue\\Jobs\\DatabaseJob), \'database\', Object(Illuminate\\Queue\\WorkerOptions))\n#23 /Users/altama/Desktop/project/mires-sistem/vendor/laravel/framework/src/Illuminate/Queue/Console/WorkCommand.php(117): Illuminate\\Queue\\Worker->daemon(\'database\', \'default\', Object(Illuminate\\Queue\\WorkerOptions))\n#24 /Users/altama/Desktop/project/mires-sistem/vendor/laravel/framework/src/Illuminate/Queue/Console/WorkCommand.php(101): Illuminate\\Queue\\Console\\WorkCommand->runWorker(\'database\', \'default\')\n#25 /Users/altama/Desktop/project/mires-sistem/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(36): Illuminate\\Queue\\Console\\WorkCommand->handle()\n#26 /Users/altama/Desktop/project/mires-sistem/vendor/laravel/framework/src/Illuminate/Container/Util.php(40): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()\n#27 /Users/altama/Desktop/project/mires-sistem/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(93): Illuminate\\Container\\Util::unwrapIfClosure(Object(Closure))\n#28 /Users/altama/Desktop/project/mires-sistem/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(37): Illuminate\\Container\\BoundMethod::callBoundMethod(Object(Illuminate\\Foundation\\Application), Array, Object(Closure))\n#29 /Users/altama/Desktop/project/mires-sistem/vendor/laravel/framework/src/Illuminate/Container/Container.php(653): Illuminate\\Container\\BoundMethod::call(Object(Illuminate\\Foundation\\Application), Array, Array, NULL)\n#30 /Users/altama/Desktop/project/mires-sistem/vendor/laravel/framework/src/Illuminate/Console/Command.php(136): Illuminate\\Container\\Container->call(Array)\n#31 /Users/altama/Desktop/project/mires-sistem/vendor/symfony/console/Command/Command.php(298): Illuminate\\Console\\Command->execute(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Illuminate\\Console\\OutputStyle))\n#32 /Users/altama/Desktop/project/mires-sistem/vendor/laravel/framework/src/Illuminate/Console/Command.php(121): Symfony\\Component\\Console\\Command\\Command->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Illuminate\\Console\\OutputStyle))\n#33 /Users/altama/Desktop/project/mires-sistem/vendor/symfony/console/Application.php(1040): Illuminate\\Console\\Command->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#34 /Users/altama/Desktop/project/mires-sistem/vendor/symfony/console/Application.php(301): Symfony\\Component\\Console\\Application->doRunCommand(Object(Illuminate\\Queue\\Console\\WorkCommand), Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#35 /Users/altama/Desktop/project/mires-sistem/vendor/symfony/console/Application.php(171): Symfony\\Component\\Console\\Application->doRun(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#36 /Users/altama/Desktop/project/mires-sistem/vendor/laravel/framework/src/Illuminate/Console/Application.php(94): Symfony\\Component\\Console\\Application->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#37 /Users/altama/Desktop/project/mires-sistem/vendor/laravel/framework/src/Illuminate/Foundation/Console/Kernel.php(129): Illuminate\\Console\\Application->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#38 /Users/altama/Desktop/project/mires-sistem/artisan(37): Illuminate\\Foundation\\Console\\Kernel->handle(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#39 {main}','2023-05-28 16:57:59'),
	(5,'f4a1cbe9-d0c5-4852-8eeb-7b5823de07ef','database','default','{\"uuid\":\"f4a1cbe9-d0c5-4852-8eeb-7b5823de07ef\",\"displayName\":\"App\\\\Jobs\\\\v2\\\\Generate\\\\GeneratePdfJob\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\v2\\\\Generate\\\\GeneratePdfJob\",\"command\":\"O:35:\\\"App\\\\Jobs\\\\v2\\\\Generate\\\\GeneratePdfJob\\\":12:{s:13:\\\"\\u0000*\\u0000nama_modul\\\";s:20:\\\"permintaan-pembelian\\\";s:7:\\\"\\u0000*\\u0000data\\\";a:2:{s:10:\\\"permintaan\\\";O:43:\\\"App\\\\Models\\\\v2\\\\Pembelian\\\\PermintaanPembelian\\\":30:{s:13:\\\"\\u0000*\\u0000connection\\\";s:12:\\\"second_mysql\\\";s:8:\\\"\\u0000*\\u0000table\\\";s:20:\\\"permintaan_pembelian\\\";s:10:\\\"\\u0000*\\u0000guarded\\\";a:0:{}s:13:\\\"\\u0000*\\u0000primaryKey\\\";s:2:\\\"id\\\";s:10:\\\"\\u0000*\\u0000keyType\\\";s:3:\\\"int\\\";s:12:\\\"incrementing\\\";b:1;s:7:\\\"\\u0000*\\u0000with\\\";a:0:{}s:12:\\\"\\u0000*\\u0000withCount\\\";a:0:{}s:19:\\\"preventsLazyLoading\\\";b:0;s:10:\\\"\\u0000*\\u0000perPage\\\";i:15;s:6:\\\"exists\\\";b:1;s:18:\\\"wasRecentlyCreated\\\";b:1;s:28:\\\"\\u0000*\\u0000escapeWhenCastingToString\\\";b:0;s:13:\\\"\\u0000*\\u0000attributes\\\";a:8:{s:15:\\\"tipe_permintaan\\\";s:1:\\\"1\\\";s:26:\\\"nomer_permintaan_pembelian\\\";s:7:\\\"PR\\/1234\\\";s:7:\\\"tanggal\\\";s:10:\\\"2023-05-28\\\";s:10:\\\"keterangan\\\";N;s:10:\\\"created_by\\\";i:1;s:10:\\\"updated_at\\\";s:19:\\\"2023-05-28 16:59:42\\\";s:10:\\\"created_at\\\";s:19:\\\"2023-05-28 16:59:42\\\";s:2:\\\"id\\\";i:48;}s:11:\\\"\\u0000*\\u0000original\\\";a:8:{s:15:\\\"tipe_permintaan\\\";s:1:\\\"1\\\";s:26:\\\"nomer_permintaan_pembelian\\\";s:7:\\\"PR\\/1234\\\";s:7:\\\"tanggal\\\";s:10:\\\"2023-05-28\\\";s:10:\\\"keterangan\\\";N;s:10:\\\"created_by\\\";i:1;s:10:\\\"updated_at\\\";s:19:\\\"2023-05-28 16:59:42\\\";s:10:\\\"created_at\\\";s:19:\\\"2023-05-28 16:59:42\\\";s:2:\\\"id\\\";i:48;}s:10:\\\"\\u0000*\\u0000changes\\\";a:0:{}s:8:\\\"\\u0000*\\u0000casts\\\";a:0:{}s:17:\\\"\\u0000*\\u0000classCastCache\\\";a:0:{}s:21:\\\"\\u0000*\\u0000attributeCastCache\\\";a:0:{}s:8:\\\"\\u0000*\\u0000dates\\\";a:0:{}s:13:\\\"\\u0000*\\u0000dateFormat\\\";N;s:10:\\\"\\u0000*\\u0000appends\\\";a:0:{}s:19:\\\"\\u0000*\\u0000dispatchesEvents\\\";a:0:{}s:14:\\\"\\u0000*\\u0000observables\\\";a:0:{}s:12:\\\"\\u0000*\\u0000relations\\\";a:0:{}s:10:\\\"\\u0000*\\u0000touches\\\";a:0:{}s:10:\\\"timestamps\\\";b:1;s:9:\\\"\\u0000*\\u0000hidden\\\";a:0:{}s:10:\\\"\\u0000*\\u0000visible\\\";a:0:{}s:11:\\\"\\u0000*\\u0000fillable\\\";a:0:{}}s:8:\\\"filename\\\";s:11:\\\"PR_1234.pdf\\\";}s:3:\\\"job\\\";N;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:19:\\\"chainCatchCallbacks\\\";N;s:5:\\\"delay\\\";N;s:11:\\\"afterCommit\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}}\"},\"clockwork_id\":\"1685267982-6120-2047765062\",\"clockwork_parent_id\":\"1685267982-5760-107186059\"}','ErrorException: strtoupper() expects parameter 1 to be string, array given in /Users/altama/Desktop/project/mires-sistem/vendor/dompdf/dompdf/src/Dompdf.php:462\nStack trace:\n#0 [internal function]: Illuminate\\Foundation\\Bootstrap\\HandleExceptions->handleError(2, \'strtoupper() ex...\', \'/Users/altama/D...\', 462, Array)\n#1 /Users/altama/Desktop/project/mires-sistem/vendor/dompdf/dompdf/src/Dompdf.php(462): strtoupper(Array)\n#2 /Users/altama/Desktop/project/mires-sistem/vendor/barryvdh/laravel-dompdf/src/PDF.php(92): Dompdf\\Dompdf->loadHtml(\'v2.pembelian.pe...\', Array)\n#3 /Users/altama/Desktop/project/mires-sistem/vendor/barryvdh/laravel-dompdf/src/Facade.php(31): Barryvdh\\DomPDF\\PDF->loadHTML(\'v2.pembelian.pe...\', Array)\n#4 /Users/altama/Desktop/project/mires-sistem/app/Jobs/v2/Generate/GeneratePdfJob.php(43): Barryvdh\\DomPDF\\Facade::__callStatic(\'loadHTML\', Array)\n#5 /Users/altama/Desktop/project/mires-sistem/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(36): App\\Jobs\\v2\\Generate\\GeneratePdfJob->handle()\n#6 /Users/altama/Desktop/project/mires-sistem/vendor/laravel/framework/src/Illuminate/Container/Util.php(40): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()\n#7 /Users/altama/Desktop/project/mires-sistem/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(93): Illuminate\\Container\\Util::unwrapIfClosure(Object(Closure))\n#8 /Users/altama/Desktop/project/mires-sistem/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(37): Illuminate\\Container\\BoundMethod::callBoundMethod(Object(Illuminate\\Foundation\\Application), Array, Object(Closure))\n#9 /Users/altama/Desktop/project/mires-sistem/vendor/laravel/framework/src/Illuminate/Container/Container.php(653): Illuminate\\Container\\BoundMethod::call(Object(Illuminate\\Foundation\\Application), Array, Array, NULL)\n#10 /Users/altama/Desktop/project/mires-sistem/vendor/laravel/framework/src/Illuminate/Bus/Dispatcher.php(128): Illuminate\\Container\\Container->call(Array)\n#11 /Users/altama/Desktop/project/mires-sistem/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(128): Illuminate\\Bus\\Dispatcher->Illuminate\\Bus\\{closure}(Object(App\\Jobs\\v2\\Generate\\GeneratePdfJob))\n#12 /Users/altama/Desktop/project/mires-sistem/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(103): Illuminate\\Pipeline\\Pipeline->Illuminate\\Pipeline\\{closure}(Object(App\\Jobs\\v2\\Generate\\GeneratePdfJob))\n#13 /Users/altama/Desktop/project/mires-sistem/vendor/laravel/framework/src/Illuminate/Bus/Dispatcher.php(132): Illuminate\\Pipeline\\Pipeline->then(Object(Closure))\n#14 /Users/altama/Desktop/project/mires-sistem/vendor/laravel/framework/src/Illuminate/Queue/CallQueuedHandler.php(120): Illuminate\\Bus\\Dispatcher->dispatchNow(Object(App\\Jobs\\v2\\Generate\\GeneratePdfJob), false)\n#15 /Users/altama/Desktop/project/mires-sistem/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(128): Illuminate\\Queue\\CallQueuedHandler->Illuminate\\Queue\\{closure}(Object(App\\Jobs\\v2\\Generate\\GeneratePdfJob))\n#16 /Users/altama/Desktop/project/mires-sistem/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(103): Illuminate\\Pipeline\\Pipeline->Illuminate\\Pipeline\\{closure}(Object(App\\Jobs\\v2\\Generate\\GeneratePdfJob))\n#17 /Users/altama/Desktop/project/mires-sistem/vendor/laravel/framework/src/Illuminate/Queue/CallQueuedHandler.php(122): Illuminate\\Pipeline\\Pipeline->then(Object(Closure))\n#18 /Users/altama/Desktop/project/mires-sistem/vendor/laravel/framework/src/Illuminate/Queue/CallQueuedHandler.php(70): Illuminate\\Queue\\CallQueuedHandler->dispatchThroughMiddleware(Object(Illuminate\\Queue\\Jobs\\DatabaseJob), Object(App\\Jobs\\v2\\Generate\\GeneratePdfJob))\n#19 /Users/altama/Desktop/project/mires-sistem/vendor/laravel/framework/src/Illuminate/Queue/Jobs/Job.php(98): Illuminate\\Queue\\CallQueuedHandler->call(Object(Illuminate\\Queue\\Jobs\\DatabaseJob), Array)\n#20 /Users/altama/Desktop/project/mires-sistem/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(428): Illuminate\\Queue\\Jobs\\Job->fire()\n#21 /Users/altama/Desktop/project/mires-sistem/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(378): Illuminate\\Queue\\Worker->process(\'database\', Object(Illuminate\\Queue\\Jobs\\DatabaseJob), Object(Illuminate\\Queue\\WorkerOptions))\n#22 /Users/altama/Desktop/project/mires-sistem/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(172): Illuminate\\Queue\\Worker->runJob(Object(Illuminate\\Queue\\Jobs\\DatabaseJob), \'database\', Object(Illuminate\\Queue\\WorkerOptions))\n#23 /Users/altama/Desktop/project/mires-sistem/vendor/laravel/framework/src/Illuminate/Queue/Console/WorkCommand.php(117): Illuminate\\Queue\\Worker->daemon(\'database\', \'default\', Object(Illuminate\\Queue\\WorkerOptions))\n#24 /Users/altama/Desktop/project/mires-sistem/vendor/laravel/framework/src/Illuminate/Queue/Console/WorkCommand.php(101): Illuminate\\Queue\\Console\\WorkCommand->runWorker(\'database\', \'default\')\n#25 /Users/altama/Desktop/project/mires-sistem/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(36): Illuminate\\Queue\\Console\\WorkCommand->handle()\n#26 /Users/altama/Desktop/project/mires-sistem/vendor/laravel/framework/src/Illuminate/Container/Util.php(40): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()\n#27 /Users/altama/Desktop/project/mires-sistem/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(93): Illuminate\\Container\\Util::unwrapIfClosure(Object(Closure))\n#28 /Users/altama/Desktop/project/mires-sistem/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(37): Illuminate\\Container\\BoundMethod::callBoundMethod(Object(Illuminate\\Foundation\\Application), Array, Object(Closure))\n#29 /Users/altama/Desktop/project/mires-sistem/vendor/laravel/framework/src/Illuminate/Container/Container.php(653): Illuminate\\Container\\BoundMethod::call(Object(Illuminate\\Foundation\\Application), Array, Array, NULL)\n#30 /Users/altama/Desktop/project/mires-sistem/vendor/laravel/framework/src/Illuminate/Console/Command.php(136): Illuminate\\Container\\Container->call(Array)\n#31 /Users/altama/Desktop/project/mires-sistem/vendor/symfony/console/Command/Command.php(298): Illuminate\\Console\\Command->execute(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Illuminate\\Console\\OutputStyle))\n#32 /Users/altama/Desktop/project/mires-sistem/vendor/laravel/framework/src/Illuminate/Console/Command.php(121): Symfony\\Component\\Console\\Command\\Command->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Illuminate\\Console\\OutputStyle))\n#33 /Users/altama/Desktop/project/mires-sistem/vendor/symfony/console/Application.php(1040): Illuminate\\Console\\Command->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#34 /Users/altama/Desktop/project/mires-sistem/vendor/symfony/console/Application.php(301): Symfony\\Component\\Console\\Application->doRunCommand(Object(Illuminate\\Queue\\Console\\WorkCommand), Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#35 /Users/altama/Desktop/project/mires-sistem/vendor/symfony/console/Application.php(171): Symfony\\Component\\Console\\Application->doRun(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#36 /Users/altama/Desktop/project/mires-sistem/vendor/laravel/framework/src/Illuminate/Console/Application.php(94): Symfony\\Component\\Console\\Application->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#37 /Users/altama/Desktop/project/mires-sistem/vendor/laravel/framework/src/Illuminate/Foundation/Console/Kernel.php(129): Illuminate\\Console\\Application->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#38 /Users/altama/Desktop/project/mires-sistem/artisan(37): Illuminate\\Foundation\\Console\\Kernel->handle(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#39 {main}','2023-05-28 16:59:46'),
	(6,'1a7bb526-c42a-4941-b63e-39da6a85f107','database','default','{\"uuid\":\"1a7bb526-c42a-4941-b63e-39da6a85f107\",\"displayName\":\"App\\\\Jobs\\\\v2\\\\Generate\\\\GeneratePdfJob\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\v2\\\\Generate\\\\GeneratePdfJob\",\"command\":\"O:35:\\\"App\\\\Jobs\\\\v2\\\\Generate\\\\GeneratePdfJob\\\":12:{s:13:\\\"\\u0000*\\u0000nama_modul\\\";s:20:\\\"permintaan-pembelian\\\";s:7:\\\"\\u0000*\\u0000data\\\";a:2:{s:10:\\\"permintaan\\\";O:43:\\\"App\\\\Models\\\\v2\\\\Pembelian\\\\PermintaanPembelian\\\":30:{s:13:\\\"\\u0000*\\u0000connection\\\";s:12:\\\"second_mysql\\\";s:8:\\\"\\u0000*\\u0000table\\\";s:20:\\\"permintaan_pembelian\\\";s:10:\\\"\\u0000*\\u0000guarded\\\";a:0:{}s:13:\\\"\\u0000*\\u0000primaryKey\\\";s:2:\\\"id\\\";s:10:\\\"\\u0000*\\u0000keyType\\\";s:3:\\\"int\\\";s:12:\\\"incrementing\\\";b:1;s:7:\\\"\\u0000*\\u0000with\\\";a:0:{}s:12:\\\"\\u0000*\\u0000withCount\\\";a:0:{}s:19:\\\"preventsLazyLoading\\\";b:0;s:10:\\\"\\u0000*\\u0000perPage\\\";i:15;s:6:\\\"exists\\\";b:1;s:18:\\\"wasRecentlyCreated\\\";b:1;s:28:\\\"\\u0000*\\u0000escapeWhenCastingToString\\\";b:0;s:13:\\\"\\u0000*\\u0000attributes\\\";a:8:{s:15:\\\"tipe_permintaan\\\";s:1:\\\"1\\\";s:26:\\\"nomer_permintaan_pembelian\\\";s:8:\\\"PR\\/12345\\\";s:7:\\\"tanggal\\\";s:10:\\\"2023-05-28\\\";s:10:\\\"keterangan\\\";N;s:10:\\\"created_by\\\";i:1;s:10:\\\"updated_at\\\";s:19:\\\"2023-05-28 17:00:40\\\";s:10:\\\"created_at\\\";s:19:\\\"2023-05-28 17:00:40\\\";s:2:\\\"id\\\";i:49;}s:11:\\\"\\u0000*\\u0000original\\\";a:8:{s:15:\\\"tipe_permintaan\\\";s:1:\\\"1\\\";s:26:\\\"nomer_permintaan_pembelian\\\";s:8:\\\"PR\\/12345\\\";s:7:\\\"tanggal\\\";s:10:\\\"2023-05-28\\\";s:10:\\\"keterangan\\\";N;s:10:\\\"created_by\\\";i:1;s:10:\\\"updated_at\\\";s:19:\\\"2023-05-28 17:00:40\\\";s:10:\\\"created_at\\\";s:19:\\\"2023-05-28 17:00:40\\\";s:2:\\\"id\\\";i:49;}s:10:\\\"\\u0000*\\u0000changes\\\";a:0:{}s:8:\\\"\\u0000*\\u0000casts\\\";a:0:{}s:17:\\\"\\u0000*\\u0000classCastCache\\\";a:0:{}s:21:\\\"\\u0000*\\u0000attributeCastCache\\\";a:0:{}s:8:\\\"\\u0000*\\u0000dates\\\";a:0:{}s:13:\\\"\\u0000*\\u0000dateFormat\\\";N;s:10:\\\"\\u0000*\\u0000appends\\\";a:0:{}s:19:\\\"\\u0000*\\u0000dispatchesEvents\\\";a:0:{}s:14:\\\"\\u0000*\\u0000observables\\\";a:0:{}s:12:\\\"\\u0000*\\u0000relations\\\";a:0:{}s:10:\\\"\\u0000*\\u0000touches\\\";a:0:{}s:10:\\\"timestamps\\\";b:1;s:9:\\\"\\u0000*\\u0000hidden\\\";a:0:{}s:10:\\\"\\u0000*\\u0000visible\\\";a:0:{}s:11:\\\"\\u0000*\\u0000fillable\\\";a:0:{}}s:8:\\\"filename\\\";s:12:\\\"PR_12345.pdf\\\";}s:3:\\\"job\\\";N;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:19:\\\"chainCatchCallbacks\\\";N;s:5:\\\"delay\\\";N;s:11:\\\"afterCommit\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}}\"},\"clockwork_id\":\"1685268040-2004-1505766260\",\"clockwork_parent_id\":\"1685268040-1570-1918277679\"}','ErrorException: strtoupper() expects parameter 1 to be string, array given in /Users/altama/Desktop/project/mires-sistem/vendor/dompdf/dompdf/src/Dompdf.php:462\nStack trace:\n#0 [internal function]: Illuminate\\Foundation\\Bootstrap\\HandleExceptions->handleError(2, \'strtoupper() ex...\', \'/Users/altama/D...\', 462, Array)\n#1 /Users/altama/Desktop/project/mires-sistem/vendor/dompdf/dompdf/src/Dompdf.php(462): strtoupper(Array)\n#2 /Users/altama/Desktop/project/mires-sistem/vendor/barryvdh/laravel-dompdf/src/PDF.php(92): Dompdf\\Dompdf->loadHtml(\'v2.pembelian.pe...\', Array)\n#3 /Users/altama/Desktop/project/mires-sistem/vendor/barryvdh/laravel-dompdf/src/Facade.php(31): Barryvdh\\DomPDF\\PDF->loadHTML(\'v2.pembelian.pe...\', Array)\n#4 /Users/altama/Desktop/project/mires-sistem/app/Jobs/v2/Generate/GeneratePdfJob.php(43): Barryvdh\\DomPDF\\Facade::__callStatic(\'loadHTML\', Array)\n#5 /Users/altama/Desktop/project/mires-sistem/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(36): App\\Jobs\\v2\\Generate\\GeneratePdfJob->handle()\n#6 /Users/altama/Desktop/project/mires-sistem/vendor/laravel/framework/src/Illuminate/Container/Util.php(40): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()\n#7 /Users/altama/Desktop/project/mires-sistem/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(93): Illuminate\\Container\\Util::unwrapIfClosure(Object(Closure))\n#8 /Users/altama/Desktop/project/mires-sistem/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(37): Illuminate\\Container\\BoundMethod::callBoundMethod(Object(Illuminate\\Foundation\\Application), Array, Object(Closure))\n#9 /Users/altama/Desktop/project/mires-sistem/vendor/laravel/framework/src/Illuminate/Container/Container.php(653): Illuminate\\Container\\BoundMethod::call(Object(Illuminate\\Foundation\\Application), Array, Array, NULL)\n#10 /Users/altama/Desktop/project/mires-sistem/vendor/laravel/framework/src/Illuminate/Bus/Dispatcher.php(128): Illuminate\\Container\\Container->call(Array)\n#11 /Users/altama/Desktop/project/mires-sistem/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(128): Illuminate\\Bus\\Dispatcher->Illuminate\\Bus\\{closure}(Object(App\\Jobs\\v2\\Generate\\GeneratePdfJob))\n#12 /Users/altama/Desktop/project/mires-sistem/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(103): Illuminate\\Pipeline\\Pipeline->Illuminate\\Pipeline\\{closure}(Object(App\\Jobs\\v2\\Generate\\GeneratePdfJob))\n#13 /Users/altama/Desktop/project/mires-sistem/vendor/laravel/framework/src/Illuminate/Bus/Dispatcher.php(132): Illuminate\\Pipeline\\Pipeline->then(Object(Closure))\n#14 /Users/altama/Desktop/project/mires-sistem/vendor/laravel/framework/src/Illuminate/Queue/CallQueuedHandler.php(120): Illuminate\\Bus\\Dispatcher->dispatchNow(Object(App\\Jobs\\v2\\Generate\\GeneratePdfJob), false)\n#15 /Users/altama/Desktop/project/mires-sistem/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(128): Illuminate\\Queue\\CallQueuedHandler->Illuminate\\Queue\\{closure}(Object(App\\Jobs\\v2\\Generate\\GeneratePdfJob))\n#16 /Users/altama/Desktop/project/mires-sistem/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(103): Illuminate\\Pipeline\\Pipeline->Illuminate\\Pipeline\\{closure}(Object(App\\Jobs\\v2\\Generate\\GeneratePdfJob))\n#17 /Users/altama/Desktop/project/mires-sistem/vendor/laravel/framework/src/Illuminate/Queue/CallQueuedHandler.php(122): Illuminate\\Pipeline\\Pipeline->then(Object(Closure))\n#18 /Users/altama/Desktop/project/mires-sistem/vendor/laravel/framework/src/Illuminate/Queue/CallQueuedHandler.php(70): Illuminate\\Queue\\CallQueuedHandler->dispatchThroughMiddleware(Object(Illuminate\\Queue\\Jobs\\DatabaseJob), Object(App\\Jobs\\v2\\Generate\\GeneratePdfJob))\n#19 /Users/altama/Desktop/project/mires-sistem/vendor/laravel/framework/src/Illuminate/Queue/Jobs/Job.php(98): Illuminate\\Queue\\CallQueuedHandler->call(Object(Illuminate\\Queue\\Jobs\\DatabaseJob), Array)\n#20 /Users/altama/Desktop/project/mires-sistem/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(428): Illuminate\\Queue\\Jobs\\Job->fire()\n#21 /Users/altama/Desktop/project/mires-sistem/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(378): Illuminate\\Queue\\Worker->process(\'database\', Object(Illuminate\\Queue\\Jobs\\DatabaseJob), Object(Illuminate\\Queue\\WorkerOptions))\n#22 /Users/altama/Desktop/project/mires-sistem/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(172): Illuminate\\Queue\\Worker->runJob(Object(Illuminate\\Queue\\Jobs\\DatabaseJob), \'database\', Object(Illuminate\\Queue\\WorkerOptions))\n#23 /Users/altama/Desktop/project/mires-sistem/vendor/laravel/framework/src/Illuminate/Queue/Console/WorkCommand.php(117): Illuminate\\Queue\\Worker->daemon(\'database\', \'default\', Object(Illuminate\\Queue\\WorkerOptions))\n#24 /Users/altama/Desktop/project/mires-sistem/vendor/laravel/framework/src/Illuminate/Queue/Console/WorkCommand.php(101): Illuminate\\Queue\\Console\\WorkCommand->runWorker(\'database\', \'default\')\n#25 /Users/altama/Desktop/project/mires-sistem/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(36): Illuminate\\Queue\\Console\\WorkCommand->handle()\n#26 /Users/altama/Desktop/project/mires-sistem/vendor/laravel/framework/src/Illuminate/Container/Util.php(40): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()\n#27 /Users/altama/Desktop/project/mires-sistem/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(93): Illuminate\\Container\\Util::unwrapIfClosure(Object(Closure))\n#28 /Users/altama/Desktop/project/mires-sistem/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(37): Illuminate\\Container\\BoundMethod::callBoundMethod(Object(Illuminate\\Foundation\\Application), Array, Object(Closure))\n#29 /Users/altama/Desktop/project/mires-sistem/vendor/laravel/framework/src/Illuminate/Container/Container.php(653): Illuminate\\Container\\BoundMethod::call(Object(Illuminate\\Foundation\\Application), Array, Array, NULL)\n#30 /Users/altama/Desktop/project/mires-sistem/vendor/laravel/framework/src/Illuminate/Console/Command.php(136): Illuminate\\Container\\Container->call(Array)\n#31 /Users/altama/Desktop/project/mires-sistem/vendor/symfony/console/Command/Command.php(298): Illuminate\\Console\\Command->execute(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Illuminate\\Console\\OutputStyle))\n#32 /Users/altama/Desktop/project/mires-sistem/vendor/laravel/framework/src/Illuminate/Console/Command.php(121): Symfony\\Component\\Console\\Command\\Command->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Illuminate\\Console\\OutputStyle))\n#33 /Users/altama/Desktop/project/mires-sistem/vendor/symfony/console/Application.php(1040): Illuminate\\Console\\Command->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#34 /Users/altama/Desktop/project/mires-sistem/vendor/symfony/console/Application.php(301): Symfony\\Component\\Console\\Application->doRunCommand(Object(Illuminate\\Queue\\Console\\WorkCommand), Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#35 /Users/altama/Desktop/project/mires-sistem/vendor/symfony/console/Application.php(171): Symfony\\Component\\Console\\Application->doRun(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#36 /Users/altama/Desktop/project/mires-sistem/vendor/laravel/framework/src/Illuminate/Console/Application.php(94): Symfony\\Component\\Console\\Application->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#37 /Users/altama/Desktop/project/mires-sistem/vendor/laravel/framework/src/Illuminate/Foundation/Console/Kernel.php(129): Illuminate\\Console\\Application->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#38 /Users/altama/Desktop/project/mires-sistem/artisan(37): Illuminate\\Foundation\\Console\\Kernel->handle(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#39 {main}','2023-05-28 17:00:41'),
	(7,'b8916923-811b-4cc4-a3fc-2fd5e46cd877','database','default','{\"uuid\":\"b8916923-811b-4cc4-a3fc-2fd5e46cd877\",\"displayName\":\"App\\\\Jobs\\\\v2\\\\Generate\\\\GeneratePdfJob\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\v2\\\\Generate\\\\GeneratePdfJob\",\"command\":\"O:35:\\\"App\\\\Jobs\\\\v2\\\\Generate\\\\GeneratePdfJob\\\":12:{s:13:\\\"\\u0000*\\u0000nama_modul\\\";s:20:\\\"permintaan-pembelian\\\";s:7:\\\"\\u0000*\\u0000data\\\";a:2:{s:10:\\\"permintaan\\\";O:43:\\\"App\\\\Models\\\\v2\\\\Pembelian\\\\PermintaanPembelian\\\":30:{s:13:\\\"\\u0000*\\u0000connection\\\";s:12:\\\"second_mysql\\\";s:8:\\\"\\u0000*\\u0000table\\\";s:20:\\\"permintaan_pembelian\\\";s:10:\\\"\\u0000*\\u0000guarded\\\";a:0:{}s:13:\\\"\\u0000*\\u0000primaryKey\\\";s:2:\\\"id\\\";s:10:\\\"\\u0000*\\u0000keyType\\\";s:3:\\\"int\\\";s:12:\\\"incrementing\\\";b:1;s:7:\\\"\\u0000*\\u0000with\\\";a:0:{}s:12:\\\"\\u0000*\\u0000withCount\\\";a:0:{}s:19:\\\"preventsLazyLoading\\\";b:0;s:10:\\\"\\u0000*\\u0000perPage\\\";i:15;s:6:\\\"exists\\\";b:1;s:18:\\\"wasRecentlyCreated\\\";b:1;s:28:\\\"\\u0000*\\u0000escapeWhenCastingToString\\\";b:0;s:13:\\\"\\u0000*\\u0000attributes\\\";a:8:{s:15:\\\"tipe_permintaan\\\";s:1:\\\"1\\\";s:26:\\\"nomer_permintaan_pembelian\\\";s:7:\\\"PR\\/2314\\\";s:7:\\\"tanggal\\\";s:10:\\\"2023-05-28\\\";s:10:\\\"keterangan\\\";N;s:10:\\\"created_by\\\";i:1;s:10:\\\"updated_at\\\";s:19:\\\"2023-05-28 17:01:38\\\";s:10:\\\"created_at\\\";s:19:\\\"2023-05-28 17:01:38\\\";s:2:\\\"id\\\";i:50;}s:11:\\\"\\u0000*\\u0000original\\\";a:8:{s:15:\\\"tipe_permintaan\\\";s:1:\\\"1\\\";s:26:\\\"nomer_permintaan_pembelian\\\";s:7:\\\"PR\\/2314\\\";s:7:\\\"tanggal\\\";s:10:\\\"2023-05-28\\\";s:10:\\\"keterangan\\\";N;s:10:\\\"created_by\\\";i:1;s:10:\\\"updated_at\\\";s:19:\\\"2023-05-28 17:01:38\\\";s:10:\\\"created_at\\\";s:19:\\\"2023-05-28 17:01:38\\\";s:2:\\\"id\\\";i:50;}s:10:\\\"\\u0000*\\u0000changes\\\";a:0:{}s:8:\\\"\\u0000*\\u0000casts\\\";a:0:{}s:17:\\\"\\u0000*\\u0000classCastCache\\\";a:0:{}s:21:\\\"\\u0000*\\u0000attributeCastCache\\\";a:0:{}s:8:\\\"\\u0000*\\u0000dates\\\";a:0:{}s:13:\\\"\\u0000*\\u0000dateFormat\\\";N;s:10:\\\"\\u0000*\\u0000appends\\\";a:0:{}s:19:\\\"\\u0000*\\u0000dispatchesEvents\\\";a:0:{}s:14:\\\"\\u0000*\\u0000observables\\\";a:0:{}s:12:\\\"\\u0000*\\u0000relations\\\";a:0:{}s:10:\\\"\\u0000*\\u0000touches\\\";a:0:{}s:10:\\\"timestamps\\\";b:1;s:9:\\\"\\u0000*\\u0000hidden\\\";a:0:{}s:10:\\\"\\u0000*\\u0000visible\\\";a:0:{}s:11:\\\"\\u0000*\\u0000fillable\\\";a:0:{}}s:8:\\\"filename\\\";s:11:\\\"PR_2314.pdf\\\";}s:3:\\\"job\\\";N;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:19:\\\"chainCatchCallbacks\\\";N;s:5:\\\"delay\\\";N;s:11:\\\"afterCommit\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}}\"},\"clockwork_id\":\"1685268098-5559-430240072\",\"clockwork_parent_id\":\"1685268098-5182-486997177\"}','ErrorException: strtoupper() expects parameter 1 to be string, array given in /Users/altama/Desktop/project/mires-sistem/vendor/dompdf/dompdf/src/Dompdf.php:462\nStack trace:\n#0 [internal function]: Illuminate\\Foundation\\Bootstrap\\HandleExceptions->handleError(2, \'strtoupper() ex...\', \'/Users/altama/D...\', 462, Array)\n#1 /Users/altama/Desktop/project/mires-sistem/vendor/dompdf/dompdf/src/Dompdf.php(462): strtoupper(Array)\n#2 /Users/altama/Desktop/project/mires-sistem/vendor/barryvdh/laravel-dompdf/src/PDF.php(92): Dompdf\\Dompdf->loadHtml(\'v2.pembelian.pe...\', Array)\n#3 /Users/altama/Desktop/project/mires-sistem/vendor/barryvdh/laravel-dompdf/src/Facade.php(31): Barryvdh\\DomPDF\\PDF->loadHTML(\'v2.pembelian.pe...\', Array)\n#4 /Users/altama/Desktop/project/mires-sistem/app/Jobs/v2/Generate/GeneratePdfJob.php(43): Barryvdh\\DomPDF\\Facade::__callStatic(\'loadHTML\', Array)\n#5 /Users/altama/Desktop/project/mires-sistem/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(36): App\\Jobs\\v2\\Generate\\GeneratePdfJob->handle()\n#6 /Users/altama/Desktop/project/mires-sistem/vendor/laravel/framework/src/Illuminate/Container/Util.php(40): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()\n#7 /Users/altama/Desktop/project/mires-sistem/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(93): Illuminate\\Container\\Util::unwrapIfClosure(Object(Closure))\n#8 /Users/altama/Desktop/project/mires-sistem/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(37): Illuminate\\Container\\BoundMethod::callBoundMethod(Object(Illuminate\\Foundation\\Application), Array, Object(Closure))\n#9 /Users/altama/Desktop/project/mires-sistem/vendor/laravel/framework/src/Illuminate/Container/Container.php(653): Illuminate\\Container\\BoundMethod::call(Object(Illuminate\\Foundation\\Application), Array, Array, NULL)\n#10 /Users/altama/Desktop/project/mires-sistem/vendor/laravel/framework/src/Illuminate/Bus/Dispatcher.php(128): Illuminate\\Container\\Container->call(Array)\n#11 /Users/altama/Desktop/project/mires-sistem/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(128): Illuminate\\Bus\\Dispatcher->Illuminate\\Bus\\{closure}(Object(App\\Jobs\\v2\\Generate\\GeneratePdfJob))\n#12 /Users/altama/Desktop/project/mires-sistem/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(103): Illuminate\\Pipeline\\Pipeline->Illuminate\\Pipeline\\{closure}(Object(App\\Jobs\\v2\\Generate\\GeneratePdfJob))\n#13 /Users/altama/Desktop/project/mires-sistem/vendor/laravel/framework/src/Illuminate/Bus/Dispatcher.php(132): Illuminate\\Pipeline\\Pipeline->then(Object(Closure))\n#14 /Users/altama/Desktop/project/mires-sistem/vendor/laravel/framework/src/Illuminate/Queue/CallQueuedHandler.php(120): Illuminate\\Bus\\Dispatcher->dispatchNow(Object(App\\Jobs\\v2\\Generate\\GeneratePdfJob), false)\n#15 /Users/altama/Desktop/project/mires-sistem/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(128): Illuminate\\Queue\\CallQueuedHandler->Illuminate\\Queue\\{closure}(Object(App\\Jobs\\v2\\Generate\\GeneratePdfJob))\n#16 /Users/altama/Desktop/project/mires-sistem/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(103): Illuminate\\Pipeline\\Pipeline->Illuminate\\Pipeline\\{closure}(Object(App\\Jobs\\v2\\Generate\\GeneratePdfJob))\n#17 /Users/altama/Desktop/project/mires-sistem/vendor/laravel/framework/src/Illuminate/Queue/CallQueuedHandler.php(122): Illuminate\\Pipeline\\Pipeline->then(Object(Closure))\n#18 /Users/altama/Desktop/project/mires-sistem/vendor/laravel/framework/src/Illuminate/Queue/CallQueuedHandler.php(70): Illuminate\\Queue\\CallQueuedHandler->dispatchThroughMiddleware(Object(Illuminate\\Queue\\Jobs\\DatabaseJob), Object(App\\Jobs\\v2\\Generate\\GeneratePdfJob))\n#19 /Users/altama/Desktop/project/mires-sistem/vendor/laravel/framework/src/Illuminate/Queue/Jobs/Job.php(98): Illuminate\\Queue\\CallQueuedHandler->call(Object(Illuminate\\Queue\\Jobs\\DatabaseJob), Array)\n#20 /Users/altama/Desktop/project/mires-sistem/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(428): Illuminate\\Queue\\Jobs\\Job->fire()\n#21 /Users/altama/Desktop/project/mires-sistem/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(378): Illuminate\\Queue\\Worker->process(\'database\', Object(Illuminate\\Queue\\Jobs\\DatabaseJob), Object(Illuminate\\Queue\\WorkerOptions))\n#22 /Users/altama/Desktop/project/mires-sistem/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(172): Illuminate\\Queue\\Worker->runJob(Object(Illuminate\\Queue\\Jobs\\DatabaseJob), \'database\', Object(Illuminate\\Queue\\WorkerOptions))\n#23 /Users/altama/Desktop/project/mires-sistem/vendor/laravel/framework/src/Illuminate/Queue/Console/WorkCommand.php(117): Illuminate\\Queue\\Worker->daemon(\'database\', \'default\', Object(Illuminate\\Queue\\WorkerOptions))\n#24 /Users/altama/Desktop/project/mires-sistem/vendor/laravel/framework/src/Illuminate/Queue/Console/WorkCommand.php(101): Illuminate\\Queue\\Console\\WorkCommand->runWorker(\'database\', \'default\')\n#25 /Users/altama/Desktop/project/mires-sistem/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(36): Illuminate\\Queue\\Console\\WorkCommand->handle()\n#26 /Users/altama/Desktop/project/mires-sistem/vendor/laravel/framework/src/Illuminate/Container/Util.php(40): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()\n#27 /Users/altama/Desktop/project/mires-sistem/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(93): Illuminate\\Container\\Util::unwrapIfClosure(Object(Closure))\n#28 /Users/altama/Desktop/project/mires-sistem/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(37): Illuminate\\Container\\BoundMethod::callBoundMethod(Object(Illuminate\\Foundation\\Application), Array, Object(Closure))\n#29 /Users/altama/Desktop/project/mires-sistem/vendor/laravel/framework/src/Illuminate/Container/Container.php(653): Illuminate\\Container\\BoundMethod::call(Object(Illuminate\\Foundation\\Application), Array, Array, NULL)\n#30 /Users/altama/Desktop/project/mires-sistem/vendor/laravel/framework/src/Illuminate/Console/Command.php(136): Illuminate\\Container\\Container->call(Array)\n#31 /Users/altama/Desktop/project/mires-sistem/vendor/symfony/console/Command/Command.php(298): Illuminate\\Console\\Command->execute(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Illuminate\\Console\\OutputStyle))\n#32 /Users/altama/Desktop/project/mires-sistem/vendor/laravel/framework/src/Illuminate/Console/Command.php(121): Symfony\\Component\\Console\\Command\\Command->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Illuminate\\Console\\OutputStyle))\n#33 /Users/altama/Desktop/project/mires-sistem/vendor/symfony/console/Application.php(1040): Illuminate\\Console\\Command->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#34 /Users/altama/Desktop/project/mires-sistem/vendor/symfony/console/Application.php(301): Symfony\\Component\\Console\\Application->doRunCommand(Object(Illuminate\\Queue\\Console\\WorkCommand), Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#35 /Users/altama/Desktop/project/mires-sistem/vendor/symfony/console/Application.php(171): Symfony\\Component\\Console\\Application->doRun(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#36 /Users/altama/Desktop/project/mires-sistem/vendor/laravel/framework/src/Illuminate/Console/Application.php(94): Symfony\\Component\\Console\\Application->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#37 /Users/altama/Desktop/project/mires-sistem/vendor/laravel/framework/src/Illuminate/Foundation/Console/Kernel.php(129): Illuminate\\Console\\Application->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#38 /Users/altama/Desktop/project/mires-sistem/artisan(37): Illuminate\\Foundation\\Console\\Kernel->handle(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#39 {main}','2023-05-28 17:01:41'),
	(8,'18703083-3dfa-4db7-9b1b-fbe230d22b7f','database','default','{\"uuid\":\"18703083-3dfa-4db7-9b1b-fbe230d22b7f\",\"displayName\":\"App\\\\Jobs\\\\v2\\\\Generate\\\\GeneratePdfJob\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"App\\\\Jobs\\\\v2\\\\Generate\\\\GeneratePdfJob\",\"command\":\"O:35:\\\"App\\\\Jobs\\\\v2\\\\Generate\\\\GeneratePdfJob\\\":12:{s:13:\\\"\\u0000*\\u0000nama_modul\\\";s:20:\\\"permintaan-pembelian\\\";s:7:\\\"\\u0000*\\u0000data\\\";a:2:{s:10:\\\"permintaan\\\";O:43:\\\"App\\\\Models\\\\v2\\\\Pembelian\\\\PermintaanPembelian\\\":30:{s:13:\\\"\\u0000*\\u0000connection\\\";s:12:\\\"second_mysql\\\";s:8:\\\"\\u0000*\\u0000table\\\";s:20:\\\"permintaan_pembelian\\\";s:10:\\\"\\u0000*\\u0000guarded\\\";a:0:{}s:13:\\\"\\u0000*\\u0000primaryKey\\\";s:2:\\\"id\\\";s:10:\\\"\\u0000*\\u0000keyType\\\";s:3:\\\"int\\\";s:12:\\\"incrementing\\\";b:1;s:7:\\\"\\u0000*\\u0000with\\\";a:0:{}s:12:\\\"\\u0000*\\u0000withCount\\\";a:0:{}s:19:\\\"preventsLazyLoading\\\";b:0;s:10:\\\"\\u0000*\\u0000perPage\\\";i:15;s:6:\\\"exists\\\";b:1;s:18:\\\"wasRecentlyCreated\\\";b:1;s:28:\\\"\\u0000*\\u0000escapeWhenCastingToString\\\";b:0;s:13:\\\"\\u0000*\\u0000attributes\\\";a:8:{s:15:\\\"tipe_permintaan\\\";s:1:\\\"1\\\";s:26:\\\"nomer_permintaan_pembelian\\\";s:4:\\\"pr\\/1\\\";s:7:\\\"tanggal\\\";s:10:\\\"2023-05-28\\\";s:10:\\\"keterangan\\\";N;s:10:\\\"created_by\\\";i:1;s:10:\\\"updated_at\\\";s:19:\\\"2023-05-28 17:05:37\\\";s:10:\\\"created_at\\\";s:19:\\\"2023-05-28 17:05:37\\\";s:2:\\\"id\\\";i:51;}s:11:\\\"\\u0000*\\u0000original\\\";a:8:{s:15:\\\"tipe_permintaan\\\";s:1:\\\"1\\\";s:26:\\\"nomer_permintaan_pembelian\\\";s:4:\\\"pr\\/1\\\";s:7:\\\"tanggal\\\";s:10:\\\"2023-05-28\\\";s:10:\\\"keterangan\\\";N;s:10:\\\"created_by\\\";i:1;s:10:\\\"updated_at\\\";s:19:\\\"2023-05-28 17:05:37\\\";s:10:\\\"created_at\\\";s:19:\\\"2023-05-28 17:05:37\\\";s:2:\\\"id\\\";i:51;}s:10:\\\"\\u0000*\\u0000changes\\\";a:0:{}s:8:\\\"\\u0000*\\u0000casts\\\";a:0:{}s:17:\\\"\\u0000*\\u0000classCastCache\\\";a:0:{}s:21:\\\"\\u0000*\\u0000attributeCastCache\\\";a:0:{}s:8:\\\"\\u0000*\\u0000dates\\\";a:0:{}s:13:\\\"\\u0000*\\u0000dateFormat\\\";N;s:10:\\\"\\u0000*\\u0000appends\\\";a:0:{}s:19:\\\"\\u0000*\\u0000dispatchesEvents\\\";a:0:{}s:14:\\\"\\u0000*\\u0000observables\\\";a:0:{}s:12:\\\"\\u0000*\\u0000relations\\\";a:0:{}s:10:\\\"\\u0000*\\u0000touches\\\";a:0:{}s:10:\\\"timestamps\\\";b:1;s:9:\\\"\\u0000*\\u0000hidden\\\";a:0:{}s:10:\\\"\\u0000*\\u0000visible\\\";a:0:{}s:11:\\\"\\u0000*\\u0000fillable\\\";a:0:{}}s:8:\\\"filename\\\";s:8:\\\"pr_1.pdf\\\";}s:3:\\\"job\\\";N;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:19:\\\"chainCatchCallbacks\\\";N;s:5:\\\"delay\\\";N;s:11:\\\"afterCommit\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}}\"},\"clockwork_id\":\"1685268337-6810-1035459669\",\"clockwork_parent_id\":\"1685268337-6399-1339004736\"}','ErrorException: strtoupper() expects parameter 1 to be string, array given in /Users/altama/Desktop/project/mires-sistem/vendor/dompdf/dompdf/src/Dompdf.php:462\nStack trace:\n#0 [internal function]: Illuminate\\Foundation\\Bootstrap\\HandleExceptions->handleError(2, \'strtoupper() ex...\', \'/Users/altama/D...\', 462, Array)\n#1 /Users/altama/Desktop/project/mires-sistem/vendor/dompdf/dompdf/src/Dompdf.php(462): strtoupper(Array)\n#2 /Users/altama/Desktop/project/mires-sistem/vendor/barryvdh/laravel-dompdf/src/PDF.php(92): Dompdf\\Dompdf->loadHtml(\'v2.pembelian.pe...\', Array)\n#3 /Users/altama/Desktop/project/mires-sistem/vendor/barryvdh/laravel-dompdf/src/Facade.php(31): Barryvdh\\DomPDF\\PDF->loadHTML(\'v2.pembelian.pe...\', Array)\n#4 /Users/altama/Desktop/project/mires-sistem/app/Jobs/v2/Generate/GeneratePdfJob.php(43): Barryvdh\\DomPDF\\Facade::__callStatic(\'loadHTML\', Array)\n#5 /Users/altama/Desktop/project/mires-sistem/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(36): App\\Jobs\\v2\\Generate\\GeneratePdfJob->handle()\n#6 /Users/altama/Desktop/project/mires-sistem/vendor/laravel/framework/src/Illuminate/Container/Util.php(40): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()\n#7 /Users/altama/Desktop/project/mires-sistem/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(93): Illuminate\\Container\\Util::unwrapIfClosure(Object(Closure))\n#8 /Users/altama/Desktop/project/mires-sistem/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(37): Illuminate\\Container\\BoundMethod::callBoundMethod(Object(Illuminate\\Foundation\\Application), Array, Object(Closure))\n#9 /Users/altama/Desktop/project/mires-sistem/vendor/laravel/framework/src/Illuminate/Container/Container.php(653): Illuminate\\Container\\BoundMethod::call(Object(Illuminate\\Foundation\\Application), Array, Array, NULL)\n#10 /Users/altama/Desktop/project/mires-sistem/vendor/laravel/framework/src/Illuminate/Bus/Dispatcher.php(128): Illuminate\\Container\\Container->call(Array)\n#11 /Users/altama/Desktop/project/mires-sistem/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(128): Illuminate\\Bus\\Dispatcher->Illuminate\\Bus\\{closure}(Object(App\\Jobs\\v2\\Generate\\GeneratePdfJob))\n#12 /Users/altama/Desktop/project/mires-sistem/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(103): Illuminate\\Pipeline\\Pipeline->Illuminate\\Pipeline\\{closure}(Object(App\\Jobs\\v2\\Generate\\GeneratePdfJob))\n#13 /Users/altama/Desktop/project/mires-sistem/vendor/laravel/framework/src/Illuminate/Bus/Dispatcher.php(132): Illuminate\\Pipeline\\Pipeline->then(Object(Closure))\n#14 /Users/altama/Desktop/project/mires-sistem/vendor/laravel/framework/src/Illuminate/Queue/CallQueuedHandler.php(120): Illuminate\\Bus\\Dispatcher->dispatchNow(Object(App\\Jobs\\v2\\Generate\\GeneratePdfJob), false)\n#15 /Users/altama/Desktop/project/mires-sistem/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(128): Illuminate\\Queue\\CallQueuedHandler->Illuminate\\Queue\\{closure}(Object(App\\Jobs\\v2\\Generate\\GeneratePdfJob))\n#16 /Users/altama/Desktop/project/mires-sistem/vendor/laravel/framework/src/Illuminate/Pipeline/Pipeline.php(103): Illuminate\\Pipeline\\Pipeline->Illuminate\\Pipeline\\{closure}(Object(App\\Jobs\\v2\\Generate\\GeneratePdfJob))\n#17 /Users/altama/Desktop/project/mires-sistem/vendor/laravel/framework/src/Illuminate/Queue/CallQueuedHandler.php(122): Illuminate\\Pipeline\\Pipeline->then(Object(Closure))\n#18 /Users/altama/Desktop/project/mires-sistem/vendor/laravel/framework/src/Illuminate/Queue/CallQueuedHandler.php(70): Illuminate\\Queue\\CallQueuedHandler->dispatchThroughMiddleware(Object(Illuminate\\Queue\\Jobs\\DatabaseJob), Object(App\\Jobs\\v2\\Generate\\GeneratePdfJob))\n#19 /Users/altama/Desktop/project/mires-sistem/vendor/laravel/framework/src/Illuminate/Queue/Jobs/Job.php(98): Illuminate\\Queue\\CallQueuedHandler->call(Object(Illuminate\\Queue\\Jobs\\DatabaseJob), Array)\n#20 /Users/altama/Desktop/project/mires-sistem/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(428): Illuminate\\Queue\\Jobs\\Job->fire()\n#21 /Users/altama/Desktop/project/mires-sistem/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(378): Illuminate\\Queue\\Worker->process(\'database\', Object(Illuminate\\Queue\\Jobs\\DatabaseJob), Object(Illuminate\\Queue\\WorkerOptions))\n#22 /Users/altama/Desktop/project/mires-sistem/vendor/laravel/framework/src/Illuminate/Queue/Worker.php(172): Illuminate\\Queue\\Worker->runJob(Object(Illuminate\\Queue\\Jobs\\DatabaseJob), \'database\', Object(Illuminate\\Queue\\WorkerOptions))\n#23 /Users/altama/Desktop/project/mires-sistem/vendor/laravel/framework/src/Illuminate/Queue/Console/WorkCommand.php(117): Illuminate\\Queue\\Worker->daemon(\'database\', \'default\', Object(Illuminate\\Queue\\WorkerOptions))\n#24 /Users/altama/Desktop/project/mires-sistem/vendor/laravel/framework/src/Illuminate/Queue/Console/WorkCommand.php(101): Illuminate\\Queue\\Console\\WorkCommand->runWorker(\'database\', \'default\')\n#25 /Users/altama/Desktop/project/mires-sistem/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(36): Illuminate\\Queue\\Console\\WorkCommand->handle()\n#26 /Users/altama/Desktop/project/mires-sistem/vendor/laravel/framework/src/Illuminate/Container/Util.php(40): Illuminate\\Container\\BoundMethod::Illuminate\\Container\\{closure}()\n#27 /Users/altama/Desktop/project/mires-sistem/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(93): Illuminate\\Container\\Util::unwrapIfClosure(Object(Closure))\n#28 /Users/altama/Desktop/project/mires-sistem/vendor/laravel/framework/src/Illuminate/Container/BoundMethod.php(37): Illuminate\\Container\\BoundMethod::callBoundMethod(Object(Illuminate\\Foundation\\Application), Array, Object(Closure))\n#29 /Users/altama/Desktop/project/mires-sistem/vendor/laravel/framework/src/Illuminate/Container/Container.php(653): Illuminate\\Container\\BoundMethod::call(Object(Illuminate\\Foundation\\Application), Array, Array, NULL)\n#30 /Users/altama/Desktop/project/mires-sistem/vendor/laravel/framework/src/Illuminate/Console/Command.php(136): Illuminate\\Container\\Container->call(Array)\n#31 /Users/altama/Desktop/project/mires-sistem/vendor/symfony/console/Command/Command.php(298): Illuminate\\Console\\Command->execute(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Illuminate\\Console\\OutputStyle))\n#32 /Users/altama/Desktop/project/mires-sistem/vendor/laravel/framework/src/Illuminate/Console/Command.php(121): Symfony\\Component\\Console\\Command\\Command->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Illuminate\\Console\\OutputStyle))\n#33 /Users/altama/Desktop/project/mires-sistem/vendor/symfony/console/Application.php(1040): Illuminate\\Console\\Command->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#34 /Users/altama/Desktop/project/mires-sistem/vendor/symfony/console/Application.php(301): Symfony\\Component\\Console\\Application->doRunCommand(Object(Illuminate\\Queue\\Console\\WorkCommand), Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#35 /Users/altama/Desktop/project/mires-sistem/vendor/symfony/console/Application.php(171): Symfony\\Component\\Console\\Application->doRun(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#36 /Users/altama/Desktop/project/mires-sistem/vendor/laravel/framework/src/Illuminate/Console/Application.php(94): Symfony\\Component\\Console\\Application->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#37 /Users/altama/Desktop/project/mires-sistem/vendor/laravel/framework/src/Illuminate/Foundation/Console/Kernel.php(129): Illuminate\\Console\\Application->run(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#38 /Users/altama/Desktop/project/mires-sistem/artisan(37): Illuminate\\Foundation\\Console\\Kernel->handle(Object(Symfony\\Component\\Console\\Input\\ArgvInput), Object(Symfony\\Component\\Console\\Output\\ConsoleOutput))\n#39 {main}','2023-05-28 17:05:40');

/*!40000 ALTER TABLE `failed_jobs` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table gudang
# ------------------------------------------------------------

DROP TABLE IF EXISTS `gudang`;

CREATE TABLE `gudang` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `nama_gudang` varchar(191) NOT NULL,
  `pic_gudang` varchar(191) DEFAULT NULL,
  `alamat_gudang` text DEFAULT NULL,
  `keterangan` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

LOCK TABLES `gudang` WRITE;
/*!40000 ALTER TABLE `gudang` DISABLE KEYS */;

INSERT INTO `gudang` (`id`, `nama_gudang`, `pic_gudang`, `alamat_gudang`, `keterangan`, `created_at`, `updated_at`)
VALUES
	(1,'wiyung',NULL,NULL,NULL,NULL,NULL),
	(2,'forest',NULL,NULL,NULL,NULL,NULL),
	(3,'toko a',NULL,NULL,NULL,'2023-05-07 09:53:51','2023-05-07 09:53:51'),
	(4,'gudang tes','ricky',NULL,NULL,'2023-05-30 09:12:04','2023-05-30 09:12:04'),
	(5,'event',NULL,NULL,NULL,'2023-05-30 10:26:59','2023-05-30 10:26:59');

/*!40000 ALTER TABLE `gudang` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table jenis_penjualan
# ------------------------------------------------------------

DROP TABLE IF EXISTS `jenis_penjualan`;

CREATE TABLE `jenis_penjualan` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `jenis_penjualan` varchar(191) NOT NULL,
  `status_aktif` varchar(1) NOT NULL DEFAULT '1' COMMENT '1=aktif',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

LOCK TABLES `jenis_penjualan` WRITE;
/*!40000 ALTER TABLE `jenis_penjualan` DISABLE KEYS */;

INSERT INTO `jenis_penjualan` (`id`, `jenis_penjualan`, `status_aktif`, `created_at`, `updated_at`)
VALUES
	(1,'TOKOPEDIA','1',NULL,NULL);

/*!40000 ALTER TABLE `jenis_penjualan` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table jobs
# ------------------------------------------------------------

DROP TABLE IF EXISTS `jobs`;

CREATE TABLE `jobs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `queue` varchar(191) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) unsigned NOT NULL,
  `reserved_at` int(10) unsigned DEFAULT NULL,
  `available_at` int(10) unsigned NOT NULL,
  `created_at` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



# Dump of table jurnal_umum
# ------------------------------------------------------------

DROP TABLE IF EXISTS `jurnal_umum`;

CREATE TABLE `jurnal_umum` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `created_by` bigint(20) unsigned NOT NULL,
  `updated_by` bigint(20) unsigned NOT NULL,
  `nomer` varchar(191) NOT NULL,
  `tanggal` date NOT NULL,
  `total` double NOT NULL,
  `keterangan` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `jurnal_umum_nomer_unique` (`nomer`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

LOCK TABLES `jurnal_umum` WRITE;
/*!40000 ALTER TABLE `jurnal_umum` DISABLE KEYS */;

INSERT INTO `jurnal_umum` (`id`, `created_by`, `updated_by`, `nomer`, `tanggal`, `total`, `keterangan`, `created_at`, `updated_at`)
VALUES
	(8,1,0,'123','2023-06-24',10000,NULL,'2023-06-24 12:54:44','2023-06-24 12:54:44'),
	(9,1,0,'2222','2023-06-24',300000,NULL,'2023-06-24 12:55:49','2023-06-24 12:55:49');

/*!40000 ALTER TABLE `jurnal_umum` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table jurnal_umum_berkas
# ------------------------------------------------------------

DROP TABLE IF EXISTS `jurnal_umum_berkas`;

CREATE TABLE `jurnal_umum_berkas` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `jurnal_umum_id` bigint(20) unsigned NOT NULL,
  `nama_berkas` varchar(191) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `jurnal_umum_berkas_jurnal_umum_id_foreign` (`jurnal_umum_id`),
  CONSTRAINT `jurnal_umum_berkas_jurnal_umum_id_foreign` FOREIGN KEY (`jurnal_umum_id`) REFERENCES `jurnal_umum` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



# Dump of table jurnal_umum_rinci
# ------------------------------------------------------------

DROP TABLE IF EXISTS `jurnal_umum_rinci`;

CREATE TABLE `jurnal_umum_rinci` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `jurnal_umum_id` bigint(20) unsigned NOT NULL,
  `coa_id` bigint(20) unsigned NOT NULL,
  `debit` double NOT NULL,
  `kredit` double NOT NULL,
  `catatan` varchar(191) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `jurnal_umum_rinci_jurnal_umum_id_foreign` (`jurnal_umum_id`),
  KEY `jurnal_umum_rinci_coa_id_foreign` (`coa_id`),
  CONSTRAINT `jurnal_umum_rinci_coa_id_foreign` FOREIGN KEY (`coa_id`) REFERENCES `coa` (`id`),
  CONSTRAINT `jurnal_umum_rinci_jurnal_umum_id_foreign` FOREIGN KEY (`jurnal_umum_id`) REFERENCES `jurnal_umum` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

LOCK TABLES `jurnal_umum_rinci` WRITE;
/*!40000 ALTER TABLE `jurnal_umum_rinci` DISABLE KEYS */;

INSERT INTO `jurnal_umum_rinci` (`id`, `jurnal_umum_id`, `coa_id`, `debit`, `kredit`, `catatan`, `created_at`, `updated_at`)
VALUES
	(34,8,4,10000,0,NULL,'2023-06-24 12:54:44','2023-06-24 12:54:44'),
	(35,8,3,0,10000,NULL,'2023-06-24 12:54:44','2023-06-24 12:54:44'),
	(36,9,5,100000,0,NULL,'2023-06-24 12:55:49','2023-06-24 12:55:49'),
	(37,9,6,200000,0,NULL,'2023-06-24 12:55:49','2023-06-24 12:55:49'),
	(38,9,4,0,300000,NULL,'2023-06-24 12:55:49','2023-06-24 12:55:49');

/*!40000 ALTER TABLE `jurnal_umum_rinci` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table kasbank_pembayaran
# ------------------------------------------------------------

DROP TABLE IF EXISTS `kasbank_pembayaran`;

CREATE TABLE `kasbank_pembayaran` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `created_by` bigint(20) unsigned NOT NULL,
  `updated_by` bigint(20) unsigned NOT NULL,
  `bank_id` bigint(20) unsigned NOT NULL,
  `nomer` varchar(191) NOT NULL,
  `tanggal` date NOT NULL,
  `nominal` double NOT NULL DEFAULT 0,
  `keterangan` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `kasbank_pembayaran_nomer_unique` (`nomer`),
  KEY `kasbank_pembayaran_bank_id_foreign` (`bank_id`),
  CONSTRAINT `kasbank_pembayaran_bank_id_foreign` FOREIGN KEY (`bank_id`) REFERENCES `coa` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

LOCK TABLES `kasbank_pembayaran` WRITE;
/*!40000 ALTER TABLE `kasbank_pembayaran` DISABLE KEYS */;

INSERT INTO `kasbank_pembayaran` (`id`, `created_by`, `updated_by`, `bank_id`, `nomer`, `tanggal`, `nominal`, `keterangan`, `created_at`, `updated_at`)
VALUES
	(8,1,1,3,'bbk/1000x','2023-06-30',134,'bayar indihome dan listrik x','2023-06-21 10:38:31','2023-06-21 11:11:25');

/*!40000 ALTER TABLE `kasbank_pembayaran` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table kasbank_pembayaran_berkas
# ------------------------------------------------------------

DROP TABLE IF EXISTS `kasbank_pembayaran_berkas`;

CREATE TABLE `kasbank_pembayaran_berkas` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `kasbank_pembayaran_id` bigint(20) unsigned NOT NULL,
  `nama_berkas` varchar(191) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `kasbank_pembayaran_berkas_kasbank_pembayaran_id_foreign` (`kasbank_pembayaran_id`),
  CONSTRAINT `kasbank_pembayaran_berkas_kasbank_pembayaran_id_foreign` FOREIGN KEY (`kasbank_pembayaran_id`) REFERENCES `kasbank_pembayaran` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

LOCK TABLES `kasbank_pembayaran_berkas` WRITE;
/*!40000 ALTER TABLE `kasbank_pembayaran_berkas` DISABLE KEYS */;

INSERT INTO `kasbank_pembayaran_berkas` (`id`, `kasbank_pembayaran_id`, `nama_berkas`, `created_at`, `updated_at`)
VALUES
	(3,8,'bbk_1000x_banner_marketplace_Mascara_min.jpg','2023-06-21 10:39:28','2023-06-21 10:39:28');

/*!40000 ALTER TABLE `kasbank_pembayaran_berkas` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table kasbank_pembayaran_rinci
# ------------------------------------------------------------

DROP TABLE IF EXISTS `kasbank_pembayaran_rinci`;

CREATE TABLE `kasbank_pembayaran_rinci` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `coa_id` bigint(20) unsigned NOT NULL,
  `kasbank_pembayaran_id` bigint(20) unsigned NOT NULL,
  `nominal` double NOT NULL DEFAULT 0,
  `catatan` varchar(191) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `kasbank_pembayaran_rinci_coa_id_foreign` (`coa_id`),
  KEY `kasbank_pembayaran_rinci_kasbank_pembayaran_id_foreign` (`kasbank_pembayaran_id`),
  CONSTRAINT `kasbank_pembayaran_rinci_coa_id_foreign` FOREIGN KEY (`coa_id`) REFERENCES `coa` (`id`),
  CONSTRAINT `kasbank_pembayaran_rinci_kasbank_pembayaran_id_foreign` FOREIGN KEY (`kasbank_pembayaran_id`) REFERENCES `kasbank_pembayaran` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

LOCK TABLES `kasbank_pembayaran_rinci` WRITE;
/*!40000 ALTER TABLE `kasbank_pembayaran_rinci` DISABLE KEYS */;

INSERT INTO `kasbank_pembayaran_rinci` (`id`, `coa_id`, `kasbank_pembayaran_id`, `nominal`, `catatan`, `created_at`, `updated_at`)
VALUES
	(25,5,8,56,'internet x','2023-06-21 11:11:25','2023-06-21 11:11:25'),
	(26,7,8,78,'listrik x','2023-06-21 11:11:25','2023-06-21 11:11:25');

/*!40000 ALTER TABLE `kasbank_pembayaran_rinci` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table kasbank_penerimaan
# ------------------------------------------------------------

DROP TABLE IF EXISTS `kasbank_penerimaan`;

CREATE TABLE `kasbank_penerimaan` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `created_by` bigint(20) unsigned NOT NULL,
  `updated_by` bigint(20) unsigned NOT NULL,
  `bank_id` bigint(20) unsigned NOT NULL,
  `nomer` varchar(191) NOT NULL,
  `tanggal` date NOT NULL,
  `nominal` double NOT NULL DEFAULT 0,
  `keterangan` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `kasbank_penerimaan_nomer_unique` (`nomer`),
  KEY `kasbank_penerimaan_bank_id_foreign` (`bank_id`),
  CONSTRAINT `kasbank_penerimaan_bank_id_foreign` FOREIGN KEY (`bank_id`) REFERENCES `coa` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

LOCK TABLES `kasbank_penerimaan` WRITE;
/*!40000 ALTER TABLE `kasbank_penerimaan` DISABLE KEYS */;

INSERT INTO `kasbank_penerimaan` (`id`, `created_by`, `updated_by`, `bank_id`, `nomer`, `tanggal`, `nominal`, `keterangan`, `created_at`, `updated_at`)
VALUES
	(6,1,1,3,'bbm/1000x','2023-06-01',250000,'dana masuk x','2023-06-21 10:34:29','2023-06-21 11:09:31');

/*!40000 ALTER TABLE `kasbank_penerimaan` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table kasbank_penerimaan_berkas
# ------------------------------------------------------------

DROP TABLE IF EXISTS `kasbank_penerimaan_berkas`;

CREATE TABLE `kasbank_penerimaan_berkas` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `kasbank_penerimaan_id` bigint(20) unsigned NOT NULL,
  `nama_berkas` varchar(191) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `kasbank_penerimaan_berkas_kasbank_penerimaan_id_foreign` (`kasbank_penerimaan_id`),
  CONSTRAINT `kasbank_penerimaan_berkas_kasbank_penerimaan_id_foreign` FOREIGN KEY (`kasbank_penerimaan_id`) REFERENCES `kasbank_penerimaan` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

LOCK TABLES `kasbank_penerimaan_berkas` WRITE;
/*!40000 ALTER TABLE `kasbank_penerimaan_berkas` DISABLE KEYS */;

INSERT INTO `kasbank_penerimaan_berkas` (`id`, `kasbank_penerimaan_id`, `nama_berkas`, `created_at`, `updated_at`)
VALUES
	(4,6,'bbm_1000x_NEO_202306_1187110.pdf','2023-06-21 10:35:13','2023-06-21 10:35:13');

/*!40000 ALTER TABLE `kasbank_penerimaan_berkas` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table kasbank_penerimaan_rinci
# ------------------------------------------------------------

DROP TABLE IF EXISTS `kasbank_penerimaan_rinci`;

CREATE TABLE `kasbank_penerimaan_rinci` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `coa_id` bigint(20) unsigned NOT NULL,
  `kasbank_penerimaan_id` bigint(20) unsigned NOT NULL,
  `nominal` double NOT NULL DEFAULT 0,
  `catatan` varchar(191) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `kasbank_penerimaan_rinci_coa_id_foreign` (`coa_id`),
  KEY `kasbank_penerimaan_rinci_kasbank_penerimaan_id_foreign` (`kasbank_penerimaan_id`),
  CONSTRAINT `kasbank_penerimaan_rinci_coa_id_foreign` FOREIGN KEY (`coa_id`) REFERENCES `coa` (`id`),
  CONSTRAINT `kasbank_penerimaan_rinci_kasbank_penerimaan_id_foreign` FOREIGN KEY (`kasbank_penerimaan_id`) REFERENCES `kasbank_penerimaan` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

LOCK TABLES `kasbank_penerimaan_rinci` WRITE;
/*!40000 ALTER TABLE `kasbank_penerimaan_rinci` DISABLE KEYS */;

INSERT INTO `kasbank_penerimaan_rinci` (`id`, `coa_id`, `kasbank_penerimaan_id`, `nominal`, `catatan`, `created_at`, `updated_at`)
VALUES
	(18,6,6,50000,'xxx','2023-06-21 11:09:31','2023-06-21 11:09:31'),
	(19,7,6,200000,'xyz','2023-06-21 11:09:31','2023-06-21 11:09:31');

/*!40000 ALTER TABLE `kasbank_penerimaan_rinci` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table konsinyasi
# ------------------------------------------------------------

DROP TABLE IF EXISTS `konsinyasi`;

CREATE TABLE `konsinyasi` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `pelanggan_id` bigint(20) unsigned NOT NULL,
  `nama_pelanggan` varchar(191) DEFAULT NULL,
  `alamat_pelanggan` text DEFAULT NULL,
  `nomer_konsinyasi` varchar(191) NOT NULL,
  `tanggal_konsinyasi` date NOT NULL,
  `gudang_asal` varchar(191) NOT NULL,
  `gudang_tujuan` varchar(191) NOT NULL,
  `keterangan` text DEFAULT NULL,
  `grandtotal` double DEFAULT 0,
  `penerima` varchar(191) DEFAULT NULL,
  `alamat_penerima` text DEFAULT NULL,
  `ekspedisi` varchar(191) DEFAULT 'Driver Mires',
  `resi` varchar(191) DEFAULT NULL,
  `status_proses` varchar(191) NOT NULL DEFAULT '0' COMMENT '0=belum diproses',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `konsinyasi_nomer_konsinyasi_unique` (`nomer_konsinyasi`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

LOCK TABLES `konsinyasi` WRITE;
/*!40000 ALTER TABLE `konsinyasi` DISABLE KEYS */;

INSERT INTO `konsinyasi` (`id`, `pelanggan_id`, `nama_pelanggan`, `alamat_pelanggan`, `nomer_konsinyasi`, `tanggal_konsinyasi`, `gudang_asal`, `gudang_tujuan`, `keterangan`, `grandtotal`, `penerima`, `alamat_penerima`, `ekspedisi`, `resi`, `status_proses`, `created_at`, `updated_at`)
VALUES
	(3,80,'JD Beauty','Jl. Wijaya Kusuma, Malang MALANG JAWA TIMUR','KONSI-001/V/2023','2023-05-23','wiyung','forest',NULL,2500000,'JD Beauty','Jl. Wijaya Kusuma, Malang MALANG JAWA TIMUR','Driver Mires',NULL,'0','2023-05-23 17:17:22','2023-05-23 17:17:22');

/*!40000 ALTER TABLE `konsinyasi` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table konsinyasi_berkas
# ------------------------------------------------------------

DROP TABLE IF EXISTS `konsinyasi_berkas`;

CREATE TABLE `konsinyasi_berkas` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `konsinyasi_id` bigint(20) unsigned NOT NULL,
  `berkas1` varchar(191) DEFAULT NULL,
  `berkas2` varchar(191) DEFAULT NULL,
  `berkas3` varchar(191) DEFAULT NULL,
  `berkas4` varchar(191) DEFAULT NULL,
  `berkas5` varchar(191) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `konsinyasi_berkas_konsinyasi_id_foreign` (`konsinyasi_id`),
  CONSTRAINT `konsinyasi_berkas_konsinyasi_id_foreign` FOREIGN KEY (`konsinyasi_id`) REFERENCES `konsinyasi` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



# Dump of table konsinyasi_rinci
# ------------------------------------------------------------

DROP TABLE IF EXISTS `konsinyasi_rinci`;

CREATE TABLE `konsinyasi_rinci` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `konsinyasi_id` bigint(20) unsigned NOT NULL,
  `gudang_asal` varchar(191) NOT NULL,
  `gudang_tujuan` varchar(191) NOT NULL,
  `produk_id` bigint(20) unsigned NOT NULL,
  `kode_produk` varchar(191) NOT NULL,
  `nama_produk` varchar(191) NOT NULL,
  `kuantitas` double NOT NULL,
  `harga` double NOT NULL,
  `subtotal` double DEFAULT 0,
  `catatan` text DEFAULT NULL,
  `status_proses` varchar(191) NOT NULL DEFAULT '0' COMMENT '0=belum diproses',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `konsinyasi_rinci_konsinyasi_id_foreign` (`konsinyasi_id`),
  CONSTRAINT `konsinyasi_rinci_konsinyasi_id_foreign` FOREIGN KEY (`konsinyasi_id`) REFERENCES `konsinyasi` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

LOCK TABLES `konsinyasi_rinci` WRITE;
/*!40000 ALTER TABLE `konsinyasi_rinci` DISABLE KEYS */;

INSERT INTO `konsinyasi_rinci` (`id`, `konsinyasi_id`, `gudang_asal`, `gudang_tujuan`, `produk_id`, `kode_produk`, `nama_produk`, `kuantitas`, `harga`, `subtotal`, `catatan`, `status_proses`, `created_at`, `updated_at`)
VALUES
	(3,3,'wiyung','forest',400,'CSM - LIPCP0100 - CAR','KLT New Lipscup Caramel',100,25000,2500000,NULL,'0','2023-05-23 17:17:22','2023-05-23 17:17:22');

/*!40000 ALTER TABLE `konsinyasi_rinci` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table log_aktifitas
# ------------------------------------------------------------

DROP TABLE IF EXISTS `log_aktifitas`;

CREATE TABLE `log_aktifitas` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `nama_user` varchar(191) NOT NULL,
  `nama_aktifitas` varchar(191) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

LOCK TABLES `log_aktifitas` WRITE;
/*!40000 ALTER TABLE `log_aktifitas` DISABLE KEYS */;

INSERT INTO `log_aktifitas` (`id`, `nama_user`, `nama_aktifitas`, `created_at`, `updated_at`)
VALUES
	(1,'Super Admin','Membuat Pesanan Penjualan Baru dengan nomer : SO-023/V/2023','2023-05-06 16:37:44','2023-05-06 16:37:44'),
	(2,'',NULL,'2023-05-06 23:20:10','2023-05-06 23:20:10'),
	(3,'Super Admin','Membuat Pesanan Penjualan Baru dengan nomer : SO-024/V/2023','2023-05-06 23:21:13','2023-05-06 23:21:13'),
	(4,'Super Admin','Membuat Pengiriman Penjualan dengan nomer : SJ-024/V/2023','2023-05-06 23:23:02','2023-05-06 23:23:02'),
	(5,'Super Admin','Membuat Konsinyasi Baru dengan nomer : KONSI-001/V/2023','2023-05-07 09:16:33','2023-05-07 09:16:33'),
	(6,'Super Admin','Memproses Pengiriman Konsinyasi dengan nomer : KONSI-001/V/2023','2023-05-07 09:17:23','2023-05-07 09:17:23'),
	(7,'Super Admin','Merubah Pesanan Penjualan dengan nomer : SO-024/V/2023','2023-05-07 09:21:35','2023-05-07 09:21:35'),
	(8,'Super Admin','Merubah Pesanan Penjualan dengan nomer : SO-024/V/2023','2023-05-07 09:33:09','2023-05-07 09:33:09'),
	(9,'Super Admin','Membuat Konsinyasi Baru dengan nomer : KONSI-002/V/2023','2023-05-07 09:54:14','2023-05-07 09:54:14'),
	(10,'Super Admin','Merubah Konsinyasi dengan nomer : KONSI-002/V/2023','2023-05-07 09:54:42','2023-05-07 09:54:42'),
	(11,'Super Admin','Menghapus Konsinyasi dengan nomer : KONSI-002/V/2023','2023-05-07 10:03:04','2023-05-07 10:03:04'),
	(12,'Super Admin','Menghapus Konsinyasi dengan nomer : KONSI-001/V/2023','2023-05-07 10:03:26','2023-05-07 10:03:26'),
	(13,'Super Admin','Membuat Konsinyasi Baru dengan nomer : KONSI-001/V/2023','2023-05-07 10:05:23','2023-05-07 10:05:23'),
	(14,'Super Admin','Memproses Pengiriman Konsinyasi dengan nomer : KONSI-001/V/2023','2023-05-07 10:05:38','2023-05-07 10:05:38'),
	(15,'Adi Saputra','Menghapus Konsinyasi dengan nomer : KONSI-001/V/2023','2023-05-07 10:18:26','2023-05-07 10:18:26'),
	(16,'Adi Saputra','Membuat Konsinyasi Baru dengan nomer : KONSI-001/V/2023','2023-05-07 10:19:11','2023-05-07 10:19:11'),
	(17,'Super Admin','Memproses Pengiriman Konsinyasi dengan nomer : KONSI-001/V/2023','2023-05-07 10:19:36','2023-05-07 10:19:36'),
	(18,'Super Admin','Membuat Konsinyasi Baru dengan nomer : KONSI-002/V/2023','2023-05-07 10:20:12','2023-05-07 10:20:12'),
	(19,'Super Admin','Menghapus Konsinyasi dengan nomer : KONSI-002/V/2023','2023-05-07 10:20:39','2023-05-07 10:20:39'),
	(20,'Super Admin','Membuat Konsinyasi Baru dengan nomer : KONSI-002/V/2023','2023-05-07 10:21:17','2023-05-07 10:21:17'),
	(21,'Alfa Novitasari','Menghapus Konsinyasi dengan nomer : KONSI-002/V/2023','2023-05-07 10:21:41','2023-05-07 10:21:41'),
	(22,'Adi Saputra','Menghapus Konsinyasi dengan nomer : KONSI-001/V/2023','2023-05-07 10:22:52','2023-05-07 10:22:52'),
	(23,'Adi Saputra','Merubah Pesanan Penjualan dengan nomer : SO-024/V/2023','2023-05-07 10:38:58','2023-05-07 10:38:58'),
	(24,'Adi Saputra','Membuat Pesanan Penjualan Baru dengan nomer : SO-025/V/2023','2023-05-07 10:41:05','2023-05-07 10:41:05'),
	(25,'Adi Saputra','Membuat Pengiriman Penjualan dengan nomer : SJ-025/V/2023','2023-05-07 10:44:48','2023-05-07 10:44:48'),
	(26,'Adi Saputra','Membuat Pengiriman Penjualan dengan nomer : SJ-025/V/2023','2023-05-07 10:50:38','2023-05-07 10:50:38'),
	(27,'Adi Saputra','Membuat Pengiriman Penjualan dengan nomer : SJ-025/V/2023','2023-05-07 10:52:03','2023-05-07 10:52:03'),
	(28,'Adi Saputra','Membuat Konsinyasi Baru dengan nomer : KONSI-001/V/2023','2023-05-07 11:27:41','2023-05-07 11:27:41'),
	(29,'Adi Saputra','Menghapus Konsinyasi dengan nomer : KONSI-001/V/2023','2023-05-07 11:37:40','2023-05-07 11:37:40'),
	(30,'Adi Saputra','Membuat Pesanan Penjualan Baru dengan nomer : SO-026/V/2023','2023-05-07 11:58:51','2023-05-07 11:58:51'),
	(31,'Adi Saputra','Menghapus Pesanan Penjualan dengan nomer : SO-024/V/2023','2023-05-07 14:40:43','2023-05-07 14:40:43'),
	(32,'Adi Saputra','Membuat Pesanan Penjualan Baru dengan nomer : SO-027/V/2023','2023-05-07 14:41:49','2023-05-07 14:41:49'),
	(33,'Super Admin','Membuat Pengiriman Penjualan dengan nomer : SJ-027/V/2023','2023-05-07 14:42:26','2023-05-07 14:42:26'),
	(34,'Adi Saputra','Menghapus Pesanan Penjualan dengan nomer : SO-027/V/2023','2023-05-07 14:42:47','2023-05-07 14:42:47'),
	(35,'Adi Saputra','Membuat Pesanan Penjualan Baru dengan nomer : SO-028/V/2023','2023-05-07 14:43:47','2023-05-07 14:43:47'),
	(36,'Adi Saputra','Membuat Pengiriman Penjualan dengan nomer : SJ-028/V/2023','2023-05-07 14:43:57','2023-05-07 14:43:57'),
	(37,'Adi Saputra','Menghapus Pesanan Penjualan dengan nomer : SO-028/V/2023','2023-05-07 14:44:09','2023-05-07 14:44:09'),
	(38,'Super Admin','Membuat Pesanan Penjualan Baru dengan nomer : SO-001/V/2023','2023-05-07 14:52:30','2023-05-07 14:52:30'),
	(39,'Super Admin','Membuat Pesanan Penjualan Baru dengan nomer : SO-002/V/2023','2023-05-07 14:53:49','2023-05-07 14:53:49'),
	(40,'Super Admin','Membuat Pesanan Penjualan Baru dengan nomer : SO-00003/V/2023','2023-05-07 14:54:28','2023-05-07 14:54:28'),
	(41,'Super Admin','Membuat Pesanan Penjualan Baru dengan nomer : SO-004/V/2023','2023-05-07 14:55:17','2023-05-07 14:55:17'),
	(42,'Super Admin','Menghapus Pesanan Penjualan dengan nomer : SO-002/V/2023','2023-05-07 14:55:56','2023-05-07 14:55:56'),
	(43,'Super Admin','Menghapus Pesanan Penjualan dengan nomer : SO-00003/V/2023','2023-05-07 14:56:23','2023-05-07 14:56:23'),
	(44,'Super Admin','Membuat Pesanan Penjualan Baru dengan nomer : SO-002/V/2023','2023-05-07 14:59:24','2023-05-07 14:59:24'),
	(45,'Super Admin','Membuat Pesanan Penjualan Baru dengan nomer : SO-00003/V/2023','2023-05-07 15:03:19','2023-05-07 15:03:19'),
	(46,'Super Admin','Menghapus Pesanan Penjualan dengan nomer : SO-001/V/2023','2023-05-07 15:05:18','2023-05-07 15:05:18'),
	(47,'Super Admin','Membuat Pesanan Penjualan Baru dengan nomer : SO-001/V/2023','2023-05-07 16:42:11','2023-05-07 16:42:11'),
	(48,'Super Admin','Membuat Pesanan Penjualan Baru dengan nomer : SO-002/V/2023','2023-05-07 16:42:41','2023-05-07 16:42:41'),
	(49,'Super Admin','Menghapus Pesanan Penjualan dengan nomer : SO-001/V/2023','2023-05-07 16:43:18','2023-05-07 16:43:18'),
	(50,'',NULL,'2023-05-07 17:00:36','2023-05-07 17:00:36'),
	(51,'',NULL,'2023-05-07 17:00:42','2023-05-07 17:00:42'),
	(52,'',NULL,'2023-05-07 17:00:54','2023-05-07 17:00:54'),
	(53,'Super Admin','Membuat Pesanan Penjualan Baru dengan nomer : SO-001/V/2023','2023-05-07 17:07:36','2023-05-07 17:07:36'),
	(54,'Super Admin','Membuat Pesanan Penjualan Baru dengan nomer : SO-002/V/2023','2023-05-07 17:11:55','2023-05-07 17:11:55'),
	(55,'ricky','susu',NULL,NULL),
	(56,'Super Admin','Membuat Pesanan Penjualan Baru dengan nomer : SO-003/V/2023','2023-05-07 17:21:25','2023-05-07 17:21:25'),
	(57,'Super Admin','Membuat Pesanan Penjualan Baru dengan nomer : SO-004/V/2023','2023-05-07 17:23:43','2023-05-07 17:23:43'),
	(58,'Super Admin','Membuat Pengiriman Penjualan dengan nomer : SJ-004/V/2023','2023-05-07 19:53:50','2023-05-07 19:53:50'),
	(59,'Super Admin','Membuat Pesanan Penjualan Baru dengan nomer : SO-005/V/2023','2023-05-07 22:09:25','2023-05-07 22:09:25'),
	(60,'Super Admin','Membuat Pesanan Penjualan Baru dengan nomer : SO-006/V/2023','2023-05-07 22:13:35','2023-05-07 22:13:35'),
	(61,'Super Admin','Membuat Pesanan Penjualan Baru dengan nomer : SO-007/V/2023','2023-05-07 22:21:06','2023-05-07 22:21:06'),
	(62,'Super Admin','Membuat Pesanan Penjualan Baru dengan nomer : SO-008/V/2023','2023-05-07 22:25:04','2023-05-07 22:25:04'),
	(63,'Super Admin','Membuat Pesanan Penjualan Baru dengan nomer : SO-009/V/2023','2023-05-07 22:25:24','2023-05-07 22:25:24'),
	(64,'Super Admin','Membuat Pesanan Penjualan Baru dengan nomer : SO-010/V/2023','2023-05-07 22:26:13','2023-05-07 22:26:13'),
	(65,'Super Admin','Membuat Konsinyasi Baru dengan nomer : KONSI-001/V/2023','2023-05-08 08:57:11','2023-05-08 08:57:11'),
	(66,'Super Admin','Membuat Pengiriman Penjualan dengan nomer : SJ-010/V/2023','2023-05-08 10:40:08','2023-05-08 10:40:08'),
	(67,'Super Admin','Membuat Permintaan Tester Baru nomer : TESTER-0004/V/2023','2023-05-08 21:17:47','2023-05-08 21:17:47'),
	(68,'Super Admin','Membuat Permintaan Tester Baru nomer : TESTER-0005/V/2023','2023-05-08 21:18:40','2023-05-08 21:18:40'),
	(69,'Super Admin','Membuat Permintaan Tester Baru nomer : TESTER-0006/V/2023','2023-05-08 21:24:10','2023-05-08 21:24:10'),
	(70,'Super Admin','Membuat Permintaan Tester Baru nomer : TESTER-0007/V/2023','2023-05-08 22:10:43','2023-05-08 22:10:43'),
	(71,'Super Admin','Membuat Permintaan Tester Baru nomer : TESTER-0008/V/2023','2023-05-09 12:02:50','2023-05-09 12:02:50'),
	(72,'Super Admin','Membuat Permintaan Tester Baru nomer : TESTER-0008/V/2023','2023-05-09 12:14:21','2023-05-09 12:14:21'),
	(73,'Super Admin','Memproses Permintaan Tester dengan nomer : TESTER-0006/V/2023','2023-05-09 15:33:22','2023-05-09 15:33:22'),
	(74,'Super Admin','Memproses Permintaan Tester dengan nomer : TESTER-0005/V/2023','2023-05-09 15:41:30','2023-05-09 15:41:30'),
	(75,'Super administrator','Membuat Konsinyasi Baru dengan nomer : KONSI-010/V/2023','2023-05-10 23:09:30','2023-05-10 23:09:30'),
	(76,'Super administrator','Membuat Konsinyasi Baru dengan nomer : KONSI-011/V/2023','2023-05-10 23:10:35','2023-05-10 23:10:35'),
	(77,'Super administrator','Membuat Konsinyasi Baru dengan nomer : KONSI-012/V/2023','2023-05-10 23:11:44','2023-05-10 23:11:44'),
	(78,'Super administrator','Membuat Konsinyasi Baru dengan nomer : KONSI-013/V/2023','2023-05-10 23:13:06','2023-05-10 23:13:06'),
	(79,'Super administrator','Membuat Konsinyasi Baru dengan nomer : KONSI-014/V/2023','2023-05-10 23:19:43','2023-05-10 23:19:43'),
	(80,'Super administrator','Membuat Konsinyasi Baru dengan nomer : KONSI-002/V/2023','2023-05-10 23:23:32','2023-05-10 23:23:32'),
	(81,'Super administrator','Membuat Konsinyasi Baru dengan nomer : KONSI-003/V/2023','2023-05-10 23:24:58','2023-05-10 23:24:58'),
	(82,'Super administrator','Membuat Konsinyasi Baru dengan nomer : KONSI-004/V/2023','2023-05-10 23:25:30','2023-05-10 23:25:30'),
	(83,'Super administrator','Membuat Konsinyasi Baru dengan nomer : KONSI-005/V/2023','2023-05-10 23:27:12','2023-05-10 23:27:12'),
	(84,'Super administrator','Membuat Konsinyasi Baru dengan nomer : KONSI-006/V/2023','2023-05-10 23:31:48','2023-05-10 23:31:48'),
	(85,'Super administrator','Membuat Konsinyasi Baru dengan nomer : KONSI-007/V/2023','2023-05-10 23:32:48','2023-05-10 23:32:48'),
	(86,'Super administrator','Membuat Konsinyasi Baru dengan nomer : KONSI-008/V/2023','2023-05-10 23:33:31','2023-05-10 23:33:31'),
	(87,'Super administrator','Membuat Konsinyasi Baru dengan nomer : KONSI-009/V/2023','2023-05-10 23:34:14','2023-05-10 23:34:14'),
	(88,'Super administrator','Membuat Konsinyasi Baru dengan nomer : KONSI-010/V/2023','2023-05-10 23:35:50','2023-05-10 23:35:50'),
	(89,'Super administrator','Membuat Konsinyasi Baru dengan nomer : KONSI-002/V/2023','2023-05-10 23:40:04','2023-05-10 23:40:04'),
	(90,'Super administrator','Membuat Konsinyasi Baru dengan nomer : KONSI-003/V/2023','2023-05-10 23:40:46','2023-05-10 23:40:46'),
	(91,'Super administrator','Membuat Konsinyasi Baru dengan nomer : KONSI-004/V/2023','2023-05-10 23:43:28','2023-05-10 23:43:28'),
	(92,'Super administrator','Membuat Pesanan Penjualan Baru dengan nomer : SO-011/V/2023','2023-05-11 08:39:38','2023-05-11 08:39:38'),
	(93,'Super administrator','Membuat Pesanan Penjualan Baru dengan nomer : SO-012/V/2023','2023-05-11 08:55:19','2023-05-11 08:55:19'),
	(94,'Super administrator','Membuat Pesanan Penjualan Baru dengan nomer : SO-013/V/2023','2023-05-11 08:56:14','2023-05-11 08:56:14'),
	(95,'Super administrator','Menghapus Pesanan Penjualan dengan nomer : SO-001/V/2023','2023-05-11 09:05:20','2023-05-11 09:05:20'),
	(96,'Super administrator','Menghapus Pesanan Penjualan dengan nomer : SO-002/V/2023','2023-05-11 09:05:56','2023-05-11 09:05:56'),
	(97,'Super administrator','Menghapus Pesanan Penjualan dengan nomer : SO-003/V/2023','2023-05-11 09:06:38','2023-05-11 09:06:38'),
	(98,'Super administrator','Menghapus Pesanan Penjualan dengan nomer : SO-013/V/2023','2023-05-11 09:12:53','2023-05-11 09:12:53'),
	(99,'Super administrator','Menghapus Pesanan Penjualan dengan nomer : SO-012/V/2023','2023-05-11 09:13:04','2023-05-11 09:13:04'),
	(100,'Super administrator','Menghapus Pesanan Penjualan dengan nomer : SO-011/V/2023','2023-05-11 09:13:17','2023-05-11 09:13:17'),
	(101,'Super administrator','Menghapus Pesanan Penjualan dengan nomer : SO-009/V/2023','2023-05-11 09:13:51','2023-05-11 09:13:51'),
	(102,'Super administrator','Menghapus Pesanan Penjualan dengan nomer : SO-008/V/2023','2023-05-11 09:14:03','2023-05-11 09:14:03'),
	(103,'Super administrator','Menghapus Pesanan Penjualan dengan nomer : SO-007/V/2023','2023-05-11 09:14:17','2023-05-11 09:14:17'),
	(104,'Super administrator','Menghapus Pesanan Penjualan dengan nomer : SO-006/V/2023','2023-05-11 09:14:31','2023-05-11 09:14:31'),
	(105,'Super administrator','Membuat Pesanan Penjualan Baru dengan nomer : SO-001/V/2023','2023-05-11 09:36:03','2023-05-11 09:36:03'),
	(106,'Super administrator','Merubah Pesanan Penjualan dengan nomer : SO-001/V/2023','2023-05-11 09:36:54','2023-05-11 09:36:54'),
	(107,'Super administrator','Membuat Pesanan Penjualan Baru dengan nomer : SO-002/V/2023','2023-05-11 09:37:52','2023-05-11 09:37:52'),
	(108,'Super administrator','Merubah Pesanan Penjualan dengan nomer : SO-002/V/2023','2023-05-11 09:38:15','2023-05-11 09:38:15'),
	(109,'Super administrator','Menghapus Pesanan Penjualan dengan nomer : SO-002/V/2023','2023-05-11 09:38:29','2023-05-11 09:38:29'),
	(110,'Super administrator','Membuat Pesanan Penjualan Baru dengan nomer : SO-003/V/2023','2023-05-11 09:56:50','2023-05-11 09:56:50'),
	(111,'Super administrator','Membuat Permintaan Tester Baru nomer : SO-013/V/2023','2023-05-11 11:04:33','2023-05-11 11:04:33'),
	(112,'Super administrator','Membuat Permintaan Tester Baru nomer : SO-013/V/2023','2023-05-11 11:44:08','2023-05-11 11:44:08'),
	(113,'Super administrator','Membuat Permintaan Tester Baru nomer : SO-013/V/2023','2023-05-11 11:45:57','2023-05-11 11:45:57'),
	(114,'Super administrator','Membuat Permintaan Tester Baru nomer : SO-013/V/2023','2023-05-11 11:47:04','2023-05-11 11:47:04'),
	(115,'Super administrator','Membuat Permintaan Tester Baru nomer : SO-013/V/2023','2023-05-11 11:48:29','2023-05-11 11:48:29'),
	(116,'Super administrator','Membuat Pesanan Penjualan Baru dengan nomer : SO-013/V/2023','2023-05-11 14:18:20','2023-05-11 14:18:20'),
	(117,'Super administrator','Membuat Pengiriman Penjualan dengan nomer : SJ-013/V/2023','2023-05-11 14:18:53','2023-05-11 14:18:53'),
	(118,'Super administrator','Membuat Pesanan Penjualan Baru dengan nomer : SO-009/V/2023','2023-05-11 17:15:23','2023-05-11 17:15:23'),
	(119,'Super administrator','Membuat Pesanan Penjualan Baru dengan nomer : SO-008/V/2023','2023-05-11 17:16:23','2023-05-11 17:16:23'),
	(120,'Super administrator','Membuat Pesanan Penjualan Baru dengan nomer : SO-007/V/2023','2023-05-11 17:23:21','2023-05-11 17:23:21'),
	(121,'Super administrator','Membuat Pesanan Penjualan Baru dengan nomer : SO-006/V/2023','2023-05-11 17:29:20','2023-05-11 17:29:20'),
	(122,'Super administrator','Menghapus Pesanan Penjualan dengan nomer : SO-006/V/2023','2023-05-11 17:39:27','2023-05-11 17:39:27'),
	(123,'Super administrator','Menghapus Pesanan Penjualan dengan nomer : SO-007/V/2023','2023-05-11 17:39:45','2023-05-11 17:39:45'),
	(124,'Super administrator','Menghapus Pesanan Penjualan dengan nomer : SO-008/V/2023','2023-05-11 17:49:41','2023-05-11 17:49:41'),
	(125,'Super administrator','Menghapus Pesanan Penjualan dengan nomer : SO-009/V/2023','2023-05-11 17:49:52','2023-05-11 17:49:52'),
	(126,'Super administrator','Membuat Pesanan Penjualan Baru dengan nomer : SO-002/V/2023','2023-05-11 17:50:27','2023-05-11 17:50:27'),
	(127,'Super administrator','Merubah Pesanan Penjualan dengan nomer : SO-002/V/2023','2023-05-11 17:51:17','2023-05-11 17:51:17'),
	(128,'Super administrator','Membuat Permintaan Tester Baru nomer : TESTER-0002/V/2023','2023-05-11 18:04:12','2023-05-11 18:04:12'),
	(129,'Super administrator','Membuat Permintaan Tester Baru nomer : TESTER-0003/V/2023','2023-05-11 18:10:01','2023-05-11 18:10:01'),
	(130,'Super administrator','Membuat Pesanan Penjualan Baru dengan nomer : SO-006/V/2023','2023-05-13 21:41:00','2023-05-13 21:41:00'),
	(131,'Super administrator','Membuat Pesanan Penjualan Baru dengan nomer : SO-007/V/2023','2023-05-13 21:44:36','2023-05-13 21:44:36'),
	(132,'Super administrator','Membuat Pesanan Penjualan Baru dengan nomer : SO-008/V/2023','2023-05-13 22:07:32','2023-05-13 22:07:32'),
	(133,'Super administrator','Membuat Konsinyasi Baru dengan nomer : KONSI-001/V/2023','2023-05-13 22:35:36','2023-05-13 22:35:36'),
	(134,'Super administrator','Membuat Pesanan Penjualan Baru dengan nomer : SO-009/V/2023','2023-05-13 22:55:24','2023-05-13 22:55:24'),
	(135,'Super administrator','Merubah Pesanan Penjualan dengan nomer : SO-009/V/2023','2023-05-13 22:59:11','2023-05-13 22:59:11'),
	(136,'Super administrator','Menghapus Pesanan Penjualan dengan nomer : SO-009/V/2023','2023-05-13 23:02:48','2023-05-13 23:02:48'),
	(137,'Super administrator','Menghapus Pesanan Penjualan dengan nomer : SO-008/V/2023','2023-05-13 23:02:57','2023-05-13 23:02:57'),
	(138,'Super administrator','Menghapus Pesanan Penjualan dengan nomer : SO-007/V/2023','2023-05-13 23:03:05','2023-05-13 23:03:05'),
	(139,'Super administrator','Menghapus Pesanan Penjualan dengan nomer : SO-006/V/2023','2023-05-13 23:03:13','2023-05-13 23:03:13'),
	(140,'Super administrator','Menghapus Pesanan Penjualan dengan nomer : SO-002/V/2023','2023-05-13 23:03:21','2023-05-13 23:03:21'),
	(141,'Super administrator','Menghapus Pesanan Penjualan dengan nomer : SO-011/V/2023','2023-05-13 23:03:29','2023-05-13 23:03:29'),
	(142,'Super administrator','Menghapus Pesanan Penjualan dengan nomer : SO-012/V/2023','2023-05-13 23:03:36','2023-05-13 23:03:36'),
	(143,'Super administrator','Membuat Pesanan Penjualan Baru dengan nomer : SO-009/V/2023','2023-05-13 23:04:38','2023-05-13 23:04:38'),
	(144,'Super administrator','Membuat Pesanan Penjualan Baru dengan nomer : SO-008/V/2023','2023-05-13 23:09:04','2023-05-13 23:09:04'),
	(145,'Super administrator','Membuat Pesanan Penjualan Baru dengan nomer : SO-007/V/2023','2023-05-14 08:49:22','2023-05-14 08:49:22'),
	(146,'Super administrator','Merubah Pesanan Penjualan dengan nomer : SO-007/V/2023','2023-05-14 08:58:33','2023-05-14 08:58:33'),
	(147,'Super administrator','Membuat Pesanan Penjualan Baru dengan nomer : SO-006/V/2023','2023-05-14 09:24:39','2023-05-14 09:24:39'),
	(148,'Super administrator','Membuat Pesanan Penjualan Baru dengan nomer : SO-002/V/2023','2023-05-14 09:28:13','2023-05-14 09:28:13'),
	(149,'Super administrator','Merubah Pesanan Penjualan dengan nomer : SO-002/V/2023','2023-05-14 09:29:03','2023-05-14 09:29:03'),
	(150,'Super administrator','Merubah Pesanan Penjualan dengan nomer : SO-002/V/2023','2023-05-14 09:30:23','2023-05-14 09:30:23'),
	(151,'Super administrator','Merubah Pesanan Penjualan dengan nomer : SO-002/V/2023','2023-05-14 11:32:31','2023-05-14 11:32:31'),
	(152,'Super administrator','Merubah Pesanan Penjualan dengan nomer : SO-002/V/2023','2023-05-14 11:33:36','2023-05-14 11:33:36'),
	(153,'Super administrator','Merubah Pesanan Penjualan dengan nomer : SO-002/V/2023','2023-05-14 11:35:03','2023-05-14 11:35:03'),
	(154,'Super administrator','Membuat Pesanan Penjualan Baru dengan nomer : SO-002/V/2023','2023-05-14 13:09:39','2023-05-14 13:09:39'),
	(155,'Super administrator','Membuat Pesanan Penjualan Baru dengan nomer : SO-001/V/2023','2023-05-14 13:20:18','2023-05-14 13:20:18'),
	(156,'Aam','Membuat Pengiriman Penjualan dengan nomer : SJ-001/V/2023','2023-05-14 13:21:26','2023-05-14 13:21:26'),
	(157,'Super administrator','Merubah Pesanan Penjualan dengan nomer : SO-001/V/2023','2023-05-14 13:22:35','2023-05-14 13:22:35'),
	(158,'Super administrator','Merubah Pesanan Penjualan dengan nomer : SO-001/V/2023','2023-05-14 13:27:22','2023-05-14 13:27:22'),
	(159,'Super administrator','Merubah Pesanan Penjualan dengan nomer : SO-001/V/2023','2023-05-14 13:28:01','2023-05-14 13:28:01'),
	(160,'Super administrator','Merubah Pesanan Penjualan dengan nomer : SO-001/V/2023','2023-05-14 13:28:49','2023-05-14 13:28:49'),
	(161,'Super administrator','Membuat Pesanan Penjualan Baru dengan nomer : SO-001/V/2023','2023-05-14 14:00:09','2023-05-14 14:00:09'),
	(162,'Super administrator','Membuat Pesanan Penjualan Baru dengan nomer : SO-001/V/2023','2023-05-14 14:03:32','2023-05-14 14:03:32'),
	(163,'Super administrator','Merubah Pesanan Penjualan dengan nomer : SO-001/V/2023','2023-05-14 14:05:53','2023-05-14 14:05:53'),
	(164,'Super administrator','Merubah Pesanan Penjualan dengan nomer : SO-001/V/2023','2023-05-14 14:07:11','2023-05-14 14:07:11'),
	(165,'Super administrator','Membuat Pesanan Penjualan Baru dengan nomer : SO-002/V/2023','2023-05-14 14:26:01','2023-05-14 14:26:01'),
	(166,'Super administrator','Membuat Pesanan Penjualan Baru dengan nomer : SO-003/V/2023','2023-05-14 14:35:55','2023-05-14 14:35:55'),
	(167,'Super administrator','Membuat Pesanan Penjualan Baru dengan nomer : SO-004/V/2023','2023-05-14 15:09:42','2023-05-14 15:09:42'),
	(168,'Super administrator','Membuat Pesanan Penjualan Baru dengan nomer : SO-001/V/2023','2023-05-14 15:12:26','2023-05-14 15:12:26'),
	(169,'Super administrator','Merubah Pesanan Penjualan dengan nomer : SO-001/V/2023','2023-05-14 15:13:29','2023-05-14 15:13:29'),
	(170,'Super administrator','Membuat Pesanan Penjualan Baru dengan nomer : SO-002/V/2023','2023-05-14 16:55:36','2023-05-14 16:55:36'),
	(171,'Super administrator','Membuat Pesanan Penjualan Baru dengan nomer : SO-003/V/2023','2023-05-14 17:20:58','2023-05-14 17:20:58'),
	(172,'Super administrator','Membuat Pesanan Penjualan Baru dengan nomer : SO-004/V/2023','2023-05-14 17:25:22','2023-05-14 17:25:22'),
	(173,'Super administrator','Membuat Pesanan Penjualan Baru dengan nomer : SO-008/V/2023','2023-05-14 17:28:20','2023-05-14 17:28:20'),
	(174,'Super administrator','Membuat Pesanan Penjualan Baru dengan nomer : SO-001/V/2023','2023-05-14 17:43:38','2023-05-14 17:43:38'),
	(175,'Super administrator','Membuat Pesanan Penjualan Baru dengan nomer : SO-002/V/2023','2023-05-14 17:45:46','2023-05-14 17:45:46'),
	(176,'Super administrator','Membuat Pesanan Penjualan Baru dengan nomer : SO-001/V/2023','2023-05-14 17:55:38','2023-05-14 17:55:38'),
	(177,'Super administrator','Merubah Pesanan Penjualan dengan nomer : SO-001/V/2023','2023-05-14 17:58:01','2023-05-14 17:58:01'),
	(178,'Super administrator','Menghapus Konsinyasi dengan nomer : KONSI-001/V/2023','2023-05-15 13:24:56','2023-05-15 13:24:56'),
	(179,'Super administrator','Membuat Konsinyasi Baru dengan nomer : KONSI-001/V/2023','2023-05-15 13:25:33','2023-05-15 13:25:33'),
	(180,'Super administrator','Merubah Konsinyasi dengan nomer : KONSI-001/V/2023','2023-05-15 13:25:44','2023-05-15 13:25:44'),
	(181,'Aam','Memproses Pengiriman Konsinyasi dengan nomer : KONSI-001/V/2023','2023-05-15 14:33:51','2023-05-15 14:33:51'),
	(182,'Super administrator','Membuat Pesanan Penjualan Baru dengan nomer : SO-002/V/2023','2023-05-22 12:38:30','2023-05-22 12:38:30'),
	(183,'Super administrator','Menghapus Pesanan Penjualan dengan nomer : SO-002/V/2023','2023-05-22 12:52:19','2023-05-22 12:52:19'),
	(184,'Super administrator','Menghapus Pesanan Penjualan dengan nomer : SO-001/V/2023','2023-05-22 12:52:42','2023-05-22 12:52:42'),
	(185,'Super administrator','Membuat Pesanan Penjualan Baru dengan nomer : SO-002/V/2023','2023-05-22 12:56:33','2023-05-22 12:56:33'),
	(186,'Super administrator','Membuat Pesanan Penjualan Baru dengan nomer : SO-001/V/2023','2023-05-22 12:57:24','2023-05-22 12:57:24'),
	(187,'Super administrator','Membuat Pesanan Penjualan Baru dengan nomer : SO-003/V/2023','2023-05-22 20:47:46','2023-05-22 20:47:46'),
	(188,'Super administrator','Membuat Pesanan Penjualan Baru dengan nomer : SO-005/V/2023','2023-05-23 09:05:15','2023-05-23 09:05:15'),
	(189,'Super administrator','Membuat Pesanan Penjualan Baru dengan nomer : SO-006/V/2023','2023-05-23 11:50:18','2023-05-23 11:50:18'),
	(190,'Super administrator','Membuat Pesanan Penjualan Baru dengan nomer : SO-001/V/2023','2023-05-23 11:55:55','2023-05-23 11:55:55'),
	(191,'Super administrator','Membuat Pengiriman Penjualan dengan nomer : SJ-001/V/2023','2023-05-23 12:10:21','2023-05-23 12:10:21'),
	(192,'Super administrator','Membuat Pesanan Penjualan Baru dengan nomer : SO-001/V/2023','2023-05-23 16:59:33','2023-05-23 16:59:33'),
	(193,'Super administrator','Membuat Pesanan Penjualan Baru dengan nomer : SO-002/V/2023','2023-05-23 17:03:17','2023-05-23 17:03:17'),
	(194,'Super administrator','Merubah Pesanan Penjualan dengan nomer : SO-002/V/2023','2023-05-23 17:04:47','2023-05-23 17:04:47'),
	(195,'Super administrator','Membuat Permintaan Tester Baru nomer : TESTER-0001/V/2023','2023-05-23 17:14:16','2023-05-23 17:14:16'),
	(196,'Super administrator','Membuat Konsinyasi Baru dengan nomer : KONSI-001/V/2023','2023-05-23 17:17:22','2023-05-23 17:17:22'),
	(197,'Super administrator','Membuat Invoice dengan nomer : MMG/23/05/0004','2023-05-23 17:50:01','2023-05-23 17:50:01'),
	(198,'Super administrator','Membuat Invoice dengan nomer : MMG/23/05/0004','2023-05-23 17:51:58','2023-05-23 17:51:58'),
	(199,'Super administrator','Menyetujui Permintaan pembelian nomer : PR/1000','2023-05-24 16:45:29','2023-05-24 16:45:29'),
	(200,'Super administrator','Menyetujui Permintaan pembelian nomer : PR/1000','2023-05-24 16:51:10','2023-05-24 16:51:10'),
	(201,'Super administrator','Menyetujui Permintaan pembelian nomer : PR/1000','2023-05-24 16:52:09','2023-05-24 16:52:09'),
	(202,'Super administrator','Menyetujui Permintaan pembelian nomer : PR/1000','2023-05-24 16:55:45','2023-05-24 16:55:45'),
	(203,'Super administrator','Menyetujui Permintaan pembelian nomer : PR/1000','2023-05-24 16:57:33','2023-05-24 16:57:33'),
	(204,'Super administrator','Tidak Menyetujui Permintaan pembelian nomer : PR/1000','2023-05-24 16:59:32','2023-05-24 16:59:32'),
	(205,'Super administrator','Membuat permintaan pembelian nomer : 1234','2023-05-24 20:19:05','2023-05-24 20:19:05'),
	(206,'Super administrator','Membuat permintaan pembelian nomer : PR/2000/100','2023-05-24 20:22:23','2023-05-24 20:22:23'),
	(207,'Super administrator','Membuat permintaan pembelian nomer : PR/1002','2023-05-25 13:19:02','2023-05-25 13:19:02'),
	(208,'Super administrator','Membuat permintaan pembelian nomer : PR/1004','2023-05-25 13:19:52','2023-05-25 13:19:52'),
	(209,'Super administrator','Membuat permintaan pembelian nomer : PR/2003','2023-05-27 10:07:28','2023-05-27 10:07:28'),
	(210,'Super administrator','Membuat permintaan pembelian nomer : PR/3001','2023-05-27 10:09:02','2023-05-27 10:09:02'),
	(211,'Super administrator','Membuat permintaan pembelian nomer : PR/5000','2023-05-27 10:10:06','2023-05-27 10:10:06'),
	(212,'Super administrator','Menyetujui Permintaan pembelian nomer : PR/5000','2023-05-27 10:13:26','2023-05-27 10:13:26'),
	(213,'Super administrator','Menyetujui Permintaan pembelian nomer : PR/5000','2023-05-27 10:16:33','2023-05-27 10:16:33'),
	(214,'Super administrator','Membuat permintaan pembelian nomer : PR/1000','2023-05-27 10:26:32','2023-05-27 10:26:32'),
	(215,'Super administrator','Menyetujui Permintaan pembelian nomer : PR/1000','2023-05-27 10:30:35','2023-05-27 10:30:35'),
	(216,'Super administrator','Menyetujui Permintaan pembelian nomer : PR/1000','2023-05-27 10:39:01','2023-05-27 10:39:01'),
	(217,'Super administrator','Menyetujui Permintaan pembelian nomer : PR/1000','2023-05-27 10:39:40','2023-05-27 10:39:40'),
	(218,'Super administrator','Menyetujui Permintaan pembelian nomer : PR/1000','2023-05-27 10:40:40','2023-05-27 10:40:40'),
	(219,'Super administrator','Menyetujui Permintaan pembelian nomer : PR/1000','2023-05-27 10:43:09','2023-05-27 10:43:09'),
	(220,'Super administrator','Menyetujui Permintaan pembelian nomer : PR/1000','2023-05-27 10:44:49','2023-05-27 10:44:49'),
	(221,'Super administrator','Membuat permintaan pembelian nomer : PR/1003','2023-05-27 15:01:26','2023-05-27 15:01:26'),
	(222,'Super administrator','Membuat permintaan pembelian nomer : PR/1004','2023-05-27 15:02:39','2023-05-27 15:02:39'),
	(223,'Super administrator','Membuat permintaan pembelian nomer : PR/1005','2023-05-27 15:03:09','2023-05-27 15:03:09'),
	(224,'Super administrator','Membuat permintaan pembelian nomer : PR/1006','2023-05-27 15:04:51','2023-05-27 15:04:51'),
	(225,'Super administrator','Membuat permintaan pembelian nomer : PR/1006','2023-05-27 15:05:44','2023-05-27 15:05:44'),
	(226,'Super administrator','Membuat permintaan pembelian nomer : PR/1007','2023-05-27 15:06:46','2023-05-27 15:06:46'),
	(227,'Super administrator','Membuat permintaan pembelian nomer : PR/2000','2023-05-27 15:08:12','2023-05-27 15:08:12'),
	(228,'Super administrator','Menyetujui Permintaan pembelian nomer : PR/2000','2023-05-27 15:11:30','2023-05-27 15:11:30'),
	(229,'Super administrator','Menyetujui Permintaan pembelian nomer : PR/2000','2023-05-27 15:14:35','2023-05-27 15:14:35'),
	(230,'Super administrator','Membuat permintaan pembelian nomer : PR/1001','2023-05-27 15:32:01','2023-05-27 15:32:01'),
	(231,'Super administrator','Menyetujui Permintaan pembelian nomer : PR/1001','2023-05-27 15:32:43','2023-05-27 15:32:43'),
	(232,'Super administrator','Menyetujui Permintaan pembelian nomer : PR/1001','2023-05-27 18:12:25','2023-05-27 18:12:25'),
	(233,'Super administrator','Membuat permintaan pembelian nomer : PR/2001','2023-05-27 18:29:20','2023-05-27 18:29:20'),
	(234,'Super administrator','Membuat permintaan pembelian nomer : 1234','2023-05-27 19:07:57','2023-05-27 19:07:57'),
	(235,'Super administrator','Memperbarui Permintaan Pembelian nomer : 1234xx','2023-05-27 19:19:49','2023-05-27 19:19:49'),
	(236,'Super administrator','Membuat permintaan pembelian nomer : PR12345','2023-05-27 19:23:23','2023-05-27 19:23:23'),
	(237,'Super administrator','Menyetujui Permintaan pembelian nomer : PR12345','2023-05-27 19:28:43','2023-05-27 19:28:43'),
	(238,'Super administrator','Membuat permintaan pembelian nomer : PR/1200','2023-05-27 19:30:41','2023-05-27 19:30:41'),
	(239,'Super administrator','Memperbarui Permintaan Pembelian nomer : PR/1200','2023-05-27 19:32:01','2023-05-27 19:32:01'),
	(240,'Super administrator','Membuat permintaan pembelian nomer : PR/1000','2023-05-27 21:56:19','2023-05-27 21:56:19'),
	(241,'Super administrator','Membuat permintaan pembelian nomer : PR/1000','2023-05-27 21:59:32','2023-05-27 21:59:32'),
	(242,'Super administrator','Membuat permintaan pembelian nomer : PR/1001','2023-05-27 22:10:43','2023-05-27 22:10:43'),
	(243,'Super administrator','Revisi Permintaan Pembelian nomer : PR/1001','2023-05-27 22:23:26','2023-05-27 22:23:26'),
	(244,'Super administrator','Menghapus permintaan pembelian nomer : PR/1001','2023-05-27 22:37:20','2023-05-27 22:37:20'),
	(245,'Super administrator','Menghapus permintaan pembelian nomer : PR/1000x','2023-05-27 22:38:29','2023-05-27 22:38:29'),
	(246,'Super administrator','Membuat permintaan pembelian nomer : PR/1004','2023-05-27 22:43:06','2023-05-27 22:43:06'),
	(247,'Super administrator','Membuat permintaan pembelian nomer : 90','2023-05-27 22:52:17','2023-05-27 22:52:17'),
	(248,'Super administrator','Membuat permintaan pembelian nomer : pr1101','2023-05-27 22:56:10','2023-05-27 22:56:10'),
	(249,'Super administrator','Membuat permintaan pembelian nomer : pr1101','2023-05-27 22:57:41','2023-05-27 22:57:41'),
	(250,'Super administrator','Membuat permintaan pembelian nomer : pr/1000','2023-05-27 23:06:30','2023-05-27 23:06:30'),
	(251,'Super administrator','Membuat permintaan pembelian nomer : pr/1002','2023-05-27 23:10:23','2023-05-27 23:10:23'),
	(252,'Super administrator','Membuat permintaan pembelian nomer : pr/1003','2023-05-27 23:11:37','2023-05-27 23:11:37'),
	(253,'Super administrator','Membuat permintaan pembelian nomer : pr/1004','2023-05-27 23:13:06','2023-05-27 23:13:06'),
	(254,'Super administrator','Membuat permintaan pembelian nomer : pr/1005','2023-05-27 23:16:14','2023-05-27 23:16:14'),
	(255,'Super administrator','Membuat permintaan pembelian nomer : pr/1001','2023-05-27 23:25:15','2023-05-27 23:25:15'),
	(256,'Super administrator','Membuat permintaan pembelian nomer : pr/1006','2023-05-27 23:31:24','2023-05-27 23:31:24'),
	(257,'Super administrator','Membuat permintaan pembelian nomer : PR/10001','2023-05-28 15:29:47','2023-05-28 15:29:47'),
	(258,'Super administrator','Membuat permintaan pembelian nomer : pr200','2023-05-28 16:04:19','2023-05-28 16:04:19'),
	(259,'Super administrator','Membuat permintaan pembelian nomer : pr300','2023-05-28 16:06:40','2023-05-28 16:06:40'),
	(260,'Super administrator','Membuat permintaan pembelian nomer : pr/4000','2023-05-28 16:08:37','2023-05-28 16:08:37'),
	(261,'Super administrator','Membuat permintaan pembelian nomer : PR/200000','2023-05-28 16:10:02','2023-05-28 16:10:02'),
	(262,'Super administrator','Membuat permintaan pembelian nomer : pr/201','2023-05-28 16:12:04','2023-05-28 16:12:04'),
	(263,'Super administrator','Membuat permintaan pembelian nomer : pr5','2023-05-28 16:17:16','2023-05-28 16:17:16'),
	(264,'Super administrator','Membuat permintaan pembelian nomer : PR1','2023-05-28 16:19:18','2023-05-28 16:19:18'),
	(265,'Super administrator','Membuat permintaan pembelian nomer : pr2','2023-05-28 16:20:24','2023-05-28 16:20:24'),
	(266,'Super administrator','Membuat permintaan pembelian nomer : pr3','2023-05-28 16:22:15','2023-05-28 16:22:15'),
	(267,'Super administrator','Membuat permintaan pembelian nomer : pr4','2023-05-28 16:22:40','2023-05-28 16:22:40'),
	(268,'Super administrator','Membuat permintaan pembelian nomer : pr5','2023-05-28 16:24:34','2023-05-28 16:24:34'),
	(269,'Super administrator','Membuat permintaan pembelian nomer : pr6','2023-05-28 16:26:00','2023-05-28 16:26:00'),
	(270,'Super administrator','Membuat permintaan pembelian nomer : pr7','2023-05-28 16:27:23','2023-05-28 16:27:23'),
	(271,'Super administrator','Membuat permintaan pembelian nomer : pr8','2023-05-28 16:27:57','2023-05-28 16:27:57'),
	(272,'Super administrator','Membuat permintaan pembelian nomer : pr1000','2023-05-28 16:41:55','2023-05-28 16:41:55'),
	(273,'Super administrator','Membuat permintaan pembelian nomer : pr/1002','2023-05-28 16:47:05','2023-05-28 16:47:05'),
	(274,'Super administrator','Membuat permintaan pembelian nomer : pr/1005','2023-05-28 16:48:54','2023-05-28 16:48:54'),
	(275,'Super administrator','Membuat permintaan pembelian nomer : pr/1000','2023-05-28 16:49:40','2023-05-28 16:49:40'),
	(276,'Super administrator','Membuat permintaan pembelian nomer : pr/10002','2023-05-28 16:51:31','2023-05-28 16:51:31'),
	(277,'Super administrator','Membuat permintaan pembelian nomer : PR/2000','2023-05-28 16:54:56','2023-05-28 16:54:56'),
	(278,'Super administrator','Membuat permintaan pembelian nomer : PR/1111','2023-05-28 16:55:41','2023-05-28 16:55:41'),
	(279,'Super administrator','Membuat permintaan pembelian nomer : pr/2222','2023-05-28 16:57:58','2023-05-28 16:57:58'),
	(280,'Super administrator','Membuat permintaan pembelian nomer : PR/1234','2023-05-28 16:59:42','2023-05-28 16:59:42'),
	(281,'Super administrator','Membuat permintaan pembelian nomer : PR/12345','2023-05-28 17:00:40','2023-05-28 17:00:40'),
	(282,'Super administrator','Membuat permintaan pembelian nomer : PR/2314','2023-05-28 17:01:38','2023-05-28 17:01:38'),
	(283,'Super administrator','Membuat permintaan pembelian nomer : pr/1','2023-05-28 17:05:37','2023-05-28 17:05:37'),
	(284,'Super administrator','Membuat permintaan pembelian nomer : pr2','2023-05-28 17:06:28','2023-05-28 17:06:28'),
	(285,'Super administrator','Membuat permintaan pembelian nomer : pr3','2023-05-28 17:07:39','2023-05-28 17:07:39'),
	(286,'Super administrator','Membuat permintaan pembelian nomer : pr4','2023-05-28 17:10:41','2023-05-28 17:10:41'),
	(287,'Super administrator','Membuat permintaan pembelian nomer : pr5','2023-05-28 17:11:29','2023-05-28 17:11:29'),
	(288,'Super administrator','Membuat permintaan pembelian nomer : pr7','2023-05-28 17:12:01','2023-05-28 17:12:01'),
	(289,'Super administrator','Membuat permintaan pembelian nomer : 1234','2023-05-28 18:16:29','2023-05-28 18:16:29'),
	(290,'Super administrator','Membuat permintaan pembelian nomer : 1234','2023-05-28 18:22:45','2023-05-28 18:22:45'),
	(291,'Super administrator','Membuat permintaan pembelian nomer : 1234','2023-05-28 18:24:43','2023-05-28 18:24:43'),
	(292,'Super administrator','Membuat permintaan pembelian nomer : pr123','2023-05-28 18:26:44','2023-05-28 18:26:44'),
	(293,'Super administrator','Membuat permintaan pembelian nomer : pr12345','2023-05-28 18:35:18','2023-05-28 18:35:18'),
	(294,'Super administrator','Membuat permintaan pembelian nomer : pr1234','2023-05-28 18:36:57','2023-05-28 18:36:57'),
	(295,'Super administrator','Membuat permintaan pembelian nomer : pr123','2023-05-28 18:39:26','2023-05-28 18:39:26'),
	(296,'Super administrator','Membuat permintaan pembelian nomer : pr1','2023-05-28 18:39:49','2023-05-28 18:39:49'),
	(297,'Super administrator','Membuat permintaan pembelian nomer : pr2','2023-05-28 18:40:49','2023-05-28 18:40:49'),
	(298,'Super administrator','Membuat permintaan pembelian nomer : pr/123','2023-05-28 18:42:07','2023-05-28 18:42:07'),
	(299,'Super administrator','Membuat permintaan pembelian nomer : pr/2000','2023-05-28 18:42:54','2023-05-28 18:42:54'),
	(300,'Super administrator','Membuat permintaan pembelian nomer : 1234','2023-05-28 18:43:38','2023-05-28 18:43:38'),
	(301,'Super administrator','Membuat permintaan pembelian nomer : pr1235','2023-05-28 18:44:18','2023-05-28 18:44:18'),
	(302,'Super administrator','Membuat permintaan pembelian nomer : pr42323','2023-05-28 18:51:05','2023-05-28 18:51:05'),
	(303,'Super administrator','Membuat permintaan pembelian nomer : pr12312312','2023-05-28 18:52:16','2023-05-28 18:52:16'),
	(304,'Super administrator','Membuat permintaan pembelian nomer : 34532453','2023-05-28 18:52:48','2023-05-28 18:52:48'),
	(305,'Super administrator','Membuat permintaan pembelian nomer : 34rk34r34','2023-05-28 18:53:11','2023-05-28 18:53:11'),
	(306,'Super administrator','Membuat permintaan pembelian nomer : 3lk4rj34lrj3','2023-05-28 18:53:40','2023-05-28 18:53:40'),
	(307,'Super administrator','Membuat permintaan pembelian nomer : pr10294923-04','2023-05-28 18:54:11','2023-05-28 18:54:11'),
	(308,'Super administrator','Membuat permintaan pembelian nomer : PR2309234--','2023-05-28 18:55:16','2023-05-28 18:55:16'),
	(309,'Super administrator','Membuat permintaan pembelian nomer : 239p23o23rio23','2023-05-28 18:55:42','2023-05-28 18:55:42'),
	(310,'Super administrator','Membuat permintaan pembelian nomer : 3r98u34r9834ur','2023-05-28 18:57:19','2023-05-28 18:57:19'),
	(311,'Super administrator','Revisi Permintaan Pembelian nomer : pr1234','2023-05-28 19:01:00','2023-05-28 19:01:00'),
	(312,'Super administrator','Menyetujui Permintaan pembelian nomer : pr1234_cancel20230528_190100','2023-05-28 19:04:37','2023-05-28 19:04:37'),
	(313,'Super administrator','Revisi Permintaan Pembelian nomer : 34rk34r34','2023-05-28 19:06:10','2023-05-28 19:06:10'),
	(314,'Super administrator','Menghapus permintaan pembelian nomer : pr123','2023-05-28 19:07:06','2023-05-28 19:07:06'),
	(315,'Super administrator','Menghapus permintaan pembelian nomer : pr1','2023-05-28 19:07:18','2023-05-28 19:07:18'),
	(316,'Super administrator','Memperbarui Permintaan Pembelian nomer : pr2','2023-05-28 19:07:29','2023-05-28 19:07:29'),
	(317,'Super administrator','Membuat Invoice dengan nomer : MMG/23/05/0004','2023-05-29 09:46:33','2023-05-29 09:46:33'),
	(318,'Super administrator','Membuat Invoice dengan nomer : MMG/23/05/0005','2023-05-29 12:51:35','2023-05-29 12:51:35'),
	(319,'Super administrator','Revisi Permintaan Pembelian nomer : pr2 revisi','2023-05-29 13:56:00','2023-05-29 13:56:00'),
	(320,'Super administrator','Membuat permintaan pembelian nomer : PR/1000','2023-05-29 14:04:11','2023-05-29 14:04:11'),
	(321,'Super administrator','Revisi Permintaan Pembelian nomer : PR/1000','2023-05-29 14:04:36','2023-05-29 14:04:36'),
	(322,'Super administrator','Revisi Permintaan Pembelian nomer : PR/1000','2023-05-29 14:05:22','2023-05-29 14:05:22'),
	(323,'Super administrator','Membuat Invoice dengan nomer : MMG/23/05/0006','2023-05-29 16:13:41','2023-05-29 16:13:41'),
	(324,'Super administrator','Membuat Invoice dengan nomer : MMG/23/05/0007','2023-05-29 16:14:55','2023-05-29 16:14:55'),
	(325,'Super administrator','Membuat Invoice dengan nomer : MMG/23/05/0008','2023-05-29 16:15:27','2023-05-29 16:15:27'),
	(326,'Super administrator','Membuat Invoice dengan nomer : MMG/23/05/0009','2023-05-29 16:17:07','2023-05-29 16:17:07'),
	(327,'Super administrator','Membuat Invoice dengan nomer : MMG/23/05/0010','2023-05-29 16:17:40','2023-05-29 16:17:40'),
	(328,'Super administrator','Membuat Invoice dengan nomer : MMG/23/05/0011','2023-05-29 16:18:04','2023-05-29 16:18:04'),
	(329,'Super administrator','Merubah Invoice dengan nomer : MMG/23/05/0011','2023-05-29 16:21:46','2023-05-29 16:21:46'),
	(330,'Super administrator','Merubah Invoice dengan nomer : MMG/23/05/0011','2023-05-29 16:22:14','2023-05-29 16:22:14'),
	(331,'Super administrator','Merubah Invoice dengan nomer : MMG/23/05/0011','2023-05-29 16:22:44','2023-05-29 16:22:44'),
	(332,'Super administrator','Merubah Invoice dengan nomer : MMG/23/05/0011','2023-05-29 16:23:28','2023-05-29 16:23:28'),
	(333,'Super administrator','Merubah Invoice dengan nomer : MMG/23/05/0011','2023-05-29 16:23:49','2023-05-29 16:23:49'),
	(334,'Super administrator','Merubah Invoice dengan nomer : MMG/23/05/0011','2023-05-29 16:24:16','2023-05-29 16:24:16'),
	(335,'Super administrator','Membuat Invoice dengan nomer : MMG/23/05/0012','2023-05-29 16:25:58','2023-05-29 16:25:58'),
	(336,'Super administrator','Merubah Invoice dengan nomer : MMG/23/05/0012','2023-05-29 16:26:39','2023-05-29 16:26:39'),
	(337,'Super administrator','Membuat Invoice dengan nomer : MMG/23/05/0001','2023-05-29 22:27:10','2023-05-29 22:27:10'),
	(338,'Super administrator','Merubah Invoice dengan nomer : MMG/23/05/0001','2023-05-29 22:27:42','2023-05-29 22:27:42'),
	(339,'Super administrator','Merubah Invoice dengan nomer : MMG/23/05/0001','2023-05-29 22:29:10','2023-05-29 22:29:10'),
	(340,'Super administrator','Merubah Invoice dengan nomer : MMG/23/05/0001','2023-05-29 22:29:43','2023-05-29 22:29:43'),
	(341,'Super administrator','Merubah Invoice dengan nomer : MMG/23/05/0001','2023-05-29 22:48:46','2023-05-29 22:48:46'),
	(342,'Super administrator','Membuat pindah stok nomer : 1234','2023-05-30 09:49:12','2023-05-30 09:49:12'),
	(343,'Super administrator','Membuat pindah stok nomer : 321','2023-05-30 09:50:17','2023-05-30 09:50:17'),
	(344,'Super administrator','Memproses pindah stok dengan nomer : 1234','2023-05-30 10:09:10','2023-05-30 10:09:10'),
	(345,'Super administrator','Memproses pindah stok dengan nomer : 1234','2023-05-30 10:24:13','2023-05-30 10:24:13'),
	(346,'Super administrator','Membuat pindah stok nomer : 1111','2023-05-30 10:27:17','2023-05-30 10:27:17'),
	(347,'Super administrator','Memproses pindah stok dengan nomer : 1111','2023-05-30 10:27:38','2023-05-30 10:27:38'),
	(348,'Super administrator','Membuat pindah stok nomer : 222','2023-05-30 10:28:30','2023-05-30 10:28:30'),
	(349,'Super administrator','Memproses pindah stok dengan nomer : 222','2023-05-30 10:28:42','2023-05-30 10:28:42'),
	(350,'Super administrator','Membuat pindah stok nomer : 1','2023-05-31 09:41:05','2023-05-31 09:41:05'),
	(351,'Super administrator','Memperbarui pindah stok nomer : 1','2023-05-31 09:59:22','2023-05-31 09:59:22'),
	(352,'Super administrator','Memperbarui pindah stok nomer : 1x','2023-05-31 09:59:36','2023-05-31 09:59:36'),
	(353,'Super administrator','Membuat Pesanan Penjualan Baru dengan nomer : SO-001/VI/2023','2023-06-05 08:21:46','2023-06-05 08:21:46'),
	(354,'Super administrator','Membuat Pesanan Penjualan Baru dengan nomer : SO-001/VI/2023','2023-06-05 15:53:38','2023-06-05 15:53:38'),
	(355,'Super administrator','Membuat Invoice dengan nomer : MMG/23/06/00002','2023-06-05 16:18:12','2023-06-05 16:18:12'),
	(356,'Super administrator','Membuat penerimaan penjualan nomer : 1','2023-06-05 20:48:39','2023-06-05 20:48:39'),
	(357,'Super administrator','Membuat penerimaan penjualan nomer : 1','2023-06-05 20:52:32','2023-06-05 20:52:32'),
	(358,'Super administrator','Merubah penerimaan penjualan nomer : 12','2023-06-05 20:53:03','2023-06-05 20:53:03'),
	(359,'Super administrator','Merubah penerimaan penjualan nomer : 12','2023-06-05 20:53:43','2023-06-05 20:53:43'),
	(360,'Super administrator','Merubah penerimaan penjualan nomer : 12','2023-06-05 20:54:02','2023-06-05 20:54:02'),
	(361,'Super administrator','Merubah penerimaan penjualan nomer : 12','2023-06-05 20:55:54','2023-06-05 20:55:54'),
	(362,'Super administrator','Membuat penerimaan penjualan nomer : 1234','2023-06-06 10:16:14','2023-06-06 10:16:14'),
	(363,'Super administrator','Membuat penerimaan penjualan nomer : 2','2023-06-06 10:21:57','2023-06-06 10:21:57'),
	(364,'Super administrator','Merubah penerimaan penjualan nomer : 2','2023-06-06 10:31:08','2023-06-06 10:31:08'),
	(365,'Super administrator','Merubah penerimaan penjualan nomer : 2','2023-06-06 10:34:57','2023-06-06 10:34:57'),
	(366,'Super administrator','Membuat penerimaan penjualan nomer : 22','2023-06-06 10:35:48','2023-06-06 10:35:48'),
	(367,'Super administrator','Merubah penerimaan penjualan nomer : 22','2023-06-06 10:36:08','2023-06-06 10:36:08'),
	(368,'Super administrator','Merubah penerimaan penjualan nomer : 22','2023-06-06 10:36:37','2023-06-06 10:36:37'),
	(369,'Super administrator','Membuat penerimaan penjualan nomer : 3','2023-06-06 10:36:59','2023-06-06 10:36:59'),
	(370,'Super administrator','Merubah penerimaan penjualan nomer : 3','2023-06-06 10:37:23','2023-06-06 10:37:23'),
	(371,'Super administrator','Membuat penerimaan penjualan nomer : e3e3e3','2023-06-06 12:37:35','2023-06-06 12:37:35'),
	(372,'Super administrator','Membuat penerimaan penjualan nomer : 1q1q1q1','2023-06-06 12:37:58','2023-06-06 12:37:58'),
	(373,'Super administrator','Membuat penerimaan penjualan nomer : 5t5t5','2023-06-06 12:38:15','2023-06-06 12:38:15'),
	(374,'Super administrator','Membuat penerimaan penjualan nomer : 1234','2023-06-06 12:42:32','2023-06-06 12:42:32'),
	(375,'Super administrator','Membuat permintaan pembelian nomer : 12345','2023-06-08 16:56:10','2023-06-08 16:56:10'),
	(376,'Super administrator','Membuat pesanan penjualan nomer : ','2023-06-10 15:48:53','2023-06-10 15:48:53'),
	(377,'Super administrator','Membuat pesanan penjualan nomer : ','2023-06-10 16:06:28','2023-06-10 16:06:28'),
	(378,'Super administrator','Membuat pesanan penjualan nomer : ','2023-06-10 16:07:01','2023-06-10 16:07:01'),
	(379,'Super administrator','Membuat pesanan penjualan nomer : ','2023-06-10 16:33:47','2023-06-10 16:33:47'),
	(380,'Super administrator','Membuat pesanan penjualan nomer : ','2023-06-10 16:36:20','2023-06-10 16:36:20'),
	(381,'Super administrator','Membuat pesanan penjualan nomer : ','2023-06-10 17:30:00','2023-06-10 17:30:00'),
	(382,'Super administrator','Membuat pesanan penjualan nomer : ','2023-06-10 17:30:43','2023-06-10 17:30:43'),
	(383,'Super administrator','Membuat pesanan penjualan nomer : ','2023-06-10 17:31:17','2023-06-10 17:31:17'),
	(384,'Super administrator','Membuat pesanan penjualan nomer : ','2023-06-10 17:32:26','2023-06-10 17:32:26'),
	(385,'Super administrator','Membuat pesanan penjualan nomer : ','2023-06-10 17:39:12','2023-06-10 17:39:12'),
	(386,'Super administrator','Membuat pesanan penjualan nomer : ','2023-06-10 17:41:41','2023-06-10 17:41:41'),
	(387,'Super administrator','Membuat pesanan penjualan nomer : ','2023-06-10 17:49:12','2023-06-10 17:49:12'),
	(388,'Super administrator','Membuat pesanan penjualan nomer : ','2023-06-10 17:49:41','2023-06-10 17:49:41'),
	(389,'Super administrator','Membuat pesanan penjualan nomer : ','2023-06-10 17:50:53','2023-06-10 17:50:53'),
	(390,'Super administrator','Membuat pesanan penjualan nomer : ','2023-06-10 17:51:26','2023-06-10 17:51:26'),
	(391,'Super administrator','Membuat pesanan penjualan nomer : ','2023-06-10 17:52:04','2023-06-10 17:52:04'),
	(392,'Super administrator','Membuat pesanan penjualan nomer : ','2023-06-10 17:52:20','2023-06-10 17:52:20'),
	(393,'Super administrator','Membuat pesanan penjualan nomer : ','2023-06-10 17:53:01','2023-06-10 17:53:01'),
	(394,'Super administrator','Membuat pesanan penjualan nomer : ','2023-06-10 17:53:35','2023-06-10 17:53:35'),
	(395,'Super administrator','Membuat pesanan penjualan nomer : ','2023-06-10 17:53:44','2023-06-10 17:53:44'),
	(396,'Super administrator','Membuat pesanan penjualan nomer : ','2023-06-10 18:06:03','2023-06-10 18:06:03'),
	(397,'Super administrator','Membuat pesanan penjualan nomer : ','2023-06-10 18:14:18','2023-06-10 18:14:18'),
	(398,'Super administrator','Membuat pesanan penjualan nomer : ','2023-06-10 18:17:15','2023-06-10 18:17:15'),
	(399,'Super administrator','Membuat pesanan penjualan nomer : ','2023-06-10 18:28:54','2023-06-10 18:28:54'),
	(400,'Super administrator','Membuat pesanan pembelian nomer : PO/1002','2023-06-10 18:30:04','2023-06-10 18:30:04'),
	(401,'Super administrator','Membuat pesanan pembelian nomer : PO/2000','2023-06-12 08:43:52','2023-06-12 08:43:52'),
	(402,'Super administrator','Membuat pesanan pembelian nomer : PO/2001','2023-06-12 08:49:17','2023-06-12 08:49:17'),
	(403,'Super administrator','Membuat permintaan pembelian nomer : PR/1000','2023-06-12 16:57:23','2023-06-12 16:57:23'),
	(404,'Super administrator','Membuat pesanan pembelian nomer : PO/1000','2023-06-12 16:58:06','2023-06-12 16:58:06'),
	(405,'Super administrator','Membuat pesanan pembelian nomer : PO/1001','2023-06-12 16:58:51','2023-06-12 16:58:51'),
	(406,'Super administrator','Membuat pesanan pembelian nomer : PO/1002','2023-06-12 16:59:26','2023-06-12 16:59:26'),
	(407,'Super administrator','Membuat pesanan pembelian nomer : PO/1003','2023-06-12 17:05:02','2023-06-12 17:05:02'),
	(408,'Super administrator','Membuat pesanan pembelian nomer : PO/1000','2023-06-12 17:05:50','2023-06-12 17:05:50'),
	(409,'Super administrator','Membuat pesanan pembelian nomer : PO/1002','2023-06-12 17:06:31','2023-06-12 17:06:31'),
	(410,'Super administrator','Membuat pesanan pembelian nomer : PO/1003','2023-06-12 18:25:46','2023-06-12 18:25:46'),
	(411,'Super administrator','Membuat pesanan pembelian nomer : PO/1000','2023-06-12 18:30:07','2023-06-12 18:30:07'),
	(412,'Super administrator','Mengubah pesanan pembelian nomer : PO/1000','2023-06-12 18:30:24','2023-06-12 18:30:24'),
	(413,'Super administrator','Mengubah pesanan pembelian nomer : PO/1000','2023-06-13 08:32:20','2023-06-13 08:32:20'),
	(414,'Super administrator','Membuat pesanan pembelian nomer : PO/3001','2023-06-13 08:32:45','2023-06-13 08:32:45'),
	(415,'Super administrator','Mengubah pesanan pembelian nomer : PO/3001','2023-06-13 08:33:05','2023-06-13 08:33:05'),
	(416,'Super administrator','Mengubah pesanan pembelian nomer : PO/3001','2023-06-13 08:33:22','2023-06-13 08:33:22'),
	(417,'Super administrator','Membuat pesanan pembelian nomer : PO/1000','2023-06-13 08:43:35','2023-06-13 08:43:35'),
	(418,'Super administrator','Mengubah pesanan pembelian nomer : PO/1000','2023-06-13 08:43:53','2023-06-13 08:43:53'),
	(419,'Super administrator','Mengubah pesanan pembelian nomer : PO/1000','2023-06-13 08:44:00','2023-06-13 08:44:00'),
	(420,'Super administrator','Membuat pesanan pembelian nomer : PO/1002','2023-06-13 09:01:44','2023-06-13 09:01:44'),
	(421,'Super administrator','Mengubah pesanan pembelian nomer : PO/1002','2023-06-13 09:06:04','2023-06-13 09:06:04'),
	(422,'Super administrator','Membuat pesanan pembelian nomer : PO/1004','2023-06-13 09:06:28','2023-06-13 09:06:28'),
	(423,'Super administrator','Menghapus pesanan pembelian nomer : PO/1004','2023-06-13 09:09:29','2023-06-13 09:09:29'),
	(424,'Super administrator','Menghapus pesanan pembelian nomer : PO/1002','2023-06-13 09:11:01','2023-06-13 09:11:01'),
	(425,'Super administrator','Membuat pesanan pembelian nomer : PO/1000','2023-06-13 09:12:43','2023-06-13 09:12:43'),
	(426,'Super administrator','Mengubah pesanan pembelian nomer : PO/1000','2023-06-13 09:13:22','2023-06-13 09:13:22'),
	(427,'Super administrator','Mengubah pesanan pembelian nomer : PO/1000','2023-06-13 09:23:08','2023-06-13 09:23:08'),
	(428,'Super administrator','Revisi Permintaan Pembelian nomer : PR/1000','2023-06-13 09:26:12','2023-06-13 09:26:12'),
	(429,'Super administrator','Membuat pesanan pembelian nomer : PO/1000','2023-06-13 10:08:57','2023-06-13 10:08:57'),
	(430,'Super administrator','Membuat pesanan pembelian nomer : PO/1000','2023-06-13 10:11:00','2023-06-13 10:11:00'),
	(431,'Super administrator','Membuat permintaan pembelian nomer : PR/1000','2023-06-13 10:24:28','2023-06-13 10:24:28'),
	(432,'Super administrator','Revisi Permintaan Pembelian nomer : PR/1000','2023-06-13 10:25:44','2023-06-13 10:25:44'),
	(433,'Super administrator','Membuat pesanan pembelian nomer : PO/1000','2023-06-13 10:29:09','2023-06-13 10:29:09'),
	(434,'Super administrator','Mengubah pesanan pembelian nomer : PO/1000','2023-06-13 10:46:31','2023-06-13 10:46:31'),
	(435,'Super administrator','Membuat permintaan pembelian nomer : PR/1000','2023-06-13 12:23:31','2023-06-13 12:23:31'),
	(436,'Super administrator','Menyetujui Permintaan pembelian nomer : PR/1000','2023-06-13 12:24:13','2023-06-13 12:24:13'),
	(437,'Super administrator','Membuat pesanan pembelian nomer : PO/1000','2023-06-13 12:24:43','2023-06-13 12:24:43'),
	(438,'Super administrator','Mengubah pesanan pembelian nomer : PO/1000','2023-06-13 12:38:20','2023-06-13 12:38:20'),
	(439,'Super administrator','Membuat permintaan pembelian nomer : PR/1002','2023-06-13 12:54:46','2023-06-13 12:54:46'),
	(440,'Super administrator','Memperbarui Permintaan Pembelian nomer : PR/1002','2023-06-13 12:56:05','2023-06-13 12:56:05'),
	(441,'Super administrator','Memperbarui Permintaan Pembelian nomer : PR/1002','2023-06-13 12:56:40','2023-06-13 12:56:40'),
	(442,'Rizki Darmawan','Menyetujui Permintaan pembelian nomer : PR/1002','2023-06-13 12:57:35','2023-06-13 12:57:35'),
	(443,'Super administrator','Revisi Permintaan Pembelian nomer : PR/1002','2023-06-13 12:59:49','2023-06-13 12:59:49'),
	(444,'Super administrator','Memperbarui Permintaan Pembelian nomer : PR/1002','2023-06-13 13:00:52','2023-06-13 13:00:52'),
	(445,'Rizki Darmawan','Menyetujui Permintaan pembelian nomer : PR/1002','2023-06-13 13:01:13','2023-06-13 13:01:13'),
	(446,'Super administrator','Membuat PO nomer : PO/1001','2023-06-13 13:02:39','2023-06-13 13:02:39'),
	(447,'Super administrator','Mengubah PO nomer : PO/1001-ubah','2023-06-13 13:03:25','2023-06-13 13:03:25'),
	(448,'Rizki Darmawan','Menyetujui PO nomer : PO/1001-ubah','2023-06-13 13:04:16','2023-06-13 13:04:16'),
	(449,'Super administrator','Membuat pesanan pembelian nomer : PO/1001-ubah','2023-06-13 13:06:50','2023-06-13 13:06:50'),
	(450,'Rizki Darmawan','Menyetujui PO nomer : PO/1001-ubah','2023-06-13 13:08:39','2023-06-13 13:08:39'),
	(451,'M. Mirzam Denofal','Menyetujui PO nomer : PO/1001-ubah','2023-06-13 13:09:00','2023-06-13 13:09:00'),
	(452,'Super administrator','Membuat pesanan pembelian nomer : PO/1001-ubah','2023-06-13 13:22:33','2023-06-13 13:22:33'),
	(453,'Super administrator','Membuat pesanan pembelian nomer : PO/1000','2023-06-13 13:23:45','2023-06-13 13:23:45'),
	(454,'Super administrator','Membuat pesanan pembelian nomer : PO/1','2023-06-13 13:26:20','2023-06-13 13:26:20'),
	(455,'Super administrator','Membuat pesanan pembelian nomer : PO/1','2023-06-13 13:28:22','2023-06-13 13:28:22'),
	(456,'Super administrator','Membuat pesanan pembelian nomer : PO/1','2023-06-13 13:29:08','2023-06-13 13:29:08'),
	(457,'Super administrator','Membuat pesanan pembelian nomer : PO/1','2023-06-13 13:30:56','2023-06-13 13:30:56'),
	(458,'Super administrator','Membuat penerimaan kasbank nomer : bbm/1001','2023-06-19 20:22:06','2023-06-19 20:22:06'),
	(459,'Super administrator','Mengubah penerimaan kasbank nomer : bbm/10001 x','2023-06-19 20:25:25','2023-06-19 20:25:25'),
	(460,'Super administrator','Mengubah penerimaan kasbank nomer : bbm/10001 x','2023-06-19 20:25:44','2023-06-19 20:25:44'),
	(461,'Super administrator','Download berkas penerimaan kasbank : bbm_10001_x_New_Project.png','2023-06-19 20:26:32','2023-06-19 20:26:32'),
	(462,'Super administrator','Download berkas penerimaan kasbank : bbm_10001_x_New_Project.png','2023-06-19 20:26:32','2023-06-19 20:26:32'),
	(463,'Super administrator','Download berkas penerimaan kasbank : bbm_10001_x_New_Project.png','2023-06-19 20:26:41','2023-06-19 20:26:41'),
	(464,'Super administrator','Download berkas penerimaan kasbank : bbm_10001_x_New_Project.png','2023-06-19 20:26:41','2023-06-19 20:26:41'),
	(465,'Super administrator','Menghapus penerimaan kasbank nomer : bbm/10001 x','2023-06-19 20:27:03','2023-06-19 20:27:03'),
	(466,'Super administrator','Menghapus penerimaan kasbank nomer : bbm/1001','2023-06-19 20:27:08','2023-06-19 20:27:08'),
	(467,'Super administrator','Menghapus pembayaran kasbank nomer : bbk/1001','2023-06-20 11:22:58','2023-06-20 11:22:58'),
	(468,'Super administrator','Membuat pembayaran kasbank nomer : bbk/1001','2023-06-20 11:23:16','2023-06-20 11:23:16'),
	(469,'Super administrator','Menghapus pembayaran kasbank nomer : bbk/1001','2023-06-20 11:30:23','2023-06-20 11:30:23'),
	(470,'Super administrator','Menghapus pembayaran kasbank nomer : bbk/1001','2023-06-20 11:32:54','2023-06-20 11:32:54'),
	(471,'Super administrator','Menghapus pembayaran kasbank nomer : bbk/1002','2023-06-20 11:32:57','2023-06-20 11:32:57'),
	(472,'Super administrator','Membuat pembayaran kasbank nomer : bbk/1001','2023-06-20 11:33:53','2023-06-20 11:33:53'),
	(473,'Super administrator','Membuat pembayaran kasbank nomer : bbk/1002','2023-06-20 11:36:11','2023-06-20 11:36:11'),
	(474,'Super administrator','Membuat pembayaran kasbank nomer : mandiri/1000','2023-06-20 11:50:38','2023-06-20 11:50:38'),
	(475,'Super administrator','Membuat pembayaran kasbank nomer : bbk/10003','2023-06-20 11:51:16','2023-06-20 11:51:16'),
	(476,'Super administrator','Membuat pembayaran kasbank nomer : bbk/1000','2023-06-20 18:32:54','2023-06-20 18:32:54'),
	(477,'Super administrator','Membuat pembayaran kasbank nomer : bbk/1002','2023-06-20 18:52:09','2023-06-20 18:52:09'),
	(478,'Super administrator','Membuat pembayaran kasbank nomer : bbk/1001','2023-06-20 18:55:44','2023-06-20 18:55:44'),
	(479,'Super administrator','Membuat pembayaran kasbank nomer : bbk/1002','2023-06-20 18:56:21','2023-06-20 18:56:21'),
	(480,'Super administrator','Membuat pembayaran kasbank nomer : bbk/1003','2023-06-20 18:57:06','2023-06-20 18:57:06'),
	(481,'Super administrator','Membuat pembayaran kasbank nomer : bbk/1000','2023-06-20 19:29:27','2023-06-20 19:29:27'),
	(482,'Super administrator','Membuat pembayaran kasbank nomer : bbk/1002','2023-06-20 19:29:49','2023-06-20 19:29:49'),
	(483,'Super administrator','Membuat pembayaran kasbank nomer : bbk/1003','2023-06-20 19:31:29','2023-06-20 19:31:29'),
	(484,'Super administrator','Menghapus pembayaran kasbank nomer : bbk/1003','2023-06-20 19:39:37','2023-06-20 19:39:37'),
	(485,'Super administrator','Membuat pembayaran kasbank nomer : bbk/1003','2023-06-20 19:42:54','2023-06-20 19:42:54'),
	(486,'Super administrator','Menghapus pembayaran kasbank nomer : bbk/1003','2023-06-20 19:43:21','2023-06-20 19:43:21'),
	(487,'Super administrator','Membuat pembayaran kasbank nomer : bbk/1003','2023-06-20 19:48:53','2023-06-20 19:48:53'),
	(488,'Super administrator','Menghapus pembayaran kasbank nomer : bbk/1003','2023-06-20 20:16:05','2023-06-20 20:16:05'),
	(489,'Super administrator','Membuat pembayaran kasbank nomer : bkk/1000','2023-06-20 20:28:18','2023-06-20 20:28:18'),
	(490,'Super administrator','Menghapus pembayaran kasbank nomer : bkk/1000','2023-06-21 08:13:14','2023-06-21 08:13:14'),
	(491,'Super administrator','Membuat pembayaran kasbank nomer : BBK/1000','2023-06-21 08:14:15','2023-06-21 08:14:15'),
	(492,'Super administrator','Membuat pembayaran kasbank nomer : bbk/1001','2023-06-21 08:16:58','2023-06-21 08:16:58'),
	(493,'Super administrator','Mengubah pembayaran kasbank nomer : bbk/1001','2023-06-21 08:17:52','2023-06-21 08:17:52'),
	(494,'Super administrator','Mengubah pembayaran kasbank nomer : bbk/1001','2023-06-21 08:18:14','2023-06-21 08:18:14'),
	(495,'Super administrator','Mengubah pembayaran kasbank nomer : bbk/1001','2023-06-21 08:19:46','2023-06-21 08:19:46'),
	(496,'Super administrator','Mengubah pembayaran kasbank nomer : bbk/1001','2023-06-21 08:20:13','2023-06-21 08:20:13'),
	(497,'Super administrator','Mengubah pembayaran kasbank nomer : bbk/1001x','2023-06-21 08:20:31','2023-06-21 08:20:31'),
	(498,'Super administrator','Membuat pembayaran kasbank nomer : bb2','2023-06-21 08:21:04','2023-06-21 08:21:04'),
	(499,'Super administrator','Menghapus pembayaran kasbank nomer : BBK/1000','2023-06-21 08:21:37','2023-06-21 08:21:37'),
	(500,'Super administrator','Menghapus pembayaran kasbank nomer : bbk/1001x','2023-06-21 08:21:43','2023-06-21 08:21:43'),
	(501,'Super administrator','Menghapus pembayaran kasbank nomer : bb2','2023-06-21 08:21:47','2023-06-21 08:21:47'),
	(502,'Super administrator','Membuat penerimaan kasbank nomer : bbm/1000','2023-06-21 08:25:28','2023-06-21 08:25:28'),
	(503,'Super administrator','Membuat penerimaan kasbank nomer : bbm/1001','2023-06-21 08:26:06','2023-06-21 08:26:06'),
	(504,'Super administrator','Mengubah penerimaan kasbank nomer : bbm/1000x','2023-06-21 08:26:37','2023-06-21 08:26:37'),
	(505,'Super administrator','Menghapus pembayaran kasbank nomer : bbk/1000','2023-06-21 09:13:18','2023-06-21 09:13:18'),
	(506,'Super administrator','Membuat pembayaran kasbank nomer : bbk/1000','2023-06-21 09:21:25','2023-06-21 09:21:25'),
	(507,'Super administrator','Menghapus pembayaran kasbank nomer : bbk/1000','2023-06-21 09:22:15','2023-06-21 09:22:15'),
	(508,'Super administrator','Menghapus penerimaan kasbank nomer : bbm/1001','2023-06-21 09:22:21','2023-06-21 09:22:21'),
	(509,'Super administrator','Menghapus penerimaan kasbank nomer : bbm/1000x','2023-06-21 09:22:24','2023-06-21 09:22:24'),
	(510,'Super administrator','Membuat pembayaran kasbank nomer : bbk/1000','2023-06-21 09:22:42','2023-06-21 09:22:42'),
	(511,'Super administrator','Membuat pembayaran kasbank nomer : bbk/1001','2023-06-21 09:23:29','2023-06-21 09:23:29'),
	(512,'Super administrator','Mengubah pembayaran kasbank nomer : bbk/1001','2023-06-21 09:51:11','2023-06-21 09:51:11'),
	(513,'Super administrator','Menghapus penerimaan kasbank nomer : bbm/1000','2023-06-21 10:28:51','2023-06-21 10:28:51'),
	(514,'Super administrator','Menghapus penerimaan kasbank nomer : bkm/1000','2023-06-21 10:28:55','2023-06-21 10:28:55'),
	(515,'Super administrator','Membuat penerimaan kasbank nomer : bkm/1000','2023-06-21 10:29:10','2023-06-21 10:29:10'),
	(516,'Super administrator','Mengubah penerimaan kasbank nomer : bkm/1000x','2023-06-21 10:29:39','2023-06-21 10:29:39'),
	(517,'Super administrator','Mengubah penerimaan kasbank nomer : bkm/1000','2023-06-21 10:31:27','2023-06-21 10:31:27'),
	(518,'Super administrator','Mengubah penerimaan kasbank nomer : bkm/1000','2023-06-21 10:32:01','2023-06-21 10:32:01'),
	(519,'Super administrator','Menghapus penerimaan kasbank nomer : bkm/1000','2023-06-21 10:33:36','2023-06-21 10:33:36'),
	(520,'Super administrator','Membuat penerimaan kasbank nomer : bbm/1000','2023-06-21 10:34:29','2023-06-21 10:34:29'),
	(521,'Super administrator','Mengubah penerimaan kasbank nomer : bbm/1000x','2023-06-21 10:35:13','2023-06-21 10:35:13'),
	(522,'Super administrator','Menghapus pembayaran kasbank nomer : bbk/1000','2023-06-21 10:36:18','2023-06-21 10:36:18'),
	(523,'Super administrator','Menghapus pembayaran kasbank nomer : bbk/1001','2023-06-21 10:36:21','2023-06-21 10:36:21'),
	(524,'Super administrator','Membuat pembayaran kasbank nomer : bbk/1000','2023-06-21 10:38:32','2023-06-21 10:38:32'),
	(525,'Super administrator','Mengubah pembayaran kasbank nomer : bbk/1000x','2023-06-21 10:39:28','2023-06-21 10:39:28'),
	(526,'Super administrator','Mengubah penerimaan kasbank nomer : bbm/1000x','2023-06-21 11:07:40','2023-06-21 11:07:40'),
	(527,'Super administrator','Mengubah penerimaan kasbank nomer : bbm/1000x','2023-06-21 11:09:31','2023-06-21 11:09:31'),
	(528,'Super administrator','Mengubah pembayaran kasbank nomer : bbk/1000x','2023-06-21 11:11:25','2023-06-21 11:11:25'),
	(529,'Super administrator','Membuat Invoice dengan nomer : MMG/23/06/00003','2023-06-21 19:03:50','2023-06-21 19:03:50'),
	(530,'Super administrator','Merubah Invoice dengan nomer : MMG/23/06/00003','2023-06-21 19:17:22','2023-06-21 19:17:22'),
	(531,'Super administrator','Membuat Invoice dengan nomer : MMG/23/06/00004','2023-06-21 19:18:11','2023-06-21 19:18:11'),
	(532,'Super administrator','Merubah Invoice dengan nomer : MMG/23/06/00003','2023-06-21 19:20:43','2023-06-21 19:20:43');

/*!40000 ALTER TABLE `log_aktifitas` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table migrations
# ------------------------------------------------------------

DROP TABLE IF EXISTS `migrations`;

CREATE TABLE `migrations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(191) NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;

INSERT INTO `migrations` (`id`, `migration`, `batch`)
VALUES
	(1,'2023_04_13_121425_create_penjualan_pesanan_table',1),
	(2,'2023_04_13_143023_create_log_aktifitas_table',1),
	(3,'2023_04_15_155039_create_pelanggan_table',1),
	(4,'2023_04_26_082325_create_jenis_penjualan_table',1),
	(5,'2023_04_29_103252_create_gudang_table',1),
	(6,'2023_04_29_183706_create_konsinyasi_table',1),
	(7,'2023_04_29_201017_create_ability_table',1),
	(8,'2023_04_29_201110_create_ability_user_table',1),
	(9,'2023_05_03_181018_create_penjualan_pengiriman_table',1),
	(10,'2023_05_04_130956_create_stok_produk_gudang_table',1),
	(11,'2023_05_06_231248_add_relasi_konsinyasi_rinci',2),
	(12,'2023_05_06_231443_add_relasi_konsinyasi_berkas',3),
	(17,'2023_05_07_121315_add_gudang_id_to_penjualan_pesanan',4),
	(18,'2023_05_07_121506_add_gudang_id_to_penjualan_pesanan_rinci',4),
	(19,'2023_05_07_132734_create_nomer_dihapus_table',5),
	(20,'2023_05_07_221943_create_jobs_table',6),
	(21,'2023_05_08_132927_create_penjualan_tester_table',7),
	(22,'2023_05_09_115833_add_relasi_permintaan_tester_rinci',8),
	(23,'2023_05_09_115919_add_relasi_permintaan_tester_berkas',8),
	(25,'2023_05_10_203256_create_transaksi_stok_table',9),
	(28,'2023_05_13_170823_create_coa_tipe_table',10),
	(29,'2023_05_13_172416_create_coa_table',11),
	(39,'2023_05_13_212450_add_akun_akun_to_penjualan_pesanan',12),
	(40,'2023_05_13_212726_add_biaya_kirim_to_penjualan_pesanan',12),
	(41,'2023_05_13_214201_add_nilai_ppn_to_penjualan_pesanan',12),
	(49,'2023_05_14_115022_create_penjualan_invoice_table',13),
	(50,'2023_05_14_131328_add_relasi_penjualan_pengiriman_ke_penjualan_pesanan',14),
	(51,'2023_05_15_095407_create_penjualan_konsinyasi_table',15),
	(52,'2023_05_22_122901_drop_foreign_penjualan_pesanan_id_to_penjualan_invoice',16),
	(53,'2023_05_22_123348_add_nomer_ref_to_penjualan_invoice',17),
	(54,'2023_05_23_090108_change_diskon_penjualan_invoice',18),
	(71,'2023_05_23_183223_create_permintaan_pembelian_table',19),
	(72,'2023_05_28_164521_create_failed_jobs_table',20),
	(105,'2023_05_29_193406_create_pindah_stok_table',21),
	(106,'2023_06_05_104757_create_penerimaan_penjualan_table',21),
	(107,'2023_06_05_145640_add_sudah_terbayar_to_penjualan_invoice',21),
	(139,'2023_06_06_185922_create_tipe_supplier_table',22),
	(140,'2023_06_06_190025_create_supplier_table',22),
	(141,'2023_06_06_190100_create_pesanan_pembelian_table',22),
	(163,'2023_06_18_121205_create_kasbank_table',23),
	(164,'2023_06_19_200602_create_kasbank_penerimaan_table',23),
	(165,'2023_06_19_203814_create_bukubank_table',23),
	(169,'2023_06_21_082714_create_buku_besar_table',24),
	(172,'2023_06_21_110137_add_updated_by_to_kasbank_pembayaran',25),
	(173,'2023_06_21_110202_add_updated_by_to_kasbank_penerimaan',25),
	(176,'2023_06_21_231740_create_jurnal_umum_table',26);

/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table nomer_dihapus
# ------------------------------------------------------------

DROP TABLE IF EXISTS `nomer_dihapus`;

CREATE TABLE `nomer_dihapus` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `nama_modul` varchar(191) DEFAULT NULL,
  `nomer` varchar(191) DEFAULT NULL,
  `sudah_dipakai` varchar(191) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

LOCK TABLES `nomer_dihapus` WRITE;
/*!40000 ALTER TABLE `nomer_dihapus` DISABLE KEYS */;

INSERT INTO `nomer_dihapus` (`id`, `nama_modul`, `nomer`, `sudah_dipakai`, `created_at`, `updated_at`)
VALUES
	(1,'pesanan_penjualan','SO-001/V/2023','1','2023-05-11 09:05:21','2023-05-23 16:59:33'),
	(2,'pesanan_penjualan','SO-002/V/2023','1','2023-05-11 09:05:56','2023-05-23 17:03:17'),
	(3,'pesanan_penjualan','SO-003/V/2023','1','2023-05-11 09:06:38','2023-05-22 20:47:46'),
	(4,'pesanan_penjualan','SO-013/V/2023','1','2023-05-11 09:12:53','2023-05-11 14:18:20'),
	(5,'pesanan_penjualan','SO-012/V/2023','1','2023-05-11 09:13:04','2023-05-14 12:30:21'),
	(6,'pesanan_penjualan','SO-011/V/2023','1','2023-05-11 09:13:17','2023-05-14 12:26:52'),
	(7,'pesanan_penjualan','SO-009/V/2023','1','2023-05-11 09:13:51','2023-05-14 13:02:58'),
	(8,'pesanan_penjualan','SO-008/V/2023','1','2023-05-11 09:14:03','2023-05-14 17:28:20'),
	(9,'pesanan_penjualan','SO-007/V/2023','1','2023-05-11 09:14:17','2023-05-14 17:28:09'),
	(10,'pesanan_penjualan','SO-006/V/2023','1','2023-05-11 09:14:31','2023-05-23 11:50:17'),
	(11,'pesanan_penjualan','SO-002/V/2023','1','2023-05-11 09:38:29','2023-05-23 17:03:17'),
	(12,'pesanan_penjualan','SO-006/V/2023','1','2023-05-11 17:39:27','2023-05-23 11:50:17'),
	(13,'pesanan_penjualan','SO-007/V/2023','1','2023-05-11 17:39:45','2023-05-14 17:28:09'),
	(14,'pesanan_penjualan','SO-008/V/2023','1','2023-05-11 17:49:41','2023-05-14 17:28:20'),
	(15,'pesanan_penjualan','SO-009/V/2023','1','2023-05-11 17:49:52','2023-05-14 13:02:58'),
	(16,'pesanan_penjualan','SO-009/V/2023','1','2023-05-13 23:02:48','2023-05-14 13:02:58'),
	(17,'pesanan_penjualan','SO-008/V/2023','1','2023-05-13 23:02:57','2023-05-14 17:28:20'),
	(18,'pesanan_penjualan','SO-007/V/2023','1','2023-05-13 23:03:05','2023-05-14 17:28:09'),
	(19,'pesanan_penjualan','SO-006/V/2023','1','2023-05-13 23:03:13','2023-05-23 11:50:17'),
	(20,'pesanan_penjualan','SO-002/V/2023','1','2023-05-13 23:03:21','2023-05-23 17:03:17'),
	(21,'pesanan_penjualan','SO-011/V/2023','1','2023-05-13 23:03:29','2023-05-14 12:26:52'),
	(22,'pesanan_penjualan','SO-012/V/2023','1','2023-05-13 23:03:36','2023-05-14 12:30:21'),
	(23,'pesanan_penjualan','SO-002/V/2023','1','2023-05-22 12:52:19','2023-05-23 17:03:17'),
	(24,'pesanan_penjualan','SO-001/V/2023','1','2023-05-22 12:52:42','2023-05-23 16:59:33');

/*!40000 ALTER TABLE `nomer_dihapus` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table pelanggan
# ------------------------------------------------------------

DROP TABLE IF EXISTS `pelanggan`;

CREATE TABLE `pelanggan` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `tipe_pelanggan` varchar(191) DEFAULT NULL,
  `kode_pelanggan` varchar(191) NOT NULL,
  `kode_area` varchar(191) DEFAULT NULL,
  `nama_pelanggan` varchar(191) NOT NULL,
  `no_handphone` varchar(191) DEFAULT NULL,
  `email` varchar(191) DEFAULT NULL,
  `detil_alamat` varchar(191) DEFAULT NULL,
  `kota` varchar(191) DEFAULT NULL,
  `provinsi` varchar(191) DEFAULT NULL,
  `keterangan` text DEFAULT NULL,
  `status_aktif` varchar(191) NOT NULL DEFAULT '1',
  `saldo` double DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `pelanggan_nama_pelanggan_unique` (`nama_pelanggan`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

LOCK TABLES `pelanggan` WRITE;
/*!40000 ALTER TABLE `pelanggan` DISABLE KEYS */;

INSERT INTO `pelanggan` (`id`, `tipe_pelanggan`, `kode_pelanggan`, `kode_area`, `nama_pelanggan`, `no_handphone`, `email`, `detil_alamat`, `kota`, `provinsi`, `keterangan`, `status_aktif`, `saldo`, `created_at`, `updated_at`)
VALUES
	(1,'1','100001','Surabaya','KLT KLINIK',NULL,NULL,'JL BRATANG BINANGUN NO 12 B, SURABAYA','KOTA SURABAYA','JAWA TIMUR',NULL,'1',0,'2022-10-31 14:39:31','2022-11-18 13:11:49'),
	(2,'1','100002','Sidoarjo','Lala Hermawati',NULL,NULL,'-','KABUPATEN SIDOARJO','JAWA TIMUR',NULL,'1',0,'2022-10-31 15:29:36','2022-10-31 15:29:36'),
	(3,'1','100003','Semarang','Linda Jaya',NULL,NULL,'-','KABUPATEN SEMARANG','JAWA TENGAH',NULL,'1',0,'2022-10-31 15:37:38','2022-10-31 15:37:38'),
	(4,'1','100004','Jakarta','Desriani',NULL,NULL,'-','KOTA JAKARTA PUSAT','DKI JAKARTA',NULL,'1',0,'2022-10-31 15:41:45','2022-10-31 15:41:45'),
	(5,'1','100005','Yogyakarta','Shelly Ima',NULL,NULL,'-','KOTA YOGYAKARTA','DI YOGYAKARTA',NULL,'1',0,'2022-10-31 15:45:27','2022-10-31 15:45:27'),
	(6,'3','100006','JTM 01','BANANA KOSMETIK',NULL,NULL,'-','KOTA SURABAYA','JAWA TIMUR',NULL,'1',0,'2022-10-31 15:49:15','2022-10-31 15:49:15'),
	(7,'1','100007','JTM 01 -','SHINJUKU PAKUWON MALL',NULL,NULL,'-','KOTA SURABAYA','JAWA TIMUR',NULL,'1',0,'2022-10-31 15:54:43','2022-10-31 15:54:43'),
	(8,'1','100008','surabay','andre',NULL,NULL,'-','KOTA SURABAYA','JAWA TIMUR',NULL,'1',0,'2022-11-16 14:21:19','2022-11-16 14:21:19'),
	(9,'3','100009','JTM-03','ABADI JAYA MART','085730174432','','JL. Raya Canggu No. 54, Jetis, Kab Mojokerto','MOJOKERTO','JAWA TIMUR','','1',0,'2022-11-18 10:00:00','2022-11-18 10:00:00'),
	(10,'3','100010','JTM-02','AISYAH KURNIA KOSMETIK','6281392285408','','Jl Pandugo No.15, Penjaringan Sari, Rungkut, Surabaya','RUNGKUT','JAWA TIMUR','','1',0,'2022-11-18 10:00:00','2022-11-18 10:00:00'),
	(11,'3','100011','JTM-03','ALFI SALON','0856-4507-7951','','JL.RAYA NGABAR, KUPANG, KEC.JETIS, KAB. MOJOKERTO','JETIS','JAWA TIMUR','','1',0,'2022-11-18 10:00:00','2022-11-18 10:00:00'),
	(12,'3','100012','JTM-01','ALMIRA KOSMETIK','6281328162420','','Jl.Bendul Mrisi No.143, Jagir, Wonokromo, SBY','WONOKROMO','JAWA TIMUR','','1',0,'2022-11-18 10:00:00','2022-11-18 10:00:00'),
	(13,'3','100013','JTM-01','ANANDA SALON','6281231580006','','Jl Babatan Indah V Blok A9 No.1, Babatan, Wiyung, Surabaya','WIYUNG','JAWA TIMUR','','1',0,'2022-11-18 10:00:00','2022-11-18 10:00:00'),
	(14,'3','100014','JTM-04','Angel Salon','085233356074','','Jl. Brawijaya Tulungrejo, Pare, Kab.Kediri','WATES','JAWA TIMUR','','1',0,'2022-11-18 10:00:00','2022-11-18 10:00:00'),
	(15,'3','100015','JTM-05','Angellash Beauty Salon','089523834183','','Jalan Punto Dewo 1, Polean, Malang ','MALANG','JAWA TIMUR','','1',0,'2022-11-18 10:00:00','2022-11-18 10:00:00'),
	(16,'3','100016','JTM-01','ANGIES STUDIO','628123577564','','Jl.Raya Lontar No.252, Lontar, Sambikerep, SBY','SAMBIKEREP','JAWA TIMUR','','1',0,'2022-11-18 10:00:00','2022-11-18 10:00:00'),
	(17,'3','100017','JTM-01','ANUGERAH KOSMETIK','','','Jl Dukuh Setro Baru VIII No.1, Gading, Tambaksari, Surabaya','TAMBAKSARI','JAWA TIMUR','','1',0,'2022-11-18 10:00:00','2022-11-18 10:00:00'),
	(18,'3','100018','JTM-03','APINK SALON ','0856-3589-982','',' Jl. Balai Desa, Murip, Kec. Jetis','JETIS','JAWA TIMUR','','1',0,'2022-11-18 10:00:00','2022-11-18 10:00:00'),
	(19,'3','100019','JTM-01','ARINTA KOSMETIK','085746864733','','Jl. Jetis Baru I No. 44, Wonokromo, Surabaya','WONOKROMO','JAWA TIMUR','','1',0,'2022-11-18 10:00:00','2022-11-18 10:00:00'),
	(20,'3','100020','JTM-03','ASTRID BEAUTY SALON','0813-8934-5221','','JL. KARTINI NO.57, MOJOSARI, KAB.MOJOKERTO','MOJOSARI','JAWA TIMUR','','1',0,'2022-11-18 10:00:00','2022-11-18 10:00:00'),
	(21,'3','100021','JTM-01','AURA COLLECTION','81330804881','','Jl. Rajoso, Wonokerto Selatan, Peterongan, Jombang','PETERONGAN','JAWA TIMUR','','1',0,'2022-11-18 10:00:00','2022-11-18 10:00:00'),
	(22,'3','100022','JTM-02','AURA JAYA KOSMETIK','6285234258997','','Kali Rungkut, Rungkut, Surabaya','RUNGKUT','JAWA TIMUR','','1',0,'2022-11-18 10:00:00','2022-11-18 10:00:00'),
	(23,'3','100023','JTM-02','AYU SHOP A','6281333308613','','Komplek Green Mansion Residence P-10, Ngingas, Waru, Sidoarjo','WARU','JAWA TIMUR','','1',0,'2022-11-18 10:00:00','2022-11-18 10:00:00'),
	(24,'3','100024','JTM-05','Ayulashes Beauty Lounge','082211983622','','Jl. Terusan Ijen No.24, Malang','MALANG','JAWA TIMUR','','1',0,'2022-11-18 10:00:00','2022-11-18 10:00:00'),
	(25,'3','100025','JTM-04','Bagus Cosmetics','085649281358','','Jl. Pattimura Ruko Perempatan Bringin, Bados, Kediri','BADOS','JAWA TIMUR','','1',0,'2022-11-18 10:00:00','2022-11-18 10:00:00'),
	(26,'3','100026','JTM-01','BANANA COSMETICS','082142648382','','Jl. Jemur Wonosari Gang Lebar No.36, Surabaya','JEMURSARI','JAWA TIMUR','','1',0,'2022-11-18 10:00:00','2022-11-18 10:00:00'),
	(27,'3','100027','JTM-01','BARBIE SHOP SBY','089665879714','','JL. Kutisari Utara 1 No. 12, Kutisari, Tenggilis Mejoyo','TENGGILIS MEJOYO','JAWA TIMUR','','1',0,'2022-11-18 10:00:00','2022-11-18 10:00:00'),
	(28,'3','100028','JTM-01','BARBIE SHOP SBY 1','085334845090','','Jl. Tenggilis Kauman No.29, Surabaya','TENGGILIS','JAWA TIMUR','','1',0,'2022-11-18 10:00:00','2022-11-18 10:00:00'),
	(29,'3','100029','JTM-02','BEAUTY CLEA','089699464779','','Jl. Dukuh Menanggal No. 103, Surabaya','DUKUH MENANGGAL','JAWA TIMUR','','1',0,'2022-11-18 10:00:00','2022-11-18 10:00:00'),
	(30,'3','100030','JTM-01','BEN AYU KOSMETIK','6282225557509','','Jl.imam Bonjol No.86A, Geluran, Kec.Taman, SDA','TAMAN','JAWA TIMUR','','1',0,'2022-11-18 10:00:00','2022-11-18 10:00:00'),
	(31,'3','100031','JTM-01','BILQIS COSMETIC','6285790600337','','Pasar Pakis, Jl.Dr.Soetomo No.7, Darmo, Wonokromo, SBY','WONOKROMO','JAWA TIMUR','','1',0,'2022-11-18 10:00:00','2022-11-18 10:00:00'),
	(32,'3','100032','JTM-01','BINTANG KOSMETIK','6281231931895','','Pasar Pagesangan, Jl.Pagesangan Timur No.24, Pagesangan, Jambangan, SBY','JAMBANGAN','JAWA TIMUR','','1',0,'2022-11-18 10:00:00','2022-11-18 10:00:00'),
	(33,'3','100033','JTM-02','BLESS KOSMETIK','6281397147200','','Jl Lebak Jaya Utara V-C No.1, Dk Setro, Tambaksari, Gading, Surabaya','TAMBAKSARI','JAWA TIMUR','','1',0,'2022-11-18 10:00:00','2022-11-18 10:00:00'),
	(34,'3','100034','JTM-01','BONNY SALON','6285100972839','','Dukuh Kawal Kali Kendal N0.42, Surabaya','','JAWA TIMUR','','1',0,'2022-11-18 10:00:00','2022-11-18 10:00:00'),
	(35,'3','100035','JTM-05','Brica Beauty','089681100081','','Jalan Kresno No. 3, Malang','MALANG','JAWA TIMUR','','1',0,'2022-11-18 10:00:00','2022-11-18 10:00:00'),
	(36,'3','100036','JTM-05','Bunga Kosmetik','082244305897','','Jl. Permadi No.12, Malang','MALANG','JAWA TIMUR','','1',0,'2022-11-18 10:00:00','2022-11-18 10:00:00'),
	(37,'3','100037','JTM-05','Cantika Pasar Besar','087859636631','','Jl. Syarif Al- Qadry No,28, Malang','MALANG','JAWA TIMUR','','1',0,'2022-11-18 10:00:00','2022-11-18 10:00:00'),
	(38,'3','100038','JTM-05','Cantika Salon','082131911574','','Jl. MT.Hariyono, Gang 12 No.116 AI, Malang','MALANG','JAWA TIMUR','','1',0,'2022-11-18 10:00:00','2022-11-18 10:00:00'),
	(39,'3','100039','JTM-03','CANTIKARIA SALON','085735536347','','JL. Teratai No. 23, Kab. Mojokerto','MOJOKERTO','JAWA TIMUR','','1',0,'2022-11-18 10:00:00','2022-11-18 10:00:00'),
	(40,'3','100040','JTM-02','CELLO SALON','6285733311515','','Jl Nginden II No.6A, Nginden Jangkungan, Sukolilo, Surabaya','SUKOLILO','JAWA TIMUR','','1',0,'2022-11-18 10:00:00','2022-11-18 10:00:00'),
	(41,'3','100041','JTM-05','Chalista Beauty Salon','081333298831','','Jl. Ky. Parseh Jaya Bumi Ayu, Malang','MALANG','JAWA TIMUR','','1',0,'2022-11-18 10:00:00','2022-11-18 10:00:00'),
	(42,'3','100042','JTM-01','COLOURS KOSMETIK','6281333332037','','Depan Kampus PGRI ADI BUANA SBY, Jl.Dk.Menanggal No.12, Gayungan, SBY','GAYUNGAN','JAWA TIMUR','','1',0,'2022-11-18 10:00:00','2022-11-18 10:00:00'),
	(43,'3','100043','JTM-02','DANI KOSMETIK','','','Pasar DTC, Wonokromo, Surabaya','WONOKROMO','JAWA TIMUR','','1',0,'2022-11-18 10:00:00','2022-11-18 10:00:00'),
	(44,'3','100044','JTM-01','DEVINA COSMETICS','082331207321','','JL. Raya Balun No.55, RT 9/ RW 9, Bohar, Taman, Sidoarjo','RAYA','JAWA TIMUR','','1',0,'2022-11-18 10:00:00','2022-11-18 10:00:00'),
	(45,'3','100045','JTM-02','DINAR KOSMETIK','085335734644','','Jl Kutuk Barat N.220, Kapasan, Sidokare, Sidoarjo','SIDOKARE','JAWA TIMUR','','1',0,'2022-11-18 10:00:00','2022-11-18 10:00:00'),
	(46,'3','100046','JTM-01','DINI KOSMETIK','085632766600','','Jl. Simo Jawar Baru VB No.17, Surabaya','','JAWA TIMUR','','1',0,'2022-11-18 10:00:00','2022-11-18 10:00:00'),
	(47,'3','100047','JTM-04','DW Mart','087758366953','','Jl. Flamboyan Jp. Inggris, Pare, Kediri','','JAWA TIMUR','','1',0,'2022-11-18 10:00:00','2022-11-18 10:00:00'),
	(48,'3','100048','JTM-04','EHG store','085706415771','','Jl. Sukarno Hatta (Ruko) Ngeblek, Pelem, Pare, Kediri','PARE','JAWA TIMUR','','1',0,'2022-11-18 10:00:00','2022-11-18 10:00:00'),
	(49,'3','100049','JTM-04','Eiffellyn Salon ( Niken )','082244870063','','Jl. Puncak Jaya 10 c (Utara Kantor Kecamatan) Kec. Pare, Kab. Kediri','PARE','JAWA TIMUR','','1',0,'2022-11-18 10:00:00','2022-11-18 10:00:00'),
	(50,'3','100050','JTM-04','Eka Shop','081515863437','','Jl. Timur Tengah, Dusun Semanding, Desa Semanding, Kec. Pagu, Kab. Kediri','PAGU','JAWA TIMUR','','1',0,'2022-11-18 10:00:00','2022-11-18 10:00:00'),
	(51,'3','100051','JTM-04','Eldee C0Smetix Pare','08112951699','','Jl. Tamrin Ruko Pare - Kediri','PARE','JAWA TIMUR','','1',0,'2022-11-18 10:00:00','2022-11-18 10:00:00'),
	(52,'3','100052','JTM-04','Elizabeth Fashion (Jeje)','085693190839','','Desa Sente, Simon Kepung, Kediri','SIMON KEPUNG','JAWA TIMUR','','1',0,'2022-11-18 10:00:00','2022-11-18 10:00:00'),
	(53,'3','100053','JTM-04','Elyas Swalayan','081232362159','','Ruko Kp. Madu Bringin Badas, Kediri','BADAS','JAWA TIMUR','','1',0,'2022-11-18 10:00:00','2022-11-18 10:00:00'),
	(54,'3','100054','JTM-01','ENS BEAUTY','85785168565','','Jl. Raya Menganto RT 01/ RW 01, Menganto, Kec. Mojowarno, Jombang','MOJOWARNO','JAWA TIMUR','','1',0,'2022-11-18 10:00:00','2022-11-18 10:00:00'),
	(55,'3','100055','JTM-05','Erna Salon','082244990542','','Jl. Muharto Gang 5 Blok 1 No.2, Malang','MALANG','JAWA TIMUR','','1',0,'2022-11-18 10:00:00','2022-11-18 10:00:00'),
	(56,'3','100056','JTM-01','EVA BEAUTY','83826394610','','Jl. KH. Ahmad Dahlan, Bandaran, Mancilan','MANCILAN','JAWA TIMUR','','1',0,'2022-11-18 10:00:00','2022-11-18 10:00:00'),
	(57,'3','100057','JTM-03','FAEZA AKSESORIS','0856-0609-7107','','JL. KARTINI NO.15, MOJOSARI, KAB. MOJOKERTO','MOJOSARI','JAWA TIMUR','','1',0,'2022-11-18 10:00:00','2022-11-18 10:00:00'),
	(58,'3','100058','JTM-03','FARAH QUEEN ACCECORIS','081357671796','','Jl Pahlawan No.48, Sarirejo, Mojosari, Mojokerto','MOJOSARI','JAWA TIMUR','','1',0,'2022-11-18 10:00:00','2022-11-18 10:00:00'),
	(59,'3','100059','JTM-04','Fellah Cosmetics','085777779777','','Jl. Gadungan 68, Desa Tawang, Kec. Wates, Kediri','GURAH','JAWA TIMUR','','1',0,'2022-11-18 10:00:00','2022-11-18 10:00:00'),
	(60,'3','100060','JTM-03','FIDIA KOSMETIK','08250383655','','Wonorejo, Trowulan, Mojokerto','TROWULAN','JAWA TIMUR','','1',0,'2022-11-18 10:00:00','2022-11-18 10:00:00'),
	(61,'3','100061','JTM-03','FINZ KOSMETIK','0856-0109-0166','','KARANGASEM, PAGERLUYUNG, KEC.GEDEG, MOJOKERTO','GEDEG','JAWA TIMUR','','1',0,'2022-11-18 10:00:00','2022-11-18 10:00:00'),
	(62,'3','100062','JTM-03','GALEN SALON','085852938707','','Perempatan Trowulan, Jl. Kejegan, Kab. Mojokerto','TROWULAN','JAWA TIMUR','','1',0,'2022-11-18 10:00:00','2022-11-18 10:00:00'),
	(63,'3','100063','JTM-03','GEKKA STORE','085856784689','','Gang Swadaya 1 No.10, Gedang Klutuk, Banjaragung, Puri, Mojokerto','PURI','JAWA TIMUR','','1',0,'2022-11-18 10:00:00','2022-11-18 10:00:00'),
	(64,'3','100064','JTM-04','GFA Store','081318008088','','Jl. Raya Pare - Kediri No. 25, Desa Darungan, Kec. Pare, Kediri','PARE','JAWA TIMUR','','1',0,'2022-11-18 10:00:00','2022-11-18 10:00:00'),
	(65,'3','100065','JTM-05','GG Anugerah Beauty','082234447290','','Jl. Lanimbar No.11 B, Malang','MALANG','JAWA TIMUR','','1',0,'2022-11-18 10:00:00','2022-11-18 10:00:00'),
	(66,'3','100066','JTM-01','GITA JAYA KOSMETIK','6287856755135','','Jl.Simo Kwagean Kuburan No.136, Putat Jaya, Sawahan, SBY','SAWAHAN','JAWA TIMUR','','1',0,'2022-11-18 10:00:00','2022-11-18 10:00:00'),
	(67,'3','100067','JTM-01','GRACE KOSMETIK','89643273887','','Jl. Raya Gudo 145, Gudo, Jombang','GUDO','JAWA TIMUR','','1',0,'2022-11-18 10:00:00','2022-11-18 10:00:00'),
	(68,'3','100068','JTM-02','GRIYA KOSMETIK','62859183959165','','Jl Berbek 3J No.16, Berbek, Waru, Sidoarjo','WARU','JAWA TIMUR','','1',0,'2022-11-18 10:00:00','2022-11-18 10:00:00'),
	(69,'3','100069','JTM-02','GV COSMETIC & ACC','6281230375092','','Jl Pekarungan, Karang nongko, Sukodono, Sidoarjo','SUKODONO','JAWA TIMUR','','1',0,'2022-11-18 10:00:00','2022-11-18 10:00:00'),
	(70,'3','100070','JTM-02','HARI KOSMETIK','6281217858176','','Jl Semolowaru Tengah 1 No.43B, Semolowaru, Sukolilo, Surabaya','SUKOLILO','JAWA TIMUR','','1',0,'2022-11-18 10:00:00','2022-11-18 10:00:00'),
	(71,'3','100071','JTM-02','HIDAYAH KOSMETIK','6281938563125','','Jl Raya Sukodono No.24, Sukodono, Sidoarjo','SUKODONO','JAWA TIMUR','','1',0,'2022-11-18 10:00:00','2022-11-18 10:00:00'),
	(72,'3','100072','JTM-02','HOGI SALON','6281230422966','','Jalan Raya Juanda, Sidoarjo','JUANDA','JAWA TIMUR','','1',0,'2022-11-18 10:00:00','2022-11-18 10:00:00'),
	(73,'3','100073','JTM-02','HOGI SALON 2','081230422966','','Jl. Raya Juanda, Sidoarjo','JUANDA','JAWA TIMUR','','1',0,'2022-11-18 10:00:00','2022-11-18 10:00:00'),
	(74,'3','100074','JTM-01','HONESTY KOSMETIK','081330448131 / 081331527097','','Jl. Kapt. Tendean  No. 129 C, Pulo jombang, Jombang','PULO JOMBANG','JAWA TIMUR','','1',0,'2022-11-18 10:00:00','2022-11-18 10:00:00'),
	(75,'3','100075','JTM-05','Ika Cosmetics','081234352765','','Jl. Raya Muharto No.17, Malang','MALANG','JAWA TIMUR','','1',0,'2022-11-18 10:00:00','2022-11-18 10:00:00'),
	(76,'3','100076','JTM-04','IM Salon Kediri','085649993394','','Ruko Bogo Plemahan, Kediri','','JAWA TIMUR','','1',0,'2022-11-18 10:00:00','2022-11-18 10:00:00'),
	(77,'3','100077','JTM-03','IRA SALON','085852155499','','Jl. Raya Pasman Jabon, Mojokerto','MOJOKERTO','JAWA TIMUR','','1',0,'2022-11-18 10:00:00','2022-11-18 10:00:00'),
	(78,'3','100078','JTM-01','JAYA MAKMUR KOSMETIK','6281515316647','','Jl. Raya Suko legok No.62, Dsn Legok, Suko, Sukodono, SDA','RAYALEGOK NO.62, DSN','JAWA TIMUR','','1',0,'2022-11-18 10:00:00','2022-11-18 10:00:00'),
	(79,'3','100079','JTM-01','JAYANTI KOSMETIK','082143993772','','Jl. Ratu Ayu No.27, Wage, Taman, Sidoarjo','RATUO.27','JAWA TIMUR','','1',0,'2022-11-18 10:00:00','2022-11-18 10:00:00'),
	(80,'3','100080','JTM-05','JD Beauty','085954490523','','Jl. Wijaya Kusuma, Malang','MALANG','JAWA TIMUR','','1',0,'2022-11-18 10:00:00','2022-11-18 10:00:00'),
	(81,'3','100081','JTM-02','JM RUMAH CANTIK KOSMETIK','081217856680','','Jl Kutuk Barat No.314, Cangkring, Sidokare, Sidoarjo','SIDOKARE','JAWA TIMUR','','1',0,'2022-11-18 10:00:00','2022-11-18 10:00:00'),
	(82,'3','100082','JTM-05','Joanna Salon','081288641234','','Jl. Ki Ageng Gribig Lt.1, Blok N-47, Malang','MALANG','JAWA TIMUR','','1',0,'2022-11-18 10:00:00','2022-11-18 10:00:00'),
	(83,'3','100083','JTM-02','KARTINI KOSMETIK','','','Pasar DTC, Wonokromo, Surabaya','WONOKROMO','JAWA TIMUR','','1',0,'2022-11-18 10:00:00','2022-11-18 10:00:00'),
	(84,'3','100084','JTM-03','KAYLA SALON','085730439003','','Jeruk Kidul, Banjarsari, Jetis, Mojokerto','JETIS','JAWA TIMUR','','1',0,'2022-11-18 10:00:00','2022-11-18 10:00:00'),
	(85,'3','100085','JTM-04','Kayla Shop','085806772735','','Desa Gadungan, Tondomulyo, Kediri','TONDOMULYO','JAWA TIMUR','','1',0,'2022-11-18 10:00:00','2022-11-18 10:00:00'),
	(86,'3','100086','JTM-04','Kharisma Kosmetik','085749269312','','Jl. Raya Wates , Sumber Agung, Kediri','AGUNG','JAWA TIMUR','','1',0,'2022-11-18 10:00:00','2022-11-18 10:00:00'),
	(87,'3','100087','JTM-02','KHAYYUN KOSMETIK','081770953850','','Pasar Soponyono Blok B - 4, Rungkut, Surabaya','RUNGKUT','JAWA TIMUR','','1',0,'2022-11-18 10:00:00','2022-11-18 10:00:00'),
	(88,'3','100088','JTM-05','KOSMECICU','0858-1549-0602','','Jl. Kol. Sugiono No.359, Gadang, Malang','GADANG','JAWA TIMUR','','1',0,'2022-11-18 10:00:00','2022-11-18 10:00:00'),
	(89,'3','100089','JTM-04','La Rossa Cosmetics','0895634663265 / 082142402243','','Jl. Dr. Wahidin Sudirohusodo, Pelem, Pare, Kediri','PARE','JAWA TIMUR','','1',0,'2022-11-18 10:00:00','2022-11-18 10:00:00'),
	(90,'3','100090','JTM-05','Lia Salon','081232741779','','Jl. Raya Kayu Parseh No.16, Bumi Ayu, Malang','MALANG','JAWA TIMUR','','1',0,'2022-11-18 10:00:00','2022-11-18 10:00:00'),
	(91,'3','100091','JTM-01','LIKA BEAUTY CARE','85608104215','','Jl. Cemp. Papensari, Rojoagung, Ploso, Jombang','PLOSO','JAWA TIMUR','','1',0,'2022-11-18 10:00:00','2022-11-18 10:00:00'),
	(92,'3','100092','JTM-02','LINDA COLLECTION','08113199693','','Swalayan Yakaya Lt.1 Rungkut, Surabaya','RUNGKUT','JAWA TIMUR','','1',0,'2022-11-18 10:00:00','2022-11-18 10:00:00'),
	(93,'3','100093','JTM-01','LIS COSMETICS ','081375345536','','JL. Kendangsari Gang Lebar No. 53, Surabaya','KENDANGSARI','JAWA TIMUR','','1',0,'2022-11-18 10:00:00','2022-11-18 10:00:00'),
	(94,'3','100094','JTM-03','LIZ AKSESSORIS','085706931228','','Pasar legi Mojoagung, Jombang','MOJOAGUNG','JAWA TIMUR','','1',0,'2022-11-18 10:00:00','2022-11-18 10:00:00'),
	(95,'3','100095','JTM-04','Luhur Cosmetics (Bu Umi)','083830077707','','Dusun Joho, Desa Sumberejo RT 4/RW 1, Ngasem, Kediri','NGASEM','JAWA TIMUR','','1',0,'2022-11-18 10:00:00','2022-11-18 10:00:00'),
	(96,'3','100096','JTM-01','LUKITA KOSEMTIK','85608884067','','Jl. Desa Gedangan RT 8/ RW 2, Mojogeneng, Gedangan, Mojowarno, Jombang','MOJOWARNO','JAWA TIMUR','','1',0,'2022-11-18 10:00:00','2022-11-18 10:00:00'),
	(97,'3','100097','JTM-01','LUNASHOP KOSMETIK','081216360557','','Jln. Pondok Benowo Indah No.28, Surabaya','BENOWO','JAWA TIMUR','','1',0,'2022-11-18 10:00:00','2022-11-18 10:00:00'),
	(98,'3','100098','JTM-05','Lyn Hair Style & Treatment','081233625224','','Jl. Simpang LA Sucipro No. 19, Malang','MALANG','JAWA TIMUR','','1',0,'2022-11-18 10:00:00','2022-11-18 10:00:00'),
	(99,'3','100099','JTM-02','MADINA KOSMETIK','6285102123238','','Jl Setro Baru X No.10A, Gading, Tambaksari, Surabaya','TAMBAKSARI','JAWA TIMUR','','1',0,'2022-11-18 10:00:00','2022-11-18 10:00:00'),
	(100,'3','100100','JTM-01','MAGITA KOSMETIK','081703772777','','Jl. Simo Kwagean No.32, Petemon, Sawahan, Surabaya','SAWAHAN','JAWA TIMUR','','1',0,'2022-11-18 10:00:00','2022-11-18 10:00:00'),
	(101,'3','100101','JTM-01','MAHREEN BEAUTY','6282337981792','','Jl.Manukan Tama Blok.19 33-34 Kav. 198 A-B, Manukan Kulon, Tandes, SBY','TANDES','JAWA TIMUR','','1',0,'2022-11-18 10:00:00','2022-11-18 10:00:00'),
	(102,'3','100102','JTM-02','MAMA NOR KOSMETIK','6282264826814','','Jl Pasar Sukodono Sebelah Utara Sidoarjo','SUKODONO','JAWA TIMUR','','1',0,'2022-11-18 10:00:00','2022-11-18 10:00:00'),
	(103,'3','100103','JTM-04','Manda Beauty Shop','081256008627','','Jl. Harinjing kepung, Kec. Kepung, Kab. Kediri','PUNCU','JAWA TIMUR','','1',0,'2022-11-18 10:00:00','2022-11-18 10:00:00'),
	(104,'3','100104','JTM-03','MAYA AKSESSORIS','081333855551','','Perempatan Trowulan, Jl. Pendopo Agung, Trowulan, Kab.Mojokerto','TROWULAN','JAWA TIMUR','','1',0,'2022-11-18 10:00:00','2022-11-18 10:00:00'),
	(105,'3','100105','JTM-05','Maya Kosmetik','081234516150','','Jl. Mertojoyo No.11 A, Malang','MALANG','JAWA TIMUR','','1',0,'2022-11-18 10:00:00','2022-11-18 10:00:00'),
	(106,'3','100106','JTM-01','MEGAH INDAH KOSMETIK','6281216971046','','Jl.Kupang Gunung Timur IV B No.16, Putat Jaya, Sawahan, SBY','SAWAHAN','JAWA TIMUR','','1',0,'2022-11-18 10:00:00','2022-11-18 10:00:00'),
	(107,'3','100107','JTM-05','MELLA SALON','0877-7413-2226','','jL. Muharto Gang V, Malang','MALANG','JAWA TIMUR','','1',0,'2022-11-18 10:00:00','2022-11-18 10:00:00'),
	(108,'3','100108','JTM-05','Mili Salon','082131694452','','Jl. Terusan Surabaya - Malang','MALANG','JAWA TIMUR','','1',0,'2022-11-18 10:00:00','2022-11-18 10:00:00'),
	(109,'3','100109','JTM-04','Nabilla Salon & Beauty','085790576066','','Dusun Krembangan RT40/RW09, Kepung Barat, Kec. Kepung, Kab. Kediri','KEPUNG','JAWA TIMUR','','1',0,'2022-11-18 10:00:00','2022-11-18 10:00:00'),
	(110,'3','100110','JTM-04','Nadia Cosmetics','085859695563','','Jl. Bumirejo RT18/RW8, Ngancar, kediri','NGANCAR','JAWA TIMUR','','1',0,'2022-11-18 10:00:00','2022-11-18 10:00:00'),
	(111,'3','100111','JTM-05','Namira Shop','081333343338','','Jl. Ky. Parseh Jaya 39B, Malang','MALANG','JAWA TIMUR','','1',0,'2022-11-18 10:00:00','2022-11-18 10:00:00'),
	(112,'3','100112','JTM-02','NAOMI JAYA KOSMETIK','6282141866486','','Jl Raya Gading Fajar 2 No.12, Perum King Safira, Sepande,Candi, Sidoarjo','CANDI','JAWA TIMUR','','1',0,'2022-11-18 10:00:00','2022-11-18 10:00:00'),
	(113,'3','100113','JTM-02','NAVILA KOSMETIK','6281615597062','','Jl Tumapel No.72, Ketajen, Gedangan, Sidoarjo','GEDANGAN','JAWA TIMUR','','1',0,'2022-11-18 10:00:00','2022-11-18 10:00:00'),
	(114,'3','100114','JTM-03','NAYA KOSMETIK','08513-3079-0399','','JL. PASAR MOJOAGUNG, JL TOTOT KEROT NO.13, GEMBIRAH UTARA, MOJOAGUNG','MOJOAGUNG','JAWA TIMUR','','1',0,'2022-11-18 10:00:00','2022-11-18 10:00:00'),
	(115,'3','100115','JTM-03','NCANTIKA STORE','085855331318','','Jl Nasional 24 No.226-228, Petok, Tunggalpager, Pungging, Mojokerto','PUNGGING','JAWA TIMUR','','1',0,'2022-11-18 10:00:00','2022-11-18 10:00:00'),
	(116,'3','100116','JTM-03','NELLY SALON','085707678318','','JL Raya Cengkong Rolak 9, Mojoanyar, Kab. Mojokerto','MOJOANYAR','JAWA TIMUR','','1',0,'2022-11-18 10:00:00','2022-11-18 10:00:00'),
	(117,'3','100117','JTM-05','Nera Kosmetik','085791296969','','Jl. Kol. Sugiono No.146, Malang','MALANG','JAWA TIMUR','','1',0,'2022-11-18 10:00:00','2022-11-18 10:00:00'),
	(118,'3','100118','JTM-01','NIA BEAUTY CARE','85749577318','','JL. Jogoroto RT 5/RW 4,Jakung, Jogoroto, Jombang','JOGOROTO','JAWA TIMUR','','1',0,'2022-11-18 10:00:00','2022-11-18 10:00:00'),
	(119,'3','100119','JTM-05','Nico Salon','081252469998','','Jl. Danau Laut Tawar Blok G No. 20, Malang','MALANG','JAWA TIMUR','','1',0,'2022-11-18 10:00:00','2022-11-18 10:00:00'),
	(120,'3','100120','JTM-05','Nina Beauty Salon','081334451369','','Jl. Danau Kering Raya C2/F17, Malang','MALANG','JAWA TIMUR','','1',0,'2022-11-18 10:00:00','2022-11-18 10:00:00'),
	(121,'3','100121','JTM-04','Nina Salon','08113672300','','Jl. Raya Kepung, Gadungan Berat, Kec. Puncu, Kab.Kediri','PUNCU','JAWA TIMUR','','1',0,'2022-11-18 10:00:00','2022-11-18 10:00:00'),
	(123,'3','100123','JTM-05','NIRMALA COSMETICS','0821-1903-3605','','Plaza Gajah Mada, Malang','MALANG','JAWA TIMUR','','1',0,'2022-11-18 10:00:00','2022-11-18 10:00:00'),
	(124,'3','100124','JTM-01','NURILA KOSMETIK','6282244149644','','Jl.Gembili Raya No.17, Jagir, Wonokromo, SBY','WONOKROMO','JAWA TIMUR','','1',0,'2022-11-18 10:00:00','2022-11-18 10:00:00'),
	(125,'3','100125','JTM-01','NURMA SHOP','6281333555397','','Kuto Porong RT.04 Rw.01, Bangsal, Mojokerto','BANGSAL','JAWA TIMUR','','1',0,'2022-11-18 10:00:00','2022-11-18 10:00:00'),
	(126,'3','100126','JTM-02','NYWA BEAUTY & ACC','6285106410022','','Semolowaru, Sukolilo, Surabaya','SUKOLILO','JAWA TIMUR','','1',0,'2022-11-18 10:00:00','2022-11-18 10:00:00'),
	(127,'3','100127','JTM-05','Omah Gincu','081234588082','','Gudang Gang 15 No.3, Malang','MALANG','JAWA TIMUR','','1',0,'2022-11-18 10:00:00','2022-11-18 10:00:00'),
	(128,'3','100128','JTM-05','OMAKU COSMETICS','0821-4317-1331','','Jl. Kembang Turi No.28, Jatimulyo, Lowokwaru','LOWOKWARU','JAWA TIMUR','','1',0,'2022-11-18 10:00:00','2022-11-18 10:00:00'),
	(129,'3','100129','JTM-04','Onel Accessoris ','082244794833','','Jl. PB. Sudirman No. 1, Pare, Kediri','PARE','JAWA TIMUR','','1',0,'2022-11-18 10:00:00','2022-11-18 10:00:00'),
	(130,'3','100130','JTM-01','OZY SANJAYA SALON','6281333323083','','Jl.Babatan UNESA No.99f, Lidah Wetan, Lakarsantri, SBY','LAKAR SANTRI','JAWA TIMUR','','1',0,'2022-11-18 10:00:00','2022-11-18 10:00:00'),
	(131,'3','100131','JTM-03','PARIS N BEAUTY SALON','0857-9190-7097','',' Jl. Raya Meri, Kota Mojokerto','MOJOKERTO','JAWA TIMUR','','1',0,'2022-11-18 10:00:00','2022-11-18 10:00:00'),
	(132,'3','100132','JTM-04','Perdana/Pratama Kosmetik','081234856044','','Jl. Ahmad Yani 67, Kota Kediri','','JAWA TIMUR','','1',0,'2022-11-18 10:00:00','2022-11-18 10:00:00'),
	(133,'3','100133','JTM-01','PERMATA KOSMETIK','6281335511627','','Jl.Raya Menganti No.30, Lidah Wetan, Lakarsantri, SBY','LAKAR SANTRI','JAWA TIMUR','','1',0,'2022-11-18 10:00:00','2022-11-18 10:00:00'),
	(134,'3','100134','JTM-01','PHARAS KOSMETIK','085230049727','','Jl. Simo Kwagean No.74, Petemon, Sawahan, Surabaya','SAWAHAN','JAWA TIMUR','','1',0,'2022-11-18 10:00:00','2022-11-18 10:00:00'),
	(135,'3','100135','JTM-05','Pinery Shop','081556662200','','Perum Bukit Cemara Tujuh Blok G-16, Malang','MALANG','JAWA TIMUR','','1',0,'2022-11-18 10:00:00','2022-11-18 10:00:00'),
	(136,'3','100136','JTM-04','Pretty Salon','081335330880','','Jl. Panglima Polim, Kediri','','JAWA TIMUR','','1',0,'2022-11-18 10:00:00','2022-11-18 10:00:00'),
	(137,'3','100137','JTM-03','PUPUT SALON','085607714900','','Jl. Brawijaya No.15, Telogo Gede, Trowulan, Mojokerto','TROWULAN','JAWA TIMUR','','1',0,'2022-11-18 10:00:00','2022-11-18 10:00:00'),
	(138,'3','100138','JTM-01','PUTRI KOSMETIK 2','6285655104284','','Jl.Taruna No.35, Sritanjung, Wage, Kec.Taman, SDA','TAMAN','JAWA TIMUR','','1',0,'2022-11-18 10:00:00','2022-11-18 10:00:00'),
	(139,'3','100139','JTM-01','PUTRI RATU KOSMETIK','6287854271558','','Jl.Balongsari Blok 3B No.6, Balongsari, Tandes, SBY','TANDES','JAWA TIMUR','','1',0,'2022-11-18 10:00:00','2022-11-18 10:00:00'),
	(140,'3','100140','JTM-04','Putri Salon','082112166778','','Jl. Halim Perdana Kusuma 46, Banjaran, Kediri','BANJARAN','JAWA TIMUR','','1',0,'2022-11-18 10:00:00','2022-11-18 10:00:00'),
	(141,'3','100141','JTM-05','Putri Shop','082234696534','','Jl. Pelabuhan Baka Hauni RT7/RW 1, Malang','MALANG','JAWA TIMUR','','1',0,'2022-11-18 10:00:00','2022-11-18 10:00:00'),
	(142,'3','100142','JTM-02','QORI KOSMETIK','','','Sidopurno, Sidokepung, Buduran, Sidoarjo','BUDURAN','JAWA TIMUR','','1',0,'2022-11-18 10:00:00','2022-11-18 10:00:00'),
	(143,'3','100143','JTM-04','Queen Shop (Remi)','085648008155','','Jl. Dr. Wahidin Sudirohusodo No.171, Gurah, (Depan Bank BRI), Kediri','','JAWA TIMUR','','1',0,'2022-11-18 10:00:00','2022-11-18 10:00:00'),
	(144,'3','100144','JTM-01','QUEENARA BEAUTY SHOP','6285737373923','','Gunung Anyar Harapan Blok ZA No.14, Gunung Anyar, SBY','GUNUNG ANYAR','JAWA TIMUR','','1',0,'2022-11-18 10:00:00','2022-11-18 10:00:00'),
	(145,'3','100145','JTM-01','R JAYA KOSMETIK','6281331212995','','Jl.Kendangsari Gg.Lebar No.4, Kendangsari, Tinggilis Mejoyo, SBY','TENGILIS MEJOYO','JAWA TIMUR','','1',0,'2022-11-18 10:00:00','2022-11-18 10:00:00'),
	(146,'3','100146','JTM-01','RADIN KOSMETIK','081233784933','','Jl. Manukan Madya No.53, Surabaya','MANUKAN','JAWA TIMUR','','1',0,'2022-11-18 10:00:00','2022-11-18 10:00:00'),
	(147,'3','100147','JTM-01','RAHMA KOSMETIK','6282132653121','','Jl.Sidosermo IV No.33, Sidosermo, Kec.Wonocolo, SBY','WONOCOLO','JAWA TIMUR','','1',0,'2022-11-18 10:00:00','2022-11-18 10:00:00'),
	(148,'3','100148','JTM-05','Rara Cosmetics','085649993423','','JL.P.Sudirman, Karang Ploso, Malang','KARANG PLOSO','JAWA TIMUR','','1',0,'2022-11-18 10:00:00','2022-11-18 10:00:00'),
	(149,'3','100149','JTM-03','RATU ACCECORIS','085748974832','','Pasar Sedati, Sukorejo, Lolawang, Ngoro, Mojokerto','NGORO','JAWA TIMUR','','1',0,'2022-11-18 10:00:00','2022-11-18 10:00:00'),
	(150,'3','100150','JTM-02','RENA KOSMETIK','085733585025','','JL. Raya Gedangan Sukodono N0,130, RT 01/RW 03, Ganting, Sidoarjo','GEDANGAN','JAWA TIMUR','','1',0,'2022-11-18 10:00:00','2022-11-18 10:00:00'),
	(151,'3','100151','JTM-04','Rere Salon','085749941499','','Jl. Raya Bendo, Tawang, Kec.Wates, Kab. Kediri','WATES','JAWA TIMUR','','1',0,'2022-11-18 10:00:00','2022-11-18 10:00:00'),
	(152,'3','100152','JTM-04','Rere Salon  2','085804284646','','Jl. Raya Kediri no 3 - Sambi, Ringin Sari, Kediri','RINGIN SARI','JAWA TIMUR','','1',0,'2022-11-18 10:00:00','2022-11-18 10:00:00'),
	(153,'3','100153','JTM-04','Rinny Salon','081336171674','','Jl. Raya Wonokasihan, Gayam, Gurah, Kediri','','JAWA TIMUR','','1',0,'2022-11-18 10:00:00','2022-11-18 10:00:00'),
	(154,'3','100154','JTM-01','RIYANTI BERKAH KOSMETIK','6281703010777','','Jl.Ketapang Suka No.5, Dsn Ketapang, Suko, Sukodono, SDA','SUKODONO','JAWA TIMUR','','1',0,'2022-11-18 10:00:00','2022-11-18 10:00:00'),
	(155,'3','100155','JTM-01','RIYANTI KOSMETIK','6281333252234','','Jl.Gajah Mada No.29, Medaeng Kulon, Kedungturi, Kec Taman, SDA','TAMAN','JAWA TIMUR','','1',0,'2022-11-18 10:00:00','2022-11-18 10:00:00'),
	(156,'3','100156','JTM-03','ROHADI KOSMETIK','085813201458','','Pasar Pelabuhan Canggu Stand E 28-29, Kedung Klinter, Canggu, Jetis, Mojokerto','JETIS','JAWA TIMUR','','1',0,'2022-11-18 10:00:00','2022-11-18 10:00:00'),
	(157,'3','100157','JTM-05','RUMAH COSMETICS','0857-9096-6109','','Jl. Supriado No.179, Malang','MALANG','JAWA TIMUR','','1',0,'2022-11-18 10:00:00','2022-11-18 10:00:00'),
	(158,'3','100158','JTM-01','RUMAH SKINCARE 1','85608805522','','Jl. Raya Sumobito, Karobelah 3, Kec. Mojoagung, Jombang','MOJOAGUNG','JAWA TIMUR','','1',0,'2022-11-18 10:00:00','2022-11-18 10:00:00'),
	(159,'3','100159','JTM-01','RUMAH SKINCARE 2','85608805522','','Jl. Bupati Ismail, Pandean, Kauman, Kec. Ngoro, Jombang','NGORO','JAWA TIMUR','','1',0,'2022-11-18 10:00:00','2022-11-18 10:00:00'),
	(160,'3','100160','JTM-05','S&N Salon','082131405377','','Ruko Toba Jalan Ki Agung Gribig FI No.24, Malang','MALANG','JAWA TIMUR','','1',0,'2022-11-18 10:00:00','2022-11-18 10:00:00'),
	(161,'3','100161','JTM-01','SAHABAT KOSMETIK','6285655423731','','Jl. Ubi 3 No.3, Jagir, Wonokromo, SBY','WONOKROMO','JAWA TIMUR','','1',0,'2022-11-18 10:00:00','2022-11-18 10:00:00'),
	(162,'3','100162','JTM-04','Salon Anik (Bu Anik)','081335872415','','Dusun Purworejo Kampung Madu, Badas, Kediri','BADAS','JAWA TIMUR','','1',0,'2022-11-18 10:00:00','2022-11-18 10:00:00'),
	(163,'3','100163','JTM-03','SALON JANIS','08582509003','','JL. Randu Gede No. 1, Margelo, Kota Mojokerto','MOJOKERTO','JAWA TIMUR','','1',0,'2022-11-18 10:00:00','2022-11-18 10:00:00'),
	(164,'3','100164','JTM-03','SALON JASMINE','0857-3377-0326','','Jl Wijaya Kusuma No.62, Mergelo, Sooko, Mojokerto','SOOKO','JAWA TIMUR','','1',0,'2022-11-18 10:00:00','2022-11-18 10:00:00'),
	(165,'3','100165','JTM-05','Salon Karona','081231646808','','Jl. Danau Bratan Timur 1 No. B -32 Malang','MALANG','JAWA TIMUR','','1',0,'2022-11-18 10:00:00','2022-11-18 10:00:00'),
	(166,'3','100166','JTM-02','SALON NK','6285645038385','','Jl Kyai Abdul Karim No.51, Rungkut Menanggal, Gn.Anyar, Surabaya','GUNUNG ANYAR','JAWA TIMUR','','1',0,'2022-11-18 10:00:00','2022-11-18 10:00:00'),
	(167,'3','100167','JTM-02','SALON NOVY','6281234267462','','Jl Penjaringan Tim No.1 C, Penjaringan Sari, Rungkut, Surabaya','RUNGKUT','JAWA TIMUR','','1',0,'2022-11-18 10:00:00','2022-11-18 10:00:00'),
	(168,'3','100168','JTM-03','SALON ROSSI','0895326439923','','Jl Raya Canggu No.56,Kedung Sumur, Canggu, Jetis, Mojokerto','JETIS','JAWA TIMUR','','1',0,'2022-11-18 10:00:00','2022-11-18 10:00:00'),
	(169,'3','100169','JTM-05','Sariielash Beauty Salon','082176971991','','Jl. Maninjau Barat Blok AP-2 No.17, Malang','MALANG','JAWA TIMUR','','1',0,'2022-11-18 10:00:00','2022-11-18 10:00:00'),
	(170,'3','100170','JTM-04','Selly Kosmetik','082244001447','','Perum. Taman Nirwana No.44, Ds.Putih, Kec.Gampangrejo, Kediri','GAMPANGREJO','JAWA TIMUR','','1',0,'2022-11-18 10:00:00','2022-11-18 10:00:00'),
	(171,'3','100171','JTM-01','SHINJUKU SALON PAKUWON','(031) 7390165','','Supermall pakuwon indah lantai 1 nomor 116 indah nomor 2','','JAWA TIMUR','','1',0,'2022-11-18 10:00:00','2022-11-18 10:00:00'),
	(172,'3','100172','JTM-02','SHINMI KOSMETIK','6285106410022','','Jl Raya Medayu Utara No.62, wonorejo, Rungkut, Surabaya','RUNGKUT','JAWA TIMUR','','1',0,'2022-11-18 10:00:00','2022-11-18 10:00:00'),
	(173,'3','100173','JTM-04','Sibling Salon & Beauty','081334626940','','Jl. Raya Maron Banyakan, Kediri','','JAWA TIMUR','','1',0,'2022-11-18 10:00:00','2022-11-18 10:00:00'),
	(174,'3','100174','JTM-04','SK Shop','085731936082','','Jl. Pare Wates, (Depan SPBU), Kediri','WATES','JAWA TIMUR','','1',0,'2022-11-18 10:00:00','2022-11-18 10:00:00'),
	(175,'3','100175','JTM-02','SOFI KOSMETIK','6287855561228','','Pasar Kapas Krampung Lt.1 blok F no 48, Kenjeran, Simokerto, Surabaya','SIMOKERTO','JAWA TIMUR','','1',0,'2022-11-18 10:00:00','2022-11-18 10:00:00'),
	(176,'3','100176','JTM-05','SUAG Beauty & Salon','085707792039','','Jl. Kyai H. Pasreh, Malang','MALANG','JAWA TIMUR','','1',0,'2022-11-18 10:00:00','2022-11-18 10:00:00'),
	(177,'3','100177','JTM-02','SUBUR KOSMETIK','6285101445559','','Jl Rungkut Alang Alang No.8, Kali Rungkut, Rungkut, Surabaya','RUNGKUT','JAWA TIMUR','','1',0,'2022-11-18 10:00:00','2022-11-18 10:00:00'),
	(178,'3','100178','JTM-01','SUMBER AYU KOSMETIK','6281937774162','','Jl. Jemur Wonosari Gg.Lebar No.44, Jemur Wonosari, Kec.wonocolo, SBY','WONOCOLO','JAWA TIMUR','','1',0,'2022-11-18 10:00:00','2022-11-18 10:00:00'),
	(179,'3','100179','JTM-03','SUMBER WANGI BRAWIJAYA','','','Jl. Brawijaya No.40. Mergelo, Mojokerto','MERGELO','JAWA TIMUR','','1',0,'2022-11-18 10:00:00','2022-11-18 10:00:00'),
	(180,'3','100180','JTM-04','Surya Swalayan','081239246041','','Jl. Raya Pare - Wates, Dermo, Plosoklaten, Kediri','PLOSOKLATEN','JAWA TIMUR','','1',0,'2022-11-18 10:00:00','2022-11-18 10:00:00'),
	(181,'3','100181','JTM-03','SYANTIK OLSHOP','0895401499100','','Jl Bancang No.30, Mergelo, Wates, Magersari, Mojokerto','MAGERSARI','JAWA TIMUR','','1',0,'2022-11-18 10:00:00','2022-11-18 10:00:00'),
	(182,'3','100182','JTM-02','TAMY SALON & ACC','6281232358236','','Jl Raya Buncitan No.49, Dsn Buncitan, Buncitan, Sedati, Sidoarjo','SEDATI','JAWA TIMUR','','1',0,'2022-11-18 10:00:00','2022-11-18 10:00:00'),
	(183,'3','100183','JTM-04','Taris Salon','081235135568','','Jl. RA. Kartini Ruko Doko No. 27, Kediri','','JAWA TIMUR','','1',0,'2022-11-18 10:00:00','2022-11-18 10:00:00'),
	(184,'3','100184','JTM-05','Tia Salon','081216666037','','Klayatan Gang 2 No.6, Malang','MALANG','JAWA TIMUR','','1',0,'2022-11-18 10:00:00','2022-11-18 10:00:00'),
	(185,'3','100185','JTM-04','Tiara Kosmetik','082334546922','','Jl. Gatot Subroto Mrican, Kediri','','JAWA TIMUR','','1',0,'2022-11-18 10:00:00','2022-11-18 10:00:00'),
	(186,'3','100186','JTM-01','TIKA LESTARI KOSMETIK','6281235919019','','Jl.Raya Pradah Indah No.34, Pradah Kali Kendal, Dk.Pakis, SBY','DUKUH PAKIS','JAWA TIMUR','','1',0,'2022-11-18 10:00:00','2022-11-18 10:00:00'),
	(187,'3','100187','JTM-05','Toko 34 Blimbing','08982950120','','Jl. Borobudur 34, Malang','MALANG','JAWA TIMUR','','1',0,'2022-11-18 10:00:00','2022-11-18 10:00:00'),
	(188,'3','100188','JTM-02','TOKO AJIB 2 KOSMETIK','08563137770','','Jl Basuki Rahmat No.5, Krian, Sidoarjo / Pasar Krian Blok IB (belakang pamayana)','KRIAN','JAWA TIMUR','','1',0,'2022-11-18 10:00:00','2022-11-18 10:00:00'),
	(189,'3','100189','JTM-03','TOKO ALMIRA','0857-3676-2545','','Pasar Kupang, Jetis, Kab. Mojokerto','MOJOKERTO','JAWA TIMUR','','1',0,'2022-11-18 10:00:00','2022-11-18 10:00:00'),
	(190,'3','100190','JTM-01','TOKO AMANDA KOSMETIK','6282245551170','','Jl.Kutisari Utara No.52C, Kutisari, Kec.Tenggilis Mejoyo, SBY','TENGGILIS MEJOYO','JAWA TIMUR','','1',0,'2022-11-18 10:00:00','2022-11-18 10:00:00'),
	(191,'3','100191','JTM-03','TOKO ANIX KOSMETIK','0813-5786-5799','','Jl. Raya Meri, Gang Selir, Mojokerto','MOJOKERTO','JAWA TIMUR','','1',0,'2022-11-18 10:00:00','2022-11-18 10:00:00'),
	(192,'3','100192','JTM-05','Toko Anti Mahal (Yln Beauty) ','087786439642/ 082260251112','','JL. Pasar Besar Lantai Bawah','MALANG','JAWA TIMUR','','1',0,'2022-11-18 10:00:00','2022-11-18 10:00:00'),
	(193,'3','100193','JTM-05','Toko Asri Kosmetik','081227700346','','Jl. Pasar Bunu, Bedak Depan No.7, Malang','MALANG','JAWA TIMUR','','1',0,'2022-11-18 10:00:00','2022-11-18 10:00:00'),
	(194,'3','100194','JTM-03','TOKO AVI COSMETICS','082228880009','','Jl Totok Kerot Gambiran Utara No.141, Gambiran utara, Mojoagung, Jombang','MOJOAGUNG','JAWA TIMUR','','1',0,'2022-11-18 10:00:00','2022-11-18 10:00:00'),
	(195,'3','100195','JTM-02','TOKO AYU COSMETICS','','','Psr Pahing Rungkut Kidul Los No.03, Surabaya','RUNGKUT','JAWA TIMUR','','1',0,'2022-11-18 10:00:00','2022-11-18 10:00:00'),
	(196,'3','100196','JTM-01','TOKO AYUNE REK','0856-4836-8503','','Jl. Karah Agung No 38, Karah Jambangan Sby','KARAHG','JAWA TIMUR','','1',0,'2022-11-18 10:00:00','2022-11-18 10:00:00'),
	(197,'3','100197','JTM-02','TOKO BERKAH MULYA','6283849550131','','Jl. Pasar Pahing, Rungkut Kidul No,30, Surabaya','RUNGKUT','JAWA TIMUR','','1',0,'2022-11-18 10:00:00','2022-11-18 10:00:00'),
	(198,'3','100198','JTM-03','TOKO BU SUSI','085708346626','','Pasar Mojoagung','MOJOAGUNG','JAWA TIMUR','','1',0,'2022-11-18 10:00:00','2022-11-18 10:00:00'),
	(199,'3','100199','JTM-03','TOKO BU YULIA ','081357624909','','Jl.Raya Kelagi, Mojokerto','MOJOKERTO','JAWA TIMUR','','1',0,'2022-11-18 10:00:00','2022-11-18 10:00:00'),
	(200,'3','100200','JTM-03','TOKO BU YUNI','','','Jl Gajah Mada No.1, Seduri, Mojosari, Mojokerto','MOJOSARI','JAWA TIMUR','','1',0,'2022-11-18 10:00:00','2022-11-18 10:00:00'),
	(201,'3','100201','JTM-05','Toko Cantik','085100766270','','Jl. Terusan Borobudur No. 41, Malang','MALANG','JAWA TIMUR','','1',0,'2022-11-18 10:00:00','2022-11-18 10:00:00'),
	(202,'3','100202','JTM-01','TOKO CLARISSA KOSMETIK','6287753377007','','Jl Pakis Tirtosari No.49, Pakis, Sawahan, Surabaya','SAWAHAN','JAWA TIMUR','','1',0,'2022-11-18 10:00:00','2022-11-18 10:00:00'),
	(203,'3','100203','JTM-04','TOKO Dahlia','085748162832','','Jl. Dahka 14, Desa Tulung rejo, Pare, Kab. Kediri','PARE','JAWA TIMUR','','1',0,'2022-11-18 10:00:00','2022-11-18 10:00:00'),
	(204,'3','100204','JTM-05','Toko Den Ayu','08193332313','','Jl. Sersan Harun No. 25 A, Pasar Besar (Lantai Dasar Blok Barat) Malang','MALANG','JAWA TIMUR','','1',0,'2022-11-18 10:00:00','2022-11-18 10:00:00'),
	(205,'3','100205','JTM-03','TOKO DENIS','0821-5807-8649','',' Jl. Cinde, Prajurit Kulon, Mojokerto','MOJOKERTO','JAWA TIMUR','','1',0,'2022-11-18 10:00:00','2022-11-18 10:00:00'),
	(206,'3','100206','JTM-03','TOKO DIANA','081245760690','','Pagar Brangkal Lama, Jl. Kamsetyadi Bno.2, Mojokerto','MOJOKERTO','JAWA TIMUR','','1',0,'2022-11-18 10:00:00','2022-11-18 10:00:00'),
	(207,'3','100207','JTM-03','TOKO DIYAH','0856-5527-3797','','JL. S. PARMAN NO.10, MOJOSARI, KAB.MOJOKERTO','MOJOSARI','JAWA TIMUR','','1',0,'2022-11-18 10:00:00','2022-11-18 10:00:00'),
	(208,'3','100208','JTM-04','Toko Eka Jaya','085850082995','','Pasar Pamenang, Pare, Kediri','PARE','JAWA TIMUR','','1',0,'2022-11-18 10:00:00','2022-11-18 10:00:00'),
	(209,'3','100209','JTM-05','Toko Empat Belas','082232348998','','Jl. Sersan Harun  No. A-16, Pasar Besar Lantai Dasar','MALANG','JAWA TIMUR','','1',0,'2022-11-18 10:00:00','2022-11-18 10:00:00'),
	(210,'3','100210','JTM-03','TOKO ERLIZA FASHION','0857-3105-1227','','JL. JOLOTUNDO, SEDATI, KEC.NGORO, KAB. MOJOKERTO','NGORO','JAWA TIMUR','','1',0,'2022-11-18 10:00:00','2022-11-18 10:00:00'),
	(211,'3','100211','JTM-04','Toko Erva','085895365591','','Jl. Pare Kandangan Depan Ponpes Kencong, Kediri','PARE','JAWA TIMUR','','1',0,'2022-11-18 10:00:00','2022-11-18 10:00:00'),
	(212,'3','100212','JTM-03','TOKO INDAH KOSMETIK','082245639898','','Jl Residence Pamuji No.16, Mergelo, Jagalan, Magersari, Mojokerto','MAGERSARI','JAWA TIMUR','','1',0,'2022-11-18 10:00:00','2022-11-18 10:00:00'),
	(213,'3','100213','JTM-05','Toko Mawadah','085736376017','','Jl. Sunandar Priyo Sudarmo No.8, Malang','MALANG','JAWA TIMUR','','1',0,'2022-11-18 10:00:00','2022-11-18 10:00:00'),
	(214,'3','100214','JTM-02','TOKO MUNIR KOSMETIK','6285854808311','','Pasar DTC, Wonokromo, Surabaya','WONOKROMO','JAWA TIMUR','','1',0,'2022-11-18 10:00:00','2022-11-18 10:00:00'),
	(215,'3','100215','JTM-03','TOKO MUSTIKA 1','081515050269','','Jl Benteng Pancasila No.113-27, Mergelo, Meri, Magersari, Mojokerto','MAGERSARI','JAWA TIMUR','','1',0,'2022-11-18 10:00:00','2022-11-18 10:00:00'),
	(216,'3','100216','JTM-03','TOKO MUSTIKA 2','081515050269','','Mergelo, Meri, Magersari, Mojokerto','MAGERSARI','JAWA TIMUR','','1',0,'2022-11-18 10:00:00','2022-11-18 10:00:00'),
	(217,'3','100217','JTM-03','TOKO MUSTIKA 3','081515050269','','Mergelo, Jagalan, Magersari, Mojokerto','MAGERSARI','JAWA TIMUR','','1',0,'2022-11-18 10:00:00','2022-11-18 10:00:00'),
	(218,'3','100218','JTM-05','Toko Nataraga 2','081357916075','','Jl. Mawar No. 22, Malang','MALANG','JAWA TIMUR','','1',0,'2022-11-18 10:00:00','2022-11-18 10:00:00'),
	(219,'3','100219','JTM-03','TOKO OLA','089669949590','','PASAR BENTENG 092/A, KOTA MOJOKERTO','MOJOKERTO','JAWA TIMUR','','1',0,'2022-11-18 10:00:00','2022-11-18 10:00:00'),
	(220,'3','100220','JTM-04','Toko Olivia Supplier Cosmetics','085290880088','','Jl. Kilisuci No.15, Kota Kediri','','JAWA TIMUR','','1',0,'2022-11-18 10:00:00','2022-11-18 10:00:00'),
	(221,'3','100221','JTM-02','TOKO QORY KOSMETIK 2','081231479109','','JL. Singo Menggolo No.100, Ganting, Gedangan, Sidoarjo','GEDANGAN','JAWA TIMUR','','1',0,'2022-11-18 10:00:00','2022-11-18 10:00:00'),
	(222,'3','100222','JTM-02','TOKO RISKI','081333515054','','Jl. Raya Karang Nongko RT3/RW3, Sidoarjo','KARANG NONGKO','JAWA TIMUR','','1',0,'2022-11-18 10:00:00','2022-11-18 10:00:00'),
	(223,'3','100223','JTM-02','TOKO RISKI KOSMETIK','6281235158521','','Jl raya karang nongko RT08 RW03 pekarungan sukodono sidoarjo','SUKODONO','JAWA TIMUR','','1',0,'2022-11-18 10:00:00','2022-11-18 10:00:00'),
	(224,'3','100224','JTM-01','TOKO SEKAR AYU','6288999990020','','Jl.Brawijaya NO.44, Sawunggaling, Kec.Wonokromo, SBY','WONOKROMO','JAWA TIMUR','','1',0,'2022-11-18 10:00:00','2022-11-18 10:00:00'),
	(225,'3','100225','JTM-03','TOKO SRIKANDI','0818381162','','Jl Boulevard No.48, Tambak Rejo, Gayaman, Mojoanyar, Mojokerto','MOJOANYAR','JAWA TIMUR','','1',0,'2022-11-18 10:00:00','2022-11-18 10:00:00'),
	(226,'3','100226','JTM-01','TOKO SUMBER REJEKI','82140644330','','Jl. Raya No.223, Mojoagung, Jombang','MOJOAGUNG','JAWA TIMUR','','1',0,'2022-11-18 10:00:00','2022-11-18 10:00:00'),
	(227,'3','100227','JTM-03','TOKO SUMBER REJEKI 2','082140644330','','Jl. Raya Mojoagung No.223, Kab. Jombang','MOJOAGUNG','JAWA TIMUR','','1',0,'2022-11-18 10:00:00','2022-11-18 10:00:00'),
	(228,'3','100228','JTM-01','TOKO SUMBER REJEKI KOSMETIK','0812-3266-8225','','Jl. Kauman Gang 3, Mojoagung, Jombang','MOJOAGUNG','JAWA TIMUR','','1',0,'2022-11-18 10:00:00','2022-11-18 10:00:00'),
	(229,'3','100229','JTM-03','TOKO SUMBER WANGI','0856-5522-3409','','Jl. Mojopahit, Kota Mojokerto','MOJOKERTO','JAWA TIMUR','','1',0,'2022-11-18 10:00:00','2022-11-18 10:00:00'),
	(230,'3','100230','JTM-01','TOKO TALITHA KOSMETIK','6281459064254','','Jl. Pulo Wonokromo No. 15, Surabaya','PULO WONOKROMO','JAWA TIMUR','','1',0,'2022-11-18 10:00:00','2022-11-18 10:00:00'),
	(231,'3','100231','JTM-05','Toko Umi Kosmetik','082232880719','','Jl. Pasar Bunul Bedak Depan No.8, Malang','MALANG','JAWA TIMUR','','1',0,'2022-11-18 10:00:00','2022-11-18 10:00:00'),
	(232,'3','100232','JTM-03','TOKO Yanti','082231353552','','PASAR TANJUNG BARU, KOTA MOJOKERTO','MOJOKERTO','JAWA TIMUR','','1',0,'2022-11-18 10:00:00','2022-11-18 10:00:00'),
	(233,'3','100233','JTM-02','TOKO YUYUN KOSMETIK','6285795952598','','Pasar Wadung Asri No. C81, Waru, Sidoarjo','WARU','JAWA TIMUR','','1',0,'2022-11-18 10:00:00','2022-11-18 10:00:00'),
	(234,'3','100234','JTM-02','TOSERBA NAZA RAHMA','6285606888470','','Jl Menanggal 3 No.24, Menanggal, Gayungan, Surabaya','GAYUNGAN','JAWA TIMUR','','1',0,'2022-11-18 10:00:00','2022-11-18 10:00:00'),
	(235,'3','100235','JTM-04','Trix Mart','085707463215','','Jl. Supriadi No. 9, Gedangsewu, Kec. Pare, Kab. Kediri','PARE','JAWA TIMUR','','1',0,'2022-11-18 10:00:00','2022-11-18 10:00:00'),
	(236,'3','100236','JTM-02','U BYE\'s SALON','6287851227664','','Jl Nginden II No.45, Nginden Jangkungan, Sukolilo, Surabaya','SUKOLILO','JAWA TIMUR','','1',0,'2022-11-18 10:00:00','2022-11-18 10:00:00'),
	(237,'3','100237','JTM-02','UD LUHUR KOSMETIK','6281553993893','','Jl Kapas Krampung No.45, Tambakrejo, Simokerto, Surabaya','SIMOKERTO','JAWA TIMUR','','1',0,'2022-11-18 10:00:00','2022-11-18 10:00:00'),
	(238,'3','100238','JTM-04','Valentine Salon','085330003650','','Jl. Prof. Dr. Mustopo 33, NgadiN Luwih, Kediri','NGADIN LUWIH','JAWA TIMUR','','1',0,'2022-11-18 10:00:00','2022-11-18 10:00:00'),
	(239,'3','100239','JTM-05','Vina Salon Kosmetik','081233602806','','Jalan Raya Candi VI 1007, Malang','MALANG','JAWA TIMUR','','1',0,'2022-11-18 10:00:00','2022-11-18 10:00:00'),
	(240,'3','100240','JTM-04','W2 Salon','082298031000','','Ruko Doro Putih, Bolong, Kediri','','JAWA TIMUR','','1',0,'2022-11-18 10:00:00','2022-11-18 10:00:00'),
	(241,'3','100241','JTM-05','YS Cosmetics & Accesoris','085649531445','','Jl. Raya Candi V, Malang','MALANG','JAWA TIMUR','','1',0,'2022-11-18 10:00:00','2022-11-18 10:00:00'),
	(242,'3','100242','JTM-04','Yuan Fashion','081217014496','','Jl. Jawa No. 42, Pare, Kediri','PARE','JAWA TIMUR','','1',0,'2022-11-18 10:00:00','2022-11-18 10:00:00'),
	(243,'3','100243','JTM-03','YUNITA KOSMETIK','085607227039','','Bebuak, Modopuro, Mojosari, Mojokerto','MOJOSARI','JAWA TIMUR','','1',0,'2022-11-18 10:00:00','2022-11-18 10:00:00'),
	(244,'3','100244','JTM-02','ZENA KOSMETIK','6281331416003','','Jl Basuki Rahmat No.53, Krian, Sidoarjo','KRIAN','JAWA TIMUR','','1',0,'2022-11-18 10:00:00','2022-11-18 10:00:00'),
	(245,'3','100245','JTM-03','ZENNY SALON','0857-8561-5613','','Jl. Raya Modongan, Sooko, Kota Mojokerto','SOOKO','JAWA TIMUR','','1',0,'2022-11-18 10:00:00','2022-11-18 10:00:00'),
	(246,'3','100246','JTM-02','ZULFAN COLLECTION','6283871741957','','Jl. Berbek No. 3E , Waru, Sidoarjo','WARU','JAWA TIMUR','','1',0,'2022-11-18 10:00:00','2022-11-18 10:00:00'),
	(247,'1','100247',NULL,'Anna Cantik',NULL,NULL,'jl. raya menganti no 11 kav 25 babatan wiyung','KABUPATEN GRESIK','JAWA TIMUR',NULL,'1',0,'2022-12-01 14:01:46','2022-12-01 14:01:46'),
	(248,'1','100248',NULL,'Tabhita Aji Ma',NULL,NULL,'jl. raya menganti no 11 kav 25 babatan wiyung','KABUPATEN PACITAN','JAWA TIMUR',NULL,'1',0,'2022-12-01 14:10:17','2022-12-01 14:10:17'),
	(249,'1','100249',NULL,'Nora Ayatul Nisa',NULL,NULL,'jl. raya menganti no 11 kav 25 babatan wiyung','KOTA SURABAYA','JAWA TIMUR',NULL,'1',0,'2022-12-02 13:45:05','2022-12-02 13:45:05'),
	(250,'1','100250',NULL,'Shannon',NULL,NULL,'jl. raya menganti no 11 kav 25 babatan wiyung','KOTA SURABAYA','JAWA TIMUR',NULL,'1',0,'2022-12-02 13:55:59','2022-12-02 13:55:59'),
	(251,'1','100251',NULL,'Natapitu',NULL,NULL,'jl. raya menganti no 11 kav 25 babatan wiyung','KABUPATEN MALANG','JAWA TIMUR',NULL,'1',0,'2022-12-03 10:43:19','2022-12-03 10:43:19'),
	(252,'1','100252',NULL,'Veralyn',NULL,NULL,'jl. raya menganti no 11 kav 25 babatan wiyung','KABUPATEN MALANG','JAWA TIMUR',NULL,'1',0,'2022-12-03 10:55:35','2022-12-03 10:55:35'),
	(253,'1','100253',NULL,'Joyo',NULL,NULL,'Jombang pinggir','KABUPATEN JOMBANG','JAWA TIMUR',NULL,'1',0,'2022-12-12 14:17:57','2022-12-12 14:17:57'),
	(254,'1','100254',NULL,'Kania Cintana',NULL,NULL,'Jl. Margodadi VI No 12 RT8/RW7 Gudih Bubutan Surabaya','KOTA SURABAYA','JAWA TIMUR',NULL,'1',0,'2023-01-17 11:20:33','2023-01-17 11:20:33'),
	(255,'1','100255',NULL,'Indriyani Karmila',NULL,NULL,'Cluster Hunian Ariska No 2 Jl. Garuda No 12 RT08/RW02 Jatiasih Bekasi Jabar','KABUPATEN BEKASI','JAWA BARAT',NULL,'1',0,'2023-01-17 11:30:27','2023-01-17 11:30:27'),
	(256,'1','100256',NULL,'Shalsa Billa',NULL,NULL,'Jl. Kenangan Ambeng RT9/RW3 Waru Kab Sidoarjo Jatim','KABUPATEN SIDOARJO','JAWA TIMUR',NULL,'1',0,'2023-01-17 11:38:30','2023-01-17 11:38:30'),
	(257,'1','100257',NULL,'Dwi Instiana',NULL,NULL,'Jl. Griyo Mapan Sentosa Blok Ef No 37 Tambak Sawah Waru sidoarjo','KABUPATEN SIDOARJO','JAWA TIMUR',NULL,'1',0,'2023-01-17 11:43:03','2023-01-17 11:43:03'),
	(259,'1','100259',NULL,'NOVI TRISNANINGTYAS',NULL,NULL,'JLN MANYAR SEDATI AGUNG II, RT 06/RW03, SEDATI AGUNG, SEDATU DEPAN TOKO BUDIDIK, SEDATI, SIDOARJO','KABUPATEN SIDOARJO','JAWA TIMUR',NULL,'1',0,'2023-01-17 14:23:35','2023-01-17 14:23:35'),
	(260,'1','100260',NULL,'ELIYA',NULL,NULL,'JLN DUKUH PAKIS VI A2 NO.72, DUKUH PAKIS, KEC. DUKUH PAKIS, KOTA SBY, JAWA TIMUR 60225, MINA CATHERING, DUKUH PAKIS, KOTA SURABAYA','KOTA SURABAYA','JAWA TIMUR',NULL,'1',0,'2023-01-17 14:58:07','2023-01-17 14:58:07'),
	(261,'1','100261',NULL,'vebby sofa lorita',NULL,NULL,'kp. melintang, jalan hormen maddati, rt 01 rw 01 melintang, rangkui,  kota pangkal pinang , bangka belitung','KABUPATEN BANGKA','KEPULAUAN BANGKA BELITUNG',NULL,'1',0,'2023-01-17 15:06:02','2023-01-17 15:06:02'),
	(262,'1','100262',NULL,'shindhy cahyaning rizki',NULL,NULL,'jln ngagel rejo utara gg v no 24, nganggelrejo, nwonokromo rw 01 rt 06, wonokromo, surabaya','KOTA SURABAYA','JAWA TIMUR',NULL,'1',0,'2023-01-17 15:16:05','2023-01-17 15:16:05'),
	(263,'1','100263',NULL,'wulan',NULL,NULL,'one resindence gang ratna sari bii no 1, pemongan, denpasar selatan, bali','KOTA DENPASAR','BALI',NULL,'1',0,'2023-01-17 15:20:17','2023-01-17 15:20:17'),
	(264,'1','100264',NULL,'iva',NULL,NULL,'jln. manyar jaya va bo 28, menur pumpungan, sukolilo, kota surabaya, jawa timur','KOTA SURABAYA','JAWA TIMUR',NULL,'1',0,'2023-01-17 15:26:07','2023-01-17 15:26:07'),
	(265,'1','100265',NULL,'Rosi',NULL,NULL,'Jemur Wonoasri Gang Kyai Mualim No 4B','KOTA SURABAYA','JAWA TIMUR',NULL,'1',0,'2023-01-18 10:52:21','2023-01-18 10:52:21'),
	(266,'1','100266',NULL,'Puryadi (Mbak Nem)',NULL,NULL,'Jl. Citra Kracil RT5/RW1 Sidopurno Sidokepung','KABUPATEN SIDOARJO','JAWA TIMUR',NULL,'1',0,'2023-01-18 10:59:05','2023-01-18 10:59:05'),
	(267,'1','100267',NULL,'Sintya Nur',NULL,NULL,'Tambak Arum Gg III Surabaya Tambakrejo Jatim','KOTA SURABAYA','JAWA TIMUR',NULL,'1',0,'2023-01-18 11:10:06','2023-01-18 11:10:06'),
	(268,'1','100268',NULL,'Widia',NULL,NULL,'Bangka Belitung Bangka Sungai Liat','KABUPATEN LAHAT','SUMATERA SELATAN',NULL,'1',0,'2023-01-18 11:29:55','2023-01-18 11:29:55'),
	(269,'1','100269',NULL,'Anisa Nur Fitria',NULL,NULL,'Jl. Simo Rejo Sari B No 135','KOTA SURABAYA','JAWA TIMUR',NULL,'1',0,'2023-01-18 11:36:29','2023-01-18 11:36:29'),
	(270,'1','100270',NULL,'Jenny Christiani',NULL,NULL,'Jl. Kapten soebijanto Djojohadikusumo Lengkong Gudang Serpong','KOTA JAKARTA SELATAN','DKI JAKARTA',NULL,'1',0,'2023-01-18 11:45:08','2023-01-18 11:45:08'),
	(271,'1','100271',NULL,'Ida',NULL,NULL,'Jl. Pemuda No27-31 RW1 Embong Kaliasin Genteng Surabaya','KOTA SURABAYA','JAWA TIMUR',NULL,'1',0,'2023-01-18 11:52:56','2023-01-18 11:52:56'),
	(272,'1','100272',NULL,'Karmila',NULL,NULL,'Jl. H Jidi RT06/RW01 No 46G Cinere Depok Jabar','KOTA DEPOK','JAWA BARAT',NULL,'1',0,'2023-01-18 13:44:05','2023-01-18 13:44:05'),
	(273,'1','100273',NULL,'aam',NULL,NULL,'babatan wiyung no 11 Kav 25 surabaya','KOTA SURABAYA','JAWA TIMUR',NULL,'1',0,'2023-01-18 13:50:08','2023-01-18 13:50:08'),
	(274,'1','100274',NULL,'Dinar',NULL,NULL,'Jl. Bendul merisi tengah No 89 Masjid jami Surabaya','KOTA SURABAYA','JAWA TIMUR',NULL,'1',0,'2023-01-18 13:54:31','2023-01-18 13:54:31'),
	(275,'1','100275',NULL,'Amanda',NULL,NULL,'Jalan P Suryanata Gg 6 No 8 Rt 15 Samarinda Kaltim','KOTA SAMARINDA','KALIMANTAN TIMUR',NULL,'1',0,'2023-01-18 13:58:30','2023-01-18 13:58:30'),
	(276,'1','100276',NULL,'Lilik Nadhifatul Agusnia',NULL,NULL,'Gondang Bangkok RT03/RW03 Gurah Kediri Jatim','KOTA KEDIRI','JAWA TIMUR',NULL,'1',0,'2023-01-18 14:03:20','2023-01-18 14:03:20'),
	(277,'1','100277',NULL,'Yeni',NULL,NULL,'Jl. Kenjeran 557 Tambaksari Surabaya Jatim','KOTA SURABAYA','JAWA TIMUR',NULL,'1',0,'2023-01-18 14:08:30','2023-01-18 14:08:30'),
	(278,'1','100278',NULL,'Munjidah',NULL,NULL,'Jl. Berbek 1F No 18 Berbek Waru Pagar Putih Sidoarjo Jatim','KABUPATEN SIDOARJO','JAWA TIMUR',NULL,'1',0,'2023-01-18 14:13:02','2023-01-18 14:13:02'),
	(279,'1','100279',NULL,'Bidan Reni',NULL,NULL,'Jl. Urang Agung RT10/RW 4 Sidoarjo','KABUPATEN SIDOARJO','JAWA TIMUR',NULL,'1',0,'2023-01-18 14:16:02','2023-01-18 14:16:02'),
	(280,'1','100280',NULL,'Juwita Dwi Okta',NULL,NULL,'Dsn. dempoh RT9/RW1 Desa Geger Madiun Jatim','KABUPATEN MADIUN','JAWA TIMUR',NULL,'1',0,'2023-01-18 14:21:39','2023-01-18 14:21:39'),
	(281,'1','100281',NULL,'Nadia/Emma',NULL,NULL,'Jl. Wonokromo Selatan Gang II No 179 Rungkut Surabaya','KOTA SURABAYA','JAWA TIMUR',NULL,'1',0,'2023-01-18 14:26:50','2023-01-18 14:26:50'),
	(283,'1','100283',NULL,'Yenny S',NULL,NULL,'Surabay','KOTA SURABAYA','JAWA TIMUR',NULL,'1',0,'2023-01-18 14:32:49','2023-01-18 14:32:49'),
	(284,'1','100284',NULL,'Molex Maisari Latansa',NULL,NULL,'Jl. Sumur Umbul RT06/RW01 Sidomulyo Kudus Jateng','KABUPATEN KUDUS','JAWA TENGAH',NULL,'1',0,'2023-01-18 14:38:15','2023-01-18 14:38:15'),
	(285,'1','100285',NULL,'diana',NULL,NULL,'jln tongas no 01 , tongaswetan, tongas no 01 ghong jati, probolinggo jawa timur','KABUPATEN PROBOLINGGO','JAWA TIMUR',NULL,'1',0,'2023-01-18 15:03:56','2023-01-18 15:03:56'),
	(286,'1','100286',NULL,'silvi aditya',NULL,NULL,'jln . patriot, kontrakan pojok warna coklat, rt 04, rw 03, sudimara pinang, pinang, tanggerang banten','KOTA TANGERANG','BANTEN',NULL,'1',0,'2023-01-18 15:08:28','2023-01-18 15:08:28'),
	(287,'1','100287',NULL,'supriyatin',NULL,NULL,'perumahan kota serang baru kbs nlok b71 no 45 serang baru, bekasi, jawa barat','KABUPATEN BEKASI','JAWA BARAT',NULL,'1',0,'2023-01-18 16:01:55','2023-01-18 16:01:55'),
	(288,'1','100288',NULL,'astrid sandhya',NULL,NULL,'jalan golf lingkungan 3 citatah, rt 04 rw 08, kelurahan ciriung, cibinong, bogor , jawa barat','KABUPATEN BOGOR','JAWA BARAT',NULL,'1',0,'2023-01-18 16:11:06','2023-01-18 16:11:06'),
	(289,'1','100289',NULL,'sylvi atika setiyani',NULL,NULL,'rsia melinda  jalan balowerti ii nomor 59 , kediri, jawa timur','KABUPATEN KEDIRI','JAWA TIMUR',NULL,'1',0,'2023-01-18 16:18:19','2023-01-18 16:18:19'),
	(290,'1','100290',NULL,'reni adriana',NULL,NULL,'pengadilan negeri lubuksikaping, jalan jend sudirman no 64, lubuk sikaping, pasaman, sumatera barat','KABUPATEN PASAMAN','SUMATERA BARAT',NULL,'1',0,'2023-01-19 10:05:06','2023-01-19 10:05:06'),
	(291,'1','100291',NULL,'Rani Andriana',NULL,NULL,'Jl. Jend Sudirman No 64 Lubuk Sikaping Pasaman Sumetra Barat','KABUPATEN PASAMAN BARAT','SUMATERA BARAT',NULL,'1',0,'2023-01-19 10:24:19','2023-01-19 10:24:19'),
	(292,'1','100292',NULL,'reza fajri',NULL,NULL,'jl. panglima sudirman XII 06 gresik Rt 01 Rw 01 Gresik, jawatimur','KABUPATEN GRESIK','JAWA TIMUR',NULL,'1',0,'2023-01-19 10:57:24','2023-01-19 10:57:24'),
	(293,'1','100293',NULL,'ainun fauziatu iffah',NULL,NULL,'jl. ambengan  batu VI no 11 RT 06 RW 04, Tamnbaksari, kota Surabaya, Jwa timur','KOTA SURABAYA','JAWA TIMUR',NULL,'1',0,'2023-01-19 11:30:42','2023-01-19 11:30:42'),
	(294,'1','100294',NULL,'supriyanto/kiki(milapriwa)',NULL,NULL,'ds. keboan sikep rt 07/04 (dekat mushola AL-muchtar) gedangan, sidoarjo, gedangan, sidoarjo, jawatimur','KABUPATEN SIDOARJO','JAWA TIMUR',NULL,'1',0,'2023-01-19 11:55:50','2023-01-19 11:55:50'),
	(295,'1','100295',NULL,'UUS Chika',NULL,NULL,'Dsn. Tirtosari RT03/RW025 Andongsari Ambulu Kab Jember Jatim','KABUPATEN JEMBER','JAWA TIMUR',NULL,'1',0,'2023-01-19 15:26:23','2023-01-19 15:26:23'),
	(296,'1','100296',NULL,'Rani Ana',NULL,NULL,'Ds. Pasinan Kesamben Wetam RT14/RW3 Guwo Sumput Gresik','KABUPATEN GRESIK','JAWA TIMUR',NULL,'1',0,'2023-01-19 15:46:04','2023-01-19 15:46:04'),
	(297,'1','100297',NULL,'Elvira',NULL,NULL,'Jl. Hayam Wuruk No 181-183 Mangli Kaliwates Jember Jatim','KABUPATEN JEMBER','JAWA TIMUR',NULL,'1',0,'2023-01-19 16:03:33','2023-01-19 16:03:33'),
	(298,'1','100298',NULL,'Novita-081336124721',NULL,NULL,'JL. Wonorejo 1 No 130 Tegalsari Pasar Kembang Surabaya','KOTA SURABAYA','JAWA TIMUR',NULL,'1',0,'2023-01-19 16:08:39','2023-01-19 16:08:39'),
	(299,'1','100299',NULL,'Cica',NULL,NULL,'Surabay','KOTA SURABAYA','JAWA TIMUR',NULL,'1',0,'2023-01-19 16:13:26','2023-01-19 16:13:26'),
	(300,'1','100300',NULL,'Nadhea Ragil',NULL,NULL,'Jl. Bebekan Masjid Gang VII Taman SIdoarjo','KABUPATEN SIDOARJO','JAWA TIMUR',NULL,'1',0,'2023-01-19 16:18:38','2023-01-19 16:18:38'),
	(301,'1','100301',NULL,'Roy Satpam Pagi',NULL,NULL,'PT. Remaja Prima Engineering Jl. Tanjungsari No 17 RW 2 Suko Manunggal Surabaya','KOTA SURABAYA','JAWA TIMUR',NULL,'1',0,'2023-01-24 09:03:27','2023-01-24 09:03:27'),
	(302,'1','100302',NULL,'Putri Indah Megawati',NULL,NULL,'Kedung Rukem Gang II Nomor 9 RT002/RW005 Kedungdoro Tegalsari Surabaya','KOTA SURABAYA','JAWA TIMUR',NULL,'1',0,'2023-01-24 09:14:28','2023-01-24 09:14:28'),
	(303,'1','100303',NULL,'Siska Mauliya',NULL,NULL,'Jl. SIwalan Kareng Lor Probolinggo','KABUPATEN PROBOLINGGO','JAWA TIMUR',NULL,'1',0,'2023-01-24 09:20:39','2023-01-24 09:20:39'),
	(304,'1','100304',NULL,'Vita Arum (Pitut)',NULL,NULL,'Jl. Kertapraja No 10 RT/RW02/01 Karangboyo Cepu Blora Jateng','KABUPATEN BLORA','JAWA TENGAH',NULL,'1',0,'2023-01-24 09:24:19','2023-01-24 09:24:19'),
	(305,'1','100305',NULL,'Hesialita Br Hinting',NULL,NULL,'Jl. Kesain Durin No 189 Singa Tiga Panah Kab Karo Sumatera Utara','KABUPATEN KARO','SUMATERA UTARA',NULL,'1',0,'2023-01-24 09:31:47','2023-01-24 09:31:47'),
	(306,'1','100306',NULL,'Dea',NULL,NULL,'Aprt The Mansion Jasmine Tower Bellavista Jl. Trembesi Kampung Dukuh Pademangan JB16F Jakarta Utara','KOTA JAKARTA UTARA','DKI JAKARTA',NULL,'1',0,'2023-01-24 09:38:40','2023-01-24 09:38:40'),
	(307,'1','100307',NULL,'Dewi Kustiana',NULL,NULL,'Grand Embassy Jl. Pakuwon Indah Blok Ah1 No 3 Lontar Sambikerep Surabaya','KOTA SURABAYA','JAWA TIMUR',NULL,'1',0,'2023-01-24 09:45:49','2023-01-24 09:45:49'),
	(308,'1','100308',NULL,'Astin Alinda',NULL,NULL,'Perum Griya Cileungsi 3 Blok A6 No 1 RT001/RW014 Kelurahan Semampir Cileungsi Bogor Jabar','KABUPATEN BOGOR','JAWA BARAT',NULL,'1',0,'2023-01-24 09:49:16','2023-01-24 09:49:16'),
	(309,'1','100309',NULL,'Nina INdrawasi',NULL,NULL,'Aprt Thamrin Residance Tower Alamanda Unit 12AB Tanah Abang Jakarta Pusat','KOTA JAKARTA PUSAT','DKI JAKARTA',NULL,'1',0,'2023-01-24 09:54:25','2023-01-24 09:54:25'),
	(310,'1','100310',NULL,'Mei Anggraeni',NULL,NULL,'Kedung Jumputrejo Gang Melati RT20/RW6 Sukodono Sidoarjo Jatim','KABUPATEN SIDOARJO','JAWA TIMUR',NULL,'1',0,'2023-01-24 09:59:29','2023-01-24 09:59:29'),
	(311,'1','100311',NULL,'Indri Rosalina',NULL,NULL,'RT14/RW05 Buduran Wonoasri Kab Madiun Jatim','KABUPATEN MADIUN','JAWA TIMUR',NULL,'1',0,'2023-01-24 10:04:20','2023-01-24 10:04:20'),
	(313,'1','100313',NULL,'Rani Nurul',NULL,NULL,'Perum Kavling Serumpun Jl. Kampung Karang Tengah RT03/RW9 Nagrak Cianjur Jabar','KABUPATEN CIANJUR','JAWA BARAT',NULL,'1',0,'2023-01-24 10:13:47','2023-01-24 10:13:47'),
	(314,'1','100314',NULL,'Rista Oktavia',NULL,NULL,'Dsn. Sono RT1/RW3 Ds Sidokerto Buduran Kab Sidoarjo Jatim','KABUPATEN SIDOARJO','JAWA TIMUR',NULL,'1',0,'2023-01-24 10:27:31','2023-01-24 10:27:31'),
	(315,'1','100315',NULL,'Gita Robiatul A',NULL,NULL,'Prime Biz Hotel Surabaya Jl. Gayung Kebonasri No 30 Gayungan Surabaya','KOTA SURABAYA','JAWA TIMUR',NULL,'1',0,'2023-01-24 10:43:37','2023-01-24 10:43:37'),
	(316,'1','100316',NULL,'Pramesti Kun',NULL,NULL,'Puri Safira Regency Cluster Grand Shanaya Blok L6 No 1 Menganti Gresik Jatim','KABUPATEN GRESIK','JAWA TIMUR',NULL,'1',0,'2023-01-24 11:04:58','2023-01-24 11:04:58'),
	(317,'1','100317',NULL,'Dwia Imami Hidayati',NULL,NULL,'Windujaya RT2/RW2 Kedung Banteng Banyumas Jateng','KABUPATEN BANYUMAS','JAWA TENGAH',NULL,'1',0,'2023-01-24 11:12:43','2023-01-24 11:12:43'),
	(318,'1','100318',NULL,'Marzella Dwi Alyanti',NULL,NULL,'Jl. Tirto Usodo Timur No 30D Banyumanik Semarang Jateng','KABUPATEN SEMARANG','JAWA TENGAH',NULL,'1',0,'2023-01-24 11:19:48','2023-01-24 11:19:48'),
	(319,'1','100319',NULL,'Ninin',NULL,NULL,'Sukolilo Jl. Semolowaru Selatan 1/2 Surabaya','KOTA SURABAYA','JAWA TIMUR',NULL,'1',0,'2023-01-24 11:25:21','2023-01-24 11:25:21'),
	(320,'1','100320',NULL,'Windi Aprilia Trisnamurti',NULL,NULL,'Purworejo Perum Sekar Asri Rt003/RW005 Blok L/20 Pasuruan','KOTA PASURUAN','JAWA TIMUR',NULL,'1',0,'2023-01-24 11:30:58','2023-01-24 11:30:58'),
	(321,'1','100321',NULL,'Hanny',NULL,NULL,'Jl. Kompleks Green Garden RT3/RW9 Kedoya Utara Bakmi Bangka 777 Green Garden','KOTA JAKARTA PUSAT','DKI JAKARTA',NULL,'1',0,'2023-01-24 11:35:49','2023-01-24 11:35:49'),
	(322,'1','100322',NULL,'Adi Santo',NULL,NULL,'Jl. Raya Dharma Husada Indah No 25 Mulyorejo Surabaya Jatim','KOTA SURABAYA','JAWA TIMUR',NULL,'1',0,'2023-01-24 11:44:20','2023-01-24 11:44:20'),
	(323,'1','100323',NULL,'Vera Ane',NULL,NULL,'Kompleks Griya Permata Asri Jl. Danau Tondano No 118 Balikpapan Selatan Kaltim','KOTA BALIKPAPAN','KALIMANTAN TIMUR',NULL,'1',0,'2023-01-24 11:48:25','2023-01-24 11:48:25'),
	(324,'1','100324',NULL,'nurotul',NULL,NULL,'perum forest mansion cluster blossom hill b8 surabaya','KOTA SURABAYA','JAWA TIMUR',NULL,'1',0,'2023-01-24 11:53:32','2023-01-24 11:53:32'),
	(325,'1','100325',NULL,'Mia',NULL,NULL,'perum forest mansion cluster blossom hill b8 surabaya','KOTA SURABAYA','JAWA TIMUR',NULL,'1',0,'2023-01-24 12:00:58','2023-01-24 12:00:58'),
	(326,'1','100326',NULL,'Friska Adtya',NULL,NULL,'Rumah Susun Tanah Abang Blok 28 Lt3 No 3 Menetng Jakarta Pusat','KOTA JAKARTA PUSAT','DKI JAKARTA',NULL,'1',0,'2023-01-24 12:06:45','2023-01-24 12:06:45'),
	(327,'1','100327',NULL,'Dian Bintari',NULL,NULL,'Jl. Hayam Wuruk Baru I no 2 Wonokromo Surabaya Jatim','KOTA SURABAYA','JAWA TIMUR',NULL,'1',0,'2023-01-24 14:18:10','2023-01-24 14:18:10'),
	(328,'1','100328',NULL,'YUYUNMARGHO',NULL,NULL,'toko binefir utara jalan sumber Rt 01 Rw 0 Telandung, desa asem jaran, sampang, jawa timur','KABUPATEN SAMPANG','JAWA TIMUR',NULL,'1',0,'2023-01-24 14:39:42','2023-01-24 14:39:42'),
	(329,'1','100329',NULL,'ITA',NULL,NULL,'DSN. SUKOREJO RT 01 RW 01 DESA GROGOL KEC GROGOL KABUPATEN KEDIRI JAWATIMUR','KABUPATEN KEDIRI','JAWA TIMUR',NULL,'1',0,'2023-01-24 14:50:21','2023-01-24 14:50:21'),
	(330,'1','100330',NULL,'NURWINDA',NULL,NULL,'jALAN KP SEKARWANGI NO 58 RT 01 RW 07, NEGALSARI, KOTA TANGGERANG, BANTEN','KABUPATEN TANGERANG','BANTEN',NULL,'1',0,'2023-01-24 15:14:20','2023-01-24 15:14:20'),
	(331,'1','100331',NULL,'NILAM NOVITA',NULL,NULL,'jl simo magersari 1/55','KOTA SURABAYA','JAWA TIMUR',NULL,'1',0,'2023-01-24 15:26:48','2023-01-24 15:26:48'),
	(332,'1','100332',NULL,'DR.DIAH',NULL,NULL,'JL RAYA KEDAMPANG KEC KUTA UTARA KABUPATEN BADUNG BALI','KABUPATEN BADUNG','BALI',NULL,'1',0,'2023-01-24 15:33:26','2023-01-24 15:33:26'),
	(333,'1','100333',NULL,'RYMA',NULL,NULL,'SUKOMULYO RT 14 RW 4 NO 23 MANYAR GRESIK JAWATIMUR','KABUPATEN GRESIK','JAWA TIMUR',NULL,'1',0,'2023-01-24 15:39:09','2023-01-24 15:39:09'),
	(334,'1','100334',NULL,'VIA ANAK BAPAK WARNI',NULL,NULL,'KP BUNGEREUN JALAN KP RT 01 RW 01 DS. PEMATANG TANGGERANG BANTEN','KABUPATEN TANGERANG','BANTEN',NULL,'1',0,'2023-01-24 15:45:22','2023-01-24 15:45:22'),
	(335,'1','100335',NULL,'BU DEVY',NULL,NULL,'APARTMENT PUNCAK PERMAI TOWER  C JL RAYA DARMO PERMAI III KEC DUKUH PAKIS SURABAYA','KOTA SURABAYA','JAWA TIMUR',NULL,'1',0,'2023-01-24 15:55:41','2023-01-24 15:55:41'),
	(336,'1','100336',NULL,'BU DEVI',NULL,NULL,'APARTMENT PUNCAK PERMAI TOWER  C JL RAYA DARMO PERMAI III KEC DUKUH PAKIS SURABAYA','KOTA SURABAYA','JAWA TIMUR',NULL,'1',0,'2023-01-24 16:08:33','2023-01-24 16:08:33'),
	(337,'1','100337',NULL,'dITA RIYADI',NULL,NULL,'JL. MENGANTI DUKUHAN GANG VII RT 12 RW 4 GRESIK JAWATIMUR','KABUPATEN GRESIK','JAWA TIMUR',NULL,'1',0,'2023-01-24 16:13:23','2023-01-24 16:13:23'),
	(338,'1','100338',NULL,'Rimba',NULL,NULL,'Jl. Dewi Sartika Tlk Mutiara Kab. Alor, Nusa tenggara tim Teluk mutiara Alor NTT','KABUPATEN ALOR','NUSA TENGGARA TIMUR',NULL,'1',0,'2023-01-25 09:22:05','2023-01-25 09:22:05'),
	(339,'1','100339',NULL,'RAHAYU',NULL,NULL,'JALAN PATIMURA NO 27 KELURAHAN GEDHANGSEWU, BOYOLANGU, KAB TULUNGAGUNG, JAWA TIMUR','KABUPATEN TULUNGAGUNG','JAWA TIMUR',NULL,'1',0,'2023-01-25 09:33:41','2023-01-25 09:33:41'),
	(340,'1','100340',NULL,'Enik',NULL,NULL,'Ds. Kedungmentawar Dsn Mambang RT004/RW002 Ngimbang Kab Lamongan Jatim','KABUPATEN LAMONGAN','JAWA TIMUR',NULL,'1',0,'2023-01-25 09:40:39','2023-01-25 09:40:39'),
	(341,'1','100341',NULL,'ANDIK/ RIDHA',NULL,NULL,'GRIYA PURI ASRI BLOK A2 NO 50 DSN TEGALSARI DS PURI, PURI KAB.MOJOKERTO, JAWA TIMUR','KABUPATEN MOJOKERTO','JAWA TIMUR',NULL,'1',0,'2023-01-25 09:42:20','2023-01-25 09:42:20'),
	(342,'1','100342',NULL,'KUSWATI',NULL,NULL,'KALILANDAK RT 05 RW 05 PURWOREJO KLAMPOK BANJARNEGARA JAWA TENGAH','KABUPATEN BANJARNEGARA','JAWA TENGAH',NULL,'1',0,'2023-01-25 09:46:55','2023-01-25 09:46:55'),
	(343,'1','100343',NULL,'MEI ANGGRAINI',NULL,NULL,'KEDUNG JUMPUTREJO  GANG MELATI RT 20 RW 6 SUKODONO KAB SIDOARJO JAWA TIMUR','KABUPATEN SIDOARJO','JAWA TIMUR',NULL,'1',0,'2023-01-25 09:51:06','2023-01-25 09:51:06'),
	(344,'1','100344',NULL,'Septyana Laksita',NULL,NULL,'Dukuh Tirip RT1/RW1 Desa Bolali Wonosari Klaten Jateng','KABUPATEN KLATEN','JAWA TENGAH',NULL,'1',0,'2023-01-25 09:51:20','2023-01-25 09:51:20'),
	(345,'1','100345',NULL,'ELVIRA CLARIZA IRAWATI',NULL,NULL,'JALAN PATIMURA GANG 1 NO 61 RT 2 RW 08 BATU KOTA BATU JAWA TIMUR','KOTA BATU','JAWA TIMUR',NULL,'1',0,'2023-01-25 09:54:59','2023-01-25 09:54:59'),
	(346,'1','100346',NULL,'Mama Dinda',NULL,NULL,'Jl. Sandang Gang Mesjid Baitul Mukminin No 11 RT5/RW4 Margahayu Tengah Bandung Jabar','KABUPATEN BANDUNG','JAWA BARAT',NULL,'1',0,'2023-01-25 09:59:27','2023-01-25 09:59:27'),
	(347,'1','100347',NULL,'NASYIATUL AISYAH',NULL,NULL,'BANYU URIP GANG 11 NO 32A SAWAHAN SURABAYA JATIM','KOTA SURABAYA','JAWA TIMUR',NULL,'1',0,'2023-01-25 09:59:41','2023-01-25 09:59:41'),
	(348,'1','100348',NULL,'Sri Wahyuni',NULL,NULL,'Jl. Kedungdoro No 36-46 Blok Surabaya Kedungdoro Tegalsari Surabaya','KOTA SURABAYA','JAWA TIMUR',NULL,'1',0,'2023-01-25 10:08:22','2023-01-25 10:08:22'),
	(349,'1','100349',NULL,'Nindy',NULL,NULL,'Jl. Rapak Dalam Samarinda Rumah no 08','KOTA SAMARINDA','KALIMANTAN TIMUR',NULL,'1',0,'2023-01-25 10:16:48','2023-01-25 10:16:48'),
	(350,'1','100350',NULL,'Hatta',NULL,NULL,'Jl. Bak Air III No 55 RT3/RW3 Tanjung Pruk 64 Jakarta Utara DKI JKT','KOTA JAKARTA UTARA','DKI JAKARTA',NULL,'1',0,'2023-01-25 12:03:12','2023-01-25 12:03:12'),
	(351,'1','100351',NULL,'Yayang Meida Sekarinati',NULL,NULL,'Jl. Jombang Gang 1A No 57B Kel. Gading Klojen Malang','KABUPATEN JOMBANG','JAWA TIMUR',NULL,'1',0,'2023-01-26 08:58:54','2023-01-26 08:58:54'),
	(352,'1','100352',NULL,'Heni Tasya Salsabilla',NULL,NULL,'Simpang 3 Bakaran, JL. Kopral Urip Ilir Plaju 46 RT04/RW12 Plaju Palembang Sumatera Selatan','KOTA PALEMBANG','SUMATERA SELATAN',NULL,'1',0,'2023-01-26 09:04:21','2023-01-26 09:04:21'),
	(353,'1','100353',NULL,'Fatihaturrohmah (Fatik)',NULL,NULL,'Dsn. Kaliasin RT02/RW06 Ds Bendung Kec Jetis Mojokerto Jatim','KOTA MOJOKERTO','JAWA TIMUR',NULL,'1',0,'2023-01-26 09:08:07','2023-01-26 09:08:07'),
	(354,'1','100354',NULL,'Saiful Cholik',NULL,NULL,'Jl. Mendit Barat RT01/RW02 Mangliawan Pakis Malang Jatim','KOTA MALANG','JAWA TIMUR',NULL,'1',0,'2023-01-26 09:13:19','2023-01-26 09:13:19'),
	(355,'1','100355',NULL,'Tita Tiara',NULL,NULL,'Jl. Cipete Dalam 1 No 21 RT3/RW03 Cilandak Kota ajakarta Selatan','KOTA JAKARTA SELATAN','DKI JAKARTA',NULL,'1',0,'2023-01-26 09:17:52','2023-01-26 09:17:52'),
	(356,'1','100356',NULL,'Dita',NULL,NULL,'Wonosari Krajan RT002/RW003 Puger Jember Jatim','KABUPATEN JEMBER','JAWA TIMUR',NULL,'1',0,'2023-01-26 09:22:53','2023-01-26 09:22:53'),
	(357,'1','100357',NULL,'Rudianto / Gita Violeta',NULL,NULL,'Jl. Raya Mastrip Warugunung Gang Makam RT05/RW01 Karangpilang Surabaya','KOTA SURABAYA','JAWA TIMUR',NULL,'1',0,'2023-01-26 09:29:28','2023-01-26 09:29:28'),
	(358,'1','100358',NULL,'Puji Kurnia',NULL,NULL,'Jl. Otista Gg Mawar No 10 RT51/RW13 Karanganyar Subang Jabar','KABUPATEN SUBANG','JAWA BARAT',NULL,'1',0,'2023-01-26 09:34:24','2023-01-26 09:34:24'),
	(359,'1','100359',NULL,'Ayulestaribatubara',NULL,NULL,'Simalungun Dolok Silau Paribuan Dusun Pasar','KABUPATEN SIMALUNGUN','SUMATERA UTARA',NULL,'1',0,'2023-01-26 10:35:19','2023-01-26 10:35:19'),
	(360,'1','100360',NULL,'Dewi Kumala (Pak Nerik)',NULL,NULL,'Dusun Mbuek RT01/RW04 Sitirejo Wagir Malang Jatim','KABUPATEN MALANG','JAWA TIMUR',NULL,'1',0,'2023-01-26 15:15:55','2023-01-26 15:15:55'),
	(362,'1','100362',NULL,'Mylla Novizza Auliya',NULL,NULL,'Jl kyai Mashad GG kamituwo Lorong 1 nO 17 A rT kOTA SURABYA 06 rW 01B Ds Banjarjo, Bojonegoro Jawatimur','KABUPATEN BOJONEGORO','JAWA TIMUR',NULL,'1',0,'2023-01-27 08:54:28','2023-01-27 08:54:28'),
	(363,'1','100363',NULL,'Rizky',NULL,NULL,'Jl mauni 55 b Rt 2 Rw 3 bangsal, pesantren , kota kediri jawa timur','KABUPATEN KEDIRI','JAWA TIMUR',NULL,'1',0,'2023-01-27 09:00:38','2023-01-27 09:00:38'),
	(364,'1','100364',NULL,'Tri Haryanto',NULL,NULL,'Dk Jurug Rt 01 Rw 09, Menden , Kebonarum, KLaten, Jawa Tengah','KABUPATEN KLATEN','JAWA TENGAH',NULL,'1',0,'2023-01-27 09:08:30','2023-01-27 09:08:30'),
	(365,'1','100365',NULL,'Retno suryaningtyas',NULL,NULL,'Dsn klobungan Rt 28 Rw 05 Desa tegalsiwalan Kec. Tegalsiwalan Kab. Probolinggo depan kecamatan tegalsiwalan barat','KABUPATEN PROBOLINGGO','JAWA TIMUR',NULL,'1',0,'2023-01-27 09:20:51','2023-01-27 09:20:51'),
	(366,'1','100366',NULL,'Endah Septa Riana',NULL,NULL,'Perum, UNS V , Jl. Elang Blok A3 No.7, Ngringo','KABUPATEN KARANGANYAR','JAWA TENGAH',NULL,'1',0,'2023-01-27 11:30:53','2023-01-27 11:30:53'),
	(368,'1','100368',NULL,'Lusi Yunikwati',NULL,NULL,'Demak jaya Gang V no 41 Bubutan kota surabaya','KOTA SURABAYA','JAWA TIMUR',NULL,'1',0,'2023-01-27 12:00:40','2023-01-27 12:00:40'),
	(369,'1','100369',NULL,'Septi Rahartiningsih',NULL,NULL,'Perum. Villa Citayam, Jl. Duren Baru Blok D6 No.1, Susukan, Bojonggede','KOTA BOGOR','JAWA BARAT',NULL,'1',0,'2023-01-27 13:02:28','2023-01-27 13:02:28'),
	(370,'1','100370',NULL,'Raisa Maya',NULL,NULL,'Lidah Wetan 1a No.76, Lakarsantri, Kota Surabaya','KOTA SURABAYA','JAWA TIMUR',NULL,'1',0,'2023-01-27 14:29:07','2023-01-27 14:29:07'),
	(372,'1','100372',NULL,'CICAVILANA',NULL,NULL,'Kost Hasanah Kavling 8, Jl. Lidah Wetan V No.47, Lakarsantri, Surabaya','KOTA SURABAYA','JAWA TIMUR',NULL,'1',0,'2023-01-27 16:16:12','2023-01-27 16:16:12'),
	(373,'1','100373',NULL,'Septi Bu Tatiek',NULL,NULL,'Jl. Babatan Liid No. 2, Babatan, Wiyung, Surabaya','KOTA SURABAYA','JAWA TIMUR',NULL,'1',0,'2023-01-27 16:25:08','2023-01-27 16:25:08'),
	(374,'1','100374',NULL,'Shopie',NULL,NULL,'Kirim Kantor','KOTA SURABAYA','JAWA TIMUR',NULL,'1',0,'2023-01-28 09:02:43','2023-01-28 09:02:43'),
	(375,'1','100375',NULL,'Marlina Johan',NULL,NULL,'Jl. Kenari 2 RT10 No 159 Kel Kenari Kec Senen DKI Jakarta','KOTA JAKARTA PUSAT','DKI JAKARTA',NULL,'1',0,'2023-01-30 09:01:14','2023-01-30 09:01:14'),
	(376,'1','100376',NULL,'Laili',NULL,NULL,'Taman Surya Kencana Cluster Uranus C15 RT06 Grogol Tulangan Sidoarjo Jawatir','KABUPATEN SIDOARJO','JAWA TIMUR',NULL,'1',0,'2023-01-30 09:17:34','2023-01-30 09:17:34'),
	(378,'1','100378',NULL,'Nina',NULL,NULL,'Mulyosari Tengah VII No 63 Surabaya Masuk Gang Sebelah Bronis Amanda Mulyorejo Surabay','KOTA SURABAYA','JAWA TIMUR',NULL,'1',0,'2023-01-30 09:36:05','2023-01-30 09:36:05'),
	(379,'1','100379',NULL,'Siti Umaisaroh (B.Polo)',NULL,NULL,'Dsn Karang Asem RT1/RW9 Kedunggede Dlanggu Kab. Mojokerto Jatim','KABUPATEN MOJOKERTO','JAWA TIMUR',NULL,'1',0,'2023-01-30 09:49:17','2023-01-30 09:49:17'),
	(380,'1','100380',NULL,'Silvi Indah',NULL,NULL,'Gadel Sari Barat II No 6 RT004/RW006 Karangpoh Tandes Surabaya Jatim','KOTA SURABAYA','JAWA TIMUR',NULL,'1',0,'2023-01-30 09:53:10','2023-01-30 09:53:10'),
	(381,'1','100381',NULL,'Bu Alim',NULL,NULL,'Jl. Sawah Besar Raya RT 6/RW 6 Kel Kaligawe Gayamsari Semarang','KABUPATEN SEMARANG','JAWA TENGAH',NULL,'1',0,'2023-01-30 10:11:25','2023-01-30 10:11:25'),
	(382,'1','100382',NULL,'Shella Adella',NULL,NULL,'Jl. Jeruk No 87 Jatiagung Taman Sidoarjo Jatim','KABUPATEN SIDOARJO','JAWA TIMUR',NULL,'1',0,'2023-01-30 10:52:41','2023-01-30 10:52:41'),
	(384,'1','100384',NULL,'Yenny',NULL,NULL,'Surabay','KOTA SURABAYA','JAWA TIMUR',NULL,'1',0,'2023-01-30 13:33:19','2023-01-30 13:33:19'),
	(385,'1','100385',NULL,'Mbak Ipik',NULL,NULL,'Jl. Betet Barat RT10/RW04 Kediri Pesantren Jatim','KOTA KEDIRI','JAWA TIMUR',NULL,'1',0,'2023-01-30 15:51:10','2023-01-30 15:51:10'),
	(386,'1','100386',NULL,'NOvellya Lia',NULL,NULL,'Tenggilis Mejoyo Jl. Kutisari Sel XV No 49 Bibit ANggur Surabaya Jatim','KOTA SURABAYA','JAWA TIMUR',NULL,'1',0,'2023-01-30 15:55:48','2023-01-30 15:55:48'),
	(387,'1','100387',NULL,'Agistya Ananda Charisa',NULL,NULL,'Perum Kemiri Indah B4 No 3 Sidoarjo Jatim','KABUPATEN SIDOARJO','JAWA TIMUR',NULL,'1',0,'2023-01-31 09:17:14','2023-01-31 09:17:14'),
	(388,'1','100388',NULL,'Devi Dwi Puspitasari',NULL,NULL,'Kebraon Gg V No 32C RT06 RW02 Karangpilang Kota Surabaya Jatim','KOTA SURABAYA','JAWA TIMUR',NULL,'1',0,'2023-01-31 09:34:03','2023-01-31 09:34:03'),
	(389,'1','100389',NULL,'Huda Kamelia',NULL,NULL,'Jl. Taman Indrakila Golf No 31 Pandanwangi Blimbing Malang Jatim','KABUPATEN MALANG','JAWA TIMUR',NULL,'1',0,'2023-01-31 09:51:39','2023-01-31 09:51:39'),
	(390,'1','100390',NULL,'Wenny Rahma',NULL,NULL,'Ds. Pangkemiri RT5/RW2 Tulangan Sidoarjo Tulangan Jatim','KABUPATEN SIDOARJO','JAWA TIMUR',NULL,'1',0,'2023-01-31 10:10:55','2023-01-31 10:10:55'),
	(391,'3','100391','JTM 02 - Satya','SHINJUKU TUNJUNGAN PLASA','(031) 99246972',NULL,'Tunjungan Plaza, 6 Lt. 4 No. 17, Jl. Basuki Rahmat No.107, Kedungdoro, Kec. Tegalsari, Kota SBY, Jawa Timur 60261','KOTA SURABAYA','JAWA TIMUR',NULL,'1',0,'2023-01-31 14:10:35','2023-01-31 14:10:35'),
	(392,'3','100392','JTM 02 - Satya','SHINJUKU ATOM MALL','(031) 3530655',NULL,'Pasar Atom Tahap 2 3rd Floor B 28-29, Surabaya, Bongkaran, Kec. Pabean Cantikan, Kota SBY, Jawa Timur 60161','KOTA SURABAYA','JAWA TIMUR',NULL,'1',0,'2023-01-31 14:12:13','2023-01-31 14:12:13'),
	(393,'1','100393',NULL,'Ki Kadek Ayu Asri Anggrei',NULL,NULL,'Jl. Subak Dalem Gang VIII No 9X Denpasar Utara Kota Denpasar Bali','KOTA DENPASAR','BALI',NULL,'1',0,'2023-01-31 14:40:24','2023-01-31 14:40:24'),
	(394,'1','100394',NULL,'Yanti',NULL,NULL,'Jl. Bunguran No 45 Pabean Cantika Surabaya','KOTA SURABAYA','JAWA TIMUR',NULL,'1',0,'2023-02-01 09:10:41','2023-02-01 09:58:33'),
	(395,'1','100395',NULL,'yosita hatan',NULL,NULL,'Dusun Kabatmantre rt 04 rw 04 muncar banyuwangi  Jawa timur','KABUPATEN BANYUWANGI','JAWA TIMUR',NULL,'1',0,'2023-02-01 09:11:13','2023-02-01 09:11:13'),
	(396,'1','100396',NULL,'Satya Candra',NULL,NULL,'Medokan Ayu, Surabaya','KOTA SURABAYA','JAWA TIMUR',NULL,'1',0,'2023-02-01 09:45:26','2023-02-01 09:45:26'),
	(397,'1','100397',NULL,'Deska',NULL,NULL,'Jl. Jarak No 53 Putat Jaya Sawahan No 53 Surabaya Jatim','KOTA SURABAYA','JAWA TIMUR',NULL,'1',0,'2023-02-01 09:53:20','2023-02-01 09:53:20'),
	(398,'1','100398',NULL,'Syamsi F.O',NULL,NULL,'Rs. Marina Permata Jl. KOdeco No 4 Baronqa','KABUPATEN TANAH BUMBU','KALIMANTAN SELATAN',NULL,'1',0,'2023-02-01 10:08:18','2023-02-01 10:08:18'),
	(400,'1','100400',NULL,'Vidhad Messi',NULL,NULL,'Jl. Darmorejo VII No 10 Surabaya Jatim','KOTA SURABAYA','JAWA TIMUR',NULL,'1',0,'2023-02-01 14:57:42','2023-02-01 14:57:42'),
	(401,'1','100401',NULL,'Fajar',NULL,NULL,'Jl. Raya Lontar Gg Satoman No 75 RT4/RW1 Lontar Sambikerep Surabaya','KOTA SURABAYA','JAWA TIMUR',NULL,'1',0,'2023-02-01 15:02:52','2023-02-01 15:02:52'),
	(402,'1','100402',NULL,'Cintia',NULL,NULL,'Forest Mansion Cluster Blossom Hill B8 Surabaya','KOTA SURABAYA','JAWA TIMUR',NULL,'1',0,'2023-02-01 15:25:48','2023-02-01 15:25:48'),
	(403,'1','100403',NULL,'Najwa Yusra H Abdul Maji',NULL,NULL,'Jl. Tipar Cakung RT5/RW8 Cakung Bar Kota jakarta Timur','KOTA JAKARTA TIMUR','DKI JAKARTA',NULL,'1',0,'2023-02-02 09:27:00','2023-02-02 09:27:00'),
	(404,'1','100404',NULL,'Melia Wijaya',NULL,NULL,'Somerset GF 6/9 Citraland Surabaya Lakarsantri Surabaya Jatim','KOTA SURABAYA','JAWA TIMUR',NULL,'1',0,'2023-02-02 09:48:41','2023-02-02 09:48:41'),
	(405,'1','100405',NULL,'Intan Putri Arianti',NULL,NULL,'Jl. Balong Biru RT11/RW4 Sadang Taman Sidoarjo Jatim','KABUPATEN SIDOARJO','JAWA TIMUR',NULL,'1',0,'2023-02-02 12:34:29','2023-02-02 12:34:29'),
	(406,'1','100406',NULL,'Rossya',NULL,NULL,'Jemur Wonosari Gang Mualim No 4B RT06/RW09 Wonocolo Surabaya Jatim','KOTA SURABAYA','JAWA TIMUR',NULL,'1',0,'2023-02-03 08:51:30','2023-02-03 08:51:30'),
	(407,'1','100407',NULL,'Harsono',NULL,NULL,'Jl. Kembangsari RT3/RW5 Kembang Boyolali Ampel Jawa Tengah','KABUPATEN BOYOLALI','JAWA TENGAH',NULL,'1',0,'2023-02-03 09:01:41','2023-02-03 09:01:41'),
	(408,'1','100408',NULL,'Amelia putri','082149120245',NULL,'Jl. Tegalsari No.61,Tegalsari, kota surabaya','KOTA SURABAYA','JAWA TIMUR',NULL,'1',0,'2023-02-03 10:42:11','2023-03-18 08:51:26'),
	(409,'1','100409',NULL,'Dhenang Yudhistira',NULL,NULL,'Jl. Saco Masjid Jami Alfalah No 98A RT13/RW8 Ragunan Pasar Minngu Pasar Jakarta Selatan','KOTA JAKARTA SELATAN','DKI JAKARTA',NULL,'1',0,'2023-02-03 14:40:54','2023-02-03 14:40:54'),
	(410,'1','100410',NULL,'Elysa Rosalina',NULL,NULL,'Apartemen Puncak Cbd. Jln Kramat Kali Jajar Tunggal Wiyung Tower A unit 1061 Surabay','KOTA SURABAYA','JAWA TIMUR',NULL,'1',0,'2023-02-03 14:46:28','2023-02-03 14:46:28'),
	(411,'1','100411',NULL,'Nurul Aini Adkha',NULL,NULL,'Samping Bidan Narti','KABUPATEN REMBANG','JAWA TENGAH',NULL,'1',0,'2023-02-04 08:52:54','2023-02-04 08:52:54'),
	(412,'1','100412',NULL,'putri',NULL,NULL,'Pasar Atom Tahap 2 Lt 3 No 28 Salon Shinjuku Pabean Cantika Jawa Timur','KOTA SURABAYA','JAWA TIMUR',NULL,'1',0,'2023-02-04 09:00:11','2023-02-04 09:00:11'),
	(413,'1','100413',NULL,'Deni Prasetyo',NULL,NULL,'Asrama Unit Zikon 13 Jl. Seroja No 93 RT6/RW13 Jagakarsa Jakarta Selatan','KOTA JAKARTA SELATAN','DKI JAKARTA',NULL,'1',0,'2023-02-04 09:06:10','2023-02-04 09:06:10'),
	(414,'1','100414',NULL,'lisa',NULL,NULL,'Dusun kasren lor desa baadan (Rumah cat putih emas selatan masjid annur kasren lor) Pangkur, Ngawi, Jawatimur','KABUPATEN NGAWI','JAWA TIMUR',NULL,'1',0,'2023-02-04 09:10:02','2023-02-04 09:10:02'),
	(415,'1','100415',NULL,'Maulidiya muti',NULL,NULL,'Taman Sari JKT,l Manggis two kost kota, Jalan manggis No.2, RT 02 RW 02, Mangga besar','KOTA JAKARTA TIMUR','DKI JAKARTA',NULL,'1',0,'2023-02-06 09:13:35','2023-02-06 09:13:35'),
	(416,'1','100416',NULL,'Arista Asmarandari',NULL,NULL,'Jl. Pesalakan No 43 Gang 2 Kelurahan Demangan Bangkalan Jatim','KABUPATEN BANGKALAN','JAWA TIMUR',NULL,'1',0,'2023-02-06 09:18:59','2023-02-06 09:18:59'),
	(417,'1','100417',NULL,'istirokah',NULL,NULL,'Desa Tambakrejo RT 05 Rw 03 Kecamatan Patebon Kendal Jawa Tengah','KABUPATEN KENDAL','JAWA TENGAH',NULL,'1',0,'2023-02-06 09:23:51','2023-02-06 09:23:51'),
	(418,'1','100418',NULL,'Arum Festy',NULL,NULL,'Perum Btn Tonggara RT9/RW3 Kedung Banteng Jateng','KABUPATEN TEGAL','JAWA TENGAH',NULL,'1',0,'2023-02-06 09:25:50','2023-02-06 09:25:50'),
	(419,'1','100419',NULL,'Dianaa',NULL,NULL,'Graha Padma JL. Padma Boulevard AA1 No.1 Krapyak - Semarang Barat Jawa Tengah','KABUPATEN SEMARANG','JAWA TENGAH',NULL,'1',0,'2023-02-06 09:35:11','2023-02-06 09:35:11'),
	(420,'1','100420',NULL,'Dhikaa',NULL,NULL,'Jalan, Jojoran Baru No 47 Baru Gubeng Surabaya Jatim','KOTA SURABAYA','JAWA TIMUR',NULL,'1',0,'2023-02-06 09:38:42','2023-02-06 09:38:42'),
	(421,'1','100421',NULL,'Vita Amelia',NULL,NULL,'Dsn. Kejambon RT 2/RW 1  ngabetan Cerme Gresik Jawatimur','KABUPATEN GRESIK','JAWA TIMUR',NULL,'1',0,'2023-02-06 14:05:51','2023-02-06 14:05:51'),
	(422,'1','100422',NULL,'Eka Wulandari',NULL,NULL,'Dsn. Karang Asem RT 1/ RW 3 Karang Andong Driyorejo Gresik Jatim','KABUPATEN GRESIK','JAWA TIMUR',NULL,'1',0,'2023-02-07 08:56:47','2023-02-07 08:56:47'),
	(423,'1','100423',NULL,'adel darminto',NULL,NULL,'Jalan kemiri Indah Barat II Blok D no 8 RT 21 Rw 5, kemiri, sidoarjo jawa timur','KABUPATEN SIDOARJO','JAWA TIMUR',NULL,'1',0,'2023-02-07 10:02:22','2023-02-07 10:02:22'),
	(424,'1','100424',NULL,'Ayu Rizal 089676227194',NULL,NULL,'Desa Lemah Putro RT6/RW2 Sidoarjo Jatim','KABUPATEN SIDOARJO','JAWA TIMUR',NULL,'1',0,'2023-02-07 11:04:49','2023-02-07 11:04:49'),
	(425,'1','100425',NULL,'Pak Rizki',NULL,NULL,'Forest Mansion Cluster Blossom Hill B8 Surabaya','KOTA SURABAYA','JAWA TIMUR',NULL,'1',0,'2023-02-07 14:04:19','2023-02-07 14:04:19'),
	(426,'1','100426',NULL,'Diah Nur Aini',NULL,NULL,'Sidotopo Kulon 329 RT011/RW004 Kel Sidotopo Semampir Surabaya Jatim','KOTA SURABAYA','JAWA TIMUR',NULL,'1',0,'2023-02-08 08:50:02','2023-02-08 08:50:02'),
	(427,'1','100427',NULL,'Rani Nawangsari',NULL,NULL,'Gumiwang Komunika Jl. Yoss Sudarso No 83 Indramayu Kab Indramayu Jabar','KABUPATEN INDRAMAYU','JAWA BARAT',NULL,'1',0,'2023-02-08 09:10:26','2023-02-08 09:10:26'),
	(428,'3','100428',NULL,'RAENA','HILMAN (08161929195)',NULL,'Gudang Argo Pantes Cibitung, (Shipper) Jl. Kalimantan Blok B2, MM2100, Cibitung-Bekasi 17520','KABUPATEN BEKASI','JAWA BARAT',NULL,'1',0,'2023-02-08 10:51:07','2023-02-08 10:51:07'),
	(429,'2','100429',NULL,'Defa 081233339109',NULL,NULL,'Perum Grand Deltasari Cluster Magnolia Blok 6 No 3 Waru Sidoarjo','KABUPATEN SIDOARJO','JAWA TIMUR',NULL,'1',0,'2023-02-08 11:20:30','2023-02-08 11:20:30'),
	(430,'1','100430',NULL,'Lilis Nopita / Pita',NULL,NULL,'Jl. Picung Kampung Sikluk RT2/RW1 Pemantang Tigaraksa Komlok Tangerang Bnaten','KABUPATEN TANGERANG','BANTEN',NULL,'1',0,'2023-02-08 15:16:59','2023-02-08 15:16:59'),
	(431,'1','100431',NULL,'toyibah',NULL,NULL,'dsn.slawe, ds balongmacekan RT 16/rw 05 kec tarik','KABUPATEN SIDOARJO','JAWA TIMUR',NULL,'1',0,'2023-02-09 08:42:44','2023-02-09 08:42:44'),
	(432,'1','100432',NULL,'Dwi Septi Nuraini',NULL,NULL,'Jl. Garuda III Blok B3 No 22 Wisma Pangeran Asri RT4/RW9 Kelurahan Pangeran Bangkalan Jatim','KABUPATEN BANGKALAN','JAWA TIMUR',NULL,'1',0,'2023-02-09 08:51:40','2023-02-09 08:51:40'),
	(433,'1','100433',NULL,'Septiani Novia Putri',NULL,NULL,'Jl. Siwalankero Tengah Gg apel No 104E Siwalankerto Wonocolo Surabaya Jatim','KOTA SURABAYA','JAWA TIMUR',NULL,'1',0,'2023-02-09 12:53:12','2023-02-09 12:53:12'),
	(434,'1','100434',NULL,'Zidane',NULL,NULL,'Jl. Gajahmada No 1 Semanding Kab Tuban Jatim','KABUPATEN TUBAN','JAWA TIMUR',NULL,'1',0,'2023-02-09 14:22:22','2023-02-09 14:22:22'),
	(435,'1','100435',NULL,'Irma',NULL,NULL,'Jl. Krembangan Bhakti gANG 12c nO 49b Krembangan Kota Surabaya Jatim','KOTA SURABAYA','JAWA TIMUR',NULL,'1',0,'2023-02-09 15:15:33','2023-02-09 15:15:33'),
	(436,'1','100436',NULL,'Mamah Yusuf',NULL,NULL,'Jl. H Jidi Kec Cinere RT006/RW01 No 46 Mamah Yusuf Depok Jabar','KOTA DEPOK','JAWA BARAT',NULL,'1',0,'2023-02-10 08:54:05','2023-02-10 08:54:05'),
	(437,'1','100437',NULL,'Shinta Deby',NULL,NULL,'Kembang Kuning Kulon Besar C/17 Sawahan Surabaya Jatim','KOTA SURABAYA','JAWA TIMUR',NULL,'1',0,'2023-02-10 11:10:12','2023-02-10 11:10:12'),
	(438,'1','100438',NULL,'shima wiyana',NULL,NULL,'jln dharmawanngsa gang bentoel rt 04 rw 04 kaliwing','KABUPATEN JEMBER','JAWA TIMUR',NULL,'1',0,'2023-02-11 08:47:01','2023-02-11 08:47:01'),
	(439,'1','100439',NULL,'mastur',NULL,NULL,'jln jati 4 no 20 rt 6 rw 5, sungai bambu, tanjung priok, jakarta utara','KOTA JAKARTA UTARA','DKI JAKARTA',NULL,'1',0,'2023-02-11 12:50:01','2023-02-11 12:50:01'),
	(440,'1','100440',NULL,'Angelica Prasanti',NULL,NULL,'JL. OSCAR VI No. 20, KOTA BARU, KEL. BEKASI BARAT, BEKASI , JAWA BARAT','KOTA BEKASI','JAWA BARAT',NULL,'1',0,'2023-02-13 09:01:05','2023-02-13 09:01:05'),
	(441,'1','100441',NULL,'BU NENENG',NULL,NULL,'JL. EVAKUASI GANG LANGGAR NO.26, KAMP.KALILEBAT BARU, DS. KARYAMULYA RT01/RW01, KESAMBI, HARJAMUKTI','KOTA CIREBON','JAWA BARAT',NULL,'1',0,'2023-02-13 09:17:49','2023-02-13 09:17:49'),
	(442,'1','100442',NULL,'DEWI ANGGRAINI',NULL,NULL,'DUSUN KALIMUJUR GANG GEMPOL, RT 02/RW 02, DS.SEDARAT, BALONG PONOROGO','KABUPATEN PONOROGO','JAWA TIMUR',NULL,'1',0,'2023-02-13 09:26:00','2023-02-13 09:26:00'),
	(443,'1','100443',NULL,'NADYA',NULL,NULL,'PERUMAHAN PURI PERMATA BLOK E - 65, SEMBUNG, TULUNGAGUNG','KABUPATEN TULUNGAGUNG','JAWA TIMUR',NULL,'1',0,'2023-02-13 09:34:38','2023-02-13 09:34:38'),
	(444,'1','100444',NULL,'NOVIA',NULL,NULL,'GRIYA BHAYANGKARA A2-6, MASANGAN KULON, SUKODONO, SIDOARJO','KABUPATEN SIDOARJO','JAWA TIMUR',NULL,'1',0,'2023-02-13 09:45:08','2023-02-13 09:45:08'),
	(445,'1','100445',NULL,'FEBBY NURJANAH',NULL,NULL,'JL.KENANGA 23 NO.5B,  RT 10, RATU AGUNG, KOTA BENGKULU','KOTA BENGKULU','BENGKULU',NULL,'1',0,'2023-02-13 10:01:40','2023-02-13 10:01:40'),
	(447,'1','100447',NULL,'FATIKA HARTANTI',NULL,NULL,'(RUMAH IBU SUMARNI) JL. DAMARSARI RT 01/RW 01, CEPIRING, KENDAL','KABUPATEN KENDAL','JAWA TENGAH',NULL,'1',0,'2023-02-13 10:23:02','2023-02-13 10:23:02'),
	(448,'1','100448',NULL,'NENG RATNA',NULL,NULL,'JL.EVAKUASI NO.26 GANG LANGGAR, RT 01/RW 01, KAMPUNG KALILEBAT BARU, KEL.KARYAMULYA, KEC. KESAMBI','KABUPATEN CIREBON','JAWA BARAT',NULL,'1',0,'2023-02-13 15:08:40','2023-02-13 15:08:40'),
	(449,'1','100449',NULL,'Meta Dewi Andriyanti',NULL,NULL,'JOjoran 5 Timur Blok A/12 Mojo Gubeng Surabaya Jatim','KOTA SURABAYA','JAWA TIMUR',NULL,'1',0,'2023-02-14 08:46:23','2023-02-14 08:46:23'),
	(450,'1','100450',NULL,'Yunita',NULL,NULL,'Jl. Kejawan Putih Tambak Gang 17 No 2 Mulyorejo Surabaya Jatim','KOTA SURABAYA','JAWA TIMUR',NULL,'1',0,'2023-02-14 08:51:06','2023-02-14 08:51:06'),
	(451,'1','100451',NULL,'ocaa',NULL,NULL,'Perum Puri Anyelir, Jl. Puspowarno No 22 Mangkujaya Ponorogo Jatim','KABUPATEN PONOROGO','JAWA TIMUR',NULL,'1',0,'2023-02-14 08:56:44','2023-02-14 08:56:44'),
	(452,'1','100452',NULL,'Eni Purwaningsih',NULL,NULL,'Jl. Pulosari 3 K No 38a Gunung Sari Dukuh Pakis Jatim','KOTA SURABAYA','JAWA TIMUR',NULL,'1',0,'2023-02-14 11:05:58','2023-02-14 11:05:58'),
	(453,'1','100453',NULL,'Pak Mirza',NULL,NULL,'perum forest mansion cluster blossom hill b8 surabaya','KOTA SURABAYA','JAWA TIMUR',NULL,'1',0,'2023-02-14 14:42:38','2023-02-14 14:42:38'),
	(454,'1','100454',NULL,'Iib',NULL,NULL,'BG Junction Mall Lantai LL Blok C 27 Bubutan Surabaya Jatim','KOTA SURABAYA','JAWA TIMUR',NULL,'1',0,'2023-02-15 09:46:16','2023-02-15 09:46:16'),
	(455,'1','100455',NULL,'Erna Juniati',NULL,NULL,'Jl. Petemon IV NO 143 I RT2/RW13 Petemon Sawahan Surabaya Jatim','KOTA SURABAYA','JAWA TIMUR',NULL,'1',0,'2023-02-15 09:53:08','2023-02-15 09:53:08'),
	(456,'1','100456',NULL,'Safira',NULL,NULL,'Jl. Baret Biru III No 10 RT 10/RW3 Kalisari Pasar Rebo Jakarta Timur DKI Jakarta','KOTA JAKARTA TIMUR','DKI JAKARTA',NULL,'1',0,'2023-02-15 10:02:53','2023-02-15 10:02:53'),
	(457,'1','100457',NULL,'Ratu Frannetha',NULL,NULL,'Jl. Hj. Bakir Perumahan Cempaka Emas Residnce2 Blok D46 RT06/RW02 Kampak Kulan Gabek Pangkal Pinang Belitung','KOTA PANGKAL PINANG','KEPULAUAN BANGKA BELITUNG',NULL,'1',0,'2023-02-15 10:09:11','2023-02-15 10:09:11'),
	(458,'1','100458',NULL,'FATAYA',NULL,NULL,'TENGGILIS MEJOYO, GG LEBAR NO 109 SURABAYA JAWATIMUR','KOTA SURABAYA','JAWA TIMUR',NULL,'1',0,'2023-02-15 16:11:43','2023-02-15 16:11:43'),
	(459,'1','100459',NULL,'ANDRI',NULL,NULL,'PERUMAHAN RANGGA REGENCY SUMBEREJO BLOK E4 LUMAJANG JAWA TIMUR BLOK E4 ADA TOKO MS.GLOW.MBAK YANTI.ANDRI','KABUPATEN LUMAJANG','JAWA TIMUR',NULL,'1',0,'2023-02-16 08:46:19','2023-02-16 08:46:19'),
	(460,'1','100460',NULL,'Brian Risga H',NULL,NULL,'Jl. Kencana Sari Barat 2 Blok AA No 1 Dukuh Pakis Surabaya Jatim','KOTA SURABAYA','JAWA TIMUR',NULL,'1',0,'2023-02-16 08:51:38','2023-02-16 08:51:38'),
	(461,'1','100461',NULL,'Elvano',NULL,NULL,'Jl. Pandugo 45E No 9 Penjaringan Sari Rungkut Surabaya Jatim','KOTA SURABAYA','JAWA TIMUR',NULL,'1',0,'2023-02-16 08:56:13','2023-02-16 08:56:13'),
	(462,'1','100462',NULL,'Ivana',NULL,NULL,'Hotel Central Park Kuta Jl. Pati Jelantik Kuta Bandung Bali','KABUPATEN BADUNG','BALI',NULL,'1',0,'2023-02-16 09:02:03','2023-02-16 09:02:03'),
	(463,'1','100463',NULL,'Nawang Wulan',NULL,NULL,'Jl. Dadali Gang Pintu Air 1 RT02/RW001 No 55 Sawahan Lama Ciputat Kota tangerang Selatan Banten','KOTA TANGERANG SELATAN','BANTEN',NULL,'1',0,'2023-02-16 09:07:23','2023-02-16 09:07:23'),
	(464,'1','100464',NULL,'Nadia Anugrah',NULL,NULL,'PT. Metiska Farma Jl. Kebayoran Lama No 557Kebayoran Kota Jakarta Selatan DKI Jakarta','KOTA JAKARTA SELATAN','DKI JAKARTA',NULL,'1',0,'2023-02-16 09:26:45','2023-02-16 09:26:45'),
	(465,'1','100465',NULL,'Lalaa (Mama Mika)',NULL,NULL,'Kusen Irfan Jaya Abadi, Jl. Sasak Dempul Burangkeng Setu RT3/RW5 Bekasi Jabar','KABUPATEN BEKASI','JAWA BARAT',NULL,'1',0,'2023-02-16 09:29:57','2023-02-16 09:29:57'),
	(466,'1','100466',NULL,'Nirmala',NULL,NULL,'Jl. Panglima Sudirman No 45A Kel Girimoyo Karangploso Malang Jatim','KOTA MALANG','JAWA TIMUR',NULL,'1',0,'2023-02-17 09:16:26','2023-02-17 09:16:26'),
	(467,'1','100467',NULL,'Anik Rohmawati',NULL,NULL,'Dsn Sumber Ds. Woromarto RT02/RW05 Kec Purwoasri kab kediri Jatim','KABUPATEN KEDIRI','JAWA TIMUR',NULL,'1',0,'2023-02-17 09:29:03','2023-02-17 09:29:03'),
	(468,'1','100468',NULL,'Siti Umi Fatimah',NULL,NULL,'Desa Pagerwojo RT05/RW01 Pagerwojo Perak Kab Jombang Jatim','KABUPATEN JOMBANG','JAWA TIMUR',NULL,'1',0,'2023-02-20 08:57:55','2023-02-20 08:57:55'),
	(469,'1','100469',NULL,'Tia Mama Azril',NULL,NULL,'Jl. Rajawali RT6/RW2 Desa Punggul Gedangan Kab Sidoarjo Jatim','KABUPATEN SIDOARJO','JAWA TIMUR',NULL,'1',0,'2023-02-20 09:22:19','2023-02-20 09:22:19'),
	(470,'1','100470',NULL,'ARDIAN MAWARNI',NULL,NULL,'RM 57 MANTREN KRAJAN KULON JALAN RAYA SOLO -PACITAN(MANTREN KRAJAN KULON)PUNUNG , PACITAN, JAWA TIMUR','KABUPATEN PACITAN','JAWA TIMUR',NULL,'1',0,'2023-02-20 09:22:53','2023-02-20 09:22:53'),
	(471,'1','100471',NULL,'Septin Wulandari',NULL,NULL,'Jl. Kehakiman RT6/RW3 Babat Jerawat Blok E3 Surabaya Jatim','KOTA SURABAYA','JAWA TIMUR',NULL,'1',0,'2023-02-20 09:26:21','2023-02-20 09:26:21'),
	(472,'1','100472',NULL,'Eka Yuskantri',NULL,NULL,'Aprt, Supermall Mansion Tanglin 2203 Jl Raya Lontar No 5 Sambikerep Surabaya Jatim','KOTA SURABAYA','JAWA TIMUR',NULL,'1',0,'2023-02-20 09:34:32','2023-02-20 09:34:32'),
	(473,'1','100473',NULL,'Bagas (Badak)',NULL,NULL,'Dusun Wonokerto RT03/04 Desa Karangbanyu Widodaren Kab. Ngawi Jatim','KABUPATEN NGAWI','JAWA TIMUR',NULL,'1',0,'2023-02-20 09:40:40','2023-02-20 09:40:40'),
	(474,'1','100474',NULL,'Rara Ritonga',NULL,NULL,'Lippo Cikarang Taman Lembah Hijau Jl. Azalea III No 10 Serang CIkarang Selatan Bekasi Jabar','KABUPATEN BEKASI','JAWA BARAT',NULL,'1',0,'2023-02-20 09:45:36','2023-02-20 09:45:36'),
	(475,'1','100475',NULL,'Sumarni Amalia',NULL,NULL,'Jln. Keramat lama RT4/Dusun Pantai Batu Belubang Pangkalan Baru Bangka Tengah Bangka belitung','KABUPATEN BANGKA TENGAH','KEPULAUAN BANGKA BELITUNG',NULL,'1',0,'2023-02-20 10:00:56','2023-02-20 10:00:56'),
	(476,'1','100476',NULL,'Kiki/Irli',NULL,NULL,'Dusun Tegal Gunung No 10 RT4/RW13 Bluru Kidul Sidoarjo Kab Sidoarjo','KABUPATEN SIDOARJO','JAWA TIMUR',NULL,'1',0,'2023-02-20 10:04:57','2023-02-20 10:04:57'),
	(477,'1','100477',NULL,'Santi',NULL,NULL,'Madyopuro Gang 5 RT02/RW02 Kedungkandang Malang Jatim','KOTA MALANG','JAWA TIMUR',NULL,'1',0,'2023-02-20 11:58:08','2023-02-20 11:58:08'),
	(478,'1','100478',NULL,'SEKAR',NULL,NULL,'JL. MOH KAHFI 1 NO 22 SEKAR JL MOH KAHFI NO 22 RT 01 RW 02 KEL CIPEDAK KEC JAGAKARSA, JAKSEL 126 30 PATOKAN TOKO KAYU SEKAR JAYA JAGAKARSA, JAKARTA, DKI JAKARTA','KOTA JAKARTA SELATAN','DKI JAKARTA',NULL,'1',0,'2023-02-20 15:00:53','2023-02-20 15:00:53'),
	(479,'1','100479',NULL,'Nadia',NULL,NULL,'Jl. Simorejo V No 43 Simomulyo Sukomanunggal SUrabaya Jatim','KOTA SURABAYA','JAWA TIMUR',NULL,'1',0,'2023-02-20 15:47:29','2023-02-20 15:47:29'),
	(480,'1','100480',NULL,'Anjali Maulida Putri Cantik',NULL,NULL,'Jl. Kerinci No 84 RT 003/15 Sidanegara Cilacap Tengah Kab Cilacap Tengah Jateng','KABUPATEN CILACAP','JAWA TENGAH',NULL,'1',0,'2023-02-21 09:38:42','2023-02-21 09:38:42'),
	(481,'1','100481',NULL,'Khanaya',NULL,NULL,'Jl. Simpang Teluk Bayur No 28 Pandnwangi Blimbing Malang Jatim','KOTA MALANG','JAWA TIMUR',NULL,'1',0,'2023-02-21 10:54:35','2023-02-21 10:54:35'),
	(482,'1','100482',NULL,'Khotijah',NULL,NULL,'Gruggak Sejati Camplong Sampang Jatim','KABUPATEN SAMPANG','JAWA TIMUR',NULL,'1',0,'2023-02-22 09:36:31','2023-02-22 09:36:31'),
	(483,'1','100483',NULL,'Fitriyana',NULL,NULL,'Jl. KH Ashim Ashari Gang Kemuliaan RT004/RW02 No 67 Cipondoh 15148 Tangerang Banten','KABUPATEN TANGERANG','BANTEN',NULL,'1',0,'2023-02-22 13:41:03','2023-02-22 13:41:03'),
	(484,'1','100484',NULL,'Tiara Permata',NULL,NULL,'Jl. Oberlin Metar Perum Pertamina No 129 Kahayan Hilir Kab Pulang Pisau Kalteng','KABUPATEN PULANG PISAU','KALIMANTAN TENGAH',NULL,'1',0,'2023-02-23 09:57:55','2023-02-23 09:57:55'),
	(485,'3','100485','JTM 02','SHINJUKU PTC 2','-','-','SHINJUKU SALON PTC LT GROUND','KABUPATEN PACITAN','JAWA TIMUR','-','1',0,'2023-02-23 10:57:48','2023-02-23 11:01:49'),
	(486,'3','100486','JTM 02','SHINJUKU OAKWOOD','(031) 60010605','-','Shinjuku Oakwood, Jl. Raya Kertajaya Indah No.79a, Manyar Sabrangan,Mulyorejo, Kota SBY','KABUPATEN PACITAN','JAWA TIMUR','-','1',0,'2023-02-23 10:59:35','2023-02-23 10:59:35'),
	(487,'3','100487','JTM 02','Shinjuku_Galaxy Mall','(031) 5957922','-','Galaxy Mall 3, Jl. Dr. Ir. H. Soekarno No.35-39, Mulyorejo, Surabaya','KOTA SURABAYA','JAWA TIMUR','-','1',0,'2023-02-23 11:00:44','2023-02-23 11:00:44'),
	(488,'1','100488',NULL,'Juna Sianturi',NULL,NULL,'Komplek Polri Brimob Gang Garuda 3 Nomor 47 Pasar Minggu Kota Jakarta Selatan DKI Jakarta','KOTA JAKARTA SELATAN','DKI JAKARTA',NULL,'1',0,'2023-02-23 13:42:14','2023-02-23 13:42:14'),
	(489,'1','100489',NULL,'Diana Nursafitri',NULL,NULL,'Buccheri Gabino Royal Plaza Lantai UG Jl. A Yani Frontage Barat Wonokromo Lantai UG EZ18-23 Surabaya Jatim','KOTA SURABAYA','JAWA TIMUR',NULL,'1',0,'2023-02-24 10:21:33','2023-02-24 10:21:33'),
	(490,'1','100490',NULL,'Peony.makeup99',NULL,NULL,'Jl. Candi Mendut Barat VI A/B5 Lowokwaru Malang Jatim','KOTA MALANG','JAWA TIMUR',NULL,'1',0,'2023-02-24 10:28:58','2023-02-24 10:28:58'),
	(491,'1','100491',NULL,'Khusnul Khotimah',NULL,NULL,'Jl. Brigjen Katamso I Waru Sidoarjo Jatim','KABUPATEN SIDOARJO','JAWA TIMUR',NULL,'1',0,'2023-02-25 08:49:35','2023-02-25 08:49:35'),
	(492,'1','100492',NULL,'fahri dental',NULL,NULL,'jalan siliwangi ahli gigi fahri dental jalan siliwangi sukabumi, jawa barat','KOTA SUKABUMI','JAWA BARAT',NULL,'1',0,'2023-02-25 08:52:24','2023-02-25 08:52:24'),
	(493,'1','100493',NULL,'Wardah Maulidiyah',NULL,NULL,'Jl. Ketintang No 69 Wonokromo Surabaya Jatim','KOTA SURABAYA','JAWA TIMUR',NULL,'1',0,'2023-02-25 08:54:10','2023-02-25 08:54:10'),
	(494,'1','100494',NULL,'Badruzaman',NULL,NULL,'Jl. Widya kencana lakarsantri Blok LL 07 surabaya jatim','KOTA SURABAYA','JAWA TIMUR',NULL,'1',0,'2023-02-25 08:59:17','2023-02-25 08:59:17'),
	(495,'1','100495',NULL,'Sari Kusuma',NULL,NULL,'Griya Kebon Agung 2/G3 No3 Sukodono Kab Sidoarjo Jatim','KABUPATEN SIDOARJO','JAWA TIMUR',NULL,'1',0,'2023-02-25 09:05:46','2023-02-25 09:05:46'),
	(496,'1','100496',NULL,'Nurul Wahidah',NULL,NULL,'Jl. Ploso Baru No 30 Tambaksari Surabaya Jatim','KOTA SURABAYA','JAWA TIMUR',NULL,'1',0,'2023-02-27 08:39:56','2023-02-27 08:39:56'),
	(497,'1','100497',NULL,'Ragil Express / ANggra Yuli',NULL,NULL,'Jl. Aselin No 53 RT7/RW1 Kel Cipedak Jagakarsa DKI Jakarta','KOTA JAKARTA SELATAN','DKI JAKARTA',NULL,'1',0,'2023-02-27 08:45:07','2023-02-27 08:45:07'),
	(498,'1','100498',NULL,'Erzha Safitri',NULL,NULL,'Jl. Tambaksari Selatan Gang 6 No 22 Surabaya Jatim','KOTA SURABAYA','JAWA TIMUR',NULL,'1',0,'2023-02-27 08:50:20','2023-02-27 08:50:20'),
	(499,'1','100499',NULL,'Dira',NULL,NULL,'Jl. Perum Bumi CIkarang Sukadami Cikarang Selatan Blok E25 No 1 Lantai 2 Cikarang Selatan Bekasi JAbar','KOTA BEKASI','JAWA BARAT',NULL,'1',0,'2023-02-27 08:59:35','2023-02-27 08:59:35'),
	(500,'1','100500',NULL,'Yani Gutt Salon',NULL,NULL,'Jl. Mastrip Timur No 95 Krajan Timur Sumbersari Jember Jatim','KABUPATEN JEMBER','JAWA TIMUR',NULL,'1',0,'2023-02-27 09:06:15','2023-02-27 09:06:15'),
	(501,'1','100501',NULL,'Shelin',NULL,NULL,'PT. Leetex Garment , Jl Rukopinangsia Blok M No 1 Panuanngangan Barat Cibodas Tangerang Banten','KABUPATEN TANGERANG','BANTEN',NULL,'1',0,'2023-02-27 09:11:23','2023-02-27 09:11:23'),
	(502,'1','100502',NULL,'Dwi Wijayanti',NULL,NULL,'Kedungjenar, Jl. Ciliwung RT5/RW3 Kedungjenar Blora Jateng','KABUPATEN BLORA','JAWA TENGAH',NULL,'1',0,'2023-02-27 09:16:07','2023-02-27 09:16:07'),
	(503,'1','100503',NULL,'MW Jaya',NULL,NULL,'Jl. ALas Malang No 41 RT02/RW3 Bringin Sambikerep No 41 Surabaya','KOTA SURABAYA','JAWA TIMUR',NULL,'1',0,'2023-02-27 09:32:00','2023-02-27 09:32:00'),
	(504,'1','100504',NULL,'Seli Ima 0811-250-115',NULL,NULL,'Jl. Batikan No 76 Pandean Kec. Umbulharjo Yogyakarta','KOTA YOGYAKARTA','DI YOGYAKARTA',NULL,'1',0,'2023-02-27 10:42:21','2023-02-27 10:42:21'),
	(505,'1','100505',NULL,'shofia',NULL,NULL,'jl trunojoyo no 67 pos satpam telkom pamekasan, jawa timur','KABUPATEN PAMEKASAN','JAWA TIMUR',NULL,'1',0,'2023-02-27 12:59:49','2023-02-27 12:59:49'),
	(506,'1','100506',NULL,'rina putri aisyah',NULL,NULL,'kedai kane jombang, jalan brigjem katamso no 64 sengon jombang, kabupaten jombang jawa timur','KABUPATEN JOMBANG','JAWA TIMUR',NULL,'1',0,'2023-02-28 08:48:05','2023-02-28 08:48:05'),
	(507,'1','100507',NULL,'Tina',NULL,NULL,'Jln. Anjasmoro No 26 RT 05 RW02 Desa Turirejo lawang Kab Malang Jatim','KABUPATEN MALANG','JAWA TIMUR',NULL,'1',0,'2023-02-28 09:26:49','2023-02-28 09:26:49'),
	(508,'1','100508',NULL,'Cintya Surya Anggraeni',NULL,NULL,'Jl. Raya Pancawarna No 11 C/AN 28 Mulung Driyorejo Gresik Jatim','KABUPATEN GRESIK','JAWA TIMUR',NULL,'1',0,'2023-02-28 09:37:39','2023-02-28 09:37:39'),
	(509,'1','100509',NULL,'Agum Ifanny',NULL,NULL,'Jl. Semolowaru Selatan Gang 1 No 1a Surabaya Sukolilo Jatim','KOTA SURABAYA','JAWA TIMUR',NULL,'1',0,'2023-02-28 14:22:59','2023-02-28 14:22:59'),
	(510,'1','100510',NULL,'santii',NULL,NULL,'JL RAYA KUPANG INDAH NO 3 1 RESTO BAKSO SOLO SAMRAT DUKUH PAKIS SURABAYA JAWA TIMUR','KOTA SURABAYA','JAWA TIMUR',NULL,'1',0,'2023-03-01 09:21:10','2023-03-01 09:21:10'),
	(511,'1','100511',NULL,'Christine',NULL,NULL,'Jl. Manyar Kertoarjo No 78 RT3/RW6 Manyar Sabrangan Mulyorejo Surabaya Jatim','KOTA SURABAYA','JAWA TIMUR',NULL,'1',0,'2023-03-01 13:38:47','2023-03-01 13:38:47'),
	(512,'1','100512',NULL,'Binti Puji Lestari',NULL,NULL,'Jl. Salam II Dukuh Bulu II RT2/RW1 Bulu Sambit Poorogo Jatim','KABUPATEN PONOROGO','JAWA TIMUR',NULL,'1',0,'2023-03-02 08:55:36','2023-03-02 08:55:36'),
	(513,'1','100513',NULL,'Maretha Claranita D',NULL,NULL,'JL. Widyasari No 3 RT16/RW5 Rejo Mulyo Kartoharjo Kota Madiun Jatim','KABUPATEN MADIUN','JAWA TIMUR',NULL,'1',0,'2023-03-02 09:01:34','2023-03-02 09:01:34'),
	(514,'1','100514',NULL,'Vivi Suwanto',NULL,NULL,'Perum Taman Gunung ANyar Blok B18 Gununganyar Surabaya Jatim','KOTA SURABAYA','JAWA TIMUR',NULL,'1',0,'2023-03-03 09:05:01','2023-03-03 09:05:01'),
	(515,'1','100515',NULL,'SITI ALFIATUL HASANAH',NULL,NULL,'DUSUN TEGAL GEBANG RT 01 RW 021 INTAN PLASTIK BANGSALSARI JEMBER, JAWA TIMUR','KABUPATEN JEMBER','JAWA TIMUR',NULL,'1',0,'2023-03-03 10:20:07','2023-03-03 10:20:07'),
	(516,'1','100516',NULL,'Caca Vinca',NULL,NULL,'Confera Resto LL-07 Lakasantri Surabaya Jatim','KOTA SURABAYA','JAWA TIMUR',NULL,'1',0,'2023-03-03 14:05:37','2023-03-03 14:05:37'),
	(517,'1','100517',NULL,'Ladya Noija',NULL,NULL,'Jl. Gubeng Kertajaya VII K No 2 RT10/RW4 Airlan Gubeng Surabaya Jatim','KOTA SURABAYA','JAWA TIMUR',NULL,'1',0,'2023-03-03 14:39:31','2023-03-03 14:39:31'),
	(518,'1','100518',NULL,'Afrillia Lestari',NULL,NULL,'Dusun Beji Desa Banjarsari RT8/RW2 Banjarsari Buduran Sidoarjo Jatim','KABUPATEN SIDOARJO','JAWA TIMUR',NULL,'1',0,'2023-03-04 09:00:38','2023-03-04 09:00:38'),
	(519,'1','100519',NULL,'Pida',NULL,NULL,'Ksp Gadai, JL. Raya Wonocolo No 113 Sepanjang Taman KSP Taman Kab Sidoarjo Jatim','KABUPATEN SIDOARJO','JAWA TIMUR',NULL,'1',0,'2023-03-04 09:05:52','2023-03-04 09:05:52'),
	(520,'1','100520',NULL,'Rizqi Madhania',NULL,NULL,'Wisma tropodo Jl. ANggrek Blok M No 11 Waru Sidoarjo Jatim','KABUPATEN SIDOARJO','JAWA TIMUR',NULL,'1',0,'2023-03-04 09:13:17','2023-03-04 09:13:17'),
	(521,'1','100521',NULL,'Chintya Huda Wati',NULL,NULL,'Kempreng RT25/RW04 Kecamatan Taman Kab Sidoarjo Jatim','KABUPATEN SIDOARJO','JAWA TIMUR',NULL,'1',0,'2023-03-04 09:19:06','2023-03-04 09:19:06'),
	(522,'1','100522',NULL,'Ema Paramitha',NULL,NULL,'Jl. Jend Sudirman Gang Sebelah Lily Bridal No Ruma 105 Taman Sari Pangkal Pinang Bangka Belitung','KOTA PANGKAL PINANG','KEPULAUAN BANGKA BELITUNG',NULL,'1',0,'2023-03-04 09:24:12','2023-03-04 09:24:12'),
	(523,'1','100523',NULL,'Caprina Dwita Rahmawati',NULL,NULL,'Tandes Kidul gg Lebar No 1C Surabaya Tandes Surabaya Jatim','KOTA SURABAYA','JAWA TIMUR',NULL,'1',0,'2023-03-04 09:33:51','2023-03-04 09:33:51'),
	(524,'1','100524',NULL,'Yoni',NULL,NULL,'Sukamulya Kel Singajaya Garut Indonesia RT05/RW03 Sukamulya Singajaya Kab Garut Jabar','KOTA SURABAYA','JAWA TIMUR',NULL,'1',0,'2023-03-04 09:45:24','2023-03-04 09:45:24'),
	(525,'1','100525',NULL,'Michelle',NULL,NULL,'Apt. Palm Mansion Tower Jasmine No 708 Jakbar 11830','KOTA JAKARTA BARAT','DKI JAKARTA',NULL,'1',0,'2023-03-04 12:09:09','2023-03-04 12:09:09'),
	(526,'1','100526',NULL,'Gilang Rahma Windari',NULL,NULL,'Ds. Pojoksari RT/06 RW/01 Sukomoro Magetan Sukomoro Magetan Jatim','KABUPATEN MAGETAN','JAWA TIMUR',NULL,'1',0,'2023-03-06 09:19:42','2023-03-06 09:19:42'),
	(527,'1','100527',NULL,'Lukik Tegar P',NULL,NULL,'Jl. Suwandak Timur No 188 Kelurahan Jogotrunan Lumanjang Kab Jatim','KABUPATEN LUMAJANG','JAWA TIMUR',NULL,'1',0,'2023-03-06 09:28:17','2023-03-06 09:28:17'),
	(528,'1','100528',NULL,'Indira Irna Dianis Ivada',NULL,NULL,'Jl. Sumber Mulyo 5/5 RT05/RW4 Bubutan Surabaya JAtim','KOTA SURABAYA','JAWA TIMUR',NULL,'1',0,'2023-03-06 09:32:46','2023-03-06 09:32:46'),
	(529,'1','100529',NULL,'Mifta',NULL,NULL,'Jl. Tegal Mulyorejo Baru Bo 142 Mulyorejo Kota Surabaya Jatim','KOTA SURABAYA','JAWA TIMUR',NULL,'1',0,'2023-03-06 09:37:15','2023-03-06 09:37:15'),
	(530,'1','100530',NULL,'Tri Wahyuni',NULL,NULL,'Tunjungan Plaza 3 Lantai UG Jl. Basuki Rahmat No 8-12 Surabaya Tegalsari Jatim','KOTA SURABAYA','JAWA TIMUR',NULL,'1',0,'2023-03-06 09:42:17','2023-03-06 09:42:17'),
	(531,'1','100531',NULL,'Syifa(Rumah Ibu Pur)',NULL,NULL,'Jl. Melati Dalam 4 No 321 RT15/RW08 Pakis Malang Jatim Malang Jatim','KOTA MALANG','JAWA TIMUR',NULL,'1',0,'2023-03-06 09:46:14','2023-03-06 09:46:14'),
	(532,'1','100532',NULL,'DIana nuraini',NULL,NULL,'perum taman sidorejo krian sidoarjo blok n 02 krian sidoarjo jawa timur','KABUPATEN SIDOARJO','JAWA TIMUR',NULL,'1',0,'2023-03-06 09:52:49','2023-03-06 09:52:49'),
	(533,'1','100533',NULL,'ANGGUN KUSUMA',NULL,NULL,'PANEKAN RT 03 RW 02 DESA SUKOWIDI MAGETAN JAWA TIMUR','KABUPATEN MAGETAN','JAWA TIMUR',NULL,'1',0,'2023-03-06 15:26:31','2023-03-06 15:26:31'),
	(534,'1','100534',NULL,'Hari (Selep)',NULL,NULL,'Jl. Dusun Arjosari RT1/RW8 Payungrejo Kutorejo Kab Mojokerto Jatim','KABUPATEN MOJOKERTO','JAWA TIMUR',NULL,'1',0,'2023-03-07 09:53:46','2023-03-07 09:53:46'),
	(535,'1','100535',NULL,'Ernes',NULL,NULL,'Jl. Plemahan Besar No 52 Kelurahan Kedungdoro Tegalsari Surabaya Jatim','KOTA SURABAYA','JAWA TIMUR',NULL,'1',0,'2023-03-07 14:12:29','2023-03-07 14:12:29'),
	(536,'1','100536',NULL,'Ainul Muna',NULL,NULL,'Desa Harjowinangun, Dusun Dangi, RT 1/RW 3 (Ngganyong), Godong, Kab.Grobongan, Jawa Tengah','KABUPATEN GROBOGAN','JAWA TENGAH',NULL,'1',0,'2023-03-08 09:04:06','2023-03-08 09:04:06'),
	(537,'1','100537',NULL,'Ella',NULL,NULL,'Jalan Tanjungsari V NO.34 A, Sukomanunggal, Kota Surabaya','KOTA SURABAYA','JAWA TIMUR',NULL,'1',0,'2023-03-08 09:24:18','2023-03-08 09:24:18'),
	(538,'1','100538',NULL,'Kristin Ayu',NULL,NULL,'RT03/RW01 Dusun Selodono Karangpatihan Pulung Ponorogo Jatim','KABUPATEN PONOROGO','JAWA TIMUR',NULL,'1',0,'2023-03-08 09:39:11','2023-03-08 09:39:11'),
	(539,'1','100539',NULL,'Ica',NULL,NULL,'Ruko Greenlake CA-11','KOTA SURABAYA','JAWA TIMUR',NULL,'1',0,'2023-03-08 15:40:31','2023-03-08 15:40:31'),
	(540,'1','100540',NULL,'Fangga Adi Ponco',NULL,NULL,'RT32/RW09 Dusun Boto Putih Desa Sumber Pasir Kec. Pakis Malang Jatim','KABUPATEN MALANG','JAWA TIMUR',NULL,'1',0,'2023-03-09 08:45:37','2023-03-09 08:45:37'),
	(541,'1','100541',NULL,'Andi Natala',NULL,NULL,'K-Link Tegal- Gerai Ima, BTN jalan kangguru II No A 108 Trayeman Flayer Gerai Ima','KABUPATEN CILACAP','JAWA TENGAH',NULL,'1',0,'2023-03-09 09:06:49','2023-03-09 09:06:49'),
	(542,'1','100542',NULL,'Adi Saputra 0895-3046-2868',NULL,NULL,'perum greenlake lakarsantri, ruko greenlake CA-11 Surabaya','KOTA SURABAYA','JAWA TIMUR',NULL,'1',0,'2023-03-09 12:46:24','2023-03-09 12:46:24'),
	(543,'1','100543',NULL,'Irfina',NULL,NULL,'Jl. RA Kartini No 7 RT7/RW7 Sengon Tanjung Brebes Tanjung Jateng','KABUPATEN BREBES','JAWA TENGAH',NULL,'1',0,'2023-03-10 08:58:45','2023-03-10 08:58:45'),
	(544,'1','100544',NULL,'Erna',NULL,NULL,'Jl. Baratajaya 19 No 73 (Koperasi Lt2) Gubeng Surabaya Jatim','KOTA SURABAYA','JAWA TIMUR',NULL,'1',0,'2023-03-10 09:04:02','2023-03-10 09:04:02'),
	(545,'1','100545',NULL,'santi Dharmayanti',NULL,NULL,'Jalan RA Mustika 1 rt 01 rW 06 Tebel Timur Gedangan (Masjid Tengah Sawah), Gedangan. sidoarjo','KABUPATEN SIDOARJO','JAWA TIMUR',NULL,'1',0,'2023-03-10 10:58:58','2023-03-10 10:58:58'),
	(546,'1','100546',NULL,'Nadila Humaira',NULL,NULL,'Paradise Serpong City Cluster Cendana Jl Cendana 3 E3/20 Setu Tangerang Banten','KABUPATEN TANGERANG','BANTEN',NULL,'1',0,'2023-03-11 08:45:11','2023-03-11 08:45:11'),
	(547,'1','100547',NULL,'EMA SILVI',NULL,NULL,'WATERBOOM MULIA KLAMBU, JALAN PURWDADI KUDUS KM 18 KALMBU SRUNGGO KLAMBU GROBOGAN JAWATENGAH','KABUPATEN GROBOGAN','JAWA TENGAH',NULL,'1',0,'2023-03-11 08:47:19','2023-03-11 08:47:19'),
	(548,'1','100548',NULL,'Danang (P.Alif)',NULL,NULL,'Jl. Raya Surabaya Mojokerto RT4/RW2 Ds, Kramat Temenggung Tarik Kab Sidoarjo Jatim','KABUPATEN MOJOKERTO','JAWA TIMUR',NULL,'1',0,'2023-03-11 08:51:10','2023-03-11 08:51:10'),
	(549,'1','100549',NULL,'SABRINA ANGELIA',NULL,NULL,'JL WONOREJO IV NO 56 TEGALSARI SURABAYA JAWATIMUR','KOTA SURABAYA','JAWA TIMUR',NULL,'1',0,'2023-03-11 09:08:02','2023-03-11 09:08:02'),
	(550,'1','100550',NULL,'Quri Zahra Manika',NULL,NULL,'Jl. Ciseke Besar No 151 Cikeruh Jatinangor Sumedang Jawabarat','KABUPATEN SUMEDANG','JAWA BARAT',NULL,'1',0,'2023-03-13 08:47:19','2023-03-13 08:47:19'),
	(551,'1','100551',NULL,'Hana Khairul Vatwa',NULL,NULL,'Sempuh Buah Jaya Jl. Sumber Wadung Sempu Kab Banyuwangi Jatim','KABUPATEN BANYUWANGI','JAWA TIMUR',NULL,'1',0,'2023-03-13 08:51:43','2023-03-13 08:51:43'),
	(552,'1','100552',NULL,'Friska Putri Natalia',NULL,NULL,'Jl. Dukuh Kupang XVI No 36 Dukuh Pakis Surabaya JAtim','KOTA SURABAYA','JAWA TIMUR',NULL,'1',0,'2023-03-13 08:59:27','2023-03-13 08:59:27'),
	(553,'1','100553',NULL,'Farell / Bu Hartini',NULL,NULL,'Dusun Sawahan RT2/RW03 Wudi Sambeng Lamongan Jatim','KABUPATEN LAMONGAN','JAWA TIMUR',NULL,'1',0,'2023-03-13 09:04:09','2023-03-13 09:04:09'),
	(554,'1','100554',NULL,'Daiska Iga Khasanah',NULL,NULL,'Dusun Talun Ds Trutup RT08 RW 03 Plumpung Kab Tuban Jatim','KABUPATEN TUBAN','JAWA TIMUR',NULL,'1',0,'2023-03-14 08:50:00','2023-03-14 08:50:00'),
	(555,'1','100555',NULL,'Lukik Tegar Prakosa',NULL,NULL,'Primebiz Hotel Surabaya Jl. Gayung Kebonsari  NO 30 Gayungan Kamar 1205 Surabaya Jatim','KABUPATEN PACITAN','JAWA TIMUR',NULL,'1',0,'2023-03-14 08:55:22','2023-03-14 08:55:22'),
	(556,'1','100556',NULL,'Mila',NULL,NULL,'Depok Cimanggis Jl. Swadaya 1 No 17 RT03/RW11 Mekarsari Cimanggis Depok','KOTA DEPOK','JAWA BARAT',NULL,'1',0,'2023-03-14 09:15:33','2023-03-14 09:15:33'),
	(557,'1','100557',NULL,'RIZQI NOOR HIDAYAT','081333324570',NULL,'JL.PANDUK SELATAN GANG MASJID NO,3, PANJANG JIWO, TENGGILIS MEJOYO,','KOTA SURABAYA','JAWA TIMUR',NULL,'1',0,'2023-03-14 09:24:14','2023-03-14 16:15:51'),
	(558,'1','100558',NULL,'Adhelia Nanda R 081290681217',NULL,NULL,'Komplek Panorama Blok H No 9 Desa Cinanjung Kec Tanjung Sari Kab Sumedang Jabar','KABUPATEN SUMEDANG','JAWA BARAT',NULL,'1',0,'2023-03-14 11:26:43','2023-03-14 11:26:43'),
	(559,'1','100559',NULL,'Eka Kurniawati Fajriyah',NULL,NULL,'Green Garden, JL. Doktor Blok D4 No 25 Dahanrejo Kebonmas Gresik Jatim','KABUPATEN GRESIK','JAWA TIMUR',NULL,'1',0,'2023-03-15 08:46:42','2023-03-15 08:46:42'),
	(560,'1','100560',NULL,'Yusan Ovie Latif',NULL,NULL,'Keloran RT 02/RW01 Kec Selogiri Kab Wonogiri Selogiri Jateng','KABUPATEN WONOGIRI','JAWA TENGAH',NULL,'1',0,'2023-03-15 08:55:36','2023-03-15 08:55:36'),
	(561,'1','100561',NULL,'Arga Yudistira',NULL,NULL,'Asrama Yonkav 8 Tank Beji Kab Pasuruan Jatim','KABUPATEN PASURUAN','JAWA TIMUR',NULL,'1',0,'2023-03-15 11:42:13','2023-03-15 11:42:13'),
	(562,'1','100562',NULL,'Alicia',NULL,NULL,'Jasmine Apartemen Tower Aurora Jl. The Mansion Pademangan Timur Pademangan Jakarta Utara DKI Jakarta','KOTA JAKARTA UTARA','DKI JAKARTA',NULL,'1',0,'2023-03-15 13:31:25','2023-03-15 13:31:25'),
	(563,'1','100563',NULL,'Cak Nur',NULL,NULL,'Jl. Pasar Mutiara 3 Ruko 3 Driyorejo Gresik Jatim','KABUPATEN GRESIK','JAWA TIMUR',NULL,'1',0,'2023-03-16 08:37:31','2023-03-16 08:37:31'),
	(564,'1','100564',NULL,'Desy Wulansari',NULL,NULL,'Jl. Kolonel Sugiono Wedoro Sukun Gg ANggrek No 169/170 RT3/RW3 Waru Sidoarjo Jatim','KABUPATEN SIDOARJO','JAWA TIMUR',NULL,'1',0,'2023-03-16 08:43:46','2023-03-16 08:43:46'),
	(566,'1','100566',NULL,'Ananda Dian',NULL,NULL,'Jl. Sambikerep Gg III No 80 Sambikerep RT05/RW04 Sambikerep Surabaya Jatim','KOTA SURABAYA','JAWA TIMUR',NULL,'1',0,'2023-03-16 11:02:36','2023-03-16 11:02:36'),
	(567,'1','100567',NULL,'zahra','085292164110',NULL,'ds brati rt 01 rw 02 kec kayen kab pati, jawa tengah dk dukuan','KABUPATEN PATI','JAWA TENGAH',NULL,'1',0,'2023-03-16 11:24:18','2023-03-16 11:24:18'),
	(568,'1','100568',NULL,'Syeba',NULL,NULL,'Royal Paka Residence Blok B 36 Gununganyar Kota Surabaya Jatim','KOTA SURABAYA','JAWA TIMUR',NULL,'1',0,'2023-03-17 08:47:17','2023-03-17 08:47:17'),
	(569,'1','100569',NULL,'Anggi',NULL,NULL,'JL. Ikan Tombro Barat No 48 Lowokwaru Malang Jatim','KOTA MALANG','JAWA TIMUR',NULL,'1',0,'2023-03-17 09:16:26','2023-03-17 09:16:26'),
	(570,'1','100570',NULL,'Kurnia Ningsih',NULL,NULL,'Kalidawir Tanggulangin Jl. Kh Basori RT7/Rw2 Kalidawir Tanggulangin Sidoarjo Jatim','KABUPATEN SIDOARJO','JAWA TIMUR',NULL,'1',0,'2023-03-17 09:38:34','2023-03-17 09:38:34'),
	(571,'1','100571',NULL,'Ulil Amri',NULL,NULL,'Desa Lambangan Dusun Kemulan Rt 5/RW4 Wonoayu Sidoarjo','KOTA SURABAYA','JAWA TIMUR',NULL,'1',0,'2023-03-17 09:54:10','2023-03-17 09:54:10'),
	(572,'1','100572',NULL,'Refinda Nadia Alfita',NULL,NULL,'Jl. Putra Agung Gang 3/No 26 Tambaksari Kota Surabaya Jatim','KOTA SURABAYA','JAWA TIMUR',NULL,'1',0,'2023-03-18 09:02:10','2023-03-18 09:02:10'),
	(573,'1','100573',NULL,'Kiki',NULL,NULL,'Jl. KH Wahid Hasyim NO 17 Kel Gunung Sekar Kec Sampang Jatim','KABUPATEN SAMPANG','JAWA TIMUR',NULL,'1',0,'2023-03-18 09:22:24','2023-03-18 09:22:24'),
	(574,'1','100574',NULL,'Rio Antika',NULL,NULL,'Perum Gunungsari Indah Blok AZ No 15 Surabaya Karangpilang Surabaya Jawatimur','KOTA SURABAYA','JAWA TIMUR',NULL,'1',0,'2023-03-18 11:50:18','2023-03-18 11:50:18'),
	(575,'1','100575',NULL,'Okhi',NULL,NULL,'JL. Panglima Sudirman No 45A Girimoyo Karangploso Malang Jatim','KABUPATEN MALANG','JAWA TIMUR',NULL,'1',0,'2023-03-20 09:07:09','2023-03-20 09:07:09'),
	(576,'1','100576',NULL,'MASAYU MAYA','085330230009',NULL,'Taman Pondok Indah KX-19, Belakang KFC Wiyung - Surabaya','KOTA SURABAYA','JAWA TIMUR',NULL,'1',0,'2023-03-20 10:37:55','2023-03-20 10:37:55'),
	(577,'1','100577',NULL,'ika','085785709862',NULL,'Jalan Kendung Jl kendung gg 1i RT 01 Rw 03 (cat kuning nomor 2)','KOTA SURABAYA','JAWA TIMUR',NULL,'1',0,'2023-03-20 12:24:13','2023-03-20 12:24:13'),
	(578,'1','100578',NULL,'naomy netty','081378660691',NULL,'Perumahan Puri mas 1 blok a1 no8 batam kota batam kepulauan riau','KOTA PEKANBARU','RIAU',NULL,'1',0,'2023-03-21 08:50:49','2023-03-21 08:50:49'),
	(579,'1','100579',NULL,'Yaya Rahman',NULL,NULL,'Rumah Jl. Kh Abd Addari No 259 Batin Tikal Taman Sari Taman Sari Pinang Bangka Belitung','KOTA PANGKAL PINANG','KEPULAUAN BANGKA BELITUNG',NULL,'1',0,'2023-03-21 08:55:23','2023-03-21 08:55:23'),
	(580,'1','100580',NULL,'Hellolilolens','Penerima 082140792806 -- Pemesan 081335588710',NULL,'Villa Kalijudan Indah Blok L No 34 Surabaya','KOTA SURABAYA','JAWA TIMUR',NULL,'1',0,'2023-03-21 09:30:35','2023-03-21 09:30:35'),
	(581,'2','100581',NULL,'Julian PN',NULL,NULL,'Bratang Gede 3C No  Wonokromo Kota Surabaya Jatim','KOTA SURABAYA','JAWA TIMUR',NULL,'1',0,'2023-03-21 14:21:29','2023-03-21 14:21:29'),
	(582,'1','100582',NULL,'Fitria Aprilliyanti',NULL,NULL,'Perum Bumi Este Muktisari Tahap 3 Blok BBJ 8 RT02/RW24 Keranjingan Sumbersari Jember Jatim','KABUPATEN JEMBER','JAWA TIMUR',NULL,'1',0,'2023-03-21 14:25:44','2023-03-21 14:25:44'),
	(583,'1','100583',NULL,'MASITA','087863643881',NULL,'TUWOWO REJO GG 7 NO 14 KENJERAN, SURABAYA, JAWA TIMUR','KOTA SURABAYA','JAWA TIMUR',NULL,'1',0,'2023-03-24 08:43:04','2023-03-24 08:43:04'),
	(584,'1','100584',NULL,'Rikapuspairawan',NULL,NULL,'Jl. Teratai Gg Celebes No 61 RT65 Karang Tarakan Barat Kota Tarakan Kaliamnatan Utara','KOTA TARAKAN','KALIMANTAN UTARA',NULL,'1',0,'2023-03-24 09:16:43','2023-03-24 09:16:43'),
	(585,'1','100585',NULL,'Taufan Surya R',NULL,NULL,'Jl. Tamrin RT3/RW3 Deyeng Ringinrejo Kediri Jatim','KABUPATEN KEDIRI','JAWA TIMUR',NULL,'1',0,'2023-03-24 09:20:06','2023-03-24 09:20:06'),
	(586,'1','100586',NULL,'Anita Desi Nur Giyanti',NULL,NULL,'Desa Keboromo RT6/RW2 Tayu Kab Pati Jateng','KABUPATEN PATI','JAWA TENGAH',NULL,'1',0,'2023-03-24 09:26:31','2023-03-24 09:26:31'),
	(587,'1','100587',NULL,'NAYLA/NURII','-',NULL,'JALAN RAYA KENDUNG RT 01/RW 03, GG 1F NO.09, BENOWO, SURABAYA','KOTA SURABAYA','JAWA TIMUR',NULL,'1',0,'2023-03-24 09:37:16','2023-03-24 09:37:16'),
	(588,'1','100588',NULL,'IDA NURYANTI/PAK SURYA','-',NULL,'PUNDUNGSARI,RT5/RW2, SEMIN(CESA STEAK), KAB.GUNUNG KIDUL, DI YOGYAKARTA','KABUPATEN GUNUNG KIDUL','DI YOGYAKARTA',NULL,'1',0,'2023-03-24 09:48:07','2023-03-24 09:48:07'),
	(589,'1','100589',NULL,'SAYU INDAH','-',NULL,'JALAN WIGUNA SELATAN III NO.10, GUNUNGANYAR TAMBAK, SURABAYA','KOTA SURABAYA','JAWA TIMUR',NULL,'1',0,'2023-03-24 10:02:48','2023-03-24 10:02:48'),
	(590,'1','100590',NULL,'BINTI','-',NULL,'RUKO SAN ANTONIO, JALAN KALISARI UTARA I BLOK N1, NO.111, KALI SARI, MULYOREJO(JMAX), SURABAYA','KOTA SURABAYA','JAWA TIMUR',NULL,'1',0,'2023-03-24 10:07:53','2023-03-24 10:07:53'),
	(591,'1','100591',NULL,'NURFANISA','-',NULL,'GINDI, RT018/RW007, KEL.JATIWANGI, KEC. ASAKOTA, KOTA BIMA, NUSA TENGGARA BARAT','KOTA BIMA','NUSA TENGGARA BARAT',NULL,'1',0,'2023-03-24 10:14:25','2023-03-24 10:14:25'),
	(593,'1','100593',NULL,'NI KADEK AYU WINDA SANTI','-',NULL,'JALAN RAYA BASANGKASA GANG RATU NO.5, SEMINYAK,KUTA, KAB.BADUNG, BALI','KABUPATEN BADUNG','BALI',NULL,'1',0,'2023-03-25 09:25:45','2023-03-25 09:25:45'),
	(594,'1','100594',NULL,'PIPIN IKA','-',NULL,'DUSUN BOGEM SELATAN RT 014/RW 003, GURAH, KAB.KEDIRI','KABUPATEN KEDIRI','JAWA TIMUR',NULL,'1',0,'2023-03-25 09:32:32','2023-03-25 09:32:32'),
	(595,'1','100595',NULL,'VITABEETI','-',NULL,'Ruko Surya Inti Permata Ii, Jalan Mayjen Hr. Muhammad,  (no177D/SKINDA CLINIC) Pradahkalikendal, Dukuh Pakis','KOTA SURABAYA','JAWA TIMUR',NULL,'1',0,'2023-03-25 09:37:38','2023-03-25 09:37:38'),
	(596,'1','100596',NULL,'KARINA MANDASARI','-',NULL,'DSN. SUKOREJO KEMADUH, JALAN KERTOSONO - LENGKONG RT 002/RW 001, KEMADUH, BARON, NGANJUK','KABUPATEN NGANJUK','JAWA TIMUR',NULL,'1',0,'2023-03-25 09:44:12','2023-03-25 09:44:12'),
	(598,'1','100598',NULL,'FIFI NUR ULWIYAH','-',NULL,'JLN. JEMUR WONOSARI GANG LEBAR NO.108, WONOCOLO, KOTA SURABAYA','KOTA SURABAYA','JAWA TIMUR',NULL,'1',0,'2023-03-25 10:07:33','2023-03-25 10:07:33'),
	(601,'1','100601',NULL,'ARZA RIZKY','-',NULL,'JL. DUKUH GEMOL GANG LEBAR 1B NO.42, RT 05/RW 03, JAJAR TUNGGAL, WIYUNG, SURABAYA','KOTA SURABAYA','JAWA TIMUR',NULL,'1',0,'2023-03-25 10:35:00','2023-03-25 10:35:00'),
	(602,'1','100602',NULL,'VANNY SUMARGO','-',NULL,'VILLA SENTRA RAYA SELATAN A2 NO 62, LAKARSANTRI, SAMBIKEREP, SURABAYA','KOTA SURABAYA','JAWA TIMUR',NULL,'1',0,'2023-03-25 12:34:19','2023-03-25 12:34:19'),
	(603,'1','100603',NULL,'Ajeng',NULL,NULL,'Dsn, Sumbertimo RT4/RW1 Desa Arjosari Kalipare Malang Jatim','KABUPATEN MALANG','JAWA TIMUR',NULL,'1',0,'2023-03-27 08:39:46','2023-03-27 08:39:46'),
	(605,'1','100605',NULL,'Viska',NULL,NULL,'Gg Nagaloka Sidakarya Denpasar Selatan Bali','KOTA DENPASAR','BALI',NULL,'1',0,'2023-03-27 08:46:46','2023-03-27 08:46:46'),
	(607,'1','100607',NULL,'Mohammad AL Imron',NULL,NULL,'Ponpes Assalam Srigunung Jl. Palembang Lilin Kab Musi Banyuasin Sumatera Selatan','KABUPATEN MUSI BANYUASIN','SUMATERA SELATAN',NULL,'1',0,'2023-03-27 08:57:32','2023-03-27 08:57:32'),
	(609,'1','100609',NULL,'anik maria siska','081515447849',NULL,'JL sawunggaling jemundo no 16 sebelah SDN Jemundo 2 taman ada gang masuk pintu pertama','KABUPATEN SIDOARJO','JAWA TIMUR',NULL,'1',0,'2023-03-27 09:02:04','2023-03-27 09:02:04'),
	(610,'1','100610',NULL,'IRINE','-',NULL,'MOH KAHFI 1, JL.KAKAS PERUM KAFI TERRACE JAGAKARSA BLOK F4, KOTA JAKARTA SELATAN','KOTA JAKARTA SELATAN','DKI JAKARTA',NULL,'1',0,'2023-03-27 09:03:02','2023-03-27 09:03:02'),
	(611,'1','100611',NULL,'HARTOYO','-',NULL,'JL. MANDALA 496B, RT 17/ RW 05, SEMAMBUNG, GEDANGAN, KAB.SIDOARJO','KABUPATEN SIDOARJO','JAWA TIMUR',NULL,'1',0,'2023-03-27 09:14:18','2023-03-27 09:14:18'),
	(612,'1','100612',NULL,'WINARTY','-',NULL,'JL. HJ SYUKUR VII, RT 20/RW 9, SEDATI GEDE, SEDATI, KAB.SIDOARJO','KABUPATEN SIDOARJO','JAWA TIMUR',NULL,'1',0,'2023-03-27 09:17:40','2023-03-27 09:17:40'),
	(613,'1','100613',NULL,'Atha',NULL,NULL,'Bukit Golft Utama Lakasantri Jalan Bukit Golft Utaman Blok F1 No 2 Sambikerep Surabay Jatim','KOTA SURABAYA','JAWA TIMUR',NULL,'1',0,'2023-03-27 09:20:01','2023-03-27 09:20:01'),
	(616,'1','100616',NULL,'Jaya OY','081330499000',NULL,'Perum Podok Tjandra Cluster Opal No 56 Jl. Delima Selatan V Tambaksari Tambakrejo Waru Kab Sidoarjo','KABUPATEN SIDOARJO','JAWA TIMUR',NULL,'1',0,'2023-03-27 09:31:40','2023-03-27 09:31:40'),
	(618,'1','100618',NULL,'GITA ROBIATUL ADAWIAH','-',NULL,'HOTEL IBIS STYLES, JALAN JEMURSARI RAYA NO.110-112, TENGGILIS MEJOYO, KOTA SURABAYA','KOTA SURABAYA','JAWA TIMUR',NULL,'1',0,'2023-03-28 08:48:33','2023-03-28 08:48:33'),
	(619,'1','100619',NULL,'Ara','081217130309',NULL,'sukun pondok indah blok v no 7 rumah tembok hijau, rumah kedua sebelah kanan TK pelita hati, suku , malang, jawa timur','KOTA MALANG','JAWA TIMUR',NULL,'1',0,'2023-03-28 09:01:20','2023-03-28 09:01:20'),
	(620,'1','100620',NULL,'Diyah Nofa','081296580820',NULL,'Jl. Sedati Agung 1 RT05/RW02 Sedati Juanda Sidoarjo','KABUPATEN SIDOARJO','JAWA TIMUR',NULL,'1',0,'2023-03-28 09:33:35','2023-03-28 09:33:35'),
	(621,'1','100621',NULL,'Sugar Dream','082245580404 - 087854623995',NULL,'Bank UB Jl. Manyar Kertoarjo No 50 Surabaya','KOTA SURABAYA','JAWA TIMUR',NULL,'1',0,'2023-03-28 13:10:47','2023-03-28 13:10:47'),
	(623,'1','100623',NULL,'ARKINSA PERASTIWI','-',NULL,'JALAN SRIMINULYO RT 15/RW 5, KEL. SUKOSARI. KARTOHARJO. MADIUN','KABUPATEN MADIUN','JAWA TIMUR',NULL,'1',0,'2023-03-28 15:17:55','2023-03-28 15:17:55'),
	(624,'1','100624',NULL,'CandraMaya',NULL,NULL,'Jl. Sunan Giri Gang 9 Sekayu Ds, Gandukepuh Sukorejo Ponorogo Jatim','KABUPATEN PONOROGO','JAWA TIMUR',NULL,'1',0,'2023-03-29 08:58:20','2023-03-29 08:58:20'),
	(625,'1','100625',NULL,'Nadien',NULL,NULL,'Apartem, The Mansion Jasmine Tower Bellavista Jl. Tremebsi At Kampung Dukuh Pademangan (JB16 F) Jakarta Utara DKI Jakarta','KOTA JAKARTA UTARA','DKI JAKARTA',NULL,'1',0,'2023-03-29 09:14:36','2023-03-29 09:14:36'),
	(626,'1','100626',NULL,'Safira Nur Afrida',NULL,NULL,'Dsn Panjen, Jl, Raden Panji RT3/RW1 Ds Jenggolo Jenu Kab. Tuban Jawa timur','KABUPATEN TUBAN','JAWA TIMUR',NULL,'1',0,'2023-03-29 09:30:03','2023-03-29 09:30:03'),
	(627,'1','100627',NULL,'FLORA BLESS','-',NULL,'PERUMAHAN ARYANA KARAWACI CLUSTER FLORA ATTIC , JL RAYA SUKA BAKTI BLOK E6 NO.19, CURUG, KAB.TANGERANG, BANTEN','KABUPATEN TANGERANG','BANTEN',NULL,'1',0,'2023-03-29 09:41:29','2023-03-29 09:41:29'),
	(628,'1','100628',NULL,'PURWANINGSIH','-',NULL,'DS. GODO RT 2/RW 2, WINONG, KAB.PATI, JAWA TENGAH','KABUPATEN PATI','JAWA TENGAH',NULL,'1',0,'2023-03-29 11:29:27','2023-03-29 11:29:27'),
	(629,'1','100629',NULL,'Yoga Irfan/Yoga Tejo /P Sob',NULL,NULL,'Jl. Dusun Kedungbuntung RT3/RW1 Desa Kedungwilut Bandung bandung tulungagung Jatim','KABUPATEN TULUNGAGUNG','JAWA TIMUR',NULL,'1',0,'2023-03-30 09:38:58','2023-03-30 09:38:58'),
	(630,'1','100630',NULL,'elda habita avianti','0895399693692',NULL,'Desa kebon, DSn kedung pawon Rt 01 Rw 01, kec paron, Kab ngawi PARON NGAWI JAWA TIMUR','KABUPATEN NGAWI','JAWA TIMUR',NULL,'1',0,'2023-03-30 09:42:43','2023-03-30 09:42:43'),
	(631,'1','100631',NULL,'Yonattan',NULL,NULL,'Perum Marison Permai Blok F3 Pilangrejo Kecamatan Wung Madiun Kab Madiun Jatim','KABUPATEN MADIUN','JAWA TIMUR',NULL,'1',0,'2023-03-30 09:43:45','2023-03-30 09:43:45'),
	(632,'1','100632',NULL,'SHERIL BUNGA','-',NULL,'JL.Kebonsari, Gang Sd Inpres No.8, RT.1/RW.2, Kebonsari, Jambangan (Pagar putih), KOTA SURABAYA','KOTA SURABAYA','JAWA TIMUR',NULL,'1',0,'2023-03-30 13:36:32','2023-03-30 13:36:32'),
	(633,'1','100633',NULL,'Viko Verlia',NULL,NULL,'Jl. Imam Bonjol No 394 BR Abian Timbul Pemecutan Kelod Denpasar Barat Kota Denpasar Bali','KOTA DENPASAR','BALI',NULL,'1',0,'2023-03-31 08:57:40','2023-03-31 08:57:40'),
	(634,'1','100634',NULL,'Alfina Cahya Meytasari',NULL,NULL,'Kos Putri Maundry 3A, Jl. Maundri 3A Blok Tingkat Taman Banguntapan Bantul Yogyakarta','KOTA YOGYAKARTA','DI YOGYAKARTA',NULL,'1',0,'2023-03-31 09:04:28','2023-03-31 09:04:28'),
	(635,'1','100635',NULL,'Putri Fatimah Rachmawati',NULL,NULL,'Jl. Menur III No 9 Menur Pumpungan Sukolilo Surabay Jatim','KOTA SURABAYA','JAWA TIMUR',NULL,'1',0,'2023-04-03 08:35:21','2023-04-03 08:35:21'),
	(636,'1','100636',NULL,'Evi Fiddiana Wati',NULL,NULL,'Ds. Sumberingin Kidul Jalan, Sumberingin Kidul Ngunut Tulungagung Jatim','KABUPATEN TULUNGAGUNG','JAWA TIMUR',NULL,'1',0,'2023-04-03 08:41:01','2023-04-03 08:41:01'),
	(637,'1','100637',NULL,'Dina Fitri',NULL,NULL,'TPU Tegal Binangun Baben Karanganom Jl. Prenjak Klaten Utara Klaten Jateng','KABUPATEN KLATEN','JAWA TENGAH',NULL,'1',0,'2023-04-03 08:46:21','2023-04-03 08:46:21'),
	(638,'1','100638',NULL,'Sarah Nalurita Wijayanti',NULL,NULL,'Jl. Sukowati 41A Jetis Ponorogo Jatim','KABUPATEN PONOROGO','JAWA TIMUR',NULL,'1',0,'2023-04-03 08:50:34','2023-04-03 08:50:34'),
	(639,'1','100639',NULL,'BARBARA SALON','0822334064958',NULL,'barbara salon, jala rajekwesi, tapelan lor, tanjung harjo, kabupaten bojonegoro jawa timur','KABUPATEN BOJONEGORO','JAWA TIMUR',NULL,'1',0,'2023-04-03 08:52:48','2023-04-03 08:52:48'),
	(640,'1','100640',NULL,'Ifa Nur Habibah',NULL,NULL,'Jl. Ambuku Klompangan Ajung RT3/RW1 Klompangan Ajung Jember Jatim','KABUPATEN JEMBER','JAWA TIMUR',NULL,'1',0,'2023-04-03 08:55:21','2023-04-03 08:55:21'),
	(641,'1','100641',NULL,'ALFIA TOMI','083147835324',NULL,'KAMONENG DUSUN DELMAN KECAMATAN TRAGAH KABUPATEN BANGKALAN','KABUPATEN BANGKALAN','JAWA TIMUR',NULL,'1',0,'2023-04-03 08:57:42','2023-04-03 08:57:42'),
	(642,'1','100642',NULL,'Dewi Febriana Susanti',NULL,NULL,'Dsn Asem Manis 2 Desa Larangan Tokol Tlanakan Pamekasan Jatim','KABUPATEN PAMEKASAN','JAWA TIMUR',NULL,'1',0,'2023-04-03 09:09:11','2023-04-03 09:09:11'),
	(643,'1','100643',NULL,'ROSII','085706752984',NULL,'SUKOREJO DESA KALIREJO DUSUN LAWATA RT 09 RW 10','KABUPATEN PASURUAN','JAWA TIMUR',NULL,'1',0,'2023-04-03 09:11:12','2023-04-03 09:11:12'),
	(644,'1','100644',NULL,'Sofiya Maghfiroh',NULL,NULL,'Jl. Soekarno Hatta Bakso Kota Cakman Griya Santa Ruko Permata No 28 Lowokwaru Malang Jatim','KABUPATEN MALANG','JAWA TIMUR',NULL,'1',0,'2023-04-03 09:14:06','2023-04-03 09:14:06'),
	(645,'1','100645',NULL,'Vina Lindawati',NULL,NULL,'Dusun Turi Desa Janti RT33/RW14 Wates Kab Kediri Jatim','KABUPATEN KEDIRI','JAWA TIMUR',NULL,'1',0,'2023-04-03 09:18:26','2023-04-03 09:18:26'),
	(646,'1','100646',NULL,'Reni',NULL,NULL,'Komplek Permata Harbaindo Blok H13 No4 Pampangan Lubuk Begalung Kota Padang sumatera Barat','KOTA PADANG','SUMATERA BARAT',NULL,'1',0,'2023-04-03 09:23:11','2023-04-03 09:23:11'),
	(647,'1','100647',NULL,'Indra',NULL,NULL,'Jl. raya Kosambi Timur RT1/RW6 KOsambi Barat Tangerang Banten','KABUPATEN TANGERANG','BANTEN',NULL,'1',0,'2023-04-03 09:28:40','2023-04-03 09:28:40'),
	(648,'1','100648',NULL,'Risa Putri Saqira',NULL,NULL,'Jl. Balap Sepeda 1A RT8/RW6 Kontrakan NO 7 Rawamangun Pulo Gadung KOta Jakarta Timur DKI Jakarta','KOTA JAKARTA TIMUR','DKI JAKARTA',NULL,'1',0,'2023-04-03 09:36:04','2023-04-03 09:36:04'),
	(649,'1','100649',NULL,'Rivan Sofwan Budiman',NULL,NULL,'Jl. Smpn 1 Cilimus Blok Kliwon Desa Bojong No 87 RT27/RW9 Bojong Cilimus Kuningan Jabar','KABUPATEN KUNINGAN','JAWA BARAT',NULL,'1',0,'2023-04-03 09:41:24','2023-04-03 09:41:24'),
	(650,'1','100650',NULL,'Adindha Yanthi',NULL,NULL,'Graha Pondok Kacang Blok D Nomor 1 Pondok Aren Kota tangerang Selatan Banten','KOTA TANGERANG','BANTEN',NULL,'1',0,'2023-04-03 09:49:34','2023-04-03 09:49:34'),
	(651,'1','100651',NULL,'Asri Kartika Sari',NULL,NULL,'Musholla Al-Islah Jl. Darussalam Utara I No 140 RT4/RW5 Batusari Batuceper Kota tangerang Banten','KOTA TANGERANG','BANTEN',NULL,'1',0,'2023-04-03 09:54:10','2023-04-03 09:54:10'),
	(652,'1','100652',NULL,'Slamet',NULL,NULL,'Desa Legundi Blok Kapuran RT16/RW5 Legundi Bantaran Rumah Slamet Bantaran Kab Probolinggo Jatim','KOTA PROBOLINGGO','JAWA TIMUR',NULL,'1',0,'2023-04-03 10:04:21','2023-04-03 10:04:21'),
	(653,'1','100653',NULL,'Tri Suryani',NULL,NULL,'Jl. Bali Cliff Gg Kausan No 1 Pondok Tuta No 103 Ungasan Kuta Selatan Bandung Bali','KABUPATEN BADUNG','BALI',NULL,'1',0,'2023-04-03 10:15:23','2023-04-03 10:15:23'),
	(654,'1','100654',NULL,'Andriana Putri',NULL,NULL,'Kontrakan H Salim Jl. Sawi I No 24 RT3/RW5 Pondok Cabe Ilir Pamulang Tangerang Selatan Banten','KABUPATEN TANGERANG','BANTEN',NULL,'1',0,'2023-04-03 10:25:38','2023-04-03 10:25:38'),
	(655,'1','100655',NULL,'Debby Purnamadewi',NULL,NULL,'Jl. Perak Timur No 216 PT Temas Perak Timur Pabean Cantikan Kota surabaya Jatim','KOTA SURABAYA','JAWA TIMUR',NULL,'1',0,'2023-04-03 10:35:21','2023-04-03 10:35:21'),
	(656,'1','100656',NULL,'Yolanda Mires','082234112713',NULL,'perum forest mansion cluster blossom hill b8 surabaya','KOTA SURABAYA','JAWA TIMUR',NULL,'1',0,'2023-04-03 11:30:50','2023-04-03 11:30:50'),
	(657,'1','100657',NULL,'Salsabilaizati',NULL,NULL,'Toko Pondok Mainan Perumahan Pondok Candra Ruko Rambutan Block D 10C Waru Sidoarjo Jatim','KABUPATEN SIDOARJO','JAWA TIMUR',NULL,'1',0,'2023-04-03 12:39:10','2023-04-03 12:39:10'),
	(658,'1','100658',NULL,'Natalia Diandra',NULL,NULL,'Jl. Ikan Piranha Atas XII (No 214D) Lowokwaru Kota Malang Jatim','KOTA MALANG','JAWA TIMUR',NULL,'1',0,'2023-04-04 08:59:14','2023-04-04 08:59:14'),
	(659,'1','100659',NULL,'Endro. Sugiarto',NULL,NULL,'Jl. Raya Panakowan RT1/RW1 Panokawan Krian Kab Sidoarjo Jatim','KABUPATEN SIDOARJO','JAWA TIMUR',NULL,'1',0,'2023-04-04 09:03:23','2023-04-04 09:03:23'),
	(660,'1','100660',NULL,'Noky',NULL,NULL,'Jl. Desa Panokawan RT1/RW1 Panokawan Krian Krian Sidoarjo Jatim','KABUPATEN SIDOARJO','JAWA TIMUR',NULL,'1',0,'2023-04-04 09:08:22','2023-04-04 09:08:22'),
	(661,'1','100661',NULL,'Nafizah',NULL,NULL,'Jl. Manukan Kasman No 54 Manukan Kulon Tandes RT3/RW10 Tandes Surabaya Jatim','KOTA SURABAYA','JAWA TIMUR',NULL,'1',0,'2023-04-04 09:21:59','2023-04-04 09:21:59'),
	(662,'1','100662',NULL,'Yunitaariany',NULL,NULL,'Jl. Bratang Gede VI C No 18 A Wonokromo Kota Surabaya Jatim','KOTA SURABAYA','JAWA TIMUR',NULL,'1',0,'2023-04-04 09:51:00','2023-04-04 09:51:00'),
	(663,'1','100663',NULL,'Khlifa Intan',NULL,NULL,'Dukuh Karanglo RT01/RW01 Desa Ngasinan Jetis Ponorogo Jetis Ponorogo Jatim','KABUPATEN PONOROGO','JAWA TIMUR',NULL,'1',0,'2023-04-04 09:58:13','2023-04-04 09:58:13'),
	(664,'1','100664',NULL,'Angel',NULL,NULL,'Jl. Magersari III RT 3/RW1 Magersari Sidoarjo Jatim','KABUPATEN SIDOARJO','JAWA TIMUR',NULL,'1',0,'2023-04-04 10:20:59','2023-04-04 10:20:59'),
	(665,'1','100665',NULL,'Tan Mei Bin',NULL,NULL,'Lebak Rejo Utara 3 No 22 Tambaksari Kota Surabaya Jatim','KOTA SURABAYA','JAWA TIMUR',NULL,'1',0,'2023-04-04 10:23:55','2023-04-04 10:23:55'),
	(666,'1','100666',NULL,'Nisa Faradisa',NULL,NULL,'Jl. Haji Jusin RT 07/01 No 17 Gang Haji Muchtar Ciracas Kota Jakarta Timur DKI Jakarta','KOTA JAKARTA TIMUR','DKI JAKARTA',NULL,'1',0,'2023-04-04 10:28:21','2023-04-04 10:28:21'),
	(667,'1','100667',NULL,'Feby Cynthia',NULL,NULL,'Toko Plastik Bca Food Packaging Jl. Ruko Mutiara C3 No 17 Cengkareng Timur Kota Jakarta DKI Jkarta','KOTA JAKARTA BARAT','DKI JAKARTA',NULL,'1',0,'2023-04-04 11:22:09','2023-04-04 11:22:09'),
	(668,'1','100668',NULL,'KRISTI WULANDARI','-',NULL,'Jln nakula rt 02 rw 01 desa pijeran kecamatan siman kabupaten ponorogo, SIMAN, KAB. PONOROGO','KABUPATEN PONOROGO','JAWA TIMUR',NULL,'1',0,'2023-04-05 08:41:44','2023-04-05 08:41:44'),
	(670,'1','100670',NULL,'DR, DENY SUSANTO','-',NULL,'Dharmahusada indah I no.49 blok M-58 (LimaJari reflexology), KOTA SURABAYA, MULYOREJO','KOTA SURABAYA','JAWA TIMUR',NULL,'1',0,'2023-04-05 08:51:16','2023-04-05 08:51:16'),
	(673,'1','100673',NULL,'NOVADRT','-',NULL,'Dusun karangkepuh rt 04 rw 02 desa karangjati pandaan.Tanya rumah nova/ibu rina jual keripik tempe), KAB. PASURUAN, PANDAAN','KABUPATEN PASURUAN','JAWA TIMUR',NULL,'1',0,'2023-04-05 09:00:53','2023-04-05 09:00:53'),
	(675,'1','100675',NULL,'TRISNA KUMALA SARI',NULL,NULL,'Terung Kulon RT.07 RW.01 Depan Balai Desa, KAB. SIDOARJO, KRIAN','KABUPATEN SIDOARJO','JAWA TIMUR',NULL,'1',0,'2023-04-05 09:09:53','2023-04-05 09:09:53'),
	(676,'1','100676',NULL,'ZARINI ALEXA','-',NULL,'Jalan Perdana No. 76, Entalsewu, Buduran (Rumah ketua rt 09), KAB. SIDOARJO, BUDURAN','KABUPATEN SIDOARJO','JAWA TIMUR',NULL,'1',0,'2023-04-05 09:12:25','2023-04-05 09:12:25'),
	(678,'1','100678',NULL,'AGUNG','-',NULL,'Jl simo jawar va4-a5 no 22, KOTA SURABAYA, SUKOMANUNGGAL','KOTA SURABAYA','JAWA TIMUR',NULL,'1',0,'2023-04-06 09:05:51','2023-04-06 09:05:51'),
	(679,'1','100679',NULL,'RATNA OLIVIA','-',NULL,'Jl kahuripan 1 no 275 rt 17 rw 05 (Gang masjid 1), KAB. SIDOARJO, SIDOARJO','KABUPATEN SIDOARJO','JAWA TIMUR',NULL,'1',0,'2023-04-06 09:15:51','2023-04-06 09:15:51'),
	(680,'1','100680',NULL,'PUTRI PERMATASARI','-',NULL,'Kavling Oma Anggaswangi No. A9 (Pagar Hitam Emas), KAB. SIDOARJO, SUKODONO','KABUPATEN SIDOARJO','JAWA TIMUR',NULL,'1',0,'2023-04-06 09:24:58','2023-04-06 09:24:58'),
	(681,'1','100681',NULL,'ATIK SURYANI','-',NULL,'Jalan Apel No.23, RT.3/RW.4, Ketajen, Gedangan, KAB. SIDOARJO, GEDANGAN','KABUPATEN SIDOARJO','JAWA TIMUR',NULL,'1',0,'2023-04-06 09:51:38','2023-04-06 09:51:38'),
	(682,'1','100682',NULL,'DIKARAFIF','-',NULL,'Nusaloka Sek XIV.6 Jl. Banda 2 Blok IA No 22 BSD, KOTA TANGERANG SELATAN, SERPONG, BANTEN','KOTA TANGERANG SELATAN','BANTEN',NULL,'1',0,'2023-04-06 10:03:59','2023-04-06 10:03:59'),
	(684,'1','100684',NULL,'AYU KUSUMA WARDHANI','08974216774',NULL,'RS WIDODO KAMAR BERSALIN JL YOS SUDARSO NO 8 NGAWI JAWA TIMUR','KABUPATEN NGAWI','JAWA TIMUR',NULL,'1',0,'2023-04-06 13:45:41','2023-04-06 13:45:41'),
	(685,'1','100685',NULL,'INDRA KELANA','0881026471571',NULL,'PLALANGAN DESA DARUNGAN KABUPATEN JEMBER KECAMATAN TANGGUL RT 06/RW12','KABUPATEN JEMBER','JAWA TIMUR',NULL,'1',0,'2023-04-08 08:55:46','2023-04-08 08:55:46'),
	(686,'1','100686',NULL,'Elmita Mariana Puspa',NULL,NULL,'Jl. Kembang Kuning Kulon Besar C17 Kec, Sawahan Kota Surabaya Jatim','KOTA SURABAYA','JAWA TIMUR',NULL,'1',0,'2023-04-08 08:56:36','2023-04-08 08:56:36'),
	(687,'1','100687',NULL,'TATA','085704112332',NULL,'DUSUN SAMBIREJO DESA PONDOKAGUNG RT 03 RW 05 SEBELAH UTARA SEBELUM SUKMA JAYA FARM DAN SEBELAH SELATAN PAS TOKO BU LILIK','KOTA BATU','JAWA TIMUR',NULL,'1',0,'2023-04-08 08:59:42','2023-04-08 08:59:42'),
	(688,'1','100688',NULL,'Rosa Xhu (Xhu Softlens)',NULL,NULL,'Kadang Gang 19 No 30 RT06/RW03 Kelurahan Kadang Kota Malang Jatim','KOTA MALANG','JAWA TIMUR',NULL,'1',0,'2023-04-08 09:11:58','2023-04-08 09:11:58'),
	(689,'1','100689',NULL,'Umiyanti',NULL,NULL,'08 Kontrakan Bu Yuti Kamar Warna Biru Jalan Cendrawasih RT06/RW08 Bajing Kulon Kroya Kab Cilacap Jateng','KABUPATEN CILACAP','JAWA TENGAH',NULL,'1',0,'2023-04-08 09:36:57','2023-04-08 09:36:57'),
	(690,'1','100690',NULL,'Iga Maharani',NULL,NULL,'Jl. Hang Tuah II No 26 RT16/RW6 Sidoklumpuk Sidoarjo Jatim','KABUPATEN SIDOARJO','JAWA TIMUR',NULL,'1',0,'2023-04-08 10:00:13','2023-04-08 10:00:13'),
	(691,'1','100691',NULL,'Akhmad Helmi',NULL,NULL,'Jl. Kamboja I No 80 RT6/RW4 Kel Mekarjaya Sukmajaya Depok Jabar','KOTA DEPOK','JAWA BARAT',NULL,'1',0,'2023-04-08 10:12:58','2023-04-08 10:12:58'),
	(692,'1','100692',NULL,'Riana Mardiningsih',NULL,NULL,'Ds. Jajar Dkh, Pengkol RT19/RW06 Kec. Kartoharjo Kab Magetan Jatim','KABUPATEN MAGETAN','JAWA TIMUR',NULL,'1',0,'2023-04-08 10:25:35','2023-04-08 10:25:35'),
	(693,'1','100693',NULL,'Alfira Rahmawati',NULL,NULL,'Dsn, Curah Palung RT3/RW3 Desa Kradenan Purwoharjo Banyuwangi Jatim','KABUPATEN BANYUWANGI','JAWA TIMUR',NULL,'1',0,'2023-04-08 10:29:40','2023-04-08 10:29:40'),
	(694,'1','100694',NULL,'Rianah Sukria',NULL,NULL,'JL. Perdana No 35 RT001/RW003 Pamulang Tangerang Selatan Banten','KOTA TANGERANG SELATAN','BANTEN',NULL,'1',0,'2023-04-08 11:50:33','2023-04-08 11:50:33'),
	(695,'1','100695',NULL,'Lita',NULL,NULL,'Dsn. Kwangen RT01/RW02 Ds Sidorejo Jetis Kab Mojokerto Jatim','KABUPATEN MOJOKERTO','JAWA TIMUR',NULL,'1',0,'2023-04-10 08:54:08','2023-04-10 08:54:08'),
	(696,'1','100696',NULL,'MIYA TAMUN','08895374892',NULL,'RT 02 RW 01 SEBELAH SELATAN BENGKEL HUDA SARANG REMBANG JAWA TENGAH','KABUPATEN REMBANG','JAWA TENGAH',NULL,'1',0,'2023-04-10 08:59:03','2023-04-10 08:59:03'),
	(697,'1','100697',NULL,'Deasy Permatasari',NULL,NULL,'Jl. Perlis Selatan NO 10 C Pabean Cantikan KOta Surabaya Jatim','KOTA SURABAYA','JAWA TIMUR',NULL,'1',0,'2023-04-10 09:03:03','2023-04-10 09:03:03'),
	(698,'1','100698',NULL,'Safana',NULL,NULL,'Jawa timur Kab Magetan Kec Sidorejo Desa Sumber Sawit Dukuh Sawit RT4/RW3 Sidorejo Kab Magetan Jatim','KABUPATEN MAGETAN','JAWA TIMUR',NULL,'1',0,'2023-04-10 09:08:46','2023-04-10 09:08:46'),
	(699,'1','100699',NULL,'Ahmad Kanafi',NULL,NULL,'Jl. Karangsono RT12/RW5 Karangsono Dander Boonegoro Jatim','KABUPATEN BOJONEGORO','JAWA TIMUR',NULL,'1',0,'2023-04-10 09:14:26','2023-04-10 09:14:26'),
	(700,'1','100700',NULL,'Seli',NULL,NULL,'KP. Sindang Palay Gang Sindang Mekar RT1/RW9 Cangkuang Kulon Dayeuhkolot Kab Bandung Jabar','KOTA BANDUNG','JAWA BARAT',NULL,'1',0,'2023-04-10 09:18:28','2023-04-10 09:18:28'),
	(701,'1','100701',NULL,'Vio',NULL,NULL,'Jl. Lamper Tengah XV No 17 Lamper Tengah Semarang Selatan Jateng','KABUPATEN SEMARANG','JAWA TENGAH',NULL,'1',0,'2023-04-10 09:22:29','2023-04-10 09:22:29'),
	(702,'1','100702',NULL,'Endah Septyaningsih',NULL,NULL,'Perumahan Puri Gading Blok H 1 No 32 RT2/RW13 Jati Melati Pondok Melati Bekasi Jabar','KABUPATEN BEKASI','JAWA BARAT',NULL,'1',0,'2023-04-10 09:25:55','2023-04-10 09:25:55'),
	(703,'1','100703',NULL,'Nisma Seren Ramadhan',NULL,NULL,'Kost Waroeng Jibe Jalan Kaliurang Gang Kenari No 1B Catur Tunggal Depok Kab Sleman DI Yogyakarta','KABUPATEN SLEMAN','DI YOGYAKARTA',NULL,'1',0,'2023-04-10 09:32:39','2023-04-10 09:32:39'),
	(704,'1','100704',NULL,'Rosikin',NULL,NULL,'Laboratorium Klinik Sejahtera Mayangan Kota Probolinggo Jalan Jend Ahmad Yani No 12 Suka Bumi Mayangan Probolinggo Jawatimur','KABUPATEN PROBOLINGGO','JAWA TIMUR',NULL,'1',0,'2023-04-10 09:36:05','2023-04-10 09:36:05'),
	(705,'1','100705',NULL,'CIki',NULL,NULL,'Komplek TTC Jl. Nila D3/16 Tanah Sereal Kota Bogor Jawa Barat','KABUPATEN BOGOR','JAWA BARAT',NULL,'1',0,'2023-04-10 09:43:20','2023-04-10 09:43:20'),
	(706,'1','100706',NULL,'Diana DC',NULL,NULL,'Wisata Bukit Mas 2 Jl. Dulyon Lidah Wetan Lakar Santri H2-33 Lakar Santri Surabaya Jatim','KOTA SURABAYA','JAWA TIMUR',NULL,'1',0,'2023-04-10 09:50:08','2023-04-10 09:50:08'),
	(707,'1','100707',NULL,'Ibu Kalimah Pijet',NULL,NULL,'Jl. Gajah Magersari 1 Gang Pisang No 9 RT11/RW04 Magersari Sidoarjo Jatim','KABUPATEN SIDOARJO','JAWA TIMUR',NULL,'1',0,'2023-04-10 09:53:32','2023-04-10 09:53:32'),
	(708,'1','100708',NULL,'Kevin',NULL,NULL,'Jl. Jaksa Agung Suprapto Gang 2 No 94 (Belakang Hotel Lotus) Mojoroto Kediri Jatim','KOTA KEDIRI','JAWA TIMUR',NULL,'1',0,'2023-04-10 10:02:38','2023-04-10 10:02:38'),
	(709,'1','100709',NULL,'Erna Diana',NULL,NULL,'Jl. Candi Sari Utara No 1 Mojolangu Lowokwaru KOta Malang Jatim','KABUPATEN MALANG','JAWA TIMUR',NULL,'1',0,'2023-04-10 13:42:21','2023-04-10 13:42:21'),
	(710,'1','100710',NULL,'FEBY CHYNTHIAA','081222129214',NULL,'PT BENUA CAKRA ABADI RUKO MUTIARA BLOK C3 NO 17','KOTA JAKARTA PUSAT','DKI JAKARTA',NULL,'1',0,'2023-04-11 08:52:16','2023-04-11 08:52:16'),
	(711,'1','100711',NULL,'Putra',NULL,NULL,'Lapasion Restauran Bar Surabaya Jl. Mayjend Yono Soewoyo No 39 40 Babatan Wiyung Surabaya Jatim','KOTA SURABAYA','JAWA TIMUR',NULL,'1',0,'2023-04-11 09:37:53','2023-04-11 09:37:53'),
	(712,'1','100712',NULL,'Margaret Citra',NULL,NULL,'RT04 RW05 Dsn Gendingan Ds Tamban Kec Pakel Kab Tulunganggung Jatim','KABUPATEN TULUNGAGUNG','JAWA TIMUR',NULL,'1',0,'2023-04-11 09:42:29','2023-04-11 09:42:29'),
	(713,'1','100713',NULL,'RIANTI GADIS AYU','-',NULL,'Jln Tukad Ngenjung, gg napuleon no 11 . SERANGAN, KOTA DENPASAR, DENPASAR SELATAN, BALI','KOTA DENPASAR','BALI',NULL,'1',0,'2023-04-11 10:27:13','2023-04-11 10:27:13'),
	(714,'1','100714',NULL,'KUSNIA NINGSIH','-',NULL,'Kalidawir Tanggulangin, Jalan Kh Basori, RT.7/RW.2, Kalidawir, Tanggulangin, KAB. SIDOARJO, TANGGULANGIN,','KABUPATEN SIDOARJO','JAWA TIMUR',NULL,'1',0,'2023-04-11 10:32:07','2023-04-11 10:32:07'),
	(715,'1','100715',NULL,'TIRTA AYU','-',NULL,'Jalan sawah Kwangsan Blok A No. 10, Sedati (sebelum perum Naura) (Kamar kos no 5), KAB. SIDOARJO, SEDATI,','KABUPATEN SIDOARJO','JAWA TIMUR',NULL,'1',0,'2023-04-11 10:35:58','2023-04-11 10:35:58'),
	(718,'1','100718',NULL,'Diana.',NULL,NULL,'Dsn, Bogem Ds Keret Rt07/Rw02 Krembung Kab Sidoarjo Jatim','KABUPATEN SIDOARJO','JAWA TIMUR',NULL,'1',0,'2023-04-12 08:54:37','2023-04-12 08:54:37'),
	(719,'1','100719',NULL,'Tika Ullyatifa',NULL,NULL,'Pondok Wage Indah 1 Blok K No 36 Taman Kab Sidoarjo Jatim','KABUPATEN SIDOARJO','JAWA TIMUR',NULL,'1',0,'2023-04-12 08:59:06','2023-04-12 08:59:06'),
	(720,'1','100720',NULL,'Sriyanti Aldi',NULL,NULL,'Dukuh Selodono Malangsari RT001/RW002 Desa Malangsari Mushola Belok Kanan Rumah ke 2 Pulung Kab Ponorogo Jatim','KABUPATEN PONOROGO','JAWA TIMUR',NULL,'1',0,'2023-04-12 09:07:33','2023-04-12 09:07:33'),
	(721,'1','100721',NULL,'fatayah','085733446010',NULL,'kirim kantor forest','KOTA SURABAYA','JAWA TIMUR',NULL,'1',0,'2023-04-12 10:35:51','2023-04-12 10:35:51'),
	(722,'1','100722',NULL,'kiki nabila','087765568990',NULL,'jl patemon 4 no 97b kel patemon kec sawahan kodepos 60252 surabaya','KOTA SURABAYA','JAWA TIMUR',NULL,'1',0,'2023-04-12 13:23:25','2023-04-12 13:23:25'),
	(723,'1','100723',NULL,'Ascott bu Mia',NULL,NULL,'perum forest mansion cluster blossom hill b8 surabaya','KOTA SURABAYA','JAWA TIMUR',NULL,'1',0,'2023-04-12 13:38:22','2023-04-12 13:38:22'),
	(724,'1','100724',NULL,'Nurhasyim',NULL,NULL,'Pasar Mutiara Mulung Driorejo Jl. Mutiara III 3 MUlung Driyorejo Gresik Jatim','KABUPATEN GRESIK','JAWA TIMUR',NULL,'1',0,'2023-04-13 08:49:47','2023-04-13 08:49:47'),
	(725,'1','100725',NULL,'Cicilia',NULL,NULL,'Lorong XI Jl. Banyu Urip NO32 RT005/RW06 Kupang Krajan Kec Sawahan XI No 32 Kota SUrabaya Jatim','KOTA SURABAYA','JAWA TIMUR',NULL,'1',0,'2023-04-13 08:59:57','2023-04-13 08:59:57'),
	(726,'1','100726',NULL,'Adelia',NULL,NULL,'Jalan Karang Menjangan No 106 Mojo Gubeng Surabaya Kota','KOTA SURABAYA','JAWA TIMUR',NULL,'1',0,'2023-04-13 10:41:42','2023-04-13 10:41:42'),
	(727,'1','100727',NULL,'Adelia Nanda',NULL,NULL,'Jl. Empunala No 287 Magersari Kota Mojokerto Jatim','KABUPATEN MOJOKERTO','JAWA TIMUR',NULL,'1',0,'2023-04-14 08:55:34','2023-04-14 08:55:34'),
	(728,'1','100728',NULL,'ANING SUGIARTI','081359582828',NULL,'TOKO BABY GENIUS, JL DIPONEGORO NO 58 PONOROGO','KABUPATEN PONOROGO','JAWA TIMUR',NULL,'1',0,'2023-04-14 13:57:59','2023-04-14 13:57:59'),
	(729,'1','100729',NULL,'Bagus Prasetyo','081802878035',NULL,'Jalan Nginden III E NO 4','KOTA SURABAYA','JAWA TIMUR',NULL,'1',0,'2023-04-14 14:05:16','2023-04-14 14:05:16'),
	(730,'1','100730',NULL,'Nita Indriani',NULL,NULL,'Kp Bedahan RT5/RW1 Pabuaran Mekar Cibinong No 12 Kab Bogor Jabar','KABUPATEN BOGOR','JAWA BARAT',NULL,'1',0,'2023-04-15 13:10:53','2023-04-15 13:10:53'),
	(731,'1','100731',NULL,'Fariha',NULL,NULL,'Jl. Benowo Sawah Barat Gang II No 11 RT6/RW1 Benowo Pakal Surabaya Jatim','KOTA SURABAYA','JAWA TIMUR',NULL,'1',0,'2023-04-17 08:40:23','2023-04-17 08:40:23'),
	(732,'1','100732',NULL,'LILIK ARDANI','085745528209',NULL,'DUSUN KAWUR GAMPINGROWO RT 06 RW 03 TARIK SIDAORJO POM MINI BAPAK MULYADI','KABUPATEN SIDOARJO','JAWA TIMUR',NULL,'1',0,'2023-04-17 08:49:47','2023-04-17 08:49:47'),
	(733,'1','100733',NULL,'Nunung Ratna Ningsih',NULL,NULL,'Perum Polri Gowok Blok D1/172 Ambarukmo Caturtunggal Kec Depok Sleman Daerah Istimewa Yogyakarta','KABUPATEN SLEMAN','DI YOGYAKARTA',NULL,'1',0,'2023-04-17 09:55:56','2023-04-17 09:55:56'),
	(734,'1','100734',NULL,'Happy Tien Milady',NULL,NULL,'Jl, Sunan Giri Gang Beringin Jaya No 06 Lamongan Jatim','KABUPATEN LAMONGAN','JAWA TIMUR',NULL,'1',0,'2023-04-17 10:00:38','2023-04-17 10:00:38'),
	(735,'1','100735',NULL,'Ikka Ningtyas',NULL,NULL,'Jatisari Tempel RT3/RW4 Mijen Kota Semarang Jateng','KABUPATEN SEMARANG','JAWA TENGAH',NULL,'1',0,'2023-04-17 10:04:35','2023-04-17 10:04:35'),
	(736,'1','100736',NULL,'Glads Collection (Ayis)','085854196502',NULL,'Ruko Greenlake CA-07','KOTA SURABAYA','JAWA TIMUR',NULL,'1',0,'2023-04-17 10:47:03','2023-04-17 10:47:03'),
	(737,'1','100737',NULL,'Nayla',NULL,NULL,'Krembangan Jaya Utara 6 No 4 Kemayoran Surabaya Jatim','KOTA SURABAYA','JAWA TIMUR',NULL,'1',0,'2023-04-17 12:33:36','2023-04-17 12:33:36'),
	(738,'1','100738',NULL,'Ade Tya (Hellolilolens)','0811861664',NULL,'Jl. Raya Putat Gede Indah No 42-A Surabaya 60189','KOTA SURABAYA','JAWA TIMUR',NULL,'1',0,'2023-04-17 12:38:19','2023-04-17 12:38:19'),
	(739,'1','100739',NULL,'Ifan',NULL,NULL,'Jl dr Angka Gang Irigasi Rt 04/07 Kel Sokanegara Purwokerto Timur Banyumas Jateng','KABUPATEN BANYUMAS','JAWA TENGAH',NULL,'1',0,'2023-04-17 12:42:36','2023-04-17 12:42:36'),
	(740,'1','100740',NULL,'Mutiara Saraswati',NULL,NULL,'Taman Tirta Cimanggu Blok D 3 No 16 Mekarwangi Tarah Sereal Bogor Tanah Sereal Kota Bogor Jabar','KABUPATEN BOGOR','JAWA BARAT',NULL,'1',0,'2023-04-18 09:16:33','2023-04-18 09:16:33'),
	(741,'1','100741',NULL,'Hamam Haris',NULL,NULL,'Kijing Dadi Lancar Jalan Blontongan RT3/RW8 Modangan Lor Blotongan Sidorejo Salatiga Jateng','KOTA SALATIGA','JAWA TENGAH',NULL,'1',0,'2023-04-18 09:27:43','2023-04-18 09:27:43'),
	(742,'1','100742',NULL,'Yolanda Pungki R',NULL,NULL,'Jalan Karangan Jalan I No 12 Babatan Wiyung Surabaya Jatim','KOTA SURABAYA','JAWA TIMUR',NULL,'1',0,'2023-04-18 09:42:49','2023-04-18 09:42:49'),
	(743,'1','100743',NULL,'Lisa Alifiyah- Bpk Ali Siregar',NULL,NULL,'Dsn. Keling Rt13/Rw04 Jumputrejo sukodono sidoarjo jatim','KABUPATEN SIDOARJO','JAWA TIMUR',NULL,'1',0,'2023-04-26 07:43:05','2023-04-26 07:43:05'),
	(744,'1','100744',NULL,'Evie',NULL,NULL,'Jl. Hayam wuruk baru 1/103 surabaya wonokromo surabaya jatim','KOTA SURABAYA','JAWA TIMUR',NULL,'1',0,'2023-04-26 13:15:49','2023-04-26 13:15:49'),
	(745,'1','100745',NULL,'Ira',NULL,NULL,'Jl. Kejawan Putih Tambak Gang 17 No 2 Mulyorejo kota Surabaya Jatim','KOTA SURABAYA','JAWA TIMUR',NULL,'1',0,'2023-04-26 14:33:28','2023-04-26 14:33:28'),
	(746,'1','100746',NULL,'Rissa Asrianar Putri',NULL,NULL,'Ds. Garon RT06/RW01 Kec. Kawedanan magetan Jatim','KABUPATEN MAGETAN','JAWA TIMUR',NULL,'1',0,'2023-04-27 08:34:38','2023-04-27 08:34:38'),
	(747,'1','100747',NULL,'cicavilana kumalasari','62 811-3537-734',NULL,'kirim greenlake ca 11','KOTA SURABAYA','JAWA TIMUR',NULL,'1',0,'2023-04-27 08:57:42','2023-04-27 08:57:42'),
	(748,'1','100748',NULL,'vita desi','082342150394',NULL,'jl banowati rt 4 rw 5 nglambong badengan kab ponorogo jawa timur id 63455','KABUPATEN PONOROGO','JAWA TIMUR',NULL,'1',0,'2023-04-27 10:17:17','2023-04-27 10:17:17'),
	(749,'1','100749',NULL,'fatik','085854249676',NULL,'dsn kaliasin rt 02 rw 06 kecamatan jetis kab. mojokerto jawatimur','KOTA MOJOKERTO','JAWA TIMUR',NULL,'1',0,'2023-04-27 14:22:40','2023-04-27 14:22:40'),
	(750,'1','100750',NULL,'Vyan Lazuardi / PNG',NULL,NULL,'Jl. Made Amd Kel Made RT4/RW3 Made Sambikerep Kota Surabaya Jatim','KOTA SURABAYA','JAWA TIMUR',NULL,'1',0,'2023-04-28 08:30:37','2023-04-28 08:30:37'),
	(751,'1','100751',NULL,'Erna (Sidoarjo)',NULL,NULL,'Perum Mentari Bumi Sejahtera BR18 (Masuk Jlm Mentari V, Candi Sidoarjo Jatim','KABUPATEN SIDOARJO','JAWA TIMUR',NULL,'1',0,'2023-04-28 08:36:01','2023-04-28 08:36:01'),
	(752,'1','100752',NULL,'Dian Dwi Ambarwati',NULL,NULL,'Wonorejo Rungkut RT04 RW01 GG 1 No 15 Surabaya Jatim','KOTA SURABAYA','JAWA TIMUR',NULL,'1',0,'2023-04-28 08:41:29','2023-04-28 08:41:29'),
	(753,'1','100753',NULL,'Tri Suryani (Surabaya)','087861866366',NULL,'Jl. Dukuh Kupang Timur 6/26B Sawahan Surabaya Jatim','KOTA SURABAYA','JAWA TIMUR',NULL,'1',0,'2023-04-28 08:59:42','2023-04-28 08:59:42'),
	(754,'1','100754',NULL,'Mareta',NULL,NULL,'Perum Pondok Permata Suci - Jl. Intan 1 No 46 Manyar Gresik Jatim','KABUPATEN GRESIK','JAWA TIMUR',NULL,'1',0,'2023-04-28 09:15:02','2023-04-28 09:15:02'),
	(755,'1','100755',NULL,'Julia Wongso (Eyes Sparkle)','0811330788',NULL,'Jl. Arief Rahman Hakiem 138-142 Perumahan Regency21 Blok F-7 Surabaya 60117 Sukolilo','KOTA SURABAYA','JAWA TIMUR',NULL,'1',0,'2023-04-28 13:19:23','2023-04-28 13:19:23'),
	(756,'1','100756',NULL,'Widia (Gresik)',NULL,NULL,'Kantor Pemasara Zas Property Jl. Raya Pasar Kedamean No 12, Kedamean Gresik Jawa Timur','KABUPATEN GRESIK','JAWA TIMUR',NULL,'1',0,'2023-04-28 14:50:40','2023-04-28 14:50:40'),
	(757,'1','100757',NULL,'Tia Aiko',NULL,NULL,'Jl. Macan Lundungan Perum Grand Mutiara Residence Blok B-7 Bukit Baru Ilir Barat I Kota Palembang Sumatera Selatan','KOTA PALEMBANG','SUMATERA SELATAN',NULL,'1',0,'2023-04-28 14:57:43','2023-04-28 14:57:43'),
	(758,'1','100758',NULL,'NORRY FEBRIANI','081528280166',NULL,'PAKIS WETAN IV/1A RT 9 RW 3(BELAKANG GELORA PANCASILA) SAWAHAN SURABAYA JAWA TIMUR','KOTA MADIUN','JAWA TIMUR',NULL,'1',0,'2023-04-29 08:47:27','2023-04-29 08:47:27'),
	(759,'1','100759',NULL,'Sofiana',NULL,NULL,'Jl. Mangkunegoro No 2A RT6/RW10 Darmo Wonokromo Kota Surabaya Jatim','KOTA SURABAYA','JAWA TIMUR',NULL,'1',0,'2023-04-29 09:01:33','2023-04-29 09:01:33'),
	(760,'1','100760',NULL,'Zafaa',NULL,NULL,'Ponpes Lubabul Fattah Tunggulsari Jalan Tunggulsari RT4/RW1 Tunggulsari Kedungwaru Kab Tulungagung Jatim','KABUPATEN TULUNGAGUNG','JAWA TIMUR',NULL,'1',0,'2023-04-29 09:07:59','2023-04-29 09:07:59'),
	(761,'1','100761',NULL,'Sartika Septiana',NULL,NULL,'Swasembada Barat XXVI No 54 RT002/RW011 Kelurahan Kebun Bawang Tanjung Priok Kota Jakarta Utara DKIJakarta','KOTA JAKARTA UTARA','DKI JAKARTA',NULL,'1',0,'2023-04-29 09:32:36','2023-04-29 09:32:36'),
	(762,'1','100762',NULL,'Mutiara Putri Taptazani',NULL,NULL,'Jl. Cibolerang No 99 (Cuci Motor 99) RT002/006 Margasuka Babakan Ciparay Kota Bandung Jabar','KABUPATEN BANDUNG','JAWA BARAT',NULL,'1',0,'2023-04-29 09:50:54','2023-04-29 09:50:54'),
	(763,'1','100763',NULL,'Farida Nur Fitriyani','089607859874',NULL,'jl. Muharto Gg 7 Rt 9 Rw 7 no. 46, kota malang, kec. Kedungkandang, kel. Kotalama','KABUPATEN MALANG','JAWA TIMUR',NULL,'1',0,'2023-04-29 10:19:35','2023-04-29 10:19:35'),
	(765,'1','100765',NULL,'Ibu Aisyah Assegaf',NULL,NULL,'Foresta Collinare C10/5 Pagedangan Kab Tangerang Banten 15157','KOTA TANGERANG','BANTEN',NULL,'1',0,'2023-05-02 09:01:09','2023-05-02 09:01:09'),
	(766,NULL,'100766',NULL,'Zhafran','123',NULL,'-','KOTA SURABAYA','JAWA TIMUR',NULL,'1',0,'2023-05-02 09:02:43','2023-05-02 09:02:43'),
	(767,'1','100767',NULL,'Fitria Ama Adityasari',NULL,NULL,'Jl. Plmahan Besar No 38A RT13/RW10 Kedungdoro Tegalsari Surabaya Jatim','KOTA SURABAYA','JAWA TIMUR',NULL,'1',0,'2023-05-02 09:11:02','2023-05-02 09:11:02'),
	(768,'1','100768',NULL,'Ika Oktavia','085708327927',NULL,'Gempol Kurung RT04/RW02 Gang Seto ada Neon Box Oktav Mua','KABUPATEN GRESIK','JAWA TIMUR',NULL,'1',0,'2023-05-02 13:51:22','2023-05-02 13:51:22'),
	(769,NULL,'100769',NULL,'Elok Faiqoh Agustini',NULL,NULL,'Gg. Modin No 10-B Kos Pak Muhith, Jemur Wonosari Wonocolo Surabaya Jatim','KOTA SURABAYA','JAWA TIMUR',NULL,'1',0,'2023-05-03 09:26:35','2023-05-03 09:26:35'),
	(771,'1','100771',NULL,'VHITA','087711910525',NULL,'KOPERASI AL BAROKAH JRANGOAN, UNNAMED ROAD, TOBETOH, JRANGOAN','KABUPATEN SAMPANG','JAWA TIMUR',NULL,'1',0,'2023-05-04 08:44:12','2023-05-04 08:44:12'),
	(772,'3','100772',NULL,'SHINJUKU EAST COST SALON','031 58208375',NULL,'mutiara Jl. Raya Laguna KJW Putih Tambak No.17, Kejawaan Putih Tamba, Kec. Mulyorejo, Kota SBY','KOTA SURABAYA','JAWA TIMUR',NULL,'1',0,'2023-05-04 09:20:40','2023-05-04 09:20:40'),
	(773,'3','100773',NULL,'SHINJUKU KLAMPIS SALON','(031) 5916164',NULL,'Jl. Klampis Jaya No.24-25, Klampis Ngasem, Kec. Sukolilo, Kota SBY','KOTA SURABAYA','JAWA TIMUR',NULL,'1',0,'2023-05-04 09:46:11','2023-05-04 09:46:11'),
	(774,'1','100774',NULL,'RISTA','081335903539',NULL,'DSN DASRI RT 1 RW 1 DESA SRITI DUSUN DASRI KEC SAWOO RT 1 RW 1 PONOROGO JAWA TIMUR','KABUPATEN PONOROGO','JAWA TIMUR',NULL,'1',0,'2023-05-05 09:03:53','2023-05-05 09:03:53'),
	(775,'1','100775',NULL,'AGUS TRI ASTUTI','085211109935',NULL,'PERUM BMI DAWUAN CIKAMPEK, UNNAMED ROAD,DAWUAN TENGAH F2 NO 4 RT 01 RW 08 CIKAMPEK JAWA BARAT','KABUPATEN CIANJUR','JAWA BARAT',NULL,'1',0,'2023-05-05 13:33:29','2023-05-05 13:33:29'),
	(776,NULL,'100776',NULL,'AMELIA KOSMETIK','081331450789',NULL,'Jln tlogosuryo NO. 27, RT 1 / RW  2 Lowokwaru,\r\nTlogomas kec Lowokwaru Malang','KABUPATEN MALANG','JAWA TIMUR',NULL,'1',0,'2023-05-05 13:35:49','2023-05-05 13:35:49'),
	(777,NULL,'100777',NULL,'Karimulya',NULL,NULL,'Pondok Sidokare Indah Blok UU No 7 RT37/RW11 Kel Sidokare Kec Sidoarjo Jatim','KABUPATEN SIDOARJO','JAWA TIMUR',NULL,'1',0,'2023-05-05 13:51:29','2023-05-05 13:51:29'),
	(778,NULL,'100778',NULL,'LIZBETH BEAUTE','082230888532',NULL,'Jl. Kesatrian Dalam no 4 , Malang','KABUPATEN MALANG','JAWA TIMUR',NULL,'1',0,'2023-05-05 13:54:40','2023-05-05 13:54:40'),
	(779,NULL,'100779',NULL,'KARTIKA SALON','081259527120',NULL,'Jalan Ngaglik 4b No 4 RT 10 RW 9 Sukun Malang','KABUPATEN MALANG','JAWA TIMUR',NULL,'1',0,'2023-05-05 14:58:50','2023-05-05 14:58:50'),
	(780,NULL,'100780',NULL,'Aira Nur Aini','08819129218',NULL,'Dsn, Munggu RT03/RW03 Ds Kapuran Badegan Ponorogo Jatim','KABUPATEN PONOROGO','JAWA TIMUR',NULL,'1',0,'2023-05-06 09:09:52','2023-05-06 09:09:52'),
	(781,NULL,'100781',NULL,'PHIL EYELASH KOSMETIK','082131406805',NULL,'Jalan Lesti Utara No 3 Bunulrejo,','KABUPATEN MALANG','JAWA TIMUR',NULL,'1',0,'2023-05-06 09:23:15','2023-05-06 09:23:15'),
	(782,NULL,'100782',NULL,'Romeo','123',NULL,'-','KABUPATEN NIAS','SUMATRA URATA',NULL,'1',0,'2023-05-08 09:23:41','2023-05-08 09:23:41'),
	(783,NULL,'100783',NULL,'Hyra','123',NULL,'-','KABUPATEN BOGOR','JAWA BARAT',NULL,'1',0,'2023-05-08 09:49:50','2023-05-08 09:49:50'),
	(784,NULL,'100784',NULL,'Hore','123',NULL,'-','KABUPATEN KERINCI','JAMBI',NULL,'1',0,'2023-05-08 09:52:35','2023-05-08 09:52:35'),
	(785,NULL,'100785',NULL,'Almahyra','1234',NULL,'-',NULL,'ACEH',NULL,'1',0,'2023-05-08 18:46:33','2023-05-08 18:46:33'),
	(786,NULL,'100786',NULL,'ronal','123',NULL,'ee','KABUPATEN SIMEULUE','ACEH',NULL,'1',0,'2023-05-09 07:50:15','2023-05-09 07:50:15'),
	(787,NULL,'100787',NULL,'ronil','123',NULL,NULL,NULL,NULL,NULL,'1',0,'2023-05-09 07:50:36','2023-05-09 07:50:36'),
	(788,NULL,'100788',NULL,'eee','eeee',NULL,'eee','KABUPATEN NIAS','SUMATRA UTARA',NULL,'1',0,'2023-05-09 07:51:54','2023-05-09 07:51:54');

/*!40000 ALTER TABLE `pelanggan` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table penerimaan_penjualan
# ------------------------------------------------------------

DROP TABLE IF EXISTS `penerimaan_penjualan`;

CREATE TABLE `penerimaan_penjualan` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `created_by` bigint(20) unsigned NOT NULL,
  `akun_bank_id` bigint(20) unsigned NOT NULL,
  `nomer_bukti` varchar(191) NOT NULL,
  `tanggal` date NOT NULL,
  `jumlah_pembayaran` double NOT NULL,
  `keterangan` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



# Dump of table penerimaan_penjualan_berkas
# ------------------------------------------------------------

DROP TABLE IF EXISTS `penerimaan_penjualan_berkas`;

CREATE TABLE `penerimaan_penjualan_berkas` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `penerimaan_penjualan_id` bigint(20) unsigned NOT NULL,
  `nama_berkas` varchar(191) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `penerimaan_penjualan_berkas_penerimaan_penjualan_id_foreign` (`penerimaan_penjualan_id`),
  CONSTRAINT `penerimaan_penjualan_berkas_penerimaan_penjualan_id_foreign` FOREIGN KEY (`penerimaan_penjualan_id`) REFERENCES `penerimaan_penjualan` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



# Dump of table penerimaan_penjualan_rinci
# ------------------------------------------------------------

DROP TABLE IF EXISTS `penerimaan_penjualan_rinci`;

CREATE TABLE `penerimaan_penjualan_rinci` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `penerimaan_penjualan_id` bigint(20) unsigned NOT NULL,
  `penjualan_invoice_id` bigint(20) unsigned NOT NULL,
  `bayar` double NOT NULL DEFAULT 0,
  `nominal_pembayaran` double NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `penerimaan_penjualan_rinci_penerimaan_penjualan_id_foreign` (`penerimaan_penjualan_id`),
  KEY `penerimaan_penjualan_rinci_penjualan_invoice_id_foreign` (`penjualan_invoice_id`),
  CONSTRAINT `penerimaan_penjualan_rinci_penerimaan_penjualan_id_foreign` FOREIGN KEY (`penerimaan_penjualan_id`) REFERENCES `penerimaan_penjualan` (`id`) ON DELETE CASCADE,
  CONSTRAINT `penerimaan_penjualan_rinci_penjualan_invoice_id_foreign` FOREIGN KEY (`penjualan_invoice_id`) REFERENCES `penjualan_invoice` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



# Dump of table penjualan_invoice
# ------------------------------------------------------------

DROP TABLE IF EXISTS `penjualan_invoice`;

CREATE TABLE `penjualan_invoice` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `penjualan_pesanan_id` bigint(20) unsigned NOT NULL COMMENT 'jika transaksi pesanan penjualan dihapus, maka transaksi invoice juga terhapus',
  `akun_bank_id` bigint(20) unsigned DEFAULT NULL,
  `akun_biayakirim_id` bigint(20) unsigned DEFAULT NULL,
  `akun_ppn_id` bigint(20) unsigned DEFAULT NULL,
  `akun_diskon_id` bigint(20) unsigned DEFAULT NULL,
  `pelanggan_id` bigint(20) unsigned NOT NULL,
  `sales_id` bigint(20) unsigned DEFAULT NULL,
  `created_by` bigint(20) unsigned NOT NULL,
  `gudang_id` bigint(20) unsigned DEFAULT NULL,
  `nomer_invoice_penjualan` varchar(191) NOT NULL,
  `nomer_ref` varchar(191) DEFAULT NULL,
  `tanggal` date NOT NULL,
  `keterangan` varchar(191) DEFAULT NULL,
  `jenis_penjualan` varchar(191) DEFAULT NULL,
  `ppn` varchar(191) DEFAULT '0',
  `nilai_ppn` double DEFAULT 0,
  `nomer_pesanan` varchar(191) DEFAULT NULL,
  `resi` varchar(191) DEFAULT NULL,
  `ekspedisi` varchar(191) DEFAULT NULL,
  `penerima` varchar(191) DEFAULT NULL,
  `alamat_penerima` text DEFAULT NULL,
  `diskon_persen_global` double DEFAULT 0,
  `diskon_nominal_global` double DEFAULT 0,
  `biaya_kirim` double DEFAULT 0,
  `status_proses` varchar(191) NOT NULL DEFAULT '0',
  `grandtotal` double DEFAULT 0,
  `grandtotal_setelah_diskon` double DEFAULT 0 COMMENT 'sudah termasuk ppn jika ada',
  `sudah_terbayar` double NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `penjualan_invoice_nomer_invoice_penjualan_unique` (`nomer_invoice_penjualan`),
  KEY `penjualan_invoice_penjualan_pesanan_id_foreign` (`penjualan_pesanan_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

LOCK TABLES `penjualan_invoice` WRITE;
/*!40000 ALTER TABLE `penjualan_invoice` DISABLE KEYS */;

INSERT INTO `penjualan_invoice` (`id`, `penjualan_pesanan_id`, `akun_bank_id`, `akun_biayakirim_id`, `akun_ppn_id`, `akun_diskon_id`, `pelanggan_id`, `sales_id`, `created_by`, `gudang_id`, `nomer_invoice_penjualan`, `nomer_ref`, `tanggal`, `keterangan`, `jenis_penjualan`, `ppn`, `nilai_ppn`, `nomer_pesanan`, `resi`, `ekspedisi`, `penerima`, `alamat_penerima`, `diskon_persen_global`, `diskon_nominal_global`, `biaya_kirim`, `status_proses`, `grandtotal`, `grandtotal_setelah_diskon`, `sudah_terbayar`, `created_at`, `updated_at`)
VALUES
	(97,61,3,139,50,74,2,1000,1,1,'MMG/23/06/00001','SO-001/VI/2023','2023-06-05',NULL,'TOKOPEDIA','2',99099.099099099,NULL,NULL,NULL,'Lala Hermawati','- KABUPATEN SIDOARJO JAWA TIMUR',0,0,0,'1',1000000,1000000,0,'2023-06-05 15:53:38','2023-06-06 10:34:57'),
	(98,0,3,139,50,74,3,1000,1,1,'MMG/23/06/00002','KN/VI/2023/000001','2023-06-05',NULL,'KONSINYASI','2',247747.748,NULL,NULL,NULL,NULL,NULL,0,0,0,'1',2500000,2500000,0,'2023-06-05 16:18:12','2023-06-06 12:42:32'),
	(99,0,4,139,4,74,1,1000,1,1,'MMG/23/06/00003',NULL,'2023-06-21',NULL,'KONSINYASI','2',13758.324,NULL,NULL,NULL,NULL,NULL,0,0,0,'0',138834,138834,0,'2023-06-21 19:03:50','2023-06-21 19:20:43'),
	(100,0,3,139,177,247,1,1000,1,1,'MMG/23/06/00004',NULL,'2023-06-21',NULL,'EVENT','2',5747.748,NULL,NULL,NULL,NULL,NULL,0,0,0,'0',58000,58000,0,'2023-06-21 19:18:11','2023-06-21 19:18:11');

/*!40000 ALTER TABLE `penjualan_invoice` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table penjualan_invoice_berkas
# ------------------------------------------------------------

DROP TABLE IF EXISTS `penjualan_invoice_berkas`;

CREATE TABLE `penjualan_invoice_berkas` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `penjualan_invoice_id` bigint(20) unsigned NOT NULL,
  `nama_berkas` varchar(191) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `penjualan_invoice_berkas_penjualan_invoice_id_foreign` (`penjualan_invoice_id`),
  CONSTRAINT `penjualan_invoice_berkas_penjualan_invoice_id_foreign` FOREIGN KEY (`penjualan_invoice_id`) REFERENCES `penjualan_invoice` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



# Dump of table penjualan_invoice_rinci
# ------------------------------------------------------------

DROP TABLE IF EXISTS `penjualan_invoice_rinci`;

CREATE TABLE `penjualan_invoice_rinci` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `penjualan_invoice_id` bigint(20) unsigned NOT NULL,
  `produk_id` bigint(20) unsigned NOT NULL,
  `gudang_id` bigint(20) unsigned NOT NULL,
  `kuantitas` double NOT NULL DEFAULT 0,
  `harga_produk` double NOT NULL DEFAULT 0,
  `diskon_persen` double NOT NULL DEFAULT 0,
  `diskon_nominal` double NOT NULL DEFAULT 0,
  `potongan_admin` double NOT NULL DEFAULT 0,
  `cashback` double NOT NULL DEFAULT 0,
  `subtotal` double NOT NULL DEFAULT 0,
  `catatan` varchar(191) DEFAULT NULL,
  `status` varchar(191) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `penjualan_invoice_rinci_penjualan_invoice_id_foreign` (`penjualan_invoice_id`),
  CONSTRAINT `penjualan_invoice_rinci_penjualan_invoice_id_foreign` FOREIGN KEY (`penjualan_invoice_id`) REFERENCES `penjualan_invoice` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

LOCK TABLES `penjualan_invoice_rinci` WRITE;
/*!40000 ALTER TABLE `penjualan_invoice_rinci` DISABLE KEYS */;

INSERT INTO `penjualan_invoice_rinci` (`id`, `penjualan_invoice_id`, `produk_id`, `gudang_id`, `kuantitas`, `harga_produk`, `diskon_persen`, `diskon_nominal`, `potongan_admin`, `cashback`, `subtotal`, `catatan`, `status`, `created_at`, `updated_at`)
VALUES
	(141,97,1,1,1,1000000,0,0,0,0,1000000,NULL,'0',NULL,NULL),
	(142,98,1,1,1,2500000,0,0,0,0,2500000,NULL,'0','2023-06-05 16:18:12','2023-06-05 16:18:12'),
	(153,100,1,1,1,15000,0,0,0,0,15000,NULL,'0','2023-06-21 19:18:11','2023-06-21 19:18:11'),
	(154,100,3,1,1,0,0,0,0,0,0,NULL,'0','2023-06-21 19:18:11','2023-06-21 19:18:11'),
	(155,100,2,1,1,45000,4.44,2000,0,0,43000,NULL,'0','2023-06-21 19:18:11','2023-06-21 19:18:11'),
	(156,99,1,1,1,10000,10,1000,0,0,9000,NULL,'0','2023-06-21 19:20:43','2023-06-21 19:20:43'),
	(157,99,2,1,1,0,0,0,0,0,0,NULL,'0','2023-06-21 19:20:43','2023-06-21 19:20:43'),
	(158,99,39,1,1,0,0,0,0,0,0,NULL,'0','2023-06-21 19:20:43','2023-06-21 19:20:43'),
	(159,99,78,1,1,20000,5,1000,0,0,19000,NULL,'0','2023-06-21 19:20:43','2023-06-21 19:20:43'),
	(160,99,424,1,1,59125,0,45666,0,0,13459,'KLT NEW Beauty Inside Out - Series','0','2023-06-21 19:20:43','2023-06-21 19:20:43'),
	(161,99,425,1,1,59125,0,8000,0,0,51125,'KLT NEW Beauty Inside Out - Series','0','2023-06-21 19:20:43','2023-06-21 19:20:43'),
	(162,99,422,1,1,59125,0,70000,0,0,-10875,'KLT NEW Beauty Inside Out - Series','0','2023-06-21 19:20:43','2023-06-21 19:20:43'),
	(163,99,423,1,1,59125,0,2000,0,0,57125,'KLT NEW Beauty Inside Out - Series','0','2023-06-21 19:20:43','2023-06-21 19:20:43');

/*!40000 ALTER TABLE `penjualan_invoice_rinci` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table penjualan_konsinyasi
# ------------------------------------------------------------

DROP TABLE IF EXISTS `penjualan_konsinyasi`;

CREATE TABLE `penjualan_konsinyasi` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `pelanggan_id` bigint(20) unsigned NOT NULL,
  `gudang_id` bigint(20) unsigned NOT NULL,
  `akun_bank_id` bigint(20) unsigned DEFAULT NULL,
  `akun_ppn_id` bigint(20) unsigned DEFAULT NULL,
  `akun_diskon_id` bigint(20) unsigned DEFAULT NULL,
  `sales_id` bigint(20) unsigned NOT NULL,
  `created_by` bigint(20) unsigned NOT NULL,
  `nomer_penjualan_konsinyasi` varchar(191) NOT NULL,
  `tanggal` date NOT NULL,
  `keterangan` varchar(191) DEFAULT NULL,
  `ppn` double NOT NULL DEFAULT 0,
  `nilai_ppn` double NOT NULL DEFAULT 0,
  `diskon_persen_global` double NOT NULL DEFAULT 0,
  `diskon_nominal_global` double NOT NULL DEFAULT 0,
  `total_sebelum_diskon` double NOT NULL DEFAULT 0,
  `total_setelah_diskon` double NOT NULL DEFAULT 0,
  `grandtotal` double NOT NULL DEFAULT 0,
  `status_proses` varchar(191) NOT NULL DEFAULT '0' COMMENT '0=belum lunas, 1=sudah lunas',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



# Dump of table penjualan_konsinyasi_berkas
# ------------------------------------------------------------

DROP TABLE IF EXISTS `penjualan_konsinyasi_berkas`;

CREATE TABLE `penjualan_konsinyasi_berkas` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `penjualan_konsinyasi_id` bigint(20) unsigned NOT NULL,
  `nama_berkas` varchar(191) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `penjualan_konsinyasi_berkas_penjualan_konsinyasi_id_foreign` (`penjualan_konsinyasi_id`),
  CONSTRAINT `penjualan_konsinyasi_berkas_penjualan_konsinyasi_id_foreign` FOREIGN KEY (`penjualan_konsinyasi_id`) REFERENCES `penjualan_konsinyasi` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



# Dump of table penjualan_konsinyasi_rinci
# ------------------------------------------------------------

DROP TABLE IF EXISTS `penjualan_konsinyasi_rinci`;

CREATE TABLE `penjualan_konsinyasi_rinci` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `penjualan_konsinyasi_id` bigint(20) unsigned NOT NULL,
  `gudang_id` bigint(20) unsigned NOT NULL,
  `produk_id` bigint(20) unsigned NOT NULL,
  `kuantitas` double NOT NULL DEFAULT 0,
  `diskon_persen` double NOT NULL DEFAULT 0,
  `diskon_nominal` double NOT NULL DEFAULT 0,
  `subtotal` double NOT NULL DEFAULT 0,
  `catatan` double NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `penjualan_konsinyasi_rinci_penjualan_konsinyasi_id_foreign` (`penjualan_konsinyasi_id`),
  CONSTRAINT `penjualan_konsinyasi_rinci_penjualan_konsinyasi_id_foreign` FOREIGN KEY (`penjualan_konsinyasi_id`) REFERENCES `penjualan_konsinyasi` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



# Dump of table penjualan_pengiriman
# ------------------------------------------------------------

DROP TABLE IF EXISTS `penjualan_pengiriman`;

CREATE TABLE `penjualan_pengiriman` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `penjualan_pesanan_id` bigint(20) unsigned NOT NULL,
  `pelanggan_id` bigint(20) unsigned NOT NULL,
  `sales_id` bigint(20) unsigned DEFAULT NULL,
  `created_by` bigint(20) unsigned NOT NULL,
  `nomer_pengiriman_penjualan` varchar(191) NOT NULL,
  `tanggal` date NOT NULL,
  `keterangan` text DEFAULT NULL,
  `jenis_penjualan` varchar(191) DEFAULT NULL,
  `ppn` varchar(191) DEFAULT '0',
  `nomer_pesanan` varchar(191) DEFAULT NULL,
  `resi` varchar(191) DEFAULT NULL,
  `ekspedisi` varchar(191) DEFAULT NULL,
  `penerima` varchar(191) DEFAULT NULL,
  `alamat_penerima` text DEFAULT NULL,
  `diskon_persen` double DEFAULT 0,
  `diskon_global` double DEFAULT 0,
  `status_proses` varchar(1) NOT NULL DEFAULT '0',
  `grandtotal` double DEFAULT 0,
  `grandtotal_setelah_diskon` double DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `penjualan_pengiriman_nomer_pengiriman_penjualan_unique` (`nomer_pengiriman_penjualan`),
  KEY `penjualan_pengiriman_penjualan_pesanan_id_foreign` (`penjualan_pesanan_id`),
  CONSTRAINT `penjualan_pengiriman_penjualan_pesanan_id_foreign` FOREIGN KEY (`penjualan_pesanan_id`) REFERENCES `penjualan_pesanan` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



# Dump of table penjualan_pengiriman_berkas
# ------------------------------------------------------------

DROP TABLE IF EXISTS `penjualan_pengiriman_berkas`;

CREATE TABLE `penjualan_pengiriman_berkas` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `penjualan_pengiriman_id` bigint(20) unsigned NOT NULL,
  `berkas1` varchar(191) DEFAULT NULL,
  `berkas2` varchar(191) DEFAULT NULL,
  `berkas3` varchar(191) DEFAULT NULL,
  `berkas4` varchar(191) DEFAULT NULL,
  `berkas5` varchar(191) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `penjualan_pengiriman_berkas_penjualan_pengiriman_id_foreign` (`penjualan_pengiriman_id`),
  CONSTRAINT `penjualan_pengiriman_berkas_penjualan_pengiriman_id_foreign` FOREIGN KEY (`penjualan_pengiriman_id`) REFERENCES `penjualan_pengiriman` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



# Dump of table penjualan_pengiriman_rinci
# ------------------------------------------------------------

DROP TABLE IF EXISTS `penjualan_pengiriman_rinci`;

CREATE TABLE `penjualan_pengiriman_rinci` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `produk_id` bigint(20) unsigned NOT NULL,
  `penjualan_pengiriman_id` bigint(20) unsigned NOT NULL,
  `kuantitas` double NOT NULL,
  `harga_produk` double NOT NULL,
  `diskon_persen` double DEFAULT 0,
  `diskon_nominal` double DEFAULT 0,
  `potongan_admin` double DEFAULT 0,
  `cashback` double DEFAULT 0,
  `subtotal` double DEFAULT 0,
  `catatan` varchar(191) DEFAULT '0',
  `status` varchar(191) NOT NULL DEFAULT '0' COMMENT '0=belum diproses, 1=sudah diproses',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `penjualan_pengiriman_rinci_penjualan_pengiriman_id_foreign` (`penjualan_pengiriman_id`),
  CONSTRAINT `penjualan_pengiriman_rinci_penjualan_pengiriman_id_foreign` FOREIGN KEY (`penjualan_pengiriman_id`) REFERENCES `penjualan_pengiriman` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



# Dump of table penjualan_pesanan
# ------------------------------------------------------------

DROP TABLE IF EXISTS `penjualan_pesanan`;

CREATE TABLE `penjualan_pesanan` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `akun_bank_id` bigint(20) unsigned DEFAULT 4,
  `akun_ppn_id` bigint(20) unsigned DEFAULT 50,
  `akun_biayakirim_id` bigint(20) unsigned DEFAULT 87,
  `akun_diskon_id` bigint(20) unsigned DEFAULT 74,
  `pelanggan_id` bigint(20) unsigned NOT NULL,
  `sales_id` bigint(20) unsigned DEFAULT NULL,
  `created_by` bigint(20) unsigned NOT NULL,
  `gudang_id` bigint(20) unsigned DEFAULT 1,
  `nomer_pesanan_penjualan` varchar(191) NOT NULL,
  `tanggal` date DEFAULT NULL,
  `keterangan` text DEFAULT NULL,
  `jenis_penjualan` varchar(191) DEFAULT NULL,
  `ppn` varchar(191) DEFAULT '0' COMMENT '0=non-ppn,1=ppn,2=include-ppn',
  `nilai_ppn` double DEFAULT 0,
  `nomer_pesanan` varchar(191) DEFAULT NULL,
  `resi` varchar(191) DEFAULT NULL,
  `ekspedisi` varchar(191) DEFAULT NULL,
  `penerima` varchar(191) DEFAULT NULL,
  `alamat_penerima` text DEFAULT NULL,
  `diskon_persen` double DEFAULT 0,
  `diskon_global` double DEFAULT 0,
  `biaya_kirim` double DEFAULT 0,
  `status_proses` varchar(1) NOT NULL DEFAULT '0',
  `grandtotal` double DEFAULT 0,
  `grandtotal_setelah_diskon` double DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `penjualan_pesanan_nomer_pesanan_penjualan_unique` (`nomer_pesanan_penjualan`),
  KEY `penjualan_pesanan_akun_diskon_id_foreign` (`akun_diskon_id`),
  KEY `penjualan_pesanan_akun_biayakirim_id_foreign` (`akun_biayakirim_id`),
  KEY `penjualan_pesanan_akun_ppn_id_foreign` (`akun_ppn_id`),
  KEY `penjualan_pesanan_akun_bank_id_foreign` (`akun_bank_id`),
  CONSTRAINT `penjualan_pesanan_akun_bank_id_foreign` FOREIGN KEY (`akun_bank_id`) REFERENCES `coa` (`id`),
  CONSTRAINT `penjualan_pesanan_akun_biayakirim_id_foreign` FOREIGN KEY (`akun_biayakirim_id`) REFERENCES `coa` (`id`),
  CONSTRAINT `penjualan_pesanan_akun_diskon_id_foreign` FOREIGN KEY (`akun_diskon_id`) REFERENCES `coa` (`id`),
  CONSTRAINT `penjualan_pesanan_akun_ppn_id_foreign` FOREIGN KEY (`akun_ppn_id`) REFERENCES `coa` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

LOCK TABLES `penjualan_pesanan` WRITE;
/*!40000 ALTER TABLE `penjualan_pesanan` DISABLE KEYS */;

INSERT INTO `penjualan_pesanan` (`id`, `akun_bank_id`, `akun_ppn_id`, `akun_biayakirim_id`, `akun_diskon_id`, `pelanggan_id`, `sales_id`, `created_by`, `gudang_id`, `nomer_pesanan_penjualan`, `tanggal`, `keterangan`, `jenis_penjualan`, `ppn`, `nilai_ppn`, `nomer_pesanan`, `resi`, `ekspedisi`, `penerima`, `alamat_penerima`, `diskon_persen`, `diskon_global`, `biaya_kirim`, `status_proses`, `grandtotal`, `grandtotal_setelah_diskon`, `created_at`, `updated_at`)
VALUES
	(61,3,50,139,74,2,1000,1,1,'SO-001/VI/2023','2023-06-05',NULL,'TOKOPEDIA','2',99099.09909909917,NULL,NULL,NULL,'Lala Hermawati','- KABUPATEN SIDOARJO JAWA TIMUR',0,0,0,'2',1000000,1000000,'2023-06-05 15:53:38','2023-06-06 10:34:57');

/*!40000 ALTER TABLE `penjualan_pesanan` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table penjualan_pesanan_berkas
# ------------------------------------------------------------

DROP TABLE IF EXISTS `penjualan_pesanan_berkas`;

CREATE TABLE `penjualan_pesanan_berkas` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `penjualan_pesanan_id` bigint(20) unsigned NOT NULL,
  `berkas1` varchar(191) DEFAULT NULL,
  `berkas2` varchar(191) DEFAULT NULL,
  `berkas3` varchar(191) DEFAULT NULL,
  `berkas4` varchar(191) DEFAULT NULL,
  `berkas5` varchar(191) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `penjualan_pesanan_berkas_penjualan_pesanan_id_foreign` (`penjualan_pesanan_id`),
  CONSTRAINT `penjualan_pesanan_berkas_penjualan_pesanan_id_foreign` FOREIGN KEY (`penjualan_pesanan_id`) REFERENCES `penjualan_pesanan` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

LOCK TABLES `penjualan_pesanan_berkas` WRITE;
/*!40000 ALTER TABLE `penjualan_pesanan_berkas` DISABLE KEYS */;

INSERT INTO `penjualan_pesanan_berkas` (`id`, `penjualan_pesanan_id`, `berkas1`, `berkas2`, `berkas3`, `berkas4`, `berkas5`, `created_at`, `updated_at`)
VALUES
	(32,61,'','','','','','2023-06-05 15:53:38','2023-06-05 15:53:38');

/*!40000 ALTER TABLE `penjualan_pesanan_berkas` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table penjualan_pesanan_rinci
# ------------------------------------------------------------

DROP TABLE IF EXISTS `penjualan_pesanan_rinci`;

CREATE TABLE `penjualan_pesanan_rinci` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `produk_id` bigint(20) unsigned NOT NULL,
  `penjualan_pesanan_id` bigint(20) unsigned NOT NULL,
  `gudang_id` bigint(20) unsigned DEFAULT 1,
  `kuantitas` double NOT NULL,
  `harga_produk` double NOT NULL,
  `diskon_persen` double DEFAULT 0,
  `diskon_nominal` double DEFAULT 0,
  `potongan_admin` double DEFAULT 0,
  `cashback` double DEFAULT 0,
  `subtotal` double DEFAULT 0,
  `catatan` varchar(191) DEFAULT '0',
  `status` varchar(191) NOT NULL DEFAULT '0' COMMENT '0=belum diproses, 1=sudah diproses',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `penjualan_pesanan_rinci_penjualan_pesanan_id_foreign` (`penjualan_pesanan_id`),
  CONSTRAINT `penjualan_pesanan_rinci_penjualan_pesanan_id_foreign` FOREIGN KEY (`penjualan_pesanan_id`) REFERENCES `penjualan_pesanan` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

LOCK TABLES `penjualan_pesanan_rinci` WRITE;
/*!40000 ALTER TABLE `penjualan_pesanan_rinci` DISABLE KEYS */;

INSERT INTO `penjualan_pesanan_rinci` (`id`, `produk_id`, `penjualan_pesanan_id`, `gudang_id`, `kuantitas`, `harga_produk`, `diskon_persen`, `diskon_nominal`, `potongan_admin`, `cashback`, `subtotal`, `catatan`, `status`, `created_at`, `updated_at`)
VALUES
	(77,1,61,1,1,1000000,0,0,0,0,1000000,NULL,'0',NULL,NULL);

/*!40000 ALTER TABLE `penjualan_pesanan_rinci` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table penjualan_tester
# ------------------------------------------------------------

DROP TABLE IF EXISTS `penjualan_tester`;

CREATE TABLE `penjualan_tester` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `pelanggan_id` bigint(20) unsigned NOT NULL,
  `sales_id` bigint(20) unsigned NOT NULL,
  `gudang_id` bigint(20) unsigned NOT NULL,
  `created_by` bigint(20) unsigned NOT NULL,
  `nomer_permintaan_tester` varchar(191) NOT NULL,
  `tanggal` date NOT NULL,
  `keterangan` text DEFAULT NULL,
  `nomer_pesanan` varchar(191) DEFAULT NULL,
  `ekspedisi` varchar(191) DEFAULT NULL,
  `resi` varchar(191) DEFAULT NULL,
  `penerima` varchar(191) DEFAULT NULL,
  `alamat_penerima` text DEFAULT NULL,
  `status_proses` varchar(191) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `penjualan_tester_nomer_permintaan_tester_unique` (`nomer_permintaan_tester`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

LOCK TABLES `penjualan_tester` WRITE;
/*!40000 ALTER TABLE `penjualan_tester` DISABLE KEYS */;

INSERT INTO `penjualan_tester` (`id`, `pelanggan_id`, `sales_id`, `gudang_id`, `created_by`, `nomer_permintaan_tester`, `tanggal`, `keterangan`, `nomer_pesanan`, `ekspedisi`, `resi`, `penerima`, `alamat_penerima`, `status_proses`, `created_at`, `updated_at`)
VALUES
	(4,1,1000,1,1,'TESTER-0001/V/2023','2023-05-23',NULL,NULL,NULL,NULL,'KLT KLINIK','JL BRATANG BINANGUN NO 12 B, SURABAYA JAWA TIMUR','0','2023-05-23 17:14:16','2023-05-23 17:14:16');

/*!40000 ALTER TABLE `penjualan_tester` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table penjualan_tester_berkas
# ------------------------------------------------------------

DROP TABLE IF EXISTS `penjualan_tester_berkas`;

CREATE TABLE `penjualan_tester_berkas` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `penjualan_tester_id` bigint(20) unsigned NOT NULL,
  `berkas` varchar(191) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `penjualan_tester_berkas_penjualan_tester_id_foreign` (`penjualan_tester_id`),
  CONSTRAINT `penjualan_tester_berkas_penjualan_tester_id_foreign` FOREIGN KEY (`penjualan_tester_id`) REFERENCES `penjualan_tester` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



# Dump of table penjualan_tester_rinci
# ------------------------------------------------------------

DROP TABLE IF EXISTS `penjualan_tester_rinci`;

CREATE TABLE `penjualan_tester_rinci` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `penjualan_tester_id` bigint(20) unsigned NOT NULL,
  `gudang_id` bigint(20) unsigned NOT NULL,
  `created_by` bigint(20) unsigned NOT NULL,
  `produk_id` bigint(20) unsigned NOT NULL,
  `kuantitas` double NOT NULL,
  `catatan` varchar(191) DEFAULT NULL,
  `status_proses` varchar(191) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `penjualan_tester_rinci_penjualan_tester_id_foreign` (`penjualan_tester_id`),
  CONSTRAINT `penjualan_tester_rinci_penjualan_tester_id_foreign` FOREIGN KEY (`penjualan_tester_id`) REFERENCES `penjualan_tester` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

LOCK TABLES `penjualan_tester_rinci` WRITE;
/*!40000 ALTER TABLE `penjualan_tester_rinci` DISABLE KEYS */;

INSERT INTO `penjualan_tester_rinci` (`id`, `penjualan_tester_id`, `gudang_id`, `created_by`, `produk_id`, `kuantitas`, `catatan`, `status_proses`, `created_at`, `updated_at`)
VALUES
	(2,4,1,1,400,15,NULL,'0',NULL,NULL),
	(3,4,1,1,422,12,NULL,'0',NULL,NULL);

/*!40000 ALTER TABLE `penjualan_tester_rinci` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table permintaan_pembelian
# ------------------------------------------------------------

DROP TABLE IF EXISTS `permintaan_pembelian`;

CREATE TABLE `permintaan_pembelian` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `created_by` bigint(20) unsigned NOT NULL,
  `tipe_permintaan` varchar(191) NOT NULL DEFAULT '1' COMMENT '1=produk,2=asset,3=jasa,4=lainnya',
  `nomer_permintaan_pembelian` varchar(191) NOT NULL,
  `tanggal` date NOT NULL,
  `keterangan` text DEFAULT NULL,
  `status_revisi` varchar(191) NOT NULL DEFAULT '0' COMMENT '0=tidak revisi,1=revisi',
  `nomer_ref_revisi` varchar(191) DEFAULT NULL COMMENT 'mengambil dari nomer revisi yang dipilih',
  `status_proses` varchar(191) NOT NULL DEFAULT '0' COMMENT '0=belum diproses,1=sudah diproses,10=ditutup',
  `direktur_id` bigint(20) unsigned DEFAULT NULL,
  `komisaris_id` bigint(20) unsigned DEFAULT NULL,
  `approve_direktur` varchar(191) NOT NULL DEFAULT '0',
  `approve_komisaris` varchar(191) NOT NULL DEFAULT '0',
  `catatan_direktur` text DEFAULT NULL,
  `catatan_komisaris` text DEFAULT NULL,
  `alasan_revisi` text DEFAULT NULL COMMENT 'jika permintaan pembelian sudah diproses, alasan revisi wajib diisi',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `permintaan_pembelian_nomer_permintaan_pembelian_unique` (`nomer_permintaan_pembelian`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

LOCK TABLES `permintaan_pembelian` WRITE;
/*!40000 ALTER TABLE `permintaan_pembelian` DISABLE KEYS */;

INSERT INTO `permintaan_pembelian` (`id`, `created_by`, `tipe_permintaan`, `nomer_permintaan_pembelian`, `tanggal`, `keterangan`, `status_revisi`, `nomer_ref_revisi`, `status_proses`, `direktur_id`, `komisaris_id`, `approve_direktur`, `approve_komisaris`, `catatan_direktur`, `catatan_komisaris`, `alasan_revisi`, `created_at`, `updated_at`)
VALUES
	(90,1,'3','PR/1000','2023-06-13','servis printer tim desain (Mr X)','0',NULL,'1',1,NULL,'1','0','oke',NULL,NULL,'2023-06-13 12:23:31','2023-06-13 12:24:43'),
	(91,1,'3','PR/1002_cancel125949','2023-06-12','ada perubahan sory','0',NULL,'10',6,NULL,'1','0','oke lanjut...',NULL,NULL,'2023-06-13 12:54:46','2023-06-13 12:59:49'),
	(92,1,'3','PR/1002','2023-06-12','revisi data ulang, salah nama','0',NULL,'0',6,NULL,'1','0','oke lanjut',NULL,NULL,'2023-06-13 12:59:49','2023-06-13 13:01:13');

/*!40000 ALTER TABLE `permintaan_pembelian` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table permintaan_pembelian_berkas
# ------------------------------------------------------------

DROP TABLE IF EXISTS `permintaan_pembelian_berkas`;

CREATE TABLE `permintaan_pembelian_berkas` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `permintaan_pembelian_id` bigint(20) unsigned NOT NULL,
  `nama_berkas` varchar(191) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `permintaan_pembelian_berkas_permintaan_pembelian_id_foreign` (`permintaan_pembelian_id`),
  CONSTRAINT `permintaan_pembelian_berkas_permintaan_pembelian_id_foreign` FOREIGN KEY (`permintaan_pembelian_id`) REFERENCES `permintaan_pembelian` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

LOCK TABLES `permintaan_pembelian_berkas` WRITE;
/*!40000 ALTER TABLE `permintaan_pembelian_berkas` DISABLE KEYS */;

INSERT INTO `permintaan_pembelian_berkas` (`id`, `permintaan_pembelian_id`, `nama_berkas`, `created_at`, `updated_at`)
VALUES
	(16,91,'PR_1002_648804a694339.pdf','2023-06-13 12:54:46','2023-06-13 12:54:46'),
	(17,91,'PR_1002_648804a6960e7.jpg','2023-06-13 12:54:46','2023-06-13 12:54:46'),
	(18,92,'PR_1002_648804a694339.pdf','2023-06-13 12:59:49','2023-06-13 12:59:49'),
	(19,92,'PR_1002_648804a6960e7.jpg','2023-06-13 12:59:49','2023-06-13 12:59:49');

/*!40000 ALTER TABLE `permintaan_pembelian_berkas` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table permintaan_pembelian_rinci
# ------------------------------------------------------------

DROP TABLE IF EXISTS `permintaan_pembelian_rinci`;

CREATE TABLE `permintaan_pembelian_rinci` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `permintaan_pembelian_id` bigint(20) unsigned NOT NULL,
  `item_id` bigint(20) unsigned NOT NULL,
  `deskripsi_item` varchar(191) DEFAULT NULL COMMENT 'untuk jenis barang jasa/lainnya',
  `kuantitas` double NOT NULL DEFAULT 0,
  `kuantitas_diterima` double NOT NULL DEFAULT 0 COMMENT 'diisi ketika ada Penerimaan Barang',
  `kuantitas_diproses` double NOT NULL DEFAULT 0 COMMENT 'diisi ketika membuat PO',
  `harga` double DEFAULT 0,
  `catatan` varchar(191) DEFAULT NULL,
  `tanggal_minta` date NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `permintaan_pembelian_rinci_permintaan_pembelian_id_foreign` (`permintaan_pembelian_id`),
  CONSTRAINT `permintaan_pembelian_rinci_permintaan_pembelian_id_foreign` FOREIGN KEY (`permintaan_pembelian_id`) REFERENCES `permintaan_pembelian` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

LOCK TABLES `permintaan_pembelian_rinci` WRITE;
/*!40000 ALTER TABLE `permintaan_pembelian_rinci` DISABLE KEYS */;

INSERT INTO `permintaan_pembelian_rinci` (`id`, `permintaan_pembelian_id`, `item_id`, `deskripsi_item`, `kuantitas`, `kuantitas_diterima`, `kuantitas_diproses`, `harga`, `catatan`, `tanggal_minta`, `created_at`, `updated_at`)
VALUES
	(165,90,443,'Servis Printer',1,0,1,150000,'printer epson','2023-06-13','2023-06-13 12:23:31','2023-06-13 13:23:45'),
	(168,91,443,'Service Mobil Avanza',1,0,0,5000000,'L 1234 OKE','2023-06-13','2023-06-13 12:56:40','2023-06-13 12:56:40'),
	(169,91,443,'Service sepeda motor jupiter',1,0,0,2000000,'L 1234 KO','2023-06-13','2023-06-13 12:56:40','2023-06-13 12:56:40'),
	(172,92,443,'Service Mobil Avanza',1,0,1,5000000,'L 1234 OKE','2023-06-13','2023-06-13 13:00:52','2023-06-13 13:30:56'),
	(173,92,443,'Service sepeda motor jupiter',1,0,0,2000000,'L 1234 KO','2023-06-13','2023-06-13 13:00:52','2023-06-13 13:00:52');

/*!40000 ALTER TABLE `permintaan_pembelian_rinci` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table pesanan_pembelian
# ------------------------------------------------------------

DROP TABLE IF EXISTS `pesanan_pembelian`;

CREATE TABLE `pesanan_pembelian` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `permintaan_pembelian_id` bigint(20) unsigned NOT NULL,
  `supplier_id` bigint(20) unsigned NOT NULL,
  `status_proses` varchar(191) NOT NULL DEFAULT '0' COMMENT '0=belum diproses,1=sudah diproses',
  `created_by` bigint(20) unsigned NOT NULL,
  `direktur_id` bigint(20) unsigned NOT NULL,
  `komisaris_id` bigint(20) unsigned NOT NULL,
  `approve_direktur` varchar(1) NOT NULL DEFAULT '0',
  `approve_komisaris` varchar(1) NOT NULL DEFAULT '0',
  `catatan_direktur` varchar(191) DEFAULT NULL,
  `catatan_komisaris` varchar(191) DEFAULT NULL,
  `nomer_pesanan_pembelian` varchar(191) NOT NULL,
  `tanggal` date NOT NULL,
  `keterangan` text DEFAULT NULL,
  `diskon_persen_global` double DEFAULT 0,
  `diskon_nominal_global` double DEFAULT 0,
  `ppn` varchar(191) NOT NULL DEFAULT '0' COMMENT '0=non-ppn, 1=ppn, 2=include-ppn',
  `nilai_ppn` double DEFAULT 0,
  `biaya_kirim` double NOT NULL DEFAULT 0,
  `total` double NOT NULL,
  `total_setelah_diskon` double NOT NULL,
  `grandtotal` double NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `pesanan_pembelian_nomer_pesanan_pembelian_unique` (`nomer_pesanan_pembelian`),
  KEY `fk_permintaan_pembelian` (`permintaan_pembelian_id`),
  KEY `fk_supplier` (`supplier_id`),
  CONSTRAINT `fk_permintaan_pembelian` FOREIGN KEY (`permintaan_pembelian_id`) REFERENCES `permintaan_pembelian` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_supplier` FOREIGN KEY (`supplier_id`) REFERENCES `supplier` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

LOCK TABLES `pesanan_pembelian` WRITE;
/*!40000 ALTER TABLE `pesanan_pembelian` DISABLE KEYS */;

INSERT INTO `pesanan_pembelian` (`id`, `permintaan_pembelian_id`, `supplier_id`, `status_proses`, `created_by`, `direktur_id`, `komisaris_id`, `approve_direktur`, `approve_komisaris`, `catatan_direktur`, `catatan_komisaris`, `nomer_pesanan_pembelian`, `tanggal`, `keterangan`, `diskon_persen_global`, `diskon_nominal_global`, `ppn`, `nilai_ppn`, `biaya_kirim`, `total`, `total_setelah_diskon`, `grandtotal`, `created_at`, `updated_at`)
VALUES
	(3,92,1,'10',1,6,5,'1','1',NULL,NULL,'PO/1_cancel133056','2023-06-12','revisi',5,269500,'1',563255,0,5390000,5120500,5683755,'2023-06-13 13:06:50','2023-06-13 13:30:56'),
	(9,92,1,'0',1,0,0,'0','0',NULL,NULL,'PO/1','2023-06-12','revisi',5,269500,'1',563255,150000,5390000,5120500,5833755,'2023-06-13 13:30:56','2023-06-13 13:30:56');

/*!40000 ALTER TABLE `pesanan_pembelian` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table pesanan_pembelian_berkas
# ------------------------------------------------------------

DROP TABLE IF EXISTS `pesanan_pembelian_berkas`;

CREATE TABLE `pesanan_pembelian_berkas` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `pesanan_pembelian_id` bigint(20) unsigned NOT NULL,
  `nama_berkas` varchar(191) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `pesanan_pembelian_berkas_pesanan_pembelian_id_foreign` (`pesanan_pembelian_id`),
  CONSTRAINT `pesanan_pembelian_berkas_pesanan_pembelian_id_foreign` FOREIGN KEY (`pesanan_pembelian_id`) REFERENCES `pesanan_pembelian` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



# Dump of table pesanan_pembelian_rinci
# ------------------------------------------------------------

DROP TABLE IF EXISTS `pesanan_pembelian_rinci`;

CREATE TABLE `pesanan_pembelian_rinci` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `pesanan_pembelian_id` bigint(20) unsigned NOT NULL,
  `item_id` bigint(20) unsigned NOT NULL,
  `deskripsi_item` varchar(191) DEFAULT NULL,
  `kuantitas` double NOT NULL,
  `kuantitas_diterima` double NOT NULL DEFAULT 0,
  `harga` double NOT NULL DEFAULT 0,
  `diskon_persen` double NOT NULL DEFAULT 0,
  `diskon_nominal` double NOT NULL DEFAULT 0,
  `subtotal` double NOT NULL DEFAULT 0,
  `catatan` varchar(191) DEFAULT NULL,
  `tanggal_diminta` date DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `pesanan_pembelian_rinci_pesanan_pembelian_id_foreign` (`pesanan_pembelian_id`),
  CONSTRAINT `pesanan_pembelian_rinci_pesanan_pembelian_id_foreign` FOREIGN KEY (`pesanan_pembelian_id`) REFERENCES `pesanan_pembelian` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

LOCK TABLES `pesanan_pembelian_rinci` WRITE;
/*!40000 ALTER TABLE `pesanan_pembelian_rinci` DISABLE KEYS */;

INSERT INTO `pesanan_pembelian_rinci` (`id`, `pesanan_pembelian_id`, `item_id`, `deskripsi_item`, `kuantitas`, `kuantitas_diterima`, `harga`, `diskon_persen`, `diskon_nominal`, `subtotal`, `catatan`, `tanggal_diminta`, `created_at`, `updated_at`)
VALUES
	(5,3,443,'Service Mobil Avanza Putih',1,0,5500000,2,110000,5390000,'L 1234 OKE',NULL,'2023-06-13 13:06:50','2023-06-13 13:06:50'),
	(11,9,443,'Service Mobil Avanza Putih',1,0,5500000,2,110000,5390000,'L 1234 OKE',NULL,'2023-06-13 13:30:56','2023-06-13 13:30:56');

/*!40000 ALTER TABLE `pesanan_pembelian_rinci` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table pindah_stok
# ------------------------------------------------------------

DROP TABLE IF EXISTS `pindah_stok`;

CREATE TABLE `pindah_stok` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `created_by` bigint(20) unsigned NOT NULL,
  `nomer_ref` varchar(191) NOT NULL,
  `tanggal` date NOT NULL,
  `tanggal_kirim` date DEFAULT NULL COMMENT 'tanggal pengiriman / tanggal proses di sistem',
  `keterangan` text DEFAULT NULL,
  `gudang_asal_id` bigint(20) unsigned NOT NULL,
  `gudang_tujuan_id` bigint(20) unsigned NOT NULL,
  `status_proses` varchar(2) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `pindah_stok_nomer_ref_unique` (`nomer_ref`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



# Dump of table pindah_stok_berkas
# ------------------------------------------------------------

DROP TABLE IF EXISTS `pindah_stok_berkas`;

CREATE TABLE `pindah_stok_berkas` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `pindah_stok_id` bigint(20) unsigned NOT NULL,
  `nama_berkas` varchar(191) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `pindah_stok_berkas_pindah_stok_id_foreign` (`pindah_stok_id`),
  CONSTRAINT `pindah_stok_berkas_pindah_stok_id_foreign` FOREIGN KEY (`pindah_stok_id`) REFERENCES `pindah_stok` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



# Dump of table pindah_stok_rinci
# ------------------------------------------------------------

DROP TABLE IF EXISTS `pindah_stok_rinci`;

CREATE TABLE `pindah_stok_rinci` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `pindah_stok_id` bigint(20) unsigned NOT NULL,
  `produk_id` bigint(20) unsigned NOT NULL,
  `kuantitas` double NOT NULL DEFAULT 0,
  `catatan` varchar(191) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `pindah_stok_rinci_pindah_stok_id_foreign` (`pindah_stok_id`),
  CONSTRAINT `pindah_stok_rinci_pindah_stok_id_foreign` FOREIGN KEY (`pindah_stok_id`) REFERENCES `pindah_stok` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



# Dump of table potongan_penerimaan_penjualan
# ------------------------------------------------------------

DROP TABLE IF EXISTS `potongan_penerimaan_penjualan`;

CREATE TABLE `potongan_penerimaan_penjualan` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `penerimaan_penjualan_rinci_id` bigint(20) unsigned NOT NULL,
  `akun_potongan_id` bigint(20) unsigned NOT NULL,
  `potongan` double NOT NULL,
  `catatan` varchar(191) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_penerimaan_penjualan_rinci` (`penerimaan_penjualan_rinci_id`),
  CONSTRAINT `fk_penerimaan_penjualan_rinci` FOREIGN KEY (`penerimaan_penjualan_rinci_id`) REFERENCES `penerimaan_penjualan_rinci` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;



# Dump of table stok_produk_gudang
# ------------------------------------------------------------

DROP TABLE IF EXISTS `stok_produk_gudang`;

CREATE TABLE `stok_produk_gudang` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `updated_by` bigint(20) unsigned NOT NULL COMMENT 'user terakhir yang melakukan update',
  `produk_id` bigint(20) unsigned NOT NULL,
  `gudang_id` bigint(20) unsigned NOT NULL,
  `nama_gudang` varchar(191) NOT NULL,
  `kode_produk` varchar(191) NOT NULL,
  `nama_produk` varchar(191) NOT NULL,
  `kuantitas` double NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

LOCK TABLES `stok_produk_gudang` WRITE;
/*!40000 ALTER TABLE `stok_produk_gudang` DISABLE KEYS */;

INSERT INTO `stok_produk_gudang` (`id`, `updated_by`, `produk_id`, `gudang_id`, `nama_gudang`, `kode_produk`, `nama_produk`, `kuantitas`, `created_at`, `updated_at`)
VALUES
	(1,1,1,1,'wiyung','SFL - DCBGKBR01 - 000','Dream Color Bangkok Brown 0.00',-175,'2023-05-23 16:59:33','2023-06-21 19:20:43'),
	(2,1,424,1,'wiyung','SCR - BLACK0101 - FTR','KLT New Beauty Inside Out Face Toner Brightening',-10012,'2023-05-23 17:04:47','2023-06-21 19:20:43'),
	(3,1,425,1,'wiyung','SCR - BLACK0101 - FWS','KLT New Beauty Inside Out Pepaya Face Wash Brightening',-10012,'2023-05-23 17:04:47','2023-06-21 19:20:43'),
	(4,1,422,1,'wiyung','SCR - BLACK0101 - DCR','KLT New Beauty Inside Out Day Cream with UV Filter',-10024,'2023-05-23 17:04:47','2023-06-21 19:20:43'),
	(5,1,423,1,'wiyung','SCR - BLACK0101 - NCR','KLT New Beauty Inside Out Night Cream Brightening',-10012,'2023-05-23 17:04:47','2023-06-21 19:20:43'),
	(6,1,400,1,'wiyung','CSM - LIPCP0100 - CAR','KLT New Lipscup Caramel',-115,'2023-05-23 17:14:16','2023-05-23 17:17:22'),
	(7,1,400,2,'forest','CSM - LIPCP0100 - CAR','KLT New Lipscup Caramel',100,'2023-05-23 17:17:22','2023-05-23 17:17:22'),
	(8,1,2,1,'wiyung','SFL - DCBGKBR01 - 075','Dream Color Bangkok Brown  0.75',-5,'2023-05-29 12:51:35','2023-06-21 19:20:43'),
	(9,1,3,1,'wiyung','SFL - DCBGKBR01 - 500','Dream Color Bangkok Brown  5.00',-3,'2023-05-29 12:51:35','2023-06-21 19:18:11'),
	(10,1,424,5,'event','SCR - BLACK0101 - FTR','KLT New Beauty Inside Out Face Toner Brightening',10001,'2023-05-30 10:27:38','2023-05-30 10:28:42'),
	(11,1,425,5,'event','SCR - BLACK0101 - FWS','KLT New Beauty Inside Out Pepaya Face Wash Brightening',10001,'2023-05-30 10:27:38','2023-05-30 10:28:42'),
	(12,1,422,5,'event','SCR - BLACK0101 - DCR','KLT New Beauty Inside Out Day Cream with UV Filter',10001,'2023-05-30 10:27:38','2023-05-30 10:28:42'),
	(13,1,423,5,'event','SCR - BLACK0101 - NCR','KLT New Beauty Inside Out Night Cream Brightening',10001,'2023-05-30 10:27:38','2023-05-30 10:28:42'),
	(14,1,4,1,'wiyung','SFL - DCBGKGR01 - 350','Dream Color Bangkok Grey 3.50',-1,'2023-06-21 19:03:50','2023-06-21 19:03:50'),
	(15,1,30,1,'wiyung','SFL - DCCELCL01 - 750','Dream Color Clear Clear  7.50',-1,'2023-06-21 19:03:50','2023-06-21 19:03:50'),
	(16,1,25,1,'wiyung','SFL - DCCELCL01 - 375','Dream Color Clear Clear  3.75',-1,'2023-06-21 19:03:50','2023-06-21 19:03:50'),
	(17,1,39,1,'wiyung','SFL - DCJENBR01 - 225','Dream Color Jennie Brown 2.25',-2,'2023-06-21 19:17:22','2023-06-21 19:20:43'),
	(18,1,78,1,'wiyung','SFL - DCJISGR01 - 200','Dream Color Jiso Grey  2.00',-2,'2023-06-21 19:17:22','2023-06-21 19:20:43');

/*!40000 ALTER TABLE `stok_produk_gudang` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table supplier
# ------------------------------------------------------------

DROP TABLE IF EXISTS `supplier`;

CREATE TABLE `supplier` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `tipe_supplier_id` bigint(20) unsigned NOT NULL,
  `kode` varchar(191) NOT NULL,
  `nama` varchar(191) NOT NULL,
  `nama_pic` varchar(191) DEFAULT NULL,
  `keterangan` text DEFAULT NULL,
  `no_telp` varchar(191) NOT NULL,
  `provinsi` varchar(191) DEFAULT NULL,
  `kota` varchar(191) DEFAULT NULL,
  `detil_alamat` text DEFAULT NULL,
  `nomer_rekening` varchar(191) DEFAULT NULL,
  `status_aktif` varchar(191) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `supplier_kode_unique` (`kode`),
  UNIQUE KEY `supplier_nama_unique` (`nama`),
  KEY `fk_tipe_supplier` (`tipe_supplier_id`),
  CONSTRAINT `fk_tipe_supplier` FOREIGN KEY (`tipe_supplier_id`) REFERENCES `tipe_supplier` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

LOCK TABLES `supplier` WRITE;
/*!40000 ALTER TABLE `supplier` DISABLE KEYS */;

INSERT INTO `supplier` (`id`, `tipe_supplier_id`, `kode`, `nama`, `nama_pic`, `keterangan`, `no_telp`, `provinsi`, `kota`, `detil_alamat`, `nomer_rekening`, `status_aktif`, `created_at`, `updated_at`)
VALUES
	(1,1,'1000','sup a',NULL,NULL,'',NULL,NULL,NULL,NULL,'1',NULL,NULL),
	(4,1,'1002X','altamaX','X','XXX','1115555','jatimX','sidoarjoX','ccrX','12345555','1','2023-06-18 11:42:01','2023-06-18 12:03:32'),
	(5,1,'1003','ronal','ronal','---','2222','jatim','sidoarjo','---','1234','1','2023-06-18 11:56:40','2023-06-18 11:56:40');

/*!40000 ALTER TABLE `supplier` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table tipe_supplier
# ------------------------------------------------------------

DROP TABLE IF EXISTS `tipe_supplier`;

CREATE TABLE `tipe_supplier` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `tipe` varchar(191) NOT NULL,
  `status_aktif` varchar(191) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `tipe_supplier_tipe_unique` (`tipe`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

LOCK TABLES `tipe_supplier` WRITE;
/*!40000 ALTER TABLE `tipe_supplier` DISABLE KEYS */;

INSERT INTO `tipe_supplier` (`id`, `tipe`, `status_aktif`, `created_at`, `updated_at`)
VALUES
	(1,'umum','1',NULL,NULL);

/*!40000 ALTER TABLE `tipe_supplier` ENABLE KEYS */;
UNLOCK TABLES;


# Dump of table transaksi_stok
# ------------------------------------------------------------

DROP TABLE IF EXISTS `transaksi_stok`;

CREATE TABLE `transaksi_stok` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `nomer_ref` varchar(191) DEFAULT NULL,
  `gudang_id` bigint(20) unsigned NOT NULL,
  `produk_id` bigint(20) unsigned NOT NULL,
  `keterangan` varchar(191) DEFAULT NULL,
  `in` double NOT NULL,
  `out` double NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

LOCK TABLES `transaksi_stok` WRITE;
/*!40000 ALTER TABLE `transaksi_stok` DISABLE KEYS */;

INSERT INTO `transaksi_stok` (`id`, `nomer_ref`, `gudang_id`, `produk_id`, `keterangan`, `in`, `out`, `created_at`, `updated_at`)
VALUES
	(1,'SO-001/V/2023',1,1,'Terjual ke KLT KLINIK',0,100,'2023-05-23 16:59:33','2023-05-23 16:59:33'),
	(3,'SO-002/V/2023',1,1,'Terjual ke Linda Jaya',0,10,'2023-05-23 17:04:47','2023-05-23 17:04:47'),
	(4,'SO-002/V/2023',1,424,'Terjual ke Linda Jaya',0,10,'2023-05-23 17:04:47','2023-05-23 17:04:47'),
	(5,'SO-002/V/2023',1,425,'Terjual ke Linda Jaya',0,10,'2023-05-23 17:04:47','2023-05-23 17:04:47'),
	(6,'SO-002/V/2023',1,422,'Terjual ke Linda Jaya',0,10,'2023-05-23 17:04:47','2023-05-23 17:04:47'),
	(7,'SO-002/V/2023',1,423,'Terjual ke Linda Jaya',0,10,'2023-05-23 17:04:47','2023-05-23 17:04:47'),
	(9,'TESTER-0001/V/2023',1,400,'Tester ke KLT KLINIK',0,15,'2023-05-23 17:16:28','2023-05-23 17:16:28'),
	(10,'TESTER-0001/V/2023',1,422,'Tester ke KLT KLINIK',0,12,'2023-05-23 17:16:28','2023-05-23 17:16:28'),
	(13,'MMG/23/05/0004',1,1,'Terjual ke Lala Hermawati',0,1,'2023-05-29 09:46:33','2023-05-29 09:46:33'),
	(14,'MMG/23/05/0005',1,1,'Terjual ke KLT KLINIK',0,1,'2023-05-29 12:51:35','2023-05-29 12:51:35'),
	(15,'MMG/23/05/0005',1,2,'Terjual ke KLT KLINIK',0,1,'2023-05-29 12:51:35','2023-05-29 12:51:35'),
	(16,'MMG/23/05/0005',1,3,'Terjual ke KLT KLINIK',0,1,'2023-05-29 12:51:35','2023-05-29 12:51:35'),
	(17,'MMG/23/05/0006',1,1,'Terjual ke KLT KLINIK',0,1,'2023-05-29 16:13:41','2023-05-29 16:13:41'),
	(18,'MMG/23/05/0007',1,1,'Terjual ke KLT KLINIK',0,1,'2023-05-29 16:14:55','2023-05-29 16:14:55'),
	(19,'MMG/23/05/0008',1,1,'Terjual ke KLT KLINIK',0,1,'2023-05-29 16:15:27','2023-05-29 16:15:27'),
	(20,'MMG/23/05/0009',1,1,'Terjual ke KLT KLINIK',0,1,'2023-05-29 16:17:07','2023-05-29 16:17:07'),
	(21,'MMG/23/05/0010',1,1,'Terjual ke KLT KLINIK',0,1,'2023-05-29 16:17:40','2023-05-29 16:17:40'),
	(22,'MMG/23/05/0011',1,1,'Terjual ke KLT KLINIK',0,1,'2023-05-29 16:18:04','2023-05-29 16:18:04'),
	(23,'MMG/23/05/0011',1,1,'Terjual ke KLT KLINIK',0,1,'2023-05-29 16:21:46','2023-05-29 16:21:46'),
	(24,'MMG/23/05/0011',1,1,'Terjual ke KLT KLINIK',0,1,'2023-05-29 16:22:14','2023-05-29 16:22:14'),
	(25,'MMG/23/05/0011',1,1,'Terjual ke KLT KLINIK',0,1,'2023-05-29 16:22:44','2023-05-29 16:22:44'),
	(26,'MMG/23/05/0011',1,1,'Terjual ke KLT KLINIK',0,1,'2023-05-29 16:23:28','2023-05-29 16:23:28'),
	(27,'MMG/23/05/0011',1,1,'Terjual ke KLT KLINIK',0,1,'2023-05-29 16:23:49','2023-05-29 16:23:49'),
	(28,'MMG/23/05/0011',1,1,'Terjual ke KLT KLINIK',0,1,'2023-05-29 16:24:16','2023-05-29 16:24:16'),
	(29,'MMG/23/05/0012',1,1,'Terjual ke BILQIS COSMETIC',0,1,'2023-05-29 16:25:58','2023-05-29 16:25:58'),
	(30,'MMG/23/05/0012',1,1,'Terjual ke BILQIS COSMETIC',0,1,'2023-05-29 16:26:39','2023-05-29 16:26:39'),
	(31,'MMG/23/05/0001',1,1,'Terjual ke Linda Jaya',0,1,'2023-05-29 22:27:09','2023-05-29 22:27:09'),
	(32,'MMG/23/05/0001',1,1,'Terjual ke Linda Jaya',0,1,'2023-05-29 22:27:42','2023-05-29 22:27:42'),
	(33,'MMG/23/05/0001',1,1,'Terjual ke Linda Jaya',0,1,'2023-05-29 22:29:10','2023-05-29 22:29:10'),
	(34,'MMG/23/05/0001',1,1,'Terjual ke Linda Jaya',0,1,'2023-05-29 22:29:43','2023-05-29 22:29:43'),
	(35,'MMG/23/05/0001',1,1,'Terjual ke Linda Jaya',0,1,'2023-05-29 22:48:46','2023-05-29 22:48:46'),
	(36,'MMG/23/05/0001',1,3,'Terjual ke Linda Jaya',0,1,'2023-05-29 22:48:46','2023-05-29 22:48:46'),
	(37,'SO-001/VI/2023',1,1,'Terjual ke KLT KLINIK',0,1,'2023-06-05 08:21:46','2023-06-05 08:21:46'),
	(38,'SO-001/VI/2023',1,1,'Terjual ke Lala Hermawati',0,1,'2023-06-05 15:53:38','2023-06-05 15:53:38'),
	(39,'MMG/23/06/00002',1,1,'Terjual ke Linda Jaya',0,1,'2023-06-05 16:18:12','2023-06-05 16:18:12'),
	(40,'MMG/23/06/00003',1,1,'Terjual ke KLT KLINIK',0,1,'2023-06-21 19:03:50','2023-06-21 19:03:50'),
	(41,'MMG/23/06/00003',1,2,'Terjual ke KLT KLINIK',0,1,'2023-06-21 19:03:50','2023-06-21 19:03:50'),
	(42,'MMG/23/06/00003',1,4,'Terjual ke KLT KLINIK',0,1,'2023-06-21 19:03:50','2023-06-21 19:03:50'),
	(43,'MMG/23/06/00003',1,30,'Terjual ke KLT KLINIK',0,1,'2023-06-21 19:03:50','2023-06-21 19:03:50'),
	(44,'MMG/23/06/00003',1,25,'Terjual ke KLT KLINIK',0,1,'2023-06-21 19:03:50','2023-06-21 19:03:50'),
	(45,'MMG/23/06/00003',1,1,'Terjual ke KLT KLINIK',0,1,'2023-06-21 19:08:54','2023-06-21 19:08:54'),
	(46,'MMG/23/06/00003',1,1,'Terjual ke KLT KLINIK',0,1,'2023-06-21 19:17:22','2023-06-21 19:17:22'),
	(47,'MMG/23/06/00003',1,2,'Terjual ke KLT KLINIK',0,1,'2023-06-21 19:17:22','2023-06-21 19:17:22'),
	(48,'MMG/23/06/00003',1,39,'Terjual ke KLT KLINIK',0,1,'2023-06-21 19:17:22','2023-06-21 19:17:22'),
	(49,'MMG/23/06/00003',1,78,'Terjual ke KLT KLINIK',0,1,'2023-06-21 19:17:22','2023-06-21 19:17:22'),
	(50,'MMG/23/06/00004',1,1,'Terjual ke KLT KLINIK',0,1,'2023-06-21 19:18:11','2023-06-21 19:18:11'),
	(51,'MMG/23/06/00004',1,3,'Terjual ke KLT KLINIK',0,1,'2023-06-21 19:18:11','2023-06-21 19:18:11'),
	(52,'MMG/23/06/00004',1,2,'Terjual ke KLT KLINIK',0,1,'2023-06-21 19:18:11','2023-06-21 19:18:11'),
	(53,'MMG/23/06/00003',1,1,'Terjual ke KLT KLINIK',0,1,'2023-06-21 19:20:43','2023-06-21 19:20:43'),
	(54,'MMG/23/06/00003',1,2,'Terjual ke KLT KLINIK',0,1,'2023-06-21 19:20:43','2023-06-21 19:20:43'),
	(55,'MMG/23/06/00003',1,39,'Terjual ke KLT KLINIK',0,1,'2023-06-21 19:20:43','2023-06-21 19:20:43'),
	(56,'MMG/23/06/00003',1,78,'Terjual ke KLT KLINIK',0,1,'2023-06-21 19:20:43','2023-06-21 19:20:43'),
	(57,'MMG/23/06/00003',1,424,'Terjual ke KLT KLINIK',0,1,'2023-06-21 19:20:43','2023-06-21 19:20:43'),
	(58,'MMG/23/06/00003',1,425,'Terjual ke KLT KLINIK',0,1,'2023-06-21 19:20:43','2023-06-21 19:20:43'),
	(59,'MMG/23/06/00003',1,422,'Terjual ke KLT KLINIK',0,1,'2023-06-21 19:20:43','2023-06-21 19:20:43'),
	(60,'MMG/23/06/00003',1,423,'Terjual ke KLT KLINIK',0,1,'2023-06-21 19:20:43','2023-06-21 19:20:43');

/*!40000 ALTER TABLE `transaksi_stok` ENABLE KEYS */;
UNLOCK TABLES;



/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
