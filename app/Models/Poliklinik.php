<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Poliklinik extends Model
{
    use HasFactory;

    protected $table = 'poliklinik';
    protected $primaryKey = 'kd_poli';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'kd_poli',
        'nm_poli',
        'status',
    ];

    public function regPeriksa(): HasMany
    {
        return $this->hasMany(RegPeriksa::class, 'kd_poli', 'kd_poli');
    }
}
