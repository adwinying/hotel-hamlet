<?php

namespace Tests\Feature\Actions\User;

use App\Actions\User\GetUserSidebar;
use App\Models\User;
use Tests\TestCase;

class GetUserSidebarTest extends TestCase
{
    public function testGetListOfSidebarItems()
    {
        $user = new User();

        $get    = app(GetUserSidebar::class);
        $result = $get->execute($user);

        $expected = [
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
                'name'   => 'Room Types',
                'url'    => '/admin/room_types',
                'imgSrc' => '/img/icons/swatch.svg',
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

        $this->assertEquals($expected, $result);
    }
}
