<?php

namespace App\Http\Controllers\Admin;

use App\Actions\Hotel\CreateHotel;
use App\Actions\Hotel\DeleteHotel;
use App\Actions\Hotel\UpdateHotel;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\HotelRequest;
use App\Http\Responses\HotelFormResponse;
use App\Http\Responses\HotelIndexResponse;
use App\Models\Hotel;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class HotelController extends Controller
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
            'name',
            'is_hidden',
        ];

        return Inertia::render('Hotel/Index', HotelIndexResponse::from([
            'query'  => request()->all($queryParams),
            'result' => Hotel::query()
                ->filter(request()->only($queryParams))
                ->orderBy($sort, $order)
                ->paginate($count, [
                    'id',
                    'name',
                    'is_hidden',
                ]),
        ]));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): Response
    {
        return Inertia::render('Hotel/Form', HotelFormResponse::from([]));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(
        HotelRequest $request,
        CreateHotel $createHotel,
    ): RedirectResponse {
        /** @var array<string, mixed> */
        $input = $request->validated();

        $hotel = $createHotel->execute($input);

        return redirect()->route('hotels.show', [$hotel])
            ->with('success', 'Hotel created.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Hotel $hotel): Response
    {
        return Inertia::render('Hotel/Form', HotelFormResponse::from([
            'hotel' => $hotel,
        ]));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(
        HotelRequest $request,
        Hotel $hotel,
        UpdateHotel $updateHotel
    ): RedirectResponse {
        /** @var array<string, mixed> */
        $input = $request->validated();

        $updateHotel->execute($hotel, $input);

        return redirect()->back()->with('success', 'Hotel updated.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(
        Hotel $hotel,
        DeleteHotel $deleteHotel
    ): RedirectResponse {
        $deleteHotel->execute($hotel);

        return redirect()->route('hotels.index')
            ->with('success', 'Hotel deleted.');
    }
}
