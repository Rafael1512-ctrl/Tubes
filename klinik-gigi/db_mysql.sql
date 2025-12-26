-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 26, 2025 at 05:24 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `dbklinikgigi`
--

DELIMITER $$
--
-- Procedures
--
CREATE DEFINER=`root`@`localhost` PROCEDURE `Sp_CancelBooking` (IN `p_IdBooking` VARCHAR(20))   BEGIN
  DECLARE EXIT HANDLER FOR SQLEXCEPTION
  BEGIN
    ROLLBACK;
    RESIGNAL;
  END;

  START TRANSACTION;

    -- Pastikan booking ada
    IF NOT EXISTS (SELECT 1 FROM `Booking` WHERE `IdBooking` = p_IdBooking) THEN
      SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'IdBooking tidak ditemukan.';
    END IF;

    -- Update status jadi CANCELLED
    UPDATE `Booking`
    SET `Status` = 'CANCELLED'
    WHERE `IdBooking` = p_IdBooking;

  COMMIT;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `Sp_InsertBooking` (IN `p_IdJadwal` VARCHAR(20), IN `p_IdPasien` VARCHAR(20), IN `p_TanggalBooking` DATETIME, IN `p_Status` VARCHAR(20), OUT `p_NewIdBooking` VARCHAR(20))   BEGIN
    DECLARE v_Kapasitas INT;
    DECLARE v_JadwalStatus VARCHAR(50);
    DECLARE v_JumlahBookingAktif INT;
    DECLARE v_refDate DATE;
    DECLARE v_yy VARCHAR(2);
    DECLARE v_mm VARCHAR(2);
    DECLARE v_prefix VARCHAR(20);
    DECLARE v_lastSeq INT;
    DECLARE v_seqStr VARCHAR(4);

    DECLARE EXIT HANDLER FOR SQLEXCEPTION
    BEGIN
        ROLLBACK;
        RESIGNAL;
    END;

    START TRANSACTION;

    -- 1. Ambil kapasitas dan status jadwal
    SELECT `Kapasitas`, `Status` INTO v_Kapasitas, v_JadwalStatus
    FROM `Jadwal`
    WHERE `IdJadwal` = p_IdJadwal FOR UPDATE;

    IF v_Kapasitas IS NULL THEN
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'IdJadwal tidak ditemukan.';
    END IF;

    IF LOWER(v_JadwalStatus) <> LOWER('Available') THEN
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Jadwal tidak tersedia. Status harus Available.';
    END IF;

    -- 2. Hitung booking aktif
    SELECT COUNT(1) INTO v_JumlahBookingAktif
    FROM `Booking`
    WHERE `IdJadwal` = p_IdJadwal AND IFNULL(`Status`, '') <> 'CANCELLED' FOR UPDATE;

    IF v_JumlahBookingAktif >= v_Kapasitas THEN
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Kapasitas jadwal sudah penuh. Tidak bisa melakukan booking.';
    END IF;

    -- 2b. Cek pasien sudah booking aktif
    IF EXISTS (
      SELECT 1 FROM `Booking`
      WHERE `IdJadwal` = p_IdJadwal
        AND `PasienID` = p_IdPasien
        AND IFNULL(`Status`,'') <> 'CANCELLED'
    ) THEN
      SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Pasien sudah memiliki booking aktif pada jadwal ini.';
    END IF;

    -- 3. Generate IdBooking
    SET v_refDate = IFNULL(DATE(p_TanggalBooking), CURDATE());
    SET v_yy = DATE_FORMAT(v_refDate, '%y');
    SET v_mm = DATE_FORMAT(v_refDate, '%m');
    SET v_prefix = CONCAT('B-', v_yy, v_mm, '-');

    SELECT IFNULL(MAX(CAST(SUBSTRING(IdBooking, 9) AS UNSIGNED)), 0) INTO v_lastSeq
    FROM `Booking`
    WHERE `IdBooking` LIKE CONCAT(v_prefix, '%') FOR UPDATE;

    SET v_lastSeq = v_lastSeq + 1;
    SET v_seqStr = LPAD(v_lastSeq, 4, '0');
    SET p_NewIdBooking = CONCAT(v_prefix, v_seqStr);

    -- 4. Insert booking
    INSERT INTO `Booking` (`IdBooking`, `IdJadwal`, `PasienID`, `TanggalBooking`, `Status`)
    VALUES (p_NewIdBooking, p_IdJadwal, p_IdPasien, p_TanggalBooking, IFNULL(p_Status, 'PRESENT'));

    COMMIT;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `Sp_InsertJadwal` (IN `p_IdDokter` VARCHAR(10), IN `p_Tanggal` DATE, IN `p_Sesi` VARCHAR(10), IN `p_Status` VARCHAR(20), OUT `p_NewIdJadwal` VARCHAR(20))   BEGIN
    DECLARE v_JamMulai TIME;
    DECLARE v_JamAkhir TIME;
    DECLARE v_Jabatan VARCHAR(100);
    DECLARE v_Kapasitas INT;
    DECLARE v_yy VARCHAR(2);
    DECLARE v_mm VARCHAR(2);
    DECLARE v_prefix VARCHAR(20);
    DECLARE v_lastSeq INT;
    DECLARE v_seqStr VARCHAR(4);

    DECLARE EXIT HANDLER FOR SQLEXCEPTION
    BEGIN
        ROLLBACK;
        RESIGNAL;
    END;

    START TRANSACTION;

    IF LOWER(p_Sesi) = 'pagi' THEN
        SET v_JamMulai = '09:00:00';
        SET v_JamAkhir = '12:00:00';
    ELSEIF LOWER(p_Sesi) = 'sore' THEN
        SET v_JamMulai = '17:00:00';
        SET v_JamAkhir = '20:00:00';
    ELSE
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Sesi tidak valid. Pilih ''pagi'' atau ''sore''.';
    END IF;

    SELECT `Jabatan` INTO v_Jabatan FROM `Pegawai` WHERE `PegawaiID` = p_IdDokter;

    IF v_Jabatan IS NULL THEN
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'IdDokter tidak ditemukan di tabel Pegawai.';
    END IF;

    IF LOWER(v_Jabatan) LIKE '%dokter gigi%' THEN
        SET v_Kapasitas = 15;
    ELSEIF LOWER(v_Jabatan) LIKE '%dokter spesialis%' THEN
        SET v_Kapasitas = 4;
    ELSE
        SET v_Kapasitas = 10;
    END IF;

    SET v_yy = DATE_FORMAT(p_Tanggal, '%y');
    SET v_mm = DATE_FORMAT(p_Tanggal, '%m');
    SET v_prefix = CONCAT('J-', v_yy, v_mm, '-');

    SELECT IFNULL(MAX(CAST(SUBSTRING(IdJadwal, 9) AS UNSIGNED)), 0) INTO v_lastSeq
    FROM `Jadwal` WHERE `IdJadwal` LIKE CONCAT(v_prefix, '%') FOR UPDATE;

    SET v_lastSeq = v_lastSeq + 1;
    SET v_seqStr = LPAD(v_lastSeq, 4, '0');
    SET p_NewIdJadwal = CONCAT(v_prefix, v_seqStr);

    INSERT INTO `Jadwal` (`IdJadwal`, `IdDokter`, `Tanggal`, `JamMulai`, `JamAkhir`, `Status`, `Kapasitas`)
    VALUES (p_NewIdJadwal, p_IdDokter, p_Tanggal, v_JamMulai, v_JamAkhir, IFNULL(p_Status, 'Available'), v_Kapasitas);

    COMMIT;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `Sp_InsertJenisObat` (IN `p_NamaJenis` VARCHAR(50))   BEGIN
    DECLARE EXIT HANDLER FOR SQLEXCEPTION
    BEGIN
        ROLLBACK;
        RESIGNAL;
    END;

    START TRANSACTION;

    IF EXISTS (SELECT 1 FROM `JenisObat` WHERE `NamaJenis` = p_NamaJenis) THEN
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Jenis obat sudah terdaftar.';
    END IF;

    INSERT INTO `JenisObat` (`NamaJenis`) VALUES (p_NamaJenis);

    COMMIT;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `Sp_InsertObat` (IN `p_NamaObat` VARCHAR(100), IN `p_Satuan` VARCHAR(20), IN `p_Harga` DECIMAL(12,2), IN `p_Stok` INT, IN `p_IdJenisObat` INT, OUT `p_NewIdObat` VARCHAR(10))   BEGIN
    DECLARE v_prefix VARCHAR(2) DEFAULT 'O-';
    DECLARE v_lastSeq INT;
    DECLARE v_seqStr VARCHAR(5);

    DECLARE EXIT HANDLER FOR SQLEXCEPTION
    BEGIN
        ROLLBACK;
        RESIGNAL;
    END;

    START TRANSACTION;

    IF NOT EXISTS (SELECT 1 FROM `JenisObat` WHERE `JenisObatID` = p_IdJenisObat) THEN
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'IdJenisObat tidak valid.';
    END IF;

    SELECT IFNULL(MAX(CAST(SUBSTRING(IdObat, 3) AS UNSIGNED)), 0) INTO v_lastSeq
    FROM `Obat` WHERE `IdObat` LIKE CONCAT(v_prefix, '%') FOR UPDATE;

    SET v_lastSeq = v_lastSeq + 1;
    SET v_seqStr = LPAD(v_lastSeq, 5, '0');
    SET p_NewIdObat = CONCAT(v_prefix, v_seqStr);

    INSERT INTO `Obat` (`IdObat`, `IdJenisObat`, `NamaObat`, `Satuan`, `Harga`, `Stok`)
    VALUES (p_NewIdObat, p_IdJenisObat, p_NamaObat, p_Satuan, p_Harga, p_Stok);

    COMMIT;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `Sp_InsertPasien` (IN `p_Nama` VARCHAR(100), IN `p_TanggalLahir` DATE, IN `p_Alamat` VARCHAR(200), IN `p_NoTelp` VARCHAR(20), IN `p_JenisKelamin` VARCHAR(1), OUT `p_NewPasienID` VARCHAR(20))   BEGIN
    DECLARE v_lastSeq INT;
    DECLARE v_seqStr VARCHAR(5);
    DECLARE v_tahun VARCHAR(4);
    DECLARE v_prefix VARCHAR(10);

    DECLARE EXIT HANDLER FOR SQLEXCEPTION
    BEGIN
        ROLLBACK;
        RESIGNAL;
    END;

    SET v_tahun = YEAR(CURDATE());
    SET v_prefix = CONCAT('P-', v_tahun, '-');

    START TRANSACTION;

    SELECT IFNULL(MAX(CAST(SUBSTRING(PasienID, 8) AS UNSIGNED)), 0) INTO v_lastSeq
    FROM `Pasien` WHERE `PasienID` LIKE CONCAT(v_prefix, '%') FOR UPDATE;

    SET v_lastSeq = v_lastSeq + 1;
    SET v_seqStr = LPAD(v_lastSeq, 5, '0');
    SET p_NewPasienID = CONCAT(v_prefix, v_seqStr);

    INSERT INTO `Pasien` (`PasienID`, `Nama`, `TanggalLahir`, `Alamat`, `NoTelp`, `JenisKelamin`)
    VALUES (p_NewPasienID, p_Nama, p_TanggalLahir, p_Alamat, p_NoTelp, p_JenisKelamin);

    COMMIT;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `Sp_InsertPegawai` (IN `p_Nama` VARCHAR(100), IN `p_Jabatan` VARCHAR(50), IN `p_TanggalMasuk` DATE, IN `p_NoTelp` VARCHAR(20), OUT `p_NewPegawaiID` VARCHAR(10), IN `p_userid` INT)   BEGIN
    DECLARE v_Prefix VARCHAR(1);
    DECLARE v_lastSeq INT;
    DECLARE v_seqStr VARCHAR(3);

    DECLARE EXIT HANDLER FOR SQLEXCEPTION
    BEGIN
        ROLLBACK;
        RESIGNAL;
    END;

    START TRANSACTION;

    IF LOWER(p_Jabatan) = 'admin' THEN
        SET v_Prefix = 'A';
    ELSEIF LOWER(p_Jabatan) IN ('dokter gigi', 'dokter spesialis') THEN
        SET v_Prefix = 'D';
    ELSE
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Jabatan tidak valid. Hanya Admin, Dokter Gigi, atau Dokter Spesialis yang diperbolehkan.';
    END IF;

    SELECT IFNULL(MAX(CAST(SUBSTRING(PegawaiID, 3) AS UNSIGNED)), 0) INTO v_lastSeq
    FROM `Pegawai` WHERE `PegawaiID` LIKE CONCAT(v_Prefix, '-%') FOR UPDATE;

    SET v_lastSeq = v_lastSeq + 1;
    SET v_seqStr = LPAD(v_lastSeq, 3, '0');
    SET p_NewPegawaiID = CONCAT(v_Prefix, '-', v_seqStr);

    INSERT INTO `Pegawai` (`PegawaiID`, `user_id`, `Nama`, `Jabatan`, `TanggalMasuk`, `NoTelp`)
    VALUES (p_NewPegawaiID,p_userid, p_Nama, p_Jabatan, p_TanggalMasuk, p_NoTelp);

    COMMIT;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_InsertRekamMedisObat` (IN `p_IdRekamMedis` VARCHAR(15), IN `p_IdObat` VARCHAR(7), IN `p_Dosis` VARCHAR(50), IN `p_Frekuensi` VARCHAR(50), IN `p_LamaHari` INT, IN `p_Jumlah` DECIMAL(12,2), IN `p_CreatedBy` VARCHAR(50))   BEGIN
    DECLARE v_HargaSatuan DECIMAL(12,2);

    DECLARE EXIT HANDLER FOR SQLEXCEPTION
    BEGIN
        ROLLBACK;
        RESIGNAL;
    END;

    START TRANSACTION;

    IF NOT EXISTS (SELECT 1 FROM `RekamMedis` WHERE `IdRekamMedis` = p_IdRekamMedis) THEN
        SIGNAL SQLSTATE '45001' SET MESSAGE_TEXT = 'IdRekamMedis tidak ditemukan.';
    END IF;
    IF NOT EXISTS (SELECT 1 FROM `Obat` WHERE `IdObat` = p_IdObat) THEN
        SIGNAL SQLSTATE '45002' SET MESSAGE_TEXT = 'IdObat tidak ditemukan.';
    END IF;

    SELECT `Harga` INTO v_HargaSatuan FROM `Obat` WHERE `IdObat` = p_IdObat;

    INSERT INTO `RekamMedis_Obat` (`IdRekamMedis`, `IdObat`, `Dosis`, `Frekuensi`, `LamaHari`, `Jumlah`, `HargaSatuan`)
    VALUES (p_IdRekamMedis, p_IdObat, p_Dosis, p_Frekuensi, p_LamaHari, p_Jumlah, v_HargaSatuan);

    COMMIT;

    SELECT 1 AS Success, p_IdRekamMedis AS IdRekamMedis, p_IdObat AS IdObat;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_InsertRekamMedisTindakan` (IN `p_IdRekamMedis` VARCHAR(15), IN `p_IdTindakan` VARCHAR(10), IN `p_Jumlah` INT, IN `p_CreatedBy` VARCHAR(50))   BEGIN
    DECLARE v_Harga DECIMAL(12,2);

    DECLARE EXIT HANDLER FOR SQLEXCEPTION
    BEGIN
        ROLLBACK;
        RESIGNAL;
    END;

    START TRANSACTION;

    IF NOT EXISTS (SELECT 1 FROM `RekamMedis` WHERE `IdRekamMedis` = p_IdRekamMedis) THEN
        SIGNAL SQLSTATE '45001' SET MESSAGE_TEXT = 'IdRekamMedis tidak ditemukan.';
    END IF;
    IF NOT EXISTS (SELECT 1 FROM `Tindakan` WHERE `IdTindakan` = p_IdTindakan) THEN
        SIGNAL SQLSTATE '45002' SET MESSAGE_TEXT = 'IdTindakan tidak ditemukan.';
    END IF;

    SELECT `Harga` INTO v_Harga FROM `Tindakan` WHERE `IdTindakan` = p_IdTindakan;

    INSERT INTO `RekamMedis_Tindakan` (`IdRekamMedis`, `IdTindakan`, `Jumlah`, `Harga`)
    VALUES (p_IdRekamMedis, p_IdTindakan, p_Jumlah, v_Harga);

    COMMIT;

    SELECT 1 AS Success, p_IdRekamMedis AS IdRekamMedis, p_IdTindakan AS IdTindakan;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_InsertRekamMedis_AutoNumber` (IN `p_IdBooking` VARCHAR(11), IN `p_PasienID` VARCHAR(12), IN `p_DokterID` VARCHAR(5), IN `p_Tanggal` DATE, IN `p_Diagnosa` VARCHAR(200), IN `p_Catatan` VARCHAR(500), IN `p_CreatedBy` VARCHAR(50))   BEGIN
    DECLARE v_Tanggal DATE;
    DECLARE v_Tahun CHAR(4);
    DECLARE v_Urutan INT;
    DECLARE v_IdRekamMedis VARCHAR(15);

    DECLARE EXIT HANDLER FOR SQLEXCEPTION
    BEGIN
        ROLLBACK;
        RESIGNAL;
    END;

    START TRANSACTION;

    IF NOT EXISTS (SELECT 1 FROM `Booking` WHERE `IdBooking` = p_IdBooking) THEN
        SIGNAL SQLSTATE '45001' SET MESSAGE_TEXT = 'Booking tidak ditemukan.';
    END IF;
    IF NOT EXISTS (SELECT 1 FROM `Pasien` WHERE `PasienID` = p_PasienID) THEN
        SIGNAL SQLSTATE '45002' SET MESSAGE_TEXT = 'Pasien tidak ditemukan.';
    END IF;
    IF NOT EXISTS (SELECT 1 FROM `Pegawai` WHERE `PegawaiID` = p_DokterID) THEN
        SIGNAL SQLSTATE '45003' SET MESSAGE_TEXT = 'Dokter tidak ditemukan.';
    END IF;

    SET v_Tanggal = IFNULL(p_Tanggal, CURDATE());
    SET v_Tahun = CAST(YEAR(v_Tanggal) AS CHAR(4));

    SELECT COUNT(*) + 1 INTO v_Urutan
    FROM `RekamMedis`
    WHERE YEAR(`Tanggal`) = YEAR(v_Tanggal);

    SET v_IdRekamMedis = CONCAT('RM-', v_Tahun, '-', LPAD(v_Urutan, 4, '0'));

    INSERT INTO `RekamMedis` (`IdRekamMedis`, `IdBooking`, `PasienID`, `DokterID`, `Tanggal`, `Diagnosa`, `Catatan`)
    VALUES (v_IdRekamMedis, p_IdBooking, p_PasienID, p_DokterID, v_Tanggal, p_Diagnosa, p_Catatan);

    COMMIT;

    SELECT 1 AS Success, v_IdRekamMedis AS IdRekamMedis;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `Sp_UpdateJadwalStatus` (IN `p_IdJadwal` VARCHAR(20), IN `p_NewStatus` VARCHAR(20))   BEGIN
    DECLARE EXIT HANDLER FOR SQLEXCEPTION
    BEGIN
        ROLLBACK;
        RESIGNAL;
    END;

    START TRANSACTION;

    IF NOT EXISTS (SELECT 1 FROM `Jadwal` WHERE `IdJadwal` = p_IdJadwal) THEN
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'IdJadwal tidak ditemukan.';
    END IF;

    UPDATE `Jadwal` SET `Status` = p_NewStatus WHERE `IdJadwal` = p_IdJadwal;

    COMMIT;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_UpdateObatTambahStok` (IN `p_IdObat` VARCHAR(7), IN `p_JumlahTambah` DECIMAL(12,2), IN `p_CreatedBy` VARCHAR(50))   BEGIN
    DECLARE v_StokSebelum DECIMAL(12,2);
    DECLARE v_StokSesudah DECIMAL(12,2);

    DECLARE EXIT HANDLER FOR SQLEXCEPTION
    BEGIN
        ROLLBACK;
        RESIGNAL;
    END;

    START TRANSACTION;

    IF NOT EXISTS (SELECT 1 FROM `Obat` WHERE `IdObat` = p_IdObat) THEN
        SIGNAL SQLSTATE '45030' SET MESSAGE_TEXT = 'IdObat tidak ditemukan.';
    END IF;

    SELECT `Stok` INTO v_StokSebelum FROM `Obat` WHERE `IdObat` = p_IdObat FOR UPDATE;

    UPDATE `Obat` SET `Stok` = `Stok` + p_JumlahTambah WHERE `IdObat` = p_IdObat;

    SELECT `Stok` INTO v_StokSesudah FROM `Obat` WHERE `IdObat` = p_IdObat;

    INSERT INTO `Obat_Log` (`IdObat`, `Tanggal`, `Aksi`, `Jumlah`, `StokSebelum`, `StokSesudah`, `CreatedBy`)
    VALUES (p_IdObat, NOW(), 'MASUK', p_JumlahTambah, v_StokSebelum, v_StokSesudah, p_CreatedBy);

    COMMIT;

    SELECT 1 AS Success, p_IdObat AS IdObat, p_JumlahTambah AS JumlahDitambah, v_StokSesudah AS StokBaru;
