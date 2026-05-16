@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="w-full space-y-4">
    
    <div class="rounded-xl bg-slate-900 p-6 text-white border border-slate-800 shadow-sm flex items-center justify-between">
        <div class="max-w-2xl">
            <h1 class="text-xl font-bold tracking-tight">Welcome back, {{ auth()->user()->full_name ?? auth()->user()->name }}.</h1>
            <p class="mt-1 text-xs text-slate-400">Review your division's core metrics, active inbound shares, and recent system transaction trails.</p>
        </div>
        <div class="hidden md:block text-right">
            <span class="text-[10px] font-bold font-mono uppercase tracking-widest text-slate-500 block">System Status</span>
            <span class="text-xs font-semibold text-emerald-400 flex items-center gap-1.5 mt-0.5 justify-end">
                <span class="h-2 w-2 rounded-full bg-emerald-500 animate-pulse"></span> Synchronized
            </span>
        </div>
    </div>

    <section class="grid gap-4 sm:grid-cols-2 xl:grid-cols-3">
        <x-stat-card title="Total Documents" :value="$stats['totalDocuments']" subtitle="Combined count of division-restricted and public records." />
        <x-stat-card title="New This Month" :value="$stats['newDocumentsThisMonth']" subtitle="Records ingested during the current operational billing cycle." />
        <x-stat-card title="Expiring Inbound Shares" :value="$stats['expiringSharedDocuments']" subtitle="External division assets shared with your team expiring within 7 days." />
    </section>

    <section class="grid gap-4 grid-cols-1 xl:grid-cols-3 items-start">
        
        <div class="xl:col-span-2 space-y-4">
            
            <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
                <div class="flex items-center justify-between gap-4 border-b border-slate-100 pb-3">
                    <div>
                        <h2 class="text-sm font-bold text-slate-900 tracking-tight">Recent System Transactions</h2>
                        <p class="text-[11px] text-slate-500 mt-0.5">Real-time tracking of file manipulations and repository modifications.</p>
                    </div>
                    <span class="inline-flex items-center px-2 py-0.5 rounded-full bg-blue-50 text-blue-700 font-bold border border-blue-100 text-[10px]">
                        {{ count($recentActivities) }} log entries
                    </span>
                </div>

                <div class="mt-4 space-y-2 max-h-[360px] overflow-y-auto pr-1">
                    @forelse($recentActivities as $activity)
                        <x-activity-item
                            :actor="$activity['actor']"
                            :document="$activity['document']"
                            :action="$activity['action']"
                            :timestamp="$activity['timestamp']"
                        />
                    @empty
                        <div class="rounded-lg border border-dashed border-slate-200 bg-slate-50 p-6 text-center text-xs text-slate-400">
                            No recent system activities recorded in this session log.
                        </div>
                    @endforelse
                </div>
            </div>

            <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
                <div class="border-b border-slate-100 pb-3">
                    <h2 class="text-sm font-bold text-slate-900 tracking-tight">Shared with My Division</h2>
                    <p class="text-[11px] text-slate-500 mt-0.5">Cross-divisional master files mapped directly onto your department workspace.</p>
                </div>

                <div class="mt-4 space-y-2">
                    @forelse($sharedWithMyDivision as $item)
                        <div class="rounded-lg border border-slate-200 bg-slate-50/70 p-3 hover:bg-slate-50 transition-colors">
                            <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
                                <div class="min-w-0">
                                    <p class="text-xs font-bold text-slate-900 truncate">{{ $item['document_name'] }}</p>
                                    <p class="text-[11px] text-slate-500 mt-0.5">
                                        Shared by <span class="font-medium text-slate-700">{{ $item['shared_by'] }}</span> from <span class="font-medium text-slate-700">{{ $item['owner_division'] }}</span> division.
                                    </p>
                                </div>
                                <div class="flex flex-row sm:flex-col items-center sm:items-end justify-between sm:justify-center gap-1.5 shrink-0 text-[10px]">
                                    <span class="inline-flex px-2 py-0.5 font-bold rounded border bg-white border-slate-200 text-slate-600 uppercase tracking-wide">
                                        {{ $item['permission'] }}
                                    </span>
                                    <span class="text-slate-400 font-mono">Expires: {{ $item['expires_at'] }}</span>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="rounded-lg border border-dashed border-slate-200 bg-slate-50 p-6 text-center text-xs text-slate-400">
                            No inbound assets currently shared with your division console.
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        <aside class="space-y-4 lg:col-span-1">
            
            <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
                <h2 class="text-sm font-bold text-slate-900 tracking-tight border-b border-slate-100 pb-2">Quick Access Nodes</h2>
                <p class="text-[11px] text-slate-500 mt-1">High-frequency documentation links mapped based on your viewing history.</p>

                <div class="mt-4 space-y-2">
                    @forelse($quickAccess as $item)
                        <div class="rounded-lg border border-slate-200 bg-slate-50 p-3 flex flex-col justify-between gap-2.5">
                            <div class="min-w-0">
                                <p class="text-xs font-bold text-slate-900 truncate">{{ $item['document_name'] }}</p>
                                <p class="text-[10px] text-slate-400 font-mono mt-0.5">{{ $item['last_action'] }} · {{ $item['last_accessed_at'] }}</p>
                            </div>
                            <div class="flex">
                                <span class="inline-flex items-center rounded px-1.5 py-0.5 text-[9px] font-bold uppercase bg-blue-50 text-blue-700 border border-blue-100 tracking-wide">
                                    Accessed {{ $item['access_count'] }} times
                                </span>
                            </div>
                        </div>
                    @empty
                        <div class="rounded-lg border border-dashed border-slate-200 bg-slate-50 p-6 text-center text-xs text-slate-400">
                            No high-frequency indexed nodes discovered yet.
                        </div>
                    @endforelse
                </div>
            </div>

            <div class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
                <h2 class="text-sm font-bold text-slate-900 tracking-tight border-b border-slate-100 pb-2">Operational Guidelines</h2>
                <ul class="mt-3 space-y-2 text-[11px] text-slate-600 leading-relaxed">
                    <li class="flex items-start gap-2">
                        <span class="h-1.5 w-1.5 rounded-full bg-blue-600 mt-1.5 shrink-0"></span> 
                        <span>Repository scope is securely filtered to aggregate division-restricted and open public directories.</span>
                    </li>
                    <li class="flex items-start gap-2">
                        <span class="h-1.5 w-1.5 rounded-full bg-blue-600 mt-1.5 shrink-0"></span> 
                        <span>Audit incoming file parameters regularly to prevent cross-department workflow disruption.</span>
                    </li>
                    <li class="flex items-start gap-2">
                        <span class="h-1.5 w-1.5 rounded-full bg-blue-600 mt-1.5 shrink-0"></span> 
                        <span>Utilize the quick access indexing grid to skip manual folder navigation for high-priority documents.</span>
                    </li>
                </ul>
            </div>
        </aside>
    </section>
</div>
@endsection