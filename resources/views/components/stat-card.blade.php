@props(['title', 'value', 'subtitle' => null])

<div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">
    <div class="flex items-start justify-between gap-4">
        <div>
            <dt class="text-sm font-semibold uppercase tracking-[0.2em] text-slate-500">{{ $title }}</dt>
            <dd class="mt-4 text-3xl font-semibold text-slate-900">{{ $value }}</dd>
        </div>
    </div>

    @if($subtitle)
        <p class="mt-4 text-sm text-slate-500">{{ $subtitle }}</p>
    @endif
</div>
