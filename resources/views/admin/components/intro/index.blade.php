@extends('admin.layouts.admin')

@section('title')
    Đoạn giới thiệu
@endsection

@section('css')

@endsection

@section('js')
    <!-- Update status -->
    <script>
        $(document).ready(function () {
            $(document).on('click', '.updateStatus', function () {
                var id = $(this).attr('introStatusId');
                var status = $('#introStatusInput' + id).attr('introStatus');
                $.ajax({
                    type: 'post',
                    url: '/admin/intro/update-status',
                    data: {id: id, status: status},
                    success: function (response) {
                        if (response != null) {
                            if (response['status'] === 1) {
                                $('#introStatusInput' + response['id']).attr('introStatus', 1);
                            } else {
                                $('#introStatusInput' + response['id']).attr('introStatus', 0);
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
                var id = $(this).attr('introDeleteId');
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
                        window.location.href = "/admin/intro/delete/" + id;
                    }
                })
            })
        })
    </script>
@endsection

@section('content')
    @include('admin.partials.page-breadcrumb', ['key' => 'Đoạn giới thiệu', 'name' => 'Index'])

    <div class="row">
        <div class="col-12 mx-auto">
            <div class="d-flex justify-content-end">
                <a href="{{ route('admin.intro.create') }}" class="btn btn-outline-success px-3"><i
                        class="fa-solid fa-circle-plus"></i> Thêm</a>
            </div>
            <hr/>
            <div class="card">
                <div class="card-body">
                    <!-- Notification Start -->
                @include('admin.partials.flash-message', ['name_success' => 'intro_delete_success_message', 'name_error' => 'intro_delete_error_message'])
                <!-- Notification End -->
                    <div class="d-flex justify-content-end">
                        <form action="{{ route('admin.intro.search') }}" method="post">
                            @csrf
                            <div class="input-group mb-3">
                                <input type="text" class="form-control" name="search" placeholder="Nhập nội dung..."
                                       value="{{ \Illuminate\Support\Facades\Request::get('tim-kiem') }}">
                                <button class="btn btn-outline-secondary" type="submit">Tìm kiếm</button>
                            </div>
                        </form>
                    </div>
                    {{--                    <div class="table-responsive">--}}
                    <table class="table mb-0 table-striped">
                        <thead>
                        <tr>
                            <th scope="col">Thứ tự</th>
                            <th scope="col" width="75%">Nội dung</th>
                            <th scope="col">Trạng thái</th>
                            <th scope="col">Hành động</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($intros as $intro)
                            <tr>
                                <th scope="row">{{ number_format($intro->order) }}</th>
                                <td>{!! $intro->content !!}</td>
                                <td>
                                    <a href="javascript:;" class="updateStatus"
                                       introStatusId="{{ $intro->id }}">
                                        @if ($intro->status == 1)
                                            <label class="switch">
                                                <input type="checkbox"
                                                       id="introStatusInput{{ $intro->id }}"
                                                       introStatus="1" checked>
                                                <span class="slider round"></span>
                                            </label>
                                        @else
                                            <label class="switch">
                                                <input type="checkbox"
                                                       id="introStatusInput{{ $intro->id }}"
                                                       introStatus="0">
                                                <span class="slider round"></span>
                                            </label>
                                        @endif
                                    </a>
                                </td>
                                <td>
                                    <a href="{{ route('admin.intro.edit', ['id' => $intro->id]) }}"
                                       class="btn btn-outline-warning">
                                        <i class="fa-solid fa-pencil"></i>
                                    </a>
                                    <a introDeleteId="{{ $intro->id }}" href="javascript:;"
                                       class="btn btn-outline-danger confirmDelete">
                                        <i class="fa-solid fa-trash-can"></i>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                    {{--                    </div>--}}
                </div>
                <div class="card-footer">
                    <div class="row">
                        <div class="col-12 d-flex justify-content-center">
                            {{ $intros->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div><!--end row-->
@endsection
