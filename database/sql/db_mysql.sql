-- Membuat dan menggunakan database
CREATE DATABASE IF NOT EXISTS `DbKlinikGigi` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

USE `DbKlinikGigi`;

-- ****** Object:  Table `dbo`.`Jadwal`    Script Date: 10/12/2025 14:04:39 ******
CREATE TABLE `Jadwal`(
	`IdJadwal` VARCHAR(11) NOT NULL,
	`IdDokter` VARCHAR(5) NOT NULL,
	`Tanggal` DATE NOT NULL,
	`JamMulai` TIME NOT NULL,
	`JamAkhir` TIME NOT NULL,
	`Status` VARCHAR(20) NULL DEFAULT 'Available',
	`Kapasitas` INT NOT NULL,
	PRIMARY KEY (`IdJadwal`),
    CONSTRAINT `CK_Jadwal_Status` CHECK ((`Status`='Booked' OR `Status`='Available'))
);

-- ****** Object:  Table `dbo`.`Pasien`    Script Date: 10/12/2025 14:04:39 ******
CREATE TABLE `Pasien`(
	`PasienID` VARCHAR(12) NOT NULL,
	`Nama` VARCHAR(100) NOT NULL,
	`TanggalLahir` DATE NULL,
	`Alamat` VARCHAR(100) NULL,
	`NoTelp` VARCHAR(20) NULL,
	`JenisKelamin` CHAR(1) NULL,
	PRIMARY KEY (`PasienID`),
    CONSTRAINT `CK_Pasien_JenisKelamin` CHECK ((`JenisKelamin`='P' OR `JenisKelamin`='L'))
);

-- ****** Object:  Table `dbo`.`Pegawai`    Script Date: 10/12/2025 14:04:39 ******
CREATE TABLE `Pegawai`(
	`PegawaiID` VARCHAR(5) NOT NULL,
	`Nama` VARCHAR(100) NOT NULL,
	`Jabatan` VARCHAR(20) NULL, -- Increased size from 10 to 20 for 'dokter spesialis'
	`TanggalMasuk` DATE NULL,
	`NoTelp` VARCHAR(20) NULL,
	PRIMARY KEY (`PegawaiID`)
);

-- ****** Object:  Table `dbo`.`Booking`    Script Date: 10/12/2025 14:04:39 ******
CREATE TABLE `Booking`(
	`IdBooking` VARCHAR(11) NOT NULL,
	`IdJadwal` VARCHAR(11) NOT NULL,
	`PasienID` VARCHAR(12) NOT NULL,
	`TanggalBooking` DATETIME NULL DEFAULT CURRENT_TIMESTAMP,
	`Status` VARCHAR(20) NULL DEFAULT 'PRESENT',
	PRIMARY KEY (`IdBooking`),
    CONSTRAINT `CK_Booking_Status` CHECK ((`Status`='PRESENT' OR `Status`='CANCELLED'))
);

-- ****** Object:  Table `dbo`.`JenisObat`    Script Date: 10/12/2025 14:04:39 ******
CREATE TABLE `JenisObat`(
	`JenisObatID` INT AUTO_INCREMENT NOT NULL,
	`NamaJenis` VARCHAR(50) NOT NULL,
	PRIMARY KEY (`JenisObatID`),
	UNIQUE (`NamaJenis`)
);

-- ****** Object:  Table `dbo`.`Obat`    Script Date: 10/12/2025 14:04:39 ******
CREATE TABLE `Obat`(
	`IdObat` VARCHAR(7) NOT NULL,
	`IdJenisObat` INT NOT NULL,
	`NamaObat` VARCHAR(100) NOT NULL,
	`Satuan` VARCHAR(20) NULL,
	`Harga` DECIMAL(12, 2) NOT NULL,
	`Stok` INT NOT NULL,
	PRIMARY KEY (`IdObat`)
);

-- ****** Object:  Table `dbo`.`Obat_Log`    Script Date: 10/12/2025 14:04:39 ******
CREATE TABLE `Obat_Log`(
	`LogID` INT AUTO_INCREMENT NOT NULL,
	`IdObat` VARCHAR(7) NOT NULL,
	`Tanggal` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
	`Aksi` VARCHAR(50) NOT NULL,
	`Jumlah` DECIMAL(12, 2) NOT NULL,
	`StokSebelum` DECIMAL(12, 2) NOT NULL,
	`StokSesudah` DECIMAL(12, 2) NOT NULL,
	`IdRekamMedis` VARCHAR(15) NULL,
	`CreatedBy` VARCHAR(50) NULL,
	PRIMARY KEY (`LogID`)
);

