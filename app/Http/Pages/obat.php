<?php
// Tambah Obat
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && $_POST['action'] == 'add') {
    $nama_obat = $conn->real_escape_string($_POST['nama_obat']);
    $dosis = $conn->real_escape_string($_POST['dosis']);
    $harga = $_POST['harga'];
    $stok = $_POST['stok'];
    $keterangan = $conn->real_escape_string($_POST['keterangan']);
    
    $sql = "INSERT INTO obat (nama_obat, dosis, harga, stok, keterangan) 
            VALUES ('$nama_obat', '$dosis', '$harga', '$stok', '$keterangan')";
    
    if ($conn->query($sql)) {
        echo '<div class="alert alert-success">Data obat berhasil ditambahkan</div>';
    } else {
        echo '<div class="alert alert-danger">Error: ' . $conn->error . '</div>';
    }
}

// Update Obat
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && $_POST['action'] == 'edit') {
    $id_obat = $_POST['id_obat'];
    $nama_obat = $conn->real_escape_string($_POST['nama_obat']);
    $dosis = $conn->real_escape_string($_POST['dosis']);
    $harga = $_POST['harga'];
    $stok = $_POST['stok'];
    $keterangan = $conn->real_escape_string($_POST['keterangan']);
    
    $sql = "UPDATE obat SET nama_obat='$nama_obat', dosis='$dosis', harga='$harga', 
            stok='$stok', keterangan='$keterangan' WHERE id_obat=$id_obat";
    
    if ($conn->query($sql)) {
        echo '<div class="alert alert-success">Data obat berhasil diupdate</div>';
    } else {
        echo '<div class="alert alert-danger">Error: ' . $conn->error . '</div>';
    }
}

// Hapus Obat
if (isset($_GET['delete_id'])) {
    $id = $_GET['delete_id'];
    $sql = "DELETE FROM obat WHERE id_obat=$id";
    if ($conn->query($sql)) {
        echo '<div class="alert alert-success">Data obat berhasil dihapus</div>';
    }
}

// Edit Obat
$edit = null;
if (isset($_GET['edit_id'])) {
    $id = $_GET['edit_id'];
    $result = $conn->query("SELECT * FROM obat WHERE id_obat=$id");
    $edit = $result->fetch_assoc();
}

// Cek struktur tabel untuk mengetahui nama kolom primary key
$columns = $conn->query("SHOW COLUMNS FROM obat");
$primary_key = 'id_obat'; // default
while ($col = $columns->fetch_assoc()) {
    if ($col['Key'] == 'PRI') {
        $primary_key = $col['Field'];
        break;
    }
}
?>
<h3><?php echo $edit ? 'Edit Obat' : 'Tambah Obat'; ?></h3>
<form method="POST" class="mb-3">
    <input type="hidden" name="action" value="<?php echo $edit ? 'edit' : 'add'; ?>">
    <?php if ($edit): ?>
        <input type="hidden" name="id_obat" value="<?php echo $edit[$primary_key]; ?>">
    <?php endif; ?>
    
    <div class="form-group mb-2">
        <label>Nama Obat</label>
        <input type="text" name="nama_obat" class="form-control" required value="<?php echo $edit['nama_obat'] ?? ''; ?>">
    </div>
    <div class="form-group mb-2">
        <label>Dosis</label>
        <input type="text" name="dosis" class="form-control" required value="<?php echo $edit['dosis'] ?? ''; ?>">
    </div>
    <div class="form-group mb-2">
        <label>Harga</label>
        <input type="number" name="harga" class="form-control" step="0.01" required value="<?php echo $edit['harga'] ?? ''; ?>">
    </div>
    <div class="form-group mb-2">
        <label>Stok</label>
        <input type="number" name="stok" class="form-control" required value="<?php echo $edit['stok'] ?? ''; ?>">
    </div>
    <div class="form-group mb-2">
        <label>Keterangan</label>
        <textarea name="keterangan" class="form-control"><?php echo $edit['keterangan'] ?? ''; ?></textarea>
    </div>
    <button type="submit" class="btn btn-primary"><?php echo $edit ? 'Update' : 'Simpan'; ?></button>
    <?php if ($edit): ?>
        <a href="?page=obat" class="btn btn-secondary">Batal</a>
    <?php endif; ?>
</form>

<h3>Daftar Obat</h3>
<table class="table table-striped table-hover">
    <thead class="table-dark">
        <tr>
            <th>ID</th>
            <th>Nama Obat</th>
            <th>Dosis</th>
            <th>Harga</th>
            <th>Stok</th>
            <th>Keterangan</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        <?php
            $result = $conn->query("SELECT * FROM obat");
            
            if (!$result) {
                echo '<tr><td colspan="7" class="text-danger">Error Query: ' . $conn->error . '</td></tr>';
            } else if ($result->num_rows === 0) {
                echo '<tr><td colspan="7" class="text-warning">Tidak ada data obat</td></tr>';
            } else {
                while ($row = $result->fetch_assoc()) {
                    $stok_color = $row['stok'] < 5 ? 'danger' : 'success';
                    echo "<tr>";
                    echo "<td>" . $row[$primary_key] . "</td>";
                    echo "<td>" . $row['nama_obat'] . "</td>";
                    echo "<td>" . $row['dosis'] . "</td>";
                    echo "<td>Rp " . number_format($row['harga'], 0, ',', '.') . "</td>";
                    echo "<td><span class='badge bg-$stok_color'>" . $row['stok'] . "</span></td>";
                    echo "<td>" . $row['keterangan'] . "</td>";
                    echo "<td>
                        <a href='?page=obat&edit_id=" . $row[$primary_key] . "' class='btn btn-sm btn-warning'>Edit</a>
                        <a href='?page=obat&delete_id=" . $row[$primary_key] . "' class='btn btn-sm btn-danger' onclick='return confirm(\"Yakin hapus?\")'>Hapus</a>
                    </td>";
                    echo "</tr>";
                }
            }
        ?>
    </tbody>
</table>