@push('scripts')

<script>
$(document).ready(function () {
    toastr.options = {
        "closeButton": true,
        "progressBar": true,
        "positionClass": "toast-top-right",
        "timeOut": "3000"
    };

    @if(session('success'))
        toastr.success(@json(session('success')));
    @endif

    @if(session('error'))
        toastr.error(@json(session('error')));
    @endif

    @if(session('info'))
        toastr.info(@json(session('info')));
    @endif

    @if(session('warning'))
        toastr.warning(@json(session('warning')));
    @endif
});
</script>
@endpush