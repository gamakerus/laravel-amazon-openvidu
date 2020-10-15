@if ($message = Session::get('info'))
<script>
    $.notify({
    icon: "add_alert",
    message: "{{ $message }}"

    }, {
    type: 'info',
    timer: 1000,
    placement: {
        from: 'top',
        align: 'center'
    }
    });
</script>
@endif
