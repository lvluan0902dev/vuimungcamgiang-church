<?php
use App\Model\PhoneNumber;
use App\Model\Email;
use App\Model\Address;
use App\Model\SocialNetwork;

$redisTime = config('app.redis_time');

$phoneNumber = \Cache::remember('vmcgc_client_phone_number_footer', $redisTime, function () {
    return PhoneNumber::where('status', 1)->orderBy('order', 'ASC')->first();
});

$email = \Cache::remember('vmcgc_client_email_footer', $redisTime, function () {
    return Email::where('status', 1)->orderBy('order', 'ASC')->first();
});

$address = \Cache::remember('vmcgc_client_address_footer', $redisTime, function () {
    return Address::where('status', 1)->orderBy('order', 'ASC')->first();
});

$socialNetworks = \Cache::remember('vmcgc_client_social_networks_footer', config('app.redis_time'), function () {
    return SocialNetwork::where('status', 1)->orderBy('order', 'ASC')->get();
});
?>

<!-- Footer Start  -->
<div class="section footer-section">

    <!-- Footer Widget Section Start -->
    <div class="footer-widget-section">

        <img class="shape-1 animation-down" src="{{ asset('assets/client/template/images/shape/shape-21.png') }}" alt="Shape">

        <div class="container">
            <div class="row">
                <div class="col-lg-6 col-md-6 order-md-1 order-lg-1">

                    <!-- Footer Widget Start -->
                    <div class="footer-widget">
                        <div class="widget-logo">
                            <a href="{{ route('client.home.index') }}"><img src="{{ asset('assets/client/default/image/logo/vui-mung-cam-giang-150-150.png') }}" alt="vui-mung-cam-giang-150-150.png"></a>
                        </div>

                        <div class="widget-address">
                            <h4 class="footer-widget-title">Hội Thánh</h4>
                            <p>Vui Mừng Báp-Tít Cẩm Giàng</p>
                        </div>

                        <ul class="widget-info">
                            <li>
                                <p> <i class="flaticon-phone-call"></i> <a href="javascript:;">{{ isset($phoneNumber->content) ? $phoneNumber->content : '' }}</a> </p>
                            </li>
                            <li>
                                <p> <i class="flaticon-email"></i> <a href="javascript:;">{{ isset($email->content) ? $email->content : '' }}</a> </p>
                            </li>
                            <li>
                                <p> <i class="flaticon-pin"></i> <a href="javascript:;">{{ isset($address->content) ? $address->content : '' }}</a> </p>
                            </li>
                        </ul>

                        <ul class="widget-social">
                            @foreach($socialNetworks as $socialNetwork)
                            <li><a href="{{ isset($socialNetwork->link) ? $socialNetwork->link : '' }}" target="_blank">{!! isset($socialNetwork->icon) ? $socialNetwork->icon : '' !!}</a></li>
                            @endforeach
                        </ul>
                    </div>
                    <!-- Footer Widget End -->

                </div>
                <div class="col-lg-6 order-md-3 order-lg-2">

                    <!-- Footer Widget Link Start -->
                    <div class="footer-widget-link">

                        <!-- Footer Widget Start -->
                        <div class="footer-widget">
                            <h4 class="footer-widget-title">Đường dẫn nhanh</h4>

                            <ul class="widget-link">
                                <li><a href="{{ route('client.album.index') }}">Album</a></li>
                                <li><a href="{{ route('client.news.index') }}">Tin tức</a></li>
                                <li><a href="{{ route('client.announcements.index') }}">Thông báo</a></li>
                                <li><a href="{{ route('client.original-bible-verse.index') }}">Câu gốc Kinh Thánh</a></li>
                                <li><a href="{{ route('client.weekly-power-point.index') }}">PowerPoint hằng tuần</a></li>
                                <li><a href="{{ route('client.song-power-point.index') }}">PowerPoint bài hát</a></li>
                                <li><a href="{{ route('client.about.index') }}">Giới thiệu</a></li>
                                <li><a href="{{ route('client.contact.index') }}">Liên hệ</a></li>
                            </ul>

                        </div>
                        <!-- Footer Widget End -->

                        <!-- Footer Widget Start -->
                        <!-- <div class="footer-widget">
                            <h4 class="footer-widget-title">Quick Links</h4>

                            <ul class="widget-link">
                                <li><a href="#">Privacy Policy</a></li>
                                <li><a href="#">Discussion</a></li>
                                <li><a href="#">Terms & Conditions</a></li>
                                <li><a href="#">Customer Support</a></li>
                                <li><a href="#">Course FAQ’s</a></li>
                            </ul>

                        </div> -->
                        <!-- Footer Widget End -->

                    </div>
                    <!-- Footer Widget Link End -->

                </div>
                <!-- <div class="col-lg-3 col-md-6 order-md-2 order-lg-3">
                    <div class="footer-widget">
                        <h4 class="footer-widget-title">Subscribe</h4>
                        <div class="widget-subscribe">
                            <p>Lorem Ipsum has been them an industry printer took a galley make book.</p>
                            <div class="widget-form">
                                <form action="#">
                                    <input type="text" placeholder="Email here">
                                    <button class="btn btn-primary btn-hover-dark">Subscribe Now</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div> -->
            </div>
        </div>

        <img class="shape-2 animation-left" src="{{ asset('assets/client/template/images/shape/shape-22.png') }}" alt="Shape">

    </div>
    <!-- Footer Widget Section End -->

    <!-- Footer Copyright Start -->
    <div class="footer-copyright">
        <div class="container">

            <!-- Footer Copyright Start -->
            <div class="copyright-wrapper">
                <div class="copyright-link">
                    <a href="https://www.facebook.com/profile.php?id=100031079060293" target="_blank">Mục sư</a>
                    <a href="https://www.facebook.com/lvluan0902/" target="_blank">Lập trình viên</a>
                    <a href="https://www.facebook.com/quyen.nguyenluong.984" target="_blank">Guitar</a>
                </div>
                <div class="copyright-text">
                    <p>&copy; 2022 <span> Hội Thánh Vui Mừng Báp-Tít Cẩm Giàng </span> Made with <i class="icofont-heart-alt"></i> by <a href="https://www.facebook.com/lvluan0902/" target="_blank">Lê Văn Luân</a></p>
                </div>
            </div>
            <!-- Footer Copyright End -->

        </div>
    </div>
    <!-- Footer Copyright End -->

</div>
<!-- Footer End -->
