<?php

namespace App\Models\v2\Master;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Coa extends Model
{
    use HasFactory;

    protected $connection = 'second_mysql';
    protected $table = 'coa';
    protected $guarded = [];

    public function scopeActive($query)
    {
        return $query->where('status_aktif', 1);
    }

    public function scopeBank($query)
    {
        return $query->where('status_aktif', 1)->where('coa_tipe_id', 1);
    }

    public function scopePendapatan($query)
    {
        return $query->where('status_aktif', 1)->where('coa_tipe_id', 11);
    }

    public function tipe()
    {
        return $this->belongsTo(Tipecoa::class, 'coa_tipe_id');
    }
}
