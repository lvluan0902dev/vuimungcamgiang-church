<?php

use App\Model\Banner;
use App\Model\Position;
use App\Model\GoogleMaps;
use App\Model\PhoneNumber;
use App\Model\Email;
use App\Model\Address;
use App\Model\SocialNetwork;
use App\Model\Letter;
use App\Model\Intro;
use App\Model\Member;
use App\Model\SongPowerPoint;
use App\Model\WeeklyPowerPoint;
use App\Model\NewsAndAnnouncements;
use App\Model\OriginalBibleVerse;
use App\Model\Album;
use App\Model\AdminUser;

$redisTime = config('app.redis_time');

$totalBanner = \Cache::remember('vmcgc_admin_total_banner', $redisTime, function () {
    return Banner::get()->count();
});

$totalPosition = \Cache::remember('vmcgc_admin_total_position', $redisTime, function () {
    return Position::get()->count();
});

$totalGoogleMaps = \Cache::remember('vmcgc_admin_total_google_maps', $redisTime, function () {
    return GoogleMaps::get()->count();
});

$totalPhoneNumber = \Cache::remember('vmcgc_admin_total_phone_number', $redisTime, function () {
    return PhoneNumber::get()->count();
});

$totalEmail = \Cache::remember('vmcgc_admin_total_email', $redisTime, function () {
    return Email::get()->count();
});

$totalAddress = \Cache::remember('vmcgc_admin_total_address', $redisTime, function () {
    return Address::get()->count();
});

$totalSocialNetwork = \Cache::remember('vmcgc_admin_total_social_network', $redisTime, function () {
    return SocialNetwork::get()->count();
});

$totalLetter = \Cache::remember('vmcgc_admin_total_letter', $redisTime, function () {
    return Letter::get()->count();
});

$totalIntro = \Cache::remember('vmcgc_admin_total_intro', $redisTime, function () {
    return Intro::get()->count();
});

$totalMember = \Cache::remember('vmcgc_admin_total_member', $redisTime, function () {
    return Member::get()->count();
});

$totalSongPowerPoint = \Cache::remember('vmcgc_admin_total_song_power_point', $redisTime, function () {
    return SongPowerPoint::get()->count();
});

$totalWeeklyPowerPoint = \Cache::remember('vmcgc_admin_total_weekly_power_point', $redisTime, function () {
    return WeeklyPowerPoint::get()->count();
});

$totalNews = \Cache::remember('vmcgc_admin_total_news', $redisTime, function () {
    return NewsAndAnnouncements::where('type', 'news')->get()->count();
});

$totalAnnouncements = \Cache::remember('vmcgc_admin_total_announcements', $redisTime, function () {
    return NewsAndAnnouncements::where('type', 'announcements')->get()->count();
});

$totalOriginalBibleVerse = \Cache::remember('vmcgc_admin_total_original_bible_verse', $redisTime, function () {
    return OriginalBibleVerse::get()->count();
});

$totalAlbum = \Cache::remember('vmcgc_admin_total_album', $redisTime, function () {
    return Album::get()->count();
});

$totalAdminUser = \Cache::remember('vmcgc_admin_total_admin_user', $redisTime, function () {
    return AdminUser::get()->count();
});
?>

