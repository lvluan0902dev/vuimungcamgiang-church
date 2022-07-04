@extends('admin.layouts.admin')

@section('title')
    Email
@endsection

@section('css')

@endsection

@section('js')

@endsection

@section('content')
    @include('admin.partials.page-breadcrumb', ['key' => 'Email', 'name' => 'Edit'])

    <div class="row">
        <div class="col-12 mx-auto">
            <form action="{{ route('admin.email.update', ['id' => $email->id]) }}" method="post">
                @csrf
                <div class="card">
                    <!-- Validate Start -->
                @include('admin.partials.validate', ['name' => 'content'])
                <!-- Validate End -->

                    <!-- Notification Start -->
                @include('admin.partials.flash-message', ['name_success' => 'email_edit_success_message', 'name_error' => 'email_edit_error_message'])
                <!-- Notification End -->
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label">Nội dung <span class="required-input">*</span></label>
                            <textarea class="form-control" rows="3"
                                      name="content">{{ $email->content }}</textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Thứ tự <span class="form-text">(Mặc định: 0)</span></label>
                            <input type="number" class="form-control" name="order" value="{{ $email->order }}">
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="status">Trạng thái</label>
                            <label class="switch">
                                <input type="checkbox" id="status"
                                       name="status" {{ $email->status == 1 ? 'checked' : '' }}>
                                <span class="slider round"></span>
                            </label>
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-outline-primary px-3">Xác nhận</button>
                        <a href="{{ route('admin.email.index') }}" class="btn btn-outline-secondary px-3">Trở lại</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <!--end row-->
@endsection