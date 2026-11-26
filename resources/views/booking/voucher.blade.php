<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <style>
        body {
            font-family: "Times New Roman", serif;
            font-size: 12px;
            color: #0f172a;
            margin: 0;
        }

        .brand {
            text-align: center;
            margin: 0 0 8px 0;
            padding: 0;
        }

        .header {
            border-bottom: 2px solid #0d0b61;
            padding-bottom: 10px;
            margin-bottom: 18px;
            text-align: center;
        }

        .title {
            font-size: 22px;
            font-weight: bold;
            color: #0d0b61;
            letter-spacing: 2px;
        }

        .meta {
            font-size: 11px;
            color: #64748b;
        }

        .section {
            margin-top: 16px;
        }

        .section-title {
            font-weight: bold;
            font-size: 13px;
            margin-bottom: 6px;
            color: #0d0b61;
            border-left: 3px solid #1fa9b8;
            padding-left: 8px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 6px;
        }

        td {
            padding: 6px 4px;
            vertical-align: top;
        }

        .label {
            font-weight: bold;
            width: 32%;
            color: #334155;
        }

        .total td {
            border-top: 1px solid #0d0b61;
            padding-top: 9px;
            font-size: 14px;
        }

        .footer {
            margin-top: 26px;
            font-size: 11px;
            color: #475569;
            border-top: 1px solid #e2e8f0;
            padding-top: 10px;
            line-height: 1.6;
        }

        .box {
            border: 1px solid #e2e8f0;
            background: #f8fafc;
            padding: 10px;
            margin-bottom: 10px;
        }

        .equal {
            width: 10px;
            font-weight: bold;
            text-align: center;
            color: #1fa9b8;
        }

        .sign {
            margin-top: 34px;
            text-align: left;
            font-size: 12px;
            color: #0d0b61;
        }
    </style>
</head>

<body>
    <div class="brand">
        <img src="{{ public_path('img/koppesma.jpeg') }}" alt="Header UMS" style="max-width:100%; height:90px; object-fit:contain; display:block; margin:0 auto;">
    </div>

    <div class="header">
        <div class="title">INVOICE</div>
        <div class="meta">ID Pemesanan: {{ $pengunjung->id }} · Pesma Inn KH. Mas Mansur</div>
    </div>

    <div class="section">
        <div class="section-title">Informasi Tamu</div>
        <div class="box">
            <table>
                <tr>
                    <td class="label">Nama Tamu</td>
                    <td class="equal">:</td>
                    <td>{{ $pengunjung->jenis_tamu == 'corporate' ? ($pengunjung->nama_pic ?? '-') : $pengunjung->nama }}</td>
                </tr>
                <tr>
                    <td class="label">Jenis Booking</td>
                    <td class="equal">:</td>
                    <td>{{ ucfirst($pengunjung->jenis_tamu) }}</td>
                </tr>
                <tr>
                    <td class="label">Kontak</td>
                    <td class="equal">:</td>
                    <td>{{ $pengunjung->no_telp ?? $pengunjung->no_telp_pic ?? '-' }}</td>
                </tr>
            </table>
        </div>
    </div>

    <div class="section">
        <div class="section-title">Detail Pemesanan</div>
        <div class="box">
            <table>
                <tr>
                    <td class="label">Tipe Kamar</td>
                    <td class="equal">:</td>
                    <td>{{ $kamar->jenis_kamar ?? '-' }}</td>
                </tr>
                <tr>
                    <td class="label">Check-in</td>
                    <td class="equal">:</td>
                    <td>{{ \Carbon\Carbon::parse($pengunjung->check_in)->translatedFormat('l, d F Y') }}</td>
                </tr>
                <tr>
                    <td class="label">Check-out</td>
                    <td class="equal">:</td>
                    <td>{{ \Carbon\Carbon::parse($pengunjung->check_out)->translatedFormat('l, d F Y') }}</td>
                </tr>
                <tr>
                    <td class="label">Durasi</td>
                    <td class="equal">:</td>
                    <td>{{ $durasi }} malam</td>
                </tr>
                <tr>
                    <td class="label">Jumlah Kamar</td>
                    <td class="equal">:</td>
                    <td>{{ $pengunjung->jumlah_kamar ?? 1 }}</td>
                </tr>
            </table>
        </div>
    </div>

    <div class="section">
        <div class="section-title">Harga</div>
        <div class="box">
            <table>
                <tr class="total">
                    <td class="label">Total Pembayaran</td>
                    <td class="equal">:</td>
                    <td><b>Rp {{ number_format($totalPembayaran, 0, ',', '.') }}</b></td>
                </tr>
                <tr>
                    <td class="label">Metode Pembayaran</td>
                    <td class="equal">:</td>
                    <td>{{ $pengunjung->metode_pembayaran ?? '-' }}</td>
                </tr>
                <tr>
                    <td class="label">Tanggal Booking</td>
                    <td class="equal">:</td>
                    <td>{{ \Carbon\Carbon::parse($pengunjung->created_at)->translatedFormat('d M Y, H:i') }}</td>
                </tr>
            </table>
        </div>
    </div>

    <div class="footer">
        Invoice ini digunakan untuk check-in di Pesma KH. Mas Mansur UMS. Harap tunjukkan invoice dan kartu identitas saat kedatangan. Terima kasih telah memesan.
    </div>

    <div class="sign">
        <b>Pesma Inn</b><br>
        KH. Mas Mansur · Surakarta
    </div>
</body>
</html>
