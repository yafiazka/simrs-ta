<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Pasien extends Model
{
    use HasFactory;

    protected $table = 'pasien';
    protected $primaryKey = 'no_rkm_medis';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'no_rkm_medis', 'nm_pasien', 'no_ktp', 'jk', 'tmp_lahir', 'tgl_lahir', 
        'nm_ibu', 'alamat', 'gol_darah', 'pekerjaan', 'stts_nikah', 'agama', 
        'tgl_daftar', 'no_tlp', 'umur', 'pnd', 'keluarga', 'namakeluarga', 
        'kd_pj', 'no_peserta', 'pekerjaanpj', 'alamatpj', 'kelurahanpj', 
        'kecamatanpj', 'kabupatenpj', 'email'
    ];

    protected $casts = [
        'tgl_lahir' => 'date',
        'tgl_daftar' => 'date',
    ];

    public function penjab()
    {
        return $this->belongsTo(Penjab::class, 'kd_pj', 'kd_pj');
    }

    public function regPeriksa(): HasMany
    {
        return $this->hasMany(RegPeriksa::class, 'no_rkm_medis', 'no_rkm_medis');
    }

    protected static function booted()
    {
        static::creating(function ($pasien) {
            // Generate auto-incrementing ID
            if (empty($pasien->no_rkm_medis)) {
                $last = self::orderBy('no_rkm_medis', 'desc')->first();
                $lastNumber = $last ? intval($last->no_rkm_medis) : 0;
                $pasien->no_rkm_medis = str_pad($lastNumber + 1, 6, '0', STR_PAD_LEFT);
            }

            // Set Tgl Daftar
            if (!$pasien->tgl_daftar) {
                $pasien->tgl_daftar = now()->toDateString();
            }

            // Calculate Umur
            if ($pasien->tgl_lahir && !$pasien->umur) {
                $birthDate = \Illuminate\Support\Carbon::parse($pasien->tgl_lahir);
                $diff = $birthDate->diff(now());
                $pasien->umur = $diff->y . " Th " . $diff->m . " Bl " . $diff->d . " Hr";
            } else {
                $pasien->umur = $pasien->umur ?: '0 Th 0 Bl 0 Hr';
            }

            // Default values for required fields
            if (!$pasien->pnd) {
                $pasien->pnd = '-';
            }
            
            if (!$pasien->kd_pj) {
                $pasien->kd_pj = '-';
            }
        });
    }
}
