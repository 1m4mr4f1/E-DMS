@extends('layouts.app')

@section('title', 'Edit Document')

@section('content')
@php
    use Illuminate\Support\Facades\Storage;
    $tags = old('tags', $document->tags->pluck('tag')->all() ?? []);
    
    // Mengambil URL dan Ekstensi File untuk Keperluan Live Preview berkas saat ini
    $fileUrl = $document->currentVersion ? Storage::url($document->currentVersion->file_path) : null;
    $extension = $document->currentVersion ? strtolower(pathinfo($document->currentVersion->file_path, PATHINFO_EXTENSION)) : '';
@endphp

<div class="w-full space-y-4">
    <div class="flex items-center justify-between border-b border-slate-200 pb-4">
        <div>
            <h1 class="text-xl font-bold text-slate-900 tracking-tight">Edit Document Profile</h1>
            <p class="text-xs text-slate-500">Modify metadata profile or upload a new file attachment to deploy the next version.</p>
        </div>
        <a href="{{ route('documents.show', $document) }}" class="inline-flex items-center gap-1.5 rounded-lg border border-slate-200 bg-white px-3 py-1.5 text-xs font-semibold text-slate-700 shadow-sm hover:bg-slate-50 transition-all">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-3.5 h-3.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
            </svg>
            Back to Details
        </a>
    </div>

    @if ($errors->any())
        <div class="rounded-xl bg-rose-50 p-4 border border-rose-200 shadow-sm">
            <div class="flex items-center gap-2 mb-1">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-rose-600" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                </svg>
                <h3 class="text-xs font-bold text-rose-800">Validation Failed:</h3>
            </div>
            <ul class="list-disc list-inside text-xs text-rose-700 ml-6 space-y-0.5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('documents.update', $document) }}" method="post" enctype="multipart/form-data">
        @csrf
        @method('PATCH')

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-5 items-start">
            
            <div class="lg:col-span-2 space-y-4 bg-white p-5 rounded-xl border border-slate-200 shadow-sm">
                <div class="grid gap-4 md:grid-cols-2">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 tracking-wide uppercase">Document Title <span class="text-rose-500">*</span></label>
                        <input type="text" name="name" value="{{ old('name', $document->name) }}" required
                            class="mt-1.5 block w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm text-slate-900 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500 transition-all shadow-sm" />
                        @error('name')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 tracking-wide uppercase">Category <span class="text-rose-500">*</span></label>
                        <select name="category_id" required
                            class="mt-1.5 block w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm text-slate-900 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500 transition-all shadow-sm">
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ old('category_id', $document->category_id) == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                            @endforeach
                        </select>
                        @error('category_id')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 tracking-wide uppercase">Brief Description</label>
                    <textarea name="description" rows="3"
                        class="mt-1.5 block w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm text-slate-900 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500 transition-all shadow-sm resize-none">{{ old('description', $document->description) }}</textarea>
                    @error('description')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
                </div>

                <div class="border-t border-slate-100 pt-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        
                        <div>
                            <label class="block text-xs font-bold text-slate-700 tracking-wide uppercase mb-2">Current Active File Preview</label>
                            @if(in_array($extension, ['pdf']))
                                <div class="h-32 overflow-hidden rounded-lg border border-slate-200 bg-slate-100 shadow-inner">
                                    <embed src="{{ $fileUrl }}" type="application/pdf" class="h-full w-full" />
                                </div>
                            @elseif(in_array($extension, ['png', 'jpg', 'jpeg']))
                                <div class="h-32 overflow-hidden rounded-lg border border-slate-200 bg-slate-50 flex items-center justify-center p-1 shadow-inner">
                                    <img src="{{ $fileUrl }}" alt="Active File Preview" class="h-full w-full object-contain" />
                                </div>
                            @else
                                <div class="h-32 rounded-lg border border-slate-200 bg-slate-50 flex flex-col items-center justify-center text-center p-3 shadow-inner">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 text-slate-400 mb-1">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" />
                                    </svg>
                                    <span class="text-[10px] font-semibold text-slate-500 tracking-wide">Preview Unavailable</span>
                                    <a href="{{ $fileUrl }}" target="_blank" class="text-[10px] text-blue-600 underline font-medium mt-0.5 hover:text-blue-700">Download to View</a>
                                </div>
                            @endif
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-700 tracking-wide uppercase mb-2">Upload New File Version <span class="text-slate-400 font-normal">(Optional)</span></label>
                            <div class="flex items-center justify-center w-full">
                                <label class="flex flex-col items-center justify-center w-full h-32 border-2 border-slate-300 border-dashed rounded-lg cursor-pointer bg-slate-50 hover:bg-slate-100/70 transition-all">
                                    <div class="flex flex-col items-center justify-center pt-2 pb-2 px-2 text-center">
                                        <svg class="w-5 h-5 mb-1 text-slate-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 16">
                                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 13h3a3 3 0 0 0 0-6h-.025A5.56 5.56 0 0 0 16 6.5 5.5 5.5 0 0 0 5.207 5.021C5.137 5.017 5.071 5 5 5a4 4 0 0 0 0 8h2.167M10 15V6m0 0L8 8m2-2l2 2"/>
                                        </svg>
                                        <p class="text-[11px] text-slate-600"><span class="font-semibold text-blue-600">Click to replace</span> or drop file</p>
                                        <p class="text-[9px] text-slate-400 mt-0.5">Leave empty to keep current file (Max. 100MB)</p>
                                    </div>
                                    <input type="file" name="file" class="hidden" id="file-uploader" onchange="displayFileName()" />
                                </label>
                            </div>
                            
                            <div id="file-name-preview" class="hidden mt-2 text-xs font-semibold text-emerald-700 bg-emerald-50 border border-emerald-200 px-3 py-1.5 rounded-lg flex items-center gap-1.5">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-3.5 h-3.5">
                                    <path fill-rule="evenodd" d="M15.621 4.379a3 3 0 00-4.242 0l-7 7a3 3 0 004.242 4.242l7-7a3 3 0 000-4.242zM14.56 5.44a1.5 1.5 0 010 2.122l-7 7a1.5 1.5 0 11-2.122-2.122l7-7a1.5 1.5 0 012.122 0z" clip-rule="evenodd" />
                                </svg>
                                <span id="target-name" class="truncate max-w-[150px]">newfile.pdf</span>
                            </div>
                            @error('file')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
                        </div>

                    </div>
                </div>
            </div>

            <div class="space-y-4 lg:col-span-1">
                <div class="bg-white p-5 rounded-xl border border-slate-200 shadow-sm space-y-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 tracking-wide uppercase">Publication Label <span class="text-rose-500">*</span></label>
                        <select name="label" required
                            class="mt-1.5 block w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm text-slate-900 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500 transition-all shadow-sm">
                            <option value="draft" {{ old('label', $document->label) === 'draft' ? 'selected' : '' }}>Draft (Internal Work-in-Progress)</option>
                            <option value="fix" {{ old('label', $document->label) === 'fix' ? 'selected' : '' }}>Final (Approved Publication)</option>
                        </select>
                        @error('label')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 tracking-wide uppercase">Access Visibility <span class="text-rose-500">*</span></label>
                        <select name="visibility" required
                            class="mt-1.5 block w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm text-slate-900 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500 transition-all shadow-sm">
                            <option value="private" {{ old('visibility', $document->visibility) === 'private' ? 'selected' : '' }}>Private (Own Division Only)</option>
                            <option value="public" {{ old('visibility', $document->visibility) === 'public' ? 'selected' : '' }}>Public (All Divisions)</option>
                        </select>
                        @error('visibility')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
                    </div>

                    <div class="border-t border-slate-100 pt-3">
                        <label class="block text-xs font-bold text-slate-700 tracking-wide uppercase">Search Tags</label>
                        <div class="mt-2 grid grid-cols-2 gap-2">
                            @for($i = 0; $i < 4; $i++)
                                <input type="text" name="tags[]" value="{{ $tags[$i] ?? '' }}"
                                    placeholder="Tag #{{ $i + 1 }}"
                                    class="block w-full rounded-lg border border-slate-300 bg-white px-2.5 py-1.5 text-xs text-slate-900 placeholder-slate-400 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500 transition-all shadow-sm" />
                            @endfor
                        </div>
                        <p class="text-[10px] text-slate-400 mt-1.5 leading-relaxed">Update optional metadata keywords to maintain search index integrity across repository modules.</p>
                        @error('tags.*')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
                    </div>
                </div>

                @if($document->currentVersion)
                    <div class="bg-slate-50 p-4 rounded-xl border border-slate-200 text-xs text-slate-600 space-y-1.5 shadow-sm">
                        <h4 class="font-bold text-slate-700 uppercase tracking-wide text-[10px]">Active Retained Version</h4>
                        <p class="font-medium text-slate-900 truncate" title="{{ $document->currentVersion->file_original_name }}">
                            {{ $document->currentVersion->file_original_name }}
                        </p>
                        <div class="flex justify-between text-slate-500 pt-0.5 text-[11px]">
                            <span>Version: v{{ $document->currentVersion->version_number }}</span>
                            <span>{{ number_format($document->currentVersion->file_size_bytes / 1024, 1) }} KB</span>
                        </div>
                    </div>
                @endif

                <div class="flex items-center gap-2">
                    <a href="{{ route('documents.show', $document) }}" 
                       class="w-1/3 inline-flex items-center justify-center rounded-lg border border-slate-300 bg-white px-3 py-2 text-xs font-semibold text-slate-700 shadow-sm hover:bg-slate-50 transition-all">
                        Cancel
                    </a>
                    <button type="submit" 
                            class="w-2/3 inline-flex items-center justify-center rounded-lg bg-emerald-600 px-4 py-2 text-xs font-semibold text-white shadow-sm hover:bg-emerald-700 focus:ring-2 focus:ring-emerald-200 transition-all gap-1.5">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-3.5 h-3.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                        </svg>
                        Save Changes
                    </button>
                </div>
            </div>

        </div>
    </form>
</div>

<script>
    function displayFileName() {
        const input = document.getElementById('file-uploader');
        const preview = document.getElementById('file-name-preview');
        const target = document.getElementById('target-name');
        
        if (input.files && input.files.length > 0) {
            target.textContent = input.files[0].name;
            preview.classList.remove('hidden');
        } else {
            preview.classList.add('hidden');
        }
    }
</script>
@endsection