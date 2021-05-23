<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class RoomRequest extends FormRequest
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
            'room_type_id' => 'required|exists:room_types,id',
            'room_no'      => [
                'required',
                'max:255',
                Rule::unique('rooms')
                    ->where(fn ($q) => $q->whereRoomTypeId($this->input('room_type_id')))
                    ->ignore($this->route('room')),
            ],
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
            'room_type_id' => 'Room Type',
        ];
    }
}
