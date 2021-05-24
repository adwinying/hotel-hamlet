<?php

namespace Database\Factories;

use App\Models\Reservation;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

class ReservationFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Reservation::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $randomDate = $this->faker->date();

        return [
            'room_id'        => $this->faker->randomDigit,
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
