<div class="mb-4 px-1">
    <label class="text-[10px] font-bold text-slate-500 uppercase tracking-wider px-2 mb-1 block">Main Division</label>
    <div class="bg-slate-900/60 border border-slate-900 rounded-lg p-2 flex items-center justify-between cursor-pointer hover:border-slate-800 transition-all shadow-inner">
        <div class="flex items-center gap-2.5 min-w-0">
            <div class="h-7 w-7 bg-blue-600/10 rounded border border-blue-600/30 flex items-center justify-center text-blue-400 font-bold text-base shrink-0">
                G
            </div>
            <span class="text-slate-200 font-medium text-xs truncate">General Division</span>
        </div>
        <svg class="h-3.5 w-3.5 text-slate-500 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5" />
        </svg>
    </div>
</div>

<div class="space-y-0.5">
    <span class="px-3 text-[10px] font-bold uppercase tracking-wider text-slate-600 block mb-1.5">General Nodes</span>
    
    <a href="/dashboard" 
       class="flex items-center gap-2.5 px-3 py-2 rounded-lg transition-all text-xs font-semibold {{ request()->is('dashboard') ? 'bg-blue-600 text-white shadow-sm shadow-blue-600/10' : 'text-slate-400 hover:bg-slate-900 hover:text-slate-100' }}">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4 shrink-0">
            <path stroke-linecap="round" stroke-linejoin="round" d="m2.25 12 8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25" />
        </svg>
        <span>Dashboard Console</span>
    </a>

    <a href="{{ route('documents.index') }}" 
       class="flex items-center gap-2.5 px-3 py-2 rounded-lg transition-all text-xs font-semibold {{ request()->routeIs('documents.index') || request()->routeIs('documents.show') || request()->routeIs('documents.create') || request()->routeIs('documents.edit') ? 'bg-blue-600 text-white shadow-sm shadow-blue-600/10' : 'text-slate-400 hover:bg-slate-900 hover:text-slate-100' }}">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4 shrink-0">
            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12.75V12A9 9 0 0 1 12 3v0.75m0-0.75a9 9 0 0 1 9 9v0.75M2.25 12.75a9 9 0 0 0 9 9V21.75m0-0.75a9 9 0 0 0 9-9V12M12 9.75A2.25 2.25 0 1 1 9.75 12 2.25 2.25 0 0 1 12 9.75Z" />
        </svg>
        <span>Documents Master</span>
    </a>
</div>