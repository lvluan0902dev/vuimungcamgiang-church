<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" href="{{ asset('assets/client/default/image/logo/vui-mung-cam-giang-180-180.png') }}" type="image/png" />
    <!--plugins-->
    <link href="{{ asset('assets/admin/template/plugins/simplebar/css/simplebar.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/admin/template/plugins/perfect-scrollbar/css/perfect-scrollbar.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/admin/template/plugins/metismenu/css/metisMenu.min.css') }}" rel="stylesheet" />
    <!-- Bootstrap CSS -->
    <link href="{{ asset('assets/admin/template/css/bootstrap.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/admin/template/css/bootstrap-extended.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/admin/template/css/style.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/admin/template/css/icons.css') }}" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css">

    <!-- loader-->
{{--    <link href="{{ asset('assets/admin/template/css/pace.min.css') }}" rel="stylesheet" />--}}


    <!--Theme Styles-->
    <link href="{{ asset('assets/admin/template/css/dark-theme.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/admin/template/css/light-theme.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/admin/template/css/semi-dark.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/admin/template/css/header-colors.css') }}" rel="stylesheet" />

    <title>@yield('title') - Quản Trị Hệ Thống Hội Thánh Vui Mừng Báp-Tít Cẩm Giàng</title>

    <!-- fontawesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css" integrity="sha512-KfkfwYDsLkIlwQp6LFnl8zNdLGxu9YAA1QvwINks4PhcElQSvqcyVLLD9aMhXd13uQjoXtEKNosOWaZqXgel0g==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <!-- switch custom -->
    <link rel="stylesheet" href="{{ asset('assets/admin/default/vendor/switch-custom/switch_custom.css') }}">
    <!-- sweetalert2 -->
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- required-style -->
    <link rel="stylesheet" href="{{ asset('assets/admin/default/css/required-style.css') }}">
    @yield('css')
</head>

<body>


<!--start wrapper-->
<div class="wrapper">
    @include('admin.partials.header')

    @include('admin.partials.sidebar')

    <!--start content-->
    <main class="page-content">
        @yield('content')
    </main>
    <!--end page main-->


    <!--start overlay-->
    <div class="overlay nav-toggle-icon"></div>
    <!--end overlay-->

    <!--Start Back To Top Button-->
    <a href="javaScript:;" class="back-to-top"><i class='bx bxs-up-arrow-alt'></i></a>
    <!--End Back To Top Button-->

{{--    @include('admin.partials.switcher')--}}

</div>
<!--end wrapper-->


<!-- Bootstrap bundle JS -->
<script src="{{ asset('assets/admin/template/js/bootstrap.bundle.min.js') }}"></script>
<!--plugins-->
<script src="{{ asset('assets/admin/template/js/jquery.min.js') }}"></script>
<script src="{{ asset('assets/admin/template/plugins/simplebar/js/simplebar.min.js') }}"></script>
<script src="{{ asset('assets/admin/template/plugins/metismenu/js/metisMenu.min.js') }}"></script>
<script src="{{ asset('assets/admin/template/plugins/perfect-scrollbar/js/perfect-scrollbar.js') }}"></script>
<script src="{{ asset('assets/admin/template/js/pace.min.js') }}"></script>
<!--app-->
<script src="{{ asset('assets/admin/template/js/app.js') }}"></script>

<!-- fontawesome -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/js/all.min.js" integrity="sha512-6PM0qYu5KExuNcKt5bURAoT6KCThUmHRewN3zUFNaoI6Di7XJPTMoT6K0nsagZKk2OB4L7E3q1uQKHNHd4stIQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<!-- tinycme -->
<script src="https://cdn.tiny.cloud/1/8j7su2hn4okmz32icrx43famupx36ujqadijpi0zzemwh2i5/tinymce/5/tinymce.min.js" referrerpolicy="origin"></script>
<script>
    var editor_config = {
        path_absolute : "/",
        selector: 'textarea.tinycme-editor',
        relative_urls: false,
        plugins: [
            "advlist autolink lists link image charmap print preview hr anchor pagebreak",
            "searchreplace wordcount visualblocks visualchars code fullscreen",
            "insertdatetime media nonbreaking save table directionality",
            "emoticons template paste textpattern"
        ],
        toolbar: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image media",
        file_picker_callback : function(callback, value, meta) {
            var x = window.innerWidth || document.documentElement.clientWidth || document.getElementsByTagName('body')[0].clientWidth;
            var y = window.innerHeight|| document.documentElement.clientHeight|| document.getElementsByTagName('body')[0].clientHeight;

            var cmsURL = editor_config.path_absolute + 'laravel-filemanager?editor=' + meta.fieldname;
            if (meta.filetype == 'image') {
                cmsURL = cmsURL + "&type=Images";
            } else {
                cmsURL = cmsURL + "&type=Files";
            }

            tinyMCE.activeEditor.windowManager.openUrl({
                url : cmsURL,
                title : 'Filemanager',
                width : x * 0.8,
                height : y * 0.8,
                resizable : "yes",
                close_previous : "no",
                onMessage: (api, message) => {
                    callback(message.content);
                }
            });
        }
    };

    tinymce.init(editor_config);
</script>
@yield('js')
</body>

</html>
