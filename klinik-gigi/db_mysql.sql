-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 28, 2025 at 11:26 AM
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
    
    IF NOT EXISTS (SELECT 1 FROM Booking WHERE IdBooking = p_IdBooking) THEN
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'IdBooking tidak ditemukan.';
    END IF;

    UPDATE Booking
    SET Status = 'CANCELLED'
    WHERE IdBooking = p_IdBooking;
    
    COMMIT;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `Sp_InsertBooking` (IN `p_IdJadwal` VARCHAR(20), IN `p_IdPasien` VARCHAR(20), IN `p_TanggalBooking` DATETIME, IN `p_Status` VARCHAR(20), OUT `p_NewIdBooking` VARCHAR(20))   BEGIN
    DECLARE v_Kapasitas INT;
    DECLARE v_JadwalStatus VARCHAR(50);
    DECLARE v_JumlahBookingAktif INT;
    DECLARE v_refDate DATE;
    DECLARE v_yy CHAR(2);
    DECLARE v_mm CHAR(2);
    DECLARE v_prefix VARCHAR(20);
    DECLARE v_lastSeq INT;
    DECLARE v_seqStr CHAR(4);
    
    DECLARE EXIT HANDLER FOR SQLEXCEPTION
    BEGIN
        ROLLBACK;
        RESIGNAL;
    END;
    
    START TRANSACTION;
    
    SELECT Kapasitas, Status INTO v_Kapasitas, v_JadwalStatus
    FROM Jadwal 
    WHERE IdJadwal = p_IdJadwal FOR UPDATE;
    
    IF v_Kapasitas IS NULL THEN
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'IdJadwal tidak ditemukan.';
    END IF;
    
    IF UPPER(v_JadwalStatus) <> 'AVAILABLE' THEN
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Jadwal tidak tersedia. Status harus Available.';
    END IF;
    
    SELECT COUNT(1) INTO v_JumlahBookingAktif
    FROM Booking 
    WHERE IdJadwal = p_IdJadwal AND COALESCE(Status, '') <> 'CANCELLED';
    
    IF v_JumlahBookingAktif >= v_Kapasitas THEN
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Kapasitas jadwal sudah penuh. Tidak bisa melakukan booking.';
    END IF;
    
    IF EXISTS (
        SELECT 1 FROM Booking
        WHERE IdJadwal = p_IdJadwal
            AND PasienID = p_IdPasien
            AND COALESCE(Status, '') <> 'CANCELLED'
    ) THEN
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Pasien sudah memiliki booking aktif pada jadwal ini.';
    END IF;
    
    SET v_refDate = COALESCE(DATE(p_TanggalBooking), CURDATE());
    SET v_yy = DATE_FORMAT(v_refDate, '%y');
    SET v_mm = DATE_FORMAT(v_refDate, '%m');
    SET v_prefix = CONCAT('B-', v_yy, v_mm, '-');
    
    SELECT COALESCE(MAX(CAST(SUBSTRING(IdBooking, 9) AS UNSIGNED)), 0) INTO v_lastSeq
    FROM Booking 
    WHERE IdBooking LIKE CONCAT(v_prefix, '%');
    
    SET v_lastSeq = v_lastSeq + 1;
    SET v_seqStr = LPAD(v_lastSeq, 4, '0');
    SET p_NewIdBooking = CONCAT(v_prefix, v_seqStr);
    
    INSERT INTO Booking (IdBooking, IdJadwal, PasienID, TanggalBooking, Status)
    VALUES (p_NewIdBooking, p_IdJadwal, p_IdPasien, p_TanggalBooking, COALESCE(p_Status, 'PRESENT'));
    
    COMMIT;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `Sp_InsertJadwal` (IN `p_IdDokter` VARCHAR(10), IN `p_Tanggal` DATE, IN `p_Sesi` VARCHAR(10), IN `p_Status` VARCHAR(20), OUT `p_NewIdJadwal` VARCHAR(20))   BEGIN
    DECLARE v_JamMulai TIME;
    DECLARE v_JamAkhir TIME;
    DECLARE v_Jabatan VARCHAR(100);
    DECLARE v_Kapasitas INT;
    DECLARE v_yy CHAR(2);
    DECLARE v_mm CHAR(2);
    DECLARE v_prefix VARCHAR(20);
    DECLARE v_lastSeq INT;
    DECLARE v_seqStr CHAR(4);
    
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
    
    SELECT Jabatan INTO v_Jabatan FROM Pegawai WHERE PegawaiID = p_IdDokter;
    
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
    
    SELECT COALESCE(MAX(CAST(SUBSTRING(IdJadwal, 9) AS UNSIGNED)), 0) INTO v_lastSeq
    FROM Jadwal 
    WHERE IdJadwal LIKE CONCAT(v_prefix, '%');
    
    SET v_lastSeq = v_lastSeq + 1;
    SET v_seqStr = LPAD(v_lastSeq, 4, '0');
    SET p_NewIdJadwal = CONCAT(v_prefix, v_seqStr);
    
    INSERT INTO Jadwal (IdJadwal, IdDokter, Tanggal, JamMulai, JamAkhir, Status, Kapasitas)
    VALUES (p_NewIdJadwal, p_IdDokter, p_Tanggal, v_JamMulai, v_JamAkhir, COALESCE(p_Status, 'Available'), v_Kapasitas);
    
    COMMIT;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `Sp_InsertJenisObat` (IN `p_NamaJenis` VARCHAR(50))   BEGIN
    DECLARE EXIT HANDLER FOR SQLEXCEPTION
    BEGIN
        ROLLBACK;
        RESIGNAL;
    END;
    
    START TRANSACTION;
    
    IF EXISTS (SELECT 1 FROM JenisObat WHERE NamaJenis = p_NamaJenis) THEN
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Jenis obat sudah terdaftar.';
    END IF;
    
    INSERT INTO JenisObat (NamaJenis) VALUES (p_NamaJenis);
    
    COMMIT;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `Sp_InsertObat` (IN `p_NamaObat` VARCHAR(100), IN `p_Satuan` VARCHAR(20), IN `p_Harga` DECIMAL(12,2), IN `p_Stok` INT, IN `p_IdJenisObat` INT, OUT `p_NewIdObat` VARCHAR(10))   BEGIN
    DECLARE v_prefix VARCHAR(2) DEFAULT 'O-';
    DECLARE v_lastSeq INT;
    DECLARE v_seqStr CHAR(5);
    
    DECLARE EXIT HANDLER FOR SQLEXCEPTION
    BEGIN
        ROLLBACK;
        RESIGNAL;
    END;
    
    START TRANSACTION;
    
    IF NOT EXISTS (SELECT 1 FROM JenisObat WHERE JenisObatID = p_IdJenisObat) THEN
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'IdJenisObat tidak valid.';
    END IF;
    
    SELECT COALESCE(MAX(CAST(SUBSTRING(IdObat, 3) AS UNSIGNED)), 0) INTO v_lastSeq
    FROM Obat 
    WHERE IdObat LIKE CONCAT(v_prefix, '%');
    
    SET v_lastSeq = v_lastSeq + 1;
    SET v_seqStr = LPAD(v_lastSeq, 5, '0');
    SET p_NewIdObat = CONCAT(v_prefix, v_seqStr);
    
    INSERT INTO Obat (IdObat, IdJenisObat, NamaObat, Satuan, Harga, Stok)
    VALUES (p_NewIdObat, p_IdJenisObat, p_NamaObat, p_Satuan, p_Harga, p_Stok);
    
    COMMIT;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `Sp_InsertPasien` (IN `p_user_id` BIGINT, IN `p_Nama` VARCHAR(100), IN `p_TanggalLahir` DATE, IN `p_Alamat` VARCHAR(200), IN `p_NoTelp` VARCHAR(20), IN `p_JenisKelamin` VARCHAR(1), OUT `p_NewPasienID` VARCHAR(20))   BEGIN
    DECLARE v_lastSeq INT;
    DECLARE v_seqStr CHAR(5);
    DECLARE v_tahun CHAR(4);
    DECLARE v_prefix VARCHAR(10);
    
    DECLARE EXIT HANDLER FOR SQLEXCEPTION
    BEGIN
        ROLLBACK;
        RESIGNAL;
    END;
    
    START TRANSACTION;
    
    SET v_tahun = YEAR(CURDATE());
    SET v_prefix = CONCAT('P-', v_tahun, '-');
    
    SELECT COALESCE(MAX(CAST(SUBSTRING(PasienID, 8) AS UNSIGNED)), 0) INTO v_lastSeq
    FROM Pasien 
    WHERE PasienID LIKE CONCAT(v_prefix, '%');
    
    SET v_lastSeq = v_lastSeq + 1;
    SET v_seqStr = LPAD(v_lastSeq, 5, '0');
    SET p_NewPasienID = CONCAT(v_prefix, v_seqStr);
    
    INSERT INTO Pasien (PasienID, user_id, Nama, TanggalLahir, Alamat, NoTelp, JenisKelamin)
    VALUES (p_NewPasienID, p_user_id, p_Nama, p_TanggalLahir, p_Alamat, p_NoTelp, p_JenisKelamin);
    
    COMMIT;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `Sp_InsertPegawai` (IN `p_Nama` VARCHAR(100), IN `p_Jabatan` VARCHAR(50), IN `p_TanggalMasuk` DATE, IN `p_NoTelp` VARCHAR(20), IN `p_userid` INT, OUT `p_NewPegawaiID` VARCHAR(10))   BEGIN
    DECLARE v_Prefix CHAR(1);
    DECLARE v_lastSeq INT;
    DECLARE v_seqStr CHAR(3);
    
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
    
    SELECT COALESCE(MAX(CAST(SUBSTRING(PegawaiID, 3) AS UNSIGNED)), 0) INTO v_lastSeq
    FROM Pegawai 
    WHERE PegawaiID LIKE CONCAT(v_Prefix, '-%');
    
    SET v_lastSeq = v_lastSeq + 1;
    SET v_seqStr = LPAD(v_lastSeq, 3, '0');
    SET p_NewPegawaiID = CONCAT(v_Prefix, '-', v_seqStr);
    
    INSERT INTO Pegawai (PegawaiID, user_id, Nama, Jabatan, TanggalMasuk, NoTelp)
    VALUES (p_NewPegawaiID, p_userid, p_Nama, p_Jabatan, p_TanggalMasuk, p_NoTelp);
    
    COMMIT;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `Sp_InsertRekamMedisObat` (IN `p_IdRekamMedis` VARCHAR(15), IN `p_IdObat` VARCHAR(7), IN `p_Dosis` VARCHAR(50), IN `p_Frekuensi` VARCHAR(50), IN `p_LamaHari` INT, IN `p_Jumlah` DECIMAL(12,2), IN `p_CreatedBy` VARCHAR(50))   BEGIN
    DECLARE v_HargaSatuan DECIMAL(12,2);
    
    DECLARE EXIT HANDLER FOR SQLEXCEPTION
    BEGIN
        ROLLBACK;
        RESIGNAL;
    END;
    
    START TRANSACTION;
    
    IF NOT EXISTS (SELECT 1 FROM RekamMedis WHERE IdRekamMedis = p_IdRekamMedis) THEN
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'IdRekamMedis tidak ditemukan.';
    END IF;
    
    IF NOT EXISTS (SELECT 1 FROM Obat WHERE IdObat = p_IdObat) THEN
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'IdObat tidak ditemukan.';
    END IF;
    
    SELECT Harga INTO v_HargaSatuan FROM Obat WHERE IdObat = p_IdObat;
    
    INSERT INTO RekamMedis_Obat (IdRekamMedis, IdObat, Dosis, Frekuensi, LamaHari, Jumlah, HargaSatuan)
    VALUES (p_IdRekamMedis, p_IdObat, p_Dosis, p_Frekuensi, p_LamaHari, p_Jumlah, v_HargaSatuan);
    
    COMMIT;
    
    SELECT 1 AS Success, p_IdRekamMedis AS IdRekamMedis, p_IdObat AS IdObat;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `Sp_InsertRekamMedisTindakan` (IN `p_IdRekamMedis` VARCHAR(15), IN `p_IdTindakan` VARCHAR(10), IN `p_Jumlah` INT, IN `p_CreatedBy` VARCHAR(50))   BEGIN
    DECLARE v_Harga DECIMAL(12,2);
    
    DECLARE EXIT HANDLER FOR SQLEXCEPTION
    BEGIN
        ROLLBACK;
        RESIGNAL;
    END;
    
    START TRANSACTION;
    
    IF NOT EXISTS (SELECT 1 FROM RekamMedis WHERE IdRekamMedis = p_IdRekamMedis) THEN
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'IdRekamMedis tidak ditemukan.';
    END IF;
    
    IF NOT EXISTS (SELECT 1 FROM Tindakan WHERE IdTindakan = p_IdTindakan) THEN
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'IdTindakan tidak ditemukan.';
    END IF;
    
    SELECT Harga INTO v_Harga FROM Tindakan WHERE IdTindakan = p_IdTindakan;
    
    INSERT INTO RekamMedis_Tindakan (IdRekamMedis, IdTindakan, Jumlah, Harga)
    VALUES (p_IdRekamMedis, p_IdTindakan, p_Jumlah, v_Harga);
    
    COMMIT;
    
    SELECT 1 AS Success, p_IdRekamMedis AS IdRekamMedis, p_IdTindakan AS IdTindakan;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `Sp_InsertRekamMedis_AutoNumber` (IN `p_IdBooking` VARCHAR(11), IN `p_PasienID` VARCHAR(12), IN `p_DokterID` VARCHAR(5), IN `p_Tanggal` DATE, IN `p_Diagnosa` VARCHAR(200), IN `p_Catatan` VARCHAR(500), IN `p_CreatedBy` VARCHAR(50))   BEGIN
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
    
    IF NOT EXISTS (SELECT 1 FROM Booking WHERE IdBooking = p_IdBooking) THEN
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Booking tidak ditemukan.';
    END IF;
    
    IF NOT EXISTS (SELECT 1 FROM Pasien WHERE PasienID = p_PasienID) THEN
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Pasien tidak ditemukan.';
    END IF;
    
    IF NOT EXISTS (SELECT 1 FROM Pegawai WHERE PegawaiID = p_DokterID) THEN
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Dokter tidak ditemukan.';
    END IF;
    
    SET v_Tanggal = COALESCE(p_Tanggal, CURDATE());
    SET v_Tahun = YEAR(v_Tanggal);
    
    SELECT COUNT(*) + 1 INTO v_Urutan
    FROM RekamMedis
    WHERE YEAR(Tanggal) = YEAR(v_Tanggal);
    
    SET v_IdRekamMedis = CONCAT('RM-', v_Tahun, '-', LPAD(v_Urutan, 4, '0'));
    
    INSERT INTO RekamMedis (IdRekamMedis, IdBooking, PasienID, DokterID, Tanggal, Diagnosa, Catatan)
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
    
    IF NOT EXISTS (SELECT 1 FROM Jadwal WHERE IdJadwal = p_IdJadwal) THEN
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'IdJadwal tidak ditemukan.';
    END IF;
    
    UPDATE Jadwal SET Status = p_NewStatus WHERE IdJadwal = p_IdJadwal;
    
    COMMIT;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `Sp_UpdateObatTambahStok` (IN `p_IdObat` VARCHAR(7), IN `p_JumlahTambah` DECIMAL(12,2), IN `p_CreatedBy` VARCHAR(50))   BEGIN
    DECLARE v_StokSebelum DECIMAL(12,2);
    DECLARE v_StokSesudah DECIMAL(12,2);
    
    DECLARE EXIT HANDLER FOR SQLEXCEPTION
    BEGIN
        ROLLBACK;
        RESIGNAL;
    END;
    
    START TRANSACTION;
    
    IF NOT EXISTS (SELECT 1 FROM Obat WHERE IdObat = p_IdObat) THEN
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'IdObat tidak ditemukan.';
    END IF;
    
    SELECT Stok INTO v_StokSebelum FROM Obat WHERE IdObat = p_IdObat FOR UPDATE;
    
    UPDATE Obat SET Stok = Stok + p_JumlahTambah WHERE IdObat = p_IdObat;
    
    SELECT Stok INTO v_StokSesudah FROM Obat WHERE IdObat = p_IdObat;
    
    INSERT INTO Obat_Log (IdObat, Tanggal, Aksi, Jumlah, StokSebelum, StokSesudah, CreatedBy)
    VALUES (p_IdObat, NOW(), 'MASUK', p_JumlahTambah, v_StokSebelum, v_StokSesudah, p_CreatedBy);
    
    COMMIT;
    
    SELECT 1 AS Success, p_IdObat AS IdObat, p_JumlahTambah AS JumlahDitambah, v_StokSesudah AS StokBaru;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `Sp_UpdateStatusBooking` (IN `p_IdBooking` VARCHAR(20), IN `p_NewStatus` VARCHAR(20))   BEGIN
    DECLARE v_CurrentStatus VARCHAR(20);
    DECLARE v_IdJadwal VARCHAR(20);
    DECLARE v_JadwalStatus VARCHAR(20);
    
    DECLARE EXIT HANDLER FOR SQLEXCEPTION
    BEGIN
        ROLLBACK;
        RESIGNAL;
    END;
    
    START TRANSACTION;
    
    -- Cek apakah booking ada
    IF NOT EXISTS (SELECT 1 FROM Booking WHERE IdBooking = p_IdBooking) THEN
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'IdBooking tidak ditemukan.';
    END IF;
    
    -- Ambil status saat ini dan IdJadwal
    SELECT Status, IdJadwal INTO v_CurrentStatus, v_IdJadwal
    FROM Booking 
    WHERE IdBooking = p_IdBooking
    FOR UPDATE;
    
    -- Validasi perubahan status
    IF v_CurrentStatus = 'CANCELLED' AND p_NewStatus != 'CANCELLED' THEN
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Booking yang sudah dibatalkan tidak dapat diaktifkan kembali.';
    END IF;
    
    IF v_CurrentStatus = 'COMPLETED' AND p_NewStatus != 'COMPLETED' THEN
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Booking yang sudah selesai tidak dapat diubah.';
    END IF;
    
    -- Jika mengubah ke CANCELLED, cek apakah masih dalam batas waktu (opsional)
    IF p_NewStatus = 'CANCELLED' AND v_CurrentStatus != 'CANCELLED' THEN
        -- Cek apakah jadwal masih tersedia untuk booking lain (hanya untuk jadwal yang belum lewat)
        -- Anda bisa menambahkan logika tambahan di sini jika perlu
        SELECT Status INTO v_JadwalStatus
        FROM Jadwal
        WHERE IdJadwal = v_IdJadwal;
        
        -- Jika jadwal masih AVAILABLE, biarkan status jadwal tetap
        -- Jika tidak, mungkin ingin mengembalikan slot
        -- Contoh: Update kapasitas jadwal jika diperlukan
    END IF;
    
    -- Update status booking
    UPDATE Booking 
    SET Status = p_NewStatus
    WHERE IdBooking = p_IdBooking;
    
    -- Jika status berubah menjadi CANCELLED, log aktivitas (opsional)
    IF p_NewStatus = 'CANCELLED' THEN
        -- Anda bisa menambahkan log ke tabel log/audit di sini
        -- INSERT INTO LogBooking (IdBooking, Aksi, Waktu) VALUES (p_IdBooking, 'CANCELLED', NOW());
        SELECT 1; -- Placeholder untuk log
    END IF;
    
    COMMIT;
    
    -- Return success message
    SELECT 1 AS Success, 
           p_IdBooking AS IdBooking, 
           v_CurrentStatus AS OldStatus, 
           p_NewStatus AS NewStatus,
           'Status booking berhasil diupdate' AS Message;
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `booking`
--

