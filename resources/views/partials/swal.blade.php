@foreach(['success', 'error', 'warning', 'info'] as $type)
    @if(session("swal_{$type}"))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const type = '{{ $type }}';
                const titleMap = {
                    'success': 'Transaksi Berhasil',
                    'error': 'Terjadi Galat',
                    'warning': 'Perhatian',
                    'info': 'Informasi Sistem'
                };
                
                Swal.fire({
                    icon: type,
                    title: titleMap[type] || 'Notifikasi',
                    text: @json(session("swal_{$type}")),
                    confirmButtonColor: '#0B6477',
                    confirmButtonText: 'Tutup',
                    customClass: {
                        popup: 'rounded-4 shadow-lg border-0',
                        title: 'fw-800 text-simrs-gray-900',
                        htmlContainer: 'text-muted small'
                    },
                    buttonsStyling: true,
                    showClass: {
                        popup: 'animate__animated animate__fadeInUp animate__faster'
                    },
                    hideClass: {
                        popup: 'animate__animated animate__fadeOutDown animate__faster'
                    }
                });
            });
        </script>
    @endif
@endforeach
