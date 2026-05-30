<?php
 
namespace App\Http\Controllers;
 
use App\Models\Discipline;
use App\Models\Game;
use App\Models\Team;
use Illuminate\Http\Request;
 
class PublicoController extends Controller
{
    public function index()
    {
        $teams = Team::with(['participants.disciplines'])->get();
        $games = Game::with(['homeTeam', 'awayTeam', 'discipline', 'referee'])->orderBy('match_date', 'desc')->get();
        $disciplines = Discipline::all();
 
        return view('public.index', compact('teams', 'games', 'disciplines'));
    }
 
    public function teams()
    {
        $teams = Team::withCount('participants')->orderBy('name')->get();
        return view('public.teams.index', compact('teams'));
    }
 
    public function teamDetail(Team $team)
    {
        $team->load(['participants.disciplines']);
        return view('public.teams.show', compact('team'));
    }
 
    public function games(Request $request)
    {
        $games = Game::with(['homeTeam', 'awayTeam', 'discipline', 'referee'])
            ->orderBy('match_date', 'desc')
            ->get();
        $disciplines = Discipline::orderBy('name')->get();
        $selectedDiscipline = null;
 
        return view('public.games.index', compact('games', 'disciplines', 'selectedDiscipline'));
    }
 
    public function gamesByDiscipline(Discipline $discipline)
    {
        $games = Game::where('discipline_id', $discipline->id)
            ->with(['homeTeam', 'awayTeam', 'discipline', 'referee'])
            ->orderBy('match_date', 'desc')
            ->get();
        $disciplines = Discipline::orderBy('name')->get();
        $selectedDiscipline = $discipline;
 
        return view('public.games.index', compact('games', 'disciplines', 'selectedDiscipline'));
    }
}