END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `booking`
--

CREATE TABLE `booking` (
  `IdBooking` varchar(11) NOT NULL,
  `IdJadwal` varchar(11) NOT NULL,
  `PasienID` varchar(12) NOT NULL,
  `TanggalBooking` datetime DEFAULT current_timestamp(),
  `Status` varchar(20) DEFAULT 'PRESENT'
) ;

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `jadwal`
--

CREATE TABLE `jadwal` (
  `IdJadwal` varchar(11) NOT NULL,
  `IdDokter` varchar(5) NOT NULL,
  `Tanggal` date NOT NULL,
  `JamMulai` time NOT NULL,
  `JamAkhir` time NOT NULL,
  `Status` varchar(20) DEFAULT 'Available',
  `Kapasitas` int(11) NOT NULL
) ;

-- --------------------------------------------------------

--
-- Table structure for table `jenisobat`
--

CREATE TABLE `jenisobat` (
  `JenisObatID` int(11) NOT NULL,
  `NamaJenis` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) UNSIGNED NOT NULL,
  `reserved_at` int(10) UNSIGNED DEFAULT NULL,
  `available_at` int(10) UNSIGNED NOT NULL,
  `created_at` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `total_jobs` int(11) NOT NULL,
  `pending_jobs` int(11) NOT NULL,
  `failed_jobs` int(11) NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` mediumtext DEFAULT NULL,
  `cancelled_at` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `finished_at` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2025_12_26_155609_add_user_id_to_pegawai_table', 2);

-- --------------------------------------------------------

--
-- Table structure for table `obat`
--

CREATE TABLE `obat` (
  `IdObat` varchar(7) NOT NULL,
  `IdJenisObat` int(11) NOT NULL,
  `NamaObat` varchar(100) NOT NULL,
  `Satuan` varchar(20) DEFAULT NULL,
  `Harga` decimal(12,2) NOT NULL,
  `Stok` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `obat_log`
--

CREATE TABLE `obat_log` (
  `LogID` int(11) NOT NULL,
  `IdObat` varchar(7) NOT NULL,
  `Tanggal` datetime NOT NULL DEFAULT current_timestamp(),
  `Aksi` varchar(50) NOT NULL,
  `Jumlah` decimal(12,2) NOT NULL,
  `StokSebelum` decimal(12,2) NOT NULL,
  `StokSesudah` decimal(12,2) NOT NULL,
  `IdRekamMedis` varchar(15) DEFAULT NULL,
  `CreatedBy` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pasien`
