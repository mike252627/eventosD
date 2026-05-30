<?php
 
namespace Database\Factories;
 
use App\Models\Discipline;
use App\Models\Game;
use App\Models\Team;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
 
class JuegoFactory extends Factory
{
    protected $model = Game::class;
 
    public function definition(): array
    {
        $status = $this->faker->randomElement(['pending', 'in_progress', 'completed']);
        return [
            'discipline_id' => Discipline::factory(),
            'home_team_id' => Team::factory(),
            'away_team_id' => Team::factory(),
            'referee_id' => User::factory(),
            'home_team_score' => $status === 'completed' ? $this->faker->numberBetween(0, 5) : 0,
            'away_team_score' => $status === 'completed' ? $this->faker->numberBetween(0, 5) : 0,
            'status' => $status,
            'match_date' => $this->faker->dateTimeBetween('-1 month', '+1 month'),
        ];
    }
}
