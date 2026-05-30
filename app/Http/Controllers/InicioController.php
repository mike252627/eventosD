<?php
 
namespace App\Http\Controllers;
 
use App\Models\Discipline;
use App\Models\Game;
use App\Models\Team;
 
class InicioController extends Controller
{
    public function __invoke()
    {
        $teams = Team::with(['participants.disciplines'])->get();
        $games = Game::with(['homeTeam', 'awayTeam', 'discipline', 'referee'])->orderBy('match_date', 'desc')->get();
        $disciplines = Discipline::all();
 
        return view('public.index', compact('teams', 'games', 'disciplines'));
    }
}
