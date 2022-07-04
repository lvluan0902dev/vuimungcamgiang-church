<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Model\Album;
use App\Model\NewsAndAnnouncements;
use App\Model\OriginalBibleVerse;
use App\Model\SongPowerPoint;
use App\Model\WeeklyPowerPoint;
use App\Traits\ActivePageTrait;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    use ActivePageTrait;

    private $songPowerPoint;
    private $weeklyPowerPoint;
    private $newsAndAnnouncements;
    private $originalBibleVerse;
    private $album;
    private $redisTime;

    public function __construct(SongPowerPoint $songPowerPoint, WeeklyPowerPoint $weeklyPowerPoint, NewsAndAnnouncements $newsAndAnnouncements, OriginalBibleVerse $originalBibleVerse, Album $album)
    {
        $this->songPowerPoint = $songPowerPoint;
        $this->weeklyPowerPoint = $weeklyPowerPoint;
        $this->newsAndAnnouncements = $newsAndAnnouncements;
        $this->originalBibleVerse = $originalBibleVerse;
        $this->album = $album;
        $this->redisTime = config('app.redis_time');
    }

    public function index()
    {
        $this->setAdminPage('dashboard', 'dashboard_index');
        $totalSongPowerPoint = \Cache::remember('vmcgc_admin_dashboard_total_song_power_point', $this->redisTime, function () {
            return $this->songPowerPoint->get()->count();
        });
        $totalWeeklyPowerPoint = \Cache::remember('vmcgc_admin_dashboard_total_weekly_power_point', $this->redisTime, function () {
            return $this->weeklyPowerPoint->get()->count();
        });
        $totalPowerPoint = $totalSongPowerPoint + $totalWeeklyPowerPoint;
        $totalNewsAndAnnouncements = \Cache::remember('vmcgc_admin_dashboard_total_news_and_announcements', $this->redisTime, function () {
            return $this->newsAndAnnouncements->get()->count();
        });
        $totalOriginalBibleVerse = \Cache::remember('vmcgc_admin_dashboard_total_original_bible_verse', $this->redisTime, function () {
            return $this->originalBibleVerse->get()->count();
        });
        $totalAlbum = \Cache::remember('vmcgc_admin_dashboard_total_album', $this->redisTime, function () {
            return $this->album->get()->count();
        });
        return view('admin.components.dashboard.index')->with(compact('totalPowerPoint', 'totalNewsAndAnnouncements', 'totalOriginalBibleVerse', 'totalAlbum'));
    }
}
