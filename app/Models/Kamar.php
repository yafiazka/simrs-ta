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

    protected static function booted()
    {
        static::creating(function ($model) {
            if (empty($model->kd_kamar)) {
                $last = self::where('kd_kamar', 'like', 'K%')->orderBy('kd_kamar', 'desc')->first();
                $lastNumber = $last ? intval(substr($last->kd_kamar, 1)) : 0;
                $model->kd_kamar = 'K' . str_pad($lastNumber + 1, 3, '0', STR_PAD_LEFT);
            }
        });
    }
}
