@extends('layouts.dashboard')

@section('theme','admin')
@section('title','Edit User')
@section('header-title','Edit User')
@section('header-subtitle','Ubah data akun dokter/pasien/pegawai')

@section('sidebar-menu')
    <a href="/admin/dashboard" class="nav-link"><i class="fa-solid fa-home"></i> Dashboard</a>
    <a href="{{ route('admin.users') }}" class="nav-link"><i class="fa-solid fa-users"></i> Manajemen User</a>
@endsection

@section('styles')
<style>
    @keyframes slideDown {
        from { opacity: 0; transform: translateY(-20px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .animate-slide-down {
        animation: slideDown 0.4s ease-out;
    }
    .alert {
        border-radius: 10px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        border-left: 5px solid;
    }
    .alert-success { border-left-color: #28a745; background-color: #d4edda; color: #155724; }
    .alert-danger { border-left-color: #dc3545; background-color: #f8d7da; color: #721c24; }
    .alert-warning { border-left-color: #ffc107; background-color: #fff3cd; color: #856404; }
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

@if($errors->any())
<div class="alert alert-warning alert-dismissible fade show animate-slide-down" role="alert">
    <div class="d-flex align-items-start">
        <i class="fa-solid fa-triangle-exclamation me-3" style="font-size: 1.5rem;"></i>
        <div>
            <strong>Perhatian!</strong>
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

<div class="card shadow-sm border-0 rounded-4">
    <div class="card-header bg-white py-3 border-0">
        <h5 class="fw-bold mb-0">Form Edit User</h5>
    </div>
    <div class="card-body p-4">
        <form action="{{ route('admin.users.update', $user->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="row g-3">
                <div class="col-md-6">
                    <label for="name" class="form-label fw-bold">Nama Lengkap</label>
                    <input type="text" name="name" class="form-control rounded-3" value="{{ old('name', $user->name) }}" required>
                </div>

                <div class="col-md-6">
                    <label for="email" class="form-label fw-bold">Email</label>
                    <input type="email" name="email" class="form-control rounded-3" value="{{ old('email', $user->email) }}" required>
                </div>

                <div class="col-md-6">
                    <label for="role" class="form-label fw-bold">Tipe User (Role)</label>
                    @php
                        $displayRole = $user->role;
                        if($user->role == 'dokter') {
                            if($user->pegawai && $user->pegawai->Jabatan == 'dokter gigi') $displayRole = 'dokter_gigi';
                            elseif($user->pegawai && $user->pegawai->Jabatan == 'dokter spesialis') $displayRole = 'dokter_spesialis';
                        }
                    @endphp
                    <select name="role" id="role" class="form-select rounded-3" required>
                        <option value="">-- Pilih Role --</option>
                        <option value="admin" {{ old('role', $displayRole) == 'admin' ? 'selected' : '' }}>Admin</option>
                        <option value="dokter_gigi" {{ old('role', $displayRole) == 'dokter_gigi' ? 'selected' : '' }}>Dokter Gigi</option>
                        <option value="dokter_spesialis" {{ old('role', $displayRole) == 'dokter_spesialis' ? 'selected' : '' }}>Dokter Spesialis</option>
                        <option value="pasien" {{ old('role', $displayRole) == 'pasien' ? 'selected' : '' }}>Pasien</option>
                    </select>
                </div>

                <div class="col-md-6">
                    <label for="password" class="form-label fw-bold">Password <small class="text-muted fw-normal">(Kosongkan jika tidak diubah)</small></label>
                    <input type="password" name="password" class="form-control rounded-3">
                </div>

                <hr class="my-4 opacity-50">

                <!-- Data khusus Pegawai/Admin/Dokter -->
                <div id="non-pasien-fields" class="col-12" style="display:none;">
                    <div class="row g-3">
                        <div class="col-md-4" id="jabatan-wrapper">
                            <label for="jabatan" class="form-label fw-bold">Jabatan</label>
                            <select name="jabatan" id="jabatan" class="form-select rounded-3">
                                <option value="">-- Pilih Jabatan --</option>
                                <option value="admin" {{ old('jabatan', $user->pegawai->Jabatan ?? '') == 'admin' ? 'selected' : '' }}>Admin</option>
                                <option value="dokter gigi" {{ old('jabatan', $user->pegawai->Jabatan ?? '') == 'dokter gigi' ? 'selected' : '' }}>Dokter Gigi</option>
                                <option value="dokter spesialis" {{ old('jabatan', $user->pegawai->Jabatan ?? '') == 'dokter spesialis' ? 'selected' : '' }}>Dokter Spesialis</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label for="tanggal_masuk" class="form-label fw-bold">Tanggal Masuk</label>
                            <input type="date" name="tanggal_masuk" class="form-control rounded-3" value="{{ old('tanggal_masuk', $user->pegawai->TanggalMasuk ?? '') }}">
                        </div>
                        <div class="col-md-4">
                            <label for="no_telp_pegawai" class="form-label fw-bold">No. Telp</label>
                            <input type="text" name="no_telp" id="no_telp_pegawai" class="form-control rounded-3" value="{{ old('no_telp', $user->pegawai->NoTelp ?? '') }}">
                        </div>
                    </div>
                </div>

                <!-- Data khusus Pasien -->
                <div id="pasien-fields" class="col-12" style="display:none;">
                    <div class="row g-3">
                        <div class="col-md-12">
                            <label for="alamat" class="form-label fw-bold">Alamat Lengkap</label>
                            <textarea name="alamat" class="form-control rounded-3" rows="2">{{ old('alamat', $user->pasien->Alamat ?? '') }}</textarea>
                        </div>
                        <div class="col-md-4">
                            <label for="tanggal_lahir" class="form-label fw-bold">Tanggal Lahir</label>
                            <input type="date" name="tanggal_lahir" class="form-control rounded-3" value="{{ old('tanggal_lahir', $user->pasien->TanggalLahir ?? '') }}">
                        </div>
                        <div class="col-md-4">
                            <label for="no_telp_pasien" class="form-label fw-bold">No. Telp</label>
                            <input type="text" name="no_telp" id="no_telp_pasien" class="form-control rounded-3" value="{{ old('no_telp', $user->pasien->NoTelp ?? '') }}">
                        </div>
                        <div class="col-md-4">
                            <label for="jenis_kelamin" class="form-label fw-bold">Jenis Kelamin</label>
                            <select name="jenis_kelamin" class="form-select rounded-3">
                                <option value="">-- Pilih Jenis Kelamin --</option>
                                <option value="L" {{ old('jenis_kelamin', $user->pasien->JenisKelamin ?? '') == 'L' ? 'selected' : '' }}>Laki-laki</option>
                                <option value="P" {{ old('jenis_kelamin', $user->pasien->JenisKelamin ?? '') == 'P' ? 'selected' : '' }}>Perempuan</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="col-12 mt-4">
                    <button type="submit" class="btn btn-primary px-4 py-2 rounded-pill shadow-sm">
                        <i class="fa-solid fa-save me-2"></i> Update Data User
                    </button>
                    <a href="{{ route('admin.users') }}" class="btn btn-light px-4 py-2 rounded-pill border ms-2">Batal</a>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
    document.getElementById('role').addEventListener('change', function() {
        let role = this.value;
        const nonPasienFields = document.getElementById('non-pasien-fields');
        const pasienFields = document.getElementById('pasien-fields');
        const jabatanWrapper = document.getElementById('jabatan-wrapper');
        const jabatanSelect = document.getElementById('jabatan');
        
        function toggleInputs(container, enable) {
            const inputs = container.querySelectorAll('input, select, textarea');
            inputs.forEach(input => { input.disabled = !enable; });
        }

        nonPasienFields.style.display = 'none';
        pasienFields.style.display = 'none';
        toggleInputs(nonPasienFields, false);
        toggleInputs(pasienFields, false);
        
        if (role === 'pasien') {
            pasienFields.style.display = 'block';
            toggleInputs(pasienFields, true);
        } else if (role !== '') {
            nonPasienFields.style.display = 'block';
            toggleInputs(nonPasienFields, true);
            
            if (role === 'admin') {
                jabatanSelect.value = 'admin';
                jabatanWrapper.style.display = 'none';
            } else if (role === 'dokter_gigi') {
                jabatanSelect.value = 'dokter gigi';
                jabatanWrapper.style.display = 'none';
            } else if (role === 'dokter_spesialis') {
                jabatanSelect.value = 'dokter spesialis';
                jabatanWrapper.style.display = 'none';
            } else {
                jabatanWrapper.style.display = 'block';
            }
        }
    });

    if(document.getElementById('role').value) {
        document.getElementById('role').dispatchEvent(new Event('change'));
    }

    function autoDismissAlert(alertElement, delay = 5000) {
        if (alertElement) {
            setTimeout(() => {
                const bsAlert = new bootstrap.Alert(alertElement);
                bsAlert.close();
            }, delay);
        }
    }
    autoDismissAlert(document.getElementById('success-alert'));
    autoDismissAlert(document.getElementById('error-alert'));
</script>
@endsection