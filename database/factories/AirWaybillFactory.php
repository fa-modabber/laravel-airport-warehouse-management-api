<?php

namespace Database\Factories;

use App\Models\AirWaybill;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\AirWaybill>
 */
class AirWaybillFactory extends Factory
{
    protected $model = AirWaybill::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $cities = ['Tehran', 'Qom', 'Isfahan', 'Shiraz', 'Tabriz'];
        $origin = $this->faker->randomElement($cities);
        $destination = $this->faker->randomElement(array_diff($cities, [$origin]));
        return [
            'number' => $this->faker->unique()->numerify('##########'),
            'origin' => $origin,
            'destination' => $destination,
        ];
    }
}
