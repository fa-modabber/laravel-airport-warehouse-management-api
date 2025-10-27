<?php

namespace Database\Factories;

use App\Models\Inventory;
use App\Models\InventoryItem;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\InventoryItem>
 */
class InventoryItemFactory extends Factory
{
    protected $model = InventoryItem::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'inventory_id' => Inventory::inRandomOrder()->first()->id ?? Inventory::factory(),
            'hs_code' => $this->faker->numerify('#####'),
            'count' => $this->faker->numberBetween(0, 100),
            'weight' => $this->faker->randomFloat(2, 0, 500),
            'description' => $this->faker->optional()->paragraph(),
        ];
    }
}
