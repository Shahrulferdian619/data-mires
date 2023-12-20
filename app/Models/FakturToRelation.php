<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FakturToRelation extends Model
{
    use HasFactory;

    protected $table = 'faktur_relation';

    public function po(){
        return $this->belongsTo(Popembelian::class, 'po_id');
    }
    public function ri(){
        return $this->belongsTo(Ri::class, 'ri_id');
    }
}
