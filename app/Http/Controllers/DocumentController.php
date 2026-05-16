<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreDocumentRequest;
use App\Http\Requests\UpdateDocumentRequest;
use App\Models\Category;
use App\Models\Document;
use App\Services\DocumentService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Illuminate\Support\Facades\Gate;

class DocumentController extends Controller
{
    public function __construct(protected DocumentService $documentService)
    {
    }

    public function index(Request $request): View
    {
        $user = $request->user();
        $query = Document::with(['currentVersion', 'tags', 'category']);

        if ($user->role_id != 1) {
            $query->where(function ($query) use ($user) {
                $query->where('visibility', 'public')
                    ->orWhere('division_id', $user->division_id);
            });
        }

        $documents = $query->latest()->paginate(12);

        return view('documents.index', compact('documents'));
    }

    public function create(): View
    {
        $categories = Category::orderBy('name')->get();

        return view('documents.create', compact('categories'));
    }

    public function store(StoreDocumentRequest $request): RedirectResponse
    {
        $document = $this->documentService->createDocument($request->validated(), $request->file('file'));

        return redirect()->route('documents.show', $document)
            ->with('success', 'Dokumen berhasil dibuat.');
    }

    public function show(Document $document): View
    {
        Gate::authorize('view', $document);

        $document->load(['currentVersion', 'versions.uploader', 'tags', 'category']);
        
        $activityLogs = DB::table('document_activity_log as log')
            ->join('users as actor', 'actor.id', '=', 'log.actor_id')
            ->where('log.document_id', $document->id)
            ->select('log.*', 'actor.email as actor_email')
            ->orderByDesc('log.created_at')
            ->limit(10)
            ->get();

        return view('documents.show', compact('document', 'activityLogs'));
    }

    public function edit(Document $document): View
    {
        Gate::authorize('update', $document);

        $categories = Category::orderBy('name')->get();

        return view('documents.edit', compact('document', 'categories'));
    }

    public function update(UpdateDocumentRequest $request, Document $document): RedirectResponse
    {
        Gate::authorize('update', $document);

        $this->documentService->updateDocument($document, $request->validated(), $request->file('file'));

        return redirect()->route('documents.show', $document)
            ->with('success', 'Dokumen berhasil diperbarui.');
    }

    public function destroy(Document $document): RedirectResponse
    {
        Gate::authorize('delete', $document);

        $this->documentService->deleteDocument($document);

        return redirect()->route('documents.index')
            ->with('success', 'Dokumen berhasil dihapus.');
    }

    public function downloadVersion(\App\Models\DocumentVersion $version)
    {
        Gate::authorize('view', $version->document);

        $document = $version->document;
        $extension = pathinfo($version->file_original_name, PATHINFO_EXTENSION);
        $customFileName = "{$document->document_number}_{$document->name}_v{$version->version_number}.{$extension}";

        return response()->streamDownload(function () use ($version) {
            echo \Illuminate\Support\Facades\Storage::disk('public')->get($version->file_path);
        }, $customFileName);
    }
}