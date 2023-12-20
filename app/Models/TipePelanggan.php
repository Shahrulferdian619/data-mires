<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TipePelanggan extends Model
{
    use HasFactory;
    protected $table = 'tipepelanggans';

    public function pelanggans()
    {
        return $this->hasMany('App\Models\Pelanggan');
    }
}
