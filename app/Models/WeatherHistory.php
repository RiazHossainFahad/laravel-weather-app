<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WeatherHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        'city', 'lon', 'lat', 'weather_condition', 'temperature', 'temperature_feel_like', 'humidity', 'wind_speed'
    ];
}