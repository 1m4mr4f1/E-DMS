<?php

namespace App\Services;

use App\Models\Document;
use App\Models\DocumentTag;
use App\Models\DocumentVersion;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Models\User;
use Illuminate\Support\Carbon;

class DocumentService
{
    public function createDocument(array $data, ?UploadedFile $file): Document
    {
        return DB::transaction(function () use ($data, $file) {
            $user = auth()->user();

            $document = Document::create([
                'name' => $data['name'],
                'description' => $data['description'] ?? null,
                'category_id' => $data['category_id'],
                'division_id' => $user->division_id,
                'created_by' => $user->id,
                'label' => $data['label'],
                'visibility' => $data['visibility'],
            ]);

            if (!empty($data['tags'])) {
                $this->syncTags($document, $data['tags']);
            }

            $version = $this->createVersion($document, $file, 1, $data['label'], $data['visibility']);
            $document->current_version_id = $version->id;
            $document->save();

            $this->logActivity($document, $version, 'created', 'Mengunggah master file dan membuat record dokumen baru.', null, $this->snapshot($document));

            return $document->load(['currentVersion', 'tags', 'category']);
        });
    }

    public function updateDocument(Document $document, array $data, ?UploadedFile $file): Document
    {
        return DB::transaction(function () use ($document, $data, $file) {
            $oldSnapshot = $this->snapshot($document);
            $descriptionStr = "";

            $document->fill([
                'name' => $data['name'],
                'description' => $data['description'] ?? null,
                'category_id' => $data['category_id'],
                'label' => $data['label'],
                'visibility' => $data['visibility'],
            ]);

            if ($file instanceof UploadedFile) {
                $document->versions()->where('is_current', true)->update(['is_current' => false]);
                $latestVersion = $document->versions()->max('version_number') ?? 0;
                $version = $this->createVersion($document, $file, $latestVersion + 1, $data['label'], $data['visibility']);
                $document->current_version_id = $version->id;
                $descriptionStr = "Mengunggah file master baru (versi v{$version->version_number})";
            }

            $changes = [];
            if ($oldSnapshot['name'] !== $data['name']) $changes[] = "judul menjadi '{$data['name']}'";
            if ($oldSnapshot['label'] !== $data['label']) $changes[] = "status publikasi menjadi " . ucfirst($data['label']);
            if ($oldSnapshot['visibility'] !== $data['visibility']) $changes[] = "visibilitas menjadi " . ucfirst($data['visibility']);
            if ($oldSnapshot['category_id'] != $data['category_id']) {
                $newCategoryName = DB::table('categories')->where('id', $data['category_id'])->value('name');
                $changes[] = "kategori menjadi '{$newCategoryName}'";
            }
            if ($oldSnapshot['description'] !== ($data['description'] ?? null)) $changes[] = "deskripsi";

            $oldTags = $oldSnapshot['tags'] ?? [];
            $newTags = collect($data['tags'] ?? [])->filter()->map(fn ($tag) => trim($tag))->unique()->values()->all();
            sort($oldTags); sort($newTags);
            if ($oldTags !== $newTags) $changes[] = "indexation keywords (tag)";

            if (count($changes) > 0) {
                $changeText = "mengubah " . implode(', ', $changes);
                $descriptionStr = $descriptionStr !== "" ? $descriptionStr . " serta " . $changeText : ucfirst($changeText);
            }

            if (empty($descriptionStr)) $descriptionStr = "Menyimpan ulang dokumen tanpa perubahan data spesifik";
            $descriptionStr .= ".";

            $this->syncTags($document, $data['tags'] ?? []);
            $document->save();
            $document->refresh();

            $this->logActivity($document, $file instanceof UploadedFile ? $version : $document->currentVersion, 'updated', $descriptionStr, $oldSnapshot, $this->snapshot($document));

            return $document->load(['currentVersion', 'tags', 'category']);
        });
    }

    public function deleteDocument(Document $document): bool
    {
        return DB::transaction(function () use ($document) {
            $document->deleted_by = auth()->id();
            $document->save();
            $this->logActivity($document, $document->currentVersion, 'deleted', 'Menghapus dan memindahkan dokumen ke dalam Trash.', $this->snapshot($document), null);
            return $document->delete();
        });
    }

    protected function createVersion(Document $document, UploadedFile $file, int $versionNumber, string $label, string $visibility): DocumentVersion
    {
        $filePath = Storage::disk('public')->putFile('documents', $file);
        return DocumentVersion::create([
            'document_id' => $document->id,
            'version_number' => $versionNumber,
            'file_path' => $filePath,
            'file_original_name' => $file->getClientOriginalName(),
            'file_size_bytes' => $file->getSize(),
            'file_mime_type' => $file->getClientMimeType() ?: $file->getMimeType(),
            'uploaded_by' => auth()->id(),
            'label_snapshot' => $label,
            'visibility_snapshot' => $visibility,
            'is_current' => true,
        ]);
    }

    protected function syncTags(Document $document, array $tags): void
    {
        $document->tags()->delete();
        collect($tags)->filter()->map(fn ($tag) => trim($tag))->filter()->unique()->each(fn ($tag) => DocumentTag::create([
            'document_id' => $document->id,
            'tag' => $tag,
            'created_by' => auth()->id(),
        ]));
    }

    protected function snapshot(Document $document): array
    {
        return [
            'name' => $document->name,
            'description' => $document->description,
            'category_id' => $document->category_id,
            'label' => $document->label,
            'visibility' => $document->visibility,
            'tags' => $document->tags->pluck('tag')->all(),
            'current_version_id' => $document->current_version_id,
        ];
    }

