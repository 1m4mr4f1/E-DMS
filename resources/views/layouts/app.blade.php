<!DOCTYPE html>
<html lang="id" class="h-full bg-slate-50">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - E-DMS</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="h-full antialiased text-slate-900">
    <div class="flex min-h-screen">
        @include('components.sidebar')

        <div class="flex-1 flex flex-col">
            @include('components.header')

            <main class="flex-1 p-8">
                @yield('content')
            </main>

            <footer class="px-8 py-4 bg-white border-t border-slate-200 text-center text-xs text-slate-400">
                &copy; {{ date('Y') }} Enterprise Document Management System | Internal Use Only v1.0
            </footer>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <script>
        const GlobalActions = {
            confirmDelete: function(e, form) {
                e.preventDefault();
                Swal.fire({
                    title: 'Apakah Anda Yakin?',
                    text: "Dokumen ini akan dipindahkan ke dalam Trash!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#ef4444',
                    cancelButtonColor: '#64748b',
                    confirmButtonText: 'Ya, Pindahkan!',
                    cancelButtonText: 'Batal',
                    reverseButtons: true,
                    customClass: { popup: 'rounded-3xl' }
                }).then((result) => {
                    if (result.isConfirmed) { 
                        form.submit(); 
                    }
                });
            }
        };

        // Otomatis menangkap semua flash message dari Laravel
        document.addEventListener("DOMContentLoaded", function() {
            @if(session('success'))
                Swal.fire({
                    icon: 'success', 
                    iconColor: '#10b981', 
                    title: 'Berhasil!',
                    text: '{!! session("success") !!}',
                    showConfirmButton: false, 
                    timer: 1500, 
                    customClass: { popup: 'rounded-3xl' }
                });
            @endif

            @if(session('error'))
                Swal.fire({
                    icon: 'error', 
                    iconColor: '#ef4444', 
                    title: 'Akses Ditolak!',
                    text: '{!! session("error") !!}',
                    showConfirmButton: true, 
                    confirmButtonColor: '#ef4444', 
                    customClass: { popup: 'rounded-3xl' }
                });
            @endif
        });
    </script>
</body>
</html>