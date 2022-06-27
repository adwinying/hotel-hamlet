<?php

namespace App\Actions\User;

use App\Models\User;

class GetUserSidebar
{
    /**
     * Get user's sidebar items
     *
     * @param User $user Target user
     */
    public function execute(User $user): array
    {
        return [
            [
                'name' => 'Dashboard',
                'url'  => '/admin',
                'icon' => 'HomeIcon',
            ],
            [
                'name' => 'Hotels',
                'url'  => '/admin/hotels',
                'icon' => 'OfficeBuildingIcon',
            ],
            [
                'name' => 'Room Types',
                'url'  => '/admin/room_types',
                'icon' => 'ColorSwatchIcon',
            ],
            [
                'name' => 'Rooms',
                'url'  => '/admin/rooms',
                'icon' => 'KeyIcon',
            ],
            [
                'name' => 'Reservations',
                'url'  => '/admin/reservations',
                'icon' => 'BookOpenIcon',
            ],
        ];
    }
}
