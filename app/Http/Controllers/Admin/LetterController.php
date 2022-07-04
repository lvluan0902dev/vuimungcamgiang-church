<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Model\Letter;
use App\Traits\ActivePageTrait;
use App\Traits\RedisTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class LetterController extends Controller
{
    use RedisTrait;
    use ActivePageTrait;

    private $letter;
    private $paginate;
    private $redisTime;

    public function __construct(Letter $letter)
    {
        $this->letter = $letter;
        $this->paginate = config('app.paginate');
        $this->redisTime = config('app.redis_time');
    }

    public function index(Request $request)
    {
        $this->setAdminPage('contact', 'letter');
        if ($request->has('tim-kiem') && !empty($request->get('tim-kiem')) && $request->get('tim-kiem') != '' || $request->get('tim-kiem') == '0') {
            $searchValue = $request->get('tim-kiem');
            $letters = $this->letter
                ->where('content', 'LIKE', '%' . $searchValue . '%')
                ->orWhere('name', 'LIKE', '%' . $searchValue . '%')
                ->orWhere('email', 'LIKE', '%' . $searchValue . '%')
                ->orWhere('phone_number', 'LIKE', '%' . $searchValue . '%')
                ->latest()->paginate($this->paginate)
                ->appends(['tim-kiem' => $searchValue]);
        } else {
            // Get page for paginate with Redis
            $page = $request->input('page', 1);
            // Cache with Redis
            $letters = \Cache::remember('vmcgc_admin_letter_page_' . $page, $this->redisTime, function () {
                return $this->letter->latest()->paginate($this->paginate);
            });
        }
        return view('admin.components.letter.index')->with(compact('letters'));
    }

    public function search(Request $request)
    {
        $this->setAdminPage('contact', 'letter');
        return redirect()->route('admin.letter.index', ['tim-kiem' => $request->input('search')]);
    }

    public function delete($id)
    {
        $this->setAdminPage('contact', 'letter');
        // Find and delete
        $letter = $this->letter->find($id);
        $letter->delete();

        // Push Notification and Return
        Session::flash('letter_delete_success_message', 'Xoá Thư thành công');
        // Clear all key in Redis
        $this->removeAllKey();
        return redirect()->back();
    }
}
