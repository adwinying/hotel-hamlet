<?php

namespace App\Actions\Hotel;

use App\Models\Hotel;

class CreateHotel
{
    /**
     * Create hotel
     * @param array $params Data to create
     * @return App\Models\Hotel
     */
    public function execute(array $input = []): Hotel
    {
        return Hotel::create($input);
    }
}
