<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class AirWaybill extends Model
{
    use HasFactory, SoftDeletes;
    protected $guarded = [];
    protected $table = "air_waybills";

    public function inventories()
    {
        return $this->hasMany(Inventory::class);
    }
}
