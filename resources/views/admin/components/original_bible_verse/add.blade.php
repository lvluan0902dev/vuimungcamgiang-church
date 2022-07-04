@extends('admin.layouts.admin')

@section('title')
    Câu gốc Kinh Thánh
@endsection

@section('css')

@endsection

@section('js')

@endsection

@section('content')
    @include('admin.partials.page-breadcrumb', ['key' => 'Câu gốc Kinh Thánh', 'name' => 'Add'])

    <div class="row">
        <div class="col-12 mx-auto">
            <form action="{{ route('admin.original-bible-verse.store') }}" method="post" enctype="multipart/form-data">
                @csrf
                <div class="card">
                    <!-- Validate Start -->
                @include('admin.partials.validate', ['name' => 'title'])
                @include('admin.partials.validate', ['name' => 'content'])
                @include('admin.partials.validate', ['name' => 'image'])
                <!-- Validate End -->

                    <!-- Notification Start -->
                @include('admin.partials.flash-message', ['name_success' => 'original_bible_verse_add_success_message', 'name_error' => 'original_bible_verse_add_error_message'])
                <!-- Notification End -->
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label">Tiêu đề <span class="required-input">*</span></label>
                            <input type="text" class="form-control" name="title" value="{{ old('title') }}">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Nội dung <span class="required-input">*</span></label>
                            <textarea class="form-control tinycme-editor" rows="10"
                                      name="content">{!! old('content') !!}</textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Hình ảnh <span class="required-input">*</span> <span
                                    class="form-text">(Kích thước đề nghị: 330x200px)</span></label>
                            <input class="form-control" type="file" name="image" accept="image/*">
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="status">Trạng thái</label>
                            <label class="switch">
                                <input type="checkbox" id="status"
                                       name="status" {{ old('status') == 'on' ? 'checked' : '' }}>
                                <span class="slider round"></span>
                            </label>
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-outline-primary px-3">Xác nhận</button>
                        <a href="{{ route('admin.original-bible-verse.index') }}" class="btn btn-outline-secondary px-3">Trở lại</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <!--end row-->
@endsection
