@extends('admin.layouts.admin')

@section('title')
    Tài khoản
@endsection

@section('css')

@endsection

@section('js')
    <!-- Update status -->
    <script>
        $(document).ready(function () {
            $(document).on('click', '.updateStatus', function () {
                var id = $(this).attr('adminUserStatusId');
                var status = $('#adminUserStatusInput' + id).attr('adminUserStatus');
                $.ajax({
                    type: 'post',
                    url: '/admin/admin-user/update-status',
                    data: {id: id, status: status},
                    success: function (response) {
                        if (response != null) {
                            if (response['status'] === 1) {
                                $('#adminUserStatusInput' + response['id']).attr('adminUserStatus', 1);
                            } else {
                                $('#adminUserStatusInput' + response['id']).attr('adminUserStatus', 0);
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
                var id = $(this).attr('adminUserDeleteId');
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
                        window.location.href = "/admin/admin-user/delete/" + id;
                    }
                })
            })
        })
    </script>
@endsection

@section('content')
    @include('admin.partials.page-breadcrumb', ['key' => 'Tài khoản', 'name' => 'Index'])

    <div class="row">
        <div class="col-12 mx-auto">
            <div class="d-flex justify-content-end">
                <a href="{{ route('admin.admin-user.create') }}" class="btn btn-outline-success px-3"><i
                        class="fa-solid fa-circle-plus"></i> Thêm</a>
            </div>
            <hr/>
            <div class="card">
                <div class="card-body">
                    <!-- Notification Start -->
                @include('admin.partials.flash-message', ['name_success' => 'admin_user_delete_success_message', 'name_error' => 'admin_user_delete_error_message'])
                <!-- Notification End -->
                    <div class="d-flex justify-content-end">
                        <form action="{{ route('admin.admin-user.search') }}" method="post">
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
                                <th scope="col">Tên</th>
                                <th scope="col">Email</th>
                                <th scope="col">Loại</th>
                                <th scope="col">Trạng thái</th>
                                <th scope="col">Hành động</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($adminUsers as $adminUser)
                                <tr>
                                    <td>{{ $adminUser->name }}</td>
                                    <td>{{ $adminUser->email }}</td>
                                    <td>{{ $adminUser->type }}</td>
                                    <td>
                                        @if ($adminUser->email != $adminUserCurrently->email)
                                            <a href="javascript:;" class="updateStatus"
                                               adminUserStatusId="{{ $adminUser->id }}">
                                                @if ($adminUser->status == 1)
                                                    <label class="switch">
                                                        <input type="checkbox"
                                                               id="adminUserStatusInput{{ $adminUser->id }}"
                                                               adminUserStatus="1" checked>
                                                        <span class="slider round"></span>
                                                    </label>
                                                @else
                                                    <label class="switch">
                                                        <input type="checkbox"
                                                               id="adminUserStatusInput{{ $adminUser->id }}"
                                                               adminUserStatus="0">
                                                        <span class="slider round"></span>
                                                    </label>
                                                @endif
                                            </a>
                                        @else
                                            <span>Hoạt động</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($adminUser->email != $adminUserCurrently->email)
                                            <a adminUserDeleteId="{{ $adminUser->id }}" href="javascript:;"
                                               class="btn btn-outline-danger confirmDelete">
                                                <i class="fa-solid fa-trash-can"></i>
                                            </a>
                                        @endif
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
                            {{ $adminUsers->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div><!--end row-->
@endsection
