<?php

namespace App\Actions\Hotel;

use App\Models\Hotel;

class DeleteHotel
{
    /**
     * Delete hotel
     * @param Hotel $hotel Hotel to be deleted
     */
    public function execute(Hotel $hotel): bool
    {
        return $hotel->delete();
    }
}
