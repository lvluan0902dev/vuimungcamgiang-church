@extends('admin.layouts.admin')

@section('title')
    Album - Hình ảnh - Hình ảnh
@endsection

@section('css')

@endsection

@section('js')
    <!-- Update status -->
    <script>
        $(document).ready(function () {
            $(document).on('click', '.updateStatus', function () {
                var id = $(this).attr('albumImageStatusId');
                var status = $('#albumImageStatusInput' + id).attr('albumImageStatus');
                $.ajax({
                    type: 'post',
                    url: '/admin/album/album-image-update-status',
                    data: {id: id, status: status},
                    success: function (response) {
                        if (response != null) {
                            if (response['status'] === 1) {
                                $('#albumImageStatusInput' + response['id']).attr('albumImageStatus', 1);
                            } else {
                                $('#albumImageStatusInput' + response['id']).attr('albumImageStatus', 0);
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
                var id = $(this).attr('albumImageDeleteId');
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
                        window.location.href = "/admin/album/album-image-delete/" + id;
                    }
                })
            })
        })
    </script>

    <!-- Field dynamic -->
    <script type="text/javascript">
        $(document).ready(function () {
            var maxField = 5; //Input fields increment limitation
            var addButton = $('.add_button'); //Add button selector
            var wrapper = $('.field_wrapper'); //Input field wrapper
            var fieldHTML = '<div><input class="form-control" type="file" name="image[]" accept="image/*"><a href="javascript:void(0);" class="remove_button btn btn-outline-danger mb-3" title="Xoá"><i class="fa-solid fa-circle-minus"></i></a></div>'; //New input field html
            var x = 1; //Initial field counter is 1

            //Once add button is clicked
            $(addButton).click(function () {
                //Check maximum number of input fields
                if (x < maxField) {
                    x++; //Increment field counter
                    $(wrapper).append(fieldHTML); //Add field html
                }
            });

            //Once remove button is clicked
            $(wrapper).on('click', '.remove_button', function (e) {
                e.preventDefault();
                $(this).parent('div').remove(); //Remove field html
                x--; //Decrement field counter
            });
        });
    </script>
@endsection

@section('content')
    @include('admin.partials.page-breadcrumb', ['key' => 'Album - Hình ảnh', 'name' => 'Index'])

    <div class="row">
        <div class="col-12 mx-auto">
            <!-- Add Album Image Start -->
            <div class="row">
                <div class="col-12 mx-auto">
                    <form action="{{ route('admin.album-image.store', ['album_id' => $album->id]) }}" method="post"
                          enctype="multipart/form-data">
                    @csrf
                    <!-- Notification Start -->
                    @include('admin.partials.flash-message', ['name_success' => 'album_image_add_success_message', 'name_error' => 'album_image_add_error_message'])
                    <!-- Notification End -->
                        <div class="card-body">
                            <div class="mb-3">
                                <label class="form-label">Hình ảnh <span class="required-input">*</span> <span
                                        class="form-text">(Tối đa: 5 ảnh)</span></label>
                                <div class="field_wrapper">
                                    <div>
                                        <input class="form-control" type="file" name="image[]" accept="image/*">
                                        <a href="javascript:void(0);"
                                           class="add_button btn btn-outline-success mb-3" title="Thêm"><i
                                                class="fa-solid fa-circle-plus"></i></a>
                                    </div>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label" for="status">Trạng thái</label>
                                <label class="switch">
                                    <input type="checkbox" id="status"
                                           name="status" {{ old('status') == 'on' ? 'checked' : '' }}>
                                    <span class="slider round"></span>
                                </label>
                            </div>
                        </div>
                        <div class="card-footer">
                            <button type="submit" class="btn btn-outline-primary px-3">Xác nhận</button>
                            <a href="{{ route('admin.album.index') }}" class="btn btn-outline-secondary px-3">Trở
                                lại</a>
                        </div>
                    </form>
                </div>
            </div>
            <!-- Add Album Image End -->
            <hr/>
            <h6 class="mb-0">Danh sách Hình ảnh của Album {{ $album->title }}</h6>
            <hr/>
            <div class="card">
                <div class="card-body">
                    <!-- Notification Start -->
                @include('admin.partials.flash-message', ['name_success' => 'album_image_delete_success_message', 'name_error' => 'album_image_delete_error_message'])
                <!-- Notification End -->
                    <div class="table-responsive">
                        <table class="table mb-0 table-striped">
                            <thead>
                            <tr>
                                <th scope="col">Hình ảnh</th>
                                <th scope="col">Trạng thái</th>
                                <th scope="col">Hành động</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($albumImages as $albumImage)
                                <tr>
                                    <td>
                                        @if (isset($albumImage->image_path) && file_exists($albumImage->image_path))
                                            <img style="width: 150px;"
                                                 src="{{ asset($albumImage->image_path) }}"
                                                 alt="{{ $albumImage->image_name }}">
                                        @endif
                                    </td>
                                    <td>
                                        <a href="javascript:;" class="updateStatus"
                                           albumImageStatusId="{{ $albumImage->id }}">
                                            @if ($albumImage->status == 1)
                                                <label class="switch">
                                                    <input type="checkbox"
                                                           id="albumImageStatusInput{{ $albumImage->id }}"
                                                           albumImageStatus="1" checked>
                                                    <span class="slider round"></span>
                                                </label>
                                            @else
                                                <label class="switch">
                                                    <input type="checkbox"
                                                           id="albumImageStatusInput{{ $albumImage->id }}"
                                                           albumImageStatus="0">
                                                    <span class="slider round"></span>
                                                </label>
                                            @endif
                                        </a>
                                    </td>
                                    <td>
                                        <a albumImageDeleteId="{{ $albumImage->id }}" href="javascript:;"
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
                            {{ $albumImages->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div><!--end row-->
@endsection
