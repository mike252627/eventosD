<?php
 
use App\Http\Controllers\InicioController;
use App\Http\Controllers\EquipoController;
use App\Http\Controllers\JuegoController;
use App\Http\Controllers\DisciplinaController;
use App\Http\Controllers\ParticipanteController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
 
Route::get('/', InicioController::class)->name('home');
 
Route::resource('equipos', EquipoController::class)->parameters([
    'equipos' => 'team',
])->names([
    'index' => 'teams.index',
    'show' => 'teams.show',
]);
 
Route::resource('partidos', JuegoController::class)->parameters([
    'partidos' => 'game',
])->names([
    'index' => 'games.index',
    'show' => 'games.show',
]);
 
Route::get('/partidos/disciplina/{discipline}', [DisciplinaController::class, 'show'])->name('games.discipline');
 
Route::middleware(['auth', 'verified'])->group(function () {
    Route::view('dashboard', 'dashboard')->name('dashboard');
 
    Route::resource('disciplinas', DisciplinaController::class)->parameters([
        'disciplinas' => 'discipline',
    ])->names([
        'index' => 'disciplines.index',
        'show' => 'disciplines.show',
    ]);
    Route::resource('participantes', ParticipanteController::class)->parameters([
        'participantes' => 'participant',
    ]);
    Route::resource('usuarios', UserController::class)->parameters([
        'usuarios' => 'user',
    ]);
});
 
require __DIR__.'/settings.php';
