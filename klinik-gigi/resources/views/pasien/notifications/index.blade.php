@extends('layouts.dashboard')

@section('theme', 'pasien')
@section('title', 'Notifikasi - Zenith Dental')
@section('header-title', 'Pusat Notifikasi')
@section('header-subtitle', 'Info terbaru mengenai jadwal dan pengumuman klinik.')

@section('sidebar-menu')
    <a href="{{ route('pasien.dashboard') }}" class="nav-link">
        <i class="fa-solid fa-house"></i> Dashboard
    </a>
    <a href="{{ route('pasien.booking.create') }}" class="nav-link">
        <i class="fa-solid fa-calendar-plus"></i> Buat Janji Temu
    </a>
    <a href="{{ route('pasien.jadwal') }}" class="nav-link">
        <i class="fa-solid fa-calendar-days"></i> Jadwal & Riwayat
    </a>
    <a href="{{ route('pasien.rekam-medis') }}" class="nav-link">
        <i class="fa-solid fa-file-medical"></i> Rekam Medis
    </a>
    <a href="{{ route('pasien.notifications') }}" class="nav-link active">
        <i class="fa-solid fa-bell"></i> Notifikasi
    </a>
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-10">
        <div class="card-custom bg-white border-0 shadow-sm overflow-hidden p-0">
            <div class="list-group list-group-flush">
                @forelse($notifications as $n)
                <div class="list-group-item p-4 border-light-subtle transition-all @if(!$n->read_at) bg-primary bg-opacity-10 @endif">
                    <div class="d-flex justify-content-between align-items-start">
                        <div class="d-flex gap-4">
                            <div class="bg-white p-3 rounded-4 shadow-sm text-primary">
                                <i class="fa-solid fa-{{ ($n->data['type'] ?? '') == 'broadcast' ? 'bullhorn' : 'bell' }} fa-lg"></i>
                            </div>
                            <div>
                                <h6 class="fw-bold mb-1 text-dark">{{ $n->data['title'] ?? 'Notifikasi' }}</h6>
                                <p class="text-muted small mb-2">{{ $n->data['message'] ?? '' }}</p>
                                <div class="d-flex align-items-center gap-3">
                                    <small class="text-muted fw-semibold">
                                        <i class="fa-solid fa-clock me-1"></i> {{ $n->created_at->diffForHumans() }}
                                    </small>
                                    <small class="text-muted">
                                        <i class="fa-solid fa-calendar-alt me-1"></i> {{ $n->created_at->format('d M Y, H:i') }}
                                    </small>
                                </div>
                            </div>
                        </div>
                        <div class="d-flex flex-column align-items-end gap-2">
                            @if(!$n->read_at)
                            <span class="badge bg-primary rounded-pill mb-2">Baru</span>
                            <form action="{{ route('pasien.notifications.read', $n->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-link text-primary p-0 fw-bold text-decoration-none">
                                    Tandai telah dibaca
                                </button>
                            </form>
                            @else
                            <span class="text-muted small italic"><i class="fa-solid fa-check-double me-1"></i> Dibaca</span>
                            @endif
                        </div>
                    </div>
                </div>
                @empty
                <div class="p-5 text-center text-muted py-5">
                    <i class="fa-solid fa-bell-slash fa-4x mb-4 opacity-25"></i>
                    <h4 class="fw-bold">Belum Ada Notifikasi</h4>
                    <p>Kami akan memberitahu Anda ketika ada update mengenai janji temu atau promo menarik!</p>
                </div>
                @endforelse
            </div>
            
            @if($notifications->hasPages())
            <div class="p-4 border-top">
                {{ $notifications->links() }}
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
