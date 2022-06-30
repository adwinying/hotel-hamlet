<?php

namespace App\Http\Controllers\Admin;

use App\Actions\Reservation\CreateReservation;
use App\Actions\Reservation\DeleteReservation;
use App\Actions\Reservation\UpdateReservation;
use App\Exceptions\RoomUnavailableException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ReservationRequest;
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
        /** @var string */
        $sort = request()->input('sort', 'id');
        /** @var 'asc'|'desc' */
        $order = request()->input('order', 'asc');
        /** @var int */
        $count = request()->input('count', 20);

        $queryParams = [
            'check_in_date',
            'check_out_date',
            'guest_name',
            'guest_email',
            'hotel_id',
            'room_type_id',
        ];

        return Inertia::render('Reservation/Index', [
            'query'  => request()->all($queryParams),
            'result' => Reservation::query()
                ->filter(request()->only($queryParams))
                ->with([
                    'room:id,room_no,room_type_id',
                    'room.roomType:id,hotel_id,name',
                    'room.roomType.hotel:id,name',
                ])
                ->orderBy($sort, $order)
                ->paginate($count, [
                    'id',
                    'room_id',
                    'check_in_date',
                    'check_out_date',
                    'guest_name',
                    'guest_email',
                ]),
            'hotels'    => fn ()    => Hotel::all(['id', 'name']),
            'roomTypes' => fn () => RoomType::all(['id', 'hotel_id', 'name']),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): Response
    {
        return Inertia::render('Reservation/Form', [
            'hotels'    => fn ()    => Hotel::all(['id', 'name']),
            'roomTypes' => fn () => RoomType::all(['id', 'hotel_id', 'name']),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(
        ReservationRequest $request,
        CreateReservation $create,
    ): RedirectResponse {
        /** @var array<string, mixed> */
        $input = $request->validated();

        try {
            $reservation = $create->execute($input);
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
        return Inertia::render('Reservation/Form', [
            'hotels'      => fn ()      => Hotel::all(['id', 'name']),
            'roomTypes'   => fn ()   => RoomType::all(['id', 'hotel_id', 'name']),
            'reservation' => [
                'id'             => $reservation->id,
                'check_in_date'  => $reservation->check_in_date->format('Y-m-d'),
                'check_out_date' => $reservation->check_out_date->format('Y-m-d'),
                'hotel_id'       => $reservation->room?->roomType?->hotel_id,
                'room_type_id'   => $reservation->room?->room_type_id,
                'room_id'        => $reservation->room_id,
                'guest_name'     => $reservation->guest_name,
                'guest_email'    => $reservation->guest_email,
                'remarks'        => $reservation->remarks,
            ],
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(
        ReservationRequest $request,
        Reservation $reservation,
        UpdateReservation $updateReservation
    ): RedirectResponse {
        /** @var array<string, mixed> */
        $input = $request->validated();

        try {
            $updateReservation->execute($reservation, $input);
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
