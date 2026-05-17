<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreDocumentRequest;
use App\Http\Requests\UpdateDocumentRequest;
use App\Models\Category;
use App\Models\Document;
use App\Services\DocumentService;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class DocumentController extends Controller
{
    public function __construct(protected DocumentService $documentService)
    {
    }

    public function index(Request $request): View
    {
        $user = $request->user();
        // Pengecekan Admin (kebal terhadap Spatie or role_id)
        $isAdmin = $user->role_id == 1 || (method_exists($user, 'hasRole') && $user->hasRole(['admin', 'Administrator']));

        // Build base query via service to keep controller thin
        $query = $this->documentService->buildAccessibleDocumentsQuery($user, $isAdmin);

        // 2. Pencarian Teks (Search)
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'ilike', "%{$search}%")
                  ->orWhere('description', 'ilike', "%{$search}%");
            });
        }

        // 3. Filter Dropdowns
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        if ($request->filled('label')) {
            $query->where('label', $request->label);
        }

        if ($request->filled('visibility')) {
            $query->where('visibility', $request->visibility);
        }

        $documents = $query->latest()->paginate(12)->withQueryString();
        $categories = Category::orderBy('name')->get();

        return view('documents.index', compact('documents', 'categories'));
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
        $shares = $this->documentService->getSharesForDocument($document->id);
        $divisions = $this->documentService->getDivisionsExcept($document->division_id);
        $activityLogs = $this->documentService->getActivityLogsForDocument($document->id, 10);

        return view('documents.show', compact('document', 'activityLogs', 'divisions', 'shares'));
    }

    public function share(Request $request, Document $document): \Illuminate\Http\RedirectResponse
    {
        \Illuminate\Support\Facades\Gate::authorize('share', $document);

        $request->validate([
            'division_id' => 'required|integer',
            'permission' => 'required|in:view,edit',
            'expires_at' => 'nullable|date|after:today',
        ]);
        $divisionId = (int) $request->input('division_id');
        $permission = (string) $request->input('permission');
        $expiresAt = $request->filled('expires_at') ? $request->input('expires_at') : null;

        $this->documentService->attachShare($document, $divisionId, $permission, $expiresAt, auth()->id());

        return back()->with('success', 'Hak akses divisi berhasil diperbarui.');
    }

    public function revokeShare($id): \Illuminate\Http\RedirectResponse
    {
        $share = $this->documentService->getShareById((int) $id);
        if (!$share) {
            return back()->with('error', 'Data pembagian dokumen tidak ditemukan.');
        }

        $document = Document::findOrFail($share->document_id);
        \Illuminate\Support\Facades\Gate::authorize('share', $document);

        $this->documentService->revokeShareById((int) $id, auth()->id());

        return back()->with('success', 'Akses divisi untuk dokumen ini telah dicabut.');
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

    public function restore($id): RedirectResponse
    {
        $user = auth()->user();
        $isAdmin = $user->role_id == 1 || (method_exists($user, 'hasRole') && $user->hasRole(['admin', 'Administrator']));
        
        if (!$isAdmin) {
            abort(403, 'Aksi tidak diizinkan. Hanya Super Admin yang dapat mengembalikan dokumen dari Trash.');
        }

        $document = Document::onlyTrashed()->findOrFail($id);
        
        $this->documentService->restoreDocument($document);

        return redirect()->route('documents.index')->with('success', 'Dokumen berhasil dikembalikan ke repositori aktif.');
    }
}