@extends('layouts.dashboard')

@section('theme','admin')
@section('title','Tambah User')
@section('header-title','Tambah User')
@section('header-subtitle','Form untuk menambahkan akun Pegawai atau Pasien')

@section('sidebar-menu')
    <a href="/admin/dashboard" class="nav-link"><i class="fa-solid fa-home"></i> Dashboard</a>
    <a href="{{ route('admin.users') }}" class="nav-link"><i class="fa-solid fa-users"></i> Manajemen User</a>
@endsection

@section('styles')
<style>
    /* Animasi untuk alert */
    @keyframes slideDown {
        from {
            opacity: 0;
            transform: translateY(-20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .animate-slide-down {
        animation: slideDown 0.4s ease-out;
    }

    /* Styling tambahan untuk alert */
    .alert {
        border-radius: 10px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        border-left: 5px solid;
    }

    .alert-success {
        border-left-color: #28a745;
        background-color: #d4edda;
        color: #155724;
    }

    .alert-danger {
        border-left-color: #dc3545;
        background-color: #f8d7da;
        color: #721c24;
    }

    .alert-warning {
        border-left-color: #ffc107;
        background-color: #fff3cd;
        color: #856404;
    }

    .alert i {
        opacity: 0.8;
    }
</style>
@endsection

@section('content')

{{-- Alert untuk pesan sukses --}}
@if(session('success'))
<div class="alert alert-success alert-dismissible fade show animate-slide-down" role="alert" id="success-alert">
    <div class="d-flex align-items-center">
        <i class="fa-solid fa-circle-check me-3" style="font-size: 1.5rem;"></i>
        <div>
            <strong>Berhasil!</strong>
            <p class="mb-0">{{ session('success') }}</p>
        </div>
    </div>
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif

{{-- Alert untuk pesan error --}}
@if(session('error'))
<div class="alert alert-danger alert-dismissible fade show animate-slide-down" role="alert" id="error-alert">
    <div class="d-flex align-items-center">
        <i class="fa-solid fa-circle-exclamation me-3" style="font-size: 1.5rem;"></i>
        <div>
            <strong>Gagal!</strong>
            <p class="mb-0">{{ session('error') }}</p>
        </div>
    </div>
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif

{{-- Alert untuk validation errors --}}
@if($errors->any())
<div class="alert alert-warning alert-dismissible fade show animate-slide-down" role="alert" id="validation-alert">
    <div class="d-flex align-items-start">
        <i class="fa-solid fa-triangle-exclamation me-3" style="font-size: 1.5rem;"></i>
        <div>
            <strong>Perhatian!</strong>
            <p class="mb-2">Terdapat kesalahan pada input:</p>
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    </div>
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif

<div class="card">
    <div class="card-body">
        <form action="{{ route('admin.users.store') }}" method="POST">
            @csrf

            <!-- Pilih tipe user (role) -->
            <div class="mb-3">
                <label for="role" class="form-label">Tipe User (Role)</label>
                <select name="role" id="role" class="form-select" required>
                    <option value="">-- Pilih Role --</option>
                    <option value="admin">Admin</option>
                    <option value="dokter">Dokter</option>
                    <option value="pasien">Pasien</option>
                </select>
            </div>

            <!-- Data umum untuk tabel users -->
            <div class="mb-3">
                <label for="name" class="form-label">Nama Lengkap</label>
                <input type="text" name="name" class="form-control" required>
            </div>

            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" name="email" class="form-control" required>
            </div>

            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" name="password" class="form-control" required>
                <small class="text-muted">Minimal 8 karakter</small>
            </div>

            <!-- Data khusus untuk role selain pasien (admin, dokter, pegawai) -->
            <div id="non-pasien-fields" style="display:none;">
                <div class="mb-3">
                    <label for="jabatan" class="form-label">Jabatan</label>
                    <select name="jabatan" class="form-control">
                        <option value="">-- Pilih Jabatan --</option>
                        <option value="admin">Admin</option>
                        <option value="dokter gigi">Dokter Gigi</option>
                        <option value="dokter spesialis">Dokter Spesialis</option>
                        <option value="pegawai">Pegawai</option>
                    </select>
                    <small class="text-muted">Untuk role Admin, pilih Admin. Untuk role Dokter, pilih Dokter Gigi atau Dokter Spesialis. Untuk role Pegawai, pilih Pegawai.</small>
                </div>
                <div class="mb-3">
                    <label for="tanggal_masuk" class="form-label">Tanggal Masuk</label>
                    <input type="date" name="tanggal_masuk" class="form-control">
                </div>
                <div class="mb-3">
                    <label for="no_telp" class="form-label">No. Telp</label>
                    <input type="text" name="no_telp" class="form-control" placeholder="Contoh: 081234567890">
                </div>
            </div>

            <!-- Data khusus Pasien -->
            <div id="pasien-fields" style="display:none;">
                <div class="mb-3">
                    <label for="alamat" class="form-label">Alamat Lengkap</label>
                    <textarea name="alamat" class="form-control" rows="3"></textarea>
                </div>
                <div class="mb-3">
                    <label for="tanggal_lahir" class="form-label">Tanggal Lahir</label>
                    <input type="date" name="tanggal_lahir" class="form-control">
                </div>
                <div class="mb-3">
                    <label for="no_telp" class="form-label">No. Telp</label>
                    <input type="text" name="no_telp" class="form-control" placeholder="Contoh: 081234567890">
                </div>
                <div class="mb-3">
                    <label for="jenis_kelamin" class="form-label">Jenis Kelamin</label>
                    <select name="jenis_kelamin" class="form-control">
                        <option value="">-- Pilih Jenis Kelamin --</option>
                        <option value="L">Laki-laki</option>
                        <option value="P">Perempuan</option>
                    </select>
                </div>
            </div>

            <button type="submit" class="btn btn-success">Simpan</button>
            <a href="{{ route('admin.users') }}" class="btn btn-secondary">Batal</a>
        </form>
    </div>
</div>

<script>
    document.getElementById('role').addEventListener('change', function() {
        let role = this.value;
        const nonPasienFields = document.getElementById('non-pasien-fields');
        const pasienFields = document.getElementById('pasien-fields');
        
        // Helper function to toggle inputs
        function toggleInputs(container, enable) {
            const inputs = container.querySelectorAll('input, select, textarea');
            inputs.forEach(input => {
                input.disabled = !enable;
            });
        }

        // Sembunyikan semua field terlebih dahulu
        nonPasienFields.style.display = 'none';
        pasienFields.style.display = 'none';
        
        // Disable semua input di kedua container
        toggleInputs(nonPasienFields, false);
        toggleInputs(pasienFields, false);
        
        // Tampilkan field sesuai role
        if (role === 'pasien') {
            pasienFields.style.display = 'block';
            toggleInputs(pasienFields, true);
        } else if (role === 'admin' || role === 'dokter' || role === 'pegawai') {
            nonPasienFields.style.display = 'block';
            toggleInputs(nonPasienFields, true);
            
            // Jika role admin, set default jabatan ke admin
            if (role === 'admin') {
                // Mencari option dengan value admin dan men-select-nya
                let jabatanSelect = document.querySelector('select[name="jabatan"]');
                for (let i = 0; i < jabatanSelect.options.length; i++) {
                    if (jabatanSelect.options[i].value === 'admin') {
                        jabatanSelect.selectedIndex = i;
                        break;
                    }
                }
            } else if (role === 'dokter') {
                // Untuk dokter, default kosong atau bisa diatur
                let jabatanSelect = document.querySelector('select[name="jabatan"]');
                jabatanSelect.selectedIndex = 0;
            } else if (role === 'pegawai') {
                // Untuk pegawai, default ke pegawai
                let jabatanSelect = document.querySelector('select[name="jabatan"]');
                for (let i = 0; i < jabatanSelect.options.length; i++) {
                    if (jabatanSelect.options[i].value === 'pegawai') {
                        jabatanSelect.selectedIndex = i;
                        break;
                    }
                }
            }
        }
    });

    // Trigger change event on load to ensure correct state (e.g. if validation fails and old input is repopulated)
    if(document.getElementById('role').value) {
        document.getElementById('role').dispatchEvent(new Event('change'));
    }

    // Auto-dismiss alerts after 5 seconds
    const successAlert = document.getElementById('success-alert');
    const errorAlert = document.getElementById('error-alert');
    const validationAlert = document.getElementById('validation-alert');
    
    function autoDismissAlert(alertElement, delay = 5000) {
        if (alertElement) {
            setTimeout(() => {
                // Bootstrap 5 way to close alert
                const bsAlert = new bootstrap.Alert(alertElement);
                bsAlert.close();
            }, delay);
        }
    }
    
    autoDismissAlert(successAlert);
    autoDismissAlert(errorAlert);
    autoDismissAlert(validationAlert, 7000); // Validation errors stay longer
</script>
@endsection