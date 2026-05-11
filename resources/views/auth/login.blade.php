<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Portal - E-DMS</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>body { font-family: 'Inter', sans-serif; }</style>
</head>
<body class="h-full bg-white antialiased">
    
    <div class="flex min-h-full">
        
        <div class="hidden lg:flex lg:w-1/2 lg:flex-col lg:justify-between bg-gradient-to-b from-slate-950 via-slate-900 to-blue-950 p-16 text-slate-100">
            
            <div class="flex items-center gap-3">
                <span class="font-extrabold text-xl tracking-tight">E-DMS PORTAL</span>
            </div>

            <div class="max-w-xl">
                <svg class="h-16 w-16 text-slate-700 opacity-80 mb-10" fill="currentColor" viewBox="0 0 32 32">
                    <path d="M10 8c-2.2 0-4 1.8-4 4s1.8 4 4 4c.6 0 1.1-.1 1.6-.4.2.4.4.9.4 1.4v2H6v2h6v-2c0-2.2-1.8-4-4-4s-1.8-4 4-4zm12 0c-2.2 0-4 1.8-4 4s1.8 4 4 4c.6 0 1.1-.1 1.6-.4.2.4.4.9.4 1.4v2h-6v2h6v-2c0-2.2-1.8-4-4-4s-1.8-4 4-4z"/>
                </svg>
                
                <h1 class="text-4xl font-bold leading-tight tracking-tight mb-6">
                    Keteraturan adalah kunci dari efisiensi. Kelola setiap dokumen Anda dengan cerdas dan aman.
                </h1>
                
                <p class="text-lg text-slate-400 font-medium">
                    — Penulis Kuote
                </p>
            </div>

            <p class="text-sm text-slate-600">
                © 2026 Internal Use Only v1.0
            </p>
        </div>

        <div class="flex flex-1 flex-col justify-center px-6 py-12 lg:flex-none lg:w-1/2 lg:px-20 xl:px-24">
            <div class="mx-auto w-full max-w-sm">
                
                <div class="lg:hidden text-center mb-10 border-b border-slate-100 pb-8">
                    <h1 class="font-extrabold text-2xl text-slate-950 tracking-tight">E-DMS Portal</h1>
                </div>

                <div class="mb-10 text-center lg:text-left">
                    <h2 class="text-3xl font-bold tracking-tight text-slate-950">
                        Selamat Datang Kembali
                    </h2>
                    <p class="mt-2 text-base text-slate-600">
                        Silakan masuk ke akun Anda
                    </p>
                </div>

                <div class="mt-10">
                    <form action="{{ route('login.store') }}" method="POST" class="space-y-6">
                        @csrf
                        
                        <div>
                            <label for="email" class="block text-sm font-semibold leading-6 text-slate-900">
                                Alamat Email
                            </label>
                            <div class="mt-2.5">
                                <input id="email" name="email" type="email" value="{{ old('email') }}" required 
                                    class="block w-full rounded-xl border border-slate-200 px-4 py-3 text-slate-900 shadow-sm outline-none transition-all placeholder:text-slate-400 focus:border-blue-600 focus:ring-2 focus:ring-blue-100 sm:text-sm sm:leading-6"
                                    placeholder="nama@perusahaan.com">
                                @error('email') <p class="text-red-500 text-xs mt-1.5">{{ $message }}</p> @enderror
                            </div>
                        </div>

                        <div>
                            <div class="flex items-center justify-between">
                                <label for="password" class="block text-sm font-semibold leading-6 text-slate-900">
                                    Kata Sandi
                                </label>
                                <div class="text-sm">
                                    <a href="#" class="font-medium text-blue-600 hover:text-blue-500 hover:underline">
                                        Lupa password?
                                    </a>
                                </div>
                            </div>
                            <div class="mt-2.5">
                                <input id="password" name="password" type="password" required 
                                    class="block w-full rounded-xl border border-slate-200 px-4 py-3 text-slate-900 shadow-sm outline-none transition-all placeholder:text-slate-400 focus:border-blue-600 focus:ring-2 focus:ring-blue-100 sm:text-sm sm:leading-6"
                                    placeholder="••••••••">
                            </div>
                        </div>

                        <div class="flex items-center">
                            <input id="remember" name="remember" type="checkbox" 
                                class="h-4 w-4 rounded border-slate-300 text-blue-600 focus:ring-blue-500">
                            <label for="remember" class="ml-2.5 block text-sm text-slate-600">
                                Ingat saya
                            </label>
                        </div>

                        <div>
                            <button type="submit" 
                                class="flex w-full justify-center rounded-xl bg-blue-600 px-4 py-3 text-sm font-semibold text-white shadow hover:bg-blue-700 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-600 transition-colors active:scale-[0.98]">
                                Masuk ke Sistem
                            </button>
                        </div>

                    </form>
                    
                    </div>
            </div>
        </div>

    </div>

</body>
</html>