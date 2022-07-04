<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>@yield('title') - Hội Thánh Vui Mừng Báp-Tít Cẩm Giàng</title>
    <meta name="robots" content="noindex, follow"/>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <!-- Favicon -->
    <link rel="shortcut icon" type="image/x-icon"
          href="{{ asset('assets/client/default/image/logo/vui-mung-cam-giang-180-180.png') }}">

    <!-- CSS
	============================================ -->

    <!-- Icon Font CSS -->
    <link rel="stylesheet" href="{{ asset('assets/client/template/css/plugins/icofont.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/client/template/css/plugins/flaticon.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/client/template/css/plugins/font-awesome.min.css') }}">

    <!-- Plugins CSS -->
    <link rel="stylesheet" href="{{ asset('assets/client/template/css/plugins/animate.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/client/template/css/plugins/swiper-bundle.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/client/template/css/plugins/magnific-popup.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/client/template/css/plugins/nice-select.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/client/template/css/plugins/apexcharts.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/client/template/css/plugins/jqvmap.min.css') }}">

    <!-- Main Style CSS -->
    <link rel="stylesheet" href="{{ asset('assets/client/template/css/style.css') }}">

    <!-- fontawesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css"
          integrity="sha512-KfkfwYDsLkIlwQp6LFnl8zNdLGxu9YAA1QvwINks4PhcElQSvqcyVLLD9aMhXd13uQjoXtEKNosOWaZqXgel0g=="
          crossorigin="anonymous" referrerpolicy="no-referrer"/>

    <style>
        @media only screen and (min-width: 992px) and (max-width: 1199px), only screen and (min-width: 768px) and (max-width: 991px), only screen and (max-width: 767px) {
            .header-logo a img {
                width: unset;
            }
        }

        @media only screen and (max-width: 575px) {
            .header-logo a img {
                width: unset;
            }
        }

        .shape-icon-box .box-content .box-wrapper {
            background-color: transparent;
        }
        
        .single-blog .blog-content .blog-author .tag a {
            height: auto;
        }
    </style>
@yield('css')

<!--====== Use the minified version files listed below for better performance and remove the files listed above ======-->
    <!-- <link rel="stylesheet" href="assets/client/template/css/vendor/plugins.min.css">
    <link rel="stylesheet" href="assets/client/template/css/style.min.css"> -->

</head>

<body>

<div class="main-wrapper">

@include('client.partials.header')

@include('client.partials.mobile-menu')

<!-- Overlay Start -->
    <div class="overlay"></div>
    <!-- Overlay End -->

@yield('content')

@include('client.partials.footer')

<!--Back To Start-->
    <a href="#" class="back-to-top">
        <i class="icofont-simple-up"></i>
    </a>
    <!--Back To End-->

</div>


<!-- JS
============================================ -->

<!-- Modernizer & jQuery JS -->
<script src="{{ asset('assets/client/template/js/vendor/modernizr-3.11.2.min.js') }}"></script>
<script src="{{ asset('assets/client/template/js/vendor/jquery-3.5.1.min.js') }}"></script>

<!-- Bootstrap JS -->
<script src="{{ asset('assets/client/template/js/plugins/popper.min.js') }}"></script>
<script src="{{ asset('assets/client/template/js/plugins/bootstrap.min.js') }}"></script>

<!-- Plugins JS -->
<script src="{{ asset('assets/client/template/js/plugins/swiper-bundle.min.js') }}"></script>
<script src="{{ asset('assets/client/template/js/plugins/jquery.magnific-popup.min.js') }}"></script>
<script src="{{ asset('assets/client/template/js/plugins/video-playlist.js') }}"></script>
<script src="{{ asset('assets/client/template/js/plugins/jquery.nice-select.min.js') }}"></script>
<script src="{{ asset('assets/client/template/js/plugins/ajax-contact.js') }}"></script>

<!--====== Use the minified version files listed below for better performance and remove the files listed above ======-->
<!-- <script src="assets/client/template/js/plugins.min.js"></script> -->


<!-- Main JS -->
<script src="{{ asset('assets/client/template/js/main.js') }}"></script>

<!-- fontawesome -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/js/all.min.js"
        integrity="sha512-6PM0qYu5KExuNcKt5bURAoT6KCThUmHRewN3zUFNaoI6Di7XJPTMoT6K0nsagZKk2OB4L7E3q1uQKHNHd4stIQ=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>

<!-- bootstrap bundle -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.0.0-beta3/js/bootstrap.bundle.min.js" integrity="sha512-DSdiuNZtfUhehZHXtit9Sa/83i06YSnvT8Js8drwdkVCDMk3JwpIxdhf2oRUByUDB3wguN2iAzoTNfxFAuqGyA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
@yield('js')
</body>

</html>
