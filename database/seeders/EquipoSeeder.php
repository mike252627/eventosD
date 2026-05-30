<?php
 
namespace Database\Seeders;
 
use App\Models\Discipline;
use App\Models\Participant;
use App\Models\Team;
use Illuminate\Database\Seeder;
 
class EquipoSeeder extends Seeder
{
    public function run(): void
    {
        $teamNames = [
            'Club Águilas',
            'Deportivo Tiburones',
            'Leones F.C.',
            'Guerreros del Norte',
            'Halcones de Xalapa',
            'Astros de Jalisco'
        ];
 
        $disciplines = Discipline::all();
 
        foreach ($teamNames as $teamName) {
            $team = Team::firstOrCreate(['name' => $teamName]);
 
            if ($team->participants()->count() === 0) {
                for ($i = 1; $i <= 5; $i++) {
                    $participant = Participant::create([
                        'name' => 'Jugador ' . $i . ' (' . $team->name . ')',
                        'team_id' => $team->id,
                    ]);
 
                    $numDisciplines = rand(1, 2);
                    $shuffled = $disciplines->shuffle()->take($numDisciplines);
                    foreach ($shuffled as $disc) {
                        $participant->disciplines()->attach($disc->id);
                    }
                }
            }
        }
    }
}
