<?php
use App\Model\PhoneNumber;
use App\Model\Email;
use App\Model\SocialNetwork;

$phoneNumber = \Cache::remember('vmcgc_client_phone_number_header_mobile', config('app.redis_time'), function () {
    return PhoneNumber::where('status', 1)->orderBy('order', 'ASC')->first();
});

$email = \Cache::remember('vmcgc_client_email_header_mobile', config('app.redis_time'), function () {
    return Email::where('status', 1)->orderBy('order', 'ASC')->first();
});

$socialNetworks = \Cache::remember('vmcgc_client_social_networks_header_mobile', config('app.redis_time'), function () {
    return SocialNetwork::where('status', 1)->orderBy('order', 'ASC')->get();
});
?>

<!-- Mobile Menu Start -->
<div class="mobile-menu">

    <!-- Menu Close Start -->
    <a class="menu-close" href="javascript:void(0)">
        <i class="icofont-close-line"></i>
    </a>
    <!-- Menu Close End -->

    <!-- Mobile Top Medal Start -->
    <div class="mobile-top">
        <p><i class="flaticon-phone-call"></i> <a href="javascript:;">{{ isset($phoneNumber->content) ? $phoneNumber->content : '' }}</a></p>
        <p><i class="flaticon-email"></i> <a href="javascript:;">{{ isset($email->content) ? $email->content : '' }}</a></p>
    </div>
    <!-- Mobile Top Medal End -->

    <!-- Mobile Menu Start -->
    <div class="mobile-menu-items">
        <ul class="nav-menu">
            <li><a href="{{ route('client.home.index') }}">Trang chủ</a></li>
            <li><a href="{{ route('client.album.index') }}">Album</a></li>
            <li>
                <a href="#">Bài viết</a>
                <ul class="sub-menu">
                    <li><a href="{{ route('client.news.index') }}">Tin tức</a></li>
                    <li><a href="{{ route('client.announcements.index') }}">Thông báo</a></li>
                    <li><a href="{{ route('client.original-bible-verse.index') }}">Câu gốc Kinh Thánh</a></li>
                </ul>
            </li>
            <li>
                <a href="#">PowerPoint</a>
                <ul class="sub-menu">
                    <li><a href="{{ route('client.weekly-power-point.index') }}">Hằng tuần</a></li>
                    <li><a href="{{ route('client.song-power-point.index') }}">Bài hát</a></li>
                </ul>
            </li>
            <li><a href="{{ route('client.about.index') }}">Giới thiệu</a></li>
            <li><a href="{{ route('client.contact.index') }}">Liên hệ</a></li>
        </ul>

    </div>
    <!-- Mobile Menu End -->

    <!-- Mobile Menu End -->
    <div class="mobile-social">
        <ul class="social">
            @foreach($socialNetworks as $socialNetwork)
                <li><a href="{{ isset($socialNetwork->link) ? $socialNetwork->link : '' }}" target="_blank">{!! isset($socialNetwork->icon) ? $socialNetwork->icon : '' !!}</a></li>
            @endforeach
        </ul>
    </div>
    <!-- Mobile Menu End -->

</div>
<!-- Mobile Menu End -->
