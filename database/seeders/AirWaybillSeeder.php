<?php

namespace Database\Seeders;

use App\Models\AirWaybill;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AirWaybillSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        AirWaybill::factory()->count(5)->create();
    }
}
