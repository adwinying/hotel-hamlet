<?php

namespace App\Http\Requests\Admin;

use Illuminate\Validation\Rule;
use Spatie\LaravelData\Attributes\Validation\Required;
use Spatie\LaravelData\Data;

class HotelFormRequest extends Data
{
    public function __construct(
        public string $name,
        #[Required]
        public bool $is_hidden,
    ) {
    }

    /**
     * @return array<string, mixed>
     */
    public static function rules(): array
    {
        return [
            'name' => [
                'required',
                'max:255',
                Rule::unique('hotels')->ignore(request()->route('hotel')),
            ],
        ];
    }

    /**
     * @return array<string, string>
     */
    public static function attributes(): array
    {
        return [
            'is_hidden' => 'Is Hidden?',
        ];
    }
}
