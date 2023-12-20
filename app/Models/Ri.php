<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ri extends Model
{
    use HasFactory;

    protected $table = 'ri';
    protected $guarded = [];

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }
    public function po()
    {
        return $this->belongsTo(Popembelian::class);
    }

    public function rinci()
    {
        return $this->hasMany(Ri_rinci::class);
    }
}
