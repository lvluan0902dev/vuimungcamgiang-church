@extends('admin.layouts.admin')

@section('title')
    Tin tức
@endsection

@section('css')

@endsection

@section('js')

@endsection

@section('content')
    @include('admin.partials.page-breadcrumb', ['key' => 'Tin tức', 'name' => 'Edit'])

    <div class="row">
        <div class="col-12 mx-auto">
            <form action="{{ route('admin.news.update', ['id' => $news->id]) }}" method="post" enctype="multipart/form-data">
                @csrf
                <div class="card">
                    <!-- Validate Start -->
                @include('admin.partials.validate', ['name' => 'title'])
                @include('admin.partials.validate', ['name' => 'content'])
                @include('admin.partials.validate', ['name' => 'image'])
                <!-- Validate End -->

                    <!-- Notification Start -->
                @include('admin.partials.flash-message', ['name_success' => 'news_edit_success_message', 'name_error' => 'news_edit_error_message'])
                <!-- Notification End -->
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label">Tiêu đề <span class="required-input">*</span></label>
                            <input type="text" class="form-control" name="title" value="{{ $news->title }}">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Nội dung <span class="required-input">*</span></label>
                            <textarea class="form-control tinycme-editor" rows="10"
                                      name="content">{!! $news->content !!}</textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Hình ảnh <span
                                    class="form-text">(Kích thước đề nghị: 330x200px)</span></label>
                            <input class="form-control" type="file" name="image" accept="image/*">
                            @if (isset($news->image_path) && file_exists($news->image_path))
                                <img class="mt-3" style="width: 150px;" src="{{ asset($news->image_path) }}"
                                     alt="{{ $news->image_name }}">
                                <input type="hidden" name="current_image_name" value="{{ $news->image_name }}">
                                <input type="hidden" name="current_image_path" value="{{ $news->image_path }}">
                            @endif
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="status">Trạng thái</label>
                            <label class="switch">
                                <input type="checkbox" id="status"
                                       name="status" {{ $news->status == 1 ? 'checked' : '' }}>
                                <span class="slider round"></span>
                            </label>
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-outline-primary px-3">Xác nhận</button>
                        <a href="{{ route('admin.news.index') }}" class="btn btn-outline-secondary px-3">Trở lại</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <!--end row-->
@endsection
