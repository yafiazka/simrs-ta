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

    public function penjab()
    {
        return $this->belongsTo(Penjab::class, 'kd_pj', 'kd_pj');
    }

    public function regPeriksa(): HasMany
    {
        return $this->hasMany(RegPeriksa::class, 'no_rkm_medis', 'no_rkm_medis');
    }
}
