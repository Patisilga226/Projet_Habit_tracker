<?php

namespace App\Policies;

use App\Models\Habit;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class HabitPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return true; // Tous les utilisateurs authentifiés peuvent voir la liste des habitudes
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Habit $habit): bool
    {
        // Un utilisateur ne peut voir que ses propres habitudes
        return $user->id === $habit->user_id;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return true; // Tous les utilisateurs authentifiés peuvent créer des habitudes
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Habit $habit): bool
    {
        // Un utilisateur ne peut modifier que ses propres habitudes
        return $user->id === $habit->user_id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Habit $habit): bool
    {
        // Un utilisateur ne peut supprimer que ses propres habitudes
        return $user->id === $habit->user_id;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Habit $habit): bool
    {
        // Un utilisateur ne peut restaurer que ses propres habitudes
        return $user->id === $habit->user_id;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Habit $habit): bool
    {
        // Un utilisateur ne peut supprimer définitivement que ses propres habitudes
        return $user->id === $habit->user_id;
    }
}
