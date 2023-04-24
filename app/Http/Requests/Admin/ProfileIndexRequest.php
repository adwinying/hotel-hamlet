<?php

namespace App\Http\Requests\Admin;

use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Validator;
use Spatie\LaravelData\Attributes\Validation\Confirmed;
use Spatie\LaravelData\Attributes\Validation\Email;
use Spatie\LaravelData\Attributes\Validation\Max;
use Spatie\LaravelData\Attributes\Validation\Min;
use Spatie\LaravelData\Attributes\Validation\RequiredWith;
use Spatie\LaravelData\Data;

class ProfileIndexRequest extends Data
{
    public function __construct(
        #[Max(255)]
        public string $name,
        #[Email,
    Max(255)]
        public string $email,
        #[RequiredWith('password')]
        public ?string $old_password,
        #[Min(8),
    Confirmed]
        public ?string $password,
    ) {
    }

    /**
     * @return array<string, string>
     */
    public static function attributes(): array
    {
        return [
            'old_password' => 'current password',
        ];
    }

    public static function withValidator(Validator $validator): void
    {
        $validator->after(function ($validator) {
            self::checkCurrentPasswordValid($validator);
        });
    }

    protected static function checkCurrentPasswordValid(Validator $validator): void
    {
        $oldPassword = $validator->getData()['old_password'] ?? null;

        if (!is_string($oldPassword)) {
            return;
        }

        $isPasswordValid = Hash::check(
            $oldPassword,
            auth()->user()?->password ?? '',
        );

        if (!$isPasswordValid) {
            $validator->errors()->add(
                'old_password',
                'Current password is incorrect.'
            );
        }
    }
}