INSERT INTO `booking` (`IdBooking`, `IdJadwal`, `PasienID`, `TanggalBooking`, `Status`) VALUES
('B-2505-0001', 'J-2505-0001', 'P-2025-00001', '2025-04-28 10:00:00', 'COMPLETED'),
('B-2505-0002', 'J-2505-0003', 'P-2025-00002', '2025-04-30 14:30:00', 'COMPLETED'),
('B-2505-0003', 'J-2505-0005', 'P-2025-00003', '2025-05-01 09:15:00', 'CANCELLED'),
('B-2505-0004', 'J-2505-0007', 'P-2025-00004', '2025-05-02 11:00:00', 'COMPLETED'),
('B-2506-0001', 'J-2506-0001', 'P-2025-00005', '2025-05-25 13:20:00', 'COMPLETED'),
('B-2506-0002', 'J-2506-0003', 'P-2025-00001', '2025-05-28 15:45:00', 'COMPLETED'),
('B-2506-0003', 'J-2506-0005', 'P-2025-00003', '2025-06-01 10:30:00', 'PRESENT'),
('B-2506-0004', 'J-2506-0007', 'P-2025-00002', '2025-06-02 16:20:00', 'COMPLETED'),
('B-2507-0001', 'J-2507-0001', 'P-2025-00004', '2025-06-25 14:00:00', 'COMPLETED'),
('B-2507-0002', 'J-2507-0003', 'P-2025-00005', '2025-06-28 09:30:00', 'CANCELLED'),
('B-2507-0003', 'J-2507-0005', 'P-2025-00001', '2025-07-01 11:45:00', 'COMPLETED'),
('B-2507-0004', 'J-2507-0007', 'P-2025-00003', '2025-07-03 13:15:00', 'PRESENT'),
('B-2512-0001', 'J-2512-0001', 'P-2025-00001', '2025-12-27 09:43:27', 'CANCELLED'),
('B-2512-0002', 'J-2512-0001', 'P-2025-00001', '2025-12-28 07:01:23', 'CANCELLED'),
('B-2512-0003', 'J-2512-0001', 'P-2025-00002', '2025-12-28 07:01:35', 'CANCELLED'),
('B-2512-0004', 'J-2512-0002', 'P-2025-00002', '2025-12-28 07:14:07', 'COMPLETED'),
('B-2512-0005', 'J-2512-0004', 'P-2025-00001', '2025-12-28 07:14:16', 'CANCELLED'),
('B-2512-0006', 'J-2512-0003', 'P-2025-00002', '2025-12-28 07:17:09', 'COMPLETED'),
('B-2512-0007', 'J-2512-0003', 'P-2025-00001', '2025-12-28 07:17:18', 'COMPLETED'),
('B-2512-0008', 'J-2512-0006', 'P-2025-00005', '2025-12-28 09:03:10', 'PRESENT'),
('B-2512-0009', 'J-2512-0009', 'P-2025-00004', '2025-12-28 09:03:19', 'PRESENT'),
('B-2512-0010', 'J-2601-0002', 'P-2025-00005', '2025-12-28 09:03:31', 'PRESENT'),
('B-2512-0011', 'J-2601-0003', 'P-2025-00001', '2025-12-28 14:00:00', 'PRESENT'),
('B-2512-0012', 'J-2601-0004', 'P-2025-00002', '2025-12-28 14:30:00', 'PRESENT'),
('B-2512-0013', 'J-2512-0011', 'P-2025-00005', '2025-12-28 17:20:38', 'COMPLETED');

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` longtext NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cachelocks`
--

