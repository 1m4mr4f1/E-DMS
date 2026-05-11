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
                &copy; 2026 Enterprise Document Management System | Internal Use Only v1.0
            </footer>
        </div>
    </div>
</body>
</html>