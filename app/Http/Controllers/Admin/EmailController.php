<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Model\Email;
use App\Traits\ActivePageTrait;
use App\Traits\CheckOrderTrait;
use App\Traits\CheckStatusTrait;
use App\Traits\RedisTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class EmailController extends Controller
{
    use CheckOrderTrait;
    use CheckStatusTrait;
    use RedisTrait;
    use ActivePageTrait;

    private $email;
    private $paginate;
    private $redisTime;

    public function __construct(Email $email)
    {
        $this->email = $email;
        $this->paginate = config('app.paginate');
        $this->redisTime = config('app.redis_time');
    }

    public function index(Request $request)
    {
        $this->setAdminPage('contact', 'email');
        if ($request->has('tim-kiem') && !empty($request->get('tim-kiem')) && $request->get('tim-kiem') != '' || $request->get('tim-kiem') == '0') {
            $searchValue = $request->get('tim-kiem');
            $emails = $this->email
                ->where('content', 'LIKE', '%' . $searchValue . '%')
                ->orWhere('order', 'LIKE', '%' . $searchValue . '%')
                ->latest()->paginate($this->paginate)
                ->appends(['tim-kiem' => $searchValue]);
        } else {
            // Get page for paginate with Redis
            $page = $request->input('page', 1);
            // Cache with Redis
            $emails = \Cache::remember('vmcgc_admin_email_page_' . $page, $this->redisTime, function () {
                return $this->email->latest()->paginate($this->paginate);
            });
        }
        return view('admin.components.email.index')->with(compact('emails'));
    }

    public function search(Request $request)
    {
        $this->setAdminPage('contact', 'email');
        return redirect()->route('admin.email.index', ['tim-kiem' => $request->input('search')]);
    }

    public function create()
    {
        $this->setAdminPage('contact', 'email');
        return view('admin.components.email.add');
    }

    public function store(Request $request)
    {
        $this->setAdminPage('contact', 'email');
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

        $this->email->create([
            'content' => $data['content'],
            'status' => $status,
            'order' => $order
        ]);

        Session::flash('email_add_success_message', 'Thêm Email thành công');
        // Clear all key in Redis
        $this->removeAllKey();
        return redirect()->back();
    }

    public function updateStatus(Request $request)
    {
        $this->setAdminPage('contact', 'email');
        $data = $request->all();

        $status = -1;

        if ($data['status'] == '1') {
            $status = 0;
        } else {
            $status = 1;
        }

        $this->email->find($data['id'])->update([
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
        $this->setAdminPage('contact', 'email');
        $email = $this->email->find($id);
        return view('admin.components.email.edit')->with(compact('email'));
    }

    public function update($id, Request $request)
    {
        $this->setAdminPage('contact', 'email');
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

        $this->email->find($id)->update([
            'content' => $data['content'],
            'status' => $status,
            'order' => $order
        ]);

        Session::flash('email_edit_success_message', 'Sửa Email thành công');
        // Clear all key in Redis
        $this->removeAllKey();
        return redirect()->back();
    }

    public function delete($id)
    {
        $this->setAdminPage('contact', 'email');
        // Find and delete
        $email = $this->email->find($id);
        $email->delete();

        // Push Notification and Return
        Session::flash('email_delete_success_message', 'Xoá Email thành công');
        // Clear all key in Redis
        $this->removeAllKey();
        return redirect()->back();
    }
}