CREATE TABLE `cachelocks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `failedjobs`
--

CREATE TABLE `failedjobs` (
  `id` bigint(20) NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `connection` longtext NOT NULL,
  `queue` longtext NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `jadwal`
--

INSERT INTO `jadwal` (`IdJadwal`, `IdDokter`, `Tanggal`, `JamMulai`, `JamAkhir`, `Status`, `Kapasitas`) VALUES
('J-2505-0001', 'D-001', '2025-05-05', '09:00:00', '12:00:00', 'Not Available', 15),
('J-2505-0002', 'D-001', '2025-05-12', '09:00:00', '12:00:00', 'Not Available', 15),
('J-2505-0003', 'D-002', '2025-05-19', '09:00:00', '12:00:00', 'Not Available', 4),
('J-2505-0004', 'D-002', '2025-05-26', '17:00:00', '20:00:00', 'Not Available', 4),
('J-2505-0005', 'D-003', '2025-05-06', '09:00:00', '12:00:00', 'Not Available', 4),
('J-2505-0006', 'D-003', '2025-05-20', '17:00:00', '20:00:00', 'Not Available', 4),
('J-2505-0007', 'D-004', '2025-05-13', '09:00:00', '12:00:00', 'Not Available', 4),
('J-2505-0008', 'D-004', '2025-05-27', '17:00:00', '20:00:00', 'Not Available', 4),
('J-2506-0001', 'D-001', '2025-06-02', '09:00:00', '12:00:00', 'Not Available', 15),
('J-2506-0002', 'D-001', '2025-06-09', '09:00:00', '12:00:00', 'Not Available', 15),
('J-2506-0003', 'D-002', '2025-06-16', '09:00:00', '12:00:00', 'Not Available', 4),
('J-2506-0004', 'D-002', '2025-06-23', '17:00:00', '20:00:00', 'Not Available', 4),
('J-2506-0005', 'D-003', '2025-06-03', '09:00:00', '12:00:00', 'Not Available', 4),
('J-2506-0006', 'D-003', '2025-06-17', '17:00:00', '20:00:00', 'Not Available', 4),
('J-2506-0007', 'D-004', '2025-06-10', '09:00:00', '12:00:00', 'Not Available', 4),
('J-2506-0008', 'D-004', '2025-06-24', '17:00:00', '20:00:00', 'Not Available', 4),
('J-2507-0001', 'D-001', '2025-07-07', '09:00:00', '12:00:00', 'Not Available', 15),
('J-2507-0002', 'D-001', '2025-07-14', '09:00:00', '12:00:00', 'Not Available', 15),
('J-2507-0003', 'D-002', '2025-07-21', '09:00:00', '12:00:00', 'Not Available', 4),
('J-2507-0004', 'D-002', '2025-07-28', '17:00:00', '20:00:00', 'Not Available', 4),
('J-2507-0005', 'D-003', '2025-07-08', '09:00:00', '12:00:00', 'Not Available', 4),
('J-2507-0006', 'D-003', '2025-07-22', '17:00:00', '20:00:00', 'Not Available', 4),
('J-2507-0007', 'D-004', '2025-07-15', '09:00:00', '12:00:00', 'Not Available', 4),
('J-2507-0008', 'D-004', '2025-07-29', '17:00:00', '20:00:00', 'Not Available', 4),
('J-2512-0001', 'D-001', '2025-12-28', '09:00:00', '12:00:00', 'Not Available', 15),
('J-2512-0002', 'D-001', '2025-12-28', '17:00:00', '20:00:00', 'Available', 15),
('J-2512-0003', 'D-002', '2025-12-30', '09:00:00', '12:00:00', 'Available', 4),
('J-2512-0004', 'D-002', '2025-12-29', '17:00:00', '20:00:00', 'Available', 4),
('J-2512-0005', 'D-001', '2025-12-28', '09:00:00', '12:00:00', 'Not Available', 15),
('J-2512-0006', 'D-003', '2025-12-29', '09:00:00', '12:00:00', 'Available', 4),
('J-2512-0007', 'D-003', '2025-12-30', '17:00:00', '20:00:00', 'Available', 4),
('J-2512-0008', 'D-004', '2025-12-29', '09:00:00', '12:00:00', 'Available', 4),
('J-2512-0009', 'D-004', '2025-12-31', '17:00:00', '20:00:00', 'Available', 4),
('J-2512-0010', 'D-002', '2025-12-30', '17:00:00', '20:00:00', 'Available', 4),
('J-2512-0011', 'D-002', '2025-12-28', '17:00:00', '20:00:00', 'Available', 4),
('J-2601-0001', 'D-001', '2026-01-02', '09:00:00', '12:00:00', 'Available', 15),
('J-2601-0002', 'D-002', '2026-01-03', '09:00:00', '12:00:00', 'Available', 4),
('J-2601-0003', 'D-003', '2026-01-05', '09:00:00', '12:00:00', 'Available', 4),
('J-2601-0004', 'D-004', '2026-01-06', '17:00:00', '20:00:00', 'Available', 4),
('J-2601-0005', 'D-001', '2026-01-04', '09:00:00', '12:00:00', 'Available', 15);

-- --------------------------------------------------------

--
-- Table structure for table `jenisobat`
--

CREATE TABLE `jenisobat` (
  `JenisObatID` int(11) NOT NULL,
  `NamaJenis` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `jenisobat`
--

INSERT INTO `jenisobat` (`JenisObatID`, `NamaJenis`) VALUES
(1, 'Analgesik'),
(5, 'Anestesi Lokal'),
(2, 'Antibiotik'),
(4, 'Antiinflamasi'),
(3, 'Antiseptik'),
(6, 'Obat Kumur Khusus'),
(7, 'Obat Orthodonti');

-- --------------------------------------------------------

--
-- Table structure for table `jobbatches`
--

CREATE TABLE `jobbatches` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `total_jobs` int(11) NOT NULL,
  `pending_jobs` int(11) NOT NULL,
  `failed_jobs` int(11) NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` longtext DEFAULT NULL,
  `cancelled_at` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `finished_at` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint(20) NOT NULL,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(4) NOT NULL DEFAULT 0,
  `reserved_at` int(11) DEFAULT NULL,
  `available_at` int(11) NOT NULL,
  `created_at` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(11) NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `obat`
