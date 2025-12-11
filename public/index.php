<?php
// Koneksi ke Database
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

// Tentukan halaman yang aktif
$page = isset($_GET['page']) ? $_GET['page'] : 'dashboard';

// Header HTML
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Informasi Klinik Gigi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        .sidebar {
            background-color: #343a40;
            min-height: 100vh;
            padding: 20px;
        }
        .sidebar a {
            color: white;
            text-decoration: none;
            display: block;
            padding: 10px;
            margin: 5px 0;
            border-radius: 5px;
        }
        .sidebar a:hover {
            background-color: #495057;
        }
        .content {
            padding: 20px;
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-2 sidebar">
                <h4 class="text-white">Klinik Gigi</h4>
                <hr class="bg-secondary">
                <a href="?page=dashboard">Dashboard</a>
                <a href="?page=pasien">Data Pasien</a>
                <a href="?page=pegawai">Data Pegawai</a>
                <a href="?page=jadwal">Data Jadwal</a>
                <a href="?page=booking">Data Booking</a>
                <a href="?page=obat">Data Obat</a>
            </div>
            
            <!-- Content -->
            <div class="col-md-10 content">
                <h2>Selamat Datang di Sistem Klinik Gigi</h2>
                <?php
                    switch($page) {
                        case 'pasien':
                            include 'C:\xampp\htdocs\KlinikGigiLaravel\app\Http\Pages\pasien.php';
                            break;
                        case 'pegawai':
                            include 'C:\xampp\htdocs\KlinikGigiLaravel\app\Http\Pages\pegawai.php';
                            break;
                        case 'jadwal':
                            include 'C:\xampp\htdocs\KlinikGigiLaravel\app\Http\Pages\jadwal.php';
                            break;
                        case 'booking':
                            include 'C:\xampp\htdocs\KlinikGigiLaravel\app\Http\Pages\booking.php';
                            break;
                        case 'obat':
                            include 'C:\xampp\htdocs\KlinikGigiLaravel\app\Http\Pages\obat.php';
                            break;
                        default:
                            include 'C:\xampp\htdocs\KlinikGigiLaravel\app\Http\Pages\dashboard.php';
                    }
                ?>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php
$conn->close();
?>