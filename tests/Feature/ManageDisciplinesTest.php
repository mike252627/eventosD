<?php

use App\Models\User;
use App\Models\Discipline;
use App\Livewire\ManageDisciplines;
use Spatie\Permission\Models\Role;
use Livewire\Livewire;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

beforeEach(function () {
    // Inicializar roles para los tests
    Role::firstOrCreate(['name' => 'admin']);
    Role::firstOrCreate(['name' => 'secretaria']);
});

test('non-admins cannot access disciplines management', function () {
    $user = User::factory()->create(); // Usuario común sin rol de admin

    $this->actingAs($user);

    Livewire::test(ManageDisciplines::class)
        ->assertStatus(403);
});

test('admins can access and create a discipline with an icon', function () {
    $admin = User::factory()->create();
    $admin->assignRole('admin');

    $this->actingAs($admin);

    Livewire::test(ManageDisciplines::class)
        ->assertSee('Control de Disciplinas')
        ->set('name', 'Tenis de Mesa')
        ->set('icon_type', 'icon')
        ->set('icon_class', 'bi-activity')
        ->call('createDiscipline')
        ->assertHasNoErrors();

    $disc = Discipline::where('name', 'Tenis de Mesa')->first();
    expect($disc)->not->toBeNull();
    expect($disc->icon_type)->toBe('icon');
    expect($disc->icon_class)->toBe('bi-activity');
    expect($disc->image_path)->toBeNull();
});

test('admins can create a discipline with an image', function () {
    Storage::fake('public');

    $admin = User::factory()->create();
    $admin->assignRole('admin');

    $this->actingAs($admin);

    // Usamos fake()->create con mimetype image/png en lugar de fake()->image para no depender de la extensión GD de PHP
    $file = UploadedFile::fake()->create('futbol.png', 50, 'image/png');

    Livewire::test(ManageDisciplines::class)
        ->set('name', 'Fútbol Rápido')
        ->set('icon_type', 'image')
        ->set('image', $file)
        ->call('createDiscipline')
        ->assertHasNoErrors();

    $disc = Discipline::where('name', 'Fútbol Rápido')->first();
    expect($disc)->not->toBeNull();
    expect($disc->icon_type)->toBe('image');
    expect($disc->image_path)->not->toBeNull();
    expect($disc->icon_class)->toBeNull();

    Storage::disk('public')->assertExists($disc->image_path);
});

test('admins cannot create duplicate disciplines', function () {
    $admin = User::factory()->create();
    $admin->assignRole('admin');

    Discipline::factory()->create(['name' => 'Fútbol']);

    $this->actingAs($admin);

    Livewire::test(ManageDisciplines::class)
        ->set('name', 'Fútbol')
        ->call('createDiscipline')
        ->assertHasErrors(['name' => 'unique']);
});

test('admins can update a discipline and change from icon to image', function () {
    Storage::fake('public');

    $admin = User::factory()->create();
    $admin->assignRole('admin');

    $discipline = Discipline::factory()->create([
        'name' => 'Fútbol Viejo',
        'icon_type' => 'icon',
        'icon_class' => 'bi-activity'
    ]);

    $this->actingAs($admin);

    $file = UploadedFile::fake()->create('new_futbol.png', 50, 'image/png');

    Livewire::test(ManageDisciplines::class)
        ->call('startEdit', $discipline->id)
        ->set('name', 'Fútbol Moderno')
        ->set('icon_type', 'image')
        ->set('image', $file)
        ->call('updateDiscipline')
        ->assertHasNoErrors();

    $fresh = $discipline->fresh();
    expect($fresh->name)->toBe('Fútbol Moderno');
    expect($fresh->icon_type)->toBe('image');
    expect($fresh->icon_class)->toBeNull();
    expect($fresh->image_path)->not->toBeNull();

    Storage::disk('public')->assertExists($fresh->image_path);
});

test('admins can delete a discipline and its stored image is deleted', function () {
    Storage::fake('public');

    $admin = User::factory()->create();
    $admin->assignRole('admin');

    // Crear un archivo ficticio y guardarlo
    $path = Storage::disk('public')->putFile('disciplines', UploadedFile::fake()->create('delete_me.png', 10, 'image/png'));

    $discipline = Discipline::factory()->create([
        'name' => 'Deporte Temporal',
        'icon_type' => 'image',
        'image_path' => $path
    ]);

    Storage::disk('public')->assertExists($path);

    $this->actingAs($admin);

    Livewire::test(ManageDisciplines::class)
        ->call('deleteDiscipline', $discipline->id)
        ->assertHasNoErrors();

    expect(Discipline::where('name', 'Deporte Temporal')->exists())->toBeFalse();
    Storage::disk('public')->assertMissing($path);
});

test('admins cannot upload non-PNG images', function () {
    Storage::fake('public');

    $admin = User::factory()->create();
    $admin->assignRole('admin');

    $this->actingAs($admin);

    $file = UploadedFile::fake()->create('futbol.jpg', 50, 'image/jpeg');

    Livewire::test(ManageDisciplines::class)
        ->set('name', 'Fútbol Con JPG')
        ->set('icon_type', 'image')
        ->set('image', $file)
        ->call('createDiscipline')
        ->assertHasErrors(['image' => 'mimes']);
});
