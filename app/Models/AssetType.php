<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class AssetType extends Model
{
    use SoftDeletes;

    protected $table = 'tipe_asset';
    protected $dates = ['deleted_at'];
    protected $guarded = [];

}
