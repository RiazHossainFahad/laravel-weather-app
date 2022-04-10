<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Log;
use Illuminate\Queue\SerializesModels;
use App\Services\API\WeatherAPIService;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use App\Services\Admin\Weather\WeatherHistoryService;

class FetchWeatherJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $cities = [];

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->cities = config('custom.weather_history_cities');
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(WeatherAPIService $weather_api_service, WeatherHistoryService $weather_history_service)
    {
        try {

            foreach ($this->cities as $key => $city) {
                // Call api
                $res = $weather_api_service->getWeatherByCity($city);

                if (!$res) {
                    Log::debug('No response from weather api!');
                    continue;
                }

                // Store weather history
                $this->processAndStoreWeatherHistory($weather_history_service, $city, $res);
            }
        } catch (\Throwable $th) {
            Log::debug($th->getMessage());
        }
    }

    protected function processAndStoreWeatherHistory($weather_history_service, $city, $res)
    {
        $data_array = [];

        $data_array['city'] = $city;
        $data_array['lon'] = $res->coord ? $res->coord->lon : '';
        $data_array['lat'] = $res->coord ? $res->coord->lat : '';
        $data_array['weather_condition'] = $res->weather ? $res->weather[0]->main : '';
        $data_array['temperature'] = $res->main ? $res->main->temp : '';
        $data_array['temperature_feel_like'] = $res->main ? $res->main->feels_like : '';
        $data_array['humidity'] = $res->main ? $res->main->humidity : '';
        $data_array['wind_speed'] = $res->wind ? ($res->wind->speed * 3.6) : '';

        $weather_history_service->updateOrCreateWeatherHistory($data_array);
        return;
    }
}