<header class="bg-white border-b border-slate-200 h-14 flex items-center justify-between px-6 sticky top-0 z-10 shadow-sm">
    
    <div class="flex items-center gap-3 text-xs text-slate-500">
        <button 
            onclick="window.history.back()" 
            class="hover:text-blue-600 text-slate-400 p-1.5 rounded-lg border border-slate-200 bg-white hover:bg-slate-50 transition-all shadow-sm group"
            title="Back">
            <svg class="h-3.5 w-3.5 transition-transform group-hover:-translate-x-0.5" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5" />
            </svg>
        </button>

        <div class="flex items-center gap-1.5 ml-1 select-none">
            <a href="{{ route('dashboard') }}" class="hover:text-blue-600 transition-colors">Pages</a>
            <span class="text-slate-300">/</span>
            <span class="font-bold text-slate-900 capitalize">
                {{ Request::segment(1) ? str_replace('-', ' ', Request::segment(1)) : 'Dashboard' }}
            </span>
        </div>
    </div>
    
    <div class="flex-1 max-w-lg mx-6 relative hidden md:block">
        <svg class="h-4 w-4 text-slate-400 absolute left-3 top-1/2 -translate-y-1/2" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
        </svg>
        <input type="text" placeholder="Search infrastructure records, categories, or users..." 
            class="w-full pl-9 pr-4 py-1.5 rounded-lg border border-slate-200 bg-slate-50/50 focus:border-blue-500 focus:bg-white focus:ring-1 focus:ring-blue-500 outline-none transition-all placeholder:text-slate-400 text-xs">
    </div>

    <div class="flex items-center gap-4">
        <div class="flex items-center text-slate-400 border-r border-slate-200 pr-4">
            <button class="p-1.5 hover:text-blue-600 hover:bg-slate-50 rounded-lg transition-all relative">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 0 0 5.454-1.31A8.967 8.967 0 0 1 18 9.75V9A6 6 0 0 0 6 9v.75a8.967 8.967 0 0 1-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 0 1-5.714 0m5.714 0a3 3 0 1 1-5.714 0" />
                </svg>
                <span class="absolute top-1.5 right-1.5 h-1.5 w-1.5 bg-blue-600 rounded-full"></span>
            </button>
        </div>

        <div class="relative">
            <button id="top-profile-trigger" class="flex items-center gap-3 p-1 hover:bg-slate-50 rounded-xl transition-all border border-transparent hover:border-slate-200/60 select-none">
                <div class="text-right hidden sm:block pl-1">
                    <p class="text-xs font-bold text-slate-800 leading-none truncate max-w-[140px]">{{ auth()->user()->full_name ?? auth()->user()->name }}</p>
                    <p class="text-[9px] uppercase tracking-wider font-extrabold text-slate-400 mt-1">
                        {{ auth()->user()->role_id == 1 ? 'Super Admin' : (auth()->user()->role_id == 2 ? 'Manager' : 'Corporate User') }}
                    </p>
                </div>

                @if(auth()->user()->avatar_url)
                    <img src="{{ asset('storage/' . auth()->user()->avatar_url) }}" class="h-8 w-8 rounded-lg object-cover border border-slate-200 p-0.5 shadow-sm shrink-0" />
                @else
                    <div class="h-8 w-8 rounded-lg bg-slate-900 flex items-center justify-center text-white font-bold text-xs shadow-md shrink-0">
                        {{ strtoupper(substr(auth()->user()->full_name ?? auth()->user()->name, 0, 1)) }}
                    </div>
                @endif

                <svg class="h-3 w-3 text-slate-400 pr-0.5" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5" />
                </svg>
            </button>

            <div id="top-profile-dropdown" class="hidden absolute right-0 mt-2 w-56 rounded-xl border border-slate-200 bg-white p-1.5 shadow-lg z-30">
                <div class="px-3 py-2 border-b border-slate-100 mb-1">
                    <p class="text-xs font-bold text-slate-900 truncate">{{ auth()->user()->full_name ?? auth()->user()->name }}</p>
                    <p class="text-[10px] text-slate-400 truncate mt-0.5">{{ auth()->user()->email }}</p>
                </div>

                <a href="{{ route('profile.edit') }}" class="flex items-center gap-2 px-3 py-2 rounded-lg text-xs font-semibold text-slate-700 hover:bg-slate-50 hover:text-slate-900 transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4 text-slate-400">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
                    </svg>
                    <span>Account Settings</span>
                </a>

                <div class="border-t border-slate-100 my-1"></div>

                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="w-full flex items-center gap-2 px-3 py-2 rounded-lg text-xs font-semibold text-rose-600 hover:bg-rose-50 transition-colors text-left">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4 text-rose-500">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5.636 5.636a9 9 0 1 0 12.728 0M12 3v9" />
                        </svg>
                        <span>Terminate Session</span>
                    </button>
                </form>
            </div>
        </div>
    </div>
</header>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const trigger = document.getElementById('top-profile-trigger');
        const dropdown = document.getElementById('top-profile-dropdown');

        if (trigger && dropdown) {
            trigger.addEventListener('click', function (event) {
                event.stopPropagation();
                dropdown.classList.toggle('hidden');
            });

            document.addEventListener('click', function (event) {
                if (!trigger.contains(event.target) && !dropdown.contains(event.target)) {
                    dropdown.classList.add('hidden');
                }
            });
        }
    });
</script>