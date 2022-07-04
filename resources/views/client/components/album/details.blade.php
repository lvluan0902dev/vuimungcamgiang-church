@extends('client.layouts.client')

@section('title')
    {{ $album->title }}
@endsection

@section('css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/5.0.0/normalize.min.css">

    <link rel='stylesheet prefetch' href='https://cdn.rawgit.com/yairEO/photobox/master/photobox/photobox.css'>

    <link rel="stylesheet" href="{{ asset('assets/client/default/photobox/css/style.css') }}">

    <style>
        .blog-details-description img {
            border-radius: unset;
            margin-top: unset;
        }
    </style>
@endsection

@section('js')
    <script src='http://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js'></script>
    <script src='https://cdn.rawgit.com/yairEO/photobox/master/photobox/jquery.photobox.js'></script>
    {{--    <script src="{{ asset('assets/client/default/photobox/js/index.js') }}"></script>--}}
    <script>
        !(function () {
            'use strict';

            var getUrlParameter = function getUrlParameter(sParam) {
                var sPageURL = window.location.search.substring(1),
                    sURLVariables = sPageURL.split('&'),
                    sParameterName,
                    i;

                for (i = 0; i < sURLVariables.length; i++) {
                    sParameterName = sURLVariables[i].split('=');

                    if (sParameterName[0] === sParam) {
                        return sParameterName[1] === undefined ? true : decodeURIComponent(sParameterName[1]);
                    }
                }
                return false;
            };

            $(document).ready(function () {
                var page = getUrlParameter('page');

                var numOfImages = window.location.search ? parseInt(window.location.search.match(/\d+$/)[0]) : 70,
                    gallery = $('#gallery'),
                    videos = [];
                // Get some photos from Flickr for the demo
                var appUrl = 'https://vuimungcamgiang.com/';
                var album_id = $('#album_id').val();
                $.ajax({
                    url: appUrl + 'api/get-all-album-image',
                    data: {
                        album_id: album_id,
                        page: page
                    },
                    type: 'post'
                })
                    .done(function (data) {
                        var loadedIndex = 1, isVideo;
                        // add the videos to the collection
                        var listImage = data.data.data;
                        $.each(listImage, function (index, photo) {
                            var imageUrl = appUrl + photo.image_path,
                                img = document.createElement('img');
                            // lazy show the photos one by one
                            img.onload = function (e) {
                                img.onload = null;
                                var link = document.createElement('a'),
                                    li = document.createElement('li')
                                link.href = this.largeUrl;
                                link.appendChild(this);
                                li.appendChild(link);
                                gallery[0].appendChild(li);
                                setTimeout(function () {
                                    $(li).addClass('loaded');
                                }, 25 * loadedIndex++);
                            };
                            img['largeUrl'] = imageUrl;
                            img.src = imageUrl;
                        });
                        // finally, initialize photobox on all retrieved images
                        $('#gallery').photobox('a', {thumbs: true}, callback);
                        // using setTimeout to make sure all images were in the DOM, before the history.load() function is looking them up to match the url hash
                        setTimeout(window._photobox.history.load, 1000);

                        function callback() {
                            // console.log('callback for loaded content:', this);
                        }
                    });
            });
        })();
    </script>
@endsection

@section('content')
    @include('client.partials.page-banner', ['name' => 'Album'])

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
                                        @if(isset($album->admin_user->image_path) && file_exists($album->admin_user->image_path))
                                            <img src="{{ asset($album->admin_user->image_path) }}"
                                                 alt="{{ $album->admin_user->image_name }}">
                                        @endif
                                    </a>
                                </div>
                                <div class="author-name">
                                    <a class="name">{{ isset($album->admin_user->name) ? $album->admin_user->name : '' }}</a>
                                </div>
                            </div>
                            <div class="blog-meta">
                                <span> <i class="icofont-calendar"></i> {{ isset($album->created_at) ? $album->created_at : '' }}</span>
                                <span> <i class="icofont-eye-alt"></i> {{ isset($numberOfViews) ? number_format($numberOfViews) : 0 }} </span>
                                <span class="tag">
                                    <a>Album</a>
                                </span>
                            </div>
                        </div>

                        <h2 class="title">{{ isset($album->title) ? $album->title : '' }}</h2>

                        <div class="blog-details-description">
                            <input type="hidden" id="album_id" value="{{ $album->id }}">
                            <!-- Page Pagination End -->
                            <div class="mt-3">
                                <div class="col-12 d-flex justify-content-center">
                                    {{ $albumImages->links() }}
                                </div>
                            </div>
                            <!-- Page Pagination End -->
                            <ul id='gallery'></ul>
                        </div>

                        {{--                        <div class="blog-details-label">--}}
                        {{--                            <h4 class="label">Chia sẻ:</h4>--}}
                        {{--                            <ul class="social">--}}
                        {{--                                <li><a href="#"><i class="flaticon-facebook"></i></a></li>--}}
                        {{--                            </ul>--}}
                        {{--                        </div>--}}

                    </div>
                    <!-- Blog Details Wrapper End -->
                </div>
                <div class="col-lg-4">

                    <!-- Blog Sidebar Start -->
                    <div class="sidebar">

                        <!-- Sidebar Widget Search Start -->
                        <div class="sidebar-widget widget-search">
                            <form action="{{ route('client.album.search') }}" method="post">
                                @csrf
                                <input type="text" name="search" placeholder="Nhập nội dung..."
                                       value="{{ \Illuminate\Support\Facades\Request::get('tim-kiem') }}">
                                <button type="submit"><i class="icofont-search-1"></i></button>
                            </form>
                        </div>
                        <!-- Sidebar Widget Search End -->
                    </div>
                    <!-- Blog Sidebar End -->
                </div>
            </div>

        </div>
    </div>
    <!-- News End -->

    @include('client.partials.download-app')
@endsection
