@extends('client.layouts.client')

@section('title')
    PowerPoint Hằng tuần
@endsection

@section('css')

@endsection

@section('js')
    <!-- Update WeeklyPowerPoint download quantity -->
    <script>
        $(document).ready(function () {
            $(document).on('click', '.weeklyPowerPointDownload', function () {
                var id = $(this).attr('weeklyPowerPointDownloadId');

                $.ajax({
                    type: 'post',
                    url: '/power-point-hang-tuan/update-number-of-downloads',
                    data: {id: id},
                    success: function (response) {
                        if (response['result'] === true) {
                            console.log('Weekly PowerPoint Number Of Downloads Updated Successfully!');
                        }
                    },
                    error: function () {
                        alert('Error');
                    }
                })
            })
        })
    </script>
@endsection

@section('content')
    @include('client.partials.page-banner', ['name' => 'PowerPoint Hằng tuần'])

    <!-- Song PowerPoint Start -->
    <div class="section">
        <div class="section-padding">
            <div class="container">
                <div class="row">
                    <div class="col-12">
                        <div class="d-flex justify-content-end">
                            <form action="{{ route('client.weekly-power-point.search') }}" method="post">
                                @csrf
                                <div class="input-group mb-3">
                                    <input type="text" class="form-control border" name="search" placeholder="Nhập nội dung..." value="{{ \Illuminate\Support\Facades\Request::get('tim-kiem') }}">
                                    <button class="btn btn-primary btn-hover-dark" type="submit">Tìm kiếm</button>
                                </div>
                            </form>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Tên</th>
                                    <th scope="col">Xem</th>
                                    <th scope="col">Lượt xem</th>
                                    <th scope="col">Tải về</th>
                                    <th scope="col">Lượt tải xuống</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($weeklyPowerPoints as $key => $weeklyPowerPoint)
                                <tr>
                                    <th scope="row">{{ number_format($key + 1) }}</th>
                                    <td>{{ $weeklyPowerPoint->name }}</td>
                                    <td>
                                        @if(isset($weeklyPowerPoint->file_path) && file_exists($weeklyPowerPoint->file_path))
                                            <a style="color: dodgerblue;" href="{{ route('client.power-point.view-weekly-power-point', ['file_name' => $weeklyPowerPoint->file_name]) }}" target="_blank">Xem</a>
                                        @endif
                                    </td>
                                    <td>{{ number_format($weeklyPowerPoint->number_of_views) }}</td>
                                    <td>
                                        @if(isset($weeklyPowerPoint->file_path) && file_exists($weeklyPowerPoint->file_path))
                                            <a weeklyPowerPointDownloadId="{{ $weeklyPowerPoint->id }}" class="weeklyPowerPointDownload" style="color: dodgerblue;" href="{{ asset($weeklyPowerPoint->file_path) }}" download="">Tải về</a>
                                        @endif
                                    </td>
                                    <td>{{ number_format($weeklyPowerPoint->number_of_downloads) }}</td>
                                </tr>
                                @endforeach
                                </tbody>
                            </table>
                            <div>
                                <div class="col-12 d-flex justify-content-center">
                                    {{ $weeklyPowerPoints->links() }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Song PowerPoint End -->

    @include('client.partials.download-app')
@endsection
