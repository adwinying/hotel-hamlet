<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class RoomTypeRequest extends FormRequest
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
            'hotel_id' => 'required|exists:hotels,id',
            'name'     => [
                'required',
                'max:255',
                Rule::unique('room_types')
                    ->where(fn ($q) => $q->whereHotelId($this->input('hotel_id')))
                    ->ignore($this->route('room_type')),
            ],
            'price' => 'required|integer|max:99999',
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
            'hotel_id' => 'Hotel',
        ];
    }
}
