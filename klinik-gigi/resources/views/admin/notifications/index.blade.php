@extends('layouts.dashboard')

@section('theme', 'admin')
@section('title', 'Notifikasi - Zenith Dental')
@section('header-title', 'Pusat Notifikasi')
@section('header-subtitle', 'Lihat semua notifikasi dan pengumuman')

@section('sidebar-menu')
<a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}"><i class="fa-solid fa-home"></i> Dashboard</a>
<a href="{{ route('admin.booking') }}" class="nav-link {{ request()->routeIs('admin.booking*') ? 'active' : '' }}"><i class="fa-solid fa-calendar-days"></i> Booking & Jadwal</a>
<a href="{{ route('admin.pasien') }}" class="nav-link {{ request()->routeIs('admin.pasien*') ? 'active' : '' }}"><i class="fa-solid fa-hospital-user"></i> Data Pasien</a>
<a href="{{ route('admin.obat') }}" class="nav-link {{ request()->routeIs('admin.obat*') ? 'active' : '' }}"><i class="fa-solid fa-pills"></i> Data Obat</a>
<a href="{{ route('admin.users') }}" class="nav-link {{ request()->routeIs('admin.users*') ? 'active' : '' }}"><i class="fa-solid fa-users"></i> Manajemen User</a>
<a href="{{ route('admin.broadcast.index') }}" class="nav-link {{ request()->routeIs('admin.broadcast*') ? 'active' : '' }}"><i class="fa-solid fa-bullhorn"></i> Broadcast</a>
<a href="{{ route('admin.notifications') }}" class="nav-link {{ request()->routeIs('admin.notifications*') ? 'active' : '' }}"><i class="fa-solid fa-bell"></i> Notifikasi</a>
<a href="{{ route('admin.pembayaran') }}" class="nav-link {{ request()->routeIs('admin.pembayaran*') ? 'active' : '' }}"><i class="fa-solid fa-file-invoice-dollar"></i> Pembayaran</a>
<a href="{{ route('admin.laporan') }}" class="nav-link {{ request()->routeIs('admin.laporan*') ? 'active' : '' }}"><i class="fa-solid fa-chart-line"></i> Laporan</a>
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-md-10">
        <div class="card-custom">
            <h5 class="fw-bold mb-4">Daftar Notifikasi</h5>
            <div class="list-group list-group-flush rounded-3">
                @forelse($notifications as $n)
                <div class="list-group-item p-4 border-bottom @if(!$n->read_at) bg-ligh @endif" style="@if(!$n->read_at) background-color: #f8f9fa; @endif">
                    <div class="d-flex justify-content-between align-items-start">
                        <div class="d-flex gap-3">
                            <div class="bg-primary bg-opacity-10 p-3 rounded-circle text-primary" style="height: fit-content;">
                                <i class="fa-solid fa-{{ isset($n->data['type']) && $n->data['type'] == 'broadcast' ? 'bullhorn' : 'bell' }} fa-lg"></i>
                            </div>
                            <div>
                                <h5 class="fw-bold mb-1">{{ $n->data['title'] ?? 'Notifikasi' }}</h5>
                                <p class="text-dark mb-2">{{ $n->data['message'] ?? '' }}</p>
                                <small class="text-muted">
                                    <i class="fa-regular fa-clock me-1"></i>
                                    {{ $n->created_at->format('d M Y, H:i') }} • {{ $n->created_at->diffForHumans() }}
                                </small>
                            </div>
                        </div>
                        @if(!$n->read_at)
                        <form action="{{ route('admin.notifications.read', $n->id) }}" method="POST">
                            @csrf
                            <button class="btn btn-sm btn-outline-primary rounded-pill">Tandai Sudah Baca</button>
                        </form>
                        @else
                            <span class="badge bg-secondary opacity-50"><i class="fa-solid fa-check-double me-1"></i> Dibaca</span>
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
