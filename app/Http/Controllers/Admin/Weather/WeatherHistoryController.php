<?php

namespace App\Http\Controllers\Admin\Weather;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\WeatherHistoryRequest;
use App\Services\Admin\Weather\WeatherHistoryService;

class WeatherHistoryController extends Controller
{
    protected $weather_history_service = null;

    public function __construct(WeatherHistoryService $weather_history_service)
    {
        $this->middleware('permission:edit_weather_record', ['only' => ['edit', 'update']]);
        $this->middleware('permission:delete_weather_record', ['only' => ['destroy']]);

        $this->weather_history_service = $weather_history_service;
    }

    /* Display a listing of the resource.
    *
    * @return \Illuminate\Http\Response
    */
    public function edit($id)
    {
        $weather_history = $this->weather_history_service->get($id);
        return view('admin.weather.history.edit', compact('weather_history'));
    }

    public function update(WeatherHistoryRequest $request, $id)
    {
        try {
            $weather_history = $this->weather_history_service->updateOrCreateWeatherHistory($request->all(), $id);

            return response()->json(['message' => 'Weather record updated successfully', 'status' => 'success'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage(), 'status' => 'error'], 200);
        }
    }

    public function destroy($id)
    {
        try {
            $weather_history = $this->weather_history_service->deleteWeatherHistory($id);
            return redirect()->back()->with('success', 'Weather record deleted successfully');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
}