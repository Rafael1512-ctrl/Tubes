<?php
// File: c:\xampp\htdocs\KlinikGigiLaravel\core\database.php

$host = 'localhost';
$user = 'root';
$password = '';
$database = 'klinikgigi';

try {
    $conn = new mysqli($host, $user, $password, $database);
    
    if ($conn->connect_error) {
        die("Koneksi gagal: " . $conn->connect_error);
    }
    
    $conn->set_charset("utf8mb4");
} catch (Exception $e) {
    die("Error: " . $e->getMessage());
}

return $conn;