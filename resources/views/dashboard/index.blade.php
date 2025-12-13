@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="container-fluid">
    <h2>Dashboard</h2>
    
    <!-- Stats Cards -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card text-white bg-primary">
                <div class="card-body">
                    <h5 class="card-title">Total Pasien</h5>
                    <p class="card-text display-6">{{ $stats['total_pasien'] }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-success">
                <div class="card-body">
                    <h5 class="card-title">Total Dokter</h5>
                    <p class="card-text display-6">{{ $stats['total_dokter'] }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-info">
                <div class="card-body">
                    <h5 class="card-title">Booking Hari Ini</h5>
                    <p class="card-text display-6">{{ $stats['booking_hari_ini'] }}</p>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Booking Hari Ini -->
    <div class="card">
        <div class="card-header">
            <h5>Booking Hari Ini</h5>
        </div>
        <div class="card-body">
            @if($bookingsToday->isEmpty())
                <p class="text-muted">Tidak ada booking hari ini.</p>
            @else
                <table class="table table-sm">
                    <thead>
                        <tr>
                            <th>Jam</th>
                            <th>Pasien</th>
                            <th>Dokter</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($bookingsToday as $booking)
                        <tr>
                            <td>{{ $booking->JamMulai }}</td>
                            <td>{{ $booking->nama_pasien }}</td>
                            <td>{{ $booking->nama_dokter }}</td>
                            <td>
                                @if($booking->Status == 'PRESENT')
                                    <span class="badge bg-success">Hadir</span>
                                @elseif($booking->Status == 'CANCELLED')
                                    <span class="badge bg-danger">Batal</span>
                                @else
                                    <span class="badge bg-warning">{{ $booking->Status }}</span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>
    </div>
</div>
@endsection