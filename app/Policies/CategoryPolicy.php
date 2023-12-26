<?php

namespace App\Policies;

use App\Models\Category;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class CategoryPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        if($user->role=='ADMIN'||$user->role=='USER')
             return true;

        return false;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Category $category): bool
    {
        if($user->role=='ADMIN'||$user->role=='USER')
             return true;

        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        if($user->role=='ADMIN')
             return true;

        return false;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Category $category): bool
    {
        if($user->role=='ADMIN')
             return true;

        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Category $category): bool
    {
        if($user->role=='ADMIN')
         return true;

         return false;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Category $category): bool
    {
        if($user->role=='ADMIN')
            return true;

        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Category $category): bool
    {
        if($user->role=='ADMIN')
            return true;

        return false;
    }

    public function deleteAny(User $user): bool
    {
        if($user->role=='ADMIN')
             return true;

        return false;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restoreAny(User $user, Category $category): bool
    {
        if($user->role=='ADMIN')
             return true;

        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDeleteAny(User $user, Category $category): bool
    {
        if($user->role=='ADMIN')
             return true;

        return false;
    }
}
