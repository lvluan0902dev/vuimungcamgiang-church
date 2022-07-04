@if(\Illuminate\Support\Facades\Session::has($name_success))
    <div
        class="alert border-0 border-success border-start border-4 bg-light-success alert-dismissible fade show py-2">
        <div class="d-flex align-items-center">
            <div class="fs-3 text-success"><i class="bi bi-check-circle-fill"></i>
            </div>
            <div class="ms-3">
                <div
                    class="text-success">{{ \Illuminate\Support\Facades\Session::get($name_success) }}</div>
            </div>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif
@if(\Illuminate\Support\Facades\Session::has($name_error))
    <div
        class="alert border-0 border-danger border-start border-4 bg-light-danger alert-dismissible fade show py-2">
        <div class="d-flex align-items-center">
            <div class="fs-3 text-danger"><i class="bi bi-x-circle-fill"></i>
            </div>
            <div class="ms-3">
                <div
                    class="text-danger">{{ \Illuminate\Support\Facades\Session::get($name_error) }}</div>
            </div>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="alert"
                aria-label="Close"></button>
    </div>
@endif
