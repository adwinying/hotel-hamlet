<?php

namespace App\Http\Controllers\Admin;

use App\Actions\Room\CreateRoom;
use App\Actions\Room\DeleteRoom;
use App\Actions\Room\GetAvailableRooms;
use App\Actions\Room\UpdateRoom;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\RoomIndexRequest;
use App\Http\Requests\Admin\RoomRequest;
use App\Http\Responses\RoomFormResponse;
use App\Http\Responses\RoomIndexResponse;
use App\Models\Hotel;
use App\Models\Reservation;
use App\Models\Room;
use App\Models\RoomType;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class RoomController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): Response
    {
        $request = RoomIndexRequest::from(request()->all());

        $query = [
            'hotel_id'     => $request->hotel_id,
            'room_type_id' => $request->room_type_id,
            'room_no'      => $request->room_no,
        ];

        return Inertia::render('Room/Index', RoomIndexResponse::from([
            'query'  => $query,
            'result' => Room::query()
                ->filter($query)
                ->with([
                    'roomType:id,hotel_id,name',
                    'roomType.hotel:id,name',
                ])
                ->orderBy($request->sort, $request->order)
                ->paginate($request->count, [
                    'id',
                    'room_type_id',
                    'room_no',
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
        return Inertia::render('Room/Form', RoomFormResponse::from([
            'hotels'    => Hotel::all(['id', 'name']),
            'roomTypes' => RoomType::all(['id', 'hotel_id', 'name']),
        ]));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(
        RoomRequest $request,
        CreateRoom $createRoom
    ): RedirectResponse {
        /** @var array<string, mixed> */
        $input = $request->validated();

        $room = $createRoom->execute($input);

        return redirect()->route('rooms.show', [$room])
            ->with('success', 'Room created.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Room $room): Response
    {
        return Inertia::render('Room/Form', RoomFormResponse::from([
            'room'      => $room,
            'hotels'    => Hotel::all(['id', 'name']),
            'roomTypes' => RoomType::all(['id', 'hotel_id', 'name']),
        ]));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(
        RoomRequest $request,
        Room $room,
        UpdateRoom $updateRoom
    ): RedirectResponse {
        /** @var array<string, mixed> */
        $input = $request->validated();

        $updateRoom->execute($room, $input);

        return redirect()->back()->with('success', 'Room updated.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(
        Room $room,
        DeleteRoom $deleteRoom,
    ): RedirectResponse {
        $deleteRoom->execute($room);

        return redirect()->route('rooms.index')
            ->with('success', 'Room deleted.');
    }

    /**
     * Get list of available rooms available on specified dates
     */
    public function getAvailableRooms(
        GetAvailableRooms $getAvailableRooms,
    ): JsonResponse {
        /** @var ?int */
        $roomTypeId = request()->input('room_type_id');
        /** @var ?string */
        $checkInDate = request()->input('check_in_date');
        /** @var ?string */
        $checkOutDate = request()->input('check_out_date');
        /** @var ?int */
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

        return response()->json($availableRooms->map(fn (Room $room) => [
            'id'           => $room->id,
            'room_type_id' => $room->room_type_id,
            'room_no'      => $room->room_no,
        ]));
    }
}
