<?php

use App\Model\OriginalBibleVerse;

$originalBibleVerses = \Cache::remember('vmcgc_client_original_bible_verses', config('app.redis_time'), function () {
    return OriginalBibleVerse::with(['admin_user' => function ($query) {
        $query->select('id', 'name', 'image_name', 'image_path');
    }])->where('status', 1)->latest()->take(3)->get();
});

?>

<!-- OriginalBibleVerse Start -->
<div class="section section-padding mt-n1">
    <div class="container">

        <!-- Section Title Start -->
        <div class="section-title shape-03 text-center">
            <h5 class="sub-title">Bài viết hằng tuần</h5>
            <h2 class="main-title">Câu gốc <span> Kinh Thánh</span></h2>
        </div>
        <!-- Section Title End -->

        <!-- Blog Wrapper Start -->
        <div class="blog-wrapper">
            <div class="row">
                @foreach($originalBibleVerses as $originalBibleVerse)
                    <div class="col-lg-4 col-md-6">

                        <!-- Single Blog Start -->
                        <div class="single-blog">
                            <div class="blog-image">
                                <a href="{{ route('client.original-bible-verse.details', ['url' => $originalBibleVerse->url]) }}">
                                    @if(isset($originalBibleVerse->image_path) && file_exists($originalBibleVerse->image_path))
                                        <img
                                            src="{{ asset($originalBibleVerse->image_path) }}"
                                            alt="{{ $originalBibleVerse->image_name }}">
                                    @endif
                                </a>
                            </div>
                            <div class="blog-content">
                                <div class="blog-author">
                                    <div class="author">
                                        <div class="author-thumb">
                                            <a>
                                                @if(isset($originalBibleVerse->admin_user->image_path) && file_exists($originalBibleVerse->admin_user->image_path))
                                                    <img
                                                        src="{{ asset($originalBibleVerse->admin_user->image_path) }}"
                                                        alt="{{ $originalBibleVerse->admin_user->image_name }}">
                                                @endif
                                            </a>
                                        </div>
                                        <div class="author-name">
                                            <a class="name">{{ isset($originalBibleVerse->admin_user->name) ? $originalBibleVerse->admin_user->name : '' }}</a>
                                        </div>
                                    </div>
                                    <div class="tag">
                                        <a>Câu gốc</a>
                                    </div>
                                </div>

                                <h4 class="title"><a
                                        href="{{ route('client.original-bible-verse.details', ['url' => $originalBibleVerse->url]) }}">{{ isset($originalBibleVerse->title) ? $originalBibleVerse->title : '' }}</a>
                                </h4>

                                <div class="blog-meta">
                                    <span> <i class="icofont-calendar"></i> {{ isset($originalBibleVerse->created_at) ? $originalBibleVerse->created_at : '' }}</span>
                                    <span> <i class="icofont-eye-alt"></i> {{ isset($originalBibleVerse->number_of_views) ? number_format($originalBibleVerse->number_of_views) : 0 }} </span>
                                </div>

                                <a href="{{ route('client.original-bible-verse.details', ['url' => $originalBibleVerse->url]) }}" class="btn btn-secondary btn-hover-primary">Xem chi tiết</a>
                            </div>
                        </div>
                        <!-- Single Blog End -->

                    </div>
                @endforeach
            </div>
        </div>
        <!-- Blog Wrapper End -->

    </div>
</div>
<!-- OriginalBibleVerse End -->
