@extends('admin.layouts.admin')

@section('title')
    Trang cá nhân
@endsection

@section('css')
    <style>
        .profile-cover {
            background-image: linear-gradient(to bottom right, rgb(26 30 33 / 50%), rgb(0 0 0 / 50%)), url(/assets/admin/default/image/personal_page/personal_page_banner.jpg);
            background-size: cover;
            height: 24rem;
            background-position: center;
            margin: -4rem -1.5rem -5.5rem -1.5rem;
            padding: 1.5rem 1.5rem 6.5rem 1.5rem;
        }
    </style>
@endsection

@section('js')

@endsection

@section('content')
    @include('admin.partials.page-breadcrumb', ['key' => 'Trang cá nhân', 'name' => 'Index'])

    <div class="profile-cover bg-dark"></div>

    <div class="row">
        <div class="col-12 col-lg-8">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <h5 class="mb-0">Tài khoản</h5>
                    <hr>
                    <div class="card shadow-none border">
                        <div class="card-header">
                            <h6 class="mb-0">Thông tin tài khoản</h6>
                        </div>
                        <!-- Validate Start -->
                    @include('admin.partials.validate', ['name' => 'name'])
                    @include('admin.partials.validate', ['name' => 'type'])
                    @include('admin.partials.validate', ['name' => 'image'])
                    <!-- Validate End -->
                        <!-- Notification Start -->
                        @include('admin.partials.flash-message', ['name_success' => 'admin_user_change_information_success_message', 'name_error' => 'admin_user_change_information_error_message'])
                        <div class="card-body">
                            <form action="{{ route('admin.admin-user.change-information', ['id' => $adminUser->id]) }}" method="post" id="adminUserChangeInformationForm"
                                  class="row g-3" enctype="multipart/form-data">
                                @csrf
                                <div class="col-12">
                                    <label class="form-label">Tên <span class="required-input">*</span></label>
                                    <input type="text" class="form-control" name="name" value="{{ $adminUser->name }}">
                                </div>
                                <div class="col-12">
                                    <label class="form-label">Loại <span class="required-input">*</span></label>
                                    <input type="text" class="form-control" name="type" value="{{ $adminUser->type }}">
                                </div>
                                <div class="col-12">
                                    <label class="form-label">Số điện thoại</label>
                                    <input type="text" class="form-control" name="phone_number"
                                           value="{{ $adminUser->phone_number }}">
                                </div>
                                <div class="col-12">
                                    <label class="form-label">Địa chỉ</label>
                                    <input type="text" class="form-control" name="address"
                                           value="{{ $adminUser->address }}">
                                </div>
                                <div class="col-12">
                                    <label class="form-label">Hình ảnh</label>
                                    <input class="form-control" type="file" name="image" accept="image/*">
                                    @if (isset($adminUser->image_path) && file_exists($adminUser->image_path))
                                        <img class="mt-3" style="width: 131px;"
                                             src="{{ asset($adminUser->image_path) }}"
                                             alt="{{ $adminUser->image_name }}">
                                        <input type="hidden" name="current_image_name"
                                               value="{{ $adminUser->image_name }}">
                                        <input type="hidden" name="current_image_path"
                                               value="{{ $adminUser->image_path }}">
                                    @else
                                        <img class="mt-3"
                                             src="{{ asset('assets/admin/default/image/personal_page/no_image_131_131.png') }}"
                                             class="user-img"
                                             alt="no_image_131_131.png">
                                    @endif
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="text-start">
                        <button form="adminUserChangeInformationForm" type="submit" class="btn btn-primary px-4">Xác nhận</button>
                    </div>
                    <hr>
                    <div class="card shadow-none border">
                        <div class="card-header">
                            <h6 class="mb-0">Đổi mật khẩu</h6>
                        </div>
                        <!-- Validate Start -->
                    @include('admin.partials.validate', ['name' => 'old_password'])
                    @include('admin.partials.validate', ['name' => 'new_password'])
                    @include('admin.partials.validate', ['name' => 'confirm_new_password'])
                    <!-- Validate End -->
                        <!-- Notification Start -->
                        @include('admin.partials.flash-message', ['name_success' => 'admin_user_change_password_success_message', 'name_error' => 'admin_user_change_password_error_message'])
                        <div class="card-body">
                            <form action="{{ route('admin.admin-user.change-password', ['id' => $adminUser->id]) }}" method="post" id="adminUserChangePasswordForm"
                                  class="row g-3">
                                @csrf
                                <div class="col-12">
                                    <label class="form-label">Mật khẩu cũ <span class="required-input">*</span></label>
                                    <input type="password" class="form-control" name="old_password">
                                </div>
                                <div class="col-12">
                                    <label class="form-label">Mật khẩu mới <span class="required-input">*</span></label>
                                    <input type="password" class="form-control" name="new_password">
                                </div>
                                <div class="col-12">
                                    <label class="form-label">Xác nhận Mật khẩu mới <span class="required-input">*</span></label>
                                    <input type="password" class="form-control" name="confirm_new_password">
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="text-start">
                        <button form="adminUserChangePasswordForm" type="submit" class="btn btn-primary px-4">Xác nhận</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-lg-4">
            <div class="card shadow-sm border-0 overflow-hidden">
                <div class="card-body">
                    <div class="profile-avatar text-center">
                        @if(isset($adminUser->image_path) && file_exists($adminUser->image_path))
                            <img src="{{ asset($adminUser->image_path) }}" class="rounded-circle shadow" width="120"
                                 height="120" alt="{{ $adminUser->image_name }}">
                        @else
                            <img class="rounded-circle shadow" width="120"
                                 height="120"
                                 src="{{ asset('assets/admin/default/image/personal_page/no_image_131_131.png') }}"
                                 class="user-img"
                                 alt="no_image_131_131.png">
                        @endif
                    </div>
                    <div class="d-flex align-items-center justify-content-around mt-5 gap-3">
                        <div class="text-center">
                            <h4 class="mb-0">{{ number_format($adminUser->news->count()) }}</h4>
                            <p class="mb-0 text-secondary">Tin tức</p>
                        </div>
                        <div class="text-center">
                            <h4 class="mb-0">{{ number_format($adminUser->announcements->count()) }}</h4>
                            <p class="mb-0 text-secondary">Thông báo</p>
                        </div>
                        <div class="text-center">
                            <h4 class="mb-0">{{ number_format($adminUser->original_bible_verses->count()) }}</h4>
                            <p class="mb-0 text-secondary">Câu gốc KT</p>
                        </div>
                    </div>
                    <div class="text-center mt-4">
                        <h4 class="mb-1">{{ isset($adminUser->name) ? $adminUser->name : '' }}</h4>
                        <p class="mb-0 text-secondary">{{ $adminUser->email }}</p>
                        <p class="mb-0 text-secondary">{{ isset($adminUser->phone_number) ? $adminUser->phone_number : '' }}</p>
                        <p class="mb-0 text-secondary">{{ isset($adminUser->address) ? $adminUser->address : '' }}</p>
                        <div class="mt-4"></div>
                        <h6 class="mb-1">{{ isset($adminUser->type) ? $adminUser->type : '' }}</h6>
                    </div>
                    {{--                    <hr>--}}
                </div>
            </div>
        </div>
    </div><!--end row-->
@endsection
