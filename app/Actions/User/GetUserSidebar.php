<?php

namespace App\Actions\User;

use App\Models\User;

class GetUserSidebar
{
    /**
     * Get user's sidebar items
     * @param User $user Target user
     */
    public function execute(User $user): array
    {
        return [
            [
                'name'   => 'Dashboard',
                'url'    => '/admin',
                'imgSrc' => '/img/icons/home.svg',
            ],
            [
                'name'   => 'Hotels',
                'url'    => '/admin/hotels',
                'imgSrc' => '/img/icons/hotel.svg',
            ],
            [
                'name'   => 'Rooms',
                'url'    => '/admin/rooms',
                'imgSrc' => '/img/icons/key.svg',
            ],
            [
                'name'   => 'Reservations',
                'url'    => '/admin/reservations',
                'imgSrc' => '/img/icons/book.svg',
            ],
        ];
    }
}
