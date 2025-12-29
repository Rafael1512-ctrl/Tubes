<!DOCTYPE html>
<html>
<head>
    <title>Pembatalan Booking</title>
    <style>
        body { font-family: sans-serif; line-height: 1.6; color: #333; }
        .container { width: 80%; margin: 20px auto; padding: 20px; border: 1px solid #ddd; border-radius: 10px; }
        .header { background: #f8d7da; color: #721c24; padding: 10px; text-align: center; border-radius: 5px; }
        .details { margin: 20px 0; }
        .footer { font-size: 0.8em; color: #777; margin-top: 30px; text-align: center; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>Pemberitahuan Pembatalan Booking</h2>
        </div>
        <p>Halo, <strong>{{ $booking->pasien->Nama }}</strong>,</p>
        <p>Kami memberitahukan bahwa booking Anda telah berhasil <strong>DIBATALKAN</strong>.</p>
        
        <div class="details">
            <p><strong>Detail Booking:</strong></p>
            <ul>
                <li>ID Booking: {{ $booking->IdBooking }}</li>
                <li>Dokter: {{ $booking->jadwal->dokter->Nama }}</li>
                <li>Tanggal: {{ \Carbon\Carbon::parse($booking->jadwal->Tanggal)->isoFormat('dddd, D MMMM Y') }}</li>
                <li>Jam: {{ substr($booking->jadwal->JamMulai, 0, 5) }} - {{ substr($booking->jadwal->JamAkhir, 0, 5) }}</li>
            </ul>
        </div>

        <p>Jika Anda merasa tidak melakukan pembatalan ini atau ingin menjadwalkan ulang, silakan hubungi kami.</p>
        
        <div class="footer">
            <p>&copy; {{ date('Y') }} Klinik Gigi. Hak Cipta Dilindungi.</p>
        </div>
    </div>
</body>
</html>
