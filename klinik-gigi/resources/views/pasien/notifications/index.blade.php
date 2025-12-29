@extends('layouts.dashboard')

@section('title', 'Notifikasi - Zenith Dental')
@section('header-title', 'Notifikasi Saya')

@section('sidebar-menu')
<a href="{{ route(Auth::user()->role . '.dashboard') }}" class="nav-link"><i class="fa-solid fa-home"></i> Beranda</a>
@if(Auth::user()->role == 'pasien')
<a href="{{ route('pasien.jadwal') }}" class="nav-link"><i class="fa-solid fa-calendar-check"></i> Jadwal Saya</a>
<a href="{{ route('pasien.rekam-medis') }}" class="nav-link"><i class="fa-solid fa-file-medical"></i> Rekam Medis</a>
@endif
<a href="{{ route(Auth::user()->role . '.notifications') }}" class="nav-link active"><i class="fa-solid fa-bell"></i> Notifikasi</a>
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-md-10">
        <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
            <div class="list-group list-group-flush">
                @forelse($notifications as $n)
                <div class="list-group-item p-4 @if(!$n->read_at) bg-light @endif">
                    <div class="d-flex justify-content-between align-items-start">
                        <div class="d-flex gap-3">
                            <div class="bg-primary bg-opacity-10 p-3 rounded-circle text-primary">
                                <i class="fa-solid fa-{{ $n->data['type'] == 'broadcast' ? 'bullhorn' : 'bell' }} fa-lg"></i>
                            </div>
                            <div>
                                <h5 class="fw-bold mb-1">{{ $n->data['title'] ?? 'Notifikasi' }}</h5>
                                <p class="text-dark mb-2">{{ $n->data['message'] ?? '' }}</p>
                                <small class="text-muted">{{ $n->created_at->format('d M Y, H:i') }} • {{ $n->created_at->diffForHumans() }}</small>
                            </div>
                        </div>
                        @if(!$n->read_at)
                        <form action="{{ route(Auth::user()->role . '.notifications.read', $n->id) }}" method="POST">
                            @csrf
                            <button class="btn btn-sm btn-outline-primary rounded-pill">Tandai Sudah Baca</button>
                        </form>
                        @endif
                    </div>
                </div>
                @empty
                <div class="p-5 text-center text-muted">
                    <i class="fa-solid fa-bell-slash fa-4x mb-4 opacity-25"></i>
                    <h4>Belum Ada Notifikasi</h4>
                    <p>Semua info terbaru akan muncul di sini.</p>
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
