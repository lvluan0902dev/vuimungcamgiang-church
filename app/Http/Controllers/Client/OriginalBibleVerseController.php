<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Model\OriginalBibleVerse;
use App\Traits\ActivePageTrait;
use App\Traits\RedisTrait;
use Illuminate\Http\Request;

class OriginalBibleVerseController extends Controller
{
    use ActivePageTrait;
    use RedisTrait;

    private $originalBibleVerse;
    private $url;
    private $numberOfViews;
    private $paginate;
    private $redisTime;

    public function __construct(OriginalBibleVerse $originalBibleVerse)
    {
        $this->originalBibleVerse = $originalBibleVerse;
        $this->paginate = config('app.paginate_posts');
        $this->redisTime = config('app.redis_time');
    }

    public function index(Request $request)
    {
        $this->setClientPage('posts', 'original_bible_verse');
        if ($request->has('tim-kiem') && !empty($request->get('tim-kiem')) && $request->get('tim-kiem') != '' || $request->get('tim-kiem') == '0') {
            $searchValue = $request->get('tim-kiem');
            $originalBibleVerses = $this->originalBibleVerse
                ->with(['admin_user' => function ($query) {
                    $query->select('id', 'name', 'image_name', 'image_path');
                }])
                ->where([['title', 'LIKE', '%' . $searchValue . '%'], ['status', 1]])
                ->orWhere([['created_at', 'LIKE', '%' . $searchValue . '%'], ['status', 1]])
                ->latest()
                ->paginate($this->paginate)
                ->appends(['tim-kiem' => $searchValue]);
        } else {
            // Get page for paginate with Redis
            $page = $request->input('page', 1);
            // Cache with Redis
            $originalBibleVerses = \Cache::remember('vmcgc_client_original_bible_verse_page_' . $page, $this->redisTime, function () {
                return $this->originalBibleVerse
                    ->with(['admin_user' => function ($query) {
                        $query->select('id', 'name', 'image_name', 'image_path');
                    }])
                    ->where('status', 1)
                    ->latest()
                    ->paginate($this->paginate);
            });
        }
        return view('client.components.original_bible_verse.index')->with(compact('originalBibleVerses'));
    }

    public function search(Request $request)
    {
        $this->setClientPage('posts', 'original_bible_verse');
        return redirect()->route('client.original-bible-verse.index', ['tim-kiem' => $request->input('search')]);
    }

    public function details($url)
    {
        $this->setClientPage('posts', 'original_bible_verse');

        $this->url = $url;

        // Cache with Redis
        $originalBibleVerse = \Cache::remember('vmcgc_client_original_bible_verse_url_' . $this->url, $this->redisTime, function () {
            return $this->originalBibleVerse->with(['admin_user' => function ($query) {
                $query->select('id', 'name', 'image_name', 'image_path');
            }])->where([['url', $this->url], ['status', 1]])->first();
        });

        // Update number_of_views

        $this->numberOfViews = $this->getKey('vmcgc_client_original_bible_verse_url_' . $this->url . '_number_of_views');

        if (empty($this->numberOfViews)) {
            $this->numberOfViews = \Cache::remember('vmcgc_client_original_bible_verse_url_' . $this->url . '_number_of_views', $this->redisTime, function () {
                return $this->originalBibleVerse->where([['url', $this->url], ['status', 1]])->first()->number_of_views + 1;
            });
        } else {
            $this->deleteKey('vmcgc_client_original_bible_verse_url_' . $this->url . '_number_of_views');
            $this->numberOfViews = \Cache::remember('vmcgc_client_original_bible_verse_url_' . $this->url . '_number_of_views', $this->redisTime, function () {
                return $this->numberOfViews + 1;
            });
        }

        $originalBibleVerse->update([
            'number_of_views' => $this->numberOfViews
        ]);

        $numberOfViews = $this->numberOfViews;

        return view('client.components.original_bible_verse.details')->with(compact('originalBibleVerse', 'numberOfViews'));
    }
}
