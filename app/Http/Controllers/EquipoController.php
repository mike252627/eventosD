<?php
 
namespace App\Http\Controllers;
 
use App\Models\Team;
use Illuminate\Http\Request;
 
class EquipoController extends Controller
{
    public function index()
    {
        $teams = Team::withCount('participants')->orderBy('name')->get();
        return view('public.teams.index', compact('teams'));
    }
 
    public function create()
    {
        return view('teams.create');
    }
 
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|max:255|unique:teams,name',
            'user_id' => 'nullable|exists:users,id',
            'logo' => 'nullable|image|mimes:png|max:2048',
        ]);
 
        $team = new Team();
        $team->name = $request->name;
        $team->user_id = $request->user_id;
 
        if ($request->hasFile('logo')) {
            $file = $request->file('logo');
            $nombreLogo = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('assets/img/logos'), $nombreLogo);
            $team->logo = $nombreLogo;
        }
 
        $team->save();
 
        return redirect()->route('teams.index')->with('success', 'Equipo creado con éxito');
    }
 
    public function show(Team $team)
    {
        $team->load(['participants.disciplines']);
        return view('public.teams.show', compact('team'));
    }
 
    public function edit(Team $team)
    {
        return view('teams.edit', compact('team'));
    }
 
    public function update(Request $request, Team $team)
    {
        $request->validate([
            'name' => 'required|max:255|unique:teams,name,' . $team->id,
            'user_id' => 'nullable|exists:users,id',
            'logo' => 'nullable|image|mimes:png|max:2048',
        ]);
 
        $data = $request->all();
 
        if ($request->hasFile('logo')) {
            if ($team->logo && file_exists(public_path('assets/img/logos/' . $team->logo))) {
                unlink(public_path('assets/img/logos/' . $team->logo));
            }
 
            $file = $request->file('logo');
            $nombreLogo = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('assets/img/logos'), $nombreLogo);
            $data['logo'] = $nombreLogo;
        }
 
        $team->update($data);
 
        return redirect()->route('teams.index')->with('success', 'Equipo actualizado con éxito');
    }
 
    public function destroy(Team $team)
    {
        if ($team->logo && file_exists(public_path('assets/img/logos/' . $team->logo))) {
            unlink(public_path('assets/img/logos/' . $team->logo));
        }
 
        $team->delete();
 
        return redirect()->route('teams.index')->with('success', 'Equipo eliminado');
    }
}
