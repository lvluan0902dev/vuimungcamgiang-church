<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Model\Album;
use App\Model\AlbumImage;
use App\Traits\ActivePageTrait;
use App\Traits\CheckStatusTrait;
use App\Traits\RedisTrait;
use App\Traits\UploadImageTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;

class AlbumImageController extends Controller
{
    use UploadImageTrait;
    use CheckStatusTrait;
    use RedisTrait;
    use ActivePageTrait;

    private $albumImage;
    private $album;
    private $album_id;
    private $paginate;
    private $redisTime;

    public function __construct(AlbumImage $albumImage, Album $album)
    {
        $this->albumImage = $albumImage;
        $this->album = $album;
        $this->paginate = config('app.paginate');
        $this->redisTime = config('app.redis_time');
    }

    public function index(Request $request, $album_id)
    {
        $this->setAdminPage('album', 'album_index');
        $this->album_id = $album_id;
        // Get page for paginate with Redis
        $page = $request->input('page', 1);
        // Cache with Redis
        $albumImages = \Cache::remember('vmcgc_admin_' . $album_id . '_album_image_page_' . $page, $this->redisTime, function () {
            return $this->albumImage
                ->where('album_id', $this->album_id)
                ->latest()
                ->paginate($this->paginate);
        });

        $album = $this->album->find($album_id);

        return view('admin.components.album.album_image.index')->with(compact('albumImages', 'album'));
    }

    public function store(Request $request, $album_id)
    {
        $this->setAdminPage('album', 'album_index');
        $data = $request->all();

        if (!isset($data['image']) || empty($data['image'])) {
            Session::flash('album_image_add_error_message', 'Vui lòng chọn Hình ảnh');
            return redirect()->back();
        }

        $album = $this->album->find($album_id);

        $status = $this->checkStatus(isset($data['status']) ? $data['status'] : null);

        foreach ($data['image'] as $image) {
            $uploadSingleImageForMultipleInput = $this->uploadSingleImageForMultipleInput($image, 'Vui Mừng Cẩm Giàng Album ' . $album->title, 'album_image', null, null);
            if ($uploadSingleImageForMultipleInput == false) {
                Session::flash('album_image_add_error_message', 'Thêm Album - Hình ảnh không thành công');
                return redirect()->back()->withInput();
            }
            $this->albumImage->create([
                'album_id' => $album_id,
                'image_name' => $uploadSingleImageForMultipleInput['image_name'],
                'image_path' => $uploadSingleImageForMultipleInput['image_path'],
                'status' => $status
            ]);
        }

        Session::flash('album_image_add_success_message', 'Thêm Album - Hình ảnh thành công');
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

        $this->albumImage->find($data['id'])->update([
            'status' => $status,
        ]);

        // Clear all key in Redis
        $this->removeAllKey();
        return response()->json([
            'id' => $data['id'],
            'status' => $status,
        ]);
    }

    public function delete($id)
    {
        $this->setAdminPage('album', 'album_index');
        // Find and delete
        $albumImage = $this->albumImage->find($id);
        // Delete image
        if (file_exists($albumImage->image_path)) {
            unlink($albumImage->image_path);
        }
        $albumImage->delete();

        // Push Notification and Return
        Session::flash('album_image_delete_success_message', 'Xoá Album - Hình ảnh thành công');
        // Clear all key in Redis
        $this->removeAllKey();
        return redirect()->back();
    }
}
