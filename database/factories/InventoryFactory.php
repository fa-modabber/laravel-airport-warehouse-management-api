<?php

namespace Database\Factories;

use App\Models\Inventory;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Customer;
use App\Models\AirWaybill;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Inventory>
 */
class InventoryFactory extends Factory
{
    protected $model = Inventory::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'code' => $this->faker->unique()->bothify('??-####'),
            'customer_id' => Customer::inRandomOrder()->first()->id ?? Customer::factory(),
            'air_waybill_id' => AirWaybill::inRandomOrder()->first()->id ?? AirWaybill::factory(),
            'is_voided' => $this->faker->boolean(50),
            'is_banned' => $this->faker->boolean(20),
            'total_count' => $this->faker->numberBetween(0, 100),
            'total_weight' => $this->faker->randomFloat(2, 0, 500)
        ];
    }
}
