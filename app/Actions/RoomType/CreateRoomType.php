<?php

namespace App\Actions\RoomType;

use App\Models\RoomType;

class CreateRoomType
{
    /**
     * Create room type
     *
     * @param array $params Data to create
     * @return App\Models\RoomType
     */
    public function execute(array $input = []): RoomType
    {
        return RoomType::create($input);
    }
}