-- ****** Object:  Table `dbo`.`RekamMedis`    Script Date: 10/12/2025 14:04:39 ******
CREATE TABLE `RekamMedis`(
	`IdRekamMedis` VARCHAR(15) NOT NULL,
	`IdBooking` VARCHAR(11) NOT NULL,
	`PasienID` VARCHAR(12) NOT NULL,
	`DokterID` VARCHAR(5) NOT NULL,
	`Tanggal` DATE NULL DEFAULT (CURDATE()),
	`Diagnosa` VARCHAR(200) NOT NULL,
	`Catatan` VARCHAR(500) NULL,
	PRIMARY KEY (`IdRekamMedis`)
);

-- ****** Object:  Table `dbo`.`Pembayaran`    Script Date: 10/12/2025 14:04:39 ******
CREATE TABLE `Pembayaran`(
	`IdPembayaran` VARCHAR(15) NOT NULL,
	`IdRekamMedis` VARCHAR(15) NOT NULL,
	`PasienID` VARCHAR(12) NOT NULL,
	`TanggalPembayaran` DATETIME NULL DEFAULT CURRENT_TIMESTAMP,
	`Metode` VARCHAR(20) NOT NULL,
	`TotalBayar` DECIMAL(12, 2) NOT NULL,
	`Status` VARCHAR(20) NULL DEFAULT 'UNPAID',
	PRIMARY KEY (`IdPembayaran`),
    CONSTRAINT `CK_Pembayaran_Metode` CHECK ((`Metode`='Transfer' OR `Metode`='Cash')),
    CONSTRAINT `CK_Pembayaran_Status` CHECK ((`Status`='PAID' OR `Status`='UNPAID'))
);

-- ****** Object:  Table `dbo`.`RekamMedis_Obat`    Script Date: 10/12/2025 14:04:39 ******
CREATE TABLE `RekamMedis_Obat`(
	`IdRekamMedis` VARCHAR(15) NOT NULL,
	`IdObat` VARCHAR(7) NOT NULL,
	`Dosis` VARCHAR(50) NULL,
	`Frekuensi` VARCHAR(50) NULL,
	`LamaHari` INT NULL,
	`Jumlah` DECIMAL(12, 2) NOT NULL,
	`HargaSatuan` DECIMAL(12, 2) NOT NULL,
	`SubTotal` DECIMAL(24, 4) AS (`Jumlah`*`HargaSatuan`) STORED,
	PRIMARY KEY (`IdRekamMedis`, `IdObat`)
);

-- ****** Object:  Table `dbo`.`Tindakan`    Script Date: 10/12/2025 14:04:39 ******
CREATE TABLE `Tindakan`(
	`IdTindakan` VARCHAR(10) NOT NULL,
	`NamaTindakan` VARCHAR(100) NOT NULL,
	`Harga` DECIMAL(12, 2) NOT NULL,
	`Durasi` TIME NULL,
	PRIMARY KEY (`IdTindakan`)
);

-- ****** Object:  Table `dbo`.`RekamMedis_Tindakan`    Script Date: 10/12/2025 14:04:39 ******
CREATE TABLE `RekamMedis_Tindakan`(
	`IdRekamMedis` VARCHAR(15) NOT NULL,
	`IdTindakan` VARCHAR(10) NOT NULL,
	`Jumlah` INT NULL DEFAULT 1,
	`Harga` DECIMAL(12, 2) NOT NULL,
	`SubTotal` DECIMAL(24, 4) AS (`Jumlah`*`Harga`) STORED,
	PRIMARY KEY (`IdRekamMedis`, `IdTindakan`)
);

