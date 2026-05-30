<?php
 
namespace App\Http\Controllers;
 
use App\Models\Discipline;
use App\Models\Game;
use Illuminate\Http\Request;
 
class DisciplinaController extends Controller
{
    public function index()
    {
        $disciplines = Discipline::orderBy('name')->get();
        return view('disciplines.index', compact('disciplines'));
    }
 
    public function create()
    {
        return view('disciplines.create');
    }
 
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|max:255|unique:disciplines,name',
        ]);
 
        Discipline::create([
            'name' => $request->name,
        ]);
 
        return redirect()->route('disciplines.index')->with('success', 'Disciplina creada con éxito');
    }
 
    public function show(Discipline $discipline)
    {
        $games = Game::where('discipline_id', $discipline->id)
            ->with(['homeTeam', 'awayTeam', 'discipline', 'referee'])
            ->orderBy('match_date', 'desc')
            ->get();
        $disciplines = Discipline::orderBy('name')->get();
        $selectedDiscipline = $discipline;
 
        return view('public.games.index', compact('games', 'disciplines', 'selectedDiscipline'));
    }
 
    public function edit(Discipline $discipline)
    {
        return view('disciplines.edit', compact('discipline'));
    }
 
    public function update(Request $request, Discipline $discipline)
    {
        $request->validate([
            'name' => 'required|max:255|unique:disciplines,name,' . $discipline->id,
        ]);
 
        $discipline->update([
            'name' => $request->name,
        ]);
 
        return redirect()->route('disciplines.index')->with('success', 'Disciplina actualizada con éxito');
    }
 
    public function destroy(Discipline $discipline)
    {
        $discipline->delete();
        return redirect()->route('disciplines.index')->with('success', 'Disciplina eliminada');
    }
}
