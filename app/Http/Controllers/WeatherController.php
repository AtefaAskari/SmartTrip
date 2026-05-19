<?php

namespace App\Http\Controllers;

use App\Models\Trip;
use App\Models\WeatherForecast;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WeatherController extends Controller
{
    public function index(Trip $trip)
    {
        if (!$trip->canBeViewedBy(Auth::user())) abort(403);
        // Generate mock weather if not exist
        if ($trip->weatherForecasts()->count() == 0) {
            $this->generateMockWeather($trip);
        }
        $forecasts = $trip->weatherForecasts()->orderBy('forecast_date')->get();
        return view('weather.index', compact('trip', 'forecasts'));
    }

    private function generateMockWeather($trip)
    {
        $conditions = ['Sunny', 'Partly Cloudy', 'Cloudy', 'Light Rain', 'Rainy'];
        $icons = ['☀️', '⛅', '☁️', '🌦️', '🌧️'];
        $city = $trip->destinations->first()->city ?? 'Unknown City';
        for ($date = $trip->start_date->copy(); $date <= $trip->end_date; $date->addDay()) {
            $idx = rand(0, count($conditions)-1);
            WeatherForecast::create([
                'trip_id' => $trip->id,
                'forecast_date' => $date,
                'city' => $city,
                'condition' => $conditions[$idx],
                'icon' => $icons[$idx],
                'temp_high' => rand(15, 35),
                'temp_low' => rand(5, 20),
            ]);
        }
    }
}