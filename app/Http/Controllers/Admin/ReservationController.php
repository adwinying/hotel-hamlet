<?php

namespace App\Http\Controllers\Admin;

use App\Actions\Reservation\CreateReservation;
use App\Actions\Reservation\DeleteReservation;
use App\Actions\Reservation\UpdateReservation;
use App\Exceptions\RoomUnavailableException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ReservationFormRequest;
use App\Http\Requests\Admin\ReservationIndexRequest;
use App\Http\Responses\Admin\ReservationFormResponse;
use App\Http\Responses\Admin\ReservationIndexResponse;
use App\Models\Hotel;
use App\Models\Reservation;
use App\Models\RoomType;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class ReservationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): Response
    {
        $request = ReservationIndexRequest::from(request()->all());

        $query = [
            'check_in_date'  => $request->check_in_date,
            'check_out_date' => $request->check_out_date,
            'guest_name'     => $request->guest_name,
            'guest_email'    => $request->guest_email,
            'hotel_id'       => $request->hotel_id,
            'room_type_id'   => $request->room_type_id,
        ];

        return Inertia::render('Reservation/Index', ReservationIndexResponse::from([
            'query'  => $query,
            'result' => Reservation::query()
                ->filter($query)
                ->with([
                    'room:id,room_no,room_type_id',
                    'room.roomType:id,hotel_id,name',
                    'room.roomType.hotel:id,name',
                ])
                ->orderBy($request->sort, $request->order)
                ->paginate($request->count, [
                    'id',
                    'room_id',
                    'check_in_date',
                    'check_out_date',
                    'guest_name',
                    'guest_email',
                ]),
            'hotels'    => Hotel::all(['id', 'name']),
            'roomTypes' => RoomType::all(['id', 'hotel_id', 'name']),
        ]));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): Response
    {
        return Inertia::render('Reservation/Form', ReservationFormResponse::from([
            'hotels'    => Hotel::all(['id', 'name']),
            'roomTypes' => RoomType::all(['id', 'hotel_id', 'name']),
        ]));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(
        ReservationFormRequest $request,
        CreateReservation $create,
    ): RedirectResponse {
        try {
            $reservation = $create->execute($request->toArray());
        } catch (RoomUnavailableException $e) {
            return redirect()->back()->withErrors([
                'room_id' => 'Selected room is unavailable.',
            ]);
        }

        return redirect()->route('reservations.show', [$reservation])
            ->with('success', 'Reservation created.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Reservation $reservation): Response
    {
        return Inertia::render('Reservation/Form', ReservationFormResponse::from([
            'hotels'      => Hotel::all(['id', 'name']),
            'roomTypes'   => RoomType::all(['id', 'hotel_id', 'name']),
            'reservation' => $reservation,
        ]));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(
        ReservationFormRequest $request,
        Reservation $reservation,
        UpdateReservation $updateReservation
    ): RedirectResponse {
        try {
            $updateReservation->execute($reservation, $request->toArray());
        } catch (RoomUnavailableException $e) {
            return redirect()->back()->withErrors([
                'room_id' => 'Selected room is unavailable.',
            ]);
        }

        return redirect()->back()->with('success', 'Reservation updated.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(
        Reservation $reservation,
        DeleteReservation $deleteReservation,
    ): RedirectResponse {
        $deleteReservation->execute($reservation);

        return redirect()->route('reservations.index')
            ->with('success', 'Reservation deleted.');
    }
}
