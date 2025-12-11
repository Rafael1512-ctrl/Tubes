<?php
// src/config.php

// Pengaturan Database
define('DB_HOST', 'localhost');
define('DB_NAME', 'klinikgigi'); // Sesuaikan dengan nama database Anda
define('DB_USER', 'root');         // User default XAMPP
define('DB_PASS', '');             // Password default XAMPP kosong

// Pengaturan Zona Waktu
date_default_timezone_set('Asia/Jakarta');

// Fungsi untuk membuat koneksi PDO
function getDbConnection() {
    $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4";
    $options = [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false,
    ];

    try {
        return new PDO($dsn, DB_USER, DB_PASS, $options);
    } catch (PDOException $e) {
        // Di lingkungan produksi, jangan tampilkan error detail ke user
        // Cukup catat error ke log file.
        error_log($e->getMessage());
        die('Koneksi database gagal. Silakan coba lagi nanti.');
    }
}

// Mulai session untuk manajemen login
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}