--

CREATE TABLE `pasien` (
  `PasienID` varchar(12) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `Nama` varchar(100) NOT NULL,
  `TanggalLahir` date DEFAULT NULL,
  `Alamat` varchar(100) DEFAULT NULL,
  `NoTelp` varchar(20) DEFAULT NULL,
  `JenisKelamin` char(1) DEFAULT NULL
) ;

--
-- Dumping data for table `pasien`
--

INSERT INTO `pasien` (`PasienID`, `user_id`, `Nama`, `TanggalLahir`, `Alamat`, `NoTelp`, `JenisKelamin`) VALUES
('P-2025-00001', 3, 'Errvin Junius', '0000-00-00', 'ajl gatau', '089723547869', 'L');

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pegawai`
--

CREATE TABLE `pegawai` (
  `PegawaiID` varchar(5) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `Nama` varchar(100) NOT NULL,
  `Jabatan` varchar(20) DEFAULT NULL,
  `TanggalMasuk` date DEFAULT NULL,
  `NoTelp` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `pegawai`
--

INSERT INTO `pegawai` (`PegawaiID`, `user_id`, `Nama`, `Jabatan`, `TanggalMasuk`, `NoTelp`) VALUES
('A-001', 1, 'Hans Maulana Budiputra', 'admin', '2025-12-26', '081234567890'),
('D-001', 2, 'Rafael', 'dokter gigi', '0000-00-00', '089878982343');

-- --------------------------------------------------------

--
-- Table structure for table `pembayaran`
--

CREATE TABLE `pembayaran` (
  `IdPembayaran` varchar(15) NOT NULL,
  `IdRekamMedis` varchar(15) NOT NULL,
  `PasienID` varchar(12) NOT NULL,
  `TanggalPembayaran` datetime DEFAULT current_timestamp(),
  `Metode` varchar(20) NOT NULL,
  `TotalBayar` decimal(12,2) NOT NULL,
  `Status` varchar(20) DEFAULT 'UNPAID'
) ;

-- --------------------------------------------------------

--
-- Table structure for table `rekammedis`
--

CREATE TABLE `rekammedis` (
  `IdRekamMedis` varchar(15) NOT NULL,
  `IdBooking` varchar(11) NOT NULL,
  `PasienID` varchar(12) NOT NULL,
  `DokterID` varchar(5) NOT NULL,
  `Tanggal` date DEFAULT curdate(),
  `Diagnosa` varchar(200) NOT NULL,
  `Catatan` varchar(500) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `rekammedis_obat`
--

CREATE TABLE `rekammedis_obat` (
  `IdRekamMedis` varchar(15) NOT NULL,
  `IdObat` varchar(7) NOT NULL,
  `Dosis` varchar(50) DEFAULT NULL,
  `Frekuensi` varchar(50) DEFAULT NULL,
  `LamaHari` int(11) DEFAULT NULL,
  `Jumlah` decimal(12,2) NOT NULL,
  `HargaSatuan` decimal(12,2) NOT NULL,
  `SubTotal` decimal(24,4) GENERATED ALWAYS AS (`Jumlah` * `HargaSatuan`) STORED
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `rekammedis_tindakan`
--

CREATE TABLE `rekammedis_tindakan` (
  `IdRekamMedis` varchar(15) NOT NULL,
  `IdTindakan` varchar(10) NOT NULL,
  `Jumlah` int(11) DEFAULT 1,
  `Harga` decimal(12,2) NOT NULL,
  `SubTotal` decimal(24,4) GENERATED ALWAYS AS (`Jumlah` * `Harga`) STORED
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tindakan`
--

