<?php
echo "<div class='alert alert-info'>";
echo "<h3>Selamat Datang di Sistem Informasi Klinik Gigi</h3>";
echo "<p>Gunakan menu di sebelah kiri untuk navigasi.</p>";
echo "</div>";

// Tampilkan statistik
$pasien = $conn->query("SELECT COUNT(*) as total FROM Pasien")->fetch_assoc();
$pegawai = $conn->query("SELECT COUNT(*) as total FROM Pegawai")->fetch_assoc();
$booking = $conn->query("SELECT COUNT(*) as total FROM Booking")->fetch_assoc();
$obat = $conn->query("SELECT COUNT(*) as total FROM Obat")->fetch_assoc();

echo "<div class='row'>";
echo "<div class='col-md-3'>";
echo "<div class='card bg-primary text-white'>";
echo "<div class='card-body'>";
echo "<h5 class='card-title'>Total Pasien</h5>";
echo "<h2>" . $pasien['total'] . "</h2>";
echo "</div>";
echo "</div>";
echo "</div>";

echo "<div class='col-md-3'>";
echo "<div class='card bg-success text-white'>";
echo "<div class='card-body'>";
echo "<h5 class='card-title'>Total Pegawai</h5>";
echo "<h2>" . $pegawai['total'] . "</h2>";
echo "</div>";
echo "</div>";
echo "</div>";

echo "<div class='col-md-3'>";
echo "<div class='card bg-warning text-white'>";
echo "<div class='card-body'>";
echo "<h5 class='card-title'>Total Booking</h5>";
echo "<h2>" . $booking['total'] . "</h2>";
echo "</div>";
echo "</div>";
echo "</div>";

echo "<div class='col-md-3'>";
echo "<div class='card bg-info text-white'>";
echo "<div class='card-body'>";
echo "<h5 class='card-title'>Total Obat</h5>";
echo "<h2>" . $obat['total'] . "</h2>";
echo "</div>";
echo "</div>";
echo "</div>";
echo "</div>";
?>