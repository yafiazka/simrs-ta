<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ResumeMedis extends Model
{
    use HasFactory;

    protected $table = 'resume_medis';

    protected $fillable = [
        'no_rawat', 'tgl_masuk', 'tgl_keluar', 'kd_dokter', 'keluhan', 
        'pemeriksaan_fisik', 'diagnosa_masuk', 'indikasi_rawat_inap', 
        'diagnosa_utama', 'diagnosa_sekunder', 'tindakan_prosedur', 
        'terapi_pulang', 'alergi_obat', 'kondisi_pulang', 'rencana_lanjut', 
        'ringkasan_riwayat', 'hasil_penunjang', 'cara_keluar'
    ];

    public function dokter(): BelongsTo
    {
        return $this->belongsTo(Dokter::class, 'kd_dokter', 'kd_dokter');
    }

    protected $casts = [
        'tgl_keluar' => 'date',
    ];

    protected static function booted()
    {
        static::created(function ($resumeMedis) {
            $resumeMedis->regPeriksa()->update(['stts' => 'Sudah']);
        });

        static::deleted(function ($resumeMedis) {
            $resumeMedis->regPeriksa()->update(['stts' => 'Belum']);
        });
    }

    public function regPeriksa(): BelongsTo
    {
        return $this->belongsTo(RegPeriksa::class, 'no_rawat', 'no_rawat');
    }
}
