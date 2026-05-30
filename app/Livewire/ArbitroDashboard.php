<?php
 
namespace App\Livewire;
 
use App\Models\Game;
use Livewire\Component;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
 
class ArbitroDashboard extends Component
{
    use AuthorizesRequests;
 
    public $selected_game_id = null;
    public $home_score = 0;
    public $away_score = 0;
    public $game_status = 'pending';
 
    public function mount()
    {
        if (!auth()->user()->hasAnyRole(['admin', 'arbitro'])) {
            abort(403, 'No autorizado.');
        }
 
        if (auth()->user()->hasRole('arbitro') && !auth()->user()->discipline_id) {
            session()->flash('error', 'No tienes una disciplina asignada en el sistema. Contacta al Administrador.');
        }
    }
 
    public function editGame($gameId)
    {
        $game = Game::findOrFail($gameId);
 
        $this->authorize('update', $game);
 
        $this->selected_game_id = $game->id;
        $this->home_score = $game->home_team_score;
        $this->away_score = $game->away_team_score;
        $this->game_status = $game->status;
    }
 
    public function cancelEdit()
    {
        $this->reset(['selected_game_id', 'home_score', 'away_score', 'game_status']);
    }
 
    public function updateGame()
    {
        $game = Game::findOrFail($this->selected_game_id);
 
        $this->authorize('update', $game);
 
        $this->validate([
            'home_score' => 'required|integer|min:0|max:100',
            'away_score' => 'required|integer|min:0|max:100',
            'game_status' => 'required|in:pending,in_progress,completed',
        ], [
            'home_score.required' => 'El marcador del local es obligatorio.',
            'home_score.integer' => 'El marcador debe ser un número entero.',
            'away_score.required' => 'El marcador del visitante es obligatorio.',
            'away_score.integer' => 'El marcador debe ser un número entero.',
            'game_status.required' => 'El estado es obligatorio.',
        ]);
 
        try {
            $game->update([
                'home_team_score' => $this->home_score,
                'away_team_score' => $this->away_score,
                'status' => $this->game_status,
            ]);
 
            $this->cancelEdit();
            session()->flash('status', 'Marcador de partido actualizado con éxito.');
        } catch (\Exception $e) {
            session()->flash('error', 'Error al actualizar el marcador: ' . $e->getMessage());
        }
    }
 
    public function render()
    {
        $user = auth()->user();
        
        if ($user->hasRole('admin')) {
            $games = Game::with(['homeTeam', 'awayTeam', 'discipline', 'referee'])
                ->orderBy('match_date', 'desc')
                ->get();
            $disciplineName = 'Todas (Administrador)';
        } else {
            $games = Game::where('discipline_id', $user->discipline_id)
                ->with(['homeTeam', 'awayTeam', 'discipline'])
                ->orderBy('match_date', 'desc')
                ->get();
            $disciplineName = $user->discipline ? $user->discipline->name : 'Ninguna';
        }
 
        return view('livewire.panel-arbitro', compact('games', 'disciplineName'))
            ->layout('layouts.bootstrap');
    }
}
