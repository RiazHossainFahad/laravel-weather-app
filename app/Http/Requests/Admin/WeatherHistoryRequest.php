<?php

namespace App\Http\Requests\Admin;

use App\Http\Requests\FormRequest;

class WeatherHistoryRequest extends FormRequest
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
            'city' => ['bail', 'required', 'string'],
            'lat' => ['bail', 'required', 'numeric'],
            'lon' => ['bail', 'required', 'numeric'],
            'weather_condition' => ['bail', 'required', 'string'],
            'temperature' => ['bail', 'required', 'numeric'],
            'temperature_feel_like' => ['bail', 'required', 'numeric'],
            'humidity' => ['bail', 'required', 'numeric'],
            'wind_speed' => ['bail', 'required', 'numeric'],
        ];
    }
}