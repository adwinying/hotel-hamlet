<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\RoomType>
 */
class RoomTypeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string,mixed>
     */
    public function definition(): array
    {
        return [
            'hotel_id' => $this->faker->numberBetween(1, 10),
            'name'     => ucfirst($this->faker->unique()->word),
            'price'    => $this->faker->numberBetween(5, 20) * 100,
        ];
    }
}
