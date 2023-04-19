<?php

namespace Database\Seeders;

use App\Models\Reservation;
use App\Models\Room;
use Illuminate\Database\Seeder;

class ReservationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $rooms = Room::query()->count()
            ? Room::all()->random(15)
            : Room::factory()->count(15)->create();

        foreach ($rooms as $room) {
            Reservation::factory()->count(mt_rand(1, 5))->create([
                'room_id' => $room->id,
            ]);
        }
    }
}
