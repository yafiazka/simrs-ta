<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kamar extends Model
{
    protected $table = 'kamar';
    protected $primaryKey = 'kd_kamar';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'kd_kamar',
        'nm_kamar',
        'kelas',
        'trf_kamar',
        'stts',
    ];
}
