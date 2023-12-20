<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Popembelian extends Model
{
    use HasFactory;

    protected $table = 'popembelian';
    protected $guarded = [];

    public function rinci()
    {
        return $this->hasMany(Popembelian_rinci::class);
    }

    public function pmtpembelian()
    {
        return $this->belongsTo(Pmtpembelian::class);
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function berkas()
    {
        return $this->hasOne(BerkasPopembelian::class);
    }

    public function recieve()
    {
        return $this->hasMany(Ri::class, 'po_id');
    }

    public function fakturrelation()
    {
        return $this->hasMany(FakturToRelation::class, 'po_id', 'id');
    }
    public function purchasing_po()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function komisaris_approve()
    {
        return $this->belongsTo(User::class, 'id_komisaris');
    }
}
