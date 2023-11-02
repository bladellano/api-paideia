<?php

namespace App\Http\Requests;

class StageRequestUpdate extends APIFormRequest
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
            'stage' => 'required',
            'description' => 'required|min:3',
        ];
    }
}
