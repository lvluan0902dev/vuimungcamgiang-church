<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Model\Address;
use App\Traits\ActivePageTrait;
use App\Traits\CheckOrderTrait;
use App\Traits\CheckStatusTrait;
use App\Traits\RedisTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class AddressController extends Controller
{
    use CheckOrderTrait;
    use CheckStatusTrait;
    use RedisTrait;
    use ActivePageTrait;

    private $address;
    private $paginate;
    private $redisTime;

    public function __construct(Address $address)
    {
        $this->address = $address;
        $this->paginate = config('app.paginate');
        $this->redisTime = config('app.redis_time');
    }

    public function index(Request $request)
    {
        $this->setAdminPage('contact', 'address');
        if ($request->has('tim-kiem') && !empty($request->get('tim-kiem')) && $request->get('tim-kiem') != '' || $request->get('tim-kiem') == '0') {
            $searchValue = $request->get('tim-kiem');
            $addresses = $this->address
                ->where('content', 'LIKE', '%' . $searchValue . '%')
                ->orWhere('order', 'LIKE', '%' . $searchValue . '%')
                ->latest()
                ->paginate($this->paginate)
                ->appends(['tim-kiem' => $searchValue]);
        } else {
            // Get page for paginate with Redis
            $page = $request->input('page', 1);
            // Cache with Redis
            $addresses = \Cache::remember('vmcgc_admin_address_page_' . $page, $this->redisTime, function () {
                return $this->address->latest()->paginate($this->paginate);
            });
        }
        return view('admin.components.address.index')->with(compact('addresses'));
    }

    public function search(Request $request)
    {
        $this->setAdminPage('contact', 'address');
        return redirect()->route('admin.address.index', ['tim-kiem' => $request->input('search')]);
    }

    public function create()
    {
        $this->setAdminPage('contact', 'address');
        return view('admin.components.address.add');
    }

    public function store(Request $request)
    {
        $this->setAdminPage('contact', 'address');
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

        $this->address->create([
            'content' => $data['content'],
            'status' => $status,
            'order' => $order
        ]);

        Session::flash('address_add_success_message', 'Thêm Địa chỉ thành công');
        // Clear all key in Redis
        $this->removeAllKey();
        return redirect()->back();
    }

    public function updateStatus(Request $request)
    {
        $this->setAdminPage('contact', 'address');
        $data = $request->all();

        $status = -1;

        if ($data['status'] == '1') {
            $status = 0;
        } else {
            $status = 1;
        }

        $this->address->find($data['id'])->update([
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
        $this->setAdminPage('contact', 'address');
        $address = $this->address->find($id);
        return view('admin.components.address.edit')->with(compact('address'));
    }

    public function update($id, Request $request)
    {
        $this->setAdminPage('contact', 'address');
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

        $this->address->find($id)->update([
            'content' => $data['content'],
            'status' => $status,
            'order' => $order
        ]);

        Session::flash('address_edit_success_message', 'Sửa Địa chỉ thành công');
        // Clear all key in Redis
        $this->removeAllKey();
        return redirect()->back();
    }

    public function delete($id)
    {
        $this->setAdminPage('contact', 'address');
        // Find and delete
        $address = $this->address->find($id);
        $address->delete();

        // Push Notification and Return
        Session::flash('address_delete_success_message', 'Xoá Địa chỉ thành công');
        // Clear all key in Redis
        $this->removeAllKey();
        return redirect()->back();
    }
}
