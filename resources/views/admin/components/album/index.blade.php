@extends('admin.layouts.admin')

@section('title')
    Album
@endsection

@section('css')

@endsection

@section('js')
    <!-- Update status -->
    <script>
        $(document).ready(function () {
            $(document).on('click', '.updateStatus', function () {
                var id = $(this).attr('albumStatusId');
                var status = $('#albumStatusInput' + id).attr('albumStatus');
                $.ajax({
                    type: 'post',
                    url: '/admin/album/update-status',
                    data: {id: id, status: status},
                    success: function (response) {
                        if (response != null) {
                            if (response['status'] === 1) {
                                $('#albumStatusInput' + response['id']).attr('albumStatus', 1);
                            } else {
                                $('#albumStatusInput' + response['id']).attr('albumStatus', 0);
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
                var id = $(this).attr('albumDeleteId');
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
                        window.location.href = "/admin/album/delete/" + id;
                    }
                })
            })
        })
    </script>
@endsection

@section('content')
    @include('admin.partials.page-breadcrumb', ['key' => 'Album', 'name' => 'Index'])

    <div class="row">
        <div class="col-12 mx-auto">
            <div class="d-flex justify-content-end">
                <a href="{{ route('admin.album.create') }}" class="btn btn-outline-success px-3"><i
                        class="fa-solid fa-circle-plus"></i> Thêm</a>
            </div>
            <hr/>
            <div class="card">
                <div class="card-body">
                    <!-- Notification Start -->
                @include('admin.partials.flash-message', ['name_success' => 'album_delete_success_message', 'name_error' => 'album_delete_error_message'])
                <!-- Notification End -->
                    <div class="d-flex justify-content-end">
                        <form action="{{ route('admin.album.search') }}" method="post">
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
                                <th scope="col">Tiêu đề</th>
                                <th scope="col">Tác giả</th>
                                <th scope="col">Lượt xem</th>
                                <th scope="col">Hình ảnh</th>
                                <th scope="col">Thời gian thêm</th>
                                <th scope="col">Trạng thái</th>
                                <th scope="col">Hành động</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($albums as $album)
                                <tr>
                                    <td>{{ $album->title }}</td>
                                    <td>{{ $album->admin_user->name }}</td>
                                    <td>{{ number_format($album->number_of_views) }}</td>
                                    <td>
                                        @if (isset($album->image_path) && file_exists($album->image_path))
                                            <img style="width: 150px;"
                                                 src="{{ asset($album->image_path) }}"
                                                 alt="{{ $album->image_name }}">
                                        @endif
                                    </td>
                                    <td>{{ $album->created_at }}</td>
                                    <td>
                                        <a href="javascript:;" class="updateStatus"
                                           albumStatusId="{{ $album->id }}">
                                            @if ($album->status == 1)
                                                <label class="switch">
                                                    <input type="checkbox"
                                                           id="albumStatusInput{{ $album->id }}"
                                                           albumStatus="1" checked>
                                                    <span class="slider round"></span>
                                                </label>
                                            @else
                                                <label class="switch">
                                                    <input type="checkbox"
                                                           id="albumStatusInput{{ $album->id }}"
                                                           albumStatus="0">
                                                    <span class="slider round"></span>
                                                </label>
                                            @endif
                                        </a>
                                    </td>
                                    <td>
                                        <a href="{{ route('admin.album-image.index', ['album_id' => $album->id]) }}"
                                           class="btn btn-outline-info">
                                            <i class="fa-solid fa-file-image"></i>
                                        </a>
                                        <a href="{{ route('admin.album.edit', ['id' => $album->id]) }}"
                                           class="btn btn-outline-warning">
                                            <i class="fa-solid fa-pencil"></i>
                                        </a>
                                        <a albumDeleteId="{{ $album->id }}" href="javascript:;"
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
                            {{ $albums->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div><!--end row-->
@endsection
