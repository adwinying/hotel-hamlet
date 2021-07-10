<?php

namespace App\Http\Controllers\Admin;

use App\Actions\Room\CreateRoom;
use App\Actions\Room\DeleteRoom;
use App\Actions\Room\GetAvailableRooms;
use App\Actions\Room\UpdateRoom;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\RoomRequest;
use App\Models\Hotel;
use App\Models\Reservation;
use App\Models\Room;
use App\Models\RoomType;
use Illuminate\Http\Request;
use Inertia\Inertia;

class RoomController extends Controller
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
            'hotel_id',
            'room_type_id',
            'room_no',
        ];

        return Inertia::render('Room/Index', [
            'query'  => request()->all($queryParams),
            'result' => Room::query()
                ->filter(request()->only($queryParams))
                ->with([
                    'roomType:id,hotel_id,name',
                    'roomType.hotel:id,name',
                ])
                ->orderBy($sort, $order)
                ->paginate($count, [
                    'id',
                    'room_type_id',
                    'room_no',
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
        return Inertia::render('Room/Form', [
            'hotels'    => fn ()    => Hotel::all(['id', 'name']),
            'roomTypes' => fn () => RoomType::all(['id', 'hotel_id', 'name']),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function store(RoomRequest $request, CreateRoom $createRoom)
    {
        $input = $request->validated();

        $room = $createRoom->execute($input);

        return redirect()->route('rooms.show', [$room])
            ->with('success', 'Room created.');
    }

    /**
     * Display the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function show(Room $room)
    {
        return Inertia::render('Room/Form', [
            'room' => [
                'id'           => $room->id,
                'hotel_id'     => $room->roomType->hotel_id,
                'room_type_id' => $room->room_type_id,
                'room_no'      => $room->room_no,
            ],
            'hotels'    => fn ()    => Hotel::all(['id', 'name']),
            'roomTypes' => fn () => RoomType::all(['id', 'hotel_id', 'name']),
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function update(
        RoomRequest $request,
        Room $room,
        UpdateRoom $updateRoom
    ) {
        $input = $request->validated();

        $updateRoom->execute($room, $input);

        return redirect()->back()->with('success', 'Room updated.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(Room $room, DeleteRoom $deleteRoom)
    {
        $deleteRoom->execute($room);

        return redirect()->route('rooms.index')
            ->with('success', 'Room deleted.');
    }

    /**
     * Get list of available rooms available on specified dates
     *
     * @return \Illuminate\Http\Response
     */
    public function getAvailableRooms(GetAvailableRooms $getAvailableRooms)
    {
        $roomTypeId    = request()->input('room_type_id');
        $checkInDate   = request()->input('check_in_date');
        $checkOutDate  = request()->input('check_out_date');
        $reservationId = request()->input('reservation_id');

        if ($roomTypeId === null
            || $checkInDate === null
            || $checkOutDate === null) {
            return response()->json([
                'errors' => 'room_type_id, check_in_date, check_out_date query params must be specified.',
            ], 422);
        }

        $reservation = Reservation::find($reservationId);

        $availableRooms = $getAvailableRooms->execute(
            RoomType::findOrFail($roomTypeId),
            $checkInDate,
            $checkOutDate,
            $reservation
        );

        return response()->json($availableRooms->map(fn ($room) => [
            'id'           => $room->id,
            'room_type_id' => $room->room_type_id,
            'room_no'      => $room->room_no,
        ]));
    }
}
