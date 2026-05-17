<?php

namespace App\Services;

use App\Models\Document;
use App\Models\DocumentTag;
use App\Models\DocumentVersion;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Models\User;

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

            $this->logActivity(
                $document,
                $version,
                'created',
                'Mengunggah master file dan membuat record dokumen baru.',
                null,
                $this->snapshot($document)
            );

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

            $version = null;

            // 1. Cek jika ada unggahan file baru
            if ($file instanceof UploadedFile) {
                $document->versions()->where('is_current', true)->update(['is_current' => false]);

                $latestVersion = $document->versions()->max('version_number') ?? 0;
                $version = $this->createVersion(
                    $document,
                    $file,
                    $latestVersion + 1,
                    $data['label'],
                    $data['visibility']
                );

                $document->current_version_id = $version->id;
                $descriptionStr = "Mengunggah file master baru (versi v{$version->version_number})";
            }

            // 2. Deteksi Perubahan Spesifik pada Metadata
            $changes = [];
            
            if ($oldSnapshot['name'] !== $data['name']) {
                $changes[] = "judul menjadi '{$data['name']}'";
            }
            if ($oldSnapshot['label'] !== $data['label']) {
                $changes[] = "status publikasi menjadi " . ucfirst($data['label']);
            }
            if ($oldSnapshot['visibility'] !== $data['visibility']) {
                $changes[] = "visibilitas menjadi " . ucfirst($data['visibility']);
            }
            if ($oldSnapshot['category_id'] != $data['category_id']) {
                // AMBIL NAMA KATEGORI DARI DATABASE UNTUK LOG
                $newCategoryName = DB::table('categories')->where('id', $data['category_id'])->value('name');
                $changes[] = "kategori menjadi '{$newCategoryName}'";
            }
            if ($oldSnapshot['description'] !== ($data['description'] ?? null)) {
                $changes[] = "deskripsi";
            }

            // Komparasi Tag (Keywords)
            $oldTags = $oldSnapshot['tags'] ?? [];
            $newTags = collect($data['tags'] ?? [])->filter()->map(fn ($tag) => trim($tag))->unique()->values()->all();
            sort($oldTags);
            sort($newTags);
            if ($oldTags !== $newTags) {
                $changes[] = "indexation keywords (tag)";
            }

            // 3. Rangkai Kalimat Cerita
            if (count($changes) > 0) {
                $changeText = "mengubah " . implode(', ', $changes);
                if ($descriptionStr !== "") {
                    $descriptionStr .= " serta " . $changeText;
                } else {
                    $descriptionStr = ucfirst($changeText);
                }
            }

            // 4. Fallback jika user asal klik Save tanpa mengubah apapun
            if (empty($descriptionStr)) {
                $descriptionStr = "Menyimpan ulang dokumen tanpa perubahan data spesifik";
            }
            
            $descriptionStr .= ".";

            // Simpan perubahan ke database
            if (!empty($data['tags'])) {
                $this->syncTags($document, $data['tags']);
            } else {
                $this->syncTags($document, []);
            }

            $document->save();
            $document->refresh();

            // Eksekusi Log
            $this->logActivity(
                $document,
                $file instanceof UploadedFile ? $version : $document->currentVersion,
                'updated',
                $descriptionStr,
                $oldSnapshot,
                $this->snapshot($document)
            );

            return $document->load(['currentVersion', 'tags', 'category']);
        });
    }

    public function deleteDocument(Document $document): bool
    {
        return DB::transaction(function () use ($document) {
            $document->deleted_by = auth()->id();
            $document->save();

            $this->logActivity(
                $document,
                $document->currentVersion,
                'deleted',
                'Menghapus dan memindahkan dokumen ke dalam Trash.',
                $this->snapshot($document),
                null
            );

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

        collect($tags)
            ->filter()
            ->map(fn ($tag) => trim($tag))
            ->filter()
            ->unique()
            ->each(fn ($tag) => DocumentTag::create([
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

    public function share(User $user, Document $document): bool
    {
        // 1. Super Admin bebas membagikan
        if ($this->isAdmin($user)) {
            return true;
        }

        // 2. Anggota divisi pemilik dokumen (Divisi Asli) bebas membagikan
        if ($document->division_id === $user->division_id) {
            return true;
        }

        // 3. Divisi Tujuan (yang mendapat akses 'edit')
        // HANYA BISA SHARE LAGI JIKA DIA ADALAH MANAGER (Asumsi role_id Manager == 2)
        if ($this->hasActiveShare($user, $document, 'edit')) {
            return $user->role_id == 2; 
        }

        return false;
    }

    public function restoreDocument(Document $document): bool
    {
        return DB::transaction(function () use ($document) {
            // 1. Kembalikan dokumen secara native
            $document->restore();
            
            // 2. Bersihkan field penanda user penghapus
            $document->deleted_by = null;
            $document->save();

            // 3. Catat aktivitas ke Audit Logs
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
}