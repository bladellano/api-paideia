<?php

namespace App\Http\Requests;

use App\Rules\GenderRule;

class StudentUpdateRequest extends APIFormRequest
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
            'cpf' => 'required|cpf',
            'image' => 'image|mimes:jpeg,png,jpg,gif|max:512',
            'name_mother' => 'required|min:3',
            'birth_date' => 'required|date',
            'gender' => ['required', new GenderRule()],
            'nationality' => 'required',
            'naturalness' => 'required',
        ];
    }

    /**
    * Get the error messages for the defined validation rules.
    * @return array<string, string>
    */
    public function messages(): array
    {
        return [
            'cpf.cpf' => 'O número CPF é inválido.',
        ];
    }
}
