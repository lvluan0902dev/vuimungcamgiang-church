<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" href="{{ asset('assets/client/default/image/logo/vui-mung-cam-giang-180-180.png') }}" type="image/png"/>
    <!-- Bootstrap CSS -->
    <link href="{{ asset('assets/admin/template/css/bootstrap.min.css') }}" rel="stylesheet"/>
    <link href="{{ asset('assets/admin/template/css/bootstrap-extended.css') }}" rel="stylesheet"/>
    <link href="{{ asset('assets/admin/template/css/style.css') }}" rel="stylesheet"/>
    <link href="{{ asset('assets/admin/template/css/icons.css') }}" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css">

    <!-- loader-->
{{--    <link href="{{ asset('assets/admin/template/css/pace.min.css') }}" rel="stylesheet"/>--}}

    <title>Đăng Nhập - Quản Trị Hệ Thống Hội Thánh Vui Mừng Báp-Tít Cẩm Giàng</title>
</head>

<body>

<!--start wrapper-->
<div class="wrapper">

    <!--start content-->
    <main class="authentication-content">
        <div class="container-fluid">
            <div class="authentication-card">
                <div class="card shadow rounded-0 overflow-hidden">
                    <div class="row g-0">
                        <div class="col-lg-6 bg-login d-flex align-items-center justify-content-center">
                            <img src="{{ asset('assets/admin/template/images/error/login-img.jpg') }}" class="img-fluid"
                                 alt="">
                        </div>
                        <div class="col-lg-6">
                            <div class="card-body p-4 p-sm-5">
                                <h5 class="card-title">Đăng Nhập</h5>
                                <p class="card-text mb-5">Để sử dụng chức năng của hệ thống Hội Thánh Vui Mừng Báp-Tít Cẩm Giàng</p>
                                <form class="form-body" action="{{ route('admin.auth.login-post') }}" method="post">
                                    @csrf
                                    <div class="login-separater text-center mb-4"><span>ĐĂNG NHẬP VỚI EMAIL</span>
                                        <hr>
                                    </div>

                                    <!-- Validate Start -->
                                    @error('email')
                                    <div
                                        class="alert border-0 border-danger border-start border-4 bg-light-danger alert-dismissible fade show py-2">
                                        <div class="d-flex align-items-center">
                                            <div class="fs-3 text-danger"><i class="bi bi-x-circle-fill"></i>
                                            </div>
                                            <div class="ms-3">
                                                <div class="text-danger">{{$message}}</div>
                                            </div>
                                        </div>
                                        <button type="button" class="btn-close" data-bs-dismiss="alert"
                                                aria-label="Close"></button>
                                    </div>
                                    @enderror
                                    @error('password')
                                    <div
                                        class="alert border-0 border-danger border-start border-4 bg-light-danger alert-dismissible fade show py-2">
                                        <div class="d-flex align-items-center">
                                            <div class="fs-3 text-danger"><i class="bi bi-x-circle-fill"></i>
                                            </div>
                                            <div class="ms-3">
                                                <div class="text-danger">{{$message}}</div>
                                            </div>
                                        </div>
                                        <button type="button" class="btn-close" data-bs-dismiss="alert"
                                                aria-label="Close"></button>
                                    </div>
                                    @enderror
                                <!-- Validate End -->

                                    <!-- Notification Start -->
                                    @if(\Illuminate\Support\Facades\Session::has('login_error_message'))
                                        <div
                                            class="alert border-0 border-danger border-start border-4 bg-light-danger alert-dismissible fade show py-2">
                                            <div class="d-flex align-items-center">
                                                <div class="fs-3 text-danger"><i class="bi bi-x-circle-fill"></i>
                                                </div>
                                                <div class="ms-3">
                                                    <div
                                                        class="text-danger">{{ \Illuminate\Support\Facades\Session::get('login_error_message') }}</div>
                                                </div>
                                            </div>
                                            <button type="button" class="btn-close" data-bs-dismiss="alert"
                                                    aria-label="Close"></button>
                                        </div>
                                @endif
                                <!-- Notification End -->
                                    <div class="row g-3">
                                        <div class="col-12">
                                            <label for="inputEmailAddress" class="form-label">Email Address</label>
                                            <div class="ms-auto position-relative">
                                                <div
                                                    class="position-absolute top-50 translate-middle-y search-icon px-3">
                                                    <i class="bi bi-envelope-fill"></i></div>
                                                <input type="text"
                                                       class="form-control radius-30 ps-5 @error('email') is-invalid @enderror"
                                                       id="inputEmailAddress" placeholder="Email Address" name="email" value="{{ old('email') }}">
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <label for="inputChoosePassword" class="form-label">Enter Password</label>
                                            <div class="ms-auto position-relative">
                                                <div
                                                    class="position-absolute top-50 translate-middle-y search-icon px-3">
                                                    <i class="bi bi-lock-fill"></i></div>
                                                <input type="password"
                                                       class="form-control radius-30 ps-5 @error('password') is-invalid @enderror"
                                                       id="inputChoosePassword" placeholder="Enter Password"
                                                       name="password">
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="d-grid">
                                                <button type="submit" class="btn btn-primary radius-30">Đăng Nhập
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!--end page main-->

</div>
<!--end wrapper-->


<!--plugins-->
<script src="{{ asset('assets/admin/template/js/jquery.min.js') }}"></script>
<script src="{{ asset('assets/admin/template/js/pace.min.js') }}"></script>
<!-- Bootstrap bundle JS -->
<script src="{{ asset('assets/admin/template/js/bootstrap.bundle.min.js') }}"></script>

</body>

</html>
