@extends('layouts.app')

@section('title', 'Data Booking')

@section('content')
<div class="container-fluid">
    <h2>Data Booking</h2>
    
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID Booking</th>
                <th>Tanggal</th>
                <th>Jam</th>
                @if(!auth()->user()->isPasien())
                <th>Nama Pasien</th>
                @endif
                <th>Nama Dokter</th>
                <th>Status</th>
                <th>Tanggal Booking</th>
            </tr>
        </thead>
        <tbody>
            @foreach($bookings as $b)
            <tr>
                <td>{{ $b->IdBooking }}</td>
                <td>{{ date('d/m/Y', strtotime($b->Tanggal)) }}</td>
                <td>{{ $b->JamMulai }} - {{ $b->JamAkhir }}</td>
                @if(!auth()->user()->isPasien())
                <td>{{ $b->NamaPasien ?? '-' }}</td>
                @endif
                <td>{{ $b->NamaDokter }}</td>
                <td>
                    @if($b->Status == 'PRESENT')
                        <span class="badge bg-success">Hadir</span>
                    @elseif($b->Status == 'CANCELLED')
                        <span class="badge bg-danger">Batal</span>
                    @else
                        <span class="badge bg-warning">{{ $b->Status }}</span>
                    @endif
                </td>
                <td>{{ $b->TanggalBooking }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    
    {{ $bookings->links() }}
</div>
@endsection