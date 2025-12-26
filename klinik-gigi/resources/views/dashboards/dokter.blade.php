@extends('layouts.dashboard')

@section('title', 'Dashboard Dokter - Zenith Dental')
@section('theme', 'dokter')
@section('header-title', 'Area Dokter')
@section('header-subtitle', 'Selamat bertugas, drg. ' . (Auth::user()->name ?? 'Dokter'))

@section('sidebar-menu')
<a href="#" class="nav-link active"><i class="fa-solid fa-stethoscope"></i> Praktek Hari Ini</a>
<a href="#" class="nav-link"><i class="fa-solid fa-calendar-week"></i> Jadwal Saya</a>
<a href="#" class="nav-link"><i class="fa-solid fa-user-injured"></i> Data Pasien</a>
<a href="#" class="nav-link"><i class="fa-solid fa-book-medical"></i> Input Rekam Medis</a>
<a href="#" class="nav-link"><i class="fa-solid fa-comments"></i> Konsultasi</a>
@endsection

@section('content')

<div class="row g-4">
    <!-- Main Column: Schedule & Patient Queue -->
    <div class="col-lg-8">
        
        <!-- Today's Overview -->
        <div class="row g-3 mb-4">
            <div class="col-md-4">
                 <div class="card-custom bg-info-subtle border-0 h-100">
                    <h6 class="text-info-emphasis fw-bold">Jadwal Hari Ini</h6>
                    <h2 class="fw-bold mb-0 text-info-emphasis">Pagi</h2>
                    <small>09:00 - 12:00</small>
                 </div>
            </div>
            <div class="col-md-4">
                 <div class="card-custom bg-primary-subtle border-0 h-100">
                    <h6 class="text-primary-emphasis fw-bold">Total Pasien</h6>
                    <h2 class="fw-bold mb-0 text-primary-emphasis">8</h2>
                    <small>3 Selesai • 5 Menunggu</small>
                 </div>
            </div>
            <div class="col-md-4">
                 <div class="card-custom bg-success-subtle border-0 h-100">
                    <h6 class="text-success-emphasis fw-bold">Status Ruangan</h6>
                    <h2 class="fw-bold mb-0 text-success-emphasis">Ready</h2>
                    <small>Ruang Praktek 2</small>
                 </div>
            </div>
        </div>

        <!-- Patient Queue -->
        <div class="card-custom mb-4">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="fw-bold m-0"><i class="fa-solid fa-list-ol me-2"></i> Antrian Pasien</h5>
                <span class="badge bg-primary">Hari Ini, 26 Des</span>
            </div>
            
            <div class="list-group list-group-flush rounded-3">
                <!-- Active Patient -->
                <div class="list-group-item p-3 border border-primary bg-primary-subtle rounded mb-2">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="d-flex align-items-center gap-3">
                            <div class="bg-primary text-white fs-4 fw-bold rounded-circle d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">1</div>
                            <div>
                                <h6 class="fw-bold mb-1">Ahmad Dahlan <span class="badge bg-danger ms-2">Urgent</span></h6>
                                <small class="text-muted d-block"><i class="fa-solid fa-clock me-1"></i> 09:15 - Cabut Gigi</small>
                            </div>
                        </div>
                        <button class="btn btn-primary"><i class="fa-solid fa-play me-1"></i> Mulai</button>
                    </div>
                </div>

                <!-- Next Patients -->
                <div class="list-group-item p-3 mb-2 rounded bg-white border">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="d-flex align-items-center gap-3">
                            <div class="bg-light text-secondary fs-5 fw-bold rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">2</div>
                            <div>
                                <h6 class="fw-bold mb-1">Dewi Sartika</h6>
                                <small class="text-muted"><i class="fa-solid fa-clock me-1"></i> 10:00 - Konsultasi</small>
                            </div>
                        </div>
                        <button class="btn btn-outline-secondary btn-sm" disabled>Menunggu</button>
                    </div>
                </div>

                <div class="list-group-item p-3 mb-2 rounded bg-white border opacity-75">
                     <div class="d-flex justify-content-between align-items-center">
                        <div class="d-flex align-items-center gap-3">
                            <div class="bg-light text-secondary fs-5 fw-bold rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">3</div>
                            <div>
                                <h6 class="fw-bold mb-1">Bambang Pamungkas</h6>
                                <small class="text-muted"><i class="fa-solid fa-clock me-1"></i> 10:45 - Scaling</small>
                            </div>
                        </div>
                         <button class="btn btn-outline-secondary btn-sm" disabled>Menunggu</button>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <!-- Right Column: Medical Record Input & Stats -->
    <div class="col-lg-4">
        
        <!-- Quick Note -->
        <div class="card-custom mb-4 bg-white border-0 shadow-lg">
            <h6 class="fw-bold mb-3 text-primary"><i class="fa-solid fa-pen-to-square"></i> Catatan Dokter</h6>
            <form>
                <div class="mb-3">
                    <label class="form-label small fw-bold">Pilih Pasien</label>
                    <select class="form-select">
                        <option>Ahmad Dahlan</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label small fw-bold">Diagnosa</label>
                    <textarea class="form-control" rows="2" placeholder="Contoh: Karies gigi molar 2 kiri..."></textarea>
                </div>
                 <div class="mb-3">
                    <label class="form-label small fw-bold">Tindakan</label>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="check1">
                        <label class="form-check-label small" for="check1">Scaling</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="check2">
                        <label class="form-check-label small" for="check2">Tambal</label>
                    </div>
                     <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="check3">
                        <label class="form-check-label small" for="check3">Resep Obat</label>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary w-100">Simpan Rekam Medis</button>
            </form>
        </div>

        <!-- Performance -->
        <div class="card-custom">
            <h6 class="fw-bold mb-3">Rating Pasien</h6>
            <div class="text-center py-3">
                <h1 class="fw-bold display-4 text-warning mb-0">4.9</h1>
                <div class="text-warning mb-2">
                    <i class="fa-solid fa-star"></i>
                    <i class="fa-solid fa-star"></i>
                    <i class="fa-solid fa-star"></i>
                    <i class="fa-solid fa-star"></i>
                    <i class="fa-solid fa-star"></i>
                </div>
                <small class="text-muted">Berdasarkan 45 ulasan bulan ini</small>
            </div>
            <hr>
            <h6 class="fw-bold mb-2 small">Ulasan Terbaru</h6>
            <div class="bg-light p-2 rounded">
                <p class="mb-1 small fst-italic">"Dokternya teliti dan tidak sakit."</p>
                <small class="text-muted fw-bold" style="font-size: 10px;">- Anonim, 2 jam lalu</small>
            </div>
        </div>

    </div>
</div>

@endsection
