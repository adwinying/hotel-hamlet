<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
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
    public function show(Hotel $hotel)
    {
        return Inertia::render('Hotel/Form', [
            'hotel' => [
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
    public function update(Request $request, Hotel $hotel)
    {
    }

    /**
     * Remove the specified resource from storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(Hotel $hotel)
    {
    }
}