--

INSERT INTO `obat` (`IdObat`, `IdJenisObat`, `NamaObat`, `Satuan`, `Harga`, `Stok`) VALUES
('O-00001', 1, 'Paracetamol 500mg', 'Tablet', 2000.00, 190),
('O-00002', 1, 'Ibuprofen 400mg', 'Tablet', 3000.00, 74),
('O-00003', 2, 'Amoxicillin 500mg', 'Kapsul', 5000.00, 21),
('O-00004', 2, 'Clindamycin 300mg', 'Kapsul', 8000.00, 40),
('O-00005', 3, 'Chlorhexidine 0.2%', 'Botol 100ml', 25000.00, 28),
('O-00006', 3, 'Povidone Iodine', 'Botol 50ml', 15000.00, 25),
('O-00007', 4, 'Dexamethasone', 'Tablet', 3500.00, 50),
('O-00008', 5, 'Lidocaine 2%', 'Ampul', 12000.00, 93),
('O-00009', 5, 'Mepivacaine 3%', 'Ampul', 15000.00, 64),
('O-00010', 1, 'Ketorolac 10mg', 'Tablet', 4500.00, 55),
('O-00011', 6, 'Fluoride Gel 1.23%', 'Tube 50g', 75000.00, 30),
('O-00012', 7, 'Wax Orthodonti', 'Pack 10 strips', 25000.00, 50),
('O-00013', 3, 'Antiseptik Rongga Mulut', 'Botol 200ml', 45000.00, 24);

