<?php
 
namespace App\Policies;
 
use App\Models\Team;
use App\Models\User;
 
class EquipoPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasAnyRole(['admin', 'secretaria', 'arbitro']);
    }
 
    public function view(User $user, Team $team): bool
    {
        return $user->hasAnyRole(['admin', 'secretaria', 'arbitro']);
    }
 
    public function create(User $user): bool
    {
        return $user->hasAnyRole(['admin', 'secretaria']);
    }
 
    public function update(User $user, Team $team): bool
    {
        return $user->hasAnyRole(['admin', 'secretaria']);
    }
 
    public function delete(User $user, Team $team): bool
    {
        return $user->hasAnyRole(['admin', 'secretaria']);
    }
}
