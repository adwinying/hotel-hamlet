<?php

namespace App\Http\Responses\Admin;

use Spatie\LaravelData\Data;

class ProfileIndexResponseProfile extends Data
{
    public function __construct(
        public string $name,
        public string $email,
    ) {
    }
}
