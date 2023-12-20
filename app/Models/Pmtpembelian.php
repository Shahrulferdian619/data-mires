<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pmtpembelian extends Model
{
    use HasFactory;

    protected $table = 'pmtpembelian';
    protected $fillable = [
        'nomer_pmtpembelian',
        'tanggal',
        'keterangan',
        'status',
        'supplier_id',
        'user_id'
    ];

    public function rinci()
    {
        return $this->hasMany(Pmtpembelian_rinci::class);
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function berkaspendukung()
    {
        return $this->hasOne(BerkasPmtpembelian::class);
    }

    public function purchasing()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
