<?php

namespace App\Http\Controllers\Admin;

use App\Actions\Reservation\CreateReservation;
use App\Exceptions\RoomUnavailableException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ReservationRequest;
use App\Models\Hotel;
use App\Models\Reservation;
use App\Models\RoomType;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ReservationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $sort  = request()->input('sort', 'id');
        $order = request()->input('order', 'asc');
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
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return Inertia::render('Reservation/Form', [
            'hotels'    => fn ()    => Hotel::all(['id', 'name']),
            'roomTypes' => fn () => RoomType::all(['id', 'hotel_id', 'name']),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function store(ReservationRequest $request, CreateReservation $create)
    {
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
     *
     * @return \Illuminate\Http\Response
     */
    public function show(Reservation $reservation)
    {
        return Inertia::render('Reservation/Form', [
            'hotels'      => fn ()      => Hotel::all(['id', 'name']),
            'roomTypes'   => fn ()   => RoomType::all(['id', 'hotel_id', 'name']),
            'reservation' => [
                'id'             => $reservation->id,
                'check_in_date'  => $reservation->check_in_date->format('Y-m-d'),
                'check_out_date' => $reservation->check_out_date->format('Y-m-d'),
                'hotel_id'       => $reservation->room->roomType->hotel_id,
                'room_type_id'   => $reservation->room->room_type_id,
                'room_id'        => $reservation->room_id,
                'guest_name'     => $reservation->guest_name,
                'guest_email'    => $reservation->guest_email,
                'remarks'        => $reservation->remarks,
            ],
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Reservation $reservation)
    {
    }

    /**
     * Remove the specified resource from storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(Reservation $reservation)
    {
    }
}
