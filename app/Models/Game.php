<?php
 
namespace App\Models;
 
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
 
#[Fillable([
    'discipline_id',
    'home_team_id',
    'away_team_id',
    'referee_id',
    'home_team_score',
    'away_team_score',
    'status',
    'match_date'
])]
class Game extends Model
{
    use HasFactory;
 
    protected static function newFactory()
    {
        return \Database\Factories\JuegoFactory::new();
    }
 
    public function discipline(): BelongsTo
    {
        return $this->belongsTo(Discipline::class);
    }
 
    public function homeTeam(): BelongsTo
    {
        return $this->belongsTo(Team::class, 'home_team_id');
    }
 
    public function awayTeam(): BelongsTo
    {
        return $this->belongsTo(Team::class, 'away_team_id');
    }
 
    public function referee(): BelongsTo
    {
        return $this->belongsTo(User::class, 'referee_id');
    }
}
