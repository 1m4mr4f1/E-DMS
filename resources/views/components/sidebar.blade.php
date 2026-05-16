<aside class="w-64 h-screen max-h-screen bg-slate-950 text-slate-400 flex-shrink-0 flex flex-col border-r border-slate-900 sticky top-0 z-20 overflow-hidden">
    
    <div class="p-4 flex items-center gap-2.5 border-b border-slate-900/60 shrink-0">
        <div class="h-8 w-8 bg-blue-600 rounded-lg flex items-center justify-center text-white font-extrabold text-xl shadow-sm shadow-blue-500/20">
            E
        </div>
        <div class="flex flex-col">
            <span class="text-white font-bold tracking-tight text-sm leading-none">E-DMS Portal</span>
            <span class="text-[10px] text-slate-500 font-medium mt-0.5 tracking-wider uppercase">Enterprise Edition</span>
        </div>
    </div>

    <nav class="flex-1 px-3 py-4 space-y-5 overflow-y-auto custom-sidebar-scrollbar select-none">
        
        <div class="space-y-1">
            @include('components.sidebar.general')
        </div>

        @if(auth()->user() && in_array(auth()->user()->role_id, [1, 2]))
            <div class="space-y-1 border-t border-slate-900/40 pt-3">
                <span class="px-3 text-[10px] font-bold uppercase tracking-wider text-slate-600 block mb-1.5">Workflow</span>
                @include('components.sidebar.workflow')
            </div>
        @endif

        @if(auth()->user() && auth()->user()->role_id == 1)
            <div class="space-y-1 border-t border-slate-900/40 pt-3">
                <span class="px-3 text-[10px] font-bold uppercase tracking-wider text-slate-600 block mb-1.5">Administration</span>
                @include('components.sidebar.admin')
            </div>
        @endif

    </nav>

    <div class="shrink-0 mt-auto bg-slate-950">
        @include('components.sidebar.profile')
    </div>

</aside>

<style>
    .custom-sidebar-scrollbar::-webkit-scrollbar {
        width: 4px;
    }
    .custom-sidebar-scrollbar::-webkit-scrollbar-track {
        background: transparent;
    }
    .custom-sidebar-scrollbar::-webkit-scrollbar-thumb {
        background: #1e293b;
        border-radius: 10px;
    }
    .custom-sidebar-scrollbar::-webkit-scrollbar-thumb:hover {
        background: #334155;
    }
</style>