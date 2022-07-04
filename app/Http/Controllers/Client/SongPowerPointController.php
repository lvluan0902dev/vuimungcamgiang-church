<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Model\SongPowerPoint;
use App\Traits\ActivePageTrait;
use Illuminate\Http\Request;

class SongPowerPointController extends Controller
{
    use ActivePageTrait;

    private $songPowerPoint;
    private $paginate;
    private $redisTime;

    public function __construct(SongPowerPoint $songPowerPoint)
    {
        $this->songPowerPoint = $songPowerPoint;
        $this->paginate = config('app.paginate');
        $this->redisTime = config('app.redis_time');
    }

    public function index(Request $request)
    {
        $this->setClientPage('powerpoint', 'song_power_point');
        if ($request->has('tim-kiem') && !empty($request->get('tim-kiem')) && $request->get('tim-kiem') != '' || $request->get('tim-kiem') == '0') {
            $searchValue = $request->get('tim-kiem');
            $songPowerPoints = $this->songPowerPoint
                ->where('name', 'LIKE', '%' . $searchValue . '%')
                ->orWhere('number_of_views', 'LIKE', '%' . $searchValue . '%')
                ->orWhere('number_of_downloads', 'LIKE', '%' . $searchValue . '%')
                ->orderBy('order', 'ASC')
                ->paginate($this->paginate)
                ->appends(['tim-kiem' => $searchValue]);
        } else {
            // Get page for paginate with Redis
            $page = $request->input('page', 1);
            // Cache with Redis
            $songPowerPoints = \Cache::remember('vmcgc_client_song_power_point_page_' . $page, $this->redisTime, function () {
                return $this->songPowerPoint->orderBy('order', 'ASC')->paginate($this->paginate);
            });
        }
        return view('client.components.song_power_point.index')->with(compact('songPowerPoints'));
    }

    public function search(Request $request)
    {
        $this->setClientPage('powerpoint', 'song_power_point');
        return redirect()->route('client.song-power-point.index', ['tim-kiem' => $request->input('search')]);
    }

    public function updateNumberOfDownloads(Request $request) {
        $data = $request->all();
        $songPowerPoint = $this->songPowerPoint->find($data['id']);
        $songPowerPoint->update([
            'number_of_downloads' => $songPowerPoint->number_of_downloads + 1
        ]);
        return response()->json([
            'result' => true
        ]);
    }
}
