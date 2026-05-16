<?php

namespace App\Policies;

use App\Models\Document;
use App\Models\User;

class DocumentPolicy
{
    public function view(User $user, Document $document): bool
    {
        return $this->isAdmin($user)
            || $document->visibility === 'public'
            || $document->division_id === $user->division_id;
    }

    public function create(User $user): bool
    {
        return $user !== null;
    }

    public function update(User $user, Document $document): bool
    {
        return $this->isAdmin($user)
            || $document->division_id === $user->division_id;
    }

    public function delete(User $user, Document $document): bool
    {
        return $this->isAdmin($user)
            || $document->division_id === $user->division_id;
    }

    protected function isAdmin(User $user): bool
    {
        return isset($user->role) && $user->role === 'admin'
            || method_exists($user, 'hasRole') && $user->hasRole('admin');
    }
}
