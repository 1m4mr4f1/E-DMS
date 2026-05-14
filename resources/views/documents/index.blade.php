@extends('layouts.app')

@section('title', 'Documents')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-900 tracking-tight">Ruang Dokumen</h1>
            <p class="text-sm text-slate-500 mt-1">Daftar berkas divisi {{ auth()->user()->division }}.</p>
        </div>
        <a href="{{ route('documents.create') }}" class="bg-blue-600 text-white px-5 py-2.5 rounded-xl text-sm font-semibold shadow-sm hover:bg-blue-700 transition-all flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Upload Baru
        </a>
    </div>

    <div class="bg-white border border-slate-200 rounded-2xl shadow-sm overflow-hidden flex flex-col">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="bg-slate-50/50 text-slate-500 font-semibold border-b border-slate-200">
                    <tr>
                        <th class="px-6 py-4">Dokumen</th>
                        <th class="px-6 py-4">Keterangan</th>
                        <th class="px-6 py-4 text-center">Divisi</th>
                        <th class="px-6 py-4 text-center">Visibilitas</th>
                        <th class="px-6 py-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($documents as $doc)
                    <tr class="hover:bg-slate-50/80 transition-colors text-slate-700">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="p-2 bg-blue-50 text-blue-600 rounded-lg">
                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                </div>
                                <div>
                                    <p class="font-semibold text-slate-900">{{ $doc->title }}</p>
                                    <p class="text-[10px] text-slate-400 font-mono tracking-tighter">{{ $doc->document_number }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 max-w-xs">
                            <p class="truncate text-xs text-slate-500">{{ $doc->description ?? '-' }}</p>
                            <p class="text-[10px] text-slate-400 mt-1 italic">Oleh: {{ $doc->creator?->employee?->name ?? 'Unknown' }}</p>
                        </td>
                        <td class="px-6 py-4 text-center text-xs font-bold text-slate-500">{{ $doc->division }}</td>
                        <td class="px-6 py-4 text-center">
                            <span class="{{ $doc->visibility_badge_class }} px-3 py-1 rounded-full text-[11px] font-bold border">
                                {{ $doc->visibility == 'company_wide' ? 'Company Wide' : 'Division Only' }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center justify-center gap-1.5 text-slate-400">
                                <a href="{{ asset('storage/' . $doc->file_path) }}" target="_blank" class="p-2 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-all" title="Lihat">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                                </a>
                                <a href="{{ asset('storage/' . $doc->file_path) }}" download class="p-2 hover:text-emerald-600 hover:bg-emerald-50 rounded-lg transition-all" title="Download">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                                </a>
                                @if($doc->is_owner)
                                <form action="{{ route('documents.destroy', $doc->id) }}" method="POST" onsubmit="GlobalActions.confirmDelete(event, this)">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="p-2 hover:text-red-600 hover:bg-red-50 rounded-lg transition-all" title="Hapus">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    </button>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="5" class="px-6 py-20 text-center text-slate-400">Belum ada dokumen.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="px-6 py-4 bg-slate-50/50 border-t border-slate-100 flex flex-col sm:flex-row items-center justify-between gap-4">
            <div class="flex items-center gap-3 text-sm text-slate-500 font-medium">
                <span>Tampilkan</span>
                <select onchange="window.location.href = `?per_page=${this.value}`" class="bg-white border border-slate-200 rounded-lg p-1 outline-none shadow-sm cursor-pointer">
                    @foreach([10, 50, 100] as $v)
                    <option value="{{ $v }}" {{ $perPage == $v ? 'selected' : '' }}>{{ $v }}</option>
                    @endforeach
                </select>
                <span>baris</span>
                <span class="ml-2 border-l pl-4 font-bold text-slate-700 uppercase text-[10px]">Total: {{ $documents->total() }}</span>
            </div>

            <div class="flex items-center gap-2">
                <a @if($documents->onFirstPage()) href="#" class="p-2 opacity-30 pointer-events-none" @else href="{{ $documents->previousPageUrl() }}" class="p-2 hover:text-blue-600" @endif>
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" /></svg>
                </a>
                <span class="text-xs font-bold text-slate-600 px-2 uppercase tracking-widest">Hal {{ $documents->currentPage() }} / {{ max(1, $documents->lastPage()) }}</span>
                <a @if(!$documents->hasMorePages()) href="#" class="p-2 opacity-30 pointer-events-none" @else href="{{ $documents->nextPageUrl() }}" class="p-2 hover:text-blue-600" @endif>
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
                </a>
            </div>
        </div>
    </div>
</div>
@endsection