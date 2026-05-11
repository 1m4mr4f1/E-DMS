@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-bold text-slate-900">Statistik Sistem</h1>
        <button class="bg-blue-600 text-white px-4 py-2 rounded-lg text-sm font-semibold">+ Upload Dokumen</button>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm">
            <p class="text-sm text-slate-500 font-medium">Total Dokumen</p>
            <p class="text-3xl font-bold mt-2">1,204</p>
        </div>
        <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm">
            <p class="text-sm text-slate-500 font-medium">Menunggu Persetujuan</p>
            <p class="text-3xl font-bold mt-2 text-orange-600">8</p>
        </div>
        <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm">
            <p class="text-sm text-slate-500 font-medium">Direview Hari Ini</p>
            <p class="text-3xl font-bold mt-2 text-blue-600">24</p>
        </div>
        <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm">
            <p class="text-sm text-slate-500 font-medium">Penyimpanan Terpakai</p>
            <p class="text-3xl font-bold mt-2">4.2 GB</p>
        </div>
    </div>
</div>
@endsection