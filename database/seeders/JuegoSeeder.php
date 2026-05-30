<?php
 
namespace Database\Seeders;
 
use App\Models\Discipline;
use App\Models\Game;
use App\Models\Team;
use App\Models\User;
use Illuminate\Database\Seeder;
 
class JuegoSeeder extends Seeder
{
    public function run(): void
    {
        $disciplines = Discipline::all();
        $teams = Team::all();
 
        if (Game::count() === 0) {
            foreach ($disciplines as $discipline) {
                $referee = User::where('discipline_id', $discipline->id)->first();
 
                $homeTeam = $teams->shuffle()->first();
                $awayTeam = $teams->reject(fn($t) => $t->id === $homeTeam->id)->shuffle()->first();
 
                Game::create([
                    'discipline_id' => $discipline->id,
                    'home_team_id' => $homeTeam->id,
                    'away_team_id' => $awayTeam->id,
                    'referee_id' => $referee ? $referee->id : null,
                    'home_team_score' => rand(1, 4),
                    'away_team_score' => rand(0, 3),
                    'status' => 'completed',
                    'match_date' => now()->subDays(rand(1, 10)),
                ]);
 
                $homeTeam2 = $teams->shuffle()->first();
                $awayTeam2 = $teams->reject(fn($t) => $t->id === $homeTeam2->id)->shuffle()->first();
 
                Game::create([
                    'discipline_id' => $discipline->id,
                    'home_team_id' => $homeTeam2->id,
                    'away_team_id' => $awayTeam2->id,
                    'referee_id' => $referee ? $referee->id : null,
                    'home_team_score' => 0,
                    'away_team_score' => 0,
                    'status' => 'pending',
                    'match_date' => now()->addDays(rand(1, 10)),
                ]);
            }
        }
    }
}
