<?php

namespace App\Http\Requests\OccupantHistoryRequest;

use Illuminate\Foundation\Http\FormRequest;

class UpdateOccupantHistoryRequest extends FormRequest
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
            'details' => 'string|required',
            'activity_by' => 'string|required',
        ];
    }
}
