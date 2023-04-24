<?php

namespace App\Http\Responses\Admin;

use Spatie\LaravelData\Data;

class ProfileIndexResponse extends Data
{
    public function __construct(
        public ProfileIndexResponseProfile $profile,
    ) {
    }
}
