<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class Packet extends Model
{

    protected $table = 'packets';

    public function rincian_paket()
    {
        return $this->hasMany(PacketRinci::class,'id_packet');
    }

}
