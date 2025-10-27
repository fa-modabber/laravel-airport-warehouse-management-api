<?php

namespace Database\Seeders;

use App\Models\InventoryItem;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class InventoryItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        InventoryItem::factory()->count(20)->create();
    }
}
