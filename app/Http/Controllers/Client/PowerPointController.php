<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Model\SongPowerPoint;
use App\Model\WeeklyPowerPoint;
use App\Traits\ActivePageTrait;
use Illuminate\Http\Request;

class PowerPointController extends Controller
{
    use ActivePageTrait;

    private $songPowerPoint;
    private $weeklyPowerPoint;

    public function __construct(SongPowerPoint $songPowerPoint, WeeklyPowerPoint $weeklyPowerPoint)
    {
        $this->songPowerPoint = $songPowerPoint;
        $this->weeklyPowerPoint = $weeklyPowerPoint;
    }

    public function viewSongPowerPoint($file_name)
    {
        $this->setClientPage('powerpoint', 'song_power_point');
        $powerPoint = $this->songPowerPoint->where('file_name', $file_name)->first();
        $powerPoint->update([
            'number_of_views' => $powerPoint->number_of_views + 1
        ]);
        return view('client.components.power_point.index')->with(compact('powerPoint'));
    }

    public function viewWeeklyPowerPoint($file_name)
    {
        $this->setClientPage('powerpoint', 'weekly_power_point');
        $powerPoint = $this->weeklyPowerPoint->where('file_name', $file_name)->first();
        $powerPoint->update([
            'number_of_views' => $powerPoint->number_of_views + 1
        ]);
        return view('client.components.power_point.index')->with(compact('powerPoint'));
    }
}
