<?php
// Tambah Pasien
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && $_POST['action'] == 'add') {
    $nama = $conn->real_escape_string($_POST['nama']);
    $nik = $conn->real_escape_string($_POST['nik']);
    $alamat = $conn->real_escape_string($_POST['alamat']);
    $no_telepon = $conn->real_escape_string($_POST['no_telepon']);
    $tanggal_lahir = $_POST['tanggal_lahir'];
    
    $sql = "INSERT INTO pasien (nama, nik, alamat, no_telepon, tanggal_lahir) 
            VALUES ('$nama', '$nik', '$alamat', '$no_telepon', '$tanggal_lahir')";
    
    if ($conn->query($sql)) {
        echo '<div class="alert alert-success">Data pasien berhasil ditambahkan</div>';
    } else {
        echo '<div class="alert alert-danger">Error: ' . $conn->error . '</div>';
    }
}

// Update Pasien
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && $_POST['action'] == 'edit') {
    $id_pasien = $_POST['id_pasien'];
    $nama = $conn->real_escape_string($_POST['nama']);
    $nik = $conn->real_escape_string($_POST['nik']);
    $alamat = $conn->real_escape_string($_POST['alamat']);
    $no_telepon = $conn->real_escape_string($_POST['no_telepon']);
    $tanggal_lahir = $_POST['tanggal_lahir'];
    
    $sql = "UPDATE pasien SET nama='$nama', nik='$nik', alamat='$alamat', 
            no_telepon='$no_telepon', tanggal_lahir='$tanggal_lahir' 
            WHERE id_pasien=$id_pasien";
    
    if ($conn->query($sql)) {
        echo '<div class="alert alert-success">Data pasien berhasil diupdate</div>';
    } else {
        echo '<div class="alert alert-danger">Error: ' . $conn->error . '</div>';
    }
}

// Hapus Pasien
if (isset($_GET['delete_id'])) {
    $id = $_GET['delete_id'];
    $sql = "DELETE FROM pasien WHERE id_pasien=$id";
    if ($conn->query($sql)) {
        echo '<div class="alert alert-success">Data pasien berhasil dihapus</div>';
    }
}

// Edit Pasien
$edit = null;
if (isset($_GET['edit_id'])) {
    $id = $_GET['edit_id'];
    $result = $conn->query("SELECT * FROM pasien WHERE id_pasien=$id");
    $edit = $result->fetch_assoc();
}

// Tampilkan Form Tambah/Edit
?>
<h3><?php echo $edit ? 'Edit Pasien' : 'Tambah Pasien'; ?></h3>
<form method="POST" class="mb-3">
    <input type="hidden" name="action" value="<?php echo $edit ? 'edit' : 'add'; ?>">
    <?php if ($edit): ?>
        <input type="hidden" name="id_pasien" value="<?php echo $edit['id_pasien']; ?>">
    <?php endif; ?>
    
    <div class="form-group mb-2">
        <label>Nama</label>
        <input type="text" name="nama" class="form-control" required value="<?php echo $edit['nama'] ?? ''; ?>">
    </div>
    <div class="form-group mb-2">
        <label>NIK</label>
        <input type="text" name="nik" class="form-control" required value="<?php echo $edit['nik'] ?? ''; ?>">
    </div>
    <div class="form-group mb-2">
        <label>Alamat</label>
        <textarea name="alamat" class="form-control" required><?php echo $edit['alamat'] ?? ''; ?></textarea>
    </div>
    <div class="form-group mb-2">
        <label>No Telepon</label>
        <input type="text" name="no_telepon" class="form-control" required value="<?php echo $edit['no_telepon'] ?? ''; ?>">
    </div>
    <div class="form-group mb-2">
        <label>Tanggal Lahir</label>
        <input type="date" name="tanggal_lahir" class="form-control" required value="<?php echo $edit['tanggal_lahir'] ?? ''; ?>">
    </div>
    <button type="submit" class="btn btn-primary"><?php echo $edit ? 'Update' : 'Simpan'; ?></button>
    <?php if ($edit): ?>
        <a href="?page=pasien" class="btn btn-secondary">Batal</a>
    <?php endif; ?>
</form>

<h3>Daftar Pasien</h3>
<table class="table table-striped table-hover">
    <thead class="table-dark">
        <tr>
            <th>ID</th>
            <th>Nama</th>
            <th>NIK</th>
            <th>Alamat</th>
            <th>No Telepon</th>
            <th>Tanggal Lahir</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        <?php
            $result = $conn->query("SELECT * FROM pasien ORDER BY id_pasien DESC");
            while ($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . $row['id_pasien'] . "</td>";
                echo "<td>" . $row['nama'] . "</td>";
                echo "<td>" . $row['nik'] . "</td>";
                echo "<td>" . $row['alamat'] . "</td>";
                echo "<td>" . $row['no_telepon'] . "</td>";
                echo "<td>" . $row['tanggal_lahir'] . "</td>";
                echo "<td>
                    <a href='?page=pasien&edit_id=" . $row['id_pasien'] . "' class='btn btn-sm btn-warning'>Edit</a>
                    <a href='?page=pasien&delete_id=" . $row['id_pasien'] . "' class='btn btn-sm btn-danger' onclick='return confirm(\"Yakin hapus?\")'>Hapus</a>
                </td>";
                echo "</tr>";
            }
        ?>
    </tbody>
</table>