-- --------------------------------------------------------

--
-- Table structure for table `obat_log`
--

CREATE TABLE `obat_log` (
  `LogID` int(11) NOT NULL,
  `IdObat` varchar(7) NOT NULL,
  `Tanggal` datetime DEFAULT current_timestamp(),
  `Aksi` varchar(50) NOT NULL,
  `Jumlah` decimal(12,2) NOT NULL,
  `StokSebelum` decimal(12,2) NOT NULL,
  `StokSesudah` decimal(12,2) NOT NULL,
  `IdRekamMedis` varchar(15) DEFAULT NULL,
  `CreatedBy` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `obat_log`
--

INSERT INTO `obat_log` (`LogID`, `IdObat`, `Tanggal`, `Aksi`, `Jumlah`, `StokSebelum`, `StokSesudah`, `IdRekamMedis`, `CreatedBy`) VALUES
(1, 'O-00001', '2025-12-28 16:05:32', 'MASUK', 100.00, 99.00, 199.00, NULL, 'admin'),
(2, 'O-00008', '2025-12-28 16:05:32', 'MASUK', 50.00, 45.00, 95.00, NULL, 'admin'),
(3, 'O-00009', '2025-12-28 16:05:32', 'MASUK', 30.00, 34.00, 64.00, NULL, 'admin'),
(4, 'O-00001', '2025-05-05 13:00:00', 'KELUAR', 9.00, 199.00, 190.00, NULL, 'dokter'),
(5, 'O-00005', '2025-05-05 13:00:00', 'KELUAR', 1.00, 30.00, 29.00, NULL, 'dokter'),
(6, 'O-00003', '2025-05-19 11:00:00', 'KELUAR', 14.00, 50.00, 36.00, NULL, 'dokter'),
(7, 'O-00002', '2025-05-13 14:00:00', 'KELUAR', 6.00, 80.00, 74.00, NULL, 'dokter'),
(8, 'O-00005', '2025-06-16 10:00:00', 'KELUAR', 1.00, 29.00, 28.00, NULL, 'dokter'),
(9, 'O-00007', '2025-07-07 12:30:00', 'KELUAR', 10.00, 60.00, 50.00, NULL, 'dokter'),
(10, 'O-00013', '2025-07-07 12:30:00', 'KELUAR', 1.00, 25.00, 24.00, NULL, 'dokter'),
(11, 'O-00003', '2025-07-08 16:00:00', 'KELUAR', 15.00, 36.00, 21.00, NULL, 'dokter'),
(12, 'O-00008', '2025-07-08 16:00:00', 'KELUAR', 2.00, 95.00, 93.00, NULL, 'dokter');

-- --------------------------------------------------------

--
-- Table structure for table `pasien`
--

CREATE TABLE `pasien` (
  `PasienID` varchar(12) NOT NULL,
  `user_id` bigint(20) DEFAULT NULL,
  `Nama` varchar(100) NOT NULL,
  `TanggalLahir` date DEFAULT NULL,
  `Alamat` varchar(100) DEFAULT NULL,
  `NoTelp` varchar(20) DEFAULT NULL,
  `JenisKelamin` char(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pasien`
--

INSERT INTO `pasien` (`PasienID`, `user_id`, `Nama`, `TanggalLahir`, `Alamat`, `NoTelp`, `JenisKelamin`) VALUES
('P-2025-00001', 3, 'Errvin Junius', '2005-12-29', 'ajl gatau', '089723547869', 'L'),
('P-2025-00002', 6, 'aryanto budi', '2005-12-09', 'jl sukakarya', '087634568721', 'L'),
('P-2025-00003', 11, 'Anita Rahma', '2018-05-15', 'Jl. Merdeka No. 12, Bandung', '08111222333', 'P'),
('P-2025-00004', 12, 'Rudi Hartono', '1985-08-20', 'Jl. Sudirman No. 45, Jakarta', '08222333444', 'L'),
('P-2025-00005', 13, 'Siti Nurhaliza', '1990-11-30', 'Jl. Gatot Subroto No. 78, Surabaya', '08333444555', 'P');

-- --------------------------------------------------------

--
-- Table structure for table `passwordresettokens`
--

CREATE TABLE `passwordresettokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pegawai`
--

CREATE TABLE `pegawai` (
  `PegawaiID` varchar(5) NOT NULL,
  `user_id` bigint(20) DEFAULT NULL,
  `Nama` varchar(100) NOT NULL,
  `Jabatan` varchar(20) DEFAULT NULL,
  `TanggalMasuk` date DEFAULT NULL,
  `NoTelp` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pegawai`
--

INSERT INTO `pegawai` (`PegawaiID`, `user_id`, `Nama`, `Jabatan`, `TanggalMasuk`, `NoTelp`) VALUES
('A-001', 1, 'Hans Maulana Budiputra', 'admin', '2025-12-26', '081234567890'),
('A-002', 5, 'Bryant supadmo', 'admin', '2025-12-27', '087634568721'),
('D-001', 2, 'Rafael', 'dokter gigi', '2025-12-26', '089878982343'),
('D-002', 7, 'Budi Santoso', 'dokter spesialis', '2025-12-27', '09876234567'),
('D-003', 9, 'Sari Mawar', 'Dokter Spesialis', '2025-01-15', '081234567891'),
('D-004', 10, 'Andi Wijaya', 'Dokter Spesialis', '2025-01-15', '081234567892');

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pembayaran`
--

INSERT INTO `pembayaran` (`IdPembayaran`, `IdRekamMedis`, `PasienID`, `TanggalPembayaran`, `Metode`, `TotalBayar`, `Status`) VALUES
('PAY-2505-0001', 'RM-2025-0004', 'P-2025-00001', '2025-05-05 13:30:00', 'Transfer', 667000.00, 'PAID'),
('PAY-2505-0002', 'RM-2025-0005', 'P-2025-00002', '2025-05-19 11:45:00', 'Asuransi', 1070000.00, 'PAID'),
('PAY-2505-0003', 'RM-2025-0006', 'P-2025-00004', '2025-05-13 14:20:00', 'Tunai', 1553000.00, 'PAID'),
('PAY-2506-0001', 'RM-2025-0007', 'P-2025-00005', '2025-06-02 12:15:00', 'Transfer', 312000.00, 'PAID'),
('PAY-2506-0002', 'RM-2025-0008', 'P-2025-00001', '2025-06-16 10:30:00', 'Asuransi', 275000.00, 'PAID'),
('PAY-2506-0003', 'RM-2025-0009', 'P-2025-00002', '2025-06-10 15:45:00', 'Tunai', 406000.00, 'PAID'),
('PAY-2507-0001', 'RM-2025-0010', 'P-2025-00004', '2025-07-07 13:00:00', 'Transfer', 85000.00, 'PAID'),
('PAY-2507-0002', 'RM-2025-0011', 'P-2025-00001', '2025-07-08 16:30:00', 'Asuransi', 2840000.00, 'PAID'),
('PAY-2512-0001', 'RM-2025-0001', 'P-2025-00002', '2025-12-28 08:12:53', 'Asuransi', 817000.00, 'PAID'),
('PAY-2512-0002', 'RM-2025-0002', 'P-2025-00002', '2025-12-28 09:19:18', 'Tunai', 15339000.00, 'PAID'),
('PAY-2512-0003', 'RM-2025-0003', 'P-2025-00001', '2025-12-28 09:19:21', 'Transfer', 5616000.00, 'PAID'),
('PAY-2512-0012', 'RM-2025-0012', 'P-2025-00005', '2025-12-28 17:23:22', 'Tunai', 50000.00, 'PAID');

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `rekammedis`
--

INSERT INTO `rekammedis` (`IdRekamMedis`, `IdBooking`, `PasienID`, `DokterID`, `Tanggal`, `Diagnosa`, `Catatan`) VALUES
('RM-2025-0001', 'B-2512-0004', 'P-2025-00002', 'D-001', '2025-12-28', 'tumbuh gigi bungsu', 'alergi niripidin'),
('RM-2025-0002', 'B-2512-0006', 'P-2025-00002', 'D-002', '2025-12-30', 'Maloklusi Angle Class I dengan spacing pada gigi anterior rahang atas', 'Pasien dewasa usia 20 tahun ingin memperbaiki estetik gigi depan. Rencana perawatan dengan clear aligner'),
('RM-2025-0003', 'B-2512-0007', 'P-2025-00001', 'D-002', '2025-12-30', 'Deep bite dengan crowding moderate pada rahang bawah', 'Pasien muda usia 20 tahun dengan keluhan gigi berjejal dan overbite. Rencana perawatan 2 tahap'),
('RM-2025-0004', 'B-2505-0001', 'P-2025-00001', 'D-001', '2025-05-05', 'Karies gigi molar pertama', 'Tambalan komposit, kontrol 6 bulan'),
('RM-2025-0005', 'B-2505-0002', 'P-2025-00002', 'D-002', '2025-05-19', 'Periodontitis kronis derajat sedang', 'Scaling dan root planing, edukasi perawatan mulut'),
('RM-2025-0006', 'B-2505-0004', 'P-2025-00004', 'D-004', '2025-05-13', 'Gigi tiruan lepasan perlu penyesuaian', 'Relining gigi tiruan atas, kontrol 1 minggu'),
('RM-2025-0007', 'B-2506-0001', 'P-2025-00005', 'D-001', '2025-06-02', 'Pulpitis reversibel gigi premolar', 'Proteksi pulpa dengan bahan sedatif, kontrol 1 bulan'),
('RM-2025-0008', 'B-2506-0002', 'P-2025-00001', 'D-002', '2025-06-16', 'Kalkulus supragingiva berat', 'Scaling ultrasonik, kontrol 3 bulan'),
('RM-2025-0009', 'B-2506-0004', 'P-2025-00002', 'D-004', '2025-06-10', 'Trauma gigi anterior dengan fraktur enamel', 'Pemolesan tepi fraktur, observasi vitalitas pulpa'),
('RM-2025-0010', 'B-2507-0001', 'P-2025-00004', 'D-001', '2025-07-07', 'Ulkus aftosa rekuren', 'Aplikasi topikal, saran diet, kontrol jika tidak membaik'),
('RM-2025-0011', 'B-2507-0003', 'P-2025-00001', 'D-003', '2025-07-08', 'Gigi molar ketiga impaksi mesioangular', 'Konsultasi bedah pencabutan, rencana operasi'),
('RM-2025-0012', 'B-2512-0013', 'P-2025-00005', 'D-002', '2025-12-28', 'konsultasi pemasangan behel', 'Minta ronthen gigi keseluruhan');

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
  `SubTotal` decimal(12,2) GENERATED ALWAYS AS (`Jumlah` * `HargaSatuan`) STORED
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `rekammedis_obat`
--

INSERT INTO `rekammedis_obat` (`IdRekamMedis`, `IdObat`, `Dosis`, `Frekuensi`, `LamaHari`, `Jumlah`, `HargaSatuan`) VALUES
('RM-2025-0001', 'O-00001', '-', '-', 1, 1.00, 2000.00),
('RM-2025-0001', 'O-00009', '-', '-', 1, 1.00, 15000.00),
('RM-2025-0002', 'O-00001', '500mg', '2x1 jika nyeri', 3, 6.00, 2000.00),
('RM-2025-0002', 'O-00010', '10mg', '2x1 jika nyeri hebat', 3, 6.00, 4500.00),
('RM-2025-0003', 'O-00001', '500mg', '3x1 jika nyeri', 5, 15.00, 2000.00),
('RM-2025-0003', 'O-00006', '10ml', 'kumur 2x sehari', 1, 1.00, 15000.00),
('RM-2025-0003', 'O-00007', '0.5mg', '2x1', 3, 6.00, 3500.00),
('RM-2025-0004', 'O-00001', '500mg', '3x1 jika nyeri', 3, 9.00, 2000.00),
('RM-2025-0004', 'O-00005', '10ml', 'kumur 2x sehari', 7, 1.00, 25000.00),
('RM-2025-0005', 'O-00003', '500mg', '2x1', 7, 14.00, 5000.00),
('RM-2025-0006', 'O-00002', '400mg', '2x1 jika nyeri', 3, 6.00, 3000.00),
('RM-2025-0007', 'O-00001', '500mg', '3x1 jika nyeri', 2, 6.00, 2000.00),
('RM-2025-0008', 'O-00005', '15ml', 'kumur 2x sehari', 5, 1.00, 25000.00),
('RM-2025-0010', 'O-00007', '0.5mg', '2x1', 5, 10.00, 3500.00),
('RM-2025-0010', 'O-00013', '10ml', 'kumur 3x sehari', 7, 1.00, 45000.00),
('RM-2025-0011', 'O-00003', '500mg', '3x1', 5, 15.00, 5000.00),
('RM-2025-0011', 'O-00008', '1.8ml', 'suntik lokal', 1, 2.00, 12000.00);

-- --------------------------------------------------------

--
-- Table structure for table `rekammedis_tindakan`
--

CREATE TABLE `rekammedis_tindakan` (
  `IdRekamMedis` varchar(15) NOT NULL,
  `IdTindakan` varchar(10) NOT NULL,
  `Jumlah` int(11) DEFAULT 1,
  `Harga` decimal(12,2) NOT NULL,
  `SubTotal` decimal(12,2) GENERATED ALWAYS AS (`Jumlah` * `Harga`) STORED
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `rekammedis_tindakan`
--

INSERT INTO `rekammedis_tindakan` (`IdRekamMedis`, `IdTindakan`, `Jumlah`, `Harga`) VALUES
('RM-2025-0001', 'T-006', 1, 800000.00),
('RM-2025-0002', 'T-019', 1, 300000.00),
('RM-2025-0002', 'T-021', 1, 15000000.00),
('RM-2025-0003', 'T-002', 1, 250000.00),
('RM-2025-0003', 'T-009', 1, 5000000.00),
('RM-2025-0003', 'T-019', 1, 300000.00),
('RM-2025-0004', 'T-002', 1, 250000.00),
('RM-2025-0004', 'T-004', 1, 400000.00),
('RM-2025-0005', 'T-002', 2, 250000.00),
('RM-2025-0005', 'T-019', 1, 300000.00),
('RM-2025-0006', 'T-001', 1, 50000.00),
('RM-2025-0006', 'T-008', 1, 1500000.00),
('RM-2025-0007', 'T-003', 1, 300000.00),
('RM-2025-0008', 'T-002', 1, 250000.00),
('RM-2025-0009', 'T-004', 1, 400000.00),
('RM-2025-0010', 'T-001', 1, 50000.00),
('RM-2025-0011', 'T-015', 1, 2500000.00),
('RM-2025-0011', 'T-019', 1, 300000.00),
('RM-2025-0012', 'T-001', 1, 50000.00);

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` longtext DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tindakan`
--

CREATE TABLE `tindakan` (
  `IdTindakan` varchar(10) NOT NULL,
  `NamaTindakan` varchar(100) NOT NULL,
  `Harga` decimal(12,2) NOT NULL,
  `Durasi` time DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tindakan`
--

INSERT INTO `tindakan` (`IdTindakan`, `NamaTindakan`, `Harga`, `Durasi`) VALUES
('T-001', 'Konsultasi Gigi', 50000.00, '00:30:00'),
('T-002', 'Pembersihan Karang Gigi (Scaling)', 250000.00, '01:00:00'),
('T-003', 'Tambal Gigi (Amalgam)', 300000.00, '01:00:00'),
('T-004', 'Tambal Gigi (Komposit)', 400000.00, '01:15:00'),
('T-005', 'Cabut Gigi Biasa', 350000.00, '00:45:00'),
('T-006', 'Cabut Gigi Bungsu', 800000.00, '01:30:00'),
('T-007', 'Perawatan Saluran Akar', 1200000.00, '02:00:00'),
('T-008', 'Pemasangan Crown', 1500000.00, '01:30:00'),
('T-009', 'Pemasangan Behel (Orthodonti)', 5000000.00, '02:00:00'),
('T-010', 'Pemutihan Gigi (Bleaching)', 1200000.00, '01:15:00'),
('T-011', 'Perawatan Gigi Susu', 300000.00, '00:45:00'),
('T-012', 'Aplikasi Fluoride Anak', 200000.00, '00:30:00'),
('T-013', 'Pencabutan Gigi Susu', 250000.00, '00:40:00'),
('T-014', 'Space Maintainer', 1500000.00, '01:00:00'),
('T-015', 'Operasi Impaksi Gigi Bungsu', 2500000.00, '02:00:00'),
('T-016', 'Biopsi Jaringan Mulut', 1500000.00, '01:30:00'),
('T-017', 'Operasi Kista Dental', 3500000.00, '02:30:00'),
('T-018', 'Reimplantasi Gigi', 1800000.00, '01:45:00'),
('T-019', 'Konsultasi Orthodonti', 300000.00, '01:00:00'),
('T-020', 'Pemasangan Braces Metal', 7500000.00, '02:30:00'),
('T-021', 'Pemasangan Invisalign', 15000000.00, '02:00:00'),
('T-022', 'Perawatan Retainer', 500000.00, '00:30:00');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` datetime DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `role` varchar(20) DEFAULT 'pasien'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`, `role`) VALUES
(1, 'Hans Maulana Budiputra', 'hans@klinik.admin', NULL, '$2y$12$ElQFh1jzDEFSBtb9Lyo6Eui8ChX7fou5Zd/pP8wbT.85TE9aUmjFy', NULL, '2025-12-27 14:49:18', '2025-12-27 07:52:48', 'admin'),
(2, 'Rafael', 'rafael@klinik.dokter', NULL, '$2y$12$CVXyWbX8N.KHBC/3IgbxneA0hLUa0BS2nTP2/D28vgBANNnxnpKdi', NULL, '2025-12-27 14:49:18', '2025-12-27 14:49:18', 'dokter'),
(3, 'Errvin junius', 'errvin@klinik.pasien', NULL, '$2y$12$EPjOiy2SMrWHbeNvmDWe2urqEDx.RTxrvR80YB6bctw6Fk7QeJOL6', NULL, '2025-12-27 14:49:18', '2025-12-27 14:49:18', 'pasien'),
(5, 'Bryant supadmo', 'bray@klinik.admin', NULL, '$2y$12$rDybcEollkyPKJ8iYfxsSul3qs6D7mZ/2QGbnHvq24ioJJ6cui8he', NULL, '2025-12-27 09:02:10', '2025-12-27 09:02:10', 'admin'),
(6, 'aryanto budi', 'budi@klinik.pasien', NULL, '$2y$12$m09kSIzwRQ79nZkQ7dArd.e17.jtRKit8aouLOuCcPhywdfh0NIIi', NULL, '2025-12-27 09:10:41', '2025-12-27 09:10:41', 'pasien'),
(7, 'Budi Santoso', 'santoso@klinik.dokter', NULL, '$2y$12$CVXyWbX8N.KHBC/3IgbxneA0hLUa0BS2nTP2/D28vgBANNnxnpKdi', NULL, '2025-12-27 09:16:28', '2025-12-28 16:10:05', 'dokter'),
(9, 'Sari Mawar', 'sari.mawar@klinik.dokter', NULL, '$2y$12$CVXyWbX8N.KHBC/3IgbxneA0hLUa0BS2nTP2/D28vgBANNnxnpKdi', NULL, '2025-12-28 15:48:45', '2025-12-28 16:09:39', 'dokter'),
(10, 'Andi Wijaya', 'andi.wijaya@klinik.dokter', NULL, '$2y$12$CVXyWbX8N.KHBC/3IgbxneA0hLUa0BS2nTP2/D28vgBANNnxnpKdi', NULL, '2025-12-28 15:48:45', '2025-12-28 16:10:13', 'dokter'),
(11, 'Anita Rahma', 'anita@klinik.pasien', NULL, '$2y$12$clat5ZCLUNglZput8oogPehI5/O7SfH5Mc7Nji41I4IBKONGhKWbq', NULL, '2025-12-28 15:53:29', '2025-12-28 15:53:29', 'pasien'),
(12, 'Rudi Hartono', 'rudi@klinik.pasien', NULL, '$2y$12$dlat5ZCLUNglZput8oogPehI5/O7SfH5Mc7Nji41I4IBKONGhKWbq', NULL, '2025-12-28 15:53:29', '2025-12-28 15:53:29', 'pasien'),
(13, 'Siti Nurhaliza', 'siti@klinik.pasien', NULL, '$2y$12$elat5ZCLUNglZput8oogPehI5/O7SfH5Mc7Nji41I4IBKONGhKWbq', NULL, '2025-12-28 15:53:29', '2025-12-28 15:53:29', 'pasien');

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

CREATE VIEW `view_jadwaldokter`  AS SELECT `j`.`IdJadwal` AS `IdJadwal`, `j`.`IdDokter` AS `IdDokter`, `b`.`IdBooking` AS `IdBooking`, `p`.`Nama` AS `Nama Pasien`, `j`.`Tanggal` AS `Tanggal`, `j`.`JamMulai` AS `JamMulai`, `j`.`JamAkhir` AS `JamAkhir`, `pg`.`Nama` AS `Nama Dokter`, `b`.`Status` AS `Status` FROM (((`jadwal` `j` join `booking` `b` on(`j`.`IdJadwal` = `b`.`IdJadwal`)) join `pasien` `p` on(`b`.`PasienID` = `p`.`PasienID`)) join `pegawai` `pg` on(`j`.`IdDokter` = `pg`.`PegawaiID`)) ;

-- --------------------------------------------------------

--
-- Structure for view `view_jadwalpasien`
--
DROP TABLE IF EXISTS `view_jadwalpasien`;

CREATE VIEW `view_jadwalpasien`  AS SELECT `j`.`IdJadwal` AS `IdJadwal`, `j`.`IdDokter` AS `IdDokter`, `j`.`Tanggal` AS `Tanggal`, `j`.`JamMulai` AS `JamMulai`, `j`.`JamAkhir` AS `JamAkhir`, `j`.`Status` AS `Status`, `p`.`Nama` AS `NamaDokter`, `p`.`Jabatan` AS `Jabatan` FROM (`jadwal` `j` join `pegawai` `p` on(`j`.`IdDokter` = `p`.`PegawaiID`)) ;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `booking`
--
ALTER TABLE `booking`
  ADD PRIMARY KEY (`IdBooking`),
  ADD KEY `IdJadwal` (`IdJadwal`),
  ADD KEY `PasienID` (`PasienID`);

--
-- Indexes for table `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `cachelocks`
--
ALTER TABLE `cachelocks`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `failedjobs`
--
ALTER TABLE `failedjobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uuid` (`uuid`);

--
-- Indexes for table `jadwal`
--
ALTER TABLE `jadwal`
  ADD PRIMARY KEY (`IdJadwal`),
  ADD KEY `IdDokter` (`IdDokter`);

--
-- Indexes for table `jenisobat`
--
ALTER TABLE `jenisobat`
  ADD PRIMARY KEY (`JenisObatID`),
  ADD UNIQUE KEY `NamaJenis` (`NamaJenis`);

--
-- Indexes for table `jobbatches`
--
ALTER TABLE `jobbatches`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `jobs`
--
ALTER TABLE `jobs`
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
  ADD KEY `IdJenisObat` (`IdJenisObat`);

--
-- Indexes for table `obat_log`
--
ALTER TABLE `obat_log`
  ADD PRIMARY KEY (`LogID`);

--
-- Indexes for table `pasien`
--
ALTER TABLE `pasien`
  ADD PRIMARY KEY (`PasienID`),
  ADD KEY `FK_Userid` (`user_id`);

--
-- Indexes for table `passwordresettokens`
--
ALTER TABLE `passwordresettokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `pegawai`
--
ALTER TABLE `pegawai`
  ADD PRIMARY KEY (`PegawaiID`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `pembayaran`
--
ALTER TABLE `pembayaran`
  ADD PRIMARY KEY (`IdPembayaran`),
  ADD KEY `IdRekamMedis` (`IdRekamMedis`),
  ADD KEY `PasienID` (`PasienID`);

--
-- Indexes for table `rekammedis`
--
ALTER TABLE `rekammedis`
  ADD PRIMARY KEY (`IdRekamMedis`),
  ADD KEY `IdBooking` (`IdBooking`),
  ADD KEY `PasienID` (`PasienID`),
  ADD KEY `DokterID` (`DokterID`);

--
-- Indexes for table `rekammedis_obat`
--
ALTER TABLE `rekammedis_obat`
  ADD PRIMARY KEY (`IdRekamMedis`,`IdObat`),
  ADD KEY `IdObat` (`IdObat`);

--
-- Indexes for table `rekammedis_tindakan`
--
ALTER TABLE `rekammedis_tindakan`
  ADD PRIMARY KEY (`IdRekamMedis`,`IdTindakan`),
  ADD KEY `IdTindakan` (`IdTindakan`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

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
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `failedjobs`
--
ALTER TABLE `failedjobs`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `jenisobat`
--
ALTER TABLE `jenisobat`
  MODIFY `JenisObatID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `obat_log`
--
ALTER TABLE `obat_log`
  MODIFY `LogID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `booking`
--
ALTER TABLE `booking`
  ADD CONSTRAINT `booking_ibfk_1` FOREIGN KEY (`IdJadwal`) REFERENCES `jadwal` (`IdJadwal`),
  ADD CONSTRAINT `booking_ibfk_2` FOREIGN KEY (`PasienID`) REFERENCES `pasien` (`PasienID`);

--
-- Constraints for table `jadwal`
--
ALTER TABLE `jadwal`
  ADD CONSTRAINT `jadwal_ibfk_1` FOREIGN KEY (`IdDokter`) REFERENCES `pegawai` (`PegawaiID`);

--
-- Constraints for table `obat`
--
ALTER TABLE `obat`
  ADD CONSTRAINT `obat_ibfk_1` FOREIGN KEY (`IdJenisObat`) REFERENCES `jenisobat` (`JenisObatID`);

--
-- Constraints for table `pasien`
--
ALTER TABLE `pasien`
  ADD CONSTRAINT `FK_Userid` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `pegawai`
--
ALTER TABLE `pegawai`
  ADD CONSTRAINT `pegawai_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `pembayaran`
--
ALTER TABLE `pembayaran`
  ADD CONSTRAINT `pembayaran_ibfk_1` FOREIGN KEY (`IdRekamMedis`) REFERENCES `rekammedis` (`IdRekamMedis`),
  ADD CONSTRAINT `pembayaran_ibfk_2` FOREIGN KEY (`PasienID`) REFERENCES `pasien` (`PasienID`);

--
-- Constraints for table `rekammedis`
--
ALTER TABLE `rekammedis`
  ADD CONSTRAINT `rekammedis_ibfk_1` FOREIGN KEY (`IdBooking`) REFERENCES `booking` (`IdBooking`),
  ADD CONSTRAINT `rekammedis_ibfk_2` FOREIGN KEY (`PasienID`) REFERENCES `pasien` (`PasienID`),
  ADD CONSTRAINT `rekammedis_ibfk_3` FOREIGN KEY (`DokterID`) REFERENCES `pegawai` (`PegawaiID`);

--
-- Constraints for table `rekammedis_obat`
--
ALTER TABLE `rekammedis_obat`
  ADD CONSTRAINT `rekammedis_obat_ibfk_1` FOREIGN KEY (`IdRekamMedis`) REFERENCES `rekammedis` (`IdRekamMedis`),
  ADD CONSTRAINT `rekammedis_obat_ibfk_2` FOREIGN KEY (`IdObat`) REFERENCES `obat` (`IdObat`);

--
-- Constraints for table `rekammedis_tindakan`
--
ALTER TABLE `rekammedis_tindakan`
  ADD CONSTRAINT `rekammedis_tindakan_ibfk_1` FOREIGN KEY (`IdRekamMedis`) REFERENCES `rekammedis` (`IdRekamMedis`),
  ADD CONSTRAINT `rekammedis_tindakan_ibfk_2` FOREIGN KEY (`IdTindakan`) REFERENCES `tindakan` (`IdTindakan`);

--
-- Constraints for table `sessions`
--
ALTER TABLE `sessions`
  ADD CONSTRAINT `sessions_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
