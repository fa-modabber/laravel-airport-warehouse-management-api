<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Inventory extends Model
{
    use HasFactory, SoftDeletes;
    protected $guarded = [];
    protected $table = "inventories";

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function items()
    {
        return $this->hasMany(InventoryItem::class);
    }

    public function updateTotals()
    {
        $this->update([
            'total_count' => $this->items()->sum('count'),
            'total_weight' => $this->items()->sum('weight'),
        ]);
    }
}
