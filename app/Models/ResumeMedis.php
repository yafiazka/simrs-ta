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
        'no_rawat',
        'keluhan',
        'pemeriksaan_fisik',
        'diagnosa',
        'terapi',
        'tgl_keluar',
    ];

    protected $casts = [
        'tgl_keluar' => 'date',
    ];

    protected static function booted()
    {
        static::created(function ($resumeMedis) {
            $resumeMedis->regPeriksa()->update(['stts' => 'Selesai']);
        });
    }

    public function regPeriksa(): BelongsTo
    {
        return $this->belongsTo(RegPeriksa::class, 'no_rawat', 'no_rawat');
    }
}
