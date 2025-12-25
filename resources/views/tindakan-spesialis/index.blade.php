@extends('layouts.app')

@section('title', 'Kontrol Berkala')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="text-secondary fw-bold">Kontrol Berkala</h2>
            <p class="text-muted">Manage jadwal kontrol berkala pasien</p>
        </div>
        @if(auth()->user()->isDokter() || auth()->user()->isAdmin())
            <a href="{{ route('tindakan-spesialis.create') }}" class="btn btn-primary rounded-pill">
                <i class="fas fa-plus me-2"></i>Buat Kontrol Berkala
            </a>
        @endif
    </div>

    <div class="card-custom">
        <div class="card-header-custom">Daftar Kontrol Berkala</div>
        <div class="p-3">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Pasien</th>
                        <th>Dokter</th>
                        <th>Tindakan</th>
                        <th>Frekuensi</th>
                        <th>Progress</th>
                        <th>Tanggal Mulai</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($tindakanList as $tindakan)
                        <tr>
                            <td>{{ $tindakan->pasien->Nama }}</td>
                            <td>{{ $tindakan->dokter->Nama }}</td>
                            <td>{{ $tindakan->NamaTindakan }}</td>
                            <td>
                                @if($tindakan->frequency == 'weekly')
                                    <span class="badge bg-info">Mingguan</span>
                                @elseif($tindakan->frequency == 'monthly')
                                    <span class="badge bg-primary">Bulanan</span>
                                @else
                                    <span class="badge bg-secondary">{{ $tindakan->custom_days }} hari</span>
                                @endif
                            </td>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <div class="progress flex-grow-1" style="height: 20px; min-width: 100px;">
                                        <div class="progress-bar {{ $tindakan->getProgressPercentage() == 100 ? 'bg-success' : 'bg-primary' }}" 
                                             role="progressbar" 
                                             style="width: {{ $tindakan->getProgressPercentage() }}%" 
                                             aria-valuenow="{{ $tindakan->getProgressPercentage() }}" 
                                             aria-valuemin="0" aria-valuemax="100">
                                            {{ $tindakan->completed_sessions }}/{{ $tindakan->total_sessions }}
                                        </div>
                                    </div>
                                    <small class="text-muted">{{ $tindakan->getProgressPercentage() }}%</small>
                                </div>
                            </td>
                            <td>{{ \Carbon\Carbon::parse($tindakan->start_date)->format('d M Y') }}</td>
                            <td>
                                @if($tindakan->status == 'active')
                                    <span class="badge bg-success">Aktif</span>
                                @elseif($tindakan->status == 'completed')
                                    <span class="badge bg-primary">Selesai</span>
                                @else
                                    <span class="badge bg-secondary">Dibatalkan</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('tindakan-spesialis.show', $tindakan->IdTindakanSpesialis) }}" 
                                   class="btn btn-sm btn-outline-primary">
                                    <i class="fas fa-eye me-1"></i>Detail
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted py-4">
                                <i class="fas fa-calendar-times fa-3x mb-3 d-block"></i>
                                Belum ada kontrol berkala
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <!-- Pagination -->
            <div class="mt-3">
                {{ $tindakanList->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
