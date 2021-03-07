<?php

namespace App\Http\Middleware;

use App\Actions\User\GetUserSidebar;
use Illuminate\Http\Request;
use Inertia\Middleware;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that's loaded on the first page visit.
     *
     * @see https://inertiajs.com/server-side-setup#root-template
     * @var string
     */
    protected $rootView = 'layouts.admin';

    /**
     * Determines the current asset version.
     *
     * @see https://inertiajs.com/asset-versioning
     * @return string|null
     */
    public function version(Request $request)
    {
        return parent::version($request);
    }

    /**
     * Defines the props that are shared by default.
     *
     * @see https://inertiajs.com/shared-data
     * @return array
     */
    public function share(Request $request)
    {
        $getSidebarItems = app(GetUserSidebar::class);

        return array_merge(parent::share($request), [
            'sidebarItems' => auth()->check()
                ? $getSidebarItems(auth()->user())
                : null,
            'userInfo' => [
                'name' => auth()->user()->name ?? null,
            ],
        ]);
    }
}
