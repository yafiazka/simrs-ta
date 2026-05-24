<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Resume Medis - {{ $resume->regPeriksa->pasien->nm_pasien }}</title>
    <style>
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            font-size: 11px;
            color: #000;
            line-height: 1.4;
            margin: 0;
            padding: 10px;
        }
        .kop-surat {
            text-align: center;
            border-bottom: 3px double #000;
            padding-bottom: 8px;
            margin-bottom: 15px;
        }
        .kop-surat h2 {
            margin: 0;
            font-size: 14px;
            text-transform: uppercase;
        }
        .kop-surat h1 {
            margin: 3px 0;
            font-size: 18px;
            text-transform: uppercase;
        }
        .kop-surat p {
            margin: 0;
            font-size: 10px;
            color: #000;
        }
        .title {
            text-align: center;
            font-size: 13px;
            font-weight: bold;
            text-transform: uppercase;
            margin-bottom: 15px;
            text-decoration: underline;
        }
        .section-title {
            font-size: 12px;
            font-weight: bold;
            border-bottom: 1px solid #000;
            padding-bottom: 3px;
            margin-top: 15px;
            margin-bottom: 8px;
            text-transform: uppercase;
            color: #000;
        }
        table.obat-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 8px;
            margin-bottom: 15px;
        }
        table.obat-table th, table.obat-table td {
            border: 1px solid #999;
            padding: 6px 8px;
            text-align: left;
            color: #000;
        }
        table.obat-table th {
            background-color: #e5e7eb;
            font-weight: bold;
        }
        .barcode-box {
            text-align: center;
            border: 1px dashed #777;
            padding: 8px;
            border-radius: 4px;
            width: 100px;
        }
        .barcode-box p {
            margin: 4px 0 0 0;
            font-size: 8px;
            color: #000;
        }
        .ttd-box {
            text-align: center;
            color: #000;
        }
        .no-print {
            display: none;
        }
    </style>