    protected function logActivity(Document $document, ?DocumentVersion $version, string $actionType, ?string $description, ?array $oldSnapshot, ?array $newSnapshot): void
    {
        DB::table('document_activity_log')->insert([
            'document_id' => $document->id,
            'document_version_id' => $version?->id,
            'actor_id' => auth()->id(),
            'action_type' => $actionType,
            'description' => $description,
            'old_snapshot' => $oldSnapshot ? json_encode($oldSnapshot) : null,
            'new_snapshot' => $newSnapshot ? json_encode($newSnapshot) : null,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'created_at' => now(),
        ]);
    }

    public function restoreDocument(Document $document): bool
    {
        return DB::transaction(function () use ($document) {
            $document->restore();
            $document->deleted_by = null;
            $document->save();

            DB::table('document_activity_log')->insert([
                'document_id' => $document->id,
                'document_version_id' => $document->current_version_id,
                'actor_id' => auth()->id(),
                'action_type' => 'restored',
                'description' => 'Mengembalikan dokumen dari Trash ke dalam repositori aktif.',
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
                'created_at' => now(),
            ]);
            return true;
        });
    }

    public function getSharesForDocument(int $documentId)
    {
        return DB::table('document_sharing')
            ->join('divisions', 'divisions.id', '=', 'document_sharing.shared_to_division_id')
            ->join('users', 'users.id', '=', 'document_sharing.shared_by')
            ->where('document_sharing.document_id', $documentId)
            ->select('document_sharing.*', 'divisions.name as division_name', 'users.full_name as sharer_name')
            ->get();
    }

    public function getDivisionsExcept(int $divisionId)
    {
        return DB::table('divisions')->where('id', '!=', $divisionId)->orderBy('name')->get();
    }

    public function getActivityLogsForDocument(int $documentId, int $limit = 10)
    {
        return DB::table('document_activity_log as log')
            ->join('users as actor', 'actor.id', '=', 'log.actor_id')
            ->where('log.document_id', $documentId)
            ->select('log.*', 'actor.email as actor_email')
            ->orderByDesc('log.created_at')
            ->limit($limit)
            ->get();
    }

    public function buildAccessibleDocumentsQuery(User $user, bool $isAdmin)
    {
        $query = $isAdmin
            ? Document::withTrashed()->with(['currentVersion', 'tags', 'category'])
            : Document::with(['currentVersion', 'tags', 'category']);

        if (!$isAdmin) {
            $query->where(function ($q) use ($user) {
                $q->where('visibility', 'public')
                    ->orWhere('division_id', $user->division_id)
                    ->orWhereExists(function ($sub) use ($user) {
                        $sub->select(DB::raw(1))
                            ->from('document_sharing')
                            ->whereColumn('document_sharing.document_id', 'documents.id')
                            ->where('document_sharing.shared_to_division_id', $user->division_id)
                            ->where('document_sharing.is_active', true) // FIX SECURITY: Pastikan data share aktif
                            ->where(function ($expiry) {
                                $expiry->whereNull('expires_at')->orWhere('expires_at', '>', now());
                            });
                    });
            });
        }
        return $query;
    }

    public function getShareById(int $id)
    {
        return DB::table('document_sharing')->where('id', $id)->first();
    }

    public function attachShare(Document $document, int $divisionId, string $permission, ?string $expiresAt, int $sharerId): void
    {
        DB::table('document_sharing')->updateOrInsert(
            [
                'document_id' => $document->id,
                'shared_to_division_id' => $divisionId,
            ],
            [
                'shared_by' => $sharerId,
                'permission' => $permission,
                'expires_at' => $expiresAt,
                'is_active' => true, // Memastikan record ter-set aktif kembali saat di-share ulang
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );

        $divName = DB::table('divisions')->where('id', $divisionId)->value('name');
        DB::table('document_activity_log')->insert([
            'document_id' => $document->id,
            'actor_id' => $sharerId,
            'action_type' => 'shared',
            'description' => "Memberikan akses " . strtoupper($permission) . " ke divisi {$divName}.",
            'created_at' => now()
        ]);

        $targetUsers = User::where('division_id', $divisionId)->get();
        if ($targetUsers->isNotEmpty()) {
            $permissionText = $permission === 'edit' ? 'hak akses Edit' : 'hak akses View Only';
            $sharerName = User::find($sharerId)->full_name ?? User::find($sharerId)->name ?? 'System';

            foreach ($targetUsers as $tUser) {
                DB::table('notifications')->insert([
                    'user_id'     => $tUser->id,
                    'type'        => 'shared',
                    'title'       => 'Akses Dokumen Baru',
                    'body'        => "{$sharerName} telah membagikan dokumen '{$document->name}' ke divisi Anda dengan {$permissionText}.",
                    'document_id' => $document->id,
                    'is_read'     => false,
                    'created_at'  => now(),
                    'updated_at'  => now(),
                ]);
            }
        }
    }

    public function revokeShareById(int $id, int $actorId): void
    {
        $share = DB::table('document_sharing')->where('id', $id)->first();
        if (!$share) return;

        $divName = DB::table('divisions')->where('id', $share->shared_to_division_id)->value('name');
        DB::table('document_activity_log')->insert([
            'document_id' => $share->document_id,
            'actor_id' => $actorId,
            'action_type' => 'revoked',
            'description' => "Mencabut seluruh akses dari divisi {$divName}.",
            'created_at' => now()
        ]);

        // Mengubah status menjadi tidak aktif alih-alih menghapus fisik jika arsitektur menghendaki flag is_active
        DB::table('document_sharing')->where('id', $id)->update(['is_active' => false, 'updated_at' => now()]);
    }
}