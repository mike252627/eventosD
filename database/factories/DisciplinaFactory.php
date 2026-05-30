<?php
 
namespace Database\Factories;
 
use App\Models\Discipline;
use Illuminate\Database\Eloquent\Factories\Factory;
 
class DisciplinaFactory extends Factory
{
    protected $model = Discipline::class;
 
    public function definition(): array
    {
        return [
            'name' => $this->faker->unique()->randomElement([
                'Fútbol', 'Básquetbol', 'Voleibol', 'Béisbol', 'Ajedrez', 'Atletismo', 'Tenis', 'Natación'
            ]),
        ];
    }
}
