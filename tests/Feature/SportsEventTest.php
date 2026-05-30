<?php
 
use App\Models\Discipline;
use App\Models\Participant;
use App\Models\Team;
use Illuminate\Database\QueryException;
 
test('public homepage returns a successful response and lists games and teams', function () {
    $team = Team::factory()->create(['name' => 'Club Halcones']);
    $disc = Discipline::factory()->create(['name' => 'Natación']);
    $part = Participant::factory()->create([
        'name' => 'Juan Perez',
        'team_id' => $team->id,
    ]);
    $part->disciplines()->attach($disc->id);
 
    $response = $this->get(route('home'));
 
    $response->assertStatus(200);
    $response->assertSee('Club Halcones');
    $response->assertSee('Natación');
 
    $detailsResponse = $this->get(route('teams.show', $team->id));
    $detailsResponse->assertStatus(200);
    $detailsResponse->assertSee('Juan Perez');
});
 
test('a participant cannot join more than 2 disciplines due to database trigger constraint', function () {
    $team = Team::factory()->create();
    $participant = Participant::factory()->create(['team_id' => $team->id]);
    
    $disc1 = Discipline::factory()->create(['name' => 'A']);
    $disc2 = Discipline::factory()->create(['name' => 'B']);
    $disc3 = Discipline::factory()->create(['name' => 'C']);
 
    $participant->disciplines()->attach($disc1->id);
    expect($participant->disciplines()->count())->toBe(1);
 
    $participant->disciplines()->attach($disc2->id);
    expect($participant->disciplines()->count())->toBe(2);
 
    try {
        $participant->disciplines()->attach($disc3->id);
        $this->fail("The database did not throw an exception when inserting a third discipline.");
    } catch (QueryException $e) {
        expect($e->getMessage())->toContain('Un participante no puede estar registrado en más de 2 disciplinas.');
    }
});
