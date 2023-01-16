<?php

namespace App\Http\Requests\OccupantRequest;

use Illuminate\Foundation\Http\FormRequest;

class StoreOccupantRequest extends FormRequest
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
            'username' => 'required|unique:users,username',
            'password' => 'required|string|min:8|confirmed',
            'name' => 'string|required',
            'address' => 'required',
            'type' => 'required',
            'status' => 'required',
            'number' => 'required',
            'gender' => 'required',
            'birthdate' => 'required',
            'vaccination' => 'required',
            'relative_name' => 'required',
            'relative_contact' => 'required',
        ];
    }
}
