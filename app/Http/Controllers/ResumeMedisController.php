<?php

namespace App\Http\Controllers;

use App\Models\ResumeMedis;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class ResumeMedisController extends Controller
{
    public function print($id)
    {
        ini_set('memory_limit', '512M');
        $resume = ResumeMedis::with([
            'regPeriksa.pasien',
            'regPeriksa.dokter',
            'regPeriksa.penjab',
            'diagnosaUtamaPenyakit',
            'resepObats'
        ])->findOrFail($id);

        $tanggal_ttd = $resume->created_at ? $resume->created_at->translatedFormat('d-m-Y H:i:s') : now()->translatedFormat('d-m-Y H:i:s');
        $nama_puskesmas = env('NAME_APP', 'Puskesmas Kute Panang');
        $qrCodeText = "Resume Medis ini sudah ditanda tangani oleh dokter pada " . $tanggal_ttd . " dan dikeluarkan oleh  " . $nama_puskesmas . ".";

        // Source - https://stackoverflow.com/a/59999248
        // Posted by seanquijote, modified by community. See post 'Timeline' for change history
        // Retrieved 2026-05-23, License - CC BY-SA 4.0
        $qrcode = base64_encode(QrCode::format('svg')->size(200)->errorCorrection('H')->generate($qrCodeText));

        $pdf = Pdf::loadView('resume-medis.print', compact('resume', 'qrcode'));
        
        return $pdf->stream('resume-medis-' . str_replace('/', '-', $resume->no_rawat) . '.pdf');
    }
}

