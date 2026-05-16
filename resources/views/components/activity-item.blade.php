@props(['actor', 'document', 'action', 'timestamp'])

<div class="rounded-3xl border border-slate-200 bg-white p-5 shadow-sm">
    <div class="flex items-center justify-between gap-4">
        <div>
            <p class="text-sm font-semibold text-slate-900">{{ $actor }}</p>
            <p class="text-sm text-slate-500">{{ $action }} <span class="font-semibold text-slate-900">{{ $document }}</span></p>
        </div>
        <span class="text-xs uppercase tracking-[0.2em] text-slate-400">{{ $timestamp }}</span>
    </div>
</div>
