@extends('admin.layouts.admin')

@section('title')
    Mạng xã hội
@endsection

@section('css')

@endsection

@section('js')
    <!-- Update status -->
    <script>
        $(document).ready(function () {
            $(document).on('click', '.updateStatus', function () {
                var id = $(this).attr('socialNetworkStatusId');
                var status = $('#socialNetworkStatusInput' + id).attr('socialNetworkStatus');
                $.ajax({
                    type: 'post',
                    url: '/admin/social-network/update-status',
                    data: {id: id, status: status},
                    success: function (response) {
                        if (response != null) {
                            if (response['status'] === 1) {
                                $('#socialNetworkStatusInput' + response['id']).attr('socialNetworkStatus', 1);
                            } else {
                                $('#socialNetworkStatusInput' + response['id']).attr('socialNetworkStatus', 0);
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
                var id = $(this).attr('socialNetworkDeleteId');
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
                        window.location.href = "/admin/social-network/delete/" + id;
                    }
                })
            })
        })
    </script>
@endsection

@section('content')
    @include('admin.partials.page-breadcrumb', ['key' => 'Mạng xã hội', 'name' => 'Index'])

    <div class="row">
        <div class="col-12 mx-auto">
            <div class="d-flex justify-content-end">
                <a href="{{ route('admin.social-network.create') }}" class="btn btn-outline-success px-3"><i
                        class="fa-solid fa-circle-plus"></i> Thêm</a>
            </div>
            <hr/>
            <div class="card">
                <div class="card-body">
                    <!-- Notification Start -->
                @include('admin.partials.flash-message', ['name_success' => 'social_network_delete_success_message', 'name_error' => 'social_network_delete_error_message'])
                <!-- Notification End -->
                    <div class="d-flex justify-content-end">
                        <form action="{{ route('admin.social-network.search') }}" method="post">
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
                                <th scope="col">Icon</th>
                                <th scope="col">Link</th>
                                <th scope="col">Trạng thái</th>
                                <th scope="col">Hành động</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($socialNetworks as $socialNetwork)
                                <tr>
                                    <th scope="row">{{ number_format($socialNetwork->order) }}</th>
                                    <td>{!! $socialNetwork->icon !!}</td>
                                    <td>
                                        <a href="{{ $socialNetwork->link }}" target="_blank">Truy cập</a>
                                    </td>
                                    <td>
                                        <a href="javascript:;" class="updateStatus"
                                           socialNetworkStatusId="{{ $socialNetwork->id }}">
                                            @if ($socialNetwork->status == 1)
                                                <label class="switch">
                                                    <input type="checkbox"
                                                           id="socialNetworkStatusInput{{ $socialNetwork->id }}"
                                                           socialNetworkStatus="1" checked>
                                                    <span class="slider round"></span>
                                                </label>
                                            @else
                                                <label class="switch">
                                                    <input type="checkbox"
                                                           id="socialNetworkStatusInput{{ $socialNetwork->id }}"
                                                           socialNetworkStatus="0">
                                                    <span class="slider round"></span>
                                                </label>
                                            @endif
                                        </a>
                                    </td>
                                    <td>
                                        <a href="{{ route('admin.social-network.edit', ['id' => $socialNetwork->id]) }}"
                                           class="btn btn-outline-warning">
                                            <i class="fa-solid fa-pencil"></i>
                                        </a>
                                        <a socialNetworkDeleteId="{{ $socialNetwork->id }}" href="javascript:;"
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
                            {{ $socialNetworks->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div><!--end row-->
@endsection
