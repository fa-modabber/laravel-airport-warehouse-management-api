<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class InventoryItem extends Model
{
    use HasFactory, SoftDeletes;
    protected $guarded = [];
    protected $table = "inventory_items";

    public function inventory()
    {
        return $this->belongsTo(Inventory::class);
    }

    protected static function booted()
    {
        static::saved(function ($item) {
            $item->inventory->updateTotals();
        });

        static::deleted(function ($item) {
            $item->inventory->updateTotals();
        });
    }
}
