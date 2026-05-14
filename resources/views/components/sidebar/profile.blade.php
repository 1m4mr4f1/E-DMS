<div class="p-4 border-t border-slate-900 space-y-1 mb-2">
    <a href="#" class="flex items-center gap-3 px-4 py-2.5 rounded-xl hover:bg-slate-900 hover:text-slate-100 transition-all text-sm font-medium">Settings</a>
    
    <form action="{{ route('logout') }}" method="POST" class="mt-4">
        @csrf
        <button class="w-full flex items-center gap-3 px-4 py-2.5 rounded-xl text-red-400 hover:bg-red-500/10 transition-all text-sm font-medium">
            Logout
        </button>
    </form>

    <div class="bg-slate-900 p-4 rounded-xl flex items-center gap-3 mt-4 border border-slate-800">
        <div class="h-10 w-10 rounded-full bg-slate-800 flex items-center justify-center text-white font-bold border-2 border-slate-700">
            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
        </div>
        <div class="overflow-hidden">
            <p class="text-sm font-semibold text-white leading-none truncate">{{ auth()->user()->name }}</p>
            <p class="text-[10px] text-slate-500 mt-1 uppercase font-bold tracking-wider">
                {{ auth()->user()->roles->first()->name ?? 'User' }}
            </p>
        </div>
    </div>
</div>