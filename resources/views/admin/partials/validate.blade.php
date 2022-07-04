@error($name)
<div
    class="alert border-0 border-danger border-start border-4 bg-light-danger alert-dismissible fade show py-2">
    <div class="d-flex align-items-center">
        <div class="fs-3 text-danger"><i class="bi bi-x-circle-fill"></i>
        </div>
        <div class="ms-3">
            <div class="text-danger">{{$message}}</div>
        </div>
    </div>
    <button type="button" class="btn-close" data-bs-dismiss="alert"
            aria-label="Close"></button>
</div>
@enderror
