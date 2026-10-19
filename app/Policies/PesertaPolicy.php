<?php

namespace App\Policies;

use App\Models\Peserta;
use App\Models\User;

class PesertaPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Peserta $peserta): bool
    {
        return true;
    }

    public function create(User $user): bool
    {
        return $user->isAdmin();
    }

    public function update(User $user, Peserta $peserta): bool
    {
        return $user->isAdmin();
    }

    public function delete(User $user, Peserta $peserta): bool
    {
        return $user->isAdmin();
    }

    public function restore(User $user, ?Peserta $peserta = null): bool
    {
        return $user->isAdmin();
    }

    public function forceDelete(User $user, ?Peserta $peserta = null): bool
    {
        return $user->isAdmin();
    }
}
