<header class="bg-white border-b border-slate-200 h-16 flex items-center justify-between px-8 sticky top-0 z-10">
    <div class="flex items-center gap-2 text-sm text-slate-500">
        <span class="hover:text-blue-600 cursor-pointer">E-DMS</span>
        <span>/</span>
        <span class="font-semibold text-slate-900">@yield('title', 'Dashboard')</span>
    </div>
    
    <div class="flex items-center gap-4">
        <div class="text-right hidden sm:block">
            <p class="text-sm font-semibold text-slate-900 leading-none">{{ auth()->user()->name }}</p>
            <p class="text-xs text-slate-500 mt-1">{{ auth()->user()->getRoleNames()->first() }}</p>
        </div>
        <div class="h-9 w-9 rounded-full bg-slate-900 flex items-center justify-center text-white font-bold border-2 border-slate-200">
            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
        </div>
    </div>
</header>