<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Model\GoogleMaps;
use App\Traits\ActivePageTrait;
use App\Traits\CheckOrderTrait;
use App\Traits\CheckStatusTrait;
use App\Traits\RedisTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class GoogleMapsController extends Controller
{
    use CheckStatusTrait;
    use CheckOrderTrait;
    use RedisTrait;
    use ActivePageTrait;

    private $googleMaps;
    private $paginate;
    private $redisTime;

    public function __construct(GoogleMaps $googleMaps)
    {
        $this->googleMaps = $googleMaps;
        $this->paginate = config('app.paginate');
        $this->redisTime = config('app.redis_time');
    }

    public function index(Request $request)
    {
        $this->setAdminPage('content', 'google_maps');
        if ($request->has('tim-kiem') && !empty($request->get('tim-kiem')) && $request->get('tim-kiem') != '' || $request->get('tim-kiem') == '0') {
            $searchValue = $request->get('tim-kiem');
            $listGoogleMaps = $this->googleMaps
                ->where('order', 'LIKE', '%' . $searchValue . '%')
                ->latest()
                ->paginate($this->paginate)
                ->appends(['tim-kiem' => $searchValue]);
        } else {
            // Get page for paginate with Redis
            $page = $request->input('page', 1);
            // Cache with Redis
            $listGoogleMaps = \Cache::remember('vmcgc_admin_google_maps_page_' . $page, $this->redisTime, function () {
                return $this->googleMaps->latest()->paginate($this->paginate);
            });
        }
        return view('admin.components.google_maps.index')->with(compact('listGoogleMaps'));
    }

    public function search(Request $request)
    {
        $this->setAdminPage('content', 'google_maps');
        return redirect()->route('admin.google-maps.index', ['tim-kiem' => $request->input('search')]);
    }

    public function create()
    {
        $this->setAdminPage('content', 'google_maps');
        return view('admin.components.google_maps.add');
    }

    public function store(Request $request)
    {
        $this->setAdminPage('content', 'google_maps');
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

        $this->googleMaps->create([
            'content' => $data['content'],
            'status' => $status,
            'order' => $order
        ]);

        Session::flash('google_maps_add_success_message', 'Thêm Google Maps thành công');
        // Clear all key in Redis
        $this->removeAllKey();
        return redirect()->back();
    }

    public function updateStatus(Request $request)
    {
        $this->setAdminPage('content', 'google_maps');
        $data = $request->all();

        $status = -1;

        if ($data['status'] == '1') {
            $status = 0;
        } else {
            $status = 1;
        }

        $this->googleMaps->find($data['id'])->update([
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
        $this->setAdminPage('content', 'google_maps');
        $googleMaps = $this->googleMaps->find($id);
        return view('admin.components.google_maps.edit')->with(compact('googleMaps'));
    }

    public function update($id, Request $request)
    {
        $this->setAdminPage('content', 'google_maps');
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

        $this->googleMaps->find($id)->update([
            'content' => $data['content'],
            'status' => $status,
            'order' => $order
        ]);

        Session::flash('google_maps_edit_success_message', 'Sửa Google Maps thành công');
        // Clear all key in Redis
        $this->removeAllKey();
        return redirect()->back();
    }

    public function delete($id)
    {
        $this->setAdminPage('content', 'google_maps');
        // Find and delete
        $googleMaps = $this->googleMaps->find($id);
        $googleMaps->delete();

        // Push Notification and Return
        Session::flash('google_maps_delete_success_message', 'Xoá Google Maps thành công');
        // Clear all key in Redis
        $this->removeAllKey();
        return redirect()->back();
    }
}
