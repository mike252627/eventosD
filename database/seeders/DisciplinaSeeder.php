<?php
 
namespace Database\Seeders;
 
use App\Models\Discipline;
use Illuminate\Database\Seeder;
 
class DisciplinaSeeder extends Seeder
{
    public function run(): void
    {
        $disciplines = [
            'Fútbol' => 'bi-dribbble',
            'Básquetbol' => 'bi-dribbble',
            'Voleibol' => 'bi-dribbble',
            'Béisbol' => 'bi-dribbble',
            'Ajedrez' => 'bi-grid-3x3-gap-fill',
            'Atletismo' => 'bi-lightning-charge-fill',
        ];
 
        foreach ($disciplines as $name => $icon) {
            Discipline::updateOrCreate(
                ['name' => $name],
                [
                    'icon_type' => 'icon',
                    'icon_class' => $icon,
                ]
            );
        }
    }
}
