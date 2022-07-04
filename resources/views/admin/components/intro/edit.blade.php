@extends('admin.layouts.admin')

@section('title')
    Đoạn giới thiệu
@endsection

@section('css')

@endsection

@section('js')

@endsection

@section('content')
    @include('admin.partials.page-breadcrumb', ['key' => 'Đoạn giới thiệu', 'name' => 'Edit'])

    <div class="row">
        <div class="col-12 mx-auto">
            <form action="{{ route('admin.intro.update', ['id' => $intro->id]) }}" method="post">
                @csrf
                <div class="card">
                    <!-- Validate Start -->
                @include('admin.partials.validate', ['name' => 'content'])
                <!-- Validate End -->

                    <!-- Notification Start -->
                @include('admin.partials.flash-message', ['name_success' => 'intro_edit_success_message', 'name_error' => 'intro_edit_error_message'])
                <!-- Notification End -->
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label">Nội dung <span class="required-input">*</span></label>
                            <textarea class="form-control tinycme-editor" rows="10"
                                      name="content">{!! $intro->content !!}</textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Thứ tự <span class="form-text">(Mặc định: 0)</span></label>
                            <input type="number" class="form-control" name="order" value="{{ $intro->order }}">
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="status">Trạng thái</label>
                            <label class="switch">
                                <input type="checkbox" id="status"
                                       name="status" {{ $intro->status == 1 ? 'checked' : '' }}>
                                <span class="slider round"></span>
                            </label>
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-outline-primary px-3">Xác nhận</button>
                        <a href="{{ route('admin.intro.index') }}" class="btn btn-outline-secondary px-3">Trở lại</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <!--end row-->
@endsection
