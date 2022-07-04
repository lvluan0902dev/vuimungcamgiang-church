<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Model\OriginalBibleVerse;
use App\Traits\ActivePageTrait;
use App\Traits\CheckStatusTrait;
use App\Traits\RedisTrait;
use App\Traits\UploadImageTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;

class OriginalBibleVerseController extends Controller
{
    use UploadImageTrait;
    use CheckStatusTrait;
    use RedisTrait;
    use ActivePageTrait;

    private $originalBibleVerse;
    private $paginate;
    private $redisTime;

    public function __construct(OriginalBibleVerse $originalBibleVerse)
    {
        $this->originalBibleVerse = $originalBibleVerse;
        $this->paginate = config('app.paginate');
        $this->redisTime = config('app.redis_time');
    }

    public function index(Request $request)
    {
        $this->setAdminPage('posts', 'original_bible_verse');
        if ($request->has('tim-kiem') && !empty($request->get('tim-kiem')) && $request->get('tim-kiem') != '' || $request->get('tim-kiem') == '0') {
            $searchValue = $request->get('tim-kiem');
            $originalBibleVerses = $this->originalBibleVerse
                ->with(['admin_user' => function($query) {
                    $query->select('id', 'name');
                }])
                ->where('title', 'LIKE', '%' . $searchValue . '%')
                ->orWhere('content', 'LIKE', '%' . $searchValue . '%')
                ->orWhere('created_at', 'LIKE', '%' . $searchValue . '%')
                ->orWhere('number_of_views', 'LIKE', '%' . $searchValue . '%')
                ->latest()
                ->paginate($this->paginate)
                ->appends(['tim-kiem' => $searchValue]);
        } else {
            // Get page for paginate with Redis
            $page = $request->input('page', 1);
            // Cache with Redis
            $originalBibleVerses = \Cache::remember('vmcgc_admin_original_bible_verse_page_' . $page, $this->redisTime, function () {
                return $this->originalBibleVerse
                    ->with(['admin_user' => function($query) {
                        $query->select('id', 'name');
                    }])
                    ->latest()
                    ->paginate($this->paginate);
            });
        }
        return view('admin.components.original_bible_verse.index')->with(compact('originalBibleVerses'));
    }

    public function search(Request $request)
    {
        $this->setAdminPage('posts', 'original_bible_verse');
        return redirect()->route('admin.original-bible-verse.index', ['tim-kiem' => $request->input('search')]);
    }

    public function create()
    {
        $this->setAdminPage('posts', 'original_bible_verse');
        return view('admin.components.original_bible_verse.add');
    }

    public function store(Request $request)
    {
        $this->setAdminPage('posts', 'original_bible_verse');
        $rules = [
            'title' => 'required',
            'content' => 'required',
            'image' => 'required|image',
        ];

        $customMessages = [
            'title.required' => 'Vui lòng nhập Tiêu đề',
            'content.required' => 'Vui lòng nhập Nội dung',
            'image.required' => 'Vui lòng chọn Hình ảnh',
            'image.image' => 'Hình ảnh không hợp lệ',
        ];

        $this->validate($request, $rules, $customMessages);

        $data = $request->all();

        $uploadSingleImage = $this->uploadSingleImage($request, 'image', 'Vui Mừng Cẩm Giàng câu gốc Kinh Thánh ' . $data['title'], 'original_bible_verse', 330, 200);
        if ($uploadSingleImage == false) {
            Session::flash('original_bible_verse_add_error_message', 'Thêm Câu gốc Kinh Thánh không thành công');
            return redirect()->back()->withInput();
        }

        $status = $this->checkStatus(isset($data['status']) ? $data['status'] : null);

        $this->originalBibleVerse->create([
            'title' => $data['title'],
            'url' => Str::slug($data['title']) . '-' . Str::random(10),
            'content' => $data['content'],
            'image_name' => $uploadSingleImage['image_name'],
            'image_path' => $uploadSingleImage['image_path'],
            'admin_user_id' => Auth::guard('admin')->id(),
            'number_of_views' => 0,
            'status' => $status,
        ]);

        Session::flash('original_bible_verse_add_success_message', 'Thêm Câu gốc Kinh Thánh thành công');
        // Clear all key in Redis
        $this->removeAllKey();
        return redirect()->back();
    }

    public function updateStatus(Request $request)
    {
        $this->setAdminPage('posts', 'original_bible_verse');
        $data = $request->all();

        $status = -1;

        if ($data['status'] == '1') {
            $status = 0;
        } else {
            $status = 1;
        }

        $this->originalBibleVerse->find($data['id'])->update([
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
        $this->setAdminPage('posts', 'original_bible_verse');
        $originalBibleVerse = $this->originalBibleVerse->find($id);
        return view('admin.components.original_bible_verse.edit')->with(compact('originalBibleVerse'));
    }

    public function update($id, Request $request)
    {
        $this->setAdminPage('posts', 'original_bible_verse');
        $rules = [
            'title' => 'required',
            'content' => 'required',
            'image' => 'image',
        ];

        $customMessages = [
            'title.required' => 'Vui lòng nhập Tiêu đề',
            'content.required' => 'Vui lòng nhập Nội dung',
            'image.image' => 'Hình ảnh không hợp lệ',
        ];

        $this->validate($request, $rules, $customMessages);

        $data = $request->all();

        $uploadSingleImage = [];
        if (isset($data['image']) && !empty($data['image'])) {
            $uploadSingleImage = $this->uploadSingleImage($request, 'image', 'Vui Mừng Cẩm Giàng câu gốc Kinh Thánh ' . $data['title'], 'original_bible_verse', 330, 200);
            if ($uploadSingleImage == false) {
                Session::flash('original_bible_verse_edit_error_message', 'Sửa Câu gốc Kinh Thánh không thành công');
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


        $status = $this->checkStatus(isset($data['status']) ? $data['status'] : null);

        $this->originalBibleVerse->find($id)->update([
            'title' => $data['title'],
            'url' => Str::slug($data['title']) . '-' . Str::random(10),
            'content' => $data['content'],
            'image_name' => $uploadSingleImage['image_name'],
            'image_path' => $uploadSingleImage['image_path'],
            'status' => $status,
        ]);

        Session::flash('original_bible_verse_edit_success_message', 'Sửa Câu gốc Kinh Thánh thành công');
        // Clear all key in Redis
        $this->removeAllKey();
        return redirect()->back();
    }

    public function delete($id)
    {
        $this->setAdminPage('posts', 'original_bible_verse');
        // Find and delete
        $originalBibleVerse = $this->originalBibleVerse->find($id);
        // Delete image
        if (file_exists($originalBibleVerse->image_path)) {
            unlink($originalBibleVerse->image_path);
        }
        $originalBibleVerse->delete();

        // Push Notification and Return
        Session::flash('original_bible_verse_delete_success_message', 'Xoá Câu gốc Kinh Thánh thành công');
        // Clear all key in Redis
        $this->removeAllKey();
        return redirect()->back();
    }
}
