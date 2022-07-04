<?php

use Illuminate\Support\Facades\Auth;

$adminUser = Auth::guard('admin')->user();
?>

<!--start top header-->
<header class="top-header">
    <nav class="navbar navbar-expand gap-3">
        <div class="mobile-toggle-icon fs-3">
            <i class="bi bi-list"></i>
        </div>

        <div class="top-navbar-right ms-auto">
            <ul class="navbar-nav align-items-center">
                <li class="nav-item dropdown dropdown-user-setting">
                    <a class="nav-link dropdown-toggle dropdown-toggle-nocaret" href="javascript:;"
                       data-bs-toggle="dropdown">
                        <div class="user-setting d-flex align-items-center">
                            @if(isset($adminUser->image_path) && file_exists($adminUser->image_path))
                                <img src="{{ asset($adminUser->image_path) }}" class="user-img"
                                     alt="{{ $adminUser->image_name }}">
                            @else
                                <img src="{{ asset('assets/admin/default/image/personal_page/no_image_131_131.png') }}"
                                     class="user-img"
                                     alt="no_image_131_131.png">
                            @endif
                        </div>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li>
                            <a class="dropdown-item" href="javascript:;">
                                <div class="d-flex align-items-center">
                                    @if(isset($adminUser->image_path) && file_exists($adminUser->image_path))
                                        <img src="{{ asset($adminUser->image_path) }}"
                                             alt="{{ $adminUser->image_name }}" class="rounded-circle" width="54"
                                             height="54">
                                    @endif
                                    <div class="ms-3">
                                        <h6 class="mb-0 dropdown-user-name">{{ isset($adminUser->name) ? $adminUser->name : '' }}</h6>
                                        <small
                                            class="mb-0 dropdown-user-designation text-secondary">{{ isset($adminUser->type) ? $adminUser->type : '' }}</small>
                                    </div>
                                </div>
                            </a>
                        </li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li>
                            <a class="dropdown-item" href="{{ route('admin.admin-user.personal-page') }}">
                                <div class="d-flex align-items-center">
                                    <div class=""><i class="bi bi-person-fill"></i></div>
                                    <div class="ms-3"><span>Trang cá nhân</span></div>
                                </div>
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="{{ route('admin.admin-user.index') }}">
                                <div class="d-flex align-items-center">
                                    <div class=""><i class="bi bi-person-lines-fill"></i></div>
                                    <div class="ms-3"><span>Danh sách tài khoản</span></div>
                                </div>
                            </a>
                        </li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li>
                            <a class="dropdown-item" href="{{ route('admin.auth.logout') }}">
                                <div class="d-flex align-items-center">
                                    <div class=""><i class="bi bi-lock-fill"></i></div>
                                    <div class="ms-3"><span>Đăng xuất</span></div>
                                </div>
                            </a>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    </nav>
</header>
<!--end top header-->
