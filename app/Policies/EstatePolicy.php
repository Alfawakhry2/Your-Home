<?php

namespace App\Policies;

use Illuminate\Auth\Access\Response;
use App\Models\Estate;
use App\Models\User;

class EstatePolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Estate $estate): bool
    {
        return true;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool {
        return true ;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Estate $estate): bool
    {
        return $user->id === $estate->user_id || $user->role === 'admin';
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Estate $estate): bool
    {
        return $user->id === $estate->user_id || $user->role === 'admin';
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Estate $estate): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Estate $estate): bool
    {
        return false;
    }
}
