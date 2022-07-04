<?php

use App\Model\NewsAndAnnouncements;

$newsAndAnnouncements = \Cache::remember('vmcgc_client_news_and_announcements', config('app.redis_time'), function () {
    return NewsAndAnnouncements::with(['admin_user' => function ($query) {
        $query->select('id', 'name', 'image_name', 'image_path');
    }])->where('status', 1)->latest()->take(3)->get();
});

?>

<!-- NewsAndAnnouncements Start -->
<div class="section section-padding mt-n1">
    <div class="container">

        <!-- Section Title Start -->
        <div class="section-title shape-03 text-center">
            <h5 class="sub-title">Tin tức & Thông báo</h5>
            <h2 class="main-title">Của <span> Hội Thánh</span></h2>
        </div>
        <!-- Section Title End -->

        <!-- Blog Wrapper Start -->
        <div class="blog-wrapper">
            <div class="row">
                @foreach($newsAndAnnouncements as $newsAndAnnouncement)
                    <div class="col-lg-4 col-md-6">

                        <!-- Single Blog Start -->
                        <div class="single-blog">
                            <div class="blog-image">
                                @if(isset($newsAndAnnouncement->type) && $newsAndAnnouncement->type == 'news')
                                    <a href="{{ route('client.news.details', ['url' => $newsAndAnnouncement->url]) }}">
                                        @if(isset($newsAndAnnouncement->image_path) && file_exists($newsAndAnnouncement->image_path))
                                            <img
                                                src="{{ asset($newsAndAnnouncement->image_path) }}"
                                                alt="{{ $newsAndAnnouncement->image_name }}">
                                        @endif
                                    </a>
                                @else
                                    <a href="{{ route('client.announcements.details', ['url' => $newsAndAnnouncement->url]) }}">
                                        @if(isset($newsAndAnnouncement->image_path) && file_exists($newsAndAnnouncement->image_path))
                                            <img
                                                src="{{ asset($newsAndAnnouncement->image_path) }}"
                                                alt="{{ $newsAndAnnouncement->image_name }}">
                                        @endif
                                    </a>
                                @endif
                            </div>
                            <div class="blog-content">
                                <div class="blog-author">
                                    <div class="author">
                                        <div class="author-thumb">
                                            <a>
                                                @if(isset($newsAndAnnouncement->admin_user->image_path) && file_exists($newsAndAnnouncement->admin_user->image_path))
                                                    <img
                                                        src="{{ asset($newsAndAnnouncement->admin_user->image_path) }}"
                                                        alt="{{ $newsAndAnnouncement->admin_user->image_name }}">
                                                @endif
                                            </a>
                                        </div>
                                        <div class="author-name">
                                            <a class="name">{{ isset($newsAndAnnouncement->admin_user->name) ? $newsAndAnnouncement->admin_user->name : '' }}</a>
                                        </div>
                                    </div>
                                    <div class="tag">
                                        <a>
                                            @if(isset($newsAndAnnouncement->type))
                                                {{ $newsAndAnnouncement->type == 'news' ? 'Tin tức' : 'Thông báo' }}
                                            @endif
                                        </a>
                                    </div>
                                </div>

                                <h4 class="title">
                                    @if(isset($newsAndAnnouncement->type) && $newsAndAnnouncement->type == 'news')
                                        <a href="{{ route('client.news.details', ['url' => $newsAndAnnouncement->url]) }}">{{ isset($newsAndAnnouncement->title) ? $newsAndAnnouncement->title : '' }}</a>
                                    @else
                                        <a href="{{ route('client.announcements.details', ['url' => $newsAndAnnouncement->url]) }}">{{ isset($newsAndAnnouncement->title) ? $newsAndAnnouncement->title : '' }}</a>
                                    @endif
                                </h4>

                                <div class="blog-meta">
                                    <span> <i class="icofont-calendar"></i> {{ isset($newsAndAnnouncement->created_at) ? $newsAndAnnouncement->created_at : '' }}</span>
                                    <span> <i class="icofont-eye-alt"></i> {{ isset($newsAndAnnouncement->number_of_views) ? number_format($newsAndAnnouncement->number_of_views) : 0 }} </span>
                                </div>

                                @if(isset($newsAndAnnouncement->type) && $newsAndAnnouncement->type == 'news')
                                <a href="{{ route('client.news.details', ['url' => $newsAndAnnouncement->url]) }}" class="btn btn-secondary btn-hover-primary">Xem chi tiết</a>
                                @else
                                    <a href="{{ route('client.announcements.details', ['url' => $newsAndAnnouncement->url]) }}" class="btn btn-secondary btn-hover-primary">Xem chi tiết</a>
                                @endif
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
<!-- NewsAndAnnouncements End -->
