<div class="mb-6 px-2">
    <label class="text-[10px] font-bold text-slate-600 uppercase tracking-widest px-2 mb-1 block">Main Division</label>
    <div class="bg-slate-900 border border-slate-800 rounded-xl p-3 flex items-center justify-between cursor-pointer">
        <div class="flex items-center gap-3">
            <div class="h-8 w-8 bg-blue-600/10 rounded-lg flex items-center justify-center text-blue-400 font-bold text-xl border border-blue-600/30">G</div>
            <span class="text-white font-medium text-sm">General Division</span>
        </div>
        <svg class="h-4 w-4 text-slate-500" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5" /></svg>
    </div>
</div>

<p class="text-[10px] font-bold text-slate-600 uppercase tracking-widest px-4 mb-2">General Documents</p>
<a href="/dashboard" class="flex items-center gap-3 px-4 py-2.5 rounded-xl transition-all {{ request()->is('dashboard') ? 'bg-blue-600 text-white' : 'hover:bg-slate-900 hover:text-slate-100' }}">
    <span class="text-sm font-medium">Dashboard</span>
</a>
<a href="{{ route('documents.index') }}" class="flex items-center gap-3 px-4 py-2.5 rounded-xl transition-all {{ request()->routeIs('documents.index') ? 'bg-blue-600 text-white' : 'hover:bg-slate-900 hover:text-slate-100' }}">
    <span class="text-sm font-medium">Documents</span>
</a>