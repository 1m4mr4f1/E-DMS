<header class="bg-white border-b border-slate-200 h-16 flex items-center justify-between px-8 sticky top-0 z-10 shadow-sm">
    
    <div class="flex items-center gap-3 text-sm text-slate-500">
        <button 
            onclick="history.back()" 
            class="hover:text-blue-600 text-slate-400 p-1.5 rounded-lg border border-slate-200 bg-white hover:bg-slate-50 transition-all shadow-sm group"
            title="Kembali">
            <svg class="h-4 w-4 transition-transform group-hover:-translate-x-0.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5" />
            </svg>
        </button>

        <div class="flex items-center gap-2 ml-1">
            <a href="{{ route('dashboard') }}" class="hover:text-blue-600 transition-colors">Pages</a>
            <span class="text-slate-300">/</span>
            <span class="font-bold text-slate-900 capitalize">
                {{ Request::segment(1) ? str_replace('-', ' ', Request::segment(1)) : 'Dashboard' }}
            </span>
        </div>
    </div>
    
    <div class="flex-1 max-w-lg mx-8 relative">
        <svg class="h-5 w-5 text-slate-400 absolute left-3 top-1/2 -translate-y-1/2" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
        </svg>
        <input type="text" placeholder="Cari dokumen, kategori, atau user..." 
            class="w-full pl-11 pr-4 py-2.5 rounded-xl border border-slate-200 bg-slate-50/50 focus:border-blue-600 focus:ring-4 focus:ring-blue-500/10 outline-none transition-all placeholder:text-slate-400 text-sm">
    </div>

    <div class="flex items-center gap-5">
        <div class="flex items-center gap-2 text-slate-400 border-r border-slate-200 pr-5">
            <button class="p-2 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-all relative">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 0 0 5.454-1.31A8.967 8.967 0 0 1 18 9.75V9A6 6 0 0 0 6 9v.75a8.967 8.967 0 0 1-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 0 1-5.714 0m5.714 0a3 3 0 1 1-5.714 0" />
                </svg>
                <span class="absolute top-2 right-2 h-2 w-2 bg-red-500 rounded-full border-2 border-white"></span>
            </button>
        </div>

        <div class="flex items-center gap-3 cursor-pointer group">
            <div class="text-right hidden sm:block">
                <p class="text-sm font-bold text-slate-900 leading-none">{{ auth()->user()->name }}</p>
                <p class="text-[10px] uppercase tracking-widest font-bold text-slate-400 mt-1">
                    {{ auth()->user()->getRoleNames()->first() ?? 'User' }}
                </p>
            </div>
            <div class="h-10 w-10 rounded-xl bg-slate-900 flex items-center justify-center text-white font-bold border-2 border-white shadow-md transition-transform group-hover:scale-105">
                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
            </div>
            <svg class="h-4 w-4 text-slate-400 group-hover:text-blue-600 transition-colors" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5" />
            </svg>
        </div>
    </div>
</header>