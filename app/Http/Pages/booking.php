<?php
// Tambah Booking
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && $_POST['action'] == 'add') {
    $id_pasien = $_POST['id_pasien'];
    $id_pegawai = $_POST['id_pegawai'];
    $tanggal_booking = $_POST['tanggal_booking'];
    $waktu_booking = $_POST['waktu_booking'];
    $keluhan = $conn->real_escape_string($_POST['keluhan']);
    $status = $conn->real_escape_string($_POST['status']);
    
    $sql = "INSERT INTO booking (id_pasien, id_pegawai, tanggal_booking, waktu_booking, keluhan, status) 
            VALUES ('$id_pasien', '$id_pegawai', '$tanggal_booking', '$waktu_booking', '$keluhan', '$status')";
    
    if ($conn->query($sql)) {
        echo '<div class="alert alert-success">Data booking berhasil ditambahkan</div>';
    } else {
        echo '<div class="alert alert-danger">Error: ' . $conn->error . '</div>';
    }
}

// Update Booking
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && $_POST['action'] == 'edit') {
    $id_booking = $_POST['id_booking'];
    $id_pasien = $_POST['id_pasien'];
    $id_pegawai = $_POST['id_pegawai'];
    $tanggal_booking = $_POST['tanggal_booking'];
    $waktu_booking = $_POST['waktu_booking'];
    $keluhan = $conn->real_escape_string($_POST['keluhan']);
    $status = $conn->real_escape_string($_POST['status']);
    
    $sql = "UPDATE booking SET id_pasien='$id_pasien', id_pegawai='$id_pegawai', 
            tanggal_booking='$tanggal_booking', waktu_booking='$waktu_booking', 
            keluhan='$keluhan', status='$status' WHERE id_booking=$id_booking";
    
    if ($conn->query($sql)) {
        echo '<div class="alert alert-success">Data booking berhasil diupdate</div>';
    } else {
        echo '<div class="alert alert-danger">Error: ' . $conn->error . '</div>';
    }
}

// Hapus Booking
if (isset($_GET['delete_id'])) {
    $id = $_GET['delete_id'];
    $sql = "DELETE FROM booking WHERE id_booking=$id";
    if ($conn->query($sql)) {
        echo '<div class="alert alert-success">Data booking berhasil dihapus</div>';
    }
}

