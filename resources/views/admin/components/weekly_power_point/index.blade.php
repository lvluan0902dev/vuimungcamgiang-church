@extends('admin.layouts.admin')

@section('title')
    PowerPoint Hằng tuần
@endsection

@section('css')

@endsection

@section('js')
    <!-- Update status -->
    <script>
        $(document).ready(function () {
            $(document).on('click', '.updateStatus', function () {
                var id = $(this).attr('weeklyPowerPointStatusId');
                var status = $('#weeklyPowerPointStatusInput' + id).attr('weeklyPowerPointStatus');
                $.ajax({
                    type: 'post',
                    url: '/admin/weekly-power-point/update-status',
                    data: {id: id, status: status},
                    success: function (response) {
                        if (response != null) {
                            if (response['status'] === 1) {
                                $('#weeklyPowerPointStatusInput' + response['id']).attr('weeklyPowerPointStatus', 1);
                            } else {
                                $('#weeklyPowerPointStatusInput' + response['id']).attr('weeklyPowerPointStatus', 0);
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
                var id = $(this).attr('weeklyPowerPointDeleteId');
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
                        window.location.href = "/admin/weekly-power-point/delete/" + id;
                    }
                })
            })
        })
    </script>
@endsection

@section('content')
    @include('admin.partials.page-breadcrumb', ['key' => 'PowerPoint Hằng tuần', 'name' => 'Index'])

    <div class="row">
        <div class="col-12 mx-auto">
            <div class="d-flex justify-content-end">
                <a href="{{ route('admin.weekly-power-point.create') }}" class="btn btn-outline-success px-3"><i
                        class="fa-solid fa-circle-plus"></i> Thêm</a>
            </div>
            <hr/>
            <div class="card">
                <div class="card-body">
                    <!-- Notification Start -->
                @include('admin.partials.flash-message', ['name_success' => 'weekly_power_point_delete_success_message', 'name_error' => 'weekly_power_point_delete_error_message'])
                <!-- Notification End -->
                    <div class="d-flex justify-content-end">
                        <form action="{{ route('admin.weekly-power-point.search') }}" method="post">
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
                            @foreach($weeklyPowerPoints as $weeklyPowerPoint)
                                <tr>
                                    <th scope="row">{{ number_format($weeklyPowerPoint->order) }}</th>
                                    <td>{{ $weeklyPowerPoint->name }}</td>
                                    <td>{{ number_format($weeklyPowerPoint->number_of_views) }}</td>
                                    <td>{{ number_format($weeklyPowerPoint->number_of_downloads) }}</td>
                                    <td>
                                        @if(isset($weeklyPowerPoint->file_path) && file_exists($weeklyPowerPoint->file_path))
                                            <a href="{{ asset($weeklyPowerPoint->file_path) }}" download="">Tải về</a>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="javascript:;" class="updateStatus"
                                           weeklyPowerPointStatusId="{{ $weeklyPowerPoint->id }}">
                                            @if ($weeklyPowerPoint->status == 1)
                                                <label class="switch">
                                                    <input type="checkbox"
                                                           id="weeklyPowerPointStatusInput{{ $weeklyPowerPoint->id }}"
                                                           weeklyPowerPointStatus="1" checked>
                                                    <span class="slider round"></span>
                                                </label>
                                            @else
                                                <label class="switch">
                                                    <input type="checkbox"
                                                           id="weeklyPowerPointStatusInput{{ $weeklyPowerPoint->id }}"
                                                           weeklyPowerPointStatus="0">
                                                    <span class="slider round"></span>
                                                </label>
                                            @endif
                                        </a>
                                    </td>
                                    <td>
                                        <a href="{{ route('admin.weekly-power-point.edit', ['id' => $weeklyPowerPoint->id]) }}"
                                           class="btn btn-outline-warning">
                                            <i class="fa-solid fa-pencil"></i>
                                        </a>
                                        <a weeklyPowerPointDeleteId="{{ $weeklyPowerPoint->id }}" href="javascript:;"
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
                            {{ $weeklyPowerPoints->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div><!--end row-->
@endsection
