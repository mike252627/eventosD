<?php
 
namespace App\Livewire;
 
use App\Models\Discipline;
use App\Models\User;
use Livewire\Component;
 
class ManageUsers extends Component
{
    public $name = '';
    public $email = '';
    public $password = '';
    public $role = '';
    public $discipline_id = '';
 
    public $selected_user_id = null;
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
        $user = User::findOrFail($id);
        $this->selected_user_id = $user->id;
        $this->name = $user->name;
        $this->email = $user->email;
        $this->role = $user->roles->first()?->name ?? '';
        $this->discipline_id = $user->discipline_id ?? '';
 
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
            'name',
            'email',
            'password',
            'role',
            'discipline_id',
            'selected_user_id',
            'is_editing',
            'is_creating'
        ]);
    }
 
    public function createUser()
    {
        $this->validate([
            'name' => 'required|string|min:3|max:100',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
            'role' => 'required|in:secretaria,arbitro',
            'discipline_id' => 'required_if:role,arbitro|nullable|exists:disciplines,id',
        ], [
            'name.required' => 'El nombre es obligatorio.',
            'email.required' => 'El correo electrónico es obligatorio.',
            'email.unique' => 'Este correo ya está registrado.',
            'password.required' => 'La contraseña es obligatoria.',
            'password.min' => 'La contraseña debe tener al menos 6 caracteres.',
            'role.required' => 'El rol es obligatorio.',
            'discipline_id.required_if' => 'Para un Árbitro es obligatorio asignar una disciplina.'
        ]);
 
        $user = User::create([
            'name' => $this->name,
            'email' => $this->email,
            'password' => bcrypt($this->password),
            'discipline_id' => $this->role === 'arbitro' ? ($this->discipline_id ?: null) : null,
        ]);
 
        $user->assignRole($this->role);
 
        $this->resetForm();
        session()->flash('status', 'Usuario creado con éxito.');
    }
 
    public function updateUser()
    {
        $user = User::findOrFail($this->selected_user_id);
 
        $this->validate([
            'name' => 'required|string|min:3|max:100',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:6',
            'role' => 'required|in:secretaria,arbitro',
            'discipline_id' => 'required_if:role,arbitro|nullable|exists:disciplines,id',
        ], [
            'name.required' => 'El nombre es obligatorio.',
            'email.required' => 'El correo electrónico es obligatorio.',
            'email.unique' => 'Este correo ya está registrado.',
            'password.min' => 'La contraseña debe tener al menos 6 caracteres.',
            'role.required' => 'El rol es obligatorio.',
            'discipline_id.required_if' => 'Para un Árbitro es obligatorio asignar una disciplina.'
        ]);
 
        $data = [
            'name' => $this->name,
            'email' => $this->email,
            'discipline_id' => $this->role === 'arbitro' ? ($this->discipline_id ?: null) : null,
        ];
 
        if (!empty($this->password)) {
            $data['password'] = bcrypt($this->password);
        }
 
        $user->update($data);
 
        $user->syncRoles([$this->role]);
 
        $this->resetForm();
        session()->flash('status', 'Usuario actualizado con éxito.');
    }
 
    public function deleteUser($id)
    {
        $user = User::findOrFail($id);
        if ($user->id === auth()->id()) {
            session()->flash('error', 'No puedes eliminarte a ti mismo.');
            return;
        }
        $user->delete();
        session()->flash('status', 'Usuario eliminado con éxito.');
    }
 
    public function render()
    {
        $users = User::role(['secretaria', 'arbitro'])
            ->with(['roles', 'discipline'])
            ->orderBy('name')
            ->get();
 
        $disciplines = Discipline::orderBy('name')->get();
 
        return view('livewire.manage-users', compact('users', 'disciplines'))
            ->layout('layouts.bootstrap');
    }
}
