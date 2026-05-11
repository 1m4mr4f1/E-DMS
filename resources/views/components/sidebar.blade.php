<aside class="w-64 bg-slate-950 text-slate-400 flex-shrink-0 flex flex-col min-h-screen">
    <div class="p-6 flex items-center gap-3">
        <div class="h-8 w-8 bg-blue-600 rounded-lg flex items-center justify-center text-white font-bold text-xl">E</div>
        <span class="text-white font-bold tracking-tight text-lg">E-DMS Portal</span>
    </div>

    <nav class="flex-1 px-4 py-4 space-y-1">
        <p class="text-[10px] font-bold text-slate-600 uppercase tracking-widest px-4 mb-2">Main Menu</p>
        <a href="/dashboard" class="flex items-center gap-3 px-4 py-2.5 rounded-xl transition-all {{ request()->is('dashboard') ? 'bg-blue-600 text-white' : 'hover:bg-slate-900 hover:text-slate-100' }}">
            <span class="text-sm font-medium">Dashboard</span>
        </a>
        <a href="#" class="flex items-center gap-3 px-4 py-2.5 rounded-xl hover:bg-slate-900 hover:text-slate-100 transition-all text-sm font-medium">
            Documents
        </a>
        
        <p class="text-[10px] font-bold text-slate-600 uppercase tracking-widest px-4 mt-6 mb-2">Workflows</p>
        <a href="#" class="flex items-center gap-3 px-4 py-2.5 rounded-xl hover:bg-slate-900 hover:text-slate-100 transition-all text-sm font-medium">
            Approvals
        </a>
        <a href="#" class="flex items-center gap-3 px-4 py-2.5 rounded-xl hover:bg-slate-900 hover:text-slate-100 transition-all text-sm font-medium">
            Audit Logs
        </a>
    </nav>

    <div class="p-4 border-t border-slate-900">
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button class="w-full flex items-center gap-3 px-4 py-2.5 rounded-xl text-red-400 hover:bg-red-500/10 transition-all text-sm font-medium">
                Logout
            </button>
        </form>
    </div>
</aside>