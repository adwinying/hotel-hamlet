<?php

namespace App\Actions\Room;

use App\Models\Room;

class CreateRoom
{
    /**
     * Create room
     *
     * @param array<string, mixed> $input Data to create
     */
    public function execute(array $input = []): Room
    {
        return Room::create($input);
    }
}
