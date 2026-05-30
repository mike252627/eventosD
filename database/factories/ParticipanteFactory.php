<?php
 
namespace Database\Factories;
 
use App\Models\Participant;
use App\Models\Team;
use Illuminate\Database\Eloquent\Factories\Factory;
 
class ParticipanteFactory extends Factory
{
    protected $model = Participant::class;
 
    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'team_id' => Team::factory(),
        ];
    }
}
