<?php

namespace App\Actions\RoomType;

use App\Models\RoomType;

class UpdateRoomType
{
    /**
     * Update room type
     *
     * @param RoomType             $roomType Room type to be updated
     * @param array<string, mixed> $input    Data to update
     */
    public function execute(RoomType $roomType, array $input = []): RoomType
    {
        $roomType->update($input);

        return $roomType;
    }
}