-- ****** Foreign Keys ******
ALTER TABLE `Booking` ADD CONSTRAINT `FK_Booking_Jadwal` FOREIGN KEY(`IdJadwal`) REFERENCES `Jadwal` (`IdJadwal`);
ALTER TABLE `Booking` ADD CONSTRAINT `FK_Booking_Pasien` FOREIGN KEY(`PasienID`) REFERENCES `Pasien` (`PasienID`);
ALTER TABLE `Jadwal` ADD CONSTRAINT `FK_Jadwal_Pegawai` FOREIGN KEY(`IdDokter`) REFERENCES `Pegawai` (`PegawaiID`);
ALTER TABLE `Obat` ADD CONSTRAINT `FK_Obat_JenisObat` FOREIGN KEY(`IdJenisObat`) REFERENCES `JenisObat` (`JenisObatID`);
ALTER TABLE `Pembayaran` ADD CONSTRAINT `FK_Pembayaran_Pasien` FOREIGN KEY(`PasienID`) REFERENCES `Pasien` (`PasienID`);
ALTER TABLE `Pembayaran` ADD CONSTRAINT `FK_Pembayaran_RekamMedis` FOREIGN KEY(`IdRekamMedis`) REFERENCES `RekamMedis` (`IdRekamMedis`);
ALTER TABLE `RekamMedis` ADD CONSTRAINT `FK_RekamMedis_Booking` FOREIGN KEY(`IdBooking`) REFERENCES `Booking` (`IdBooking`);
ALTER TABLE `RekamMedis` ADD CONSTRAINT `FK_RekamMedis_Pasien` FOREIGN KEY(`PasienID`) REFERENCES `Pasien` (`PasienID`);
ALTER TABLE `RekamMedis` ADD CONSTRAINT `FK_RekamMedis_Pegawai` FOREIGN KEY(`DokterID`) REFERENCES `Pegawai` (`PegawaiID`);
ALTER TABLE `RekamMedis_Obat` ADD CONSTRAINT `FK_RekamMedisObat_Obat` FOREIGN KEY(`IdObat`) REFERENCES `Obat` (`IdObat`);
ALTER TABLE `RekamMedis_Obat` ADD CONSTRAINT `FK_RekamMedisObat_RekamMedis` FOREIGN KEY(`IdRekamMedis`) REFERENCES `RekamMedis` (`IdRekamMedis`);
ALTER TABLE `RekamMedis_Tindakan` ADD CONSTRAINT `FK_RMT_RekamMedis` FOREIGN KEY(`IdRekamMedis`) REFERENCES `RekamMedis` (`IdRekamMedis`);
ALTER TABLE `RekamMedis_Tindakan` ADD CONSTRAINT `FK_RMT_Tindakan` FOREIGN KEY(`IdTindakan`) REFERENCES `Tindakan` (`IdTindakan`);

-- ****** Indexes ******
CREATE INDEX `IX_RekamMedis_Obat_IdObat` ON `RekamMedis_Obat` (`IdObat`);
CREATE INDEX `IX_RekamMedis_Obat_IdRekamMedis` ON `RekamMedis_Obat` (`IdRekamMedis`);

-- ****** Views ******
CREATE OR REPLACE VIEW `View_JadwalDokter` AS
SELECT 
    j.IdJadwal, 
    j.IdDokter, 
    b.IdBooking, 
    p.Nama AS `Nama Pasien`, 
    j.Tanggal, 
    j.JamMulai, 
    j.JamAkhir, 
    pg.Nama AS `Nama Dokter`, 
    b.Status
FROM `Jadwal` j
INNER JOIN `Booking` b ON j.IdJadwal = b.IdJadwal
INNER JOIN `Pasien` p ON b.PasienID = p.PasienID
INNER JOIN `Pegawai` pg ON j.IdDokter = pg.PegawaiID;

CREATE OR REPLACE VIEW `View_JadwalPasien` AS
SELECT 
    j.IdJadwal, 
    j.IdDokter, 
    j.Tanggal, 
    j.JamMulai, 
    j.JamAkhir, 
    j.Status, 
    p.Nama AS NamaDokter, 
    p.Jabatan
FROM `Jadwal` AS j
INNER JOIN `Pegawai` AS p ON j.IdDokter = p.PegawaiID;

-- ****** Stored Procedures ******

DELIMITER $$

-- ****** Object:  StoredProcedure `dbo`.`Sp_CancelBooking` ******
CREATE PROCEDURE `Sp_CancelBooking`(
  IN p_IdBooking VARCHAR(20)
)
BEGIN
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

-- ****** Object:  StoredProcedure `dbo`.`Sp_InsertBooking` ******
CREATE PROCEDURE `Sp_InsertBooking`(
  IN p_IdJadwal VARCHAR(20),
  IN p_IdPasien VARCHAR(20),
  IN p_TanggalBooking DATETIME,
  IN p_Status VARCHAR(20),
  OUT p_NewIdBooking VARCHAR(20)
)
BEGIN
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