</head>
<body>

    @php
        $logoPath = public_path('img/logo.png');
        $logoData = '';
        if (!app()->environment('testing') && extension_loaded('gd') && file_exists($logoPath)) {
            $logoData = base64_encode(file_get_contents($logoPath));
        }
    @endphp

    <table style="width: 100%; border-bottom: 3px double #000; padding-bottom: 8px; margin-bottom: 15px; border-collapse: collapse;">
        <tr>
            <td style="width: 12%; padding: 0; text-align: left; vertical-align: middle; border: 0;">
                @if($logoData)
                    <img src="data:image/png;base64,{{ $logoData }}" style="height: 55px; width: 55px; object-fit: contain;">
                @endif
            </td>
            <td style="width: 76%; padding: 0; text-align: center; vertical-align: middle; border: 0; line-height: 1.2;">
                <span style="font-size: 12px; font-weight: bold; text-transform: uppercase; display: block; color: #000;">Pemerintah Kabupaten Aceh Tengah</span>
                <span style="font-size: 15px; font-weight: bold; text-transform: uppercase; display: block; margin: 3px 0; color: #000;">Dinas Kesehatan - Puskesmas Kute Panang</span>
                <span style="font-size: 9px; color: #111; display: block;">Jl. Lintas Gayo - Blang Mancung, Kute Panang, Kabupaten Aceh Tengah, Aceh</span>
                <span style="font-size: 9px; color: #111; display: block;">Email: pusk.kutepanang@gmail.com | Telp: (0643) 123456</span>
            </td>
            <td style="width: 12%; padding: 0; border: 0;"></td>
        </tr>
    </table>

    <div class="title">Resume Medis Pasien Rawat Jalan</div>

    <table style="width: 100%; border: 1px solid #999; background-color: #f9fafb; border-collapse: collapse; margin-bottom: 15px; color: #000;">
        <tr>
            <td style="width: 50%; padding: 10px; vertical-align: top; border: 0; line-height: 1.6;">
                <strong>No. Rekam Medis:</strong> {{ $resume->regPeriksa->pasien->no_rkm_medis }}<br>
                <strong>Nama Pasien:</strong> {{ $resume->regPeriksa->pasien->nm_pasien }}<br>
                <strong>Jenis Kelamin:</strong> {{ $resume->regPeriksa->pasien->jk == 'L' ? 'Laki-laki' : 'Perempuan' }}<br>
                <strong>Tanggal Lahir:</strong> {{ $resume->regPeriksa->pasien->tgl_lahir?->format('d F Y') ?? '-' }}
            </td>
            <td style="width: 50%; padding: 10px; vertical-align: top; border: 0; line-height: 1.6;">
                <strong>No. Rawat:</strong> {{ $resume->no_rawat }}<br>
                <strong>Tanggal Kunjungan:</strong> {{ \Carbon\Carbon::parse($resume->tgl_masuk)->format('d F Y') }}<br>
                <strong>Cara Bayar:</strong> {{ $resume->regPeriksa->penjab?->png_jawab ?? '-' }}<br>
                <strong>Alamat:</strong> {{ $resume->regPeriksa->pasien->alamat ?? '-' }}
            </td>
        </tr>
    </table>

    <div class="section-title">Hasil Pemeriksaan Klinis</div>
    <table style="width: 100%; border-collapse: collapse; margin-bottom: 15px; border: 1px solid #999; color: #000;">
        <tr style="background-color: #f9fafb;">
            <td style="width: 25%; padding: 6px; border: 1px solid #999; font-weight: bold;">Tensi (TD):</td>
            <td style="width: 25%; padding: 6px; border: 1px solid #999;">{{ $resume->tensi ?? '-' }}</td>
            <td style="width: 25%; padding: 6px; border: 1px solid #999; font-weight: bold;">Tinggi Badan (TB):</td>
            <td style="width: 25%; padding: 6px; border: 1px solid #999;">{{ $resume->tb ? $resume->tb . ' cm' : '-' }}</td>
        </tr>
        <tr>
            <td style="padding: 6px; border: 1px solid #999; font-weight: bold;">Nadi:</td>
            <td style="padding: 6px; border: 1px solid #999;">{{ $resume->nadi ? $resume->nadi . ' x/menit' : '-' }}</td>
            <td style="padding: 6px; border: 1px solid #999; font-weight: bold;">Berat Badan (BB):</td>
            <td style="padding: 6px; border: 1px solid #999;">{{ $resume->bb ? $resume->bb . ' kg' : '-' }}</td>
        </tr>
        <tr style="background-color: #f9fafb;">
            <td style="padding: 6px; border: 1px solid #999; font-weight: bold;">Suhu:</td>
            <td style="padding: 6px; border: 1px solid #999;">{{ $resume->suhu ? $resume->suhu . ' °C' : '-' }}</td>
            <td style="padding: 6px; border: 1px solid #999; font-weight: bold;">Respirasi:</td>
            <td style="padding: 6px; border: 1px solid #999;">{{ $resume->respirasi ? $resume->respirasi . ' x/menit' : '-' }}</td>
        </tr>
        <tr>
            <td style="padding: 6px; border: 1px solid #999; font-weight: bold;">SpO2:</td>
            <td style="padding: 6px; border: 1px solid #999;">{{ $resume->spo2 ? $resume->spo2 . ' %' : '-' }}</td>
            <td style="padding: 6px; border: 1px solid #999; font-weight: bold;">GCS:</td>
            <td style="padding: 6px; border: 1px solid #999;">{{ $resume->gcs ?? '-' }}</td>
        </tr>
    </table>

    <div style="margin-top: 10px; color: #000;">
        <strong style="display: block; margin-bottom: 4px;">Keluhan / Anamnesa Awal:</strong>
        <div style="background: #ffffff; border: 1px solid #999; padding: 6px 8px; border-radius: 4px; min-height: 30px; white-space: pre-line; color: #000;">{{ $resume->keluhan ?: '-' }}</div>
    </div>

    <div style="margin-top: 10px; color: #000;">
        <strong style="display: block; margin-bottom: 4px;">Diagnosa Utama (ICD-10):</strong>
        <div style="background: #ffffff; border: 1px solid #999; padding: 6px 8px; border-radius: 4px; font-weight: bold; color: #000;">
            [{{ $resume->diagnosa_utama }}] {{ $resume->diagnosaUtamaPenyakit?->nm_penyakit ?? 'Diagnosa tidak ditemukan' }}
        </div>
    </div>

    <div style="margin-top: 10px; color: #000;">
        <strong style="display: block; margin-bottom: 4px;">Cara Dipulangkan:</strong>
        <div style="background: #ffffff; border: 1px solid #999; padding: 6px 8px; border-radius: 4px; color: #000;">
            {{ $resume->cara_keluar === 'dirujuk_rs' ? 'Dirujuk ke Rumah Sakit (RS)' : 'Dipulangkan' }}
        </div>
    </div>

    <div style="margin-top: 10px; color: #000;">
        <strong style="display: block; margin-bottom: 4px;">Instruksi Medis:</strong>
        <div style="background: #ffffff; border: 1px solid #999; padding: 6px 8px; border-radius: 4px; min-height: 30px; white-space: pre-line; color: #000;">{{ $resume->instruksi ?: '-' }}</div>
    </div>

    <div class="section-title">Resep Obat</div>
    @if($resume->resepObats && $resume->resepObats->count() > 0)
        <table class="obat-table">
            <thead>
                <tr>
                    <th style="width: 5%;">No</th>
                    <th>Nama Obat</th>
                    <th style="width: 15%;">Jumlah</th>
                    <th>Aturan Pakai</th>
                </tr>
            </thead>
            <tbody>
                @foreach($resume->resepObats as $index => $obat)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $obat->nama_obat }}</td>
                        <td>{{ $obat->jumlah_obat }}</td>
                        <td>{{ $obat->aturan_pakai }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <p style="font-style: italic; color: #888; margin-top: 5px;">Tidak ada resep obat yang diinputkan.</p>
    @endif

    <table style="width: 100%; border: 0; margin-top: 30px; border-collapse: collapse;">
        <tr>
            <td style="width: 60%; border: 0; padding: 0;"></td>
            <td style="width: 40%; text-align: center; vertical-align: bottom; border: 0; padding: 0;">
                <div class="ttd-box">
                    <p style="margin: 0 0 5px 0;">Kute Panang, {{ \Carbon\Carbon::parse($resume->tgl_masuk)->format('d F Y') }}</p>
                    <p style="margin: 0 0 5px 0;">Dokter Penanggung Jawab,</p>
                    
                    <!-- Real QR code for DPJP Signature Verification -->
                    <div style="display: block; margin: 8px auto; width: 75px; height: 75px;">
                        <!--
                        // Source - https://stackoverflow.com/a/59999248
                        // Posted by seanquijote, modified by community. See post 'Timeline' for change history
                        // Retrieved 2026-05-23, License - CC BY-SA 4.0
                        -->
                        <img src="data:image/png;base64, {!! $qrcode !!}" style="width: 75px; height: 75px;">
                    </div>

                    <p style="margin: 0;"><strong><u>{{ $resume->regPeriksa->dokter->nm_dokter ?? 'Dokter Pemeriksa' }}</u></strong></p>
                    <p style="margin: 0; font-size: 10px; color: #555;">SIP. {{ $resume->regPeriksa->dokter->sip ?? '-' }}</p>
                </div>
            </td>
        </tr>
    </table>

</body>
</html>
