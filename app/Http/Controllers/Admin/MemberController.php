<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Model\Member;
use App\Traits\ActivePageTrait;
use App\Traits\CheckOrderTrait;
use App\Traits\CheckStatusTrait;
use App\Traits\RedisTrait;
use App\Traits\UploadImageTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class MemberController extends Controller
{
    use UploadImageTrait;
    use CheckStatusTrait;
    use CheckOrderTrait;
    use RedisTrait;
    use ActivePageTrait;

    private $member;
    private $paginate;
    private $redisTime;

    public function __construct(Member $member)
    {
        $this->member = $member;
        $this->paginate = config('app.paginate');
        $this->redisTime = config('app.redis_time');
    }

    public function index(Request $request)
    {
        $this->setAdminPage('about', 'member');
        if ($request->has('tim-kiem') && !empty($request->get('tim-kiem')) && $request->get('tim-kiem') != '' || $request->get('tim-kiem') == '0') {
            $searchValue = $request->get('tim-kiem');
            $members = $this->member
                ->where('name', 'LIKE', '%' . $searchValue . '%')
                ->orWhere('type', 'LIKE', '%' . $searchValue . '%')
                ->orWhere('order', 'LIKE', '%' . $searchValue . '%')
                ->latest()
                ->paginate($this->paginate)
                ->appends(['tim-kiem' => $searchValue]);
        } else {
            // Get page for paginate with Redis
            $page = $request->input('page', 1);
            // Cache with Redis
            $members = \Cache::remember('vmcgc_admin_member_page_' . $page, $this->redisTime, function () {
                return $this->member->latest()->paginate($this->paginate);
            });
        }
        return view('admin.components.member.index')->with(compact('members'));
    }

    public function search(Request $request)
    {
        $this->setAdminPage('about', 'member');
        return redirect()->route('admin.member.index', ['tim-kiem' => $request->input('search')]);
    }

    public function create()
    {
        $this->setAdminPage('about', 'member');
        return view('admin.components.member.add');
    }

    public function store(Request $request)
    {
        $this->setAdminPage('about', 'member');
        $rules = [
            'name' => 'required',
            'type' => 'required',
            'image' => 'required|image',
        ];

        $customMessages = [
            'name.required' => 'Vui lòng nhập Tên',
            'type.required' => 'Vui lòng nhập Loại thành viên',
            'image.required' => 'Vui lòng chọn Hình ảnh',
            'image.image' => 'Hình ảnh không hợp lệ',
        ];

        $this->validate($request, $rules, $customMessages);

        $data = $request->all();

        $uploadSingleImage = $this->uploadSingleImage($request, 'image', 'Vui Mừng Cẩm Giàng thành viên ' . $data['name'], 'member', 131, 131);
        if ($uploadSingleImage == false) {
            Session::flash('member_add_error_message', 'Thêm Thành viên không thành công');
            return redirect()->back()->withInput();
        }

        $status = $this->checkStatus(isset($data['status']) ? $data['status'] : null);

        $order = $this->checkOrder(isset($data['order']) ? $data['order'] : null);

        $this->member->create([
            'name' => $data['name'],
            'type' => $data['type'],
            'image_name' => $uploadSingleImage['image_name'],
            'image_path' => $uploadSingleImage['image_path'],
            'status' => $status,
            'order' => $order
        ]);

        Session::flash('member_add_success_message', 'Thêm Thành viên thành công');
        // Clear all key in Redis
        $this->removeAllKey();
        return redirect()->back();
    }

    public function updateStatus(Request $request)
    {
        $this->setAdminPage('about', 'member');
        $data = $request->all();

        $status = -1;

        if ($data['status'] == '1') {
            $status = 0;
        } else {
            $status = 1;
        }

        $this->member->find($data['id'])->update([
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
        $this->setAdminPage('about', 'member');
        $member = $this->member->find($id);
        return view('admin.components.member.edit')->with(compact('member'));
    }

    public function update($id, Request $request)
    {
        $this->setAdminPage('about', 'member');
        $rules = [
            'name' => 'required',
            'type' => 'required',
            'image' => 'image',
        ];

        $customMessages = [
            'name.required' => 'Vui lòng nhập Tên',
            'type.required' => 'Vui lòng nhập Loại thành viên',
            'image.image' => 'Hình ảnh không hợp lệ',
        ];

        $this->validate($request, $rules, $customMessages);

        $data = $request->all();

        $uploadSingleImage = [];
        if (isset($data['image']) && !empty($data['image']))
        {
            $uploadSingleImage = $this->uploadSingleImage($request, 'image', 'Vui Mừng Cẩm Giàng thành viên ' . $data['name'], 'member', 131, 131);
            if ($uploadSingleImage == false) {
                Session::flash('member_edit_error_message', 'Sửa Thành viên không thành công');
                return redirect()->back()->withInput();
            }

            // Delete old image
            if (isset($data['current_image_name']) && isset($data['current_image_path']) && file_exists($data['current_image_path'])) {
                unlink($data['current_image_path']);
            }
        }
        else
        {
            $uploadSingleImage['image_name'] = $data['current_image_name'];
            $uploadSingleImage['image_path'] = $data['current_image_path'];
        }


        $status = $this->checkStatus(isset($data['status']) ? $data['status'] : null);

        $order = $this->checkOrder(isset($data['order']) ? $data['order'] : null);

        $this->member->find($id)->update([
            'name' => $data['name'],
            'type' => $data['type'],
            'image_name' => $uploadSingleImage['image_name'],
            'image_path' => $uploadSingleImage['image_path'],
            'status' => $status,
            'order' => $order
        ]);

        Session::flash('member_edit_success_message', 'Sửa Thành viên thành công');
        // Clear all key in Redis
        $this->removeAllKey();
        return redirect()->back();
    }

    public function delete($id)
    {
        $this->setAdminPage('about', 'member');
        // Find and delete
        $member = $this->member->find($id);
        // Delete image
        if (file_exists($member->image_path))
        {
            unlink($member->image_path);
        }
        $member->delete();

        // Push Notification and Return
        Session::flash('member_delete_success_message', 'Xoá Thành viên thành công');
        // Clear all key in Redis
        $this->removeAllKey();
        return redirect()->back();
    }
}
