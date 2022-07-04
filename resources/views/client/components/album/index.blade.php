@extends('client.layouts.client')

@section('title')
    Album
@endsection

@section('css')

@endsection

@section('js')

@endsection

@section('content')
    @include('client.partials.page-banner', ['name' => 'Album'])

    <!-- News Start -->
    <div class="section section-padding mt-n10">
        <div class="container">

            <div class="row gx-10">
                <div class="col-lg-8">

                    <!-- Blog Wrapper Start -->
                    <div class="blog-wrapper">
                        <div class="row">
                            @foreach($albums as $album)
                            <div class="col-md-6">

                                <!-- Single Blog Start -->
                                <div class="single-blog">
                                    <div class="blog-image">
                                        <a href="{{ route('client.album.details', ['url' => $album->url]) }}">
                                            @if(isset($album->image_path) && file_exists($album->image_path))
                                                <img
                                                    src="{{ asset($album->image_path) }}"
                                                    alt="{{ $album->image_name }}">
                                            @endif
                                        </a>
                                    </div>
                                    <div class="blog-content">
                                        <div class="blog-author">
                                            <div class="author">
                                                <div class="author-thumb">
                                                    <a>
                                                        @if(isset($album->admin_user->image_path) && file_exists($album->admin_user->image_path))
                                                            <img
                                                                src="{{ asset($album->admin_user->image_path) }}"
                                                                alt="{{ $album->admin_user->image_name }}">
                                                        @endif
                                                    </a>
                                                </div>
                                                <div class="author-name">
                                                    <a class="name">{{ isset($album->admin_user->name) ? $album->admin_user->name : '' }}</a>
                                                </div>
                                            </div>
                                            <div class="tag">
                                                <a>Album</a>
                                            </div>
                                        </div>

                                        <h4 class="title"><a href="{{ route('client.album.details', ['url' => $album->url]) }}">{{ isset($album->title) ? $album->title : '' }}</a></h4>

                                        <div class="blog-meta">
                                            <span> <i class="icofont-calendar"></i> {{ isset($album->created_at) ? $album->created_at : '' }}</span>
                                            <span> <i class="icofont-eye-alt"></i> {{ isset($album->number_of_views) ? number_format($album->number_of_views) : 0 }} </span>
                                        </div>

                                        <a href="{{ route('client.album.details', ['url' => $album->url]) }}" class="btn btn-secondary btn-hover-primary">Xem chi tiết</a>
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
                            {{ $albums->links() }}
                        </div>
                    </div>
                    <!-- Page Pagination End -->

                </div>
                <div class="col-lg-4">

                    <!-- Blog Sidebar Start -->
                    <div class="sidebar">

                        <!-- Sidebar Widget Search Start -->
                        <div class="sidebar-widget widget-search">
                            <form action="{{ route('client.album.search') }}" method="post">
                                @csrf
                                <input type="text" name="search" placeholder="Nhập nội dung..." value="{{ \Illuminate\Support\Facades\Request::get('tim-kiem') }}">
                                <button type="submit"><i class="icofont-search-1"></i></button>
                            </form>
                        </div>
                        <!-- Sidebar Widget Search End -->

{{--                        @include('client.partials.post-category')--}}
                    </div>
                    <!-- Blog Sidebar End -->
                </div>
            </div>

        </div>
    </div>
    <!-- News End -->

    @include('client.partials.download-app')
@endsection
