<?php

namespace Tests\Feature\Requests\Admin;

use App\Http\Requests\Admin\ReservationRequest;
use App\Models\Room;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\ValidationTestCase;

class ReservationRequestTest extends ValidationTestCase
{
    use RefreshDatabase;

    protected $roomId = 101;

    protected function setUp(): void
    {
        parent::setUp();

        Room::factory()->create([
            'id' => $this->roomId,
        ]);
    }

    protected function request(): FormRequest
    {
        return new ReservationRequest();
    }

    protected function baseInput(): array
    {
        return [
            'check_in_date'  => today()->format('Y-m-d'),
            'check_out_date' => today()->addWeek()->format('Y-m-d'),
            'room_id'        => $this->roomId,
            'guest_name'     => str_repeat('a', 255),
            'guest_email'    => str_repeat('b', 246) . '@test.com',
            'remarks'        => str_repeat('c', 1000),
        ];
    }

    public function formData()
    {
        return [
            'All OK' => [
                true,
            ],

            'check_in_date is empty' => [
                false, [], 'check_in_date',
            ],

            'check_in_date is not in date format' => [
                false, ['check_in_date' => '2020-02-30'],
            ],

            'check_in_date is not in date format 2' => [
                false, ['check_in_date' => 'foobar'],
            ],

            'check_out_date is empty' => [
                false, [], 'check_out_date',
            ],

            'check_out_date is not out date format' => [
                false, ['check_out_date' => '2020-02-30'],
            ],

            'check_out_date is not out date format 2' => [
                false, ['check_out_date' => 'foobar'],
            ],

            'check_out_date is before check_in_date' => [
                false, ['check_out_date' => today()->subDay()->format('Y-m-d')],
            ],

            'room_id is empty' => [
                false, [], 'room_id',
            ],

            'room_id does not exists in db' => [
                false, ['room_id' => 0],
            ],

            'guest_name is empty' => [
                false, [], 'guest_name',
            ],

            'guest_name exceeds 255 chars' => [
                false, ['guest_name' => str_repeat('a', 256)],
            ],

            'guest_email is empty' => [
                false, [], 'guest_email',
            ],

            'guest_email is not a valid email address' => [
                false, ['guest_email' => 'foobar'],
            ],

            'guest_email exceeds 255 chars' => [
                false, ['guest_name' => str_repeat('b', 247) . '@test.com'],
            ],

            'remarks is empty' => [
                true, [], 'remarks',
            ],

            'remarks exceeds 1000 chars' => [
                false, ['remarks' => str_repeat('c', 1001)],
            ],
        ];
    }
}
