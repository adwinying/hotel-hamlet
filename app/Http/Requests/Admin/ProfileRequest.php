<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Hash;

class ProfileRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name'         => 'required|max:255',
            'email'        => 'required|email|max:255',
            'old_password' => 'required_with:password',
            'password'     => 'nullable|min:8|confirmed',
        ];
    }

    /**
     * Get the attributes for the defined validation rules.
     *
     * @return array
     */
    public function attributes()
    {
        return [
            'old_password' => 'current password',
        ];
    }

    /**
     * Configure the validator instance.
     *
     * @param \Illuminate\Validation\Validator $validator
     */
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            if ($this->input('old_password')) {
                $this->checkCurrentPasswordValid($validator);
            }
        });
    }

    protected function checkCurrentPasswordValid($validator)
    {
        $isPasswordValid = Hash::check(
            $this->input('old_password'),
            auth()->user()->password,
        );

        if (!$isPasswordValid) {
            $validator->errors()->add(
                'old_password',
                'Current password is incorrect.'
            );
        }
    }
}
