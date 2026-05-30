<?php
 
namespace Database\Seeders;
 
use App\Models\Discipline;
use Illuminate\Database\Seeder;
 
class DisciplinaSeeder extends Seeder
{
    public function run(): void
    {
        $disciplines = [
            'Fútbol',
            'Básquetbol',
            'Voleibol',
            'Béisbol',
            'Ajedrez',
            'Atletismo',
        ];
 
        foreach ($disciplines as $name) {
            Discipline::firstOrCreate(['name' => $name]);
        }
    }
}
