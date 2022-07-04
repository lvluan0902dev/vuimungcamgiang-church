<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Model\Album;
use App\Traits\CheckStatusTrait;
use App\Traits\RedisTrait;
use App\Traits\UploadImageTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use \App\Traits\ActivePageTrait;

class AlbumController extends Controller
{
    use UploadImageTrait;
    use CheckStatusTrait;
    use RedisTrait;
    use ActivePageTrait;

    private $album;
    private $paginate;
    private $redisTime;

    public function __construct(Album $album)
    {
        $this->album = $album;
        $this->paginate = config('app.paginate');
        $this->redisTime = config('app.redis_time');
    }

    public function index(Request $request)
    {
        $this->setAdminPage('album', 'album_index');
        if ($request->has('tim-kiem') && !empty($request->get('tim-kiem')) && $request->get('tim-kiem') != '' || $request->get('tim-kiem') == '0') {
            $searchValue = $request->get('tim-kiem');
            $albums = $this->album
                ->with(['admin_user' => function ($query) {
                    $query->select('id', 'name');
                }])
                ->where('title', 'LIKE', '%' . $searchValue . '%')
                ->orWhere('created_at', 'LIKE', '%' . $searchValue . '%')
                ->orWhere('number_of_views', 'LIKE', '%' . $searchValue . '%')
                ->latest()
                ->paginate($this->paginate)
                ->appends(['tim-kiem' => $searchValue]);
        } else {
            // Get page for paginate with Redis
            $page = $request->input('page', 1);
            // Cache with Redis
            $albums = \Cache::remember('vmcgc_admin_album_page_' . $page, $this->redisTime, function () {
                return $this->album
                    ->with(['admin_user' => function ($query) {
                        $query->select('id', 'name');
                    }])
                    ->latest()
                    ->paginate($this->paginate);
            });
        }
        return view('admin.components.album.index')->with(compact('albums'));
    }

    public function search(Request $request)
    {
        $this->setAdminPage('album', 'album_index');
        return redirect()->route('admin.album.index', ['tim-kiem' => $request->input('search')]);
    }

    public function create()
    {
        $this->setAdminPage('album', 'album_index');
        return view('admin.components.album.add');
    }

    public function store(Request $request)
    {
        $this->setAdminPage('album', 'album_index');
        $rules = [
            'title' => 'required',
            'image' => 'required|image',
        ];

        $customMessages = [
            'title.required' => 'Vui lòng nhập Tiêu đề',
            'image.required' => 'Vui lòng chọn Hình ảnh',
            'image.image' => 'Hình ảnh không hợp lệ',
        ];

        $this->validate($request, $rules, $customMessages);

        $data = $request->all();

        $uploadSingleImage = $this->uploadSingleImage($request, 'image', 'Vui Mừng Cẩm Giàng Album ' . $data['title'], 'album', 330, 200);
        if ($uploadSingleImage == false) {
            Session::flash('album_add_error_message', 'Thêm Album không thành công');
            return redirect()->back()->withInput();
        }

        $status = $this->checkStatus(isset($data['status']) ? $data['status'] : null);

        $this->album->create([
            'title' => $data['title'],
            'url' => Str::slug($data['title']) . '-' . Str::random(10),
            'image_name' => $uploadSingleImage['image_name'],
            'image_path' => $uploadSingleImage['image_path'],
            'admin_user_id' => Auth::guard('admin')->id(),
            'number_of_views' => 0,
            'status' => $status,
        ]);

        Session::flash('album_add_success_message', 'Thêm Album thành công');
        // Clear all key in Redis
        $this->removeAllKey();
        return redirect()->back();
    }

    public function updateStatus(Request $request)
    {
        $this->setAdminPage('album', 'album_index');
        $data = $request->all();

        $status = -1;

        if ($data['status'] == '1') {
            $status = 0;
        } else {
            $status = 1;
        }

        $this->album->find($data['id'])->update([
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
        $this->setAdminPage('album', 'album_index');
        $album = $this->album->find($id);
        return view('admin.components.album.edit')->with(compact('album'));
    }

    public function update($id, Request $request)
    {
        $this->setAdminPage('album', 'album_index');
        $rules = [
            'title' => 'required',
            'image' => 'image',
        ];

        $customMessages = [
            'title.required' => 'Vui lòng nhập Tiêu đề',
            'image.image' => 'Hình ảnh không hợp lệ',
        ];

        $this->validate($request, $rules, $customMessages);

        $data = $request->all();

        $uploadSingleImage = [];
        if (isset($data['image']) && !empty($data['image'])) {
            $uploadSingleImage = $this->uploadSingleImage($request, 'image', 'Vui Mừng Cẩm Giàng Album ' . $data['title'], 'album', 330, 200);
            if ($uploadSingleImage == false) {
                Session::flash('album_edit_error_message', 'Sửa Album không thành công');
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

        $this->album->find($id)->update([
            'title' => $data['title'],
            'url' => Str::slug($data['title']) . '-' . Str::random(10),
            'image_name' => $uploadSingleImage['image_name'],
            'image_path' => $uploadSingleImage['image_path'],
            'status' => $status,
        ]);

        Session::flash('album_edit_success_message', 'Sửa Album thành công');
        // Clear all key in Redis
        $this->removeAllKey();
        return redirect()->back();
    }

    public function delete($id)
    {
        $this->setAdminPage('album', 'album_index');
        // Find and delete
        $album = $this->album->with(['album_images' => function ($query) {
            $query->select('id', 'album_id', 'image_name', 'image_path');
        }])->find($id);

        // Delete image
        if (file_exists($album->image_path)) {
            unlink($album->image_path);
        }

        $albumImages = $album->album_images;

        // Delete Album image
        foreach ($albumImages as $image) {
            // Delete image
            if (file_exists($image->image_path)) {
                unlink($image->image_path);
            }

            $image->delete();
        }

        $album->delete();

        // Push Notification and Return
        Session::flash('album_delete_success_message', 'Xoá Album thành công');
        // Clear all key in Redis
        $this->removeAllKey();
        return redirect()->back();
    }
}
