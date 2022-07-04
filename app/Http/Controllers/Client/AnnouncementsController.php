<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Model\NewsAndAnnouncements;
use App\Traits\ActivePageTrait;
use App\Traits\RedisTrait;
use Illuminate\Http\Request;

class AnnouncementsController extends Controller
{
    use ActivePageTrait;
    use RedisTrait;

    private $newsAndAnnouncements;
    private $url;
    private $numberOfViews;
    private $paginate;
    private $redisTime;

    public function __construct(NewsAndAnnouncements $newsAndAnnouncements)
    {
        $this->newsAndAnnouncements = $newsAndAnnouncements;
        $this->paginate = config('app.paginate_posts');
        $this->redisTime = config('app.redis_time');
    }

    public function index(Request $request)
    {
        $this->setClientPage('posts', 'announcements');
        if ($request->has('tim-kiem') && !empty($request->get('tim-kiem')) && $request->get('tim-kiem') != '' || $request->get('tim-kiem') == '0') {
            $searchValue = $request->get('tim-kiem');
            $listAnnouncements = $this->newsAndAnnouncements
                ->with(['admin_user' => function ($query) {
                    $query->select('id', 'name', 'image_name', 'image_path');
                }])
                ->where([['title', 'LIKE', '%' . $searchValue . '%'], ['type', 'announcements'], ['status', 1]])
                ->orWhere([['created_at', 'LIKE', '%' . $searchValue . '%'], ['type', 'announcements'], ['status', 1]])
                ->latest()
                ->paginate($this->paginate)
                ->appends(['tim-kiem' => $searchValue]);
        } else {
            // Get page for paginate with Redis
            $page = $request->input('page', 1);
            // Cache with Redis
            $listAnnouncements = \Cache::remember('vmcgc_client_announcements_page_' . $page, $this->redisTime, function () {
                return $this->newsAndAnnouncements
                    ->with(['admin_user' => function ($query) {
                        $query->select('id', 'name', 'image_name', 'image_path');
                    }])
                    ->where([['type', 'announcements'], ['status', 1]])
                    ->latest()
                    ->paginate($this->paginate);
            });
        }
        return view('client.components.announcements.index')->with(compact('listAnnouncements'));
    }

    public function search(Request $request)
    {
        $this->setClientPage('posts', 'announcements');
        return redirect()->route('client.announcements.index', ['tim-kiem' => $request->input('search')]);
    }

    public function details($url)
    {
        $this->setClientPage('posts', 'announcements');

        $this->url = $url;

        // Cache with Redis
        $announcements = \Cache::remember('vmcgc_client_announcements_url_' . $this->url, $this->redisTime, function () {
            return $this->newsAndAnnouncements->with(['admin_user' => function ($query) {
                $query->select('id', 'name', 'image_name', 'image_path');
            }])->where([['url', $this->url], ['type', 'announcements'], ['status', 1]])->first();
        });

        // Update number_of_views

        $this->numberOfViews = $this->getKey('vmcgc_client_announcements_url_' . $this->url . '_number_of_views');

        if (empty($this->numberOfViews)) {
            $this->numberOfViews = \Cache::remember('vmcgc_client_announcements_url_' . $this->url . '_number_of_views', $this->redisTime, function () {
                return $this->newsAndAnnouncements->where([['url', $this->url], ['type', 'announcements'], ['status', 1]])->first()->number_of_views + 1;
            });
        } else {
            $this->deleteKey('vmcgc_client_announcements_url_' . $this->url . '_number_of_views');
            $this->numberOfViews = \Cache::remember('vmcgc_client_announcements_url_' . $this->url . '_number_of_views', $this->redisTime, function () {
                return $this->numberOfViews + 1;
            });
        }

        $announcements->update([
            'number_of_views' => $this->numberOfViews
        ]);

        $numberOfViews = $this->numberOfViews;

        return view('client.components.announcements.details')->with(compact('announcements', 'numberOfViews'));
    }
}
