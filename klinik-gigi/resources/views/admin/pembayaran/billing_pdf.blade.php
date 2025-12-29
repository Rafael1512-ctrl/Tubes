<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Billing Invoice - {{ $pembayaran->IdPembayaran }}</title>
    <style>
        body { font-family: 'Helvetica', 'Arial', sans-serif; color: #333; font-size: 13px; line-height: 1.5; }
        .invoice-box { max-width: 800px; margin: auto; padding: 0px; }
        .header { border-bottom: 2px solid #007bff; padding-bottom: 20px; margin-bottom: 20px; }
        .clinic-info { float: left; }
        .invoice-info { float: right; text-align: right; }
        .clear { clear: both; }
        .section-title { background: #f8f9fa; padding: 8px 12px; font-weight: bold; margin: 20px 0 10px 0; border-left: 4px solid #007bff; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        table th { text-align: left; background: #f8f9fa; padding: 10px; border-bottom: 1px solid #dee2e6; }
        table td { padding: 10px; border-bottom: 1px solid #eee; }
        .text-right { text-align: right; }
        .total-row { font-size: 1.1em; font-weight: bold; background: #fff !important; }
        .total-amount { color: #007bff; font-size: 1.4em; }
        .footer { margin-top: 50px; text-align: center; font-size: 11px; color: #777; border-top: 1px solid #eee; padding-top: 10px; }
        .stamp { border: 2px solid #28a745; color: #28a745; display: inline-block; padding: 5px 15px; border-radius: 5px; font-weight: bold; text-transform: uppercase; transform: rotate(-10deg); margin-top: 20px; }
    </style>
</head>
<body>
    <div class="invoice-box">
        <div class="header">
            <div class="clinic-info">
                <h2 style="color: #007bff; margin: 0;">KLINIK GIGI ZENITH</h2>
                <p style="margin: 5px 0;">Jl. Kesehatan No. 123, Bandung<br>Telp: (022) 1234567</p>
            </div>
            <div class="invoice-info">
                <h3 style="margin: 0;">BILLING INVOICE</h3>
                <p style="margin: 5px 0;">
                    Nomor: {{ $pembayaran->IdPembayaran }}<br>
                    Tanggal: {{ $pembayaran->TanggalPembayaran->format('d/m/Y H:i') }}<br>
                    Metode: {{ $pembayaran->Metode }}
                </p>
            </div>
            <div class="clear"></div>
        </div>

        <div style="margin-bottom: 30px;">
            <div style="float: left; width: 50%;">
                <p style="margin: 0; color: #777; text-transform: uppercase; font-size: 10px; font-weight: bold;">Informasi Pasien</p>
                <p style="margin: 5px 0;">
                    <strong>{{ $pembayaran->pasien->Nama }}</strong><br>
                    ID: {{ $pembayaran->PasienID }}<br>
                    Telp: {{ $pembayaran->pasien->NoTelp ?? '-' }}
                </p>
            </div>
            <div style="float: right; width: 40%; text-align: right;">
                <p style="margin: 0; color: #777; text-transform: uppercase; font-size: 10px; font-weight: bold;">Dokter Pemeriksa</p>
                <p style="margin: 5px 0;">
                    {{ $pembayaran->rekamMedis->dokter->Nama ?? '-' }}<br>
                    ID Rekam Medis: {{ $pembayaran->IdRekamMedis }}
                </p>
            </div>
            <div class="clear"></div>
        </div>

        <div class="section-title">Detail Tindakan</div>
        <table>
            <thead>
                <tr>
                    <th>Tindakan</th>
                    <th class="text-right">Biaya</th>
                    <th class="text-right">Jml</th>
                    <th class="text-right">Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @foreach($pembayaran->rekamMedis->tindakan as $t)
                <tr>
                    <td>{{ $t->NamaTindakan }}</td>
                    <td class="text-right">Rp {{ number_format($t->pivot->Harga, 0, ',', '.') }}</td>
                    <td class="text-right">{{ $t->pivot->Jumlah }}</td>
                    <td class="text-right">Rp {{ number_format($t->pivot->Harga * $t->pivot->Jumlah, 0, ',', '.') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        @if($pembayaran->rekamMedis->obat->count() > 0)
        <div class="section-title">Resep Obat</div>
        <table>
            <thead>
                <tr>
                    <th>Nama Obat</th>
                    <th class="text-right">Harga Satuan</th>
                    <th class="text-right">Jml</th>
                    <th class="text-right">Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @foreach($pembayaran->rekamMedis->obat as $o)
                <tr>
                    <td>{{ $o->NamaObat }}</td>
                    <td class="text-right">Rp {{ number_format($o->pivot->HargaSatuan, 0, ',', '.') }}</td>
                    <td class="text-right">{{ $o->pivot->Jumlah }}</td>
                    <td class="text-right">Rp {{ number_format($o->pivot->HargaSatuan * $o->pivot->Jumlah, 0, ',', '.') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @endif

        <div style="float: right; width: 300px; margin-top: 20px;">
            <table>
                <tr class="total-row">
                    <td>GRAND TOTAL</td>
                    <td class="text-right total-amount">Rp {{ number_format($pembayaran->TotalBayar, 0, ',', '.') }}</td>
                </tr>
            </table>
            <div style="text-align: center;">
                <div class="stamp">LUNAS</div>
            </div>
        </div>
        <div class="clear"></div>

        <div class="footer">
            <p>Terima kasih telah mempercayakan kesehatan gigi Anda kepada kami.<br>
            Billing ini dihasilkan secara otomatis dan sah sebagai bukti pembayaran.</p>
        </div>
    </div>
</body>
</html>
