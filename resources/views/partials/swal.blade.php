@foreach(['success', 'error', 'warning', 'info'] as $type)
    @if(session("swal_{$type}"))
        <script>
            Swal.fire({
                icon: '{{ $type }}',
                title: '{{ $type === 'success' ? 'Berhasil' : ($type === 'error' ? 'Gagal' : 'Informasi') }}',
                text: @json(session("swal_{$type}")),
                confirmButtonColor: '#0B6477'
            });
        </script>
    @endif
@endforeach
