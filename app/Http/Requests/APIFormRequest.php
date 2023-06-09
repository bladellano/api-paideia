<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;

abstract class APIFormRequest extends FormRequest
{
    abstract public function rules();
    abstract public function authorize();

    protected function failedValidation(Validator $validator)
    {
        $errors = (new ValidationException($validator))->errors();

        throw new HttpResponseException(
            response()->json(['errors'=> $errors], JsonResponse::HTTP_UNPROCESSABLE_ENTITY)
        );
    }

}
