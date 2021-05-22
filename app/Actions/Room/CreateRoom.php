<?php

namespace App\Actions\Room;

use App\Models\Room;

class CreateRoom
{
    /**
     * Create room
     * @param array $params Data to create
     * @return App\Models\Room
     */
    public function execute(array $input = []): Room
    {
        return Room::create($input);
    }
}
