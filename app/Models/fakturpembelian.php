<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class fakturpembelian extends Model
{
    use HasFactory;

    protected $table = 'fakturpembelians';

    public function faktur_rinci(){
        return $this->hasMany(fakturpembelian_rinci::class);
    }

    public function faktur_to_ri(){
        return $this->hasMany(FakturToRi::class, 'faktur_id');
    }
    
    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function rinci()
    {
        return $this->hasMany(fakturpembelian_rinci::class);
    }

    // public function relation(){
    //     return $this->hasOne(FakturToRelation::class, 'faktur_id');
    // }

    public function relation(){
        return $this->hasMany(FakturToRelation::class, 'faktur_id');
    }

    public function payment(){
        return $this->hasMany(PaymentToFaktur::class, 'faktur_id');
    }
}
