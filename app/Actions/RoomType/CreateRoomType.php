<?php

namespace App\Actions\RoomType;

use App\Models\RoomType;

class CreateRoomType
{
    /**
     * Create room type
     *
     * @param array<string, mixed> $input Data to create
     */
    public function execute(array $input = []): RoomType
    {
        return RoomType::create($input);
    }
}
