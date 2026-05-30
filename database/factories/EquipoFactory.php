<?php
 
namespace Database\Factories;
 
use App\Models\Team;
use Illuminate\Database\Eloquent\Factories\Factory;
 
class EquipoFactory extends Factory
{
    protected $model = Team::class;
 
    public function definition(): array
    {
        return [
            'name' => 'Club ' . $this->faker->unique()->city(),
        ];
    }
}
