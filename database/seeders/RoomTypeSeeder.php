<?php

namespace Database\Seeders;

use App\Models\Hotel;
use App\Models\RoomType;
use Illuminate\Database\Seeder;

class RoomTypeSeeder extends Seeder
{
    const ROOM_TYPES = [
        ['Single', 80],
        ['Deluxe', 150],
        ['Suite', 200],
    ];

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $hotels = Hotel::count()
            ? Hotel::all()->random(5)
            : Hotel::factory()->count(5)->create();

        foreach ($hotels as $hotel) {
            foreach (self::ROOM_TYPES as [$types, $price]) {
                RoomType::factory()->create([
                    'hotel_id' => $hotel->id,
                    'name'     => $types,
                    'price'    => $price,
                ]);
            }
        }
    }
}
