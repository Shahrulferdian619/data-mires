<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pelanggan extends Model
{
    use HasFactory;

    protected $table = 'pelanggans';
    protected $guarded = [];

    public function tipepelanggan()
    {
        return $this->belongsTo('App\Models\TipePelanggan', 'tipepelanggan_id');
    }
}
