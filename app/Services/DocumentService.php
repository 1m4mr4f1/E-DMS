<?php

namespace App\Services;

use App\Models\Document;
use App\Models\DocumentTag;
use App\Models\DocumentVersion;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

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

            $document->fill([
                'name' => $data['name'],
                'description' => $data['description'] ?? null,
                'category_id' => $data['category_id'],
                'label' => $data['label'],
                'visibility' => $data['visibility'],
            ]);

            if ($file instanceof UploadedFile) {
                // Nonaktifkan is_current pada semua versi sebelumnya
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
            }

            if (!empty($data['tags'])) {
                $this->syncTags($document, $data['tags']);
            } else {
                $this->syncTags($document, []);
            }

            $document->save();
            $document->refresh();

            $this->logActivity(
                $document,
                $file instanceof UploadedFile ? $version : $document->currentVersion,
                'updated',
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
                $this->snapshot($document),
                null
            );

            return $document->delete();
        });
    }

    protected function createVersion(Document $document, UploadedFile $file, int $versionNumber, string $label, string $visibility): DocumentVersion
    {
        // Menyimpan file fisik dengan memaksanya ke disk 'public'
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

    protected function logActivity(Document $document, ?DocumentVersion $version, string $actionType, ?array $oldSnapshot, ?array $newSnapshot): void
    {
        DB::table('document_activity_log')->insert([
            'document_id' => $document->id,
            'document_version_id' => $version?->id,
            'actor_id' => auth()->id(),
            'action_type' => $actionType,
            'old_snapshot' => $oldSnapshot ? json_encode($oldSnapshot) : null,
            'new_snapshot' => $newSnapshot ? json_encode($newSnapshot) : null,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'created_at' => now(),
        ]);
    }
}