<?php

namespace App\Http\Requests\Admin;

use Illuminate\Validation\Rule;
use App\Http\Requests\FormRequest;


class UserRequest extends FormRequest
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
        $rules = [
            'name' => ['bail', 'required'],
            'email' => ['bail', 'required', 'email', Rule::unique('users')->ignore($this->id)],
            'password' => ['bail', 'required', 'min:6', 'confirmed'],
            'roles' => ['bail', 'required', 'array', 'min: 1']
        ];

        if ($this->id) {
            $rules['password'] = ['bail', 'min:6', 'confirmed'];
        }
        return $rules;
    }
}