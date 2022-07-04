<?php
use App\Model\Banner;

$banner = \Cache::remember('vmcgc_client_banner', config('app.redis_time'), function () {
    return Banner::where('status', 1)->orderBy('order', 'ASC')->first();
});

?>

<!-- Slider Start -->
<div class="section slider-section">

    <!-- Slider Shape Start -->
    <div class="slider-shape">
        <img class="shape-1 animation-round" src="{{ asset('assets/client/template/images/shape/shape-8.png') }}" alt="Shape">
    </div>
    <!-- Slider Shape End -->

    <div class="container">

        <!-- Slider Content Start -->
        <div class="slider-content">
            <h4 class="sub-title">{{ isset($banner->title) ? $banner->title : '' }}</h4>
            <h2 class="main-title">
                {!! isset($banner->content) ? $banner->content : '' !!}
            </h2>
            <!-- <a class="btn btn-primary btn-hover-dark" href="#">Start A Course</a> -->
        </div>
        <!-- Slider Content End -->

    </div>

    <!-- Slider Courses Box Start -->

    <!-- Slider Courses Box End -->

    <!-- Slider Rating Box Start -->

    <!-- Slider Rating Box End -->

    <!-- Slider Images Start -->
    <div class="slider-images">
        <div class="images">
            @if(isset($banner->image_path) && file_exists($banner->image_path))
                <img src="{{ asset($banner->image_path) }}" alt="{{ $banner->image_name }}">
            @endif
        </div>
    </div>
    <!-- Slider Images End -->

    <!-- Slider Video Start -->

    <!-- Slider Video End -->

</div>
<!-- Slider End -->
