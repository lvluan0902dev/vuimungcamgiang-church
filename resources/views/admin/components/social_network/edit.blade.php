@extends('admin.layouts.admin')

@section('title')
    Mạng xã hội
@endsection

@section('css')

@endsection

@section('js')

@endsection

@section('content')
    @include('admin.partials.page-breadcrumb', ['key' => 'Mạng xã hội', 'name' => 'Edit'])

    <div class="row">
        <div class="col-12 mx-auto">
            <form action="{{ route('admin.social-network.update', ['id' => $socialNetwork->id]) }}" method="post">
                @csrf
                <div class="card">
                    <!-- Validate Start -->
                @include('admin.partials.validate', ['name' => 'icon'])
                @include('admin.partials.validate', ['name' => 'link'])
                <!-- Validate End -->

                    <!-- Notification Start -->
                @include('admin.partials.flash-message', ['name_success' => 'social_network_edit_success_message', 'name_error' => 'social_network_edit_error_message'])
                <!-- Notification End -->
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label">Icon <span class="required-input">*</span> <span class="form-text">(Lấy icon <a href="https://fontawesome.com/v6/search" target="_blank">tại đây</a>)</span></label>
                            <input type="text" class="form-control" name="icon" value="{{ $socialNetwork->icon }}">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Link <span class="required-input">*</span></label>
                            <textarea class="form-control" rows="3"
                                      name="link">{{ $socialNetwork->link }}</textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Thứ tự <span class="form-text">(Mặc định: 0)</span></label>
                            <input type="number" class="form-control" name="order" value="{{ $socialNetwork->order }}">
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="status">Trạng thái</label>
                            <label class="switch">
                                <input type="checkbox" id="status"
                                       name="status" {{ $socialNetwork->status == 1 ? 'checked' : '' }}>
                                <span class="slider round"></span>
                            </label>
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-outline-primary px-3">Xác nhận</button>
                        <a href="{{ route('admin.social-network.index') }}" class="btn btn-outline-secondary px-3">Trở lại</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <!--end row-->
@endsection
