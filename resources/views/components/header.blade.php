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

    <div class="flex items-center gap-4">
        
        <div class="relative inline-block text-left border-r border-slate-200 pr-4" id="notification-dropdown-wrapper">
            @php
                $userId = auth()->id();
                // Hitung jumlah data belum terbaca dari tabel kustom
                $unreadCount = DB::table('notifications')->where('user_id', $userId)->where('is_read', false)->count();
                // Ambil 5 notifikasi teranyar
                $allNotifications = DB::table('notifications')->where('user_id', $userId)->orderByDesc('created_at')->limit(5)->get();
            @endphp

            <button type="button" id="notification-bell-btn" class="relative p-1.5 text-slate-400 hover:text-slate-600 hover:bg-slate-100 rounded-lg transition-all focus:outline-none">
                <span class="sr-only">View notifications</span>
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75v-.7V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 1 1-5.714 0" />
                </svg>

                @if($unreadCount > 0)
                    <span id="notification-badge" class="absolute top-1 right-1 inline-flex items-center justify-center px-1.5 py-0.5 text-[9px] font-bold leading-none text-white transform translate-x-1/2 -translate-y-1/2 bg-rose-600 rounded-full shadow-sm border border-white animate-pulse">
                        {{ $unreadCount }}
                    </span>
                @endif
            </button>

            <div id="notification-dropdown" class="hidden absolute right-0 mt-2 w-80 rounded-xl border border-slate-200 bg-white shadow-lg focus:outline-none z-50 origin-top-right transition-all">
                <div class="p-3 border-b border-slate-100 flex items-center justify-between bg-slate-50/50 rounded-t-xl">
                    <h3 class="text-xs font-bold text-slate-900 tracking-tight">System Notifications</h3>
                    @if($unreadCount > 0)
                        <form action="{{ route('notifications.read-all') }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" class="text-[10px] font-bold text-blue-600 hover:text-blue-700 hover:underline">
                                Mark all as read
                            </button>
                        </form>
                    @endif
                </div>

                <div class="max-h-64 overflow-y-auto divide-y divide-slate-100" id="notification-items-container">
                    @forelse($allNotifications as $notification)
                        <a href="{{ route('notifications.read', $notification->id) }}" 
                           class="block p-3 text-left transition-colors hover:bg-slate-50 {{ !$notification->is_read ? 'bg-blue-50/40 hover:bg-blue-50/70' : '' }}">
                            <div class="flex gap-2.5 items-start">
                                @if(!$notification->is_read)
                                    <span class="w-1.5 h-1.5 rounded-full bg-blue-600 mt-1.5 shrink-0"></span>
                                @else
                                    <span class="w-1.5 h-1.5 rounded-full bg-transparent mt-1.5 shrink-0"></span>
                                @endif
                                
                                <div class="space-y-0.5 min-w-0">
                                    <p class="text-xs font-bold text-slate-800 truncate">{{ $notification->title }}</p>
                                    <p class="text-[11px] text-slate-600 leading-relaxed break-words">{{ $notification->body }}</p>
                                    <p class="text-[9px] font-medium text-slate-400 mt-1">
                                        {{ \Carbon\Carbon::parse($notification->created_at)->diffForHumans() }}
                                    </p>
                                </div>
                            </div>
                        </a>
                    @empty
                        <div class="py-8 text-center text-slate-400">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-7 h-7 mx-auto mb-2 text-slate-300">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75v-.7V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0" />
                            </svg>
                            <p class="text-[11px] font-medium">No new notifications.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        <div class="relative" id="profile-dropdown-wrapper">
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
        const profileTrigger = document.getElementById('top-profile-trigger');
        const profileDropdown = document.getElementById('top-profile-dropdown');
        const profileWrapper = document.getElementById('profile-dropdown-wrapper');

        const notifTrigger = document.getElementById('notification-bell-btn');
        const notifDropdown = document.getElementById('notification-dropdown');
        const notifWrapper = document.getElementById('notification-dropdown-wrapper');

        if (profileTrigger && profileDropdown) {
            profileTrigger.addEventListener('click', function (e) {
                e.stopPropagation();
                profileDropdown.classList.toggle('hidden');
                if (notifDropdown) notifDropdown.classList.add('hidden');
            });
        }

        if (notifTrigger && notifDropdown) {
            notifTrigger.addEventListener('click', function (e) {
                e.stopPropagation();
                notifDropdown.classList.toggle('hidden');
                if (profileDropdown) profileDropdown.classList.add('hidden');
            });
        }

        document.addEventListener('click', function (e) {
            if (profileWrapper && !profileWrapper.contains(e.target) && profileDropdown) {
                profileDropdown.classList.add('hidden');
            }
            if (notifWrapper && !notifWrapper.contains(e.target) && notifDropdown) {
                notifDropdown.classList.add('hidden');
            }
        });
    });
</script>