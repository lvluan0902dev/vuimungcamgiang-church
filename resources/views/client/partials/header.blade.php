<?php

use App\Model\PhoneNumber;
use App\Model\Email;
use App\Model\SocialNetwork;

$phoneNumber = \Cache::remember('vmcgc_client_phone_number_header', config('app.redis_time'), function () {
    return PhoneNumber::where('status', 1)->orderBy('order', 'ASC')->first();
});

$email = \Cache::remember('vmcgc_client_email_header', config('app.redis_time'), function () {
    return Email::where('status', 1)->orderBy('order', 'ASC')->first();
});

$socialNetworks = \Cache::remember('vmcgc_client_social_networks_header', config('app.redis_time'), function () {
    return SocialNetwork::where('status', 1)->orderBy('order', 'ASC')->get();
});
?>

<!-- Header Section Start -->
<div class="header-section">

    <!-- Header Top Start -->
    <div class="header-top d-none d-lg-block">
        <div class="container">

            <!-- Header Top Wrapper Start -->
            <div class="header-top-wrapper">

                <!-- Header Top Left Start -->
                <div class="header-top-left">
                    <p>Hội Thánh <a href="javascript:;">Vui Mừng Báp-Tít Cẩm Giàng</a></p>
                </div>
                <!-- Header Top Left End -->

                <!-- Header Top Medal Start -->
                <div class="header-top-medal">
                    <div class="top-info">
                        <p><i class="flaticon-phone-call"></i> <a
                                href="javascript:;">{{ isset($phoneNumber->content) ? $phoneNumber->content : '' }}</a>
                        </p>
                        <p><i class="flaticon-email"></i> <a
                                href="javascript:;">{{ isset($email->content) ? $email->content : '' }}</a></p>
                    </div>
                </div>
                <!-- Header Top Medal End -->

                <!-- Header Top Right Start -->
                <div class="header-top-right">
                    <ul class="social">
                        @foreach($socialNetworks as $socialNetwork)
                            <li><a href="{{ isset($socialNetwork->link) ? $socialNetwork->link : '' }}"
                                   target="_blank">{!! isset($socialNetwork->icon) ? $socialNetwork->icon : '' !!}</a>
                            </li>
                        @endforeach
                    </ul>
                </div>
                <!-- Header Top Right End -->

            </div>
            <!-- Header Top Wrapper End -->

        </div>
    </div>
    <!-- Header Top End -->

    <!-- Header Main Start -->
    <div class="header-main">
        <div class="container">

            <!-- Header Main Start -->
            <div class="header-main-wrapper">

                <!-- Header Logo Start -->
                <div class="header-logo">
                    <a href="{{ route('client.home.index') }}"><img
                            src="{{ asset('assets/client/default/image/logo/vui-mung-cam-giang-59-59.png') }}"
                            alt="vui-mung-cam-giang-59-59.png"></a>
                </div>
                <!-- Header Logo End -->

                <!-- Header Menu Start -->
                <div class="header-menu d-none d-lg-block">
                    <ul class="nav-menu">
                        <li class="{{ Session::get('page_client') == 'home' ? 'active' : '' }}">
                            <a href="{{ route('client.home.index') }}">Trang chủ</a>
                        </li>
                        <li class="{{ Session::get('page_client') == 'album' ? 'active' : '' }}">
                            <a href="{{ route('client.album.index') }}">Album</a>
                        </li>
                        <li class="{{ Session::get('page_client') == 'posts' ? 'active' : '' }}">
                            <a href="javascript:;">Bài viết</a>
                            <ul class="sub-menu">
                                <li class="{{ Session::get('item_client') == 'news' ? 'active' : '' }}">
                                    <a href="{{ route('client.news.index') }}">Tin tức</a>
                                </li>
                                <li class="{{ Session::get('item_client') == 'announcements' ? 'active' : '' }}">
                                    <a href="{{ route('client.announcements.index') }}">Thông báo</a>
                                </li>
                                <li class="{{ Session::get('item_client') == 'original_bible_verse' ? 'active' : '' }}">
                                    <a href="{{ route('client.original-bible-verse.index') }}">Câu gốc Kinh Thánh</a>
                                </li>
                            </ul>
                        </li>
                        <li class="{{ Session::get('page_client') == 'powerpoint' ? 'active' : '' }}">
                            <a href="javascript:;">PowerPoint</a>
                            <ul class="sub-menu">
                                <li class="{{ Session::get('item_client') == 'weekly_power_point' ? 'active' : '' }}">
                                    <a href="{{ route('client.weekly-power-point.index') }}">Hằng tuần</a>
                                </li>
                                <li class="{{ Session::get('item_client') == 'song_power_point' ? 'active' : '' }}">
                                    <a href="{{ route('client.song-power-point.index') }}">Bài hát</a>
                                </li>
                            </ul>
                        </li>
                        <li class="{{ Session::get('page_client') == 'about' ? 'active' : '' }}">
                            <a href="{{ route('client.about.index') }}">Giới thiệu</a>
                        </li>
                        <li class="{{ Session::get('page_client') == 'contact' ? 'active' : '' }}">
                            <a href="{{ route('client.contact.index') }}">Liên hệ</a>
                        </li>
                    </ul>

                </div>
                <!-- Header Menu End -->

                <!-- Header Sing In & Up Start -->
            {{--                <div class="header-sign-in-up d-none d-lg-block">--}}
            {{--                    <ul>--}}
            {{--                        <li><a class="sign-in" href="login.html">Sign In</a></li>--}}
            {{--                        <li><a class="sign-up" href="register.html">Sign Up</a></li>--}}
            {{--                    </ul>--}}
            {{--                </div>--}}
            <!-- Header Sing In & Up End -->

                <!-- Header Mobile Toggle Start -->
                <div class="header-toggle d-lg-none">
                    <a class="menu-toggle" href="javascript:void(0)">
                        <span></span>
                        <span></span>
                        <span></span>
                    </a>
                </div>
                <!-- Header Mobile Toggle End -->

            </div>
            <!-- Header Main End -->

        </div>
    </div>
    <!-- Header Main End -->

</div>
<!-- Header Section End -->
