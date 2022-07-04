@extends('client.layouts.client')

@section('title')
    {{ $originalBibleVerse->title }}
@endsection

@section('css')

@endsection

@section('js')

@endsection

@section('content')
    @include('client.partials.page-banner', ['name' => 'Câu gốc Kinh Thánh'])

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
                                        @if(isset($originalBibleVerse->admin_user->image_path) && file_exists($originalBibleVerse->admin_user->image_path))
                                            <img src="{{ asset($originalBibleVerse->admin_user->image_path) }}"
                                                 alt="{{ $originalBibleVerse->admin_user->image_name }}">
                                        @endif
                                    </a>
                                </div>
                                <div class="author-name">
                                    <a class="name">{{ isset($originalBibleVerse->admin_user->name) ? $originalBibleVerse->admin_user->name : '' }}</a>
                                </div>
                            </div>
                            <div class="blog-meta">
                                <span> <i class="icofont-calendar"></i> {{ isset($originalBibleVerse->created_at) ? $originalBibleVerse->created_at : '' }}</span>
                                <span> <i class="icofont-eye-alt"></i> {{ isset($numberOfViews) ? number_format($numberOfViews) : 0 }} </span>
                                <span class="tag">
                                    <a>Câu gốc</a>
                                </span>
                            </div>
                        </div>

                        <h2 class="title">{{ isset($originalBibleVerse->title) ? $originalBibleVerse->title : '' }}</h2>

                        <div class="blog-details-description">
                            {!! isset($originalBibleVerse->content) ? $originalBibleVerse->content : '' !!}
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
                            <form action="{{ route('client.original-bible-verse.search') }}" method="post">
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
