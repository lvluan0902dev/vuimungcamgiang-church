<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Model\WeeklyPowerPoint;
use App\Traits\ActivePageTrait;
use App\Traits\CheckOrderTrait;
use App\Traits\CheckStatusTrait;
use App\Traits\RedisTrait;
use App\Traits\UploadFileTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class WeeklyPowerPointController extends Controller
{
    use UploadFileTrait;
    use CheckStatusTrait;
    use CheckOrderTrait;
    use RedisTrait;
    use ActivePageTrait;

    private $weeklyPowerPoint;
    private $paginate;
    private $redisTime;

    public function __construct(WeeklyPowerPoint $weeklyPowerPoint)
    {
        $this->weeklyPowerPoint = $weeklyPowerPoint;
        $this->paginate = config('app.paginate');
        $this->redisTime = config('app.redis_time');
    }

    public function index(Request $request)
    {
        $this->setAdminPage('powerpoint', 'weekly_power_point');
        if ($request->has('tim-kiem') && !empty($request->get('tim-kiem')) && $request->get('tim-kiem') != '' || $request->get('tim-kiem') == '0') {
            $searchValue = $request->get('tim-kiem');
            $weeklyPowerPoints = $this->weeklyPowerPoint
                ->where('name', 'LIKE', '%' . $searchValue . '%')
                ->orWhere('number_of_views', 'LIKE', '%' . $searchValue . '%')
                ->orWhere('number_of_downloads', 'LIKE', '%' . $searchValue . '%')
                ->orWhere('order', 'LIKE', '%' . $searchValue . '%')
                ->latest()
                ->paginate($this->paginate)
                ->appends(['tim-kiem' => $searchValue]);
        } else {
            // Get page for paginate with Redis
            $page = $request->input('page', 1);
            // Cache with Redis
            $weeklyPowerPoints = \Cache::remember('vmcgc_admin_weekly_power_point_page_' . $page, $this->redisTime, function () {
                return $this->weeklyPowerPoint->latest()->paginate($this->paginate);
            });
        }
        return view('admin.components.weekly_power_point.index')->with(compact('weeklyPowerPoints'));
    }

    public function search(Request $request)
    {
        $this->setAdminPage('powerpoint', 'weekly_power_point');
        return redirect()->route('admin.weekly-power-point.index', ['tim-kiem' => $request->input('search')]);
    }

    public function create()
    {
        $this->setAdminPage('powerpoint', 'weekly_power_point');
        return view('admin.components.weekly_power_point.add');
    }

    public function store(Request $request)
    {
        $this->setAdminPage('powerpoint', 'weekly_power_point');
        $rules = [
            'name' => 'required',
            'file' => 'required',
        ];

        $customMessages = [
            'name.required' => 'Vui lòng nhập Tên',
            'file.required' => 'Vui lòng chọn File',
        ];

        $this->validate($request, $rules, $customMessages);

        $uploadSingleFilePowerPoint = $this->uploadSingleFilePowerPoint($request, 'file', 'Vui Mừng Cẩm Giàng Power-Point ' . $request->get('name'), 'weekly_power_point');
        if ($uploadSingleFilePowerPoint == false) {
            Session::flash('weekly_power_point_add_error_message', 'Thêm PowerPoint Hằng tuần không thành công');
            return redirect()->back()->withInput();
        }

        $data = $request->all();

        $status = $this->checkStatus(isset($data['status']) ? $data['status'] : null);

        $order = $this->checkOrder(isset($data['order']) ? $data['order'] : null);

        $this->weeklyPowerPoint->create([
            'name' => $data['name'],
            'file_name' => $uploadSingleFilePowerPoint['file_name'],
            'file_path' => $uploadSingleFilePowerPoint['file_path'],
            'number_of_views' => 0,
            'number_of_downloads' => 0,
            'status' => $status,
            'order' => $order
        ]);

        Session::flash('weekly_power_point_add_success_message', 'Thêm PowerPoint Hằng tuần thành công');
        // Clear all key in Redis
        $this->removeAllKey();
        return redirect()->back();
    }

    public function updateStatus(Request $request)
    {
        $this->setAdminPage('powerpoint', 'weekly_power_point');
        $data = $request->all();

        $status = -1;

        if ($data['status'] == '1') {
            $status = 0;
        } else {
            $status = 1;
        }

        $this->weeklyPowerPoint->find($data['id'])->update([
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
        $this->setAdminPage('powerpoint', 'weekly_power_point');
        $weeklyPowerPoint = $this->weeklyPowerPoint->find($id);
        return view('admin.components.weekly_power_point.edit')->with(compact('weeklyPowerPoint'));
    }

    public function update($id, Request $request)
    {
        $this->setAdminPage('powerpoint', 'weekly_power_point');
        $rules = [
            'name' => 'required',
        ];

        $customMessages = [
            'name.required' => 'Vui lòng nhập Tên',
        ];

        $this->validate($request, $rules, $customMessages);

        $data = $request->all();
        $uploadSingleFilePowerPoint = [];
        if (isset($data['file']) && !empty($data['file'])) {
            $uploadSingleFilePowerPoint = $this->uploadSingleFilePowerPoint($request, 'file', 'Vui Mừng Cẩm Giàng Power-Point ' . $request->get('name'), 'weekly_power_point');
            if ($uploadSingleFilePowerPoint == false) {
                Session::flash('weekly_power_point_edit_error_message', 'Sửa PowerPoint Hằng tuần không thành công');
                return redirect()->back()->withInput();
            }

            // Delete old file
            if (isset($data['current_file_name']) && isset($data['current_file_path']) && file_exists($data['current_file_path'])) {
                unlink($data['current_file_path']);
            }
        } else {
            $uploadSingleFilePowerPoint['file_name'] = $data['current_file_name'];
            $uploadSingleFilePowerPoint['file_path'] = $data['current_file_path'];
        }


        $status = $this->checkStatus(isset($data['status']) ? $data['status'] : null);

        $order = $this->checkOrder(isset($data['order']) ? $data['order'] : null);

        $this->weeklyPowerPoint->find($id)->update([
            'name' => $data['name'],
            'file_name' => $uploadSingleFilePowerPoint['file_name'],
            'file_path' => $uploadSingleFilePowerPoint['file_path'],
            'status' => $status,
            'order' => $order
        ]);

        Session::flash('weekly_power_point_edit_success_message', 'Sửa PowerPoint Hằng tuần thành công');
        // Clear all key in Redis
        $this->removeAllKey();
        return redirect()->back();
    }

    public function delete($id)
    {
        $this->setAdminPage('powerpoint', 'weekly_power_point');
        // Find and delete
        $weeklyPowerPoint = $this->weeklyPowerPoint->find($id);
        // Delete file
        if (file_exists($weeklyPowerPoint->file_path)) {
            unlink($weeklyPowerPoint->file_path);
        }
        $weeklyPowerPoint->delete();

        // Push Notification and Return
        Session::flash('weekly_power_point_delete_success_message', 'Xoá PowerPoint Hằng tuần thành công');
        // Clear all key in Redis
        $this->removeAllKey();
        return redirect()->back();
    }
}
