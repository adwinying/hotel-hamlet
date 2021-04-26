<?php

namespace App\Actions\Hotel;

use App\Models\Hotel;

class UpdateHotel
{
    /**
     * Update hotel
     * @param Hotel $hotel  Hotel to be updated
     * @param array $params Data to update
     * @return App\Models\Hotel
     */
    public function execute(Hotel $hotel, array $input = []): Hotel
    {
        $hotel->update($input);

        return $hotel;
    }
}
