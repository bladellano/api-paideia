<?php

namespace App\Http\Requests;

use App\Rules\GenderRule;

class StudentRequest extends APIFormRequest
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
            'email' => 'email',
            'phone' => 'regex:/^\(\d{2}\)\d{5}-\d{4}$/',
            'cpf' => 'required|cpf',
            'rg' => '',
            'expedient_body' => '',
            'name_mother' => 'required|min:3',
            'birth_date' => 'required|date',
            'gender' => ['required',new GenderRule()],
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
