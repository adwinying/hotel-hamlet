<?php

namespace App\Actions\Hotel;

use App\Models\Hotel;

class CreateHotel
{
    /**
     * Create hotel
     *
     * @param array<string, mixed> $input Data to create
     */
    public function execute(array $input = []): Hotel
    {
        return Hotel::create($input);
    }
}
