@extends('client.layouts.client')

@section('title')
    Trang chủ
@endsection

@section('css')

@endsection

@section('js')

@endsection

@section('content')
    @include('client.components.home.partials.banner')

    @include('client.components.home.partials.news_and_announcements')

    @include('client.partials.call-to-contact')

    <!-- How It Work End -->
    <div class="section section-padding mt-n1">

    </div>
    <!-- How It Work End -->

    @include('client.partials.download-app')

    @include('client.components.home.partials.position')

    <!-- Brand Logo Start -->
    <div class="section section-padding-02">

    </div>
    <!-- Brand Logo End -->

    @include('client.components.home.partials.original_bible_verse')
@endsection
