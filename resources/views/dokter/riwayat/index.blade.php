@extends('layouts.dashboard')

@section('theme','dokter')
@section('title','Riwayat Praktek')
@section('header-title','Riwayat Praktek')
@section('header-subtitle','Daftar rekam medis yang telah Anda buat')

@section('sidebar-menu')
<a href="/dokter/dashboard" class="nav-link"><i class="fa-solid fa-home"></i> Dashboard</a>
<a href="{{ route('dokter.jadwal') }}" class="nav-link"><i class="fa-solid fa-calendar-week"></i> Jadwal Saya</a>
<a href="{{ route('dokter.pasien') }}" class="nav-link"><i class="fa-solid fa-user-injured"></i> Data Pasien</a>
<a href="{{ route('dokter.riwayat') }}" class="nav-link active"><i class="fa-solid fa-history"></i> Riwayat Praktek</a>
@endsection

@section('content')

<div class="card-custom mb-4">
    <form action="{{ route('dokter.riwayat') }}" method="GET" class="row g-3 align-items-end">
        <div class="col-md-4">
            <label class="form-label small fw-bold">Filter Tanggal</label>
            <input type="date" name="tanggal" class="form-control" value="{{ request('tanggal') }}">
        </div>
        <div class="col-md-4">
            <button type="submit" class="btn btn-primary"><i class="fa-solid fa-filter me-2"></i>Filter</button>
            <a href="{{ route('dokter.riwayat') }}" class="btn btn-light border"><i class="fa-solid fa-rotate-right me-2"></i>Reset</a>
        </div>
    </form>
</div>

<div class="card-custom">
    <div class="table-responsive">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>ID RM</th>
                    <th>Tanggal</th>
                    <th>Pasien</th>
                    <th>Diagnosa</th>
                    <th>Tindakan & Obat</th>
                    <th class="text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($riwayat as $rm)
                <tr>
                    <td><code>{{ $rm->IdRekamMedis }}</code></td>
                    <td>
                        <div class="fw-bold">{{ \Carbon\Carbon::parse($rm->Tanggal)->isoFormat('D MMM YYYY') }}</div>
                    </td>
                    <td>
                        <div class="fw-bold">{{ $rm->pasien->Nama ?? '-' }}</div>
                        <small class="text-muted">{{ $rm->PasienID }}</small>
                    </td>
                    <td>
                        <div class="text-truncate" style="max-width: 200px;" title="{{ $rm->Diagnosa }}">
                            {{ $rm->Diagnosa }}
                        </div>
                    </td>
                    <td>
                        <div class="d-flex flex-wrap gap-1">
                            @foreach($rm->tindakan as $t)
                                <span class="badge bg-success-subtle text-success-emphasis border border-success-subtle">{{ $t->NamaTindakan }}</span>
                            @endforeach
                            @foreach($rm->obat as $o)
                                <span class="badge bg-info-subtle text-info-emphasis border border-info-subtle">{{ $o->NamaObat }}</span>
                            @endforeach
                        </div>
                    </td>
                    <td class="text-center">
                        <button class="btn btn-light btn-sm border" data-bs-toggle="modal" data-bs-target="#modalRM{{ $rm->IdRekamMedis }}">
                            <i class="fa-solid fa-eye me-1"></i> Detail
                        </button>
                    </td>
                </tr>

                <!-- Modal Detail -->
                <div class="modal fade" id="modalRM{{ $rm->IdRekamMedis }}" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-lg modal-dialog-centered">
                        <div class="modal-content border-0 shadow-lg" style="border-radius: 15px;">
                            <div class="modal-header border-0 bg-primary text-white" style="border-radius: 15px 15px 0 0;">
                                <h5 class="modal-title fw-bold"><i class="fa-solid fa-file-medical me-2"></i>Detail Rekam Medis</h5>
                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body p-4">
                                <div class="row mb-4">
                                    <div class="col-md-6 border-end">
                                        <h6 class="text-muted small text-uppercase fw-bold mb-3">Informasi Pasien</h6>
                                        <h5 class="fw-bold mb-1">{{ $rm->pasien->Nama }}</h5>
                                        <p class="mb-1 text-muted">{{ $rm->PasienID }}</p>
                                        <p class="mb-0">{{ $rm->pasien->JenisKelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}, {{ $rm->pasien->TanggalLahir ? \Carbon\Carbon::parse($rm->pasien->TanggalLahir)->age : '-' }} Thn</p>
                                    </div>
                                    <div class="col-md-6 ps-md-4">
                                        <h6 class="text-muted small text-uppercase fw-bold mb-3">Informasi Pemeriksaan</h6>
                                        <p class="mb-1"><strong>ID RM:</strong> {{ $rm->IdRekamMedis }}</p>
                                        <p class="mb-1"><strong>Tanggal:</strong> {{ \Carbon\Carbon::parse($rm->Tanggal)->isoFormat('dddd, D MMMM YYYY') }}</p>
                                        <p class="mb-0"><strong>Dokter:</strong> {{ $dokter->Nama }}</p>
                                    </div>
                                </div>

                                <div class="mb-4 p-3 bg-light rounded-3">
                                    <h6 class="fw-bold mb-2">Diagnosa</h6>
                                    <p class="mb-0">{{ $rm->Diagnosa }}</p>
                                </div>

                                @if($rm->Catatan)
                                <div class="mb-4">
                                    <h6 class="fw-bold mb-2">Catatan Tambahan</h6>
                                    <p class="mb-0 text-muted">{{ $rm->Catatan }}</p>
                                </div>
                                @endif

                                <div class="row">
                                    <div class="col-md-6">
                                        <h6 class="fw-bold mb-2"><i class="fa-solid fa-user-doctor me-2 text-primary"></i>Tindakan</h6>
                                        <ul class="list-group list-group-flush">
                                            @forelse($rm->tindakan as $t)
                                                <li class="list-group-item bg-transparent px-0 py-2 border-0">
                                                    <i class="fa-solid fa-check text-success me-2"></i>{{ $t->NamaTindakan }}
                                                </li>
                                            @empty
                                                <li class="list-group-item bg-transparent px-0 py-2 border-0 text-muted italic">Tidak ada tindakan</li>
                                            @endforelse
                                        </ul>
                                    </div>
                                    <div class="col-md-6">
                                        <h6 class="fw-bold mb-2"><i class="fa-solid fa-pills me-2 text-info"></i>Resep Obat</h6>
                                         <ul class="list-group list-group-flush">
                                            @forelse($rm->obat as $o)
                                                <li class="list-group-item bg-transparent px-0 py-2 border-0">
                                                    <i class="fa-solid fa-pills text-info me-2"></i>{{ $o->NamaObat }}
                                                    <div class="small text-muted ms-4">{{ $o->pivot->Dosis }} - {{ $o->pivot->Frekuensi }}</div>
                                                </li>
                                            @empty
                                                <li class="list-group-item bg-transparent px-0 py-2 border-0 text-muted italic">Tidak ada resep</li>
                                            @endforelse
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @empty
                <tr>
                    <td colspan="6" class="text-center py-5 text-muted">
                        <i class="fa-solid fa-inbox fa-3x mb-3 opacity-25"></i>
                        <p>Belum ada riwayat praktek.</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $riwayat->links() }}
    </div>
</div>

@endsection
