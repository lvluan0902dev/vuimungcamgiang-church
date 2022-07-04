@extends('admin.layouts.admin')

@section('title')
    Thành viên
@endsection

@section('css')

@endsection

@section('js')

@endsection

@section('content')
    @include('admin.partials.page-breadcrumb', ['key' => 'Thành viên', 'name' => 'Add'])

    <div class="row">
        <div class="col-12 mx-auto">
            <form action="{{ route('admin.member.store') }}" method="post" enctype="multipart/form-data">
                @csrf
                <div class="card">
                    <!-- Validate Start -->
                @include('admin.partials.validate', ['name' => 'name'])
                @include('admin.partials.validate', ['name' => 'type'])
                @include('admin.partials.validate', ['name' => 'image'])
                <!-- Validate End -->

                    <!-- Notification Start -->
                @include('admin.partials.flash-message', ['name_success' => 'member_add_success_message', 'name_error' => 'member_add_error_message'])
                <!-- Notification End -->
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label">Tên <span class="required-input">*</span></label>
                            <input type="text" class="form-control" name="name" value="{{ old('name') }}">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Loại thành viên <span class="required-input">*</span></label>
                            <input type="text" class="form-control" name="type" value="{{ old('type') }}">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Hình ảnh <span class="required-input">*</span> <span
                                    class="form-text">(Kích thước đề nghị: 131x131px)</span></label>
                            <input class="form-control" type="file" name="image" accept="image/*">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Thứ tự <span class="form-text">(Mặc định: 0)</span></label>
                            <input type="number" class="form-control" name="order" value="{{ old('order') }}">
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
                        <a href="{{ route('admin.member.index') }}" class="btn btn-outline-secondary px-3">Trở lại</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <!--end row-->
@endsection
