<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Model\NewsAndAnnouncements;
use App\Traits\ActivePageTrait;
use App\Traits\RedisTrait;
use Illuminate\Http\Request;

class NewsController extends Controller
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
        $this->setClientPage('posts', 'news');
        if ($request->has('tim-kiem') && !empty($request->get('tim-kiem')) && $request->get('tim-kiem') != '' || $request->get('tim-kiem') == '0') {
            $searchValue = $request->get('tim-kiem');
            $listNews = $this->newsAndAnnouncements
                ->with(['admin_user' => function ($query) {
                    $query->select('id', 'name', 'image_name', 'image_path');
                }])
                ->where([['title', 'LIKE', '%' . $searchValue . '%'], ['type', 'news'], ['status', 1]])
                ->orWhere([['created_at', 'LIKE', '%' . $searchValue . '%'], ['type', 'news'], ['status', 1]])
                ->latest()
                ->paginate($this->paginate)
                ->appends(['tim-kiem' => $searchValue]);
        } else {
            // Get page for paginate with Redis
            $page = $request->input('page', 1);
            // Cache with Redis
            $listNews = \Cache::remember('vmcgc_client_news_page_' . $page, $this->redisTime, function () {
                return $this->newsAndAnnouncements
                    ->with(['admin_user' => function ($query) {
                        $query->select('id', 'name', 'image_name', 'image_path');
                    }])
                    ->where([['type', 'news'], ['status', 1]])
                    ->latest()
                    ->paginate($this->paginate);
            });
        }
        return view('client.components.news.index')->with(compact('listNews'));
    }

    public function search(Request $request)
    {
        $this->setClientPage('posts', 'news');
        return redirect()->route('client.news.index', ['tim-kiem' => $request->input('search')]);
    }

    public function details($url)
    {
        $this->setClientPage('posts', 'news');

        $this->url = $url;

        // Cache with Redis
        $news = \Cache::remember('vmcgc_client_news_url_' . $this->url, $this->redisTime, function () {
            return $this->newsAndAnnouncements->with(['admin_user' => function ($query) {
                $query->select('id', 'name', 'image_name', 'image_path');
            }])->where([['url', $this->url], ['type', 'news'], ['status', 1]])->first();
        });

        // Update number_of_views

        $this->numberOfViews = $this->getKey('vmcgc_client_news_url_' . $this->url . '_number_of_views');

        if (empty($this->numberOfViews)) {
            $this->numberOfViews = \Cache::remember('vmcgc_client_news_url_' . $this->url . '_number_of_views', $this->redisTime, function () {
                return $this->newsAndAnnouncements->where([['url', $this->url], ['type', 'news'], ['status', 1]])->first()->number_of_views + 1;
            });
        } else {
            $this->deleteKey('vmcgc_client_news_url_' . $this->url . '_number_of_views');
            $this->numberOfViews = \Cache::remember('vmcgc_client_news_url_' . $this->url . '_number_of_views', $this->redisTime, function () {
                return $this->numberOfViews + 1;
            });
        }

        $news->update([
            'number_of_views' => $this->numberOfViews
        ]);

        $numberOfViews = $this->numberOfViews;

        return view('client.components.news.details')->with(compact('news', 'numberOfViews'));
    }
}
