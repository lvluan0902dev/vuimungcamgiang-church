<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Model\NewsAndAnnouncements;
use App\Traits\ActivePageTrait;
use App\Traits\CheckStatusTrait;
use App\Traits\RedisTrait;
use App\Traits\UploadImageTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;

class NewsController extends Controller
{
    use UploadImageTrait;
    use CheckStatusTrait;
    use RedisTrait;
    use ActivePageTrait;

    private $newsAndAnnouncements;
    private $paginate;
    private $redisTime;

    public function __construct(NewsAndAnnouncements $newsAndAnnouncements)
    {
        $this->newsAndAnnouncements = $newsAndAnnouncements;
        $this->paginate = config('app.paginate');
        $this->redisTime = config('app.redis_time');
    }

    public function index(Request $request)
    {
        $this->setAdminPage('posts', 'news');
        if ($request->has('tim-kiem') && !empty($request->get('tim-kiem')) && $request->get('tim-kiem') != '' || $request->get('tim-kiem') == '0') {
            $searchValue = $request->get('tim-kiem');
            $listNews = $this->newsAndAnnouncements
                ->with(['admin_user' => function($query) {
                    $query->select('id', 'name');
                }])
                ->where([['title', 'LIKE', '%' . $searchValue . '%'], ['type', 'news']])
                ->orWhere([['content', 'LIKE', '%' . $searchValue . '%'], ['type', 'news']])
                ->orWhere([['created_at', 'LIKE', '%' . $searchValue . '%'], ['type', 'news']])
                ->orWhere([['number_of_views', 'LIKE', '%' . $searchValue . '%'], ['type', 'news']])
                ->latest()
                ->paginate($this->paginate)
                ->appends(['tim-kiem' => $searchValue]);
        } else {
            // Get page for paginate with Redis
            $page = $request->input('page', 1);
            // Cache with Redis
            $listNews = \Cache::remember('vmcgc_admin_news_page_' . $page, $this->redisTime, function () {
                return $this->newsAndAnnouncements
                    ->with(['admin_user' => function($query) {
                        $query->select('id', 'name');
                    }])
                    ->where('type', 'news')
                    ->latest()
                    ->paginate($this->paginate);
            });
        }
        return view('admin.components.news.index')->with(compact('listNews'));
    }

    public function search(Request $request)
    {
        $this->setAdminPage('posts', 'news');
        return redirect()->route('admin.news.index', ['tim-kiem' => $request->input('search')]);
    }

    public function create()
    {
        $this->setAdminPage('posts', 'news');
        return view('admin.components.news.add');
    }

    public function store(Request $request)
    {
        $this->setAdminPage('posts', 'news');
        $rules = [
            'title' => 'required',
            'content' => 'required',
            'image' => 'required|image',
        ];

        $customMessages = [
            'title.required' => 'Vui lòng nhập Tiêu đề',
            'content.required' => 'Vui lòng nhập Nội dung',
            'image.required' => 'Vui lòng chọn Hình ảnh',
            'image.image' => 'Hình ảnh không hợp lệ',
        ];

        $this->validate($request, $rules, $customMessages);

        $data = $request->all();

        $uploadSingleImage = $this->uploadSingleImage($request, 'image', 'Vui Mừng Cẩm Giàng tin tức ' . $data['title'], 'news', 330, 200);
        if ($uploadSingleImage == false) {
            Session::flash('news_add_error_message', 'Thêm Tin tức không thành công');
            return redirect()->back()->withInput();
        }

        $status = $this->checkStatus(isset($data['status']) ? $data['status'] : null);

        $this->newsAndAnnouncements->create([
            'title' => $data['title'],
            'url' => Str::slug($data['title']) . '-' . Str::random(10),
            'content' => $data['content'],
            'image_name' => $uploadSingleImage['image_name'],
            'image_path' => $uploadSingleImage['image_path'],
            'type' => 'news',
            'admin_user_id' => Auth::guard('admin')->id(),
            'number_of_views' => 0,
            'status' => $status,
        ]);

        Session::flash('news_add_success_message', 'Thêm Tin tức thành công');
        // Clear all key in Redis
        $this->removeAllKey();
        return redirect()->back();
    }

    public function updateStatus(Request $request)
    {
        $this->setAdminPage('posts', 'news');
        $data = $request->all();

        $status = -1;

        if ($data['status'] == '1') {
            $status = 0;
        } else {
            $status = 1;
        }

        $this->newsAndAnnouncements->find($data['id'])->update([
            'status' => $status,
        ]);

        // Clear all key in Redis
        $this->removeAllKey();
        return response()->json([
            'id' => $data['id'],
            'status' => $status,
        ]);
    }

    public function edit($id)
    {
        $this->setAdminPage('posts', 'news');
        $news = $this->newsAndAnnouncements->find($id);
        return view('admin.components.news.edit')->with(compact('news'));
    }

    public function update($id, Request $request)
    {
        $this->setAdminPage('posts', 'news');
        $rules = [
            'title' => 'required',
            'content' => 'required',
            'image' => 'image',
        ];

        $customMessages = [
            'title.required' => 'Vui lòng nhập Tiêu đề',
            'content.required' => 'Vui lòng nhập Nội dung',
            'image.image' => 'Hình ảnh không hợp lệ',
        ];

        $this->validate($request, $rules, $customMessages);

        $data = $request->all();

        $uploadSingleImage = [];
        if (isset($data['image']) && !empty($data['image'])) {
            $uploadSingleImage = $this->uploadSingleImage($request, 'image', 'Vui Mừng Cẩm Giàng tin tức ' . $data['title'], 'news', 330, 200);
            if ($uploadSingleImage == false) {
                Session::flash('news_edit_error_message', 'Sửa Tin tức không thành công');
                return redirect()->back()->withInput();
            }

            // Delete old image
            if (isset($data['current_image_name']) && isset($data['current_image_path']) && file_exists($data['current_image_path'])) {
                unlink($data['current_image_path']);
            }
        } else {
            $uploadSingleImage['image_name'] = $data['current_image_name'];
            $uploadSingleImage['image_path'] = $data['current_image_path'];
        }


        $status = $this->checkStatus(isset($data['status']) ? $data['status'] : null);

        $this->newsAndAnnouncements->find($id)->update([
            'title' => $data['title'],
            'url' => Str::slug($data['title']) . '-' . Str::random(10),
            'content' => $data['content'],
            'image_name' => $uploadSingleImage['image_name'],
            'image_path' => $uploadSingleImage['image_path'],
            'status' => $status,
        ]);

        Session::flash('news_edit_success_message', 'Sửa Tin tức thành công');
        // Clear all key in Redis
        $this->removeAllKey();
        return redirect()->back();
    }

    public function delete($id)
    {
        $this->setAdminPage('posts', 'news');
        // Find and delete
        $news = $this->newsAndAnnouncements->find($id);
        // Delete image
        if (file_exists($news->image_path)) {
            unlink($news->image_path);
        }
        $news->delete();

        // Push Notification and Return
        Session::flash('news_delete_success_message', 'Xoá Tin tức thành công');
        // Clear all key in Redis
        $this->removeAllKey();
        return redirect()->back();
    }
}
