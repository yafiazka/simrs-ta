<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ResepObat extends Model
{
    use HasFactory;

    protected $table = 'resep_obat';

    protected $fillable = [
        'resume_medis_id',
        'nama_obat',
        'jumlah_obat',
        'aturan_pakai',
    ];

    public function resumeMedis(): BelongsTo
    {
        return $this->belongsTo(ResumeMedis::class, 'resume_medis_id', 'id');
    }
}
