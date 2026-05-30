<?php
 
namespace Database\Seeders;
 
use Illuminate\Database\Seeder;
 
class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            RoleAndPermissionSeeder::class,
            DisciplinaSeeder::class,
            UserSeeder::class,
            EquipoSeeder::class,
            JuegoSeeder::class,
        ]);
    }
}
