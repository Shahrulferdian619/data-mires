<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class PacketRinci extends Model
{
    protected $table = 'packet_rinci';
    
    public function barang()
    {
        return $this->belongsTo('\App\Models\Barang', 'id_barang');
    }
}
