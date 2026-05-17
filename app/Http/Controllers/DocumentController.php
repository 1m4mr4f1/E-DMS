<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreDocumentRequest;
use App\Http\Requests\UpdateDocumentRequest;
use App\Models\Category;
use App\Models\Document;
use App\Models\User; // <-- TAMBAHAN: Import Model User
use App\Notifications\DocumentSharedNotification; // <-- TAMBAHAN: Import Kelas Notifikasi
use App\Services\DocumentService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Notification; // <-- TAMBAHAN: Import Facade Notification
use Illuminate\View\View;

class DocumentController extends Controller
{
    public function __construct(protected DocumentService $documentService)
    {
    }

    public function index(Request $request): View
    {
        $user = $request->user();
        
        // Pengecekan Admin (Kebal Error tipe data & Spatie)
        $isAdmin = $user->role_id == 1 || (method_exists($user, 'hasRole') && $user->hasRole(['admin', 'Administrator']));

        // KUNCI TRASH: Jika Admin, tarik semua termasuk yang dihapus
        $query = $isAdmin 
            ? Document::withTrashed()->with(['currentVersion', 'tags', 'category']) 
            : Document::with(['currentVersion', 'tags', 'category']);

        // 1. Strict Data Isolation Guard (Hanya berlaku untuk NON-ADMIN)
        if (!$isAdmin) {
            $query->where(function ($q) use ($user) {
                $q->where('visibility', 'public')
                  ->orWhere('division_id', $user->division_id)
                  ->orWhereExists(function ($sub) use ($user) {
                      $sub->select(DB::raw(1))
                          ->from('document_sharing')
                          ->whereColumn('document_sharing.document_id', 'documents.id')
                          ->where('document_sharing.shared_to_division_id', $user->division_id)
                          ->where(function ($expiry) {
                              $expiry->whereNull('expires_at')
                                     ->orWhere('expires_at', '>', now());
                          });
                  });
            });
        }

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
        
        $shares = DB::table('document_sharing')
            ->join('divisions', 'divisions.id', '=', 'document_sharing.shared_to_division_id')
            ->join('users', 'users.id', '=', 'document_sharing.shared_by')
            ->where('document_sharing.document_id', $document->id)
            ->select('document_sharing.*', 'divisions.name as division_name', 'users.full_name as sharer_name')
            ->get();

        $divisions = DB::table('divisions')
            ->where('id', '!=', $document->division_id)
            ->orderBy('name')
            ->get();

        $activityLogs = DB::table('document_activity_log as log')
            ->join('users as actor', 'actor.id', '=', 'log.actor_id')
            ->where('log.document_id', $document->id)
            ->select('log.*', 'actor.email as actor_email')
            ->orderByDesc('log.created_at')
            ->limit(10)
            ->get();

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

        DB::table('document_sharing')->updateOrInsert(
            [
                'document_id' => $document->id,
                'shared_to_division_id' => $request->division_id
            ],
            [
                'shared_by' => auth()->id(),
                'permission' => $request->permission,
                'expires_at' => $request->expires_at,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );

        $divName = DB::table('divisions')->where('id', $request->division_id)->value('name');
        DB::table('document_activity_log')->insert([
            'document_id' => $document->id,
            'actor_id' => auth()->id(),
            'action_type' => 'shared',
            'description' => "Memberikan akses " . strtoupper($request->permission) . " ke divisi {$divName}.",
            'created_at' => now()
        ]);

        // --- FITUR NOTIFIKASI: SESUAI TABEL KUSTOM KAMU ---
        // Mengambil semua user yang berada di divisi target penerima share
        $targetUsers = User::where('division_id', $request->division_id)->get();
        
        if ($targetUsers->isNotEmpty()) {
            $permissionText = $request->permission === 'edit' ? 'hak akses Edit' : 'hak akses View Only';
            $sharerName = auth()->user()->full_name ?? auth()->user()->name;

            foreach ($targetUsers as $tUser) {
                DB::table('notifications')->insert([
                    'user_id'     => $tUser->id,
                    'type'        => 'shared',
                    'title'       => 'Akses Dokumen Baru',
                    'body'        => "{$sharerName} telah membagikan dokumen '{$document->name}' ke divisi Anda dengan {$permissionText}.",
                    'document_id' => $document->id,
                    'is_read'     => false, // Kolom boolean sesuai tabel kustom kamu
                    'created_at'  => now(),
                    'updated_at'  => now(),
                ]);
            }
        }

        return back()->with('success', 'Hak akses divisi berhasil diperbarui.');
    }

    public function revokeShare($id): \Illuminate\Http\RedirectResponse
    {
        $share = DB::table('document_sharing')->where('id', $id)->first();
        if (!$share) {
            return back()->with('error', 'Data pembagian dokumen tidak ditemukan.');
        }

        $document = Document::findOrFail($share->document_id);
        
        \Illuminate\Support\Facades\Gate::authorize('share', $document);

        $divName = DB::table('divisions')->where('id', $share->shared_to_division_id)->value('name');
        DB::table('document_activity_log')->insert([
            'document_id' => $document->id,
            'actor_id' => auth()->id(),
            'action_type' => 'revoked',
            'description' => "Mencabut seluruh akses dari divisi {$divName}.",
            'created_at' => now()
        ]);

        DB::table('document_sharing')->where('id', $id)->delete();

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