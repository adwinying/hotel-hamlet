<?php

namespace App\Actions\Room;

use App\Models\Room;

class DeleteRoom
{
    /**
     * Delete room
     *
     * @param Room $room Room to be deleted
     */
    public function execute(Room $room): bool
    {
        return $room->delete();
    }
}
