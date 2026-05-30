<?php
 
namespace App\Http\Controllers;
 
use App\Models\Discipline;
use App\Models\Game;
use Illuminate\Http\Request;
 
class JuegoController extends Controller
{
    public function index(Request $request)
    {
        $games = Game::with(['homeTeam', 'awayTeam', 'discipline', 'referee'])
            ->orderBy('match_date', 'desc')
            ->get();
        $disciplines = Discipline::orderBy('name')->get();
        $selectedDiscipline = null;
 
        return view('public.games.index', compact('games', 'disciplines', 'selectedDiscipline'));
    }
 
    public function create()
    {
        return view('games.create');
    }
 
    public function store(Request $request)
    {
        $request->validate([
            'discipline_id' => 'required|exists:disciplines,id',
            'home_team_id' => 'required|exists:teams,id|different:away_team_id',
            'away_team_id' => 'required|exists:teams,id|different:home_team_id',
            'referee_id' => 'nullable|exists:users,id',
            'home_team_score' => 'required|integer|min:0',
            'away_team_score' => 'required|integer|min:0',
            'status' => 'required|in:pending,in_progress,completed',
            'match_date' => 'required|date|after_or_equal:today',
        ], [
            'home_team_id.different' => 'El equipo local y el visitante no pueden ser el mismo.',
            'away_team_id.different' => 'El equipo local y el visitante no pueden ser el mismo.',
            'match_date.required' => 'La fecha y hora del encuentro son obligatorias.',
            'match_date.after_or_equal' => 'No está permitido agendar partidos en fechas pasadas.',
        ]);
 
        if ($request->referee_id) {
            $ref = \App\Models\User::find($request->referee_id);
            if (!$ref || !$ref->hasRole('arbitro')) {
                return back()->withErrors(['referee_id' => 'El usuario seleccionado debe tener el rol de Árbitro.'])->withInput();
            }
        }
 
        Game::create([
            'discipline_id' => $request->discipline_id,
            'home_team_id' => $request->home_team_id,
            'away_team_id' => $request->away_team_id,
            'referee_id' => $request->referee_id ?: null,
            'home_team_score' => $request->home_team_score,
            'away_team_score' => $request->away_team_score,
            'status' => $request->status,
            'match_date' => $request->match_date,
        ]);
 
        return redirect()->route('games.index')->with('success', 'Partido creado con éxito');
    }
 
    public function show(Game $game)
    {
        return view('games.show', compact('game'));
    }
 
    public function edit(Game $game)
    {
        return view('games.edit', compact('game'));
    }
 
    public function update(Request $request, Game $game)
    {
        $request->validate([
            'discipline_id' => 'required|exists:disciplines,id',
            'home_team_id' => 'required|exists:teams,id|different:away_team_id',
            'away_team_id' => 'required|exists:teams,id|different:home_team_id',
            'referee_id' => 'nullable|exists:users,id',
            'home_team_score' => 'required|integer|min:0',
            'away_team_score' => 'required|integer|min:0',
            'status' => 'required|in:pending,in_progress,completed',
            'match_date' => 'required|date|after_or_equal:today',
        ], [
            'home_team_id.different' => 'El equipo local y el visitante no pueden ser el mismo.',
            'away_team_id.different' => 'El equipo local y el visitante no pueden ser el mismo.',
            'match_date.required' => 'La fecha y hora del encuentro son obligatorias.',
            'match_date.after_or_equal' => 'No está permitido agendar partidos en fechas pasadas.',
        ]);
 
        if ($request->referee_id) {
            $ref = \App\Models\User::find($request->referee_id);
            if (!$ref || !$ref->hasRole('arbitro')) {
                return back()->withErrors(['referee_id' => 'El usuario seleccionado debe tener el rol de Árbitro.'])->withInput();
            }
        }
 
        $game->update([
            'discipline_id' => $request->discipline_id,
            'home_team_id' => $request->home_team_id,
            'away_team_id' => $request->away_team_id,
            'referee_id' => $request->referee_id ?: null,
            'home_team_score' => $request->home_team_score,
            'away_team_score' => $request->away_team_score,
            'status' => $request->status,
            'match_date' => $request->match_date,
        ]);
 
        return redirect()->route('games.index')->with('success', 'Partido actualizado con éxito');
    }
 
    public function destroy(Game $game)
    {
        $game->delete();
        return redirect()->route('games.index')->with('success', 'Partido eliminado');
    }
}
