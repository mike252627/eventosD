<?php
 
namespace App\Policies;
 
use App\Models\Game;
use App\Models\User;
 
class JuegoPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasAnyRole(['admin', 'secretaria', 'arbitro']);
    }
 
    public function view(User $user, Game $game): bool
    {
        return $user->hasAnyRole(['admin', 'secretaria', 'arbitro']);
    }
 
    public function create(User $user): bool
    {
        return $user->hasRole('admin');
    }
 
    public function update(User $user, Game $game): bool
    {
        if ($user->hasRole('admin')) {
            return true;
        }
 
        if ($user->hasRole('arbitro')) {
            return $user->discipline_id === $game->discipline_id;
        }
 
        return false;
    }
 
    public function delete(User $user, Game $game): bool
    {
        return $user->hasRole('admin');
    }
}
