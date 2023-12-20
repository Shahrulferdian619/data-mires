<?php

namespace App\Models\v2\Master;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    use HasFactory;

    protected $connection = 'second_mysql';
    protected $table = 'supplier';
    protected $guarded = [];

    public function scopeActive($query)
    {
        return $query->where('status_aktif', 1);
    }

    public function tipe_supplier()
    {
        return $this->belongsTo(TipeSupplier::class,'tipe_supplier_id');
    }
}
