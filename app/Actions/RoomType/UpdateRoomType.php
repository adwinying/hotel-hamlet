<?php

namespace App\Actions\RoomType;

use App\Models\RoomType;

class UpdateRoomType
{
    /**
     * Update room type
     *
     * @param RoomType $roomType Room type to be updated
     * @param array    $params   Data to update
     * @return App\Models\RoomType
     */
    public function execute(RoomType $roomType, array $input = []): RoomType
    {
        $roomType->update($input);

        return $roomType;
    }
}
