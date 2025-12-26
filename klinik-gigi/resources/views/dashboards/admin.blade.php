@extends('layouts.dashboard')

@section('title', 'Admin Dashboard - Zenith Dental')
@section('theme', 'admin')
@section('header-title', 'Dashboard Admin')
@section('header-subtitle', 'Kelola operasional klinik secara efisien')

@section('sidebar-menu')
<a href="#" class="nav-link active"><i class="fa-solid fa-gauge"></i> Overview</a>
<a href="#" class="nav-link"><i class="fa-solid fa-calendar-days"></i> Booking & Jadwal</a>
<a href="{{ route('admin.users') }}" class="nav-link"><i class="fa-solid fa-users"></i> Manajemen User</a>
<a href="#" class="nav-link"><i class="fa-solid fa-file-medical"></i> Rekam Medis</a>
<a href="#" class="nav-link"><i class="fa-solid fa-chart-line"></i> Laporan</a>
<a href="#" class="nav-link"><i class="fa-solid fa-bullhorn"></i> Promo & Artikel</a>
@endsection

@section('content')

<!-- Stats Row -->
<div class="row g-4 mb-5">
    <div class="col-md-3">
        <div class="card-custom bg-primary text-white p-4">
            <div class="d-flex justify-content-between align-items-start mb-3">
                <div>
                    <h2 class="fw-bold mb-0">1,240</h2>
                    <small class="opacity-75">Total Pasien</small>
                </div>
                <div class="bg-white bg-opacity-25 p-2 rounded">
                    <i class="fa-solid fa-users fa-lg"></i>
                </div>
            </div>
            <div class="progress bg-white bg-opacity-25" style="height: 4px;">
                <div class="progress-bar bg-white" style="width: 70%"></div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card-custom bg-success text-white p-4">
             <div class="d-flex justify-content-between align-items-start mb-3">
                <div>
                    <h2 class="fw-bold mb-0">85</h2>
                    <small class="opacity-75">Booking Hari Ini</small>
                </div>
                <div class="bg-white bg-opacity-25 p-2 rounded">
                    <i class="fa-solid fa-calendar-check fa-lg"></i>
                </div>
            </div>
             <div class="progress bg-white bg-opacity-25" style="height: 4px;">
                <div class="progress-bar bg-white" style="width: 45%"></div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card-custom bg-warning text-white p-4">
             <div class="d-flex justify-content-between align-items-start mb-3">
                <div>
                    <h2 class="fw-bold mb-0">Rp 45jt</h2>
                    <small class="opacity-75">Pendapatan Bulan Ini</small>
                </div>
                <div class="bg-white bg-opacity-25 p-2 rounded">
                    <i class="fa-solid fa-wallet fa-lg"></i>
                </div>
            </div>
             <div class="progress bg-white bg-opacity-25" style="height: 4px;">
                <div class="progress-bar bg-white" style="width: 60%"></div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card-custom bg-info text-white p-4">
             <div class="d-flex justify-content-between align-items-start mb-3">
                <div>
                    <h2 class="fw-bold mb-0">12</h2>
                    <small class="opacity-75">Dokter Aktif</small>
                </div>
                <div class="bg-white bg-opacity-25 p-2 rounded">
                    <i class="fa-solid fa-user-doctor fa-lg"></i>
                </div>
            </div>
             <div class="progress bg-white bg-opacity-25" style="height: 4px;">
                <div class="progress-bar bg-white" style="width: 90%"></div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    <!-- Booking Requests -->
    <div class="col-lg-8">
        <div class="card-custom mb-4">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h5 class="fw-bold m-0">Permintaan Booking Terbaru</h5>
                <button class="btn btn-sm btn-outline-primary">Lihat Semua</button>
            </div>
            
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Pasien</th>
                            <th>Dokter</th>
                            <th>Jadwal</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <img src="https://ui-avatars.com/api/?name=Ahmad&background=random" class="rounded-circle" width="32">
                                    <span class="fw-bold">Ahmad Dahlan</span>
                                </div>
                            </td>
                            <td>drg. Budi Santoso</td>
                            <td>27 Des 2025, 09:00</td>
                            <td><span class="badge bg-warning text-dark">Menunggu</span></td>
                            <td>
                                <button class="btn btn-sm btn-success rounded-circle"><i class="fa-solid fa-check"></i></button>
                                <button class="btn btn-sm btn-danger rounded-circle"><i class="fa-solid fa-xmark"></i></button>
                            </td>
                        </tr>
                         <tr>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <img src="https://ui-avatars.com/api/?name=Dewi&background=random" class="rounded-circle" width="32">
                                    <span class="fw-bold">Dewi Sartika</span>
                                </div>
                            </td>
                            <td>drg. Sari Mawar</td>
                            <td>27 Des 2025, 10:30</td>
                            <td><span class="badge bg-warning text-dark">Menunggu</span></td>
                            <td>
                                <button class="btn btn-sm btn-success rounded-circle"><i class="fa-solid fa-check"></i></button>
                                <button class="btn btn-sm btn-danger rounded-circle"><i class="fa-solid fa-xmark"></i></button>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <img src="https://ui-avatars.com/api/?name=Joko&background=random" class="rounded-circle" width="32">
                                    <span class="fw-bold">Joko Widodo</span>
                                </div>
                            </td>
                            <td>drg. Andi Wijaya</td>
                            <td>28 Des 2025, 14:00</td>
                            <td><span class="badge bg-success">Disetujui</span></td>
                            <td>
                                <button class="btn btn-sm btn-light border" disabled>Done</button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Quick Access -->
        <div class="row g-3">
            <div class="col-md-6">
                 <div class="card-custom h-100 p-4">
                    <h6 class="fw-bold mb-3">Manajemen Promo</h6>
                    <div class="d-flex gap-3 align-items-center bg-light p-3 rounded mb-3">
                        <img src="{{ asset('images/promo_whitening.png') }}" class="rounded" width="50" height="50" style="object-fit: cover">
                        <div>
                             <h6 class="m-0 text-truncate" style="max-width: 150px;">Promo Whitening 50%</h6>
                             <small class="text-success">Active</small>
                        </div>
                        <button class="btn btn-sm btn-light ms-auto"><i class="fa-solid fa-pen"></i></button>
                    </div>
                    <button class="btn btn-primary w-100 btn-sm"><i class="fa-solid fa-plus"></i> Tambah Promo</button>
                 </div>
            </div>
            <div class="col-md-6">
                 <div class="card-custom h-100 p-4">
                    <h6 class="fw-bold mb-3">Pengumuman Broadcast</h6>
                    <form>
                        <textarea class="form-control mb-2" rows="2" placeholder="Tulis pesan untuk semua pasien..."></textarea>
                        <button class="btn btn-dark w-100 btn-sm"><i class="fa-solid fa-paper-plane"></i> Kirim</button>
                    </form>
                 </div>
            </div>
        </div>

    </div>

    <!-- Right Column -->
    <div class="col-lg-4">
        <!-- Revenue Chart Placeholder -->
        <div class="card-custom mb-4">
             <h6 class="fw-bold mb-3">Statistik Layanan</h6>
             <div class="d-flex align-items-center justify-content-between mb-2">
                 <span>Cabut Gigi</span>
                 <span class="fw-bold">45%</span>
             </div>
             <div class="progress mb-3" style="height: 6px;">
                 <div class="progress-bar" style="width: 45%"></div>
             </div>
             
             <div class="d-flex align-items-center justify-content-between mb-2">
                 <span>Scaling</span>
                 <span class="fw-bold">30%</span>
             </div>
             <div class="progress mb-3" style="height: 6px;">
                 <div class="progress-bar bg-info" style="width: 30%"></div>
             </div>

             <div class="d-flex align-items-center justify-content-between mb-2">
                 <span>Tambal</span>
                 <span class="fw-bold">25%</span>
             </div>
             <div class="progress" style="height: 6px;">
                 <div class="progress-bar bg-warning" style="width: 25%"></div>
             </div>
        </div>

        <!-- Recent Audit -->
        <div class="card-custom">
            <h6 class="fw-bold mb-3">Aktivitas Audit</h6>
            <ul class="list-unstyled m-0">
                <li class="d-flex gap-3 mb-3">
                    <div class="bg-light p-2 rounded-circle"><i class="fa-solid fa-file-pen text-primary"></i></div>
                    <div>
                        <small class="d-block fw-bold">Update Rekam Medis</small>
                        <small class="text-muted" style="font-size: 11px;">Oleh drg. Budi • 5 menit lalu</small>
                    </div>
                </li>
                 <li class="d-flex gap-3 mb-3">
                    <div class="bg-light p-2 rounded-circle"><i class="fa-solid fa-user-plus text-success"></i></div>
                    <div>
                        <small class="d-block fw-bold">Pasien Baru Terdaftar</small>
                        <small class="text-muted" style="font-size: 11px;">Oleh Admin • 1 jam lalu</small>
                    </div>
                </li>
                 <li class="d-flex gap-3">
                    <div class="bg-light p-2 rounded-circle"><i class="fa-solid fa-trash text-danger"></i></div>
                    <div>
                        <small class="d-block fw-bold">Hapus Jadwal Dokter</small>
                        <small class="text-muted" style="font-size: 11px;">Oleh Admin • 3 jam lalu</small>
                    </div>
                </li>
            </ul>
        </div>
    </div>
</div>

@endsection
