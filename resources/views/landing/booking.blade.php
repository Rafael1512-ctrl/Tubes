@extends('layouts.app_landing')

@section('title', 'Booking Janji Temu')

@section('content')
<section class="py-5 bg-light" style="min-height: 100vh; padding-top: 120px !important;">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card border-0 shadow-lg rounded-3 overflow-hidden">
                    <div class="card-header bg-primary text-white p-4 text-center">
                        <h3 class="mb-0 fw-bold">Booking Konsultasi</h3>
                        <p class="mb-0 opacity-75">Isi form di bawah ini untuk menjadwalkan kunjungan Anda.</p>
                    </div>
                    <div class="card-body p-5">
                        <form action="#" method="POST">
                            @csrf
                            
                            <!-- Step 1: Personal Info -->
                            <h5 class="fw-bold mb-4 text-primary border-bottom pb-2">1. Data Diri</h5>
                            <div class="row g-3 mb-4">
                                <div class="col-md-6">
                                    <label class="form-label fw-bold small">Nama Lengkap</label>
                                    <input type="text" class="form-control form-control-lg bg-light border-0" placeholder="Contoh: Budi Santoso" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-bold small">Nomor WhatsApp</label>
                                    <input type="tel" class="form-control form-control-lg bg-light border-0" placeholder="08..." required>
                                </div>
                            </div>

                            <!-- Step 2: Layanan -->
                            <h5 class="fw-bold mb-4 text-primary border-bottom pb-2">2. Pilih Layanan</h5>
                            <div class="row g-3 mb-4">
                                <div class="col-md-12">
                                    <label class="form-label fw-bold small">Jenis Perawatan</label>
                                    <select class="form-select form-select-lg bg-light border-0" required>
                                        <option selected disabled>Pilih Layanan...</option>
                                        <option value="veneer">Porcelain Veneer</option>
                                        <option value="whitening">Teeth Whitening</option>
                                        <option value="ortho">Kawat Gigi / Invisalign</option>
                                        <option value="checkup">Check-up Rutin</option>
                                        <option value="konsultasi">Konsultasi Estetika</option>
                                    </select>
                                </div>
                            </div>

                             <!-- Step 3: Jadwal (Simplified) -->
                             <h5 class="fw-bold mb-4 text-primary border-bottom pb-2">3. Rencana Kunjungan</h5>
                             <div class="row g-3 mb-4">
                                 <div class="col-md-6">
                                     <label class="form-label fw-bold small">Tanggal</label>
                                     <input type="date" class="form-control form-control-lg bg-light border-0" required>
                                 </div>
                                 <div class="col-md-6">
                                     <label class="form-label fw-bold small">Jam Preferensi</label>
                                     <select class="form-select form-select-lg bg-light border-0">
                                         <option>Pagi (09:00 - 12:00)</option>
                                         <option>Siang (13:00 - 16:00)</option>
                                         <option>Sore (17:00 - 20:00)</option>
                                     </select>
                                 </div>
                                 <div class="col-12">
                                     <label class="form-label fw-bold small">Keluhan / Catatan Tambahan</label>
                                     <textarea class="form-control bg-light border-0" rows="3" placeholder="Ceritakan sedikit tentang kondisi gigi Anda atau harapan Anda..."></textarea>
                                 </div>
                             </div>

                             <div class="d-grid">
                                 <button type="submit" class="btn btn-primary btn-lg rounded-pill fw-bold shadow-sm py-3">
                                     <i class="fas fa-paper-plane me-2"></i> Kirim Booking
                                 </button>
                             </div>

                             <p class="text-center text-muted small mt-4">
                                 *Konfirmasi jadwal akan dikirimkan melalui WhatsApp dalam waktu maksimal 1x24 jam.
                             </p>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
