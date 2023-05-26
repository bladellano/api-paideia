<?php

namespace App\Http\Requests;

use App\Rules\GenderRule;
use Illuminate\Foundation\Http\FormRequest;

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
            'email' => 'required|email',
            'phone' => 'required|regex:/^\(\d{2}\)\d{5}-\d{4}$/',
            'cpf' => 'required|cpf',
            'rg' => 'required',
            'expedient_body' => 'required',
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
            'cpf.cpf' => 'CPF number is invalid.',
        ];
    }
}
