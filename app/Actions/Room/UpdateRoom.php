<?php

namespace App\Actions\Room;

use App\Models\Room;

class UpdateRoom
{
    /**
     * Update room
     *
     * @param Room                 $room  Room to be updated
     * @param array<string, mixed> $input Data to update
     */
    public function execute(Room $room, array $input = []): Room
    {
        $room->update($input);

        return $room;
    }
}
