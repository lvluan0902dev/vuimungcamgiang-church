<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Model\Intro;
use App\Traits\ActivePageTrait;
use App\Traits\CheckOrderTrait;
use App\Traits\CheckStatusTrait;
use App\Traits\RedisTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class IntroController extends Controller
{
    use CheckOrderTrait;
    use CheckStatusTrait;
    use RedisTrait;
    use ActivePageTrait;

    private $intro;
    private $paginate;
    private $redisTime;

    public function __construct(Intro $intro)
    {
        $this->intro = $intro;
        $this->paginate = config('app.paginate');
        $this->redisTime = config('app.redis_time');
    }

    public function index(Request $request)
    {
        $this->setAdminPage('about', 'intro');
        if ($request->has('tim-kiem') && !empty($request->get('tim-kiem')) && $request->get('tim-kiem') != '' || $request->get('tim-kiem') == '0') {
            $searchValue = $request->get('tim-kiem');
            $intros = $this->intro
                ->where('content', 'LIKE', '%' . $searchValue . '%')
                ->orWhere('order', 'LIKE', '%' . $searchValue . '%')
                ->latest()
                ->paginate($this->paginate)
                ->appends(['tim-kiem' => $searchValue]);
        } else {
            // Get page for paginate with Redis
            $page = $request->input('page', 1);
            // Cache with Redis
            $intros = \Cache::remember('vmcgc_admin_intro_page_' . $page, $this->redisTime, function () {
                return $this->intro->latest()->paginate($this->paginate);
            });
        }
        return view('admin.components.intro.index')->with(compact('intros'));
    }

    public function search(Request $request)
    {
        $this->setAdminPage('about', 'intro');
        return redirect()->route('admin.intro.index', ['tim-kiem' => $request->input('search')]);
    }

    public function create()
    {
        $this->setAdminPage('about', 'intro');
        return view('admin.components.intro.add');
    }

    public function store(Request $request)
    {
        $this->setAdminPage('about', 'intro');
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

        $this->intro->create([
            'content' => $data['content'],
            'status' => $status,
            'order' => $order
        ]);

        Session::flash('intro_add_success_message', 'Thêm Đoạn giới thiệu thành công');
        // Clear all key in Redis
        $this->removeAllKey();
        return redirect()->back();
    }

    public function updateStatus(Request $request)
    {
        $this->setAdminPage('about', 'intro');
        $data = $request->all();

        $status = -1;

        if ($data['status'] == '1') {
            $status = 0;
        } else {
            $status = 1;
        }

        $this->intro->find($data['id'])->update([
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
        $this->setAdminPage('about', 'intro');
        $intro = $this->intro->find($id);
        return view('admin.components.intro.edit')->with(compact('intro'));
    }

    public function update($id, Request $request)
    {
        $this->setAdminPage('about', 'intro');
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

        $this->intro->find($id)->update([
            'content' => $data['content'],
            'status' => $status,
            'order' => $order
        ]);

        Session::flash('intro_edit_success_message', 'Sửa Đoạn giới thiệu thành công');
        // Clear all key in Redis
        $this->removeAllKey();
        return redirect()->back();
    }

    public function delete($id)
    {
        $this->setAdminPage('about', 'intro');
        // Find and delete
        $intro = $this->intro->find($id);
        $intro->delete();

        // Push Notification and Return
        Session::flash('intro_delete_success_message', 'Xoá Đoạn giới thiệu thành công');
        // Clear all key in Redis
        $this->removeAllKey();
        return redirect()->back();
    }
}
