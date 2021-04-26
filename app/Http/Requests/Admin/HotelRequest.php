<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class HotelRequest extends FormRequest
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
            'name'      => 'required|max:255',
            'is_hidden' => 'required|boolean',
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
            'is_hidden' => 'Is Hidden?',
        ];
    }
}
