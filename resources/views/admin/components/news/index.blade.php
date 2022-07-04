@extends('admin.layouts.admin')

@section('title')
    Tin tức
@endsection

@section('css')

@endsection

@section('js')
    <!-- Update status -->
    <script>
        $(document).ready(function () {
            $(document).on('click', '.updateStatus', function () {
                var id = $(this).attr('newsStatusId');
                var status = $('#newsStatusInput' + id).attr('newsStatus');
                $.ajax({
                    type: 'post',
                    url: '/admin/news/update-status',
                    data: {id: id, status: status},
                    success: function (response) {
                        if (response != null) {
                            if (response['status'] === 1) {
                                $('#newsStatusInput' + response['id']).attr('newsStatus', 1);
                            } else {
                                $('#newsStatusInput' + response['id']).attr('newsStatus', 0);
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
                var id = $(this).attr('newsDeleteId');
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
                        window.location.href = "/admin/news/delete/" + id;
                    }
                })
            })
        })
    </script>
@endsection

@section('content')
    @include('admin.partials.page-breadcrumb', ['key' => 'Tin tức', 'name' => 'Index'])

    <div class="row">
        <div class="col-12 mx-auto">
            <div class="d-flex justify-content-end">
                <a href="{{ route('admin.news.create') }}" class="btn btn-outline-success px-3"><i
                        class="fa-solid fa-circle-plus"></i> Thêm</a>
            </div>
            <hr/>
            <div class="card">
                <div class="card-body">
                    <!-- Notification Start -->
                @include('admin.partials.flash-message', ['name_success' => 'news_delete_success_message', 'name_error' => 'news_delete_error_message'])
                <!-- Notification End -->
                    <div class="d-flex justify-content-end">
                        <form action="{{ route('admin.news.search') }}" method="post">
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
                            @foreach($listNews as $news)
                                <tr>
                                    <td>{{ $news->title }}</td>
                                    <td>{{ $news->admin_user->name }}</td>
                                    <td>{{ number_format($news->number_of_views) }}</td>
                                    <td>
                                        @if (isset($news->image_path) && file_exists($news->image_path))
                                            <img style="width: 150px;"
                                                 src="{{ asset($news->image_path) }}"
                                                 alt="{{ $news->image_name }}">
                                        @endif
                                    </td>
                                    <td>{{ $news->created_at }}</td>
                                    <td>
                                        <a href="javascript:;" class="updateStatus"
                                           newsStatusId="{{ $news->id }}">
                                            @if ($news->status == 1)
                                                <label class="switch">
                                                    <input type="checkbox"
                                                           id="newsStatusInput{{ $news->id }}"
                                                           newsStatus="1" checked>
                                                    <span class="slider round"></span>
                                                </label>
                                            @else
                                                <label class="switch">
                                                    <input type="checkbox"
                                                           id="newsStatusInput{{ $news->id }}"
                                                           newsStatus="0">
                                                    <span class="slider round"></span>
                                                </label>
                                            @endif
                                        </a>
                                    </td>
                                    <td>
                                        <a href="{{ route('admin.news.edit', ['id' => $news->id]) }}"
                                           class="btn btn-outline-warning">
                                            <i class="fa-solid fa-pencil"></i>
                                        </a>
                                        <a newsDeleteId="{{ $news->id }}" href="javascript:;"
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
                            {{ $listNews->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div><!--end row-->
@endsection
