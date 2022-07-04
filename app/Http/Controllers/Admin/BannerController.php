<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Model\Banner;
use App\Traits\ActivePageTrait;
use App\Traits\CheckOrderTrait;
use App\Traits\CheckStatusTrait;
use App\Traits\RedisTrait;
use App\Traits\UploadImageTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Redis;

class BannerController extends Controller
{
    use UploadImageTrait;
    use CheckStatusTrait;
    use CheckOrderTrait;
    use RedisTrait;
    use ActivePageTrait;

    private $banner;
    private $paginate;
    private $redisTime;

    public function __construct(Banner $banner)
    {
        $this->banner = $banner;
        $this->paginate = config('app.paginate');
        $this->redisTime = config('app.redis_time');
    }

    public function index(Request $request)
    {
        $this->setAdminPage('home_page', 'banner');
        if ($request->has('tim-kiem') && !empty($request->get('tim-kiem')) && $request->get('tim-kiem') != '' || $request->get('tim-kiem') == '0') {
            $searchValue = $request->get('tim-kiem');
            $banners = $this->banner
                ->where('title', 'LIKE', '%' . $searchValue . '%')
                ->orWhere('content', 'LIKE', '%' . $searchValue . '%')
                ->orWhere('order', 'LIKE', '%' . $searchValue . '%')
                ->latest()
                ->paginate($this->paginate)
                ->appends(['tim-kiem' => $searchValue]);
        } else {
            // Get page for paginate with Redis
            $page = $request->input('page', 1);
            // Cache with Redis
            $banners = \Cache::remember('vmcgc_admin_banner_page_' . $page, $this->redisTime, function () {
                return $this->banner->latest()->paginate($this->paginate);
            });
        }
        return view('admin.components.banner.index')->with(compact('banners'));
    }

    public function search(Request $request)
    {
        $this->setAdminPage('home_page', 'banner');
        return redirect()->route('admin.banner.index', ['tim-kiem' => $request->input('search')]);
    }

    public function create()
    {
        $this->setAdminPage('home_page', 'banner');
        return view('admin.components.banner.add');
    }

    public function store(Request $request)
    {
        $this->setAdminPage('home_page', 'banner');
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

        $uploadSingleImage = $this->uploadSingleImage($request, 'image', 'Vui Mừng Cẩm Giàng Banner ' . $data['title'], 'banner', 800, 725);
        if ($uploadSingleImage == false) {
            Session::flash('banner_add_error_message', 'Thêm Banner không thành công');
            return redirect()->back()->withInput();
        }

        $status = $this->checkStatus(isset($data['status']) ? $data['status'] : null);

        $order = $this->checkOrder(isset($data['order']) ? $data['order'] : null);

        $this->banner->create([
            'title' => $data['title'],
            'content' => $data['content'],
            'image_name' => $uploadSingleImage['image_name'],
            'image_path' => $uploadSingleImage['image_path'],
            'status' => $status,
            'order' => $order
        ]);

        Session::flash('banner_add_success_message', 'Thêm Banner thành công');
        // Clear all key in Redis
        $this->removeAllKey();
        return redirect()->back();
    }

    public function updateStatus(Request $request)
    {
        $this->setAdminPage('home_page', 'banner');
        $data = $request->all();

        $status = -1;

        if ($data['status'] == '1') {
            $status = 0;
        } else {
            $status = 1;
        }

        $this->banner->find($data['id'])->update([
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
        $this->setAdminPage('home_page', 'banner');
        $banner = $this->banner->find($id);
        return view('admin.components.banner.edit')->with(compact('banner'));
    }

    public function update($id, Request $request)
    {
        $this->setAdminPage('home_page', 'banner');
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
            $uploadSingleImage = $this->uploadSingleImage($request, 'image', 'Vui Mừng Cẩm Giàng Banner ' . $data['title'], 'banner', 800, 725);
            if ($uploadSingleImage == false) {
                Session::flash('banner_edit_error_message', 'Sửa Banner không thành công');
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

        $order = $this->checkOrder(isset($data['order']) ? $data['order'] : null);

        $this->banner->find($id)->update([
            'title' => $data['title'],
            'content' => $data['content'],
            'image_name' => $uploadSingleImage['image_name'],
            'image_path' => $uploadSingleImage['image_path'],
            'status' => $status,
            'order' => $order
        ]);

        Session::flash('banner_edit_success_message', 'Sửa Banner thành công');
        // Clear all key in Redis
        $this->removeAllKey();
        return redirect()->back();
    }

    public function delete($id)
    {
        $this->setAdminPage('home_page', 'banner');
        // Find and delete
        $banner = $this->banner->find($id);
        // Delete image
        if (file_exists($banner->image_path)) {
            unlink($banner->image_path);
        }
        $banner->delete();

        // Push Notification and Return
        Session::flash('banner_delete_success_message', 'Xoá Banner thành công');
        // Clear all key in Redis
        $this->removeAllKey();
        return redirect()->back();
    }
}
