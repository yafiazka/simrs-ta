<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Penjab extends Model
{
    protected $table = 'penjab';
    protected $primaryKey = 'kd_pj';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'kd_pj',
        'png_jawab',
        'nama_perusahaan',
        'alamat_asuransi',
        'no_telp',
        'attn',
        'status',
    ];
}
