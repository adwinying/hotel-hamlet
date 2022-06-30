<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class ReservationRequest extends FormRequest
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
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'check_in_date'  => 'required|date_format:Y-m-d',
            'check_out_date' => 'required|date_format:Y-m-d|after:check_in_date',
            'room_id'        => 'required|exists:rooms,id',
            'guest_name'     => 'required|max:255',
            'guest_email'    => 'required|email|max:255',
            'remarks'        => 'nullable|max:1000',
        ];
    }

    /**
     * Get the attributes for the defined validation rules.
     *
     * @return array<string, string>
     */
    public function attributes()
    {
        return [
            'room_id' => 'Room No.',
        ];
    }
}
