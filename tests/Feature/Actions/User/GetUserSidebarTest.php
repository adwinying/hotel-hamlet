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

        $this->assertEquals($expected, $result);
    }
}
