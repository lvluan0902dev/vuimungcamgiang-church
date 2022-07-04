@extends('client.layouts.client')

@section('title')
    Tin tức
@endsection

@section('css')

@endsection

@section('js')

@endsection

@section('content')
    @include('client.partials.page-banner', ['name' => 'Tin tức'])

    <!-- News Start -->
    <div class="section section-padding mt-n10">
        <div class="container">

            <div class="row gx-10">
                <div class="col-lg-8">

                    <!-- Blog Wrapper Start -->
                    <div class="blog-wrapper">
                        <div class="row">
                            @foreach($listNews as $news)
                            <div class="col-md-6">

                                <!-- Single Blog Start -->
                                <div class="single-blog">
                                    <div class="blog-image">
                                        <a href="{{ route('client.news.details', ['url' => $news->url]) }}">
                                            @if(isset($news->image_path) && file_exists($news->image_path))
                                                <img
                                                    src="{{ asset($news->image_path) }}"
                                                    alt="{{ $news->image_name }}">
                                            @endif
                                        </a>
                                    </div>
                                    <div class="blog-content">
                                        <div class="blog-author">
                                            <div class="author">
                                                <div class="author-thumb">
                                                    <a>
                                                        @if(isset($news->admin_user->image_path) && file_exists($news->admin_user->image_path))
                                                            <img
                                                                src="{{ asset($news->admin_user->image_path) }}"
                                                                alt="{{ $news->admin_user->image_name }}">
                                                        @endif
                                                    </a>
                                                </div>
                                                <div class="author-name">
                                                    <a class="name">{{ isset($news->admin_user->name) ? $news->admin_user->name : '' }}</a>
                                                </div>
                                            </div>
                                            <div class="tag">
                                                <a>
                                                    @if(isset($news->type))
                                                        {{ $news->type == 'news' ? 'Tin tức' : 'Thông báo' }}
                                                    @endif
                                                </a>
                                            </div>
                                        </div>

                                        <h4 class="title"><a href="{{ route('client.news.details', ['url' => $news->url]) }}">{{ isset($news->title) ? $news->title : '' }}</a></h4>

                                        <div class="blog-meta">
                                            <span> <i class="icofont-calendar"></i> {{ isset($news->created_at) ? $news->created_at : '' }}</span>
                                            <span> <i class="icofont-eye-alt"></i> {{ isset($news->number_of_views) ? number_format($news->number_of_views) : 0 }} </span>
                                        </div>

                                        <a href="{{ route('client.news.details', ['url' => $news->url]) }}" class="btn btn-secondary btn-hover-primary">Xem chi tiết</a>
                                    </div>
                                </div>
                                <!-- Single Blog End -->

                            </div>
                            @endforeach
                        </div>
                    </div>
                    <!-- Blog Wrapper End -->

                    <!-- Page Pagination End -->
                    <div class="mt-3">
                        <div class="col-12 d-flex justify-content-center">
                            {{ $listNews->links() }}
                        </div>
                    </div>
                    <!-- Page Pagination End -->

                </div>
                <div class="col-lg-4">

                    <!-- Blog Sidebar Start -->
                    <div class="sidebar">

                        <!-- Sidebar Widget Search Start -->
                        <div class="sidebar-widget widget-search">
                            <form action="{{ route('client.news.search') }}" method="post">
                                @csrf
                                <input type="text" name="search" placeholder="Nhập nội dung..." value="{{ \Illuminate\Support\Facades\Request::get('tim-kiem') }}">
                                <button type="submit"><i class="icofont-search-1"></i></button>
                            </form>
                        </div>
                        <!-- Sidebar Widget Search End -->

                        @include('client.partials.post-category')
                    </div>
                    <!-- Blog Sidebar End -->
                </div>
            </div>

        </div>
    </div>
    <!-- News End -->

    @include('client.partials.download-app')
@endsection
