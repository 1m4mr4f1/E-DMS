<?php

namespace App\Http\Controllers;

use App\Models\Document;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class DocumentController extends Controller
{
    /**
     * Menampilkan daftar dokumen dengan Sekat Divisi
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        
        // Pengaturan Pagination (10, 50, 100)
        $perPage = $request->input('per_page', 10);
        if (!in_array($perPage, [10, 50, 100])) {
            $perPage = 10;
        }

        // Query Utama
        $query = Document::with([
            'creator:id,employee_id',
            'creator.employee:id,name',
            'divisionRelation:id,name',
        ]);

        /**
         * LOGIKA SEKAT DIVISI:
         * super_admin & admin: Bisa melihat semua data tanpa filter.
         * employee: Hanya bisa melihat dokumen yang divisinya sama dengan mereka.
         */
        if (!$user->hasAnyRole(['super_admin', 'admin'])) {
            $employeeDivisionId = $user->employee?->division_id;
            $query->where('division_id', $employeeDivisionId);
        }

        $documents = $query->latest()->paginate($perPage);

        return view('documents.index', compact('documents', 'perPage'));
    }

    public function create()
    {
        return view('documents.create');
    }

    /**
     * Menyimpan Dokumen Baru
     */
    public function store(Request $request)
    {
        $request->validate([
            'title'         => 'required|string|max:255',
            'visibility'    => 'required|in:division_only,company_wide',
            'document_file' => 'required|file|mimes:pdf,doc,docx,xls,xlsx|max:10240',
            'description'   => 'nullable|string',
        ]);

        $user = Auth::user();
        $file = $request->file('document_file');
        
        // Nama file unik
        $cleanFileName = time() . '_' . str_replace(' ', '_', $file->getClientOriginalName());
        $filePath = $file->storeAs('documents', $cleanFileName, 'public');

        Document::create([
            'title'           => $request->title,
            'description'     => $request->description,
            'file_path'       => $filePath,
            'mime_type'       => $file->getClientMimeType(),
            'file_size'       => $file->getSize(),
            'uploaded_by'     => $user->id,
            'division_id'     => $user->hasAnyRole(['super_admin', 'admin']) ? null : $user->employee?->division_id,
            'visibility'      => $request->visibility,
            'version'         => 1,
        ]);

        return redirect()->route('documents.index')->with('success', 'Dokumen berhasil diunggah!');
    }

    /**
     * Menghapus Dokumen (Hanya untuk Pemilik)
     */
    public function destroy(Document $document)
    {
        // Proteksi tingkat Controller (Double Protection)
        if (Auth::id() !== $document->uploaded_by) {
            return redirect()->back()->with('error', 'Anda tidak memiliki hak untuk menghapus dokumen ini.');
        }

        // Hapus file fisik
        if (Storage::disk('public')->exists($document->file_path)) {
            Storage::disk('public')->delete($document->file_path);
        }

        $document->delete();

        return redirect()->route('documents.index')->with('success', 'Dokumen telah dihapus.');
    }
}