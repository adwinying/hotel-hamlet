<?php

namespace Tests\Feature\Actions\Reservation;

use App\Actions\Reservation\DeleteReservation;
use App\Models\Reservation;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DeleteReservationTest extends TestCase
{
    use RefreshDatabase;

    public function testCanDeleteDb()
    {
        $reservation = Reservation::factory()->create();

        $delete = app(DeleteReservation::class);
        $result = $delete->execute($reservation);

        $this->assertTrue($result);
        $this->assertDatabaseMissing('reservations', [
            'id'         => $reservation->id,
            'deleted_at' => null,
        ]);
    }
}
