<?php

namespace App\Livewire;

use App\Models\Discipline;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;

class ManageDisciplines extends Component
{
    use WithFileUploads;

    public $name = '';
    public $icon_type = 'icon'; // 'icon' o 'image'
    public $icon_class = 'bi-trophy-fill'; // icono por defecto
    public $image; // para subida de archivos
    public $existing_image_path = null;

    public $selected_discipline_id = null;
    public $is_editing = false;
    public $is_creating = false;

    // Lista de iconos comunes recomendados para deportes
    public $recommended_icons = [
        'bi-trophy-fill' => 'Trofeo (General)',
        'bi-dribbble' => 'Balon (Deporte General)',
        'bi-flag-fill' => 'Meta/Bandera',
        'bi-activity' => 'Ritmo/Fitness',
        'bi-award-fill' => 'Medalla/Premio',
        'bi-lightning-fill' => 'Rayo/Velocidad',
        'bi-person-running' => 'Correr/Atletismo',
        'bi-bicycle' => 'Ciclismo',
        'bi-water' => 'Natación/Acuático',
        'bi-controller' => 'Mando/Ajedrez/E-sports',
        'bi-fire' => 'Fuego/Competición',
        'bi-star-fill' => 'Estrella/Destacado',
        'bi-heart-fill' => 'Corazón/Pasión',
    ];

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
        $discipline = Discipline::findOrFail($id);
        $this->selected_discipline_id = $discipline->id;
        $this->name = $discipline->name;
        $this->icon_type = $discipline->icon_type ?? 'icon';
        $this->icon_class = $discipline->icon_class ?? 'bi-trophy-fill';
        $this->existing_image_path = $discipline->image_path;
        $this->image = null;

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
            'icon_type',
            'icon_class',
            'image',
            'existing_image_path',
            'selected_discipline_id',
            'is_editing',
            'is_creating'
        ]);
        $this->icon_type = 'icon';
        $this->icon_class = 'bi-trophy-fill';
    }

    public function createDiscipline()
    {
        $rules = [
            'name' => 'required|string|min:3|max:100|unique:disciplines,name',
            'icon_type' => 'required|in:icon,image',
        ];

        $messages = [
            'name.required' => 'El nombre de la disciplina es obligatorio.',
            'name.min' => 'El nombre debe tener al menos 3 caracteres.',
            'name.max' => 'El nombre no puede exceder los 100 caracteres.',
            'name.unique' => 'Esta disciplina ya se encuentra registrada.',
            'icon_type.required' => 'El tipo de distintivo es obligatorio.',
        ];

        if ($this->icon_type === 'icon') {
            $rules['icon_class'] = 'required|string';
            $messages['icon_class.required'] = 'Debes seleccionar o escribir una clase de icono de Bootstrap.';
        } else {
            $rules['image'] = 'required|image|mimes:png|max:1024';
            $messages['image.required'] = 'Debes subir una imagen para la disciplina.';
            $messages['image.image'] = 'El archivo debe ser una imagen válida.';
            $messages['image.mimes'] = 'La imagen debe estar en formato PNG.';
            $messages['image.max'] = 'La imagen no debe pesar más de 1MB.';
        }

        $this->validate($rules, $messages);

        $imagePath = null;
        if ($this->icon_type === 'image' && $this->image) {
            $imagePath = $this->image->store('disciplines', 'public');
        }

        Discipline::create([
            'name' => $this->name,
            'icon_type' => $this->icon_type,
            'icon_class' => $this->icon_type === 'icon' ? $this->icon_class : null,
            'image_path' => $imagePath,
        ]);

        $this->resetForm();
        session()->flash('status', 'Disciplina creada con éxito.');
    }

    public function updateDiscipline()
    {
        $discipline = Discipline::findOrFail($this->selected_discipline_id);

        $rules = [
            'name' => 'required|string|min:3|max:100|unique:disciplines,name,' . $discipline->id,
            'icon_type' => 'required|in:icon,image',
        ];

        $messages = [
            'name.required' => 'El nombre de la disciplina es obligatorio.',
            'name.min' => 'El nombre debe tener al menos 3 caracteres.',
            'name.max' => 'El nombre no puede exceder los 100 caracteres.',
            'name.unique' => 'Esta disciplina ya se encuentra registrada.',
            'icon_type.required' => 'El tipo de distintivo es obligatorio.',
        ];

        if ($this->icon_type === 'icon') {
            $rules['icon_class'] = 'required|string';
            $messages['icon_class.required'] = 'Debes seleccionar o escribir una clase de icono de Bootstrap.';
        } else {
            if ($this->image) {
                $rules['image'] = 'image|mimes:png|max:1024';
                $messages['image.image'] = 'El archivo debe ser una imagen válida.';
                $messages['image.mimes'] = 'La imagen debe estar en formato PNG.';
                $messages['image.max'] = 'La imagen no debe pesar más de 1MB.';
            }
        }

        $this->validate($rules, $messages);

        $imagePath = $discipline->image_path;

        if ($this->icon_type === 'image') {
            if ($this->image) {
                // Eliminar imagen anterior si existía
                if ($discipline->image_path) {
                    Storage::disk('public')->delete($discipline->image_path);
                }
                $imagePath = $this->image->store('disciplines', 'public');
            }
            $iconClass = null;
        } else {
            // Es icono, eliminar imagen si existía
            if ($discipline->image_path) {
                Storage::disk('public')->delete($discipline->image_path);
                $imagePath = null;
            }
            $iconClass = $this->icon_class;
        }

        $discipline->update([
            'name' => $this->name,
            'icon_type' => $this->icon_type,
            'icon_class' => $iconClass,
            'image_path' => $imagePath,
        ]);

        $this->resetForm();
        session()->flash('status', 'Disciplina actualizada con éxito.');
    }

    public function deleteDiscipline($id)
    {
        $discipline = Discipline::findOrFail($id);

        if ($discipline->image_path) {
            Storage::disk('public')->delete($discipline->image_path);
        }

        $discipline->delete();

        session()->flash('status', 'Disciplina eliminada con éxito.');
    }

    public function render()
    {
        $disciplines = Discipline::withCount(['games', 'referees'])
            ->orderBy('name')
            ->get();

        return view('livewire.manage-disciplines', compact('disciplines'))
            ->layout('layouts.bootstrap');
    }
}
