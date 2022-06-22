<?php

namespace Database\Seeders;

use App\Models\Hotel;
use Illuminate\Database\Seeder;

class HotelSeeder extends Seeder
{
    public const AVAILABLE_HOTELS = [
        'Hotel Hamlet City',
        'Hotel Hamlet Beachside',
        'Hotel Hamlet Ski Resort',
        'Hotel Hamlet Campgrounds',
    ];

    public const UNAVAILABLE_HOTELS = [
        'Hotel Hamlet Airport Express',
    ];

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach (self::AVAILABLE_HOTELS as $hotelName) {
            Hotel::factory()->create([
                'name'      => $hotelName,
                'is_hidden' => false,
            ]);
        }

        foreach (self::UNAVAILABLE_HOTELS as $hotelName) {
            Hotel::factory()->create([
                'name'      => $hotelName,
                'is_hidden' => true,
            ]);
        }
    }
}
