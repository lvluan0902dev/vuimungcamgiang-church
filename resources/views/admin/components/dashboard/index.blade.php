@extends('admin.layouts.admin')

@section('title')
    Dashboard
@endsection

@section('css')

@endsection

@section('js')

@endsection

@section('content')
    @include('admin.partials.page-breadcrumb', ['key' => 'Dashboard', 'name' => 'Index'])

    <div class="row row-cols-1 row-cols-lg-2 row-cols-xl-2 row-cols-xxl-4">
        <div class="col">
            <div class="card overflow-hidden radius-10">
                <div class="card-body">
                    <div class="d-flex align-items-stretch justify-content-between overflow-hidden">
                        <div class="w-50">
                            <p>PowerPoint</p>
                            <h4 class="">{{ number_format($totalPowerPoint) }}</h4>
                        </div>
                        <div class="w-50">
                            <img style="width: 150px;" src="{{ asset('assets/admin/default/image/dashboard/powerpoint_icon.gif') }}" alt="powerpoint_icon.gif">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col">
            <div class="card overflow-hidden radius-10">
                <div class="card-body">
                    <div class="d-flex align-items-stretch justify-content-between overflow-hidden">
                        <div class="w-50">
                            <p>Album</p>
                            <h4 class="">{{ number_format($totalAlbum) }}</h4>
                        </div>
                        <div class="w-50">
                            <img style="width: 150px;" src="{{ asset('assets/admin/default/image/dashboard/album_icon.gif') }}" alt="album_icon.gif">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col">
            <div class="card overflow-hidden radius-10">
                <div class="card-body">
                    <div class="d-flex align-items-stretch justify-content-between overflow-hidden">
                        <div class="w-50">
                            <p>Tin tức và Thông báo</p>
                            <h4 class="">{{ number_format($totalNewsAndAnnouncements) }}</h4>
                        </div>
                        <div class="w-50">
                            <img style="width: 150px;" src="{{ asset('assets/admin/default/image/dashboard/news_and_announcements_icon.gif') }}" alt="news_and_announcements_icon.gif">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col">
            <div class="card overflow-hidden radius-10">
                <div class="card-body">
                    <div class="d-flex align-items-stretch justify-content-between overflow-hidden">
                        <div class="w-50">
                            <p>Câu gốc Kinh Thánh</p>
                            <h4 class="">{{ number_format($totalOriginalBibleVerse) }}</h4>
                        </div>
                        <div class="w-50">
                            <img style="width: 150px;" src="{{ asset('assets/admin/default/image/dashboard/original_bible_verse_icon.gif') }}" alt="original_bible_verse_icon.gif">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
