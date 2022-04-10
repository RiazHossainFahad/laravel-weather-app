<?php

namespace App\Services\Admin\Weather;

use App\Services\BaseService;
use App\Models\WeatherHistory;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class WeatherHistoryService extends BaseService
{
    public function __construct(WeatherHistory $model)
    {
        parent::__construct($model);
    }

    public function getPaginatedData($request)
    {
        $weather_histories = $this->model->query();

        if ($request->has('city')) {
            $weather_histories->whereIn('city', $request->city);
        }

        if ($request->has('dates') && $request->dates) {
            $dates = explode('to', $request->dates);
            $start_date = Carbon::parse($dates[0])->format('Y-m-d 00:00:00');
            $end_date = Carbon::parse($dates[1])->format('Y-m-d 23:59:59');
            $weather_histories->whereBetween('created_at', [$start_date, $end_date]);
        }

        if ($request->has('weather_condition')) {
            $weather_histories->when($request->weather_condition, fn ($q) => $q->where('weather_condition', 'like',  '%' . $request->weather_condition . '%'));
        }

        if ($request->has('min_temperature')) {
            $weather_histories->when($request->min_temperature, fn ($q) => $q->where('temperature', '>=', $request->min_temperature));
        }

        if ($request->has('max_temperature')) {
            $weather_histories->when($request->max_temperature, fn ($q) => $q->where('temperature', '<=', $request->max_temperature));
        }

        $weather_histories = $weather_histories->latest()->paginate(10);

        if ($request->has('city') && $request->city) {
            $weather_histories->appends(['city' => $request->city]);
        }

        if ($request->has('dates') && $request->dates) {
            $weather_histories->appends(['dates' => $request->dates]);
        }

        if ($request->has('weather_condition') && $request->weather_condition) {
            $weather_histories->appends(['weather_condition' => $request->weather_condition]);
        }

        if ($request->has('min_temperature') && $request->min_temperature) {
            $weather_histories->appends(['min_temperature' => $request->min_temperature]);
        }

        if ($request->has('max_temperature') && $request->max_temperature) {
            $weather_histories->appends(['max_temperature' => $request->max_temperature]);
        }

        return $weather_histories;
    }

    public function updateOrCreateWeatherHistory($data_array, $id = null)
    {
        try {
            DB::beginTransaction();
            $data = collect($data_array)->only([
                'city', 'lon', 'lat', 'weather_condition', 'temperature', 'temperature_feel_like', 'humidity', 'wind_speed'
            ])->toArray();

            if (!$id) {
                $data['created_by'] = auth()->id();
            } else {
                $data['updated_by'] = auth()->id();
            }

            $model_data = $this->model->updateOrCreate(['id' => $id], $data);

            DB::commit();

            return $model_data;
        } catch (\Throwable $th) {
            DB::rollback();

            throw $th;
        }
    }

    public function deleteWeatherHistory($id)
    {
        try {
            DB::beginTransaction();

            $this->model->where('id', $id)->delete();

            DB::commit();

            return null;
        } catch (\Throwable $th) {
            DB::rollback();

            throw $th;
        }
    }
}