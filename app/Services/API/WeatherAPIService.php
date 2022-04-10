<?php

namespace App\Services\API;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;

class WeatherAPIService
{
    protected $weather_base_url = null;
    protected $weather_app_id = null;

    public function __construct()
    {
        $this->weather_base_url = config('services.weather.base_url');
        $this->weather_app_id = config('services.weather.app_id');
    }

    public function getHttpClient()
    {
        return new Client([
            'base_uri' => $this->weather_base_url . '/',
        ]);
    }

    public function getWeatherByCity($city, $units = 'metric')
    {
        try {
            $client = $this->getHttpClient();
            $response = $client->request(
                'GET',
                "weather?q={$city}&appid={$this->weather_app_id}&units={$units}"
            );
            $results = json_decode($response->getBody()->getContents());
            return $results;
        } catch (\Exception $ex) {
            Log::debug('---API ERROR-- ' . $ex->getMessage());

            return false;
        }
    }
}