@extends('layouts.app')

@php
    use Illuminate\Support\Facades\Storage;
    $fileUrl = Storage::url($document->currentVersion->file_path);
    $extension = strtolower(pathinfo($document->currentVersion->file_path, PATHINFO_EXTENSION));
@endphp

@section('title', 'Document Details')

@section('content')
<div class="w-full space-y-4">
    
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between border-b border-slate-200 pb-4 gap-3">
        <div>
            <div class="flex items-center gap-2">
                <span class="text-xs font-bold font-mono px-2 py-0.5 bg-slate-100 text-slate-700 rounded border border-slate-200">
                    {{ $document->document_number }}
                </span>
                <h1 class="text-xl font-bold text-slate-900 tracking-tight">{{ $document->name }}</h1>
            </div>
            <p class="text-xs text-slate-500 mt-1 max-w-3xl">{{ $document->description ?? 'No description provided for this repository record.' }}</p>
        </div>
        
        <div class="flex flex-wrap items-center gap-2">
            <a href="{{ route('documents.index') }}" class="inline-flex items-center gap-1.5 rounded-lg border border-slate-200 bg-white px-3 py-1.5 text-xs font-semibold text-slate-700 shadow-sm hover:bg-slate-50 transition-all">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-3.5 h-3.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
                </svg>
                Back
            </a>
            
            <a href="{{ route('documents.edit', $document) }}" class="inline-flex items-center gap-1.5 rounded-lg border border-amber-200 bg-amber-50 px-3 py-1.5 text-xs font-semibold text-amber-700 shadow-sm hover:bg-amber-100/70 transition-all">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-3.5 h-3.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L6.832 19.82a4.5 4.5 0 0 1-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 0 1 1.13-1.897L16.863 4.487Zm0 0L19.5 7.125" />
                </svg>
                Edit Profile
            </a>

            <form action="{{ route('documents.destroy', $document) }}" method="post" onsubmit="GlobalActions.confirmDelete(event, this)" class="inline">
                @csrf
                @method('DELETE')
                <button type="submit" class="inline-flex items-center gap-1.5 rounded-lg border border-rose-200 bg-rose-50 px-3 py-1.5 text-xs font-semibold text-rose-700 shadow-sm hover:bg-rose-100/70 transition-all">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-3.5 h-3.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                    </svg>
                    Delete
                </button>
            </form>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-5 items-start">
        
        <section class="lg:col-span-2 space-y-4">
            
            <div class="bg-white p-5 rounded-xl border border-slate-200 shadow-sm space-y-4">
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-xs">
                    <div class="border border-slate-100 p-2.5 rounded-lg bg-slate-50/50">
                        <span class="block text-[10px] font-bold text-slate-400 uppercase tracking-wide">Category</span>
                        <span class="block mt-1 font-semibold text-slate-800">{{ $document->category?->name ?? 'Unassigned' }}</span>
                    </div>
                    <div class="border border-slate-100 p-2.5 rounded-lg bg-slate-50/50">
                        <span class="block text-[10px] font-bold text-slate-400 uppercase tracking-wide">Division Access</span>
                        <span class="block mt-1 font-semibold text-slate-800">{{ $document->divisionRelation?->name ?? 'Global' }}</span>
                    </div>
                    <div class="border border-slate-100 p-2.5 rounded-lg bg-slate-50/50">
                        <span class="block text-[10px] font-bold text-slate-400 uppercase tracking-wide">Publication Label</span>
                        <span class="mt-1 inline-flex px-2 py-0.5 font-bold rounded {{ $document->label === 'fix' ? 'bg-emerald-50 text-emerald-700 border border-emerald-100' : 'bg-amber-50 text-amber-700 border border-amber-100' }}">
                            {{ ucfirst($document->label) }}
                        </span>
                    </div>
                    <div class="border border-slate-100 p-2.5 rounded-lg bg-slate-50/50">
                        <span class="block text-[10px] font-bold text-slate-400 uppercase tracking-wide">Visibility</span>
                        <span class="mt-1 inline-flex px-2 py-0.5 font-bold rounded {{ $document->visibility === 'public' ? 'bg-blue-50 text-blue-700 border border-blue-100' : 'bg-slate-100 text-slate-600 border border-slate-200' }}">
                            {{ ucfirst($document->visibility) }}
                        </span>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-xs border-t border-slate-100 pt-4">
                    <div class="flex items-center gap-3 bg-slate-50 border border-slate-200 p-3 rounded-lg">
                        <div class="p-2 bg-white rounded-md border border-slate-200 text-slate-400">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wide">Created By</p>
                            <p class="font-semibold text-slate-800 mt-0.5">{{ $document->creator?->name ?? 'System' }}</p>
                        </div>
                    </div>

                    <div class="flex items-center gap-3 bg-slate-50 border border-slate-200 p-3 rounded-lg">
                        <div class="p-2 bg-white rounded-md border border-slate-200 text-slate-400">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5m-9-6h.008v.008H12v-.008ZM12 15h.008v.008H12V15Zm0 2.25h.008v.008H12v-.008ZM9.75 15h.008v.008H9.75V15Zm0 2.25h.008v.008H9.75v-.008ZM7.5 15h.008v.008H7.5V15Zm0 2.25h.008v.008H7.5v-.008Zm6.75-4.5h.008v.008h-.008v-.008Zm0 2.25h.008v.008h-.008V15Zm0 2.25h.008v.008h-.008v-.008Zm2.25-4.5h.008v.008H16.5v-.008Zm0 2.25h.008v.008H16.5V15Z" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wide">Date Established</p>
                            <p class="font-semibold text-slate-800 mt-0.5">{{ $document->created_at->format('d M Y H:i') }}</p>
                        </div>
                    </div>
                </div>

                @if($document->tags->count())
                    <div class="border-t border-slate-100 pt-3">
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-wide">Indexation Keywords</p>
                        <div class="mt-2 flex flex-wrap gap-1.5">
                            @foreach($document->tags as $tag)
                                <span class="inline-flex items-center rounded bg-slate-100 px-2 py-0.5 text-xs font-medium text-slate-600 border border-slate-200">
                                    {{ $tag->tag }}
                                </span>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>

            <div class="bg-white p-5 rounded-xl border border-slate-200 shadow-sm space-y-3">
                <div class="flex items-center justify-between border-b border-slate-100 pb-2">
                    <div>
                        <h2 class="text-sm font-bold text-slate-900 tracking-tight">Active Document Preview</h2>
                        <p class="text-[11px] text-slate-500 mt-0.5">Version v{{ $document->currentVersion->version_number }} — {{ $document->currentVersion->file_original_name }}</p>
                    </div>
                </div>

                @if(in_array($extension, ['pdf']))
                    <div class="h-[640px] overflow-hidden rounded-lg border border-slate-200 bg-slate-100 shadow-inner">
                        <embed src="{{ $fileUrl }}" type="application/pdf" class="h-full w-full" />
                    </div>
                @elseif(in_array($extension, ['png', 'jpg', 'jpeg']))
                    <div class="overflow-hidden rounded-lg border border-slate-200 bg-slate-50 p-2 shadow-inner max-h-[640px] flex items-center justify-center">
                        <img src="{{ $fileUrl }}" alt="Preview {{ $document->name }}" class="max-h-full object-contain" />
                    </div>
                @else
                    <div class="rounded-lg border border-slate-200 bg-slate-50 p-6 text-center shadow-inner">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1" stroke="currentColor" class="w-12 h-12 text-slate-400 mx-auto mb-2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9z" />
                        </svg>
                        <p class="text-xs font-semibold text-slate-800">Preview is not available for this file extension ({{ strtoupper($extension) }}).</p>
                        <p class="text-[11px] text-slate-400 mt-0.5 mb-4">You can access the active version file layout directly via download execution.</p>
                        
                        <a href="{{ route('documents.versions.download', $document->currentVersion) }}" 
                           onclick="triggerDownloadToast('{{ $document->currentVersion->file_original_name }}')"
                           class="inline-flex items-center gap-1.5 rounded-lg bg-blue-600 px-4 py-2 text-xs font-semibold text-white shadow-sm hover:bg-blue-700 transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-3.5 h-3.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5M16.5 12 12 16.5m0 0L7.5 12m4.5 4.5V3" />
                            </svg>
                            Download Active File Version
                        </a>
                    </div>
                @endif
            </div>
        </section>

        <aside class="space-y-4 lg:col-span-1">
            
            <div class="bg-white p-5 rounded-xl border border-slate-200 shadow-sm space-y-3">
                <h2 class="text-sm font-bold text-slate-900 border-b border-slate-100 pb-2 tracking-tight">Version Deployment History</h2>
                <div class="space-y-2 max-h-[300px] overflow-y-auto pr-1">
                    @foreach($document->versions as $version)
                        <div class="rounded-lg border border-slate-200 p-3 bg-slate-50/50 hover:bg-slate-50 transition-all flex flex-col justify-between gap-2">
                            <div class="flex items-start justify-between gap-2">
                                <div class="min-w-0">
                                    <p class="text-xs font-bold text-slate-900">Version v{{ $version->version_number }}</p>
                                    <p class="text-[11px] text-slate-500 truncate mt-0.5" title="{{ $version->file_original_name }}">{{ $version->file_original_name }}</p>
                                </div>
                                
                                <a href="{{ route('documents.versions.download', $version) }}" 
                                   onclick="triggerDownloadToast('{{ $version->file_original_name }}')"
                                   title="Download Version File"
                                   class="p-1.5 rounded bg-white border border-slate-200 text-slate-500 hover:text-blue-600 hover:bg-blue-50 transition-all shrink-0">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-3.5 h-3.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5M16.5 12 12 16.5m0 0L7.5 12m4.5 4.5V3" />
                                    </svg>
                                </a>
                            </div>
                            <div class="flex items-center justify-between text-[10px] border-t border-slate-200/60 pt-1.5 text-slate-400">
                                <span class="font-medium text-slate-500">By: {{ $version->uploader?->name ?? 'System' }}</span>
                                <span>Snapshot: {{ ucfirst($version->label_snapshot) }}</span>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="bg-white p-5 rounded-xl border border-slate-200 shadow-sm space-y-3">
                <h2 class="text-sm font-bold text-slate-900 border-b border-slate-100 pb-2 tracking-tight">Recent System Activity Logs</h2>
                <div class="space-y-2.5 max-h-[320px] overflow-y-auto pr-1">
                    @forelse($activityLogs as $activity)
                        <div class="text-xs border-l-2 border-blue-500 bg-slate-50/50 p-2.5 rounded-r-lg border border-y-slate-200 border-r-slate-200">
                            <div class="flex items-center justify-between font-bold text-slate-800">
                                <span class="text-blue-700 tracking-wide uppercase text-[10px]">{{ $activity->action_type }}</span>
                                <span class="text-[10px] font-normal text-slate-400">{{ \Carbon\Carbon::parse($activity->created_at)->format('d M H:i') }}</span>
                            </div>
                            <p class="mt-1 text-[11px] text-slate-600 leading-relaxed">
                                Executed by <span class="font-medium text-slate-800">{{ $activity->actor_email }}</span>
                            </p>
                        </div>
                    @empty
                        <p class="text-xs text-slate-400 text-center py-4">No activities recorded for this document.</p>
                    @endforelse
                </div>
            </div>
        </aside>
    </div>
</div>

<script>
    function triggerDownloadToast(fileName) {
        // Memastikan library SweetAlert2 sudah ter-load di global scope framework
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                icon: 'success',
                title: 'Downloading File',
                text: fileName,
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 2500,
                timerProgressBar: true,
                background: '#ffffff',
                iconColor: '#3b82f6', // Menyesuaikan tema biru utama E-DMS Portal
                customClass: {
                    popup: 'border border-slate-200 rounded-xl shadow-lg'
                }
            });
        }
    }
</script>
@endsection