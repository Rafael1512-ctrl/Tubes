@extends('layouts.app')

@section('title', 'Tambah Rekam Medis')

@section('content')
<div class="container-fluid">
    <h2>Tambah Rekam Medis</h2>
    
    <form method="POST" action="{{ route('rekam-medis.store') }}">
        @csrf
        <div class="row">
            <div class="col-md-6">
                <div class="mb-3">
                    <label for="IdBooking" class="form-label">Pilih Booking</label>
                    <select class="form-control" id="IdBooking" name="IdBooking" required>
                        <option value="">Pilih Booking</option>
                        @foreach($bookings as $booking)
                        <option value="{{ $booking->IdBooking }}">
                            {{ $booking->IdBooking }} - {{ $booking->pasien->Nama }} - {{ $booking->jadwal->Tanggal }}
                        </option>
                        @endforeach
                    </select>
                </div>
                
                <div class="mb-3">
                    <label for="Diagnosa" class="form-label">Diagnosa</label>
                    <textarea class="form-control" id="Diagnosa" name="Diagnosa" rows="3" required></textarea>
                </div>
                
                <div class="mb-3">
                    <label for="Anamnesa" class="form-label">Anamnesa</label>
                    <textarea class="form-control" id="Anamnesa" name="Anamnesa" rows="3"></textarea>
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="mb-3">
                    <label for="Catatan" class="form-label">Catatan</label>
                    <textarea class="form-control" id="Catatan" name="Catatan" rows="3"></textarea>
                </div>
                
                <div class="mb-3">
                    <label for="ResepDokter" class="form-label">Resep Dokter</label>
                    <textarea class="form-control" id="ResepDokter" name="ResepDokter" rows="3"></textarea>
                </div>
            </div>
        </div>
        
        <!-- Bagian Obat -->
        <div class="card mb-3">
            <div class="card-header">Resep Obat</div>
            <div class="card-body">
                <div id="obat-container">
                    <div class="row mb-2">
                        <div class="col-md-3">
                            <label>Obat</label>
                            <select class="form-control" name="obat[]">
                                <option value="">Pilih Obat</option>
                                @foreach($obat as $o)
                                <option value="{{ $o->IdObat }}">{{ $o->NamaObat }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label>Dosis</label>
                            <input type="text" class="form-control" name="dosis[]" placeholder="1x1">
                        </div>
                        <div class="col-md-2">
                            <label>Frekuensi</label>
                            <input type="text" class="form-control" name="frekuensi[]" placeholder="3x sehari">
                        </div>
                        <div class="col-md-2">
                            <label>Lama (hari)</label>
                            <input type="number" class="form-control" name="lama_hari[]" min="1">
                        </div>
                        <div class="col-md-2">
                            <label>Jumlah</label>
                            <input type="number" class="form-control" name="jumlah_obat[]" min="1">
                        </div>
                    </div>
                </div>
                <button type="button" class="btn btn-sm btn-secondary" id="tambah-obat">Tambah Obat</button>
            </div>
        </div>
        
        <!-- Bagian Tindakan -->
        <div class="card mb-3">
            <div class="card-header">Tindakan</div>
            <div class="card-body">
                <div id="tindakan-container">
                    @foreach($tindakan as $t)
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="tindakan[]" value="{{ $t->IdTindakan }}" id="tindakan{{ $t->IdTindakan }}">
                        <label class="form-check-label" for="tindakan{{ $t->IdTindakan }}">
                            {{ $t->NamaTindakan }} - Rp {{ number_format($t->Harga, 0, ',', '.') }}
                        </label>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
        
        <button type="submit" class="btn btn-primary">Simpan Rekam Medis</button>
        <a href="{{ route('rekam-medis.index') }}" class="btn btn-secondary">Batal</a>
    </form>
</div>

<script>
document.getElementById('tambah-obat').addEventListener('click', function() {
    const container = document.getElementById('obat-container');
    const newRow = document.createElement('div');
    newRow.className = 'row mb-2';
    newRow.innerHTML = `
        <div class="col-md-3">
            <select class="form-control" name="obat[]">
                <option value="">Pilih Obat</option>
                @foreach($obat as $o)
                <option value="{{ $o->IdObat }}">{{ $o->NamaObat }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-2">
            <input type="text" class="form-control" name="dosis[]" placeholder="1x1">
        </div>
        <div class="col-md-2">
            <input type="text" class="form-control" name="frekuensi[]" placeholder="3x sehari">
        </div>
        <div class="col-md-2">
            <input type="number" class="form-control" name="lama_hari[]" min="1">
        </div>
        <div class="col-md-2">
            <input type="number" class="form-control" name="jumlah_obat[]" min="1">
        </div>
        <div class="col-md-1">
            <button type="button" class="btn btn-danger btn-sm hapus-obat">Hapus</button>
        </div>
    `;
    container.appendChild(newRow);
    
    // Tambah event listener untuk tombol hapus
    newRow.querySelector('.hapus-obat').addEventListener('click', function() {
        container.removeChild(newRow);
    });
});
</script>
@endsection