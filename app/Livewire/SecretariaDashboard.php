<?php
 
namespace App\Livewire;
 
use App\Models\Discipline;
use App\Models\Participant;
use App\Models\Team;
use Livewire\Component;
use Livewire\WithFileUploads;
 
class SecretariaDashboard extends Component
{
    use WithFileUploads;
 
    public $team_name = '';
    public $team_logo;
    public $current_team_logo = '';
    public $my_team = null;
 
    public $participant_name = '';
    public $participant_team_id = '';
    public $participant_photo;
 
    public $selected_participant_id = '';
    public $selected_discipline_ids = [];
 
    public function mount()
    {
        if (!auth()->user()->hasAnyRole(['admin', 'secretaria'])) {
            abort(403, 'No autorizado.');
        }
 
        $this->loadMyTeam();
    }
 
    public function loadMyTeam()
    {
        if (auth()->user()->hasRole('secretaria')) {
            $this->my_team = Team::where('user_id', auth()->id())->first();
            if ($this->my_team) {
                $this->team_name = $this->my_team->name;
                $this->current_team_logo = $this->my_team->logo;
                $this->participant_team_id = $this->my_team->id;
            }
        }
    }
 
    public function updatedSelectedParticipantId($value)
    {
        if ($value) {
            $participant = Participant::find($value);
            if ($participant) {
                $this->selected_discipline_ids = $participant->disciplines->pluck('id')->map(fn($id) => (string) $id)->toArray();
            } else {
                $this->selected_discipline_ids = [];
            }
        } else {
            $this->selected_discipline_ids = [];
        }
    }
 
