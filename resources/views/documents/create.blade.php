@extends('layouts.app')

@section('title', 'Upload New Document')

@section('content')
@php
    use Illuminate\Support\Facades\Storage;
    
    // Initializing a new model instance since the controller doesn't pass $document on create
    $document = new App\Models\Document;
    $tags = old('tags', $document->tags->pluck('tag')->all() ?? []);
@endphp

<div class="w-full space-y-4">
    <div class="flex items-center justify-between border-b border-slate-200 pb-4">
        <div>
            <h1 class="text-xl font-bold text-slate-900 tracking-tight">Upload New Document</h1>
            <p class="text-xs text-slate-500">Fill in the metadata and upload a file to establish the initial version (v1).</p>
        </div>
        <a href="{{ route('documents.index') }}" class="inline-flex items-center gap-1.5 rounded-lg border border-slate-200 bg-white px-3 py-1.5 text-xs font-semibold text-slate-700 shadow-sm hover:bg-slate-50 transition-all">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-3.5 h-3.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
            </svg>
            Back to Repository
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

    <form action="{{ route('documents.store') }}" method="post" enctype="multipart/form-data">
        @csrf

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-5 items-start">
            
            <div class="lg:col-span-2 space-y-4 bg-white p-5 rounded-xl border border-slate-200 shadow-sm">
                <div class="grid gap-4 md:grid-cols-2">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 tracking-wide uppercase">Document Title <span class="text-rose-500">*</span></label>
                        <input type="text" name="name" value="{{ old('name', $document->name ?? '') }}" required placeholder="e.g., Standard Operating Procedure Financial Audit"
                            class="mt-1.5 block w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm text-slate-900 placeholder-slate-400 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500 transition-all shadow-sm" />
                        @error('name')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 tracking-wide uppercase">Category <span class="text-rose-500">*</span></label>
                        <select name="category_id" required
                            class="mt-1.5 block w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm text-slate-900 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500 transition-all shadow-sm">
                            <option value="">-- Select Category --</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ old('category_id', $document->category_id ?? '') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                            @endforeach
                        </select>
                        @error('category_id')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 tracking-wide uppercase">Brief Description</label>
                    <textarea name="description" rows="3" placeholder="Provide a brief summary or contextual notes regarding this document record..."
                        class="mt-1.5 block w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm text-slate-900 placeholder-slate-400 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500 transition-all shadow-sm resize-none">{{ old('description', $document->description ?? '') }}</textarea>
                    @error('description')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
                </div>

                <div class="border-t border-slate-100 pt-4">
                    <label class="block text-xs font-bold text-slate-700 tracking-wide uppercase mb-2">File Attachment <span class="text-rose-500">*</span></label>
                    <div class="flex items-center justify-center w-full">
                        <label class="flex flex-col items-center justify-center w-full h-24 border-2 border-slate-300 border-dashed rounded-lg cursor-pointer bg-slate-50 hover:bg-slate-100/70 transition-all">
                            <div class="flex flex-col items-center justify-center pt-3 pb-3">
                                <svg class="w-6 h-6 mb-1.5 text-slate-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 16">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 13h3a3 3 0 0 0 0-6h-.025A5.56 5.56 0 0 0 16 6.5 5.5 5.5 0 0 0 5.207 5.021C5.137 5.017 5.071 5 5 5a4 4 0 0 0 0 8h2.167M10 15V6m0 0L8 8m2-2l2 2"/>
                                </svg>
                                <p class="text-xs text-slate-600"><span class="font-semibold text-blue-600">Click to browse files</span> or drag and drop your document here</p>
                                <p class="text-[10px] text-slate-400 mt-0.5">Supported formats: PDF, DOCX, XLSX, PPTX, PNG, JPG (Max. 100MB)</p>
                            </div>
                            <input type="file" name="file" required class="hidden" id="file-uploader" onchange="displayFileName()" />
                        </label>
                    </div>
                    
                    <div id="file-name-preview" class="hidden mt-2 text-xs font-semibold text-emerald-700 bg-emerald-50 border border-emerald-200 px-3 py-1.5 rounded-lg flex items-center gap-1.5">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-3.5 h-3.5">
                            <path fill-rule="evenodd" d="M15.621 4.379a3 3 0 00-4.242 0l-7 7a3 3 0 004.242 4.242l7-7a3 3 0 000-4.242zM14.56 5.44a1.5 1.5 0 010 2.122l-7 7a1.5 1.5 0 11-2.122-2.122l7-7a1.5 1.5 0 012.122 0z" clip-rule="evenodd" />
                        </svg>
                        <span id="target-name">filename.pdf</span>
                    </div>
                    @error('file')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
                </div>
            </div>

            <div class="space-y-4 lg:col-span-1">
                <div class="bg-white p-5 rounded-xl border border-slate-200 shadow-sm space-y-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 tracking-wide uppercase">Publication Label <span class="text-rose-500">*</span></label>
                        <select name="label" required
                            class="mt-1.5 block w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm text-slate-900 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500 transition-all shadow-sm">
                            <option value="draft" {{ old('label', $document->label ?? '') === 'draft' ? 'selected' : '' }}>Draft (Internal Work-in-Progress)</option>
                            <option value="fix" {{ old('label', $document->label ?? '') === 'fix' ? 'selected' : '' }}>Final (Approved Publication)</option>
                        </select>
                        @error('label')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 tracking-wide uppercase">Access Visibility <span class="text-rose-500">*</span></label>
                        <select name="visibility" required
                            class="mt-1.5 block w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm text-slate-900 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500 transition-all shadow-sm">
                            <option value="private" {{ old('visibility', $document->visibility ?? '') === 'private' ? 'selected' : '' }}>Private (Own Division Only)</option>
                            <option value="public" {{ old('visibility', $document->visibility ?? '') === 'public' ? 'selected' : '' }}>Public (All Divisions)</option>
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
                        <p class="text-[10px] text-slate-400 mt-1.5 leading-relaxed">Provide up to 4 optional metadata keywords to facilitate efficient cross-module search indexing.</p>
                        @error('tags.*')<p class="mt-1 text-xs text-rose-600">{{ $message }}</p>@enderror
                    </div>
                </div>

                <button type="submit" class="w-full inline-flex items-center justify-center rounded-lg bg-blue-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-blue-700 focus:ring-2 focus:ring-blue-200 transition-all gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                    </svg>
                    Publish Document
                </button>
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