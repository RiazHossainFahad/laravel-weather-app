<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest as ParentFormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Exceptions\HttpResponseException;

class FormRequest extends ParentFormRequest
{
    protected function failedValidation(Validator $validator)
    {
        if ($this->expectsJson()) {
            $errors = (new ValidationException($validator))->errors();
            throw new HttpResponseException(
                response()->json(['message' => collect($errors)->first(function ($value, $key) {
                    return $value;
                })[0], 'status' => 'error'], 200)
            );
        }

        parent::failedValidation($validator);
    }
}