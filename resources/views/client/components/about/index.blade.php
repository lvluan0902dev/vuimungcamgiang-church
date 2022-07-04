@extends('client.layouts.client')

@section('title')
    Giới thiệu
@endsection

@section('css')

@endsection

@section('js')

@endsection

@section('content')
    @include('client.partials.page-banner', ['name' => 'Giới thiệu'])

    <!-- About Start -->
    <div class="section">

        <div class="section-padding-02 mt-n10">
            <div class="container">
                <div class="row">
                    <div class="col-lg-6">

                        <!-- About Images Start -->
                        <div class="about-images">
                            <div class="images">
                                <img src="{{ asset('assets/client/default/image/about/vui-mung-cam-giang-about-banner.png') }}" alt="vui-mung-cam-giang-about-banner.png">
                            </div>

                            <div class="about-years">
                                <div class="years-icon">
                                    <img src="{{ asset('assets/client/default/image/logo/vui-mung-cam-giang-78-78.png') }}" alt="vui-mung-cam-giang-78-78.png">
                                </div>
                                <p><strong>10+</strong> năm hoạt động</p>
                            </div>
                        </div>
                        <!-- About Images End -->

                    </div>
                    <div class="col-lg-6">

                        <!-- About Content Start -->
                        <div class="about-content">
                            <h5 class="sub-title">Chào mừng bạn</h5>
                            <h2 class="main-title">Ghé thăm phần giới thiệu của Hội Thánh <span>Vui Mừng Báp-Tít Cẩm Giàng</span></h2>
                            <p>Tôi là Lê Văn Luân - người lập trình Website Hội Thánh Vui Mừng Báp-Tít Cẩm Giàng. Rất mong muốn phát triển mảng Website cho Hội Thánh nơi tôi sinh sống cũng như các Hội Thánh khác.</p>
{{--                            <a href="#" class="btn btn-primary btn-hover-dark">Start A Course</a>--}}
                        </div>
                        <!-- About Content End -->

                    </div>
                </div>
            </div>
        </div>

        <div class="section-padding-02 mt-n6">
            <div class="container">

                <!-- About Items Wrapper Start -->
                <div class="about-items-wrapper">
                    <div class="row">
                        <div class="col-md-6">
                            {!! isset($intros[0]->content) ? $intros[0]->content : '' !!}
                        </div>
                        <div class="col-md-6">
                            {!! isset($intros[1]->content) ? $intros[1]->content : '' !!}
                        </div>
                    </div>
                </div>
                <!-- About Items Wrapper End -->

            </div>
        </div>

    </div>
    <!-- About End -->

    @include('client.partials.call-to-contact')

    <!-- Team Member's Start -->
    <div class="section section-padding mt-n1">
        <div class="container">

            <!-- Section Title Start -->
            <div class="section-title shape-03 text-center">
                <h5 class="sub-title">Thành viên</h5>
                <h2 class="main-title">tại Hội Thánh <span> Vui Mừng Báp-Tít Cẩm Giàng</span></h2>
            </div>
            <!-- Section Title End -->

            <!-- Team Wrapper Start -->
            <div class="team-wrapper">
                <div class="row row-cols-lg-5 row-cols-sm-3 row-cols-2 ">
                    @foreach($members as $member)
                    <div class="col">

                        <!-- Single Team Start -->
                        <div class="single-team">
                            <div class="team-thumb">
                                @if (isset($member->image_path) && file_exists($member->image_path))
                                    <img src="{{ $member->image_path }}" alt="{{ $member->image_name }}">
                                @endif
                            </div>
                            <div class="team-content">
                                <h4 class="name">{{ isset($member->name) ? $member->name : '' }}</h4>
                                <span class="designation">{{ isset($member->type) ? $member->type : '' }}</span>
                            </div>
                        </div>
                        <!-- Single Team End -->

                    </div>
                    @endforeach
                </div>
            </div>
            <!-- Team Wrapper End -->

        </div>
    </div>
    <!-- Team Member's End -->

    @include('client.partials.download-app')
@endsection
