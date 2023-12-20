<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JurnalVoucher extends Model
{
    protected $table = 'jurnal_voucher';
    protected $guarded = [];
    protected $keyType = 'string';
    
    use HasFactory;
    
}
