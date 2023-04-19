<?php

namespace Database\Factories;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Reservation>
 */
class ReservationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string,mixed>
     */
    public function definition(): array
    {
        $randomDate = $this->faker->dateTimeBetween('-1 year', '1 year');

        return [
            'room_id'        => $this->faker->numberBetween(1, 10),
            'check_in_date'  => $randomDate,
            'check_out_date' => Carbon::parse($randomDate)
                ->addDays($this->faker->numberBetween(1, 10)),
            'guest_name'  => $this->faker->name,
            'guest_email' => $this->faker->email,
            'pin'         => '$2y$10$zbBNnnBMrEZtPHmU55w4Y.93pcSkYuwMTj983hDtJU8wcU8T6ItIy', // 1234
            'remarks'     => $this->faker->paragraph,
        ];
    }
}
