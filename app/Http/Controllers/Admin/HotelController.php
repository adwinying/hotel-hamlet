<?php

namespace App\Http\Controllers\Admin;

use App\Actions\Hotel\CreateHotel;
use App\Actions\Hotel\DeleteHotel;
use App\Actions\Hotel\UpdateHotel;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\HotelRequest;
use App\Models\Hotel;
use Illuminate\Http\Request;
use Inertia\Inertia;

class HotelController extends Controller
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
            'name',
            'is_hidden',
        ];

        return Inertia::render('Hotel/Index', [
            'query'  => request()->all($queryParams),
            'result' => Hotel::query()
                ->filter(request()->only($queryParams))
                ->orderBy($sort, $order)
                ->paginate($count, [
                    'id',
                    'name',
                    'is_hidden',
                ]),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return Inertia::render('Hotel/Form');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function store(HotelRequest $request, CreateHotel $createHotel)
    {
        $input = $request->validated();

        $hotel = $createHotel->execute($input);

        return redirect()->route('hotels.show', [$hotel])
            ->with('success', 'Hotel created.');
    }

    /**
     * Display the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function show(Hotel $hotel)
    {
        return Inertia::render('Hotel/Form', [
            'hotel' => [
                'id'        => $hotel->id,
                'name'      => $hotel->name,
                'is_hidden' => $hotel->is_hidden,
            ],
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function edit(Hotel $hotel)
    {
    }

    /**
     * Update the specified resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function update(
        HotelRequest $request,
        Hotel $hotel,
        UpdateHotel $updateHotel
    ) {
        $input = $request->validated();

        $updateHotel->execute($hotel, $input);

        return redirect()->back()->with('success', 'Hotel updated.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(Hotel $hotel, DeleteHotel $deleteHotel)
    {
        $deleteHotel->execute($hotel);

        return redirect()->route('hotels.index')
            ->with('success', 'Hotel deleted.');
    }
}
