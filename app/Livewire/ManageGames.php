<?php
 
namespace App\Livewire;
 
use App\Models\Discipline;
use App\Models\Game;
use App\Models\Team;
use App\Models\User;
use Livewire\Component;
 
class ManageGames extends Component
{
    public $discipline_id = '';
    public $home_team_id = '';
    public $away_team_id = '';
    public $referee_id = '';
    public $home_team_score = 0;
    public $away_team_score = 0;
    public $status = 'pending';
    public $match_date = '';
 
    public $selected_game_id = null;
    public $is_editing = false;
    public $is_creating = false;
 
    public function mount()
    {
        if (!auth()->user()->hasRole('admin')) {
            abort(403, 'No autorizado.');
        }
    }
 
    public function startCreate()
    {
        $this->resetForm();
        $this->is_creating = true;
        $this->is_editing = false;
    }
 
    public function startEdit($id)
    {
        $game = Game::findOrFail($id);
        $this->selected_game_id = $game->id;
        $this->discipline_id = $game->discipline_id;
        $this->home_team_id = $game->home_team_id;
        $this->away_team_id = $game->away_team_id;
        $this->referee_id = $game->referee_id ?? '';
        $this->home_team_score = $game->home_team_score;
        $this->away_team_score = $game->away_team_score;
        $this->status = $game->status;
        $this->match_date = $game->match_date ? \Carbon\Carbon::parse($game->match_date)->format('Y-m-d\TH:i') : '';
 
        $this->is_editing = true;
        $this->is_creating = false;
    }
 
    public function cancel()
    {
        $this->resetForm();
    }
 
    public function resetForm()
    {
        $this->reset([
            'discipline_id',
            'home_team_id',
            'away_team_id',
            'referee_id',
            'home_team_score',
            'away_team_score',
            'status',
            'match_date',
            'selected_game_id',
            'is_editing',
            'is_creating'
        ]);
    }
 
    public function createGame()
    {
        $this->validate([
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
            'match_date.after_or_equal' => 'No está permitido agendar partidos en fechas pasadas.'
        ]);
 
        if ($this->referee_id) {
            $ref = User::find($this->referee_id);
            if (!$ref || !$ref->hasRole('arbitro')) {
                session()->flash('error', 'El usuario seleccionado debe tener el rol de Árbitro.');
                return;
            }
        }
 
        Game::create([
            'discipline_id' => $this->discipline_id,
            'home_team_id' => $this->home_team_id,
            'away_team_id' => $this->away_team_id,
            'referee_id' => $this->referee_id ?: null,
            'home_team_score' => $this->home_team_score,
            'away_team_score' => $this->away_team_score,
            'status' => $this->status,
            'match_date' => $this->match_date,
        ]);
 
        $this->resetForm();
        session()->flash('status', 'Partido programado con éxito.');
    }
 
    public function updateGame()
    {
        $game = Game::findOrFail($this->selected_game_id);
 
        $this->validate([
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
            'match_date.after_or_equal' => 'No está permitido agendar partidos en fechas pasadas.'
        ]);
 
        if ($this->referee_id) {
            $ref = User::find($this->referee_id);
            if (!$ref || !$ref->hasRole('arbitro')) {
                session()->flash('error', 'El usuario seleccionado debe tener el rol de Árbitro.');
                return;
            }
        }
 
        $game->update([
            'discipline_id' => $this->discipline_id,
            'home_team_id' => $this->home_team_id,
            'away_team_id' => $this->away_team_id,
            'referee_id' => $this->referee_id ?: null,
            'home_team_score' => $this->home_team_score,
            'away_team_score' => $this->away_team_score,
            'status' => $this->status,
            'match_date' => $this->match_date,
        ]);
 
        $this->resetForm();
        session()->flash('status', 'Partido actualizado con éxito.');
    }
 
    public function deleteGame($id)
    {
        $game = Game::findOrFail($id);
        $game->delete();
        session()->flash('status', 'Partido eliminado con éxito.');
    }
 
    public function render()
    {
        $games = Game::with(['homeTeam', 'awayTeam', 'discipline', 'referee'])->orderBy('match_date', 'desc')->get();
        $teams = Team::orderBy('name')->get();
        $disciplines = Discipline::orderBy('name')->get();
        
        $referees = User::role('arbitro')->orderBy('name')->get();
 
        return view('livewire.manage-games', compact('games', 'teams', 'disciplines', 'referees'))
            ->layout('layouts.bootstrap');
    }
}
