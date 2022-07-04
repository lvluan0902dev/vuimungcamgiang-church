@extends('client.layouts.client')

@section('title')
    {{ $announcements->title }}
@endsection

@section('css')

@endsection

@section('js')

@endsection

@section('content')
    @include('client.partials.page-banner', ['name' => 'Thông báo'])

    <!-- News Start -->
    <div class="section section-padding mt-n10">
        <div class="container">

            <div class="row gx-10">
                <div class="col-lg-8">
                    <!-- Blog Details Wrapper Start -->
                    <div class="blog-details-wrapper">
                        <div class="blog-details-admin-meta">
                            <div class="author">
                                <div class="author-thumb">
                                    <a>
                                        @if(isset($announcements->admin_user->image_path) && file_exists($announcements->admin_user->image_path))
                                            <img src="{{ asset($announcements->admin_user->image_path) }}"
                                                 alt="{{ $announcements->admin_user->image_name }}">
                                        @endif
                                    </a>
                                </div>
                                <div class="author-name">
                                    <a class="name">{{ isset($announcements->admin_user->name) ? $announcements->admin_user->name : '' }}</a>
                                </div>
                            </div>
                            <div class="blog-meta">
                                <span> <i class="icofont-calendar"></i> {{ isset($announcements->created_at) ? $announcements->created_at : '' }}</span>
                                <span> <i class="icofont-eye-alt"></i> {{ isset($numberOfViews) ? number_format($numberOfViews) : 0 }} </span>
                                <span class="tag">
                                    <a>
                                        @if(isset($announcements->type))
                                            {{ $announcements->type == 'news' ? 'Thông báo' : 'Thông báo' }}
                                        @endif
                                    </a>
                                </span>
                            </div>
                        </div>

                        <h2 class="title">{{ isset($announcements->title) ? $announcements->title : '' }}</h2>

                        <div class="blog-details-description">
                            {!! isset($announcements->content) ? $announcements->content : '' !!}
                        </div>

                        <div class="blog-details-label">
                            <h4 class="label">Chia sẻ:</h4>
                            <ul class="social">
                                <li><a href="#"><i class="flaticon-facebook"></i></a></li>
                            </ul>
                        </div>

                    </div>
                    <!-- Blog Details Wrapper End -->
                </div>
                <div class="col-lg-4">

                    <!-- Blog Sidebar Start -->
                    <div class="sidebar">

                        <!-- Sidebar Widget Search Start -->
                        <div class="sidebar-widget widget-search">
                            <form action="{{ route('client.announcements.search') }}" method="post">
                                @csrf
                                <input type="text" name="search" placeholder="Nhập nội dung..."
                                       value="{{ \Illuminate\Support\Facades\Request::get('tim-kiem') }}">
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
