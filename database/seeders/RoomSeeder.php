<?php

namespace Database\Seeders;

use App\Models\Room;
use App\Models\RoomType;
use Illuminate\Database\Seeder;

class RoomSeeder extends Seeder
{
    public const ROOM_NO_MAPPING = [
        'Single' => 200,
        'Deluxe' => 300,
        'Suite'  => 400,
    ];

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $roomTypes = RoomType::count()
            ? RoomType::all()
            : RoomType::factory()->count(5)->create();

        foreach ($roomTypes as $roomType) {
            foreach (range(1, 10) as $i) {
                $roomNoPrefix = self::ROOM_NO_MAPPING[$roomType->name] ?? 100;

                Room::factory()->create([
                    'room_type_id' => $roomType->id,
                    'room_no'      => $roomNoPrefix + $i,
                ]);
            }
        }
    }
}
