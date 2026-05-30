<?php
 
namespace Database\Seeders;
 
use App\Models\Discipline;
use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
 
class UserSeeder extends Seeder
{
    public function run(): void
    {
        $adminRole = Role::where('name', 'admin')->first();
        $secretariaRole = Role::where('name', 'secretaria')->first();
        $arbitroRole = Role::where('name', 'arbitro')->first();
 
        $admin = User::firstOrCreate(
            ['email' => 'admin@eventos.com'],
            [
                'name' => 'Admin Eventos',
                'password' => bcrypt('password'),
            ]
        );
        $admin->assignRole($adminRole);
 
        $secretaria = User::firstOrCreate(
            ['email' => 'secretaria@eventos.com'],
            [
                'name' => 'Secretaria Deporte',
                'password' => bcrypt('password'),
            ]
        );
        $secretaria->assignRole($secretariaRole);
 
        $disciplinesData = [
            'Fútbol' => 'arbitro.futbol@eventos.com',
            'Básquetbol' => 'arbitro.basquetbol@eventos.com',
            'Voleibol' => 'arbitro.voleibol@eventos.com',
            'Béisbol' => 'arbitro.beisbol@eventos.com',
            'Ajedrez' => 'arbitro.ajedrez@eventos.com',
            'Atletismo' => 'arbitro.atletismo@eventos.com',
        ];
 
        foreach ($disciplinesData as $disciplineName => $refereeEmail) {
            $discipline = Discipline::where('name', $disciplineName)->first();
            if ($discipline) {
                $referee = User::firstOrCreate(
                    ['email' => $refereeEmail],
                    [
                        'name' => 'Árbitro de ' . $disciplineName,
                        'password' => bcrypt('password'),
                        'discipline_id' => $discipline->id,
                    ]
                );
                $referee->assignRole($arbitroRole);
            }
        }
    }
}
