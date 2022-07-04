@extends('admin.layouts.admin')

@section('title')
    Người giữ chức vụ
@endsection

@section('css')

@endsection

@section('js')

@endsection

@section('content')
    @include('admin.partials.page-breadcrumb', ['key' => 'Người giữ chức vụ', 'name' => 'Edit'])

    <div class="row">
        <div class="col-12 mx-auto">
            <form action="{{ route('admin.position.update', ['id' => $position->id]) }}" method="post" enctype="multipart/form-data">
                @csrf
                <div class="card">
                    <!-- Validate Start -->
                @include('admin.partials.validate', ['name' => 'name'])
                @include('admin.partials.validate', ['name' => 'type'])
                @include('admin.partials.validate', ['name' => 'content'])
                @include('admin.partials.validate', ['name' => 'image'])
                <!-- Validate End -->

                    <!-- Notification Start -->
                @include('admin.partials.flash-message', ['name_success' => 'position_edit_success_message', 'name_error' => 'position_edit_error_message'])
                <!-- Notification End -->
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label">Tên <span class="required-input">*</span></label>
                            <input type="text" class="form-control" name="name" value="{{ $position->name }}">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Chức vụ <span class="required-input">*</span></label>
                            <input type="text" class="form-control" name="type" value="{{ $position->type }}">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Nội dung <span class="required-input">*</span></label>
                            <textarea class="form-control" rows="3"
                                      name="content">{!! $position->content !!}</textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Hình ảnh <span
                                    class="form-text">(Kích thước đề nghị: 131x131px)</span></label>
                            <input class="form-control" type="file" name="image" accept="image/*">
                            @if (isset($position->image_path) && file_exists($position->image_path))
                                <img class="mt-3" style="width: 131px;" src="{{ asset($position->image_path) }}"
                                     alt="{{ $position->image_name }}">
                                <input type="hidden" name="current_image_name" value="{{ $position->image_name }}">
                                <input type="hidden" name="current_image_path" value="{{ $position->image_path }}">
                            @endif
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Thứ tự <span class="form-text">(Mặc định: 0)</span></label>
                            <input type="number" class="form-control" name="order" value="{{ $position->order }}">
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="status">Trạng thái</label>
                            <label class="switch">
                                <input type="checkbox" id="status"
                                       name="status" {{ $position->status == 1 ? 'checked' : '' }}>
                                <span class="slider round"></span>
                            </label>
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-outline-primary px-3">Xác nhận</button>
                        <a href="{{ route('admin.position.index') }}" class="btn btn-outline-secondary px-3">Trở lại</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <!--end row-->
@endsection
