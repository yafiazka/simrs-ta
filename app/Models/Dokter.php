<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Dokter extends Model
{
    protected $table = 'dokter';
    protected $primaryKey = 'kd_dokter';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'kd_dokter',
        'nm_dokter',
        'spesialis',
        'no_telp',
        'status',
    ];

    protected static function booted()
    {
        static::creating(function ($model) {
            if (empty($model->kd_dokter)) {
                $last = self::where('kd_dokter', 'like', 'D%')->orderBy('kd_dokter', 'desc')->first();
                $lastNumber = $last ? intval(substr($last->kd_dokter, 1)) : 0;
                $model->kd_dokter = 'D' . str_pad($lastNumber + 1, 3, '0', STR_PAD_LEFT);
            }
        });
    }
}
