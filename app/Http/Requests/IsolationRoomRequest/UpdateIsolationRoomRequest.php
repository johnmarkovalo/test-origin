<?php

namespace App\Http\Requests\IsolationRoomRequest;

use Illuminate\Foundation\Http\FormRequest;

class UpdateIsolationRoomRequest extends FormRequest
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
            'isolation_id' => [
                'required',
                'exists:isolations,id',
            ],
            'room_no' => 'string|required',
            'status' => 'nullable'
        ];
    }
}