    public function updatedTeamLogo()
    {
        try {
            $this->validateOnly('team_logo', [
                'team_logo' => 'nullable|image|mimes:png|max:2048',
            ], [
                'team_logo.image' => 'El logotipo debe ser una imagen.',
                'team_logo.mimes' => 'El logotipo del equipo debe estar en formato PNG (.png).',
                'team_logo.max' => 'El logotipo no debe pesar más de 2MB.',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            $this->reset('team_logo');
            throw $e;
        }
    }
 
    public function updatedParticipantPhoto()
    {
        try {
            $this->validateOnly('participant_photo', [
                'participant_photo' => 'nullable|image|mimes:png|max:2048',
            ], [
                'participant_photo.image' => 'La foto del participante debe ser una imagen.',
                'participant_photo.mimes' => 'La foto del participante debe estar en formato PNG (.png).',
                'participant_photo.max' => 'La foto no debe pesar más de 2MB.',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            $this->reset('participant_photo');
            throw $e;
        }
    }
 
    public function createTeam()
    {
        if (!auth()->user()->hasAnyRole(['admin', 'secretaria'])) {
            abort(403, 'No autorizado.');
        }
 
        if (auth()->user()->hasRole('secretaria') && $this->my_team) {
            session()->flash('error', 'Ya tienes un equipo registrado.');
            return;
        }
 
        $this->validate([
            'team_name' => 'required|string|min:3|max:100|unique:teams,name',
            'team_logo' => 'nullable|image|mimes:png|max:2048',
        ], [
            'team_name.required' => 'El nombre del equipo es obligatorio.',
            'team_name.unique' => 'Ya existe un equipo con ese nombre.',
            'team_name.min' => 'El nombre del equipo debe tener al menos 3 caracteres.',
            'team_logo.image' => 'El archivo debe ser una imagen.',
            'team_logo.mimes' => 'La imagen debe ser en formato PNG.',
            'team_logo.max' => 'La imagen no debe pesar más de 2MB.',
        ]);
 
        $logoPath = null;
        if ($this->team_logo) {
            $logoPath = $this->team_logo->store('logos', 'public');
        }
 
        $team = Team::create([
            'name' => $this->team_name,
            'user_id' => auth()->user()->hasRole('secretaria') ? auth()->id() : null,
            'logo' => $logoPath
        ]);
 
        $this->reset(['team_name', 'team_logo']);
        $this->loadMyTeam();
        session()->flash('status', 'Equipo registrado con éxito.');
    }
 
    public function updateTeam()
    {
        if (!auth()->user()->hasAnyRole(['admin', 'secretaria'])) {
            abort(403, 'No autorizado.');
        }
 
        $team = null;
        if (auth()->user()->hasRole('secretaria')) {
            $team = $this->my_team;
        }
 
        if (!$team) {
            session()->flash('error', 'No tienes un equipo asignado para editar.');
            return;
        }
 
        $this->validate([
            'team_name' => 'required|string|min:3|max:100|unique:teams,name,' . $team->id,
            'team_logo' => 'nullable|image|mimes:png|max:2048',
        ], [
            'team_name.required' => 'El nombre del equipo es obligatorio.',
            'team_name.unique' => 'Ya existe un equipo con ese nombre.',
            'team_name.min' => 'El nombre del equipo debe tener al menos 3 caracteres.',
            'team_logo.image' => 'El archivo debe ser una imagen.',
            'team_logo.mimes' => 'La imagen debe ser en formato PNG.',
            'team_logo.max' => 'La imagen no debe pesar más de 2MB.',
        ]);
 
        $data = ['name' => $this->team_name];
 
        if ($this->team_logo) {
            $data['logo'] = $this->team_logo->store('logos', 'public');
        }
 
        $team->update($data);
 
        $this->reset('team_logo');
        $this->loadMyTeam();
        session()->flash('status', 'Equipo actualizado con éxito.');
    }
 
    public function createParticipant()
    {
        if (!auth()->user()->hasAnyRole(['admin', 'secretaria'])) {
            abort(403, 'No autorizado.');
        }
 
        if (auth()->user()->hasRole('secretaria')) {
            if (!$this->my_team) {
                session()->flash('error', 'Debes registrar un equipo antes de agregar participantes.');
                return;
            }
            $this->participant_team_id = $this->my_team->id;
        }
 
        $this->validate([
            'participant_name' => 'required|string|min:3|max:100',
            'participant_team_id' => 'required|exists:teams,id',
            'participant_photo' => 'nullable|image|mimes:png|max:2048',
        ], [
            'participant_name.required' => 'El nombre del participante es obligatorio.',
            'participant_team_id.required' => 'Debe seleccionar un equipo.',
            'participant_photo.image' => 'El archivo debe ser una imagen.',
            'participant_photo.mimes' => 'La foto debe ser en formato PNG.',
            'participant_photo.max' => 'La foto no debe pesar más de 2MB.',
        ]);
 
        $photoPath = null;
        if ($this->participant_photo) {
            $photoPath = $this->participant_photo->store('photos', 'public');
        }
 
        Participant::create([
            'name' => $this->participant_name,
            'team_id' => $this->participant_team_id,
            'photo' => $photoPath,
        ]);
 
        $this->reset(['participant_name', 'participant_photo']);
        if (auth()->user()->hasRole('admin')) {
            $this->reset('participant_team_id');
        }
        session()->flash('status', 'Participante registrado con éxito.');
    }
 
    public function assignDisciplines()
    {
        if (!auth()->user()->hasAnyRole(['admin', 'secretaria'])) {
            abort(403, 'No autorizado.');
        }
 
        $this->validate([
            'selected_participant_id' => 'required|exists:participants,id',
            'selected_discipline_ids' => 'required|array|min:1|max:2',
        ], [
            'selected_participant_id.required' => 'Debe seleccionar un participante.',
            'selected_discipline_ids.required' => 'Debe seleccionar al menos una disciplina.',
            'selected_discipline_ids.min' => 'Debe seleccionar al menos una disciplina.',
            'selected_discipline_ids.max' => 'Un participante puede estar registrado en un máximo de 2 disciplinas.',
        ]);
 
        try {
            $participant = Participant::findOrFail($this->selected_participant_id);
            
            if (auth()->user()->hasRole('secretaria') && $participant->team_id !== $this->my_team->id) {
                abort(403, 'No autorizado.');
            }
 
            $participant->disciplines()->sync($this->selected_discipline_ids);
 
            session()->flash('status', 'Disciplinas asignadas con éxito.');
        } catch (\Exception $e) {
            session()->flash('error', 'Error al guardar la asignación: ' . $e->getMessage());
        }
    }
 
    public function render()
    {
        $user = auth()->user();
 
        if ($user->hasRole('admin')) {
            $teams = Team::orderBy('name')->get();
            $participants = Participant::with('team', 'disciplines')->orderBy('name')->get();
        } else {
            if ($this->my_team) {
                $teams = Team::where('id', $this->my_team->id)->get();
                $participants = Participant::where('team_id', $this->my_team->id)->with('team', 'disciplines')->orderBy('name')->get();
            } else {
                $teams = collect();
                $participants = collect();
            }
        }
 
        $disciplines = Discipline::orderBy('name')->get();
 
        return view('livewire.secretaria-dashboard', compact('teams', 'participants', 'disciplines'))
            ->layout('layouts.bootstrap');
    }
}
