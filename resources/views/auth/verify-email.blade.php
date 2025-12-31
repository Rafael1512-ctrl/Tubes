@extends('layouts.dashboard')

@section('title', 'Verifikasi Email')
@section('header-title', 'Verifikasi Email')
@section('header-subtitle', 'Satu langkah lagi untuk mengakses dashboard')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card-custom text-center p-5">
            <div class="bg-primary bg-opacity-10 text-primary rounded-circle d-inline-flex align-items-center justify-content-center mb-4" style="width: 80px; height: 80px;">
                <i class="fas fa-envelope-open-text fa-3x"></i>
            </div>
            <h3 class="fw-bold mb-3">Verifikasi Email Anda</h3>
            <p class="text-muted mb-4">
                Terima kasih telah mendaftar! Sebelum memulai, silakan verifikasi alamat email Anda dengan mengklik link yang baru saja kami kirimkan ke email Anda.
            </p>

            @if (session('message'))
                <div class="alert alert-success border-0 rounded-4 mb-4">
                    {{ session('message') }}
                </div>
            @endif

            <div class="d-flex flex-column gap-3">
                <form method="POST" action="{{ route('verification.send') }}">
                    @csrf
                    <button type="submit" class="btn btn-primary rounded-pill px-5 py-2 fw-bold">
                        Kirim Ulang Email Verifikasi
                    </button>
                </form>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="btn btn-link text-muted text-decoration-none small">
                        Logout
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
