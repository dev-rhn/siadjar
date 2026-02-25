<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PenerimaSurat extends Model
{
    protected $table = 'penerima_surats';

    protected $fillable = [
        'surat_id',
        'nama_penerima',
    ];

    public function surat()
    {
        return $this->belongsTo(Surat::class);
    }
}
