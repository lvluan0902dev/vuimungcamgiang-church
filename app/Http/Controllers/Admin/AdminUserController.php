<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Model\AdminUser;
use App\Traits\ActivePageTrait;
use App\Traits\CheckStatusTrait;
use App\Traits\RedisTrait;
use App\Traits\UploadImageTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class AdminUserController extends Controller
{
    use ActivePageTrait;
    use UploadImageTrait;
    use RedisTrait;
    use CheckStatusTrait;

    private $adminUser;
    private $adminUserId;
    private $paginate;
    private $redisTime;

    public function __construct(AdminUser $adminUser)
    {
        $this->adminUser = $adminUser;
        $this->paginate = config('app.paginate');
        $this->redisTime = config('app.redis_time');
    }

    public function personalPage()
    {
        $this->setAdminPage('', '');
        $adminUserId = Auth::guard('admin')->id();
        $this->adminUserId = $adminUserId;
        $adminUser = \Cache::remember('vmcgc_admin_admin_user' . $this->adminUserId, $this->redisTime, function () {
            return $this->adminUser
                ->with(
                    [
                        'news' => function ($query) {
                            $query->select('id', 'admin_user_id');
                        },
                        'announcements' => function ($query) {
                            $query->select('id', 'admin_user_id');
                        },
                        'original_bible_verses' => function ($query) {
                            $query->select('id', 'admin_user_id');
                        }
                    ]
                )
                ->find($this->adminUserId);
        });
        return view('admin.components.admin_user.personal_page')->with(compact('adminUser'));
    }

    public function changeInformation($id, Request $request)
    {
        $this->setAdminPage('', '');
        $rules = [
            'name' => 'required',
            'type' => 'required',
            'image' => 'image',
        ];

        $customMessages = [
            'name.required' => 'Vui lòng nhập Tên',
            'type.required' => 'Vui lòng nhập Loại',
            'image.image' => 'Hình ảnh không hợp lệ',
        ];

        $this->validate($request, $rules, $customMessages);

        $data = $request->all();

        $uploadSingleImage = [];
        if (isset($data['image']) && !empty($data['image'])) {
            $uploadSingleImage = $this->uploadSingleImage($request, 'image', 'Vui Mừng Cẩm Giàng tài khoản ' . $data['name'], 'admin_user', 131, 131);
            if ($uploadSingleImage == false) {
                Session::flash('admin_user_change_information_error_message', 'Sửa Thông tin tài khoản không thành công');
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

        if (!isset($data['phone_number']) || empty($data['phone_number'])) {
            $data['phone_number'] = '';
        }

        if (!isset($data['address']) || empty($data['address'])) {
            $data['address'] = '';
        }

        $this->adminUser->find($id)->update([
            'name' => $data['name'],
            'type' => $data['type'],
            'phone_number' => $data['phone_number'],
            'address' => $data['address'],
            'image_name' => $uploadSingleImage['image_name'],
            'image_path' => $uploadSingleImage['image_path'],
        ]);

        Session::flash('admin_user_change_information_success_message', 'Sửa Thông tin tài khoản thành công');
        // Clear all key in Redis
        $this->removeAllKey();
        return redirect()->back();
    }

    public function changePassword($id, Request $request)
    {
        $this->setAdminPage('', '');
        $rules = [
            'old_password' => 'required',
            'new_password' => 'required',
            'confirm_new_password' => 'required',
        ];

        $customMessages = [
            'old_password.required' => 'Vui lòng nhập Mật khẩu cũ',
            'new_password.required' => 'Vui lòng nhập Mật khẩu mới',
            'confirm_new_password.required' => 'Vui lòng Xác nhận Mật khẩu mới',
        ];

        $this->validate($request, $rules, $customMessages);

        $data = $request->all();

        $adminUser = $this->adminUser->find($id);

        // Check old password is correct
        if (!Hash::check($data['old_password'], $adminUser->password)) {
            Session::flash('admin_user_change_password_error_message', 'Mật khẩu cũ không chính xác');
            return redirect()->back();
        }

        // Check new password equal confirm new password
        if ($data['new_password'] != $data['confirm_new_password']) {
            Session::flash('admin_user_change_password_error_message', 'Xác nhận Mật khẩu mới không chính xác');
            return redirect()->back();
        }

        // satisfy the conditions
        $adminUser->update([
            'password' => Hash::make($data['confirm_new_password'])
        ]);

        Session::flash('admin_user_change_password_success_message', 'Đổi mật khẩu thành công');
        return redirect()->back();
    }

    // ---------------------------------
    public function index(Request $request)
    {
        $this->setAdminPage('admin_user', 'admin_user_index');
        if ($request->has('tim-kiem') && !empty($request->get('tim-kiem')) && $request->get('tim-kiem') != '' || $request->get('tim-kiem') == '0') {
            $searchValue = $request->get('tim-kiem');
            $adminUsers = $this->adminUser
                ->where('name', 'LIKE', '%' . $searchValue . '%')
                ->orWhere('email', 'LIKE', '%' . $searchValue . '%')
                ->orWhere('type', 'LIKE', '%' . $searchValue . '%')
                ->latest()
                ->paginate($this->paginate)
                ->appends(['tim-kiem' => $searchValue]);
        } else {
            // Get page for paginate with Redis
            $page = $request->input('page', 1);
            // Cache with Redis
            $adminUsers = \Cache::remember('vmcgc_admin_admin_user_page_' . $page, $this->redisTime, function () {
                return $this->adminUser->latest()->paginate($this->paginate);
            });
        }

        $adminUserCurrently = Auth::guard('admin')->user();
        return view('admin.components.admin_user.index')->with(compact('adminUsers', 'adminUserCurrently'));
    }

    public function search(Request $request)
    {
        $this->setAdminPage('admin_user', 'admin_user_index');
        return redirect()->route('admin.admin-user.index', ['tim-kiem' => $request->input('search')]);
    }

    public function create()
    {
        $this->setAdminPage('admin_user', 'admin_user_index');
        return view('admin.components.admin_user.add');
    }

    public function store(Request $request)
    {
        $this->setAdminPage('admin_user', 'admin_user_index');
        $rules = [
            'name' => 'required',
            'email' => 'required|email:filter',
        ];

        $customMessages = [
            'name.required' => 'Vui lòng nhập Tên',
            'email.required' => 'Vui lòng nhập Email',
            'email.email' => 'Email không đúng định dạng',
        ];

        $this->validate($request, $rules, $customMessages);

        $data = $request->all();

        $status = $this->checkStatus(isset($data['status']) ? $data['status'] : null);

        $this->adminUser->create([
            'name' => $data['name'],
            'email' => $data['email'],
            'type' => $data['type'],
            'status' => $status,
            'phone_number' => '',
            'address' => '',
            'image_name' => '',
            'image_path' => '',
            'password' => Hash::make('123456')
        ]);

        Session::flash('admin_user_add_success_message', 'Thêm Tài khoản thành công. Mật khẩu mặc định là 123456');
        // Clear all key in Redis
        $this->removeAllKey();
        return redirect()->back();
    }

    public function updateStatus(Request $request)
    {
        $this->setAdminPage('admin_user', 'admin_user_index');
        $data = $request->all();

        $status = -1;

        if ($data['status'] == '1') {
            $status = 0;
        } else {
            $status = 1;
        }

        $this->adminUser->find($data['id'])->update([
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
        $this->setAdminPage('admin_user', 'admin_user_index');
        // Find and delete
        $adminUser = $this->adminUser->find($id);
        // Delete image
        if (file_exists($adminUser->image_path))
        {
            unlink($adminUser->image_path);
        }
        $adminUser->delete();

        // Push Notification and Return
        Session::flash('admin_user_delete_success_message', 'Xoá Tài khoản thành công');
        // Clear all key in Redis
        $this->removeAllKey();
        return redirect()->back();
    }
}
