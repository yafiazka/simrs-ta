<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Penyakit extends Model
{
    use HasFactory;

    protected $table = 'penyakit';
    protected $primaryKey = 'kd_penyakit';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'kd_penyakit',
        'nm_penyakit',
        'nama_penyakit_en',
        'ciri_ciri',
        'keterangan',
        'kd_ktg',
        'status',
    ];
}
