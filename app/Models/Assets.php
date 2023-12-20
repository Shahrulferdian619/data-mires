<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class Assets extends Model
{
    use SoftDeletes;

    protected $table = 'asset';
    protected $dates = ['deleted_at'];
    protected $guarded = [];

    public function tipe()
    {
        return $this->belongsTo('\App\Models\AssetType', 'id_tipeasset');
    }
}
