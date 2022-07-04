<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Model\SocialNetwork;
use App\Traits\ActivePageTrait;
use App\Traits\CheckOrderTrait;
use App\Traits\CheckStatusTrait;
use App\Traits\RedisTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class SocialNetworkController extends Controller
{
    use CheckOrderTrait;
    use CheckStatusTrait;
    use RedisTrait;
    use ActivePageTrait;

    private $socialNetwork;
    private $paginate;
    private $redisTime;

    public function __construct(SocialNetwork $socialNetwork)
    {
        $this->socialNetwork = $socialNetwork;
        $this->paginate = config('app.paginate');
        $this->redisTime = config('app.redis_time');
    }

    public function index(Request $request)
    {
        $this->setAdminPage('contact', 'social_network');
        if ($request->has('tim-kiem') && !empty($request->get('tim-kiem')) && $request->get('tim-kiem') != '' || $request->get('tim-kiem') == '0') {
            $searchValue = $request->get('tim-kiem');
            $socialNetworks = $this->socialNetwork
                ->where('order', 'LIKE', '%' . $searchValue . '%')
                ->latest()
                ->paginate($this->paginate)
                ->appends(['tim-kiem' => $searchValue]);
        } else {
            // Get page for paginate with Redis
            $page = $request->input('page', 1);
            // Cache with Redis
            $socialNetworks = \Cache::remember('vmcgc_admin_social_network_page_' . $page, $this->redisTime, function () {
                return $this->socialNetwork->latest()->paginate($this->paginate);
            });
        }
        return view('admin.components.social_network.index')->with(compact('socialNetworks'));
    }

    public function search(Request $request)
    {
        $this->setAdminPage('contact', 'social_network');
        return redirect()->route('admin.banner.index', ['tim-kiem' => $request->input('search')]);
    }

    public function create()
    {
        $this->setAdminPage('contact', 'social_network');
        return view('admin.components.social_network.add');
    }

    public function store(Request $request)
    {
        $this->setAdminPage('contact', 'social_network');
        $rules = [
            'link' => 'required',
            'icon' => 'required',
        ];

        $customMessages = [
            'link.required' => 'Vui lòng nhập Link',
            'icon.required' => 'Vui lòng nhập Icon',
        ];

        $this->validate($request, $rules, $customMessages);

        $data = $request->all();

        $status = $this->checkStatus(isset($data['status']) ? $data['status'] : null);

        $order = $this->checkOrder(isset($data['order']) ? $data['order'] : null);

        $this->socialNetwork->create([
            'icon' => $data['icon'],
            'link' => $data['link'],
            'status' => $status,
            'order' => $order
        ]);

        Session::flash('social_network_add_success_message', 'Thêm Mạng xã hội thành công');
        // Clear all key in Redis
        $this->removeAllKey();
        return redirect()->back();
    }

    public function updateStatus(Request $request)
    {
        $this->setAdminPage('contact', 'social_network');
        $data = $request->all();

        $status = -1;

        if ($data['status'] == '1') {
            $status = 0;
        } else {
            $status = 1;
        }

        $this->socialNetwork->find($data['id'])->update([
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
        $this->setAdminPage('contact', 'social_network');
        $socialNetwork = $this->socialNetwork->find($id);
        return view('admin.components.social_network.edit')->with(compact('socialNetwork'));
    }

    public function update($id, Request $request)
    {
        $this->setAdminPage('contact', 'social_network');
        $rules = [
            'link' => 'required',
            'icon' => 'required',
        ];

        $customMessages = [
            'link.required' => 'Vui lòng nhập Link',
            'icon.required' => 'Vui lòng nhập Icon',
        ];

        $this->validate($request, $rules, $customMessages);

        $data = $request->all();

        $status = $this->checkStatus(isset($data['status']) ? $data['status'] : null);

        $order = $this->checkOrder(isset($data['order']) ? $data['order'] : null);

        $this->socialNetwork->find($id)->update([
            'icon' => $data['icon'],
            'link' => $data['link'],
            'status' => $status,
            'order' => $order
        ]);

        Session::flash('social_network_edit_success_message', 'Sửa Mạng xã hội thành công');
        // Clear all key in Redis
        $this->removeAllKey();
        return redirect()->back();
    }

    public function delete($id)
    {
        $this->setAdminPage('contact', 'social_network');
        // Find and delete
        $socialNetwork = $this->socialNetwork->find($id);
        $socialNetwork->delete();

        // Push Notification and Return
        Session::flash('social_network_delete_success_message', 'Xoá Mạng xã hội thành công');
        // Clear all key in Redis
        $this->removeAllKey();
        return redirect()->back();
    }
}
