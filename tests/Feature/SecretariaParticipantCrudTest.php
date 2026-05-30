<?php

use App\Models\User;
use App\Models\Team;
use App\Models\Participant;
use App\Livewire\SecretariaDashboard;
use Spatie\Permission\Models\Role;
use Livewire\Livewire;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

beforeEach(function () {
    // Inicializar roles
    Role::firstOrCreate(['name' => 'admin']);
    Role::firstOrCreate(['name' => 'secretaria']);
});

test('guest cannot access secretaria dashboard', function () {
    Livewire::test(SecretariaDashboard::class)
        ->assertStatus(403);
});

test('secretaria can edit participant of her own team', function () {
    $secUser = User::factory()->create();
    $secUser->assignRole('secretaria');

    $team = Team::factory()->create(['user_id' => $secUser->id]);
    $participant = Participant::factory()->create([
        'name' => 'Juan Garcia',
        'team_id' => $team->id
    ]);

    $this->actingAs($secUser);

    Livewire::test(SecretariaDashboard::class)
        ->call('startEditParticipant', $participant->id)
        ->assertSet('participant_name', 'Juan Garcia')
        ->set('participant_name', 'Juan Garcia Lopez')
        ->call('updateParticipant')
        ->assertHasNoErrors();

    expect($participant->fresh()->name)->toBe('Juan Garcia Lopez');
});

test('secretaria cannot edit participant of another team', function () {
    $secUser1 = User::factory()->create();
    $secUser1->assignRole('secretaria');
    $team1 = Team::factory()->create(['user_id' => $secUser1->id]);

    $secUser2 = User::factory()->create();
    $secUser2->assignRole('secretaria');
    $team2 = Team::factory()->create(['user_id' => $secUser2->id]);

    $participantOfTeam2 = Participant::factory()->create([
        'name' => 'Pedro Solis',
        'team_id' => $team2->id
    ]);

    // intentamos actuar como secretaria 1 sobre participante de equipo 2
    $this->actingAs($secUser1);

    Livewire::test(SecretariaDashboard::class)
        ->call('startEditParticipant', $participantOfTeam2->id)
        ->assertStatus(403);
});

test('secretaria can delete participant of her own team', function () {
    Storage::fake('public');

    $secUser = User::factory()->create();
    $secUser->assignRole('secretaria');

    $team = Team::factory()->create(['user_id' => $secUser->id]);
    
    $photoPath = Storage::disk('public')->putFile('photos', UploadedFile::fake()->create('juan.png', 10, 'image/png'));
    $participant = Participant::factory()->create([
        'name' => 'Juan Garcia',
        'team_id' => $team->id,
        'photo' => $photoPath
    ]);

    Storage::disk('public')->assertExists($photoPath);

    $this->actingAs($secUser);

    Livewire::test(SecretariaDashboard::class)
        ->call('deleteParticipant', $participant->id)
        ->assertHasNoErrors();

    expect(Participant::where('id', $participant->id)->exists())->toBeFalse();
    Storage::disk('public')->assertMissing($photoPath);
});

test('secretaria cannot delete participant of another team', function () {
    $secUser1 = User::factory()->create();
    $secUser1->assignRole('secretaria');
    $team1 = Team::factory()->create(['user_id' => $secUser1->id]);

    $secUser2 = User::factory()->create();
    $secUser2->assignRole('secretaria');
    $team2 = Team::factory()->create(['user_id' => $secUser2->id]);

    $participantOfTeam2 = Participant::factory()->create([
        'name' => 'Pedro Solis',
        'team_id' => $team2->id
    ]);

    $this->actingAs($secUser1);

    Livewire::test(SecretariaDashboard::class)
        ->call('deleteParticipant', $participantOfTeam2->id)
        ->assertStatus(403);

    expect(Participant::where('id', $participantOfTeam2->id)->exists())->toBeTrue();
});

test('admin can edit and delete any participant', function () {
    $admin = User::factory()->create();
    $admin->assignRole('admin');

    $team = Team::factory()->create();
    $participant = Participant::factory()->create([
        'name' => 'Juan Garcia',
        'team_id' => $team->id
    ]);

    $this->actingAs($admin);

    Livewire::test(SecretariaDashboard::class)
        ->call('startEditParticipant', $participant->id)
        ->assertSet('participant_name', 'Juan Garcia')
        ->set('participant_name', 'Juan Admin Edit')
        ->call('updateParticipant')
        ->assertHasNoErrors()
        ->call('deleteParticipant', $participant->id)
        ->assertHasNoErrors();

    expect(Participant::where('id', $participant->id)->exists())->toBeFalse();
});
