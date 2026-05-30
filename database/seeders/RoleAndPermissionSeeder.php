<?php
 
namespace Database\Seeders;
 
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;
 
class RoleAndPermissionSeeder extends Seeder
{
    public function run(): void
    {
        app()[PermissionRegistrar::class]->forgetCachedPermissions();
 
        Role::firstOrCreate(['name' => 'admin']);
        Role::firstOrCreate(['name' => 'secretaria']);
        Role::firstOrCreate(['name' => 'arbitro']);
    }
}
