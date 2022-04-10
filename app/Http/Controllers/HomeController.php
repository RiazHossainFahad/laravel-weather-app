<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\Admin\Weather\WeatherHistoryService;

class HomeController extends Controller
{
    protected $weather_history_service = null;

    public function __construct(WeatherHistoryService $weather_history_service)
    {
        $this->weather_history_service = $weather_history_service;
    }

    public function welcome(Request $request)
    {
        $weather_histories = $this->weather_history_service->getPaginatedData($request);
        return view('welcome', compact('weather_histories'));
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('home');
    }
}