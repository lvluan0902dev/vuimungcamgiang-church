<?php

use App\Model\Position;

$positions = \Cache::remember('vmcgc_client_positions', config('app.redis_time'), function () {
    return Position::where('status', 1)->orderBy('order', 'ASC')->get();
});

?>

<!-- Testimonial End -->
<div class="section section-padding-02 mt-n1">
    <div class="container">

        <!-- Section Title Start -->
        <div class="section-title shape-03 text-center">
            <h5 class="sub-title">Những người giữ chức vụ tại</h5>
            <h2 class="main-title">Hội Thánh <span> Vui Mừng Báp-Tít Cẩm Giàng</span></h2>
        </div>
        <!-- Section Title End -->

        <!-- Testimonial Wrapper End -->
        <div class="testimonial-wrapper testimonial-active">
            <div class="swiper-container">
                <div class="swiper-wrapper">
                @foreach($positions as $position)
                    <!-- Single Testimonial Start -->
                        <div class="single-testimonial swiper-slide">
                            <div class="testimonial-author">
                                <div class="author-thumb">
                                    @if(isset($position->image_path) && file_exists($position->image_path))
                                        <img src="{{ asset($position->image_path) }}"
                                             alt="{{ $position->image_name }}">
                                    @endif
                                    <i class="icofont-quote-left"></i>
                                </div>
                            </div>
                            <div class="testimonial-content">
                                <p>{{ isset($position->content) ? $position->content : '' }}</p>
                                <h4 class="name">{{ isset($position->name) ? $position->name : '' }}</h4>
                                <span class="designation">{{ isset($position->type) ? $position->type : '' }}</span>
                            </div>
                        </div>
                        <!-- Single Testimonial End -->
                    @endforeach
                </div>

                <!-- Add Pagination -->
                <div class="swiper-pagination"></div>
            </div>
        </div>
        <!-- Testimonial Wrapper End -->

    </div>
</div>
<!-- Testimonial End -->
