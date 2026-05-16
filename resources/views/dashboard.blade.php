@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="space-y-8">
    <div class="bg-gradient-to-r from-blue-600 to-blue-900 p-8 rounded-2xl text-white flex items-center justify-between shadow-xl">
        <div class="max-w-xl">
            <h1 class="text-3xl font-bold leading-tight tracking-tight">Kelola Dokumen Enterprise dengan Keamanan Tinggi</h1>
            <p class="text-blue-200 mt-3 text-base">Sistem ini memantau setiap perubahan versi dan jejak audit secara otomatis.</p>
        </div>
        
        @hasanyrole('Admin|Manager|Editor')
        <button class="bg-white text-blue-700 px-6 py-3 rounded-xl font-semibold text-sm shadow hover:bg-slate-100 transition-all">
            + Upload Dokumen Baru
        </button>
        @endhasanyrole
    </div>

    <div class="bg-white p-8 rounded-2xl border border-slate-200 shadow-sm">
        <div class="flex items-center justify-between mb-8">
            <h2 class="text-xl font-bold text-slate-900 tracking-tight">Statistik Overview</h2>
            <div class="flex gap-2 text-sm">
                <span class="bg-slate-100 px-3 py-1 rounded-full text-slate-600 font-medium italic">
                    Izin Akses: {{ optional(auth()->user()->roles->first())->name ?? auth()->user()->roleRelation?->name ?? 'Belum ditetapkan' }}
                </span>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
            <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm flex items-start gap-4">
                <div class="h-12 w-12 bg-blue-600/10 rounded-xl flex items-center justify-center text-blue-600 border border-blue-600/20">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z"/></svg>
                </div>
                <div>
                    <p class="text-xs text-slate-500 font-bold uppercase tracking-wider">Total Dokumen</p>
                    <p class="text-2xl font-bold mt-1 text-slate-950">1,248</p>
                </div>
            </div>

            @hasanyrole('Admin|Manager')
            <div class="bg-white p-6 rounded-2xl border border-orange-100 shadow-sm flex items-start gap-4">
                <div class="h-12 w-12 bg-orange-600/10 rounded-xl flex items-center justify-center text-orange-600 border border-orange-600/20">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9 3.75h.008v.008H12v-.008Z"/></svg>
                </div>
                <div>
                    <p class="text-xs text-slate-500 font-bold uppercase tracking-wider">Pending Approval</p>
                    <p class="text-2xl font-bold mt-1 text-orange-600">12</p>
                </div>
            </div>
            @endhasanyrole

            @role('Admin')
            <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm flex items-start gap-4">
                <div class="h-12 w-12 bg-slate-600/10 rounded-xl flex items-center justify-center text-slate-600 border border-slate-600/20">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25" /></svg>
                </div>
                <div>
                    <p class="text-xs text-slate-500 font-bold uppercase tracking-wider">System Logs</p>
                    <p class="text-2xl font-bold mt-1 text-slate-950">342</p>
                </div>
            </div>
            @endrole
        </div>
    </div>
</div>
@endsection