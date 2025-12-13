@extends('layouts.app')

@section('title', 'Jadwal Tersedia')

@section('content')
<div class="container-fluid">
    <h2>Jadwal Tersedia</h2>
    
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Tanggal</th>
                <th>Jam</th>
                <th>Dokter</th>
                <th>Jabatan</th>
                <th>Status</th>
                <th>Sisa Kuota</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($jadwal as $j)
            <tr>
                <td>{{ date('d/m/Y', strtotime($j->Tanggal)) }}</td>
                <td>{{ $j->JamMulai }} - {{ $j->JamAkhir }}</td>
                <td>{{ $j->nama_dokter }}</td>
                <td>{{ $j->Jabatan }}</td>
                <td>
                    @if($j->Status == 'Available')
                        <span class="badge bg-success">Tersedia</span>
                    @else
                        <span class="badge bg-danger">{{ $j->Status }}</span>
                    @endif
                </td>
                <td>{{ $j->sisa }}</td>
                <td>
                    @if(auth()->user()->isPasien())
                    <form action="{{ route('booking.store') }}" method="POST" style="display: inline;">
                        @csrf
                        <input type="hidden" name="IdJadwal" value="{{ $j->IdJadwal }}">
                        <button type="submit" class="btn btn-sm btn-primary" 
                            onclick="return confirm('Booking jadwal ini?')">Booking</button>
                    </form>
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    
    {{ $jadwal->links() }}
</div>
@endsection