<!--start sidebar -->
<aside class="sidebar-wrapper" data-simplebar="true">
    <div class="sidebar-header">
        <div>
            <img src="{{ asset('assets/client/default/image/logo/vui-mung-cam-giang-270-270.png') }}" class="logo-icon"
                 alt="vui-mung-cam-giang-270-270.png">
        </div>
        <div>
            <h4 class="logo-text">Vui Mừng</h4>
        </div>
        <div class="toggle-icon ms-auto"><i class="bi bi-list"></i>
        </div>
    </div>
    <!--navigation-->
    <ul class="metismenu" id="menu">
        <li class="{{ Session::get('page_admin') == 'dashboard' ? 'mm-active' : '' }}">
            <a href="{{ route('admin.dashboard.index') }}">
                <div class="parent-icon">
                    <i class="fa-solid fa-gauge-high"></i>
                </div>
                <div class="menu-title">Dashboard</div>
            </a>
        </li>
        <li class="{{ Session::get('page_admin') == 'album' ? 'mm-active' : '' }}">
            <a href="{{ route('admin.album.index') }}">
                <div class="parent-icon">
                    <i class="fa-regular fa-images"></i>
                </div>
                <div class="menu-title">Album ({{ number_format($totalAlbum) }})</div>
            </a>
        </li>
        <li class="{{ Session::get('page_admin') == 'home_page' ? 'mm-active' : '' }}">
            <a href="javascript:;" class="has-arrow">
                <div class="parent-icon">
                    <i class="fa-solid fa-house-laptop"></i>
                </div>
                <div class="menu-title">Trang chủ</div>
            </a>
            <ul class="{{ Session::get('page_admin') == 'home_page' ? 'mm-show' : '' }}">
                <li class="{{ Session::get('item_admin') == 'banner' ? 'mm-active' : '' }}">
                    <a href="{{ route('admin.banner.index') }}"><i class="bi bi-circle"></i>Banner
                        ({{ number_format($totalBanner) }})</a>
                </li>
                <li class="{{ Session::get('item_admin') == 'position' ? 'mm-active' : '' }}">
                    <a href="{{ route('admin.position.index') }}"><i class="bi bi-circle"></i>Người giữ chức vụ
                        ({{ number_format($totalPosition) }})</a>
                </li>
            </ul>
        </li>

        <li class="{{ Session::get('page_admin') == 'about' ? 'mm-active' : '' }}">
            <a href="javascript:;" class="has-arrow">
                <div class="parent-icon">
                    <i class="fa-solid fa-circle-info"></i>
                </div>
                <div class="menu-title">Giới thiệu</div>
            </a>
            <ul class="{{ Session::get('page_admin') == 'about' ? 'mm-show' : '' }}">
                <li class="{{ Session::get('item_admin') == 'intro' ? 'mm-active' : '' }}">
                    <a href="{{ route('admin.intro.index') }}"><i class="bi bi-circle"></i>Đoạn giới thiệu
                        ({{ number_format($totalIntro) }})</a>
                </li>
                <li class="{{ Session::get('item_admin') == 'member' ? 'mm-active' : '' }}">
                    <a href="{{ route('admin.member.index') }}"><i class="bi bi-circle"></i>Thành viên
                        ({{ number_format($totalMember) }})</a>
                </li>
            </ul>
        </li>

        <li class="{{ Session::get('page_admin') == 'contact' ? 'mm-active' : '' }}">
            <a href="javascript:;" class="has-arrow">
                <div class="parent-icon">
                    <i class="fa-solid fa-address-card"></i>
                </div>
                <div class="menu-title">Liên hệ</div>
            </a>
            <ul class="{{ Session::get('page_admin') == 'contact' ? 'mm-show' : '' }}">
                <li class="{{ Session::get('item_admin') == 'google_maps' ? 'mm-active' : '' }}">
                    <a href="{{ route('admin.google-maps.index') }}"><i class="bi bi-circle"></i>Google Maps
                        ({{ number_format($totalGoogleMaps) }})</a>
                </li>
                <li class="{{ Session::get('item_admin') == 'phone_number' ? 'mm-active' : '' }}">
                    <a href="{{ route('admin.phone-number.index') }}"><i class="bi bi-circle"></i>Số điện thoại
                        ({{ number_format($totalPhoneNumber) }})</a>
                </li>
                <li class="{{ Session::get('item_admin') == 'email' ? 'mm-active' : '' }}">
                    <a href="{{ route('admin.email.index') }}"><i class="bi bi-circle"></i>Email
                        ({{ number_format($totalEmail) }})</a>
                </li>
                <li class="{{ Session::get('item_admin') == 'address' ? 'mm-active' : '' }}">
                    <a href="{{ route('admin.address.index') }}"><i class="bi bi-circle"></i>Địa chỉ
                        ({{ number_format($totalAddress) }})</a>
                </li>
                <li class="{{ Session::get('item_admin') == 'social_network' ? 'mm-active' : '' }}">
                    <a href="{{ route('admin.social-network.index') }}"><i class="bi bi-circle"></i>Mạng xã hội
                        ({{ number_format($totalSocialNetwork) }})</a>
                </li>
                <li class="{{ Session::get('item_admin') == 'letter' ? 'mm-active' : '' }}">
                    <a href="{{ route('admin.letter.index') }}"><i class="bi bi-circle"></i>Thư
                        ({{ number_format($totalLetter) }})</a>
                </li>
            </ul>
        </li>

        <li class="{{ Session::get('page_admin') == 'posts' ? 'mm-active' : '' }}">
            <a href="javascript:;" class="has-arrow">
                <div class="parent-icon">
                    <i class="fa-solid fa-newspaper"></i>
                </div>
                <div class="menu-title">Bài viết</div>
            </a>
            <ul class="{{ Session::get('page_admin') == 'posts' ? 'mm-show' : '' }}">
                <li class="{{ Session::get('item_admin') == 'news' ? 'mm-active' : '' }}">
                    <a href="{{ route('admin.news.index') }}"><i class="bi bi-circle"></i>Tin tức
                        ({{ number_format($totalNews) }})</a>
                </li>
                <li class="{{ Session::get('item_admin') == 'announcements' ? 'mm-active' : '' }}">
                    <a href="{{ route('admin.announcements.index') }}"><i class="bi bi-circle"></i>Thông báo
                        ({{ number_format($totalAnnouncements) }})</a>
                </li>
                <li class="{{ Session::get('item_admin') == 'original_bible_verse' ? 'mm-active' : '' }}">
                    <a href="{{ route('admin.original-bible-verse.index') }}"><i class="bi bi-circle"></i>Câu gốc Kinh
                        Thánh ({{ number_format($totalOriginalBibleVerse) }})</a>
                </li>
            </ul>
        </li>

        <li class="{{ Session::get('page_admin') == 'powerpoint' ? 'mm-active' : '' }}">
            <a href="javascript:;" class="has-arrow">
                <div class="parent-icon">
                    <i class="fa-solid fa-file-powerpoint"></i>
                </div>
                <div class="menu-title">PowerPoint</div>
            </a>
            <ul class="{{ Session::get('page_admin') == 'powerpoint' ? 'mm-show' : '' }}">
                <li class="{{ Session::get('item_admin') == 'weekly_power_point' ? 'mm-active' : '' }}">
                    <a href="{{ route('admin.weekly-power-point.index') }}"><i class="bi bi-circle"></i>Hằng tuần
                        ({{ number_format($totalWeeklyPowerPoint) }})</a>
                </li>
                <li class="{{ Session::get('item_admin') == 'song_power_point' ? 'mm-active' : '' }}">
                    <a href="{{ route('admin.song-power-point.index') }}"><i class="bi bi-circle"></i>Bài hát
                        ({{ number_format($totalSongPowerPoint) }})</a>
                </li>
            </ul>
        </li>
        <li class="{{ Session::get('page_admin') == 'admin_user' ? 'mm-active' : '' }}">
            <a href="{{ route('admin.admin-user.index') }}">
                <div class="parent-icon">
                    <i class="fa-solid fa-user-group"></i>
                </div>
                <div class="menu-title">Tài khoản ({{ number_format($totalAdminUser) }})</div>
            </a>
        </li>

        <li>
            <a href="javascript:;" class="has-arrow">
                <div class="parent-icon">
                    <i class="fa-solid fa-screwdriver-wrench"></i>
                </div>
                <div class="menu-title">Tool</div>
            </a>
            <ul>
                <li><a href="{{ route('tool.linkstorage') }}"><i class="bi bi-circle"></i>linkstorage</a>
                </li>
                <li><a href="{{ route('tool.clear') }}"><i class="bi bi-circle"></i>clear</a>
                </li>
                <li><a href="{{ route('tool.cache') }}"><i class="bi bi-circle"></i>cache</a>
                </li>
                <li><a href="{{ route('tool.flushall') }}"><i class="bi bi-circle"></i>flushall</a>
                </li>
            </ul>
        </li>
        {{--        <li>--}}
        {{--            <a href="javascript:;" class="has-arrow">--}}
        {{--                <div class="parent-icon"><i class="bi bi-house-fill"></i>--}}
        {{--                </div>--}}
        {{--                <div class="menu-title">ABC</div>--}}
        {{--            </a>--}}
        {{--            <ul>--}}
        {{--                <li> <a href="#"><i class="bi bi-circle"></i>XXX</a>--}}
        {{--                </li>--}}
        {{--            </ul>--}}
        {{--        </li>--}}
    </ul>
    <!--end navigation-->
</aside>
<!--end sidebar -->
