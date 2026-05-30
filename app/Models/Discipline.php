<?php
 
namespace App\Models;
 
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
 
#[Fillable(['name'])]
class Discipline extends Model
{
    use HasFactory;
 
    protected static function newFactory()
    {
        return \Database\Factories\DisciplinaFactory::new();
    }
 
    public function participants(): BelongsToMany
    {
        return $this->belongsToMany(Participant::class);
    }
 
    public function referees(): HasMany
    {
        return $this->hasMany(User::class, 'discipline_id');
    }
 
    public function games(): HasMany
    {
        return $this->hasMany(Game::class);
    }
}
