<?php

namespace App\Http\Controllers\Admin;

use App\Actions\RoomType\CreateRoomType;
use App\Actions\RoomType\DeleteRoomType;
use App\Actions\RoomType\UpdateRoomType;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\RoomTypeRequest;
use App\Models\Hotel;
use App\Models\RoomType;
use Inertia\Inertia;

class RoomTypeController extends Controller
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
            'name',
        ];

        return Inertia::render('RoomType/Index', [
            'query'  => request()->all($queryParams),
            'result' => RoomType::query()
                ->filter(request()->only($queryParams))
                ->with('hotel:id,name')
                ->orderBy($sort, $order)
                ->paginate($count, [
                    'id',
                    'hotel_id',
                    'name',
                ]),
            'hotels' => fn () => Hotel::all(['id', 'name']),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return Inertia::render('RoomType/Form', [
            'hotels' => fn () => Hotel::all(['id', 'name']),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function store(
        RoomTypeRequest $request,
        CreateRoomType $createRoomType
    ) {
        $input = $request->validated();

        $roomType = $createRoomType->execute($input);

        return redirect()->route('room_types.show', [$roomType])
            ->with('success', 'Room type created.');
    }

    /**
     * Display the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function show(RoomType $roomType)
    {
        return Inertia::render('RoomType/Form', [
            'roomType' => [
                'id'       => $roomType->id,
                'hotel_id' => $roomType->hotel_id,
                'name'     => $roomType->name,
                'price'    => $roomType->price,
            ],
            'hotels' => fn () => Hotel::all(['id', 'name']),
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function update(
        RoomTypeRequest $request,
        RoomType $roomType,
        UpdateRoomType $updateRoomType
    ) {
        $input = $request->validated();

        $updateRoomType->execute($roomType, $input);

        return redirect()->back()->with('success', 'Room type updated.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(RoomType $roomType, DeleteRoomType $deleteRoomType)
    {
        $deleteRoomType->execute($roomType);

        return redirect()->route('room_types.index')
            ->with('success', 'Room type deleted.');
    }
}
