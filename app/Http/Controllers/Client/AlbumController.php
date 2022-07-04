<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Model\Album;
use App\Model\AlbumImage;
use App\Traits\ActivePageTrait;
use App\Traits\RedisTrait;
use Illuminate\Http\Request;

class AlbumController extends Controller
{
    use ActivePageTrait;
    use RedisTrait;

    private $album;
    private $album_id;
    private $albumImage;
    private $url;
    private $numberOfViews;
    private $paginate;
    private $redisTime;

    public function __construct(Album $album, AlbumImage $albumImage)
    {
        $this->album = $album;
        $this->albumImage = $albumImage;
        $this->paginate = config('app.paginate_posts');
        $this->redisTime = config('app.redis_time');
    }

    public function index(Request $request)
    {
        $this->setClientPage('album', 'album_index');
        if ($request->has('tim-kiem') && !empty($request->get('tim-kiem')) && $request->get('tim-kiem') != '' || $request->get('tim-kiem') == '0') {
            $searchValue = $request->get('tim-kiem');
            $albums = $this->album
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
            $albums = \Cache::remember('vmcgc_client_album_page_' . $page, $this->redisTime, function () {
                return $this->album
                    ->with(['admin_user' => function ($query) {
                        $query->select('id', 'name', 'image_name', 'image_path');
                    }])
                    ->where('status', 1)
                    ->latest()
                    ->paginate($this->paginate);
            });
        }
        return view('client.components.album.index')->with(compact('albums'));
    }

    public function search(Request $request)
    {
        $this->setClientPage('album', 'album_index');
        return redirect()->route('client.album.index', ['tim-kiem' => $request->input('search')]);
    }

    public function details($url, Request $request)
    {
        $this->setClientPage('album', 'album_index');

        $this->url = $url;

        // Cache with Redis
        $album = \Cache::remember('vmcgc_client_album_url_' . $this->url, $this->redisTime, function () {
            return $this->album->with(['admin_user' => function ($query) {
                $query->select('id', 'name', 'image_name', 'image_path');
            }])->where([['url', $this->url], ['status', 1]])->first();
        });

        $this->album_id = $album->id;

        // Get page for paginate with Redis
        $page = $request->input('page', 1);
        // Cache with Redis
        $albumImages = \Cache::remember('vmcgc_client_album_details_' . $this->album_id . '_pageParent_' . $page, $this->redisTime, function () {
            return $this->albumImage->where([
                ['status', 1],
                ['album_id', $this->album_id]
            ])->latest()->paginate(27);
        });

        // Init get number_of_views
        $this->numberOfViews = $this->getKey('vmcgc_client_album_url_' . $this->url . '_number_of_views');
        if ($page == 1) {
            // Update number_of_views

            if (empty($this->numberOfViews)) {
                $this->numberOfViews = \Cache::remember('vmcgc_client_album_url_' . $this->url . '_number_of_views', $this->redisTime, function () {
                    return $this->album->where([['url', $this->url], ['status', 1]])->first()->number_of_views + 1;
                });
            } else {
                $this->deleteKey('vmcgc_client_album_url_' . $this->url . '_number_of_views');
                $this->numberOfViews = \Cache::remember('vmcgc_client_album_url_' . $this->url . '_number_of_views', $this->redisTime, function () {
                    return $this->numberOfViews + 1;
                });
            }

            $album->update([
                'number_of_views' => $this->numberOfViews
            ]);
        }

        $numberOfViews = $this->numberOfViews;

        return view('client.components.album.details')->with(compact('album', 'albumImages', 'numberOfViews'));
    }

    public function getAllAlbumImage(Request $request)
    {
        $data = $request->all();

        $this->album_id = $data['album_id'];

        // Get page for paginate with Redis
        $page = $request->input('page', 1);
        // Cache with Redis
        $albumImages = \Cache::remember('vmcgc_client_album_details_' . $this->album_id . '_pageChild_' . $page, $this->redisTime, function () {
            return $this->albumImage->where([
                ['status', 1],
                ['album_id', $this->album_id]
            ])->latest()->paginate(27);
        });

        return response()->json([
            'data' => $albumImages,
            'message' => 'Get All Album Image Success',
            'status' => 200
        ]);
    }
}
