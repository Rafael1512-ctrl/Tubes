@extends('layouts.app')

@section('title', 'Detail Kontrol Berkala')

@section('content')
<div class="container-fluid">
    <div class="mb-4">
        <a href="{{ route('tindakan-spesialis.index') }}" class="btn btn-outline-secondary btn-sm mb-2">
            <i class="fas fa-arrow-left me-2"></i>Kembali
        </a>
        <h2 class="text-secondary fw-bold">Detail Kontrol Berkala</h2>
    </div>

    <!-- Info Card -->
    <div class="row mb-4">
        <div class="col-md-8">
            <div class="card-custom">
                <div class="card-header-custom">Informasi Tindakan</div>
                <div class="p-4">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <strong>Pasien:</strong><br>
                            {{ $tindakan->pasien->Nama }}
                        </div>
                        <div class="col-md-6">
                            <strong>Dokter:</strong><br>
                            {{ $tindakan->dokter->Nama }}
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-12">
                            <strong>Nama Tindakan:</strong><br>
                            {{ $tindakan->NamaTindakan }}
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <strong>Frekuensi:</strong><br>
                            @if($tindakan->frequency == 'weekly')
                                Mingguan
                            @elseif($tindakan->frequency == 'monthly')
                                Bulanan
                            @else
                                Setiap {{ $tindakan->custom_days }} hari
                            @endif
                        </div>
                        <div class="col-md-4">
                            <strong>Total Sesi:</strong><br>
                            {{ $tindakan->total_sessions }}
                        </div>
                        <div class="col-md-4">
                            <strong>Tanggal Mulai:</strong><br>
                            {{ \Carbon\Carbon::parse($tindakan->start_date)->format('d M Y') }}
                        </div>
                    </div>
                    @if($tindakan->plan_goal)
                    <div class="row">
                        <div class="col-md-12">
                            <strong>Rencana/Tujuan:</strong><br>
                            <p class="text-muted">{{ $tindakan->plan_goal }}</p>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card-custom border-start border-4 border-primary">
                <div class="p-4 text-center">
                    <h5 class="text-uppercase text-muted mb-3">Progress</h5>
                    <div class="display-4 text-primary mb-2">{{ $tindakan->getProgressPercentage() }}%</div>
                    <div class="progress mb-3" style="height: 25px;">
                        <div class="progress-bar {{ $tindakan->status == 'completed' ? 'bg-success' : 'bg-primary' }}" 
                             role="progressbar" 
                             style="width: {{ $tindakan->getProgressPercentage() }}%">
                            {{ $tindakan->completed_sessions }}/{{ $tindakan->total_sessions }}
                        </div>
                    </div>
                    <span class="badge 
                        @if($tindakan->status == 'active') bg-success
                        @elseif($tindakan->status == 'completed') bg-primary
                        @else bg-secondary
                        @endif fs-6">
                        {{ ucfirst($tindakan->status) }}
                    </span>
                </div>
            </div>
        </div>
    </div>

    <!-- Sessions Table -->
    <div class="card-custom">
        <div class="card-header-custom">Daftar Sesi Pertemuan</div>
        <div class="p-3">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Sesi</th>
                        <th>Tanggal Terjadwal</th>
                        <th>Tanggal Aktual</th>
                        <th>Status</th>
                        <th>Catatan</th>
                        @if(auth()->user()->isDokter() || auth()->user()->isAdmin())
                        <th>Aksi</th>
                        @endif
                    </tr>
                </thead>
                <tbody>
                    @foreach($tindakan->sessions as $sesi)
                        <tr>
                            <td><strong>#{{ $sesi->session_number }}</strong></td>
                            <td>{{ \Carbon\Carbon::parse($sesi->scheduled_date)->format('d M Y') }}</td>
                            <td>
                                @if($sesi->actual_date)
                                    {{ \Carbon\Carbon::parse($sesi->actual_date)->format('d M Y') }}
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>
                                @if($sesi->status == 'scheduled')
                                    <span class="badge bg-info">Terjadwal</span>
                                @elseif($sesi->status == 'attended')
                                    <span class="badge bg-success">Hadir</span>
                                @elseif($sesi->status == 'completed')
                                    <span class="badge bg-primary">Selesai</span>
                                @elseif($sesi->status == 'rescheduled')
                                    <span class="badge bg-warning text-dark">Dijadwalkan Ulang</span>
                                @else
                                    <span class="badge bg-danger">Batal</span>
                                @endif
                            </td>
                            <td>
                                @if($sesi->notes)
                                    <small>{{ Str::limit($sesi->notes, 50) }}</small>
                                @elseif($sesi->reschedule_reason)
                                    <small class="text-warning"><i>{{ Str::limit($sesi->reschedule_reason, 50) }}</i></small>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            @if(auth()->user()->isDokter() || auth()->user()->isAdmin())
                            <td>
                                @if($sesi->status == 'scheduled' || $sesi->status == 'rescheduled')
                                    <div class="btn-group btn-group-sm">
                                        <!-- Mark Attended -->
                                        <button type="button" class="btn btn-outline-success" data-bs-toggle="modal" data-bs-target="#attendModal{{ $sesi->id }}">
                                            <i class="fas fa-check"></i>
                                        </button>
                                        <!-- Reschedule -->
                                        <button type="button" class="btn btn-outline-warning" data-bs-toggle="modal" data-bs-target="#rescheduleModal{{ $sesi->id }}">
                                            <i class="fas fa-calendar-alt"></i>
                                        </button>
                                        <!-- Cancel -->
                                        <button type="button" class="btn btn-outline-danger" data-bs-toggle="modal" data-bs-target="#cancelModal{{ $sesi->id }}">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </div>

                                    <!-- Attend Modal -->
                                    <div class="modal fade" id="attendModal{{ $sesi->id }}" tabindex="-1">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <form action="{{ route('tindakan-spesialis.session.update', $sesi->id) }}" method="POST">
                                                    @csrf
                                                    <input type="hidden" name="status" value="attended">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">Tandai Hadir - Sesi #{{ $sesi->session_number }}</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="mb-3">
                                                            <label class="form-label">Catatan (Opsional)</label>
                                                            <textarea name="notes" class="form-control" rows="3" placeholder="Catatan sesi..."></textarea>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                        <button type="submit" class="btn btn-success">Tandai Hadir</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Reschedule Modal -->
                                    <div class="modal fade" id="rescheduleModal{{ $sesi->id }}" tabindex="-1">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <form action="{{ route('tindakan-spesialis.session.reschedule', $sesi->id) }}" method="POST">
                                                    @csrf
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">Reschedule - Sesi #{{ $sesi->session_number }}</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="mb-3">
                                                            <label class="form-label">Tanggal Baru <span class="text-danger">*</span></label>
                                                            <input type="date" name="new_date" class="form-control" min="{{ date('Y-m-d', strtotime('+1 day')) }}" required>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label class="form-label">Alasan <span class="text-danger">*</span></label>
                                                            <textarea name="reason" class="form-control" rows="2" placeholder="Alasan reschedule..." required></textarea>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                        <button type="submit" class="btn btn-warning">Reschedule</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Cancel Modal -->
                                    <div class="modal fade" id="cancelModal{{ $sesi->id }}" tabindex="-1">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <form action="{{ route('tindakan-spesialis.session.cancel', $sesi->id) }}" method="POST">
                                                    @csrf
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">Batalkan Session - Sesi #{{ $sesi->session_number }}</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="alert alert-warning">
                                                            <i class="fas fa-exclamation-triangle me-2"></i>
                                                            Yakin membatalkan sesi ini?
                                                        </div>
                                                        <div class="mb-3">
                                                            <label class="form-label">Alasan <span class="text-danger">*</span></label>
                                                            <textarea name="reason" class="form-control" rows="2" placeholder="Alasan pembatalan..." required></textarea>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tidak</button>
                                                        <button type="submit" class="btn btn-danger">Ya, Batalkan</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            @endif
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
