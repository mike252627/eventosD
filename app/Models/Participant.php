<?php
 
namespace App\Models;
 
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
 
#[Fillable(['name', 'team_id', 'photo'])]
class Participant extends Model
{
    use HasFactory;
 
    protected static function newFactory()
    {
        return \Database\Factories\ParticipanteFactory::new();
    }
 
    public function team(): BelongsTo
    {
        return $this->belongsTo(Team::class);
    }
 
    public function disciplines(): BelongsToMany
    {
        return $this->belongsToMany(Discipline::class);
    }
}
