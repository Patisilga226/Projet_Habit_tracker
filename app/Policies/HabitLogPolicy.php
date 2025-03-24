<?php

namespace App\Policies;

use App\Models\HabitLog;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class HabitLogPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return true; // Tous les utilisateurs authentifiés peuvent voir la liste des logs
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, HabitLog $habitLog): bool
    {
        return $user->id === $habitLog->habit->user_id;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user, HabitLog $habitLog): bool
    {
        return $user->id === $habitLog->habit->user_id;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, HabitLog $habitLog): bool
    {
        return $user->id === $habitLog->habit->user_id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, HabitLog $habitLog): bool
    {
        return $user->id === $habitLog->habit->user_id;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, HabitLog $habitLog): bool
    {
        // Un utilisateur ne peut restaurer que les logs de ses propres habitudes
        return $user->id === $habitLog->habit->user_id;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, HabitLog $habitLog): bool
    {
        // Un utilisateur ne peut supprimer définitivement que les logs de ses propres habitudes
        return $user->id === $habitLog->habit->user_id;
    }
}
