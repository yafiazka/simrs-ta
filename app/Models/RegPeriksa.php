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

    public function dokter(): BelongsTo
    {
        return $this->belongsTo(Dokter::class, 'kd_dokter', 'kd_dokter');
    }

    public function resumeMedis(): HasOne
    {
        return $this->hasOne(ResumeMedis::class, 'no_rawat', 'no_rawat');
    }

    protected static function booted()
    {
        static::creating(function ($reg) {
            // Set Jam Reg
            if (!$reg->jam_reg) {
                $reg->jam_reg = now()->format('H:i:s');
            }

            // Set No Reg (Antrian)
            if (!$reg->no_reg) {
                $lastNoReg = self::whereDate('tgl_registrasi', $reg->tgl_registrasi ?? now()->toDateString())
                    ->where('kd_poli', $reg->kd_poli)
                    ->max('no_reg');
                
                $reg->no_reg = str_pad((int)$lastNoReg + 1, 3, '0', STR_PAD_LEFT);
            }

            // Hitung Umur Daftar
            if ($reg->no_rkm_medis) {
                $pasien = Pasien::where('no_rkm_medis', $reg->no_rkm_medis)->first();
                if ($pasien && $pasien->tgl_lahir) {
                    $birthDate = \Illuminate\Support\Carbon::parse($pasien->tgl_lahir);
                    $diff = $birthDate->diff(now());
                    $reg->umurdaftar = $diff->y;
                    $reg->sttsumur = 'Th';
                    
                    if ($diff->y == 0) {
                        if ($diff->m > 0) {
                            $reg->umurdaftar = $diff->m;
                            $reg->sttsumur = 'Bl';
                        } else {
                            $reg->umurdaftar = $diff->d;
                            $reg->sttsumur = 'Hr';
                        }
                    }
                }
            }

            // Set PJ info from Pasien if not provided
            if ($reg->no_rkm_medis && (!$reg->p_jawab || !$reg->almt_pj || !$reg->hubunganpj)) {
                $pasien = Pasien::where('no_rkm_medis', $reg->no_rkm_medis)->first();
                if ($pasien) {
                    $reg->p_jawab = $reg->p_jawab ?: $pasien->namakeluarga;
                    $reg->almt_pj = $reg->almt_pj ?: $pasien->alamatpj;
                    $reg->hubunganpj = $reg->hubunganpj ?: $pasien->keluarga;
                }
            }
        });
    }

    /**
     * Use hyphenated no_rawat for routing to avoid slash issues
     */
    public function getRouteKey()
    {
        return str_replace('/', '-', $this->getAttribute($this->getRouteKeyName()));
    }

    /**
     * Resolve hyphenated no_rawat back to original format
     */
    public function resolveRouteBinding($value, $field = null)
    {
        $value = str_replace('-', '/', $value);
        return $this->where($field ?? $this->getRouteKeyName(), $value)->firstOrFail();
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
