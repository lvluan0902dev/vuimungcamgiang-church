<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Model\PhoneNumber;
use App\Traits\ActivePageTrait;
use App\Traits\CheckOrderTrait;
use App\Traits\CheckStatusTrait;
use App\Traits\RedisTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class PhoneNumberController extends Controller
{
    use CheckOrderTrait;
    use CheckStatusTrait;
    use RedisTrait;
    use ActivePageTrait;

    private $phoneNumber;
    private $paginate;
    private $redisTime;

    public function __construct(PhoneNumber $phoneNumber)
    {
        $this->phoneNumber = $phoneNumber;
        $this->paginate = config('app.paginate');
        $this->redisTime = config('app.redis_time');
    }

    public function index(Request $request)
    {
        $this->setAdminPage('contact', 'phone_number');
        if ($request->has('tim-kiem') && !empty($request->get('tim-kiem')) && $request->get('tim-kiem') != '' || $request->get('tim-kiem') == '0') {
            $searchValue = $request->get('tim-kiem');
            $phoneNumbers = $this->phoneNumber
                ->where('content', 'LIKE', '%' . $searchValue . '%')
                ->orWhere('order', 'LIKE', '%' . $searchValue . '%')
                ->latest()
                ->paginate($this->paginate)
                ->appends(['tim-kiem' => $searchValue]);
        } else {
            // Get page for paginate with Redis
            $page = $request->input('page', 1);
            // Cache with Redis
            $phoneNumbers = \Cache::remember('vmcgc_admin_phone_number_page_' . $page, $this->redisTime, function () {
                return $this->phoneNumber->latest()->paginate($this->paginate);
            });
        }
        return view('admin.components.phone_number.index')->with(compact('phoneNumbers'));
    }

    public function search(Request $request)
    {
        $this->setAdminPage('contact', 'phone_number');
        return redirect()->route('admin.phone-number.index', ['tim-kiem' => $request->input('search')]);
    }

    public function create()
    {
        $this->setAdminPage('contact', 'phone_number');
        return view('admin.components.phone_number.add');
    }

    public function store(Request $request)
    {
        $this->setAdminPage('contact', 'phone_number');
        $rules = [
            'content' => 'required',
        ];

        $customMessages = [
            'content.required' => 'Vui lòng nhập Nội dung',
        ];

        $this->validate($request, $rules, $customMessages);

        $data = $request->all();

        $status = $this->checkStatus(isset($data['status']) ? $data['status'] : null);

        $order = $this->checkOrder(isset($data['order']) ? $data['order'] : null);

        $this->phoneNumber->create([
            'content' => $data['content'],
            'status' => $status,
            'order' => $order
        ]);

        Session::flash('phone_number_add_success_message', 'Thêm Số điện thoại thành công');
        // Clear all key in Redis
        $this->removeAllKey();
        return redirect()->back();
    }

    public function updateStatus(Request $request)
    {
        $this->setAdminPage('contact', 'phone_number');
        $data = $request->all();

        $status = -1;

        if ($data['status'] == '1') {
            $status = 0;
        } else {
            $status = 1;
        }

        $this->phoneNumber->find($data['id'])->update([
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
        $this->setAdminPage('contact', 'phone_number');
        $phoneNumber = $this->phoneNumber->find($id);
        return view('admin.components.phone_number.edit')->with(compact('phoneNumber'));
    }

    public function update($id, Request $request)
    {
        $this->setAdminPage('contact', 'phone_number');
        $rules = [
            'content' => 'required',
        ];

        $customMessages = [
            'content.required' => 'Vui lòng nhập Nội dung',
        ];

        $this->validate($request, $rules, $customMessages);

        $data = $request->all();

        $status = $this->checkStatus(isset($data['status']) ? $data['status'] : null);

        $order = $this->checkOrder(isset($data['order']) ? $data['order'] : null);

        $this->phoneNumber->find($id)->update([
            'content' => $data['content'],
            'status' => $status,
            'order' => $order
        ]);

        Session::flash('phone_number_edit_success_message', 'Sửa Số điện thoại thành công');
        // Clear all key in Redis
        $this->removeAllKey();
        return redirect()->back();
    }

    public function delete($id)
    {
        $this->setAdminPage('contact', 'phone_number');
        // Find and delete
        $phoneNumber = $this->phoneNumber->find($id);
        $phoneNumber->delete();

        // Push Notification and Return
        Session::flash('phone_number_delete_success_message', 'Xoá Số điện thoại thành công');
        // Clear all key in Redis
        $this->removeAllKey();
        return redirect()->back();
    }
}
