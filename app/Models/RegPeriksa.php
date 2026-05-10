<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class RegPeriksa extends Model
{
    use HasFactory;

    protected $table = 'reg_periksa';
    protected $primaryKey = 'no_rawat';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'no_reg', 'no_rawat', 'tgl_registrasi', 'jam_reg', 'kd_dokter', 
        'no_rkm_medis', 'kd_poli', 'p_jawab', 'almt_pj', 'hubunganpj', 
        'biaya_reg', 'stts', 'stts_daftar', 'status_lanjut', 'kd_pj', 
        'umurdaftar', 'sttsumur', 'status_bayar', 'status_poli', 'jam_panggil'
    ];

    public function penjab()
    {
        return $this->belongsTo(Penjab::class, 'kd_pj', 'kd_pj');
    }

    protected $casts = [
        'tgl_registrasi' => 'datetime',
    ];

    public function pasien(): BelongsTo
    {
        return $this->belongsTo(Pasien::class, 'no_rkm_medis', 'no_rkm_medis');
    }

    public function poliklinik(): BelongsTo
    {
        return $this->belongsTo(Poliklinik::class, 'kd_poli', 'kd_poli');
    }

    public function resumeMedis(): HasOne
    {
        return $this->hasOne(ResumeMedis::class, 'no_rawat', 'no_rawat');
    }

    /**
     * Generate automatic no_rawat
     */
    public static function generateNoRawat()
    {
        $date = now()->format('Y/m/d');
        $lastReg = self::whereDate('tgl_registrasi', now()->toDateString())
            ->orderBy('no_rawat', 'desc')
            ->first();

        if ($lastReg) {
            $lastNo = explode('/', $lastReg->no_rawat);
            $nextNo = str_pad((int)end($lastNo) + 1, 6, '0', STR_PAD_LEFT);
        } else {
            $nextNo = '000001';
        }

        return "{$date}/{$nextNo}";
    }
}
