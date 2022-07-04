@extends('client.layouts.client')

@section('title')
    Xem PowerPoint {{ $powerPoint->name }}
@endsection

@section('css')

@endsection

@section('js')

@endsection

@section('content')
    @include('client.partials.page-banner', ['name' => 'Xem PowerPoint ' . $powerPoint->name])

    <!-- Song PowerPoint Start -->
    <div class="section">
        <div class="section-padding">
            <div class="container">
                <div class="row">
                    <div class="col-12">
                        <iframe src='https://view.officeapps.live.com/op/view.aspx?src={{ asset($powerPoint->file_path) }}' width='100%' height='600px' frameborder='0'></iframe>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Song PowerPoint End -->

    @include('client.partials.download-app')
@endsection
