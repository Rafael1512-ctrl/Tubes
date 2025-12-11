<?php
$sql = "SELECT * FROM Pegawai";
$result = $conn->query($sql);
?>

<h3>Data Pegawai</h3>
<table class="table table-bordered table-striped">
    <thead class="table-dark">
        <tr>
            <th>ID Pegawai</th>
            <th>Nama</th>
            <th>Jabatan</th>
            <th>Tanggal Masuk</th>
            <th>No Telp</th>
        </tr>
    </thead>
    <tbody>
        <?php
        if ($result && $result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . $row['PegawaiID'] . "</td>";
                echo "<td>" . $row['Nama'] . "</td>";
                echo "<td>" . $row['Jabatan'] . "</td>";
                echo "<td>" . $row['TanggalMasuk'] . "</td>";
                echo "<td>" . $row['NoTelp'] . "</td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='5'>Tidak ada data</td></tr>";
        }
        ?>
    </tbody>
</table>

<?php
// Tambah Pegawai
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && $_POST['action'] == 'add') {
    $nama = $conn->real_escape_string($_POST['nama']);
    $jabatan = $conn->real_escape_string($_POST['jabatan']);
    $notelp = $conn->real_escape_string($_POST['notelp']);
    $tanggalmasuk = $_POST['tanggalmasuk'];
    
    $sql = "INSERT INTO Pegawai (Nama, Jabatan, NoTelp, TanggalMasuk) 
            VALUES ('$nama', '$jabatan', '$notelp', '$tanggalmasuk')";
    
    if ($conn->query($sql)) {
        echo '<div class="alert alert-success">Data pegawai berhasil ditambahkan</div>';
    } else {
        echo '<div class="alert alert-danger">Error: ' . $conn->error . '</div>';
    }
}

// Update Pegawai
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action']) && $_POST['action'] == 'edit') {
    $pegawaiid = $_POST['pegawaiid'];
    $nama = $conn->real_escape_string($_POST['nama']);
    $jabatan = $conn->real_escape_string($_POST['jabatan']);
    $notelp = $conn->real_escape_string($_POST['notelp']);
    $tanggalmasuk = $_POST['tanggalmasuk'];
    
    $sql = "UPDATE Pegawai SET Nama='$nama', Jabatan='$jabatan', NoTelp='$notelp', 
            TanggalMasuk='$tanggalmasuk' WHERE PegawaiID=$pegawaiid";
    
    if ($conn->query($sql)) {
        echo '<div class="alert alert-success">Data pegawai berhasil diupdate</div>';
    } else {
        echo '<div class="alert alert-danger">Error: ' . $conn->error . '</div>';
    }
}

// Hapus Pegawai
if (isset($_GET['delete_id'])) {
    $id = $_GET['delete_id'];
    $sql = "DELETE FROM Pegawai WHERE PegawaiID=$id";
    if ($conn->query($sql)) {
        echo '<div class="alert alert-success">Data pegawai berhasil dihapus</div>';
    }
}

// Edit Pegawai
$edit = null;
if (isset($_GET['edit_id'])) {
    $id = $_GET['edit_id'];
    $result = $conn->query("SELECT * FROM Pegawai WHERE PegawaiID=$id");
    $edit = $result->fetch_assoc();
}
?>

<h3><?php echo $edit ? 'Edit Pegawai' : 'Tambah Pegawai'; ?></h3>
<form method="POST" class="mb-3">
    <input type="hidden" name="action" value="<?php echo $edit ? 'edit' : 'add'; ?>">
    <?php if ($edit): ?>
        <input type="hidden" name="pegawaiid" value="<?php echo $edit['PegawaiID']; ?>">
    <?php endif; ?>
    
    <div class="form-group mb-2">
        <label>Nama</label>
        <input type="text" name="nama" class="form-control" required value="<?php echo $edit['Nama'] ?? ''; ?>">
    </div>
    <div class="form-group mb-2">
        <label>Jabatan</label>
        <input type="text" name="jabatan" class="form-control" required value="<?php echo $edit['Jabatan'] ?? ''; ?>">
    </div>
    <div class="form-group mb-2">
        <label>No Telepon</label>
        <input type="text" name="notelp" class="form-control" required value="<?php echo $edit['NoTelp'] ?? ''; ?>">
    </div>
    <div class="form-group mb-2">
        <label>Tanggal Masuk</label>
        <input type="date" name="tanggalmasuk" class="form-control" required value="<?php echo $edit['TanggalMasuk'] ?? ''; ?>">
    </div>
    <button type="submit" class="btn btn-primary"><?php echo $edit ? 'Update' : 'Simpan'; ?></button>
    <?php if ($edit): ?>
        <a href="?page=pegawai" class="btn btn-secondary">Batal</a>
    <?php endif; ?>
</form>

<h3>Daftar Pegawai</h3>
<table class="table table-striped table-hover">
    <thead class="table-dark">
        <tr>
            <th>ID Pegawai</th>
            <th>Nama</th>
            <th>Jabatan</th>
            <th>No Telp</th>
            <th>Tanggal Masuk</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        <?php
            $result = $conn->query("SELECT * FROM Pegawai ORDER BY PegawaiID DESC");
            
            if (!$result) {
                echo '<tr><td colspan="6" class="text-danger">Error: ' . $conn->error . '</td></tr>';
            } else if ($result->num_rows === 0) {
                echo '<tr><td colspan="6" class="text-warning">Tidak ada data pegawai</td></tr>';
            } else {
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . $row['PegawaiID'] . "</td>";
                    echo "<td>" . $row['Nama'] . "</td>";
                    echo "<td>" . $row['Jabatan'] . "</td>";
                    echo "<td>" . $row['NoTelp'] . "</td>";
                    echo "<td>" . $row['TanggalMasuk'] . "</td>";
                    echo "<td>
                        <a href='?page=pegawai&edit_id=" . $row['PegawaiID'] . "' class='btn btn-sm btn-warning'>Edit</a>
                        <a href='?page=pegawai&delete_id=" . $row['PegawaiID'] . "' class='btn btn-sm btn-danger' onclick='return confirm(\"Yakin hapus?\")'>Hapus</a>
                    </td>";
                    echo "</tr>";"</tr>";
                }
            }
        ?>
    </tbody>
</table>