// Edit Booking
$edit = null;
if (isset($_GET['edit_id'])) {
    $id = $_GET['edit_id'];
    $result = $conn->query("SELECT * FROM booking WHERE id_booking=$id");
    $edit = $result->fetch_assoc();
}
?>
<h3><?php echo $edit ? 'Edit Booking' : 'Tambah Booking'; ?></h3>
<form method="POST" class="mb-3">
    <input type="hidden" name="action" value="<?php echo $edit ? 'edit' : 'add'; ?>">
    <?php if ($edit): ?>
        <input type="hidden" name="id_booking" value="<?php echo $edit['id_booking']; ?>">
    <?php endif; ?>
    
    <div class="form-group mb-2">
        <label>Pasien</label>
        <select name="id_pasien" class="form-control" required>
            <option value="">Pilih Pasien</option>
            <?php
                $pasien = $conn->query("SELECT id_pasien, nama FROM pasien ORDER BY nama");
                while ($row = $pasien->fetch_assoc()) {
                    $selected = ($edit && $edit['id_pasien'] == $row['id_pasien']) ? 'selected' : '';
                    echo "<option value='" . $row['id_pasien'] . "' $selected>" . $row['nama'] . "</option>";
                }
            ?>
        </select>
    </div>
    <div class="form-group mb-2">
        <label>Pegawai</label>
        <select name="id_pegawai" class="form-control" required>
            <option value="">Pilih Pegawai</option>
            <?php
                $pegawai = $conn->query("SELECT id_pegawai, nama FROM pegawai ORDER BY nama");
                while ($row = $pegawai->fetch_assoc()) {
                    $selected = ($edit && $edit['id_pegawai'] == $row['id_pegawai']) ? 'selected' : '';
                    echo "<option value='" . $row['id_pegawai'] . "' $selected>" . $row['nama'] . "</option>";
                }
            ?>
        </select>
    </div>
    <div class="form-group mb-2">
        <label>Tanggal Booking</label>
        <input type="date" name="tanggal_booking" class="form-control" required value="<?php echo $edit['tanggal_booking'] ?? ''; ?>">
    </div>
    <div class="form-group mb-2">
        <label>Waktu Booking</label>
        <input type="time" name="waktu_booking" class="form-control" required value="<?php echo $edit['waktu_booking'] ?? ''; ?>">
    </div>
    <div class="form-group mb-2">
        <label>Keluhan</label>
        <textarea name="keluhan" class="form-control" required><?php echo $edit['keluhan'] ?? ''; ?></textarea>
    </div>
    <div class="form-group mb-2">
        <label>Status</label>
        <select name="status" class="form-control" required>
            <option value="">Pilih Status</option>
            <option value="Pending" <?php echo ($edit && $edit['status'] == 'Pending') ? 'selected' : ''; ?>>Pending</option>
            <option value="Dikonfirmasi" <?php echo ($edit && $edit['status'] == 'Dikonfirmasi') ? 'selected' : ''; ?>>Dikonfirmasi</option>
            <option value="Selesai" <?php echo ($edit && $edit['status'] == 'Selesai') ? 'selected' : ''; ?>>Selesai</option>
            <option value="Dibatalkan" <?php echo ($edit && $edit['status'] == 'Dibatalkan') ? 'selected' : ''; ?>>Dibatalkan</option>
        </select>
    </div>
    <button type="submit" class="btn btn-primary"><?php echo $edit ? 'Update' : 'Simpan'; ?></button>
    <?php if ($edit): ?>
        <a href="?page=booking" class="btn btn-secondary">Batal</a>
    <?php endif; ?>
</form>

<h3>Daftar Booking</h3>
<table class="table table-striped table-hover">
    <thead class="table-dark">
        <tr>
            <th>ID</th>
            <th>Pasien</th>
            <th>Pegawai</th>
            <th>Tanggal</th>
            <th>Waktu</th>
            <th>Keluhan</th>
            <th>Status</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        <?php
            $result = $conn->query("SELECT b.*, p.nama as nama_pasien, pe.nama as nama_pegawai 
                                   FROM booking b 
                                   JOIN pasien p ON b.id_pasien = p.id_pasien 
                                   JOIN pegawai pe ON b.id_pegawai = pe.id_pegawai 
                                   ORDER BY b.id_booking DESC");
            while ($row = $result->fetch_assoc()) {
                $status_color = '';
                if ($row['status'] == 'Pending') $status_color = 'warning';
                elseif ($row['status'] == 'Dikonfirmasi') $status_color = 'info';
                elseif ($row['status'] == 'Selesai') $status_color = 'success';
                else $status_color = 'danger';
                
                echo "<tr>";
                echo "<td>" . $row['id_booking'] . "</td>";
                echo "<td>" . $row['nama_pasien'] . "</td>";
                echo "<td>" . $row['nama_pegawai'] . "</td>";
                echo "<td>" . $row['tanggal_booking'] . "</td>";
                echo "<td>" . $row['waktu_booking'] . "</td>";
                echo "<td>" . substr($row['keluhan'], 0, 30) . "..." . "</td>";
                echo "<td><span class='badge bg-$status_color'>" . $row['status'] . "</span></td>";
                echo "<td>
                    <a href='?page=booking&edit_id=" . $row['id_booking'] . "' class='btn btn-sm btn-warning'>Edit</a>
                    <a href='?page=booking&delete_id=" . $row['id_booking'] . "' class='btn btn-sm btn-danger' onclick='return confirm(\"Yakin hapus?\")'>Hapus</a>
                </td>";
                echo "</tr>";
            }
        ?>
    </tbody>
</table>