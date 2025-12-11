<?php
// Tambah Jadwal
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && $_POST['action'] == 'add') {
    $id_pegawai = $_POST['id_pegawai'];
    $hari = $conn->real_escape_string($_POST['hari']);
    $jam_mulai = $_POST['jam_mulai'];
    $jam_selesai = $_POST['jam_selesai'];
    
    $sql = "INSERT INTO jadwal (id_pegawai, hari, jam_mulai, jam_selesai) 
            VALUES ('$id_pegawai', '$hari', '$jam_mulai', '$jam_selesai')";
    
    if ($conn->query($sql)) {
        echo '<div class="alert alert-success">Data jadwal berhasil ditambahkan</div>';
    } else {
        echo '<div class="alert alert-danger">Error: ' . $conn->error . '</div>';
    }
}

// Update Jadwal
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && $_POST['action'] == 'edit') {
    $id_jadwal = $_POST['id_jadwal'];
    $id_pegawai = $_POST['id_pegawai'];
    $hari = $conn->real_escape_string($_POST['hari']);
    $jam_mulai = $_POST['jam_mulai'];
    $jam_selesai = $_POST['jam_selesai'];
    
    $sql = "UPDATE jadwal SET id_pegawai='$id_pegawai', hari='$hari', 
            jam_mulai='$jam_mulai', jam_selesai='$jam_selesai' WHERE id_jadwal=$id_jadwal";
    
    if ($conn->query($sql)) {
        echo '<div class="alert alert-success">Data jadwal berhasil diupdate</div>';
    } else {
        echo '<div class="alert alert-danger">Error: ' . $conn->error . '</div>';
    }
}

// Hapus Jadwal
if (isset($_GET['delete_id'])) {
    $id = $_GET['delete_id'];
    $sql = "DELETE FROM jadwal WHERE id_jadwal=$id";
    if ($conn->query($sql)) {
        echo '<div class="alert alert-success">Data jadwal berhasil dihapus</div>';
    }
}

// Edit Jadwal
$edit = null;
if (isset($_GET['edit_id'])) {
    $id = $_GET['edit_id'];
    $result = $conn->query("SELECT * FROM jadwal WHERE id_jadwal=$id");
    $edit = $result->fetch_assoc();
}
?>
<h3><?php echo $edit ? 'Edit Jadwal' : 'Tambah Jadwal'; ?></h3>
<form method="POST" class="mb-3">
    <input type="hidden" name="action" value="<?php echo $edit ? 'edit' : 'add'; ?>">
    <?php if ($edit): ?>
        <input type="hidden" name="id_jadwal" value="<?php echo $edit['id_jadwal']; ?>">
    <?php endif; ?>
    
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
        <label>Hari</label>
        <select name="hari" class="form-control" required>
            <option value="">Pilih Hari</option>
            <?php
                $hari_list = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'];
                foreach ($hari_list as $h) {
                    $selected = ($edit && $edit['hari'] == $h) ? 'selected' : '';
                    echo "<option value='$h' $selected>$h</option>";
                }
            ?>
        </select>
    </div>
    <div class="form-group mb-2">
        <label>Jam Mulai</label>
        <input type="time" name="jam_mulai" class="form-control" required value="<?php echo $edit['jam_mulai'] ?? ''; ?>">
    </div>
    <div class="form-group mb-2">
        <label>Jam Selesai</label>
        <input type="time" name="jam_selesai" class="form-control" required value="<?php echo $edit['jam_selesai'] ?? ''; ?>">
    </div>
    <button type="submit" class="btn btn-primary"><?php echo $edit ? 'Update' : 'Simpan'; ?></button>
    <?php if ($edit): ?>
        <a href="?page=jadwal" class="btn btn-secondary">Batal</a>
    <?php endif; ?>
</form>

<h3>Daftar Jadwal</h3>
<table class="table table-striped table-hover">
    <thead class="table-dark">
        <tr>
            <th>ID</th>
            <th>Pegawai</th>
            <th>Hari</th>
            <th>Jam Mulai</th>
            <th>Jam Selesai</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        <?php
            $result = $conn->query("SELECT j.*, p.nama FROM jadwal j 
                                   JOIN pegawai p ON j.id_pegawai = p.id_pegawai 
                                   ORDER BY j.id_jadwal DESC");
            while ($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . $row['id_jadwal'] . "</td>";
                echo "<td>" . $row['nama'] . "</td>";
                echo "<td>" . $row['hari'] . "</td>";
                echo "<td>" . $row['jam_mulai'] . "</td>";
                echo "<td>" . $row['jam_selesai'] . "</td>";
                echo "<td>
                    <a href='?page=jadwal&edit_id=" . $row['id_jadwal'] . "' class='btn btn-sm btn-warning'>Edit</a>
                    <a href='?page=jadwal&delete_id=" . $row['id_jadwal'] . "' class='btn btn-sm btn-danger' onclick='return confirm(\"Yakin hapus?\")'>Hapus</a>
                </td>";
                echo "</tr>";
            }
        ?>
    </tbody>
</table>