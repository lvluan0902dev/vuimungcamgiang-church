<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Model\Position;
use App\Traits\ActivePageTrait;
use App\Traits\CheckOrderTrait;
use App\Traits\CheckStatusTrait;
use App\Traits\RedisTrait;
use App\Traits\UploadImageTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class PositionController extends Controller
{
    use CheckOrderTrait;
    use CheckStatusTrait;
    use RedisTrait;
    use UploadImageTrait;
    use ActivePageTrait;

    private $position;
    private $paginate;
    private $redisTime;

    public function __construct(Position $position)
    {
        $this->position = $position;
        $this->paginate = config('app.paginate');
        $this->redisTime = config('app.redis_time');
    }

    public function index(Request $request)
    {
        $this->setAdminPage('home_page', 'position');
        if ($request->has('tim-kiem') && !empty($request->get('tim-kiem')) && $request->get('tim-kiem') != '' || $request->get('tim-kiem') == '0') {
            $searchValue = $request->get('tim-kiem');
            $positions = $this->position
                ->where('name', 'LIKE', '%' . $searchValue . '%')
                ->orWhere('type', 'LIKE', '%' . $searchValue . '%')
                ->orWhere('content', 'LIKE', '%' . $searchValue . '%')
                ->orWhere('order', 'LIKE', '%' . $searchValue . '%')
                ->latest()
                ->paginate($this->paginate)
                ->appends(['tim-kiem' => $searchValue]);
        } else {
            // Get page for paginate with Redis
            $page = $request->input('page', 1);
            // Cache with Redis
            $positions = \Cache::remember('vmcgc_admin_position_page_' . $page, $this->redisTime, function () {
                return $this->position->latest()->paginate($this->paginate);
            });
        }
        return view('admin.components.position.index')->with(compact('positions'));
    }

    public function search(Request $request)
    {
        $this->setAdminPage('home_page', 'position');
        return redirect()->route('admin.position.index', ['tim-kiem' => $request->input('search')]);
    }

    public function create()
    {
        $this->setAdminPage('home_page', 'position');
        return view('admin.components.position.add');
    }

    public function store(Request $request)
    {
        $this->setAdminPage('home_page', 'position');
        $rules = [
            'name' => 'required',
            'type' => 'required',
            'content' => 'required',
            'image' => 'required|image',
        ];

        $customMessages = [
            'name.required' => 'Vui lòng nhập Tên',
            'type.required' => 'Vui lòng nhập Chức vụ',
            'content.required' => 'Vui lòng nhập Nội dung',
            'image.required' => 'Vui lòng chọn Hình ảnh',
            'image.image' => 'Hình ảnh không hợp lệ',
        ];

        $this->validate($request, $rules, $customMessages);

        $data = $request->all();

        $uploadSingleImage = $this->uploadSingleImage($request, 'image', 'Vui Mừng Cẩm Giàng người giữ chức vụ ' . $data['name'], 'position', 131, 131);
        if ($uploadSingleImage == false) {
            Session::flash('position_add_error_message', 'Thêm Người giữ chức vụ không thành công');
            return redirect()->back()->withInput();
        }

        $status = $this->checkStatus(isset($data['status']) ? $data['status'] : null);

        $order = $this->checkOrder(isset($data['order']) ? $data['order'] : null);

        $this->position->create([
            'name' => $data['name'],
            'type' => $data['type'],
            'content' => $data['content'],
            'image_name' => $uploadSingleImage['image_name'],
            'image_path' => $uploadSingleImage['image_path'],
            'status' => $status,
            'order' => $order
        ]);

        Session::flash('position_add_success_message', 'Thêm Người giữ chức vụ thành công');
        // Clear all key in Redis
        $this->removeAllKey();
        return redirect()->back();
    }

    public function updateStatus(Request $request)
    {
        $this->setAdminPage('home_page', 'position');
        $data = $request->all();

        $status = -1;

        if ($data['status'] == '1') {
            $status = 0;
        } else {
            $status = 1;
        }

        $this->position->find($data['id'])->update([
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
        $this->setAdminPage('home_page', 'position');
        $position = $this->position->find($id);
        return view('admin.components.position.edit')->with(compact('position'));
    }

    public function update($id, Request $request)
    {
        $this->setAdminPage('home_page', 'position');
        $rules = [
            'name' => 'required',
            'type' => 'required',
            'content' => 'required',
            'image' => 'image',
        ];

        $customMessages = [
            'name.required' => 'Vui lòng nhập Tên',
            'type.required' => 'Vui lòng nhập Chức vụ',
            'content.required' => 'Vui lòng nhập Nội dung',
            'image.image' => 'Hình ảnh không hợp lệ',
        ];

        $this->validate($request, $rules, $customMessages);

        $data = $request->all();

        $uploadSingleImage = [];
        if (isset($data['image']) && !empty($data['image'])) {
            $uploadSingleImage = $this->uploadSingleImage($request, 'image', 'Vui Mừng Cẩm Giàng người giữ chức vụ ' . $data['name'], 'position', 131, 131);
            if ($uploadSingleImage == false) {
                Session::flash('banner_edit_error_message', 'Sửa Người giữ chức vụ không thành công');
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

        $this->position->find($id)->update([
            'name' => $data['name'],
            'type' => $data['type'],
            'content' => $data['content'],
            'image_name' => $uploadSingleImage['image_name'],
            'image_path' => $uploadSingleImage['image_path'],
            'status' => $status,
            'order' => $order
        ]);

        Session::flash('position_edit_success_message', 'Sửa Người giữ chức vụ thành công');
        // Clear all key in Redis
        $this->removeAllKey();
        return redirect()->back();
    }

    public function delete($id)
    {
        $this->setAdminPage('home_page', 'position');
        // Find and delete
        $position = $this->position->find($id);
        // Delete image
        if (file_exists($position->image_path)) {
            unlink($position->image_path);
        }
        $position->delete();

        // Push Notification and Return
        Session::flash('position_delete_success_message', 'Xoá Người giữ chức vụ thành công');
        // Clear all key in Redis
        $this->removeAllKey();
        return redirect()->back();
    }
}
