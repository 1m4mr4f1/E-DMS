<?php

namespace App\Policies;

use App\Models\Document;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class DocumentPolicy
{
    public function view(User $user, Document $document): bool
    {
        if ($this->isAdmin($user) || $document->visibility === 'public' || $document->division_id == $user->division_id) {
            return true;
        }
        return $this->hasActiveShare($user, $document);
    }

    public function create(User $user): bool
    {
        return $user !== null;
    }

    public function update(User $user, Document $document): bool
    {
        if (strtolower($document->label) === 'fix') {
            return false;
        }

        if ($this->isAdmin($user) || $document->division_id == $user->division_id) {
            return true;
        }
        return $this->hasActiveShare($user, $document, 'edit');
    }

    public function delete(User $user, Document $document): bool
    {
        if (strtolower($document->label) === 'fix') {
            return false;
        }

        if ($this->isAdmin($user) || $document->division_id == $user->division_id) {
            return true;
        }
        return $this->hasActiveShare($user, $document, 'edit');
    }

    public function share(User $user, Document $document): bool
    {
        // 1. Super Admin bebas membagikan
        if ($this->isAdmin($user)) {
            return true;
        }

        // 2. Divisi Asli bebas membagikan
        if ($document->division_id == $user->division_id) {
            return true;
        }

        // 3. Divisi Tujuan (Hanya jika punya akses 'edit' dan dia adalah MANAGER)
        if ($this->hasActiveShare($user, $document, 'edit')) {
            if ($this->isManager($user)) {
                return true;
            }
        }

        return false;
    }

    // --- HELPER UNTUK DETEKSI ROLE (KEBAL ERROR) ---

    protected function isAdmin(User $user): bool
    {
        // 1. Cek properti langsung
        if (isset($user->role_id) && $user->role_id == 1) return true;
        
        // 2. Cek Spatie
        if (method_exists($user, 'hasRole') && $user->hasRole(['admin', 'Administrator'])) return true;

        // 3. Tembak Database Langsung (Paling Akurat)
        $roleId = DB::table('model_has_roles')->where('model_id', $user->id)->value('role_id');
        return $roleId == 1;
    }

    protected function isManager(User $user): bool
    {
        // 1. Cek properti langsung
        if (isset($user->role_id) && $user->role_id == 2) return true;
        
        // 2. Cek Spatie
        if (method_exists($user, 'hasRole') && $user->hasRole(['manager', 'Manager'])) return true;

        // 3. Tembak Database Langsung (Paling Akurat)
        $roleId = DB::table('model_has_roles')->where('model_id', $user->id)->value('role_id');
        return $roleId == 2;
    }

    protected function hasActiveShare(User $user, Document $document, string $requiredPermission = null): bool
    {
        $query = DB::table('document_sharing')
            ->where('document_id', $document->id)
            ->where('shared_to_division_id', $user->division_id)
            ->where(function ($query) {
                $query->whereNull('expires_at')
                      ->orWhere('expires_at', '>', now());
            });

        if ($requiredPermission) {
            $query->where('permission', $requiredPermission);
        }

        return $query->exists();
    }
}