<?php

namespace Tests\Feature\Requests\Admin;

use App\Http\Requests\Admin\HotelRequest;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;
use Tests\ValidationTestCase;

class HotelRequestTest extends ValidationTestCase
{
    protected function request(): FormRequest
    {
        return new HotelRequest();
    }

    protected function baseInput(): array
    {
        return [
            'name'      => Str::random(255),
            'is_hidden' => mt_rand(0, 1),
        ];
    }

    public function formData()
    {
        return [
            'All OK' => [
                true,
            ],

            'name is empty' => [
                false, [], 'name',
            ],

            'name exceeds 255 chars' => [
                false, ['name' => Str::random(256)],
            ],

            'is_hidden is empty' => [
                false, [], 'is_hidden',
            ],

            'is_hidden is not boolean' => [
                false, ['is_hidden' => 'foobar'],
            ],
        ];
    }
}
