<?php

namespace App\Actions\RoomType;

use App\Models\RoomType;

class DeleteRoomType
{
    /**
     * Delete room type
     *
     * @param RoomType $roomType Room type to be deleted
     */
    public function execute(RoomType $roomType): ?bool
    {
        return $roomType->delete();
    }
}
