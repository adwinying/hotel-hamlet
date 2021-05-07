<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Hotel;
use App\Models\RoomType;
use Illuminate\Http\Request;
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
    public function show(RoomType $roomType)
    {
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function edit(RoomType $roomType)
    {
    }

    /**
     * Update the specified resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, RoomType $roomType)
    {
    }

    /**
     * Remove the specified resource from storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(RoomType $roomType)
    {
    }
}
