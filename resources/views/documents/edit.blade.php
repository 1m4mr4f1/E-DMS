@extends('layouts.app')

@section('title', 'Edit Metadata Dokumen')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-slate-900 tracking-tight">Edit Metadata</h1>
            <p class="text-sm text-slate-500 mt-1">SOP-FIN-2026-001 (v1.0)</p>
        </div>
        <a href="#" class="text-sm font-medium text-slate-500 hover:text-slate-900 flex items-center gap-2">
            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 15L3 9m0 0l6-6M3 9h12a6 6 0 010 12h-3" /></svg>
            Batal
        </a>
    </div>

    <div class="bg-blue-50 border-l-4 border-blue-600 p-4 rounded-r-lg">
        <p class="text-sm text-blue-800">
            <strong>Catatan Audit:</strong> Pengubahan metadata ini akan tercatat dalam sistem Audit Log. Untuk mengganti isi file dokumen, silakan gunakan fitur <strong>Upload Revisi Baru</strong> agar versi lama tetap tersimpan.
        </p>
    </div>

    <form action="#" method="POST" class="bg-white border border-slate-200 rounded-xl shadow-sm p-8 space-y-6">
        @csrf
        @method('PUT') <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Judul Dokumen <span class="text-red-500">*</span></label>
                <input type="text" name="title" value="Standard Operating Procedure Keuangan" required class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-600 focus:border-transparent outline-none transition-all text-sm">
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1">Kategori <span class="text-red-500">*</span></label>
                <select name="category_id" required class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-600 focus:border-transparent outline-none transition-all text-sm bg-white">
                    <option value="1" selected>Standard Operating Procedure (SOP)</option>
                    <option value="2">Manual Book</option>
                </select>
            </div>
        </div>

        <div>
            <label class="block text-sm font-medium text-slate-700 mb-1">Deskripsi Ringkas</label>
            <textarea name="description" rows="4" class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-600 focus:border-transparent outline-none transition-all text-sm">Dokumen ini mengatur tata cara pencairan dana operasional untuk setiap divisi...</textarea>
        </div>

        <div class="pt-4 flex items-center justify-end gap-3 border-t border-slate-100">
            <button type="submit" class="px-5 py-2.5 text-sm font-semibold text-white bg-blue-600 rounded-lg hover:bg-blue-700 shadow transition-all">Update Metadata</button>
        </div>
    </form>
</div>
@endsection