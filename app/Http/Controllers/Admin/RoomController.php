<?php

namespace App\Http\Controllers\Admin;

use App\Actions\Room\CreateRoom;
use App\Actions\Room\DeleteRoom;
use App\Actions\Room\GetAvailableRooms;
use App\Actions\Room\UpdateRoom;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\RoomAvailabilityCheckRequest;
use App\Http\Requests\Admin\RoomFormRequest;
use App\Http\Requests\Admin\RoomIndexRequest;
use App\Http\Responses\Admin\RoomAvailabilityCheckResponseRoom;
use App\Http\Responses\Admin\RoomFormResponse;
use App\Http\Responses\Admin\RoomIndexResponse;
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
        RoomFormRequest $request,
        CreateRoom $createRoom
    ): RedirectResponse {
        $room = $createRoom->execute($request->toArray());

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
        RoomFormRequest $request,
        Room $room,
        UpdateRoom $updateRoom
    ): RedirectResponse {
        $updateRoom->execute($room, $request->toArray());

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
        RoomAvailabilityCheckRequest $request,
        GetAvailableRooms $getAvailableRooms,
    ): JsonResponse {
        $reservation = Reservation::query()->find($request->reservation_id);
        $roomType    = RoomType::query()->findOrFail($request->room_type_id);

        $availableRooms = $getAvailableRooms->execute(
            $roomType,
            $request->check_in_date,
            $request->check_out_date,
            $reservation
        );

        return response()->json(
            RoomAvailabilityCheckResponseRoom::collection(
                $availableRooms->toArray(),
            ),
        );
    }
}
