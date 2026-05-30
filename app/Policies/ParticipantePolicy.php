<?php
 
namespace App\Policies;
 
use App\Models\Participant;
use App\Models\User;
 
class ParticipantePolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasAnyRole(['admin', 'secretaria']);
    }
 
    public function view(User $user, Participant $participant): bool
    {
        return $user->hasAnyRole(['admin', 'secretaria']);
    }
 
    public function create(User $user): bool
    {
        return $user->hasAnyRole(['admin', 'secretaria']);
    }
 
    public function update(User $user, Participant $participant): bool
    {
        return $user->hasAnyRole(['admin', 'secretaria']);
    }
 
    public function delete(User $user, Participant $participant): bool
    {
        return $user->hasAnyRole(['admin', 'secretaria']);
    }
}
