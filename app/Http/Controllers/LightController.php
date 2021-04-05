<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use DateTime;
use DateTimeZone;
use GuzzleHttp;

class LightController extends Controller
{
    private $dateFormat = "Y-m-d H:i:s";
    private $timeFormat = "H:i:s";
    private $timezone = "Europe/Amsterdam";
    private $appropriateDayLight = 1020;
    private $appropriateNightLight = 1000;

    /**
     * determine if the light value is sufficient
     *
     */
    public function determine($light_value)
    {
        $currentDateTime = new DateTime("now", new DateTimeZone($this->timezone));
        DB::insert('insert into light_values (value, time) values (?, ?)', [$light_value, $currentDateTime->format($this->dateFormat)]);

        $weather = $this->get_weather_data();

        if ($currentDateTime->format($this->timeFormat) < $weather->sys->sunrise || $currentDateTime->format($this->timeFormat) > $weather->sys->sunset) {
            if ($light_value > $this->appropriateNightLight) {
                return response()->json(['sufficient' => 1]);
            } else {
                return response()->json(['sufficient' => 0]);
            }
        } else {
            if ($light_value > $this->appropriateDayLight) {
                return response()->json(['sufficient' => 1]);
            } else {
                return response()->json(['sufficient' => 0]);
            }
        }
    }

    public function overview()
    {
        $light_values = DB::select('select * from light_values order by time desc limit 5');

        $weather = $this->get_weather_data();

        return view('overview', ['light_values' => $light_values, 'weather' => $weather]);
    }

    private function convert_timestamps($weatherSys)
    {
        $converted = new DateTime();
        $converted->setTimezone(new DateTimeZone($this->timezone));
        $converted->setTimestamp($weatherSys);
        return $converted->format($this->timeFormat);
    }

    private function get_weather_data()
    {
        $client = new GuzzleHttp\Client(['base_uri' => 'api.openweathermap.org/data/2.5/weather?q=Amsterdam&appid=' . env('WEATHER_API_KEY') . '&units=metric']);
        $weather = json_decode($client->get('')->getBody());
        $weather->sys->sunrise = $this->convert_timestamps($weather->sys->sunrise);
        $weather->sys->sunset = $this->convert_timestamps($weather->sys->sunset);
        return $weather;
    }
}
