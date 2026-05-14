@extends('layouts.app')

@section('title', 'Detail Dokumen')

@section('content')
<div class="max-w-5xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <div class="flex items-center gap-3 mb-1">
                <span class="bg-blue-100 text-blue-700 px-2.5 py-0.5 rounded-full text-xs font-semibold border border-blue-200">SOP-FIN-2026-001</span>
                <span class="bg-green-100 text-green-700 px-2.5 py-0.5 rounded-full text-xs font-semibold border border-green-200">Published</span>
            </div>
            <h1 class="text-2xl font-bold text-slate-900 tracking-tight">Standard Operating Procedure Keuangan</h1>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('documents.index') }}" class="px-4 py-2 bg-white border border-slate-300 text-slate-700 rounded-lg text-sm font-medium hover:bg-slate-50">Kembali</a>
            <button class="px-4 py-2 bg-blue-600 text-white rounded-lg text-sm font-medium hover:bg-blue-700 shadow flex items-center gap-2">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3" /></svg>
                Download File
            </button>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="md:col-span-2 space-y-6">
            <div class="bg-white border border-slate-200 rounded-xl shadow-sm p-6">
                <h3 class="text-sm font-bold text-slate-900 uppercase tracking-wider mb-4 border-b border-slate-100 pb-2">Deskripsi Dokumen</h3>
                <p class="text-slate-600 text-sm leading-relaxed">
                    Dokumen ini mengatur tata cara pencairan dana operasional untuk setiap divisi, termasuk syarat kelengkapan nota dan persetujuan manajerial tingkat akhir.
                </p>
            </div>

            <div class="bg-slate-100 border border-slate-200 rounded-xl flex items-center justify-center h-96 text-slate-400">
                <div class="text-center">
                    <svg class="h-12 w-12 mx-auto mb-2 opacity-50" fill="currentColor" viewBox="0 0 24 24"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8l-6-6Zm-1 1.5L18.5 9H13V3.5ZM6 20V4h5v6h6v10H6Z"/></svg>
                    <p class="text-sm font-medium">Preview File PDF akan tampil di sini</p>
                </div>
            </div>
        </div>

        <div class="space-y-6">
            <div class="bg-white border border-slate-200 rounded-xl shadow-sm p-6">
                <h3 class="text-sm font-bold text-slate-900 uppercase tracking-wider mb-4 border-b border-slate-100 pb-2">Informasi Detail</h3>
                <ul class="space-y-4 text-sm">
                    <li>
                        <span class="block text-slate-500 mb-0.5">Versi Saat Ini</span>
                        <span class="font-medium text-slate-900">v1.0</span>
                    </li>
                    <li>
                        <span class="block text-slate-500 mb-0.5">Divisi</span>
                        <span class="font-medium text-slate-900">Finance</span>
                    </li>
                    <li>
                        <span class="block text-slate-500 mb-0.5">Kategori</span>
                        <span class="font-medium text-slate-900">Standard Operating Procedure</span>
                    </li>
                    <li>
                        <span class="block text-slate-500 mb-0.5">Diunggah Oleh</span>
                        <div class="flex items-center gap-2 mt-1">
                            <div class="h-6 w-6 bg-slate-800 rounded-full text-white flex items-center justify-center text-xs font-bold">R</div>
                            <span class="font-medium text-slate-900">Rafi Admin</span>
                        </div>
                    </li>
                    <li>
                        <span class="block text-slate-500 mb-0.5">Tanggal Upload</span>
                        <span class="font-medium text-slate-900">14 Mei 2026, 14:30 WIB</span>
                    </li>
                </ul>
            </div>

            <div class="bg-white border border-slate-200 rounded-xl shadow-sm p-4 space-y-2">
                <button class="w-full px-4 py-2 bg-orange-50 text-orange-700 border border-orange-200 rounded-lg text-sm font-medium hover:bg-orange-100 transition-colors">Ajukan Persetujuan (Approval)</button>
                <button class="w-full px-4 py-2 bg-slate-50 text-slate-700 border border-slate-200 rounded-lg text-sm font-medium hover:bg-slate-100 transition-colors">Edit Metadata</button>
            </div>
        </div>
    </div>
</div>
@endsection