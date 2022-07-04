@extends('admin.layouts.admin')

@section('title')
    PowerPoint Bài hát
@endsection

@section('css')

@endsection

@section('js')
    <!-- Update status -->
    <script>
        $(document).ready(function () {
            $(document).on('click', '.updateStatus', function () {
                var id = $(this).attr('songPowerPointStatusId');
                var status = $('#songPowerPointStatusInput' + id).attr('songPowerPointStatus');
                $.ajax({
                    type: 'post',
                    url: '/admin/song-power-point/update-status',
                    data: {id: id, status: status},
                    success: function (response) {
                        if (response != null) {
                            if (response['status'] === 1) {
                                $('#songPowerPointStatusInput' + response['id']).attr('songPowerPointStatus', 1);
                            } else {
                                $('#songPowerPointStatusInput' + response['id']).attr('songPowerPointStatus', 0);
                            }
                        }
                    }, error: function () {
                        alert('Error');
                    }
                })
            })
        })
    </script>

    <!-- Delete -->
    <script>
        $(document).ready(function () {
            $(document).on('click', '.confirmDelete', function () {
                var id = $(this).attr('songPowerPointDeleteId');
                Swal.fire({
                    title: 'Xoá mục đã chọn?',
                    text: "Mục đã chọn sẽ bị xoá!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Xác nhận',
                    cancelButtonText: 'Huỷ',
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = "/admin/song-power-point/delete/" + id;
                    }
                })
            })
        })
    </script>
@endsection

@section('content')
    @include('admin.partials.page-breadcrumb', ['key' => 'PowerPoint Bài hát', 'name' => 'Index'])

    <div class="row">
        <div class="col-12 mx-auto">
            <div class="d-flex justify-content-end">
                <a href="{{ route('admin.song-power-point.create') }}" class="btn btn-outline-success px-3"><i
                        class="fa-solid fa-circle-plus"></i> Thêm</a>
            </div>
            <hr/>
            <div class="card">
                <div class="card-body">
                    <!-- Notification Start -->
                @include('admin.partials.flash-message', ['name_success' => 'song_power_point_delete_success_message', 'name_error' => 'song_power_point_delete_error_message'])
                <!-- Notification End -->
                    <div class="d-flex justify-content-end">
                        <form action="{{ route('admin.song-power-point.search') }}" method="post">
                            @csrf
                            <div class="input-group mb-3">
                                <input type="text" class="form-control" name="search" placeholder="Nhập nội dung..."
                                       value="{{ \Illuminate\Support\Facades\Request::get('tim-kiem') }}">
                                <button class="btn btn-outline-secondary" type="submit">Tìm kiếm</button>
                            </div>
                        </form>
                    </div>
                    <div class="table-responsive">
                        <table class="table mb-0 table-striped">
                            <thead>
                            <tr>
                                <th scope="col">Thứ tự</th>
                                <th scope="col">Tên</th>
                                <th scope="col">Lượt xem</th>
                                <th scope="col">Lượt tải xuống</th>
                                <th scope="col">Tải về</th>
                                <th scope="col">Trạng thái</th>
                                <th scope="col">Hành động</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($songPowerPoints as $songPowerPoint)
                                <tr>
                                    <th scope="row">{{ number_format($songPowerPoint->order) }}</th>
                                    <td>{{ $songPowerPoint->name }}</td>
                                    <td>{{ number_format($songPowerPoint->number_of_views) }}</td>
                                    <td>{{ number_format($songPowerPoint->number_of_downloads) }}</td>
                                    <td>
                                        @if(isset($songPowerPoint->file_path) && file_exists($songPowerPoint->file_path))
                                            <a href="{{ asset($songPowerPoint->file_path) }}" download="">Tải về</a>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="javascript:;" class="updateStatus"
                                           songPowerPointStatusId="{{ $songPowerPoint->id }}">
                                            @if ($songPowerPoint->status == 1)
                                                <label class="switch">
                                                    <input type="checkbox"
                                                           id="songPowerPointStatusInput{{ $songPowerPoint->id }}"
                                                           songPowerPointStatus="1" checked>
                                                    <span class="slider round"></span>
                                                </label>
                                            @else
                                                <label class="switch">
                                                    <input type="checkbox"
                                                           id="songPowerPointStatusInput{{ $songPowerPoint->id }}"
                                                           songPowerPointStatus="0">
                                                    <span class="slider round"></span>
                                                </label>
                                            @endif
                                        </a>
                                    </td>
                                    <td>
                                        <a href="{{ route('admin.song-power-point.edit', ['id' => $songPowerPoint->id]) }}"
                                           class="btn btn-outline-warning">
                                            <i class="fa-solid fa-pencil"></i>
                                        </a>
                                        <a songPowerPointDeleteId="{{ $songPowerPoint->id }}" href="javascript:;"
                                           class="btn btn-outline-danger confirmDelete">
                                            <i class="fa-solid fa-trash-can"></i>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer">
                    <div class="row">
                        <div class="col-12 d-flex justify-content-center">
                            {{ $songPowerPoints->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div><!--end row-->
@endsection