CREATE TABLE `tindakan` (
  `IdTindakan` varchar(10) NOT NULL,
  `NamaTindakan` varchar(100) NOT NULL,
  `Harga` decimal(12,2) NOT NULL,
  `Durasi` time DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `role` varchar(20) NOT NULL DEFAULT 'pasien'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`, `role`) VALUES
(1, 'Hans Maulana Budiputra', 'hans@klinik.admin', NULL, '$2y$12$ElQFh1jzDEFSBtb9Lyo6Eui8ChX7fou5Zd/pP8wbT.85TE9aUmjFy', NULL, '2025-12-26 07:30:42', '2025-12-26 07:30:42', 'admin'),
(2, 'Rafael', 'rafael@klinik.dokter', NULL, '$2y$12$CVXyWbX8N.KHBC/3IgbxneA0hLUa0BS2nTP2/D28vgBANNnxnpKdi', NULL, '2025-12-26 07:30:42', '2025-12-26 07:30:42', 'dokter'),
(3, 'Errvin junius', 'errvin@klinik.pasien', NULL, '$2y$12$EPjOiy2SMrWHbeNvmDWe2urqEDx.RTxrvR80YB6bctw6Fk7QeJOL6', NULL, '2025-12-26 07:30:55', '2025-12-26 07:30:55', 'pasien');

-- --------------------------------------------------------

--
-- Stand-in structure for view `view_jadwaldokter`
-- (See below for the actual view)
--
CREATE TABLE `view_jadwaldokter` (
`IdJadwal` varchar(11)
,`IdDokter` varchar(5)
,`IdBooking` varchar(11)
,`Nama Pasien` varchar(100)
,`Tanggal` date
,`JamMulai` time
,`JamAkhir` time
,`Nama Dokter` varchar(100)
,`Status` varchar(20)
);

-- --------------------------------------------------------

--
-- Stand-in structure for view `view_jadwalpasien`
-- (See below for the actual view)
--
CREATE TABLE `view_jadwalpasien` (
`IdJadwal` varchar(11)
,`IdDokter` varchar(5)
,`Tanggal` date
,`JamMulai` time
,`JamAkhir` time
,`Status` varchar(20)
,`NamaDokter` varchar(100)
,`Jabatan` varchar(20)
);

-- --------------------------------------------------------

--
-- Structure for view `view_jadwaldokter`
--
DROP TABLE IF EXISTS `view_jadwaldokter`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `view_jadwaldokter`  AS SELECT `j`.`IdJadwal` AS `IdJadwal`, `j`.`IdDokter` AS `IdDokter`, `b`.`IdBooking` AS `IdBooking`, `p`.`Nama` AS `Nama Pasien`, `j`.`Tanggal` AS `Tanggal`, `j`.`JamMulai` AS `JamMulai`, `j`.`JamAkhir` AS `JamAkhir`, `pg`.`Nama` AS `Nama Dokter`, `b`.`Status` AS `Status` FROM (((`jadwal` `j` join `booking` `b` on(`j`.`IdJadwal` = `b`.`IdJadwal`)) join `pasien` `p` on(`b`.`PasienID` = `p`.`PasienID`)) join `pegawai` `pg` on(`j`.`IdDokter` = `pg`.`PegawaiID`)) ;

-- --------------------------------------------------------

--
-- Structure for view `view_jadwalpasien`
--
DROP TABLE IF EXISTS `view_jadwalpasien`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `view_jadwalpasien`  AS SELECT `j`.`IdJadwal` AS `IdJadwal`, `j`.`IdDokter` AS `IdDokter`, `j`.`Tanggal` AS `Tanggal`, `j`.`JamMulai` AS `JamMulai`, `j`.`JamAkhir` AS `JamAkhir`, `j`.`Status` AS `Status`, `p`.`Nama` AS `NamaDokter`, `p`.`Jabatan` AS `Jabatan` FROM (`jadwal` `j` join `pegawai` `p` on(`j`.`IdDokter` = `p`.`PegawaiID`)) ;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `booking`
--
ALTER TABLE `booking`
  ADD PRIMARY KEY (`IdBooking`),
  ADD KEY `FK_Booking_Jadwal` (`IdJadwal`),
  ADD KEY `FK_Booking_Pasien` (`PasienID`);

--
-- Indexes for table `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `jadwal`
--
ALTER TABLE `jadwal`
  ADD PRIMARY KEY (`IdJadwal`),
  ADD KEY `FK_Jadwal_Pegawai` (`IdDokter`);

--
-- Indexes for table `jenisobat`
--
ALTER TABLE `jenisobat`
  ADD PRIMARY KEY (`JenisObatID`),
  ADD UNIQUE KEY `NamaJenis` (`NamaJenis`);

--
-- Indexes for table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Indexes for table `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `obat`
--
ALTER TABLE `obat`
  ADD PRIMARY KEY (`IdObat`),
  ADD KEY `FK_Obat_JenisObat` (`IdJenisObat`);

--
-- Indexes for table `obat_log`
--
ALTER TABLE `obat_log`
  ADD PRIMARY KEY (`LogID`);

--
-- Indexes for table `pasien`
--
ALTER TABLE `pasien`
  ADD PRIMARY KEY (`PasienID`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `pegawai`
--
ALTER TABLE `pegawai`
  ADD PRIMARY KEY (`PegawaiID`),
  ADD KEY `pegawai_user_id_foreign` (`user_id`);

--
-- Indexes for table `pembayaran`
--
ALTER TABLE `pembayaran`
  ADD PRIMARY KEY (`IdPembayaran`),
  ADD KEY `FK_Pembayaran_Pasien` (`PasienID`),
  ADD KEY `FK_Pembayaran_RekamMedis` (`IdRekamMedis`);

--
-- Indexes for table `rekammedis`
--
ALTER TABLE `rekammedis`
  ADD PRIMARY KEY (`IdRekamMedis`),
  ADD KEY `FK_RekamMedis_Booking` (`IdBooking`),
  ADD KEY `FK_RekamMedis_Pasien` (`PasienID`),
  ADD KEY `FK_RekamMedis_Pegawai` (`DokterID`);

--
-- Indexes for table `rekammedis_obat`
--
ALTER TABLE `rekammedis_obat`
  ADD PRIMARY KEY (`IdRekamMedis`,`IdObat`),
  ADD KEY `IX_RekamMedis_Obat_IdObat` (`IdObat`),
  ADD KEY `IX_RekamMedis_Obat_IdRekamMedis` (`IdRekamMedis`);

--
-- Indexes for table `rekammedis_tindakan`
--
ALTER TABLE `rekammedis_tindakan`
  ADD PRIMARY KEY (`IdRekamMedis`,`IdTindakan`),
  ADD KEY `FK_RMT_Tindakan` (`IdTindakan`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `tindakan`
--
ALTER TABLE `tindakan`
  ADD PRIMARY KEY (`IdTindakan`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `jenisobat`
--
ALTER TABLE `jenisobat`
  MODIFY `JenisObatID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `obat_log`
--
ALTER TABLE `obat_log`
  MODIFY `LogID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `booking`
--
ALTER TABLE `booking`
  ADD CONSTRAINT `FK_Booking_Jadwal` FOREIGN KEY (`IdJadwal`) REFERENCES `jadwal` (`IdJadwal`),
  ADD CONSTRAINT `FK_Booking_Pasien` FOREIGN KEY (`PasienID`) REFERENCES `pasien` (`PasienID`);

--
-- Constraints for table `jadwal`
--
ALTER TABLE `jadwal`
  ADD CONSTRAINT `FK_Jadwal_Pegawai` FOREIGN KEY (`IdDokter`) REFERENCES `pegawai` (`PegawaiID`);

--
-- Constraints for table `obat`
--
ALTER TABLE `obat`
  ADD CONSTRAINT `FK_Obat_JenisObat` FOREIGN KEY (`IdJenisObat`) REFERENCES `jenisobat` (`JenisObatID`);

--
-- Constraints for table `pegawai`
--
ALTER TABLE `pegawai`
  ADD CONSTRAINT `pegawai_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `pembayaran`
--
ALTER TABLE `pembayaran`
  ADD CONSTRAINT `FK_Pembayaran_Pasien` FOREIGN KEY (`PasienID`) REFERENCES `pasien` (`PasienID`),
  ADD CONSTRAINT `FK_Pembayaran_RekamMedis` FOREIGN KEY (`IdRekamMedis`) REFERENCES `rekammedis` (`IdRekamMedis`);

--
-- Constraints for table `rekammedis`
--
ALTER TABLE `rekammedis`
  ADD CONSTRAINT `FK_RekamMedis_Booking` FOREIGN KEY (`IdBooking`) REFERENCES `booking` (`IdBooking`),
  ADD CONSTRAINT `FK_RekamMedis_Pasien` FOREIGN KEY (`PasienID`) REFERENCES `pasien` (`PasienID`),
  ADD CONSTRAINT `FK_RekamMedis_Pegawai` FOREIGN KEY (`DokterID`) REFERENCES `pegawai` (`PegawaiID`);

--
-- Constraints for table `rekammedis_obat`
--
ALTER TABLE `rekammedis_obat`
  ADD CONSTRAINT `FK_RekamMedisObat_Obat` FOREIGN KEY (`IdObat`) REFERENCES `obat` (`IdObat`),
  ADD CONSTRAINT `FK_RekamMedisObat_RekamMedis` FOREIGN KEY (`IdRekamMedis`) REFERENCES `rekammedis` (`IdRekamMedis`);

--
-- Constraints for table `rekammedis_tindakan`
--
ALTER TABLE `rekammedis_tindakan`
  ADD CONSTRAINT `FK_RMT_RekamMedis` FOREIGN KEY (`IdRekamMedis`) REFERENCES `rekammedis` (`IdRekamMedis`),
  ADD CONSTRAINT `FK_RMT_Tindakan` FOREIGN KEY (`IdTindakan`) REFERENCES `tindakan` (`IdTindakan`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
