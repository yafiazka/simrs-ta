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
        'no_rkm_medis',
        'nm_pasien',
        'no_ktp',
        'jk',
        'tgl_lahir',
        'alamat',
        'no_tlp',
        'agama',
        'gol_darah',
    ];

    public function regPeriksa(): HasMany
    {
        return $this->hasMany(RegPeriksa::class, 'no_rkm_medis', 'no_rkm_medis');
    }
}
