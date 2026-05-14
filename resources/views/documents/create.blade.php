@extends('layouts.app')

@section('title', 'Upload Dokumen')

@section('content')
<div class="max-w-2xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-bold text-slate-900 tracking-tight">Upload Dokumen</h1>
        <a href="{{ route('documents.index') }}" class="text-sm font-medium text-slate-500 hover:text-slate-900 flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 15l-3-3m0 0l3-3m-3 3h8M3 12a9 9 0 1118 0 9 9 0 01-18 0z"/></svg>
            Kembali
        </a>
    </div>

    <form id="upload-form" action="{{ route('documents.store') }}" method="POST" enctype="multipart/form-data" class="bg-white border border-slate-200 rounded-2xl shadow-sm p-8 space-y-5">
        @csrf
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
            <div class="space-y-1.5">
                <label class="text-xs font-bold text-slate-500 uppercase tracking-wider">Judul Dokumen</label>
                <input type="text" name="title" required placeholder="Contoh: Rencana Strategis 2026" class="w-full px-4 py-2.5 bg-slate-50 border-transparent focus:bg-white focus:ring-2 focus:ring-blue-600 rounded-xl outline-none transition-all text-sm">
            </div>
            <div class="space-y-1.5">
                <label class="text-xs font-bold text-slate-500 uppercase tracking-wider">Visibilitas Dokumen</label>
                <select name="visibility" required class="w-full px-4 py-2.5 bg-slate-50 border-transparent focus:bg-white focus:ring-2 focus:ring-blue-600 rounded-xl outline-none transition-all text-sm">
                    <option value="division_only">Hanya Divisi</option>
                    <option value="company_wide">Seluruh Perusahaan</option>
                </select>
            </div>
        </div>

        <div class="space-y-1.5">
            <label class="text-xs font-bold text-slate-500 uppercase tracking-wider">Keterangan Tambahan</label>
            <textarea name="description" rows="3" placeholder="Jelaskan isi atau tujuan dokumen ini..." class="w-full px-4 py-2.5 bg-slate-50 border-transparent focus:bg-white focus:ring-2 focus:ring-blue-600 rounded-xl outline-none transition-all text-sm"></textarea>
        </div>

        <div class="space-y-1.5">
            <label class="text-xs font-bold text-slate-500 uppercase tracking-wider">File Dokumen</label>
            <div id="dropzone" class="border-2 border-dashed border-slate-200 rounded-2xl p-8 text-center hover:bg-slate-50 hover:border-blue-300 transition-all cursor-pointer relative group">
                <input type="file" name="document_file" id="file-upload" required class="absolute inset-0 w-full h-full opacity-0 cursor-pointer">
                
                <div id="upload-prompt" class="space-y-2">
                    <div class="w-12 h-12 bg-blue-50 text-blue-500 rounded-full flex items-center justify-center mx-auto transition-transform group-hover:scale-110">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
                    </div>
                    <p class="text-sm font-semibold text-slate-700">Pilih dokumen untuk diunggah</p>
                    <p class="text-xs text-slate-400">Format: PDF, Word, Excel (Maks. 10MB)</p>
                </div>

                <div id="file-preview" class="hidden space-y-2">
                    <div class="w-12 h-12 bg-emerald-50 text-emerald-500 rounded-full flex items-center justify-center mx-auto">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <p id="file-name" class="text-sm font-bold text-slate-900"></p>
                    <span class="text-[10px] bg-emerald-100 text-emerald-700 px-2 py-0.5 rounded font-bold uppercase">Ready</span>
                </div>
            </div>
        </div>

        <button type="submit" id="submit-btn" class="w-full py-3 bg-blue-600 text-white rounded-xl font-bold shadow-lg shadow-blue-200 hover:bg-blue-700 hover:shadow-blue-300 transition-all flex items-center justify-center gap-2">
            Simpan Dokumen
        </button>
    </form>
</div>

<script>
    const fileInput = document.getElementById('file-upload');
    const uploadPrompt = document.getElementById('upload-prompt');
    const filePreview = document.getElementById('file-preview');
    const fileNameDisplay = document.getElementById('file-name');
    const form = document.getElementById('upload-form');
    const btn = document.getElementById('submit-btn');

    fileInput.addEventListener('change', function() {
        if(this.files && this.files.length > 0) {
            const file = this.files[0];
            uploadPrompt.classList.add('hidden');
            filePreview.classList.remove('hidden');
            filePreview.classList.add('block');
            fileNameDisplay.textContent = file.name;
            document.getElementById('dropzone').classList.add('border-emerald-400', 'bg-emerald-50/30');
        }
    });

    form.addEventListener('submit', function() {
        btn.disabled = true;
        btn.classList.add('opacity-75', 'cursor-not-allowed');
        btn.innerHTML = `
            <svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            <span>Memproses...</span>
        `;
    });
</script>
@endsection