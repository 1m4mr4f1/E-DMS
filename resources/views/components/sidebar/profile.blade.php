<div class="space-y-1">
    
    <a href="{{ route('profile.edit') }}" 
       class="flex items-center gap-2.5 px-3 py-1.5 rounded-lg transition-all text-xs font-semibold {{ request()->routeIs('profile.edit') ? 'bg-slate-900 text-white border border-slate-800' : 'text-slate-400 hover:bg-slate-900 hover:text-slate-100' }}">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4 shrink-0 {{ request()->routeIs('profile.edit') ? 'text-blue-500' : 'text-slate-500' }}">
            <path stroke-linecap="round" stroke-linejoin="round" d="M9.594 3.94c.09-.542.56-.94 1.11-.94h2.593c.55 0 1.02.398 1.11.94l.213 1.281c.063.374.313.686.645.87.074.04.147.083.22.127.324.196.72.257 1.075.124l1.217-.456a1.125 1.125 0 0 1 1.37.49l1.296 2.247a1.125 1.125 0 0 1-.26 1.43l-1.003.767a1.123 1.123 0 0 0-.417 1.03c.004.074.006.148.006.222 0 .074-.002.148-.006.222a1.123 1.123 0 0 0 .417 1.03l1.003.767a1.125 1.125 0 0 1 .26 1.43l-1.296 2.247a1.125 1.125 0 0 1-1.37.49l-1.216-.456a1.125 1.125 0 0 0-1.075.124c-.073.044-.146.087-.22.128-.332.183-.582.495-.645.869l-.213 1.281c-.09.543-.56.94-1.11.94h-2.594c-.55 0-1.019-.398-1.11-.94l-.213-1.281a1.125 1.125 0 0 0-.646-.869c-.074-.041-.147-.084-.22-.129a1.125 1.125 0 0 0-1.075-.124l-1.216.456a1.125 1.125 0 0 1-1.37-.49l-1.296-2.247a1.125 1.125 0 0 1 .26-1.43l1.003-.767a1.122 1.122 0 0 0 .417-1.03c-.004-.074-.006-.148-.006-.222 0-.074.002-.148.006-.222a1.122 1.122 0 0 0-.417-1.03l-1.003-.767a1.125 1.125 0 0 1-.26-1.43l1.296-2.247a1.125 1.125 0 0 1 1.37-.49l1.216.456c.356.133.751.072 1.076-.124.072-.041.146-.084.218-.128.333-.183.582-.495.646-.869l.214-1.28Z" />
            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
        </svg>
        <span>Account Settings</span>
    </a>
    
    <form action="{{ route('logout') }}" method="POST" class="pt-1">
        @csrf
        <button class="w-full flex items-center gap-2.5 px-3 py-1.5 rounded-lg text-rose-400 hover:bg-rose-500/10 transition-all text-xs font-semibold">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4 shrink-0">
                <path stroke-linecap="round" stroke-linejoin="round" d="M5.636 5.636a9 9 0 1 0 12.728 0M12 3v9" />
            </svg>
            <span>Terminate Session</span>
        </button>
    </form>

    <div class="bg-slate-900/80 p-2.5 rounded-lg flex items-center gap-2.5 mt-3 border border-slate-900 shadow-inner">
        @if(auth()->user()->avatar_url)
            <img src="{{ asset('storage/' . auth()->user()->avatar_url) }}" class="h-8 w-8 rounded object-cover border border-slate-700 shrink-0" />
        @else
            <div class="h-8 w-8 rounded bg-slate-800 flex items-center justify-center text-slate-200 font-bold text-xs border border-slate-700 shrink-0 select-none">
                {{ strtoupper(substr(auth()->user()->full_name ?? auth()->user()->name, 0, 1)) }}
            </div>
        @endif
        <div class="overflow-hidden min-w-0">
            <p class="text-xs font-bold text-slate-200 leading-none truncate" title="{{ auth()->user()->full_name ?? auth()->user()->name }}">
                {{ auth()->user()->full_name ?? auth()->user()->name }}
            </p>
            <p class="text-[9px] text-slate-500 mt-1 uppercase font-extrabold tracking-wider truncate">
                {{ auth()->user()->role_id == 1 ? 'Super Admin' : (auth()->user()->role_id == 2 ? 'Manager' : 'Corporate User') }}
            </p>
        </div>
    </div>
</div>