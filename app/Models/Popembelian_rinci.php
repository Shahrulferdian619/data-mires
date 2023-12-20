<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Popembelian_rinci extends Model
{
    use HasFactory;

    protected $table = 'popembelian_rinci';
    protected $guarded = [];

    public function barang()
    {
        return $this->belongsTo(Barang::class);
    }
    public function po(){
        return $this->belongsTo(Popembelian::class, 'popembelian_id');
    }
}
