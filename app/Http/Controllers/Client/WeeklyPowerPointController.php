<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Model\WeeklyPowerPoint;
use App\Traits\ActivePageTrait;
use Illuminate\Http\Request;

class WeeklyPowerPointController extends Controller
{
    use ActivePageTrait;

    private $weeklyPowerPoint;
    private $paginate;
    private $redisTime;

    public function __construct(WeeklyPowerPoint $weeklyPowerPoint)
    {
        $this->weeklyPowerPoint = $weeklyPowerPoint;
        $this->paginate = config('app.paginate');
        $this->redisTime = config('app.redis_time');
    }

    public function index(Request $request)
    {
        $this->setClientPage('powerpoint', 'weekly_power_point');
        if ($request->has('tim-kiem') && !empty($request->get('tim-kiem')) && $request->get('tim-kiem') != '' || $request->get('tim-kiem') == '0') {
            $searchValue = $request->get('tim-kiem');
            $weeklyPowerPoints = $this->weeklyPowerPoint
                ->where('name', 'LIKE', '%' . $searchValue . '%')
                ->orWhere('number_of_views', 'LIKE', '%' . $searchValue . '%')
                ->orWhere('number_of_downloads', 'LIKE', '%' . $searchValue . '%')
                ->latest()
                ->paginate($this->paginate)
                ->appends(['tim-kiem' => $searchValue]);
        } else {
            // Get page for paginate with Redis
            $page = $request->input('page', 1);
            // Cache with Redis
            $weeklyPowerPoints = \Cache::remember('vmcgc_client_weekly_power_point_page_' . $page, $this->redisTime, function () {
                return $this->weeklyPowerPoint->latest()->paginate($this->paginate);
            });
        }
        return view('client.components.weekly_power_point.index')->with(compact('weeklyPowerPoints'));
    }

    public function search(Request $request)
    {
        $this->setClientPage('powerpoint', 'weekly_power_point');
        return redirect()->route('client.weekly-power-point.index', ['tim-kiem' => $request->input('search')]);
    }

    public function updateNumberOfDownloads(Request $request) {
        $data = $request->all();
        $weeklyPowerPoint = $this->weeklyPowerPoint->find($data['id']);
        $weeklyPowerPoint->update([
            'number_of_downloads' => $weeklyPowerPoint->number_of_downloads + 1
        ]);
        return response()->json([
            'result' => true
        ]);
    }
}
