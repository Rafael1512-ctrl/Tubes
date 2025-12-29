<!DOCTYPE html>
<html>
<head>
    <title>Konfirmasi Pembayaran</title>
    <style>
        body { font-family: sans-serif; line-height: 1.6; color: #333; }
        .container { width: 80%; margin: 20px auto; padding: 20px; border: 1px solid #ddd; border-radius: 10px; }
        .header { background: #d4edda; color: #155724; padding: 10px; text-align: center; border-radius: 5px; }
        .details { margin: 20px 0; }
        .total { font-size: 1.2em; font-weight: bold; color: #28a745; }
        .footer { font-size: 0.8em; color: #777; margin-top: 30px; text-align: center; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>Konfirmasi Pembayaran Berhasil</h2>
        </div>
        <p>Halo, <strong>{{ $pembayaran->pasien->Nama }}</strong>,</p>
        <p>Terima kasih. Pembayaran Anda untuk rekam medis <strong>{{ $pembayaran->IdRekamMedis }}</strong> telah kami terima.</p>
        
        <div class="details">
            <p><strong>Detail Pembayaran:</strong></p>
            <ul>
                <li>ID Pembayaran: {{ $pembayaran->IdPembayaran }}</li>
                <li>Tanggal: {{ \Carbon\Carbon::parse($pembayaran->TanggalPembayaran)->isoFormat('D MMMM Y, HH:mm') }}</li>
                <li>Metode: {{ $pembayaran->Metode }}</li>
                <li class="total">Total Bayar: Rp {{ number_format($pembayaran->TotalBayar, 0, ',', '.') }}</li>
            </ul>
        </div>

        <p>Semoga Anda lekas sembuh dan terima kasih telah mempercayai layanan kami.</p>
        
        <div class="footer">
            <p>&copy; {{ date('Y') }} Klinik Gigi. Hak Cipta Dilindungi.</p>
        </div>
    </div>
</body>
</html>
