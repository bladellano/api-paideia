<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PoloRequest extends APIFormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'name' => 'required|min:3',
            'city' => 'required|min:3',
            'uf' => 'required|min:2',
            'responsible' => 'required|min:3',
            'address' => 'required',
            'email' => 'required|email',
            'phone' => 'required|regex:/^\(\d{2}\)\d{5}-\d{4}$/',
        ];
    }
}
