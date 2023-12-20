<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentToFaktur extends Model
{
    use HasFactory;

    public function faktur()
    {
        return $this->belongsTo(fakturpembelian::class);
    }
    public function payment(){
        return $this->belongsTo(Payment::class, 'payment_id');
    }
}
