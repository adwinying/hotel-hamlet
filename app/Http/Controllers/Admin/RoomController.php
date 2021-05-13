<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Hotel;
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
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
    }

    /**
     * Display the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function show(Room $room)
    {
    }

    /**
     * Update the specified resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Room $room)
    {
    }

    /**
     * Remove the specified resource from storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(Room $room)
    {
    }
}