-- ****** Object:  StoredProcedure `dbo`.`Sp_InsertJadwal` ******
CREATE PROCEDURE `Sp_InsertJadwal`(
  IN p_IdDokter VARCHAR(10),
  IN p_Tanggal DATE,
  IN p_Sesi VARCHAR(10),
  IN p_Status VARCHAR(20),
  OUT p_NewIdJadwal VARCHAR(20)
)
BEGIN
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

-- ****** Object:  StoredProcedure `dbo`.`Sp_InsertJenisObat` ******
CREATE PROCEDURE `Sp_InsertJenisObat`(
  IN p_NamaJenis VARCHAR(50)
)
BEGIN
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

-- ****** Object:  StoredProcedure `dbo`.`Sp_InsertObat` ******
CREATE PROCEDURE `Sp_InsertObat`(
  IN p_NamaObat VARCHAR(100),
  IN p_Satuan VARCHAR(20),
  IN p_Harga DECIMAL(12,2),
  IN p_Stok INT,
  IN p_IdJenisObat INT,
  OUT p_NewIdObat VARCHAR(10)
)
BEGIN
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

-- ****** Object:  StoredProcedure `dbo`.`Sp_InsertPasien` ******
CREATE PROCEDURE `Sp_InsertPasien`(
  IN p_Nama VARCHAR(100),
  IN p_TanggalLahir DATE,
  IN p_Alamat VARCHAR(200),
  IN p_NoTelp VARCHAR(20),
  IN p_JenisKelamin VARCHAR(1),
  OUT p_NewPasienID VARCHAR(20)
)
BEGIN
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

-- ****** Object:  StoredProcedure `dbo`.`Sp_InsertPegawai` ******
CREATE PROCEDURE `Sp_InsertPegawai`(
  IN p_Nama VARCHAR(100),
  IN p_Jabatan VARCHAR(50),
  IN p_TanggalMasuk DATE,
  IN p_NoTelp VARCHAR(20),
  OUT p_NewPegawaiID VARCHAR(10)
)
BEGIN
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

    INSERT INTO `Pegawai` (`PegawaiID`, `Nama`, `Jabatan`, `TanggalMasuk`, `NoTelp`)
    VALUES (p_NewPegawaiID, p_Nama, p_Jabatan, p_TanggalMasuk, p_NoTelp);

    COMMIT;
END$$

-- ****** Object:  StoredProcedure `dbo`.`sp_InsertRekamMedis_AutoNumber` ******
CREATE PROCEDURE `sp_InsertRekamMedis_AutoNumber`(
  IN p_IdBooking VARCHAR(11),
  IN p_PasienID VARCHAR(12),
  IN p_DokterID VARCHAR(5),
  IN p_Tanggal DATE,
  IN p_Diagnosa VARCHAR(200),
  IN p_Catatan VARCHAR(500),
  IN p_CreatedBy VARCHAR(50)
)
BEGIN
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

-- ****** Object:  StoredProcedure `dbo`.`sp_InsertRekamMedisObat` ******
CREATE PROCEDURE `sp_InsertRekamMedisObat`(
  IN p_IdRekamMedis VARCHAR(15),
  IN p_IdObat VARCHAR(7),
  IN p_Dosis VARCHAR(50),
  IN p_Frekuensi VARCHAR(50),
  IN p_LamaHari INT,
  IN p_Jumlah DECIMAL(12,2),
  IN p_CreatedBy VARCHAR(50)
)
BEGIN
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

-- ****** Object:  StoredProcedure `dbo`.`sp_InsertRekamMedisTindakan` ******
CREATE PROCEDURE `sp_InsertRekamMedisTindakan`(
  IN p_IdRekamMedis VARCHAR(15),
  IN p_IdTindakan VARCHAR(10),
  IN p_Jumlah INT,
  IN p_CreatedBy VARCHAR(50)
)
BEGIN
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

-- ****** Object:  StoredProcedure `dbo`.`Sp_UpdateJadwalStatus` ******
CREATE PROCEDURE `Sp_UpdateJadwalStatus`(
  IN p_IdJadwal VARCHAR(20),
  IN p_NewStatus VARCHAR(20)
)
BEGIN
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

-- ****** Object:  StoredProcedure `dbo`.`sp_UpdateObatTambahStok` ******
CREATE PROCEDURE `sp_UpdateObatTambahStok`(
  IN p_IdObat VARCHAR(7),
  IN p_JumlahTambah DECIMAL(12,2),
  IN p_CreatedBy VARCHAR(50)
)
BEGIN
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
