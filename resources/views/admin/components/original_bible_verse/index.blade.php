@extends('admin.layouts.admin')

@section('title')
    Câu gốc Kinh Thánh
@endsection

@section('css')

@endsection

@section('js')
    <!-- Update status -->
    <script>
        $(document).ready(function () {
            $(document).on('click', '.updateStatus', function () {
                var id = $(this).attr('originalBibleVerseStatusId');
                var status = $('#originalBibleVerseStatusInput' + id).attr('originalBibleVerseStatus');
                $.ajax({
                    type: 'post',
                    url: '/admin/original-bible-verse/update-status',
                    data: {id: id, status: status},
                    success: function (response) {
                        if (response != null) {
                            if (response['status'] === 1) {
                                $('#originalBibleVerseStatusInput' + response['id']).attr('originalBibleVerseStatus', 1);
                            } else {
                                $('#originalBibleVerseStatusInput' + response['id']).attr('originalBibleVerseStatus', 0);
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
                var id = $(this).attr('originalBibleVerseDeleteId');
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
                        window.location.href = "/admin/original-bible-verse/delete/" + id;
                    }
                })
            })
        })
    </script>
@endsection

@section('content')
    @include('admin.partials.page-breadcrumb', ['key' => 'Câu gốc Kinh Thánh', 'name' => 'Index'])

    <div class="row">
        <div class="col-12 mx-auto">
            <div class="d-flex justify-content-end">
                <a href="{{ route('admin.original-bible-verse.create') }}" class="btn btn-outline-success px-3"><i
                        class="fa-solid fa-circle-plus"></i> Thêm</a>
            </div>
            <hr/>
            <div class="card">
                <div class="card-body">
                    <!-- Notification Start -->
                @include('admin.partials.flash-message', ['name_success' => 'original_bible_verse_delete_success_message', 'name_error' => 'original_bible_verse_delete_error_message'])
                <!-- Notification End -->
                    <div class="d-flex justify-content-end">
                        <form action="{{ route('admin.original-bible-verse.search') }}" method="post">
                            @csrf
                            <div class="input-group mb-3">
                                <input type="text" class="form-control" name="search" placeholder="Nhập nội dung..." value="{{ \Illuminate\Support\Facades\Request::get('tim-kiem') }}">
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
                            @foreach($originalBibleVerses as $originalBibleVerse)
                                <tr>
                                    <td>{{ $originalBibleVerse->title }}</td>
                                    <td>{{ $originalBibleVerse->admin_user->name }}</td>
                                    <td>{{ number_format($originalBibleVerse->number_of_views) }}</td>
                                    <td>
                                        @if (isset($originalBibleVerse->image_path) && file_exists($originalBibleVerse->image_path))
                                            <img style="width: 150px;" src="{{ asset($originalBibleVerse->image_path) }}"
                                                 alt="{{ $originalBibleVerse->image_name }}">
                                        @endif
                                    </td>
                                    <td>{{ $originalBibleVerse->created_at }}</td>
                                    <td>
                                        <a href="javascript:;" class="updateStatus"
                                           originalBibleVerseStatusId="{{ $originalBibleVerse->id }}">
                                            @if ($originalBibleVerse->status == 1)
                                                <label class="switch">
                                                    <input type="checkbox"
                                                           id="originalBibleVerseStatusInput{{ $originalBibleVerse->id }}"
                                                           originalBibleVerseStatus="1" checked>
                                                    <span class="slider round"></span>
                                                </label>
                                            @else
                                                <label class="switch">
                                                    <input type="checkbox"
                                                           id="originalBibleVerseStatusInput{{ $originalBibleVerse->id }}"
                                                           originalBibleVerseStatus="0">
                                                    <span class="slider round"></span>
                                                </label>
                                            @endif
                                        </a>
                                    </td>
                                    <td>
                                        <a href="{{ route('admin.original-bible-verse.edit', ['id' => $originalBibleVerse->id]) }}" class="btn btn-outline-warning">
                                            <i class="fa-solid fa-pencil"></i>
                                        </a>
                                        <a originalBibleVerseDeleteId="{{ $originalBibleVerse->id }}" href="javascript:;" class="btn btn-outline-danger confirmDelete">
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
                            {{ $originalBibleVerses->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div><!--end row-->
